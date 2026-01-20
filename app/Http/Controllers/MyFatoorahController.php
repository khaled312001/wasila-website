<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use App\Models\Order;
use App\Helpers\SettingsHelper;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreatedMail;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;
use Exception;

class MyFatoorahController extends Controller
{
    /**
     * @var array
     */
    public $mfConfig = [];

    /**
     * Initiate MyFatoorah Configuration
     */
    public function __construct() {
        $countryIso = config('myfatoorah.country_iso');
        
        // Convert country ISO codes to MyFatoorah vcCode format
        $vcCodeMap = [
            'SA' => 'SAU',  // Saudi Arabia
            'AE' => 'ARE',  // UAE
            'KW' => 'KWT',  // Kuwait
            'BH' => 'BHR',  // Bahrain
            'QA' => 'QAT',  // Qatar
            'OM' => 'OMN',  // Oman
            'JO' => 'JOR',  // Jordan
            'EG' => 'EGY',  // Egypt
        ];
        
        // If countryIso is already in vcCode format (e.g., 'SAU'), use it directly
        // Otherwise, convert it using the map
        $vcCode = $vcCodeMap[$countryIso] ?? $countryIso;
        
        // Get API key - prefer env over config
        $apiKey = env('MYFATOORAH_API_KEY');
        if (empty($apiKey)) {
            $apiKey = config('myfatoorah.api_key');
        }
        $apiKey = trim($apiKey ?? '');
        
        // Get test mode - prefer env over config
        $testMode = env('MYFATOORAH_TEST_MODE');
        if ($testMode === null) {
            $testMode = config('myfatoorah.test_mode');
        }
        // Ensure boolean value
        if (is_string($testMode)) {
            $testMode = strtolower($testMode);
            $testMode = !in_array($testMode, ['false', '0', 'no', 'off', '']);
        }
        $testMode = filter_var($testMode, FILTER_VALIDATE_BOOLEAN);
        
        $this->mfConfig = [
            'apiKey'      => $apiKey,
            'isTest'      => $testMode,
            'countryCode' => $vcCode, // Use vcCode format (SAU, KWT, etc.)
            'vcCode'      => $vcCode, // Also set vcCode for MyFatoorahPaymentEmbedded compatibility
        ];
    }

    /**
     * Redirect to MyFatoorah Invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return Response
     */
    public function index() {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 0;
            $sessionId = request('sid') ?: null;

            $orderId  = request('oid') ?: null;
            if (!$orderId) {
                throw new Exception('Order ID is required. Please provide the order ID in the URL parameter (oid).');
            }
            
            $curlData = $this->getPayLoadData($orderId);

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

            // Convert object to array if needed
            if (is_object($payment)) {
                $payment = json_decode(json_encode($payment), true);
            }

            return redirect($payment['invoiceURL']);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => 'false', 'Message' => $exMessage]);
        }
    }

    /**
     * Example on how to map order data to MyFatoorah
     * You can get the data using the order object in your system
     * 
     * @param int|string $orderId
     * 
     * @return array
     */
    private function getPayLoadData($orderId = null) {
        $callbackURL = route('myfatoorah.callback');

        //You can get the data using the order object in your system
        $order = Order::with('orderItems.service')->find($orderId);
        
        if (!$order) {
            throw new Exception('Order not found');
        }

        // Format phone number for MyFatoorah
        // MyFatoorah requires: MobileCountryCode without + (e.g., "966" not "+966")
        // and CustomerMobile should be digits only, without country code
        $countryCode = $order->country_code ?? '+966';
        $mobileCountryCode = ltrim($countryCode, '+'); // Remove + sign
        
        // Clean customer phone number: remove all non-digit characters and country code if present
        $customerPhone = $order->customer_phone ?? '';
        $originalPhone = $customerPhone;
        $customerPhone = $this->cleanPhoneNumber($customerPhone, $countryCode);
        
        // Validate phone number
        $isValidPhone = $this->isValidPhoneNumber($customerPhone, $countryCode);
        
        // Log phone number processing
        Log::info('MyFatoorah: Processing phone number', [
            'order_id' => $orderId,
            'original_phone' => $originalPhone,
            'cleaned_phone' => $customerPhone,
            'phone_length' => strlen($customerPhone),
            'is_valid' => $isValidPhone,
            'country_code' => $countryCode,
            'mobile_country_code' => $mobileCountryCode
        ]);
        
        // If customer phone is empty or invalid, use company phone as fallback
        if (!$isValidPhone || empty($customerPhone) || strlen($customerPhone) < 8) {
            $companyPhone = SettingsHelper::contactPhone();
            $customerPhone = $this->cleanPhoneNumber($companyPhone, '+966');
            $isValidPhone = $this->isValidPhoneNumber($customerPhone, '+966');
            
            // Log the fallback for debugging
            Log::info('MyFatoorah: Using company phone as fallback', [
                'order_id' => $orderId,
                'original_customer_phone' => $originalPhone,
                'company_phone_raw' => SettingsHelper::contactPhone(),
                'cleaned_company_phone' => $customerPhone,
                'is_valid' => $isValidPhone
            ]);
        }
        
        // Final validation: ensure phone is not empty and valid
        if (empty($customerPhone) || !$isValidPhone || strlen($customerPhone) < 8) {
            // Use a default valid Saudi phone number if all else fails
            $customerPhone = '559229980'; // Default company phone without country code (9 digits, starts with 5)
            Log::warning('MyFatoorah: Using hardcoded default phone number', [
                'order_id' => $orderId,
                'original_phone' => $originalPhone,
                'final_phone' => $customerPhone
            ]);
        }
        
        // Final check: ensure phone length is correct (8-9 digits for Saudi)
        if (strlen($customerPhone) < 8) {
            // Pad with leading 5 if too short (Saudi mobile format)
            $customerPhone = '5' . str_pad($customerPhone, 8, '0', STR_PAD_LEFT);
            Log::warning('MyFatoorah: Phone number was too short, padded it', [
                'order_id' => $orderId,
                'final_phone' => $customerPhone
            ]);
        }
        
        // Log final phone number being sent
        Log::info('MyFatoorah: Final phone number to send', [
            'order_id' => $orderId,
            'customer_mobile' => $customerPhone,
            'mobile_country_code' => $mobileCountryCode,
            'phone_length' => strlen($customerPhone)
        ]);

        return [
            'CustomerName'       => $order->customer_name,
            'InvoiceValue'       => $order->total_amount,
            'DisplayCurrencyIso' => 'SAR',
            'CustomerEmail'      => $order->customer_email,
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => $mobileCountryCode,
            'CustomerMobile'     => $customerPhone,
            'Language'           => app()->getLocale() === 'ar' ? 'ar' : 'en',
            'CustomerReference'  => $order->order_number,
            'UserDefinedField'   => $order->id,
            'CustomerAddress'    => [
                'Address' => $order->customer_address,
                'City' => 'الرياض',
                'Country' => 'SA'
            ],
            'InvoiceItems'       => $order->orderItems->map(function ($item) {
                return [
                    'ItemName' => $item->service->name_ar ?? $item->service->name_en ?? 'Item',
                    'Quantity' => $item->quantity,
                    'UnitPrice' => $item->unit_price,
                    'Weight' => 0,
                    'Width' => 0,
                    'Height' => 0,
                    'Depth' => 0
                ];
            })->toArray(),
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . (defined('MYFATOORAH_LARAVEL_PACKAGE_VERSION') ? MYFATOORAH_LARAVEL_PACKAGE_VERSION : '2.2')
        ];
    }

    /**
     * Get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     * 
     * @return Response
     */
    public function callback() {
        try {
            $paymentId = request('paymentId');

            if (!$paymentId) {
                Log::warning('MyFatoorah callback: No paymentId provided', [
                    'request_data' => request()->all()
                ]);
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الدفع.');
            }

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            
            try {
                $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
            } catch (\Exception $e) {
                Log::error('MyFatoorah callback: Failed to get payment status', [
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('home')
                    ->with('error', 'حدث خطأ في التحقق من حالة الدفع. يرجى التواصل معنا مع رقم الطلب.');
            }

            // Convert object to array if needed
            if (is_object($data)) {
                $data = json_decode(json_encode($data), true);
            }

            // Check if data is valid
            if (empty($data) || !isset($data['InvoiceStatus'])) {
                Log::error('MyFatoorah callback: Invalid payment status data', [
                    'payment_id' => $paymentId,
                    'data' => $data
                ]);
                return redirect()->route('home')
                    ->with('error', 'حدث خطأ في معالجة حالة الدفع. يرجى التواصل معنا.');
            }

            // Log payment status for debugging
            Log::info('MyFatoorah callback: Payment status received', [
                'payment_id' => $paymentId,
                'invoice_status' => $data['InvoiceStatus'] ?? 'Unknown',
                'order_id' => $data['UserDefinedField'] ?? null
            ]);

            $message = $this->getTestMessage($data['InvoiceStatus'] ?? 'Unknown', $data['InvoiceError'] ?? '');

            // Update order status if payment is successful
            if (isset($data['UserDefinedField']) && $data['UserDefinedField']) {
                $orderId = $data['UserDefinedField'];
                $order = Order::with('orderItems.service')->find($orderId);
                
                if ($order) {
                    $invoiceStatus = $data['InvoiceStatus'] ?? 'Unknown';
                    
                    if (strtolower($invoiceStatus) === 'paid') {
                        $order->update([
                            'payment_status' => 'paid',
                            'payment_method' => $data['PaymentMethod'] ?? 'MyFatoorah',
                            'payment_reference' => $paymentId,
                            'status' => 'confirmed'
                        ]);
                        
                        // Send email to admin
                        try {
                            $adminEmail = SettingsHelper::contactEmail();
                            Mail::to($adminEmail)->send(new OrderCreatedMail($order->fresh()->load('orderItems.service')));
                        } catch (\Exception $e) {
                            Log::error('Failed to send order email: ' . $e->getMessage());
                        }
                        
                        // Store order data in session for confirmation page
                        $orderData = [
                            'order_number' => $order->order_number,
                            'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                            'service_price' => $order->orderItems->first()->unit_price ?? 0,
                            'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                            'customer_name' => $order->customer_name,
                            'customer_email' => $order->customer_email,
                            'customer_phone' => $order->customer_phone,
                            'customer_address' => $order->customer_address,
                            'total_amount' => $order->total_amount,
                            'payment_status' => 'paid',
                            'payment_method' => $order->payment_method
                        ];
                        
                        request()->session()->put('order_confirmation', $orderData);
                        
                        // Redirect to confirmation page
                        $locale = app()->getLocale();
                        $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                        return redirect($confirmationUrl)
                            ->with('success', 'تم الدفع بنجاح! شكراً لك على دعمك لمشروع وسيلة الخيري.');
                    } elseif (strtolower($invoiceStatus) === 'failed') {
                        $order->update([
                            'payment_status' => 'failed',
                            'payment_reference' => $paymentId
                        ]);
                        
                        // Store order data in session
                        $orderData = [
                            'order_number' => $order->order_number,
                            'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                            'service_price' => $order->orderItems->first()->unit_price ?? 0,
                            'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                            'customer_name' => $order->customer_name,
                            'customer_email' => $order->customer_email,
                            'customer_phone' => $order->customer_phone,
                            'customer_address' => $order->customer_address,
                            'total_amount' => $order->total_amount,
                            'payment_status' => 'failed'
                        ];
                        
                        request()->session()->put('order_confirmation', $orderData);
                        
                        // Redirect to confirmation page
                        $locale = app()->getLocale();
                        $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                        return redirect($confirmationUrl)
                            ->with('error', 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى أو التواصل معنا.');
                    }
                }
            }

            $response = ['IsSuccess' => true, 'Message' => $message, 'Data' => $data];
            return response()->json($response);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            if ($exMessage === 'myfatoorah.' . $ex->getMessage()) {
                $exMessage = $ex->getMessage();
            }
            return redirect()->route('home')
                ->with('error', 'حدث خطأ في معالجة الدفع: ' . $exMessage);
        }
    }

    /**
     * Execute payment using sessionId from embedded payment
     */
    public function executePayment(Request $request) {
        try {
            $sessionId = $request->input('sessionId');
            $orderId = $request->input('orderId');
            
            Log::info('MyFatoorah executePayment: Starting', [
                'session_id' => $sessionId,
                'order_id' => $orderId
            ]);
            
            if (!$sessionId || !$orderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session ID and Order ID are required'
                ], 400);
            }
            
            $order = Order::with('orderItems.service')->find($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            
            // Prepare invoice data
            $curlData = $this->getPayLoadData($orderId);
            
            Log::info('MyFatoorah executePayment: Invoice data prepared', [
                'order_id' => $orderId,
                'total_amount' => $order->total_amount
            ]);
            
            // Use sessionId to create invoice and get paymentId
            // According to MyFatoorah docs, when we have sessionId from embedded payment,
            // we need to use it to create invoice and execute payment
            // The sessionId is used to link the payment to the embedded session
            try {
                $mfObj = new MyFatoorahPayment($this->mfConfig);
                
                // According to MyFatoorah PHP library docs: getInvoiceURL($postFields, $paymentMethodId)
                // For embedded payment with sessionId, we need to include sessionId in the postFields
                // Add sessionId to the payload data
                if ($sessionId) {
                    $curlData['SessionId'] = $sessionId;
                }
                
                // $paymentMethodId = 0 means redirect to MyFatoorah invoice page
                $payment = $mfObj->getInvoiceURL($curlData, 0);
                
                // Convert object to array if needed (MyFatoorah may return stdClass objects)
                if (is_object($payment)) {
                    $payment = json_decode(json_encode($payment), true);
                }
                
                // Check if response contains an error
                if (isset($payment['IsSuccess']) && $payment['IsSuccess'] === false) {
                    $errorMessage = $payment['Message'] ?? $payment['message'] ?? 'Unknown error from MyFatoorah';
                    Log::error('MyFatoorah executePayment: Invoice creation failed', [
                        'error_message' => $errorMessage,
                        'payment_response' => $payment,
                        'session_id' => $sessionId,
                        'order_id' => $orderId
                    ]);
                    
                    throw new \Exception($errorMessage);
                }
                
                // Check if response is null or empty
                if (empty($payment)) {
                    Log::error('MyFatoorah executePayment: Empty payment response', [
                        'session_id' => $sessionId,
                        'order_id' => $orderId,
                        'curl_data' => $curlData
                    ]);
                    throw new \Exception('Empty response from MyFatoorah');
                }
                
                Log::info('MyFatoorah executePayment: Invoice created', [
                    'payment_response' => $payment,
                    'payment_keys' => is_array($payment) ? array_keys($payment) : 'not_array',
                    'payment_type' => gettype($payment),
                    'has_invoice_id' => isset($payment['InvoiceId']),
                    'has_invoice_url' => isset($payment['invoiceURL']),
                    'invoice_url_value' => $payment['invoiceURL'] ?? $payment['InvoiceURL'] ?? $payment['invoice_url'] ?? 'NOT_FOUND',
                    'all_url_keys' => is_array($payment) ? array_filter(array_keys($payment), function($key) {
                        return stripos($key, 'url') !== false || stripos($key, 'invoice') !== false;
                    }) : []
                ]);
            } catch (\Exception $e) {
                Log::error('MyFatoorah executePayment: Error creating invoice', [
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'session_id' => $sessionId,
                    'order_id' => $orderId,
                    'curl_data_keys' => is_array($curlData) ? array_keys($curlData) : 'not_array'
                ]);
                
                $locale = app()->getLocale();
                $errorMessage = $locale === 'ar' 
                    ? 'حدث خطأ في إنشاء فاتورة الدفع. يرجى المحاولة مرة أخرى.' 
                    : 'Error creating payment invoice. Please try again.';
                    
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => true,
                    'error_type' => 'invoice_creation_error',
                    'retry' => true
                ], 500);
            }
            
            // Extract paymentId/InvoiceId from response
            $paymentId = $payment['InvoiceId'] ?? $payment['paymentId'] ?? $payment['PaymentId'] ?? null;
            
            // Check if invoiceURL exists - this is needed for OTP/3D Secure authentication
            $invoiceURL = $payment['invoiceURL'] ?? $payment['InvoiceURL'] ?? $payment['invoice_url'] ?? null;
            
            // If invoiceURL is not in response but we have InvoiceId, build the URL manually
            if (!$invoiceURL && $paymentId) {
                $isTest = $this->mfConfig['isTest'];
                $vcCode = $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU';
                $countries = MyFatoorah::getMFCountries();
                
                if (isset($countries[$vcCode])) {
                    $portalBase = $isTest ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];
                    $invoiceURL = rtrim($portalBase, '/') . '/pay/' . $paymentId;
                } else {
                    // Fallback: use default portal URL
                    if ($isTest) {
                        $invoiceURL = 'https://test.myfatoorah.com/pay/' . $paymentId;
                    } else {
                        $invoiceURL = 'https://portal.myfatoorah.com/pay/' . $paymentId;
                    }
                }
            }
            
            // If we have invoiceURL, redirect user to it for OTP/3D Secure
            if ($invoiceURL && !empty(trim($invoiceURL))) {
                Log::info('MyFatoorah executePayment: Invoice URL found/built, redirecting user for OTP/3D Secure', [
                    'invoice_url' => $invoiceURL,
                    'invoice_id' => $paymentId
                ]);
                
                // If we have paymentId, save it for tracking
                if ($paymentId) {
                    $order->update([
                        'payment_reference' => $paymentId,
                        'notes' => 'في انتظار إتمام الدفع (OTP/3D Secure)'
                    ]);
                }
                
                // Return invoiceURL for frontend to redirect
                return response()->json([
                    'success' => true,
                    'invoiceURL' => $invoiceURL,
                    'redirect' => true,
                    'paymentId' => $paymentId,
                    'message' => app()->getLocale() === 'ar' 
                        ? 'سيتم توجيهك إلى صفحة البنك لإتمام الدفع' 
                        : 'You will be redirected to complete payment'
                ]);
            }
            
            // If no paymentId and no invoiceURL, something went wrong
            if (!$paymentId) {
                Log::error('MyFatoorah executePayment: No paymentId and no invoiceURL found', [
                    'payment_response' => $payment,
                    'payment_keys' => is_array($payment) ? array_keys($payment) : 'not_array'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() === 'ar' 
                        ? 'لم يتم إنشاء رابط الدفع. يرجى المحاولة مرة أخرى.' 
                        : 'Payment link could not be created. Please try again.'
                ], 400);
            }
            
            // Save paymentId to order for tracking
            $order->update([
                'payment_reference' => $paymentId,
                'notes' => 'في انتظار تأكيد الدفع من MyFatoorah'
            ]);
            
            // Return paymentId for frontend to check status
            return response()->json([
                'success' => true,
                'paymentId' => $paymentId,
                'message' => 'Payment invoice created',
                'redirect' => false,
                'keepPolling' => true,
                'pollUrl' => route('myfatoorah.check-payment-status', ['paymentId' => $paymentId])
            ]);
            
        } catch (\Exception $e) {
            Log::error('MyFatoorah executePayment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'session_id' => $request->input('sessionId'),
                'order_id' => $request->input('orderId')
            ]);
            
            $locale = app()->getLocale();
            $errorMessage = $locale === 'ar'
                ? 'حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى.'
                : 'An error occurred processing the payment. Please try again.';
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => true
            ], 500);
        }
    }

    /**
     * OLD executePayment - REMOVED - Use callback() instead
     */
    private function executePayment_OLD(Request $request) {
        try {
            $sessionId = $request->input('sessionId');
            $orderId = $request->input('orderId');
            
            Log::info('MyFatoorah executePayment: Starting', [
                'session_id' => $sessionId,
                'order_id' => $orderId
            ]);
            
            if (!$sessionId || !$orderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session ID and Order ID are required'
                ], 400);
            }
            
            $order = Order::with('orderItems.service')->find($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            
            // Prepare invoice data
            $curlData = $this->getPayLoadData($orderId);
            
            Log::info('MyFatoorah executePayment: Invoice data prepared', [
                'order_id' => $orderId,
                'total_amount' => $order->total_amount
            ]);
            
            // Use sessionId to create invoice and get paymentId
            // According to MyFatoorah docs, when we have sessionId from embedded payment,
            // we need to use it to create invoice and execute payment
            // The sessionId is used to link the payment to the embedded session
            try {
                $mfObj = new MyFatoorahPayment($this->mfConfig);
                
                // According to MyFatoorah PHP library docs: getInvoiceURL($postFields, $paymentMethodId)
                // For embedded payment with sessionId, we need to include sessionId in the postFields
                // Add sessionId to the payload data
                if ($sessionId) {
                    $curlData['SessionId'] = $sessionId;
                }
                
                // $paymentMethodId = 0 means redirect to MyFatoorah invoice page
                $payment = $mfObj->getInvoiceURL($curlData, 0);
                
                // Convert object to array if needed (MyFatoorah may return stdClass objects)
                if (is_object($payment)) {
                    $payment = json_decode(json_encode($payment), true);
                }
                
                // Check if response contains an error
                if (isset($payment['IsSuccess']) && $payment['IsSuccess'] === false) {
                    $errorMessage = $payment['Message'] ?? $payment['message'] ?? 'Unknown error from MyFatoorah';
                    Log::error('MyFatoorah executePayment: Invoice creation failed', [
                        'error_message' => $errorMessage,
                        'payment_response' => $payment,
                        'session_id' => $sessionId,
                        'order_id' => $orderId
                    ]);
                    
                    throw new \Exception($errorMessage);
                }
                
                // Check if response is null or empty
                if (empty($payment)) {
                    Log::error('MyFatoorah executePayment: Empty payment response', [
                        'session_id' => $sessionId,
                        'order_id' => $orderId,
                        'curl_data' => $curlData
                    ]);
                    throw new \Exception('Empty response from MyFatoorah');
                }
                
                Log::info('MyFatoorah executePayment: Invoice created', [
                    'payment_response' => $payment,
                    'payment_keys' => is_array($payment) ? array_keys($payment) : 'not_array',
                    'payment_type' => gettype($payment),
                    'has_invoice_id' => isset($payment['InvoiceId']),
                    'has_invoice_url' => isset($payment['invoiceURL']),
                    'invoice_url_value' => $payment['invoiceURL'] ?? $payment['InvoiceURL'] ?? $payment['invoice_url'] ?? 'NOT_FOUND',
                    'all_url_keys' => is_array($payment) ? array_filter(array_keys($payment), function($key) {
                        return stripos($key, 'url') !== false || stripos($key, 'invoice') !== false;
                    }) : []
                ]);
                
                // Log full response for debugging (but limit size)
                if (is_array($payment)) {
                    $logData = $payment;
                    // Remove large data if present
                    if (isset($logData['InvoiceTransactions']) && is_array($logData['InvoiceTransactions'])) {
                        $logData['InvoiceTransactions'] = '[' . count($logData['InvoiceTransactions']) . ' transactions]';
                    }
                    Log::info('MyFatoorah executePayment: Full payment response', $logData);
                } else {
                    Log::warning('MyFatoorah executePayment: Payment response is not an array', [
                        'response_type' => gettype($payment),
                        'response_value' => $payment
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('MyFatoorah executePayment: Error creating invoice', [
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'session_id' => $sessionId,
                    'order_id' => $orderId,
                    'curl_data_keys' => is_array($curlData) ? array_keys($curlData) : 'not_array'
                ]);
                
                // DO NOT update order status to failed here - this is just an invoice creation error
                // The order should remain in its current state so user can retry
                // Only update order status when we get a confirmed failure from MyFatoorah payment status
                
                // Return user-friendly error message in Arabic
                $locale = app()->getLocale();
                $errorMessage = $locale === 'ar' 
                    ? 'حدث خطأ في إنشاء فاتورة الدفع. يرجى المحاولة مرة أخرى.' 
                    : 'Error creating payment invoice. Please try again.';
                    
                // Check for specific error types
                $errorMsgLower = strtolower($e->getMessage());
                if (strpos($errorMsgLower, 'session') !== false || strpos($errorMsgLower, 'expired') !== false) {
                    $errorMessage = $locale === 'ar'
                        ? 'انتهت صلاحية جلسة الدفع. يرجى المحاولة مرة أخرى من البداية.'
                        : 'Payment session expired. Please try again from the beginning.';
                } elseif (strpos($errorMsgLower, 'invoice') !== false || strpos($errorMsgLower, 'invalid') !== false) {
                    $errorMessage = $locale === 'ar'
                        ? 'حدث خطأ في إنشاء فاتورة الدفع. يرجى التحقق من البيانات والمحاولة مرة أخرى.'
                        : 'Error creating payment invoice. Please check your data and try again.';
                } elseif (strpos($errorMsgLower, 'network') !== false || strpos($errorMsgLower, 'connection') !== false) {
                    $errorMessage = $locale === 'ar'
                        ? 'مشكلة في الاتصال. يرجى التحقق من اتصالك بالإنترنت والمحاولة مرة أخرى.'
                        : 'Connection problem. Please check your internet connection and try again.';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => true,
                    'error_type' => 'invoice_creation_error',
                    'retry' => true  // Indicate that user can retry
                ], 500);
            }
            
            // Extract paymentId/InvoiceId from response
            $paymentId = $payment['InvoiceId'] ?? $payment['paymentId'] ?? $payment['PaymentId'] ?? null;
            
            // Check if invoiceURL exists - this is needed for OTP/3D Secure authentication
            // If invoiceURL exists, we MUST redirect user to it to complete payment
            // This check MUST be done FIRST before any other processing
            $invoiceURL = $payment['invoiceURL'] ?? $payment['InvoiceURL'] ?? $payment['invoice_url'] ?? null;
            
            // If invoiceURL is not in response but we have InvoiceId, build the URL manually
            // This is needed for Embedded Payment flow where invoiceURL might not be returned
            if (!$invoiceURL && $paymentId) {
                Log::info('MyFatoorah executePayment: Building invoiceURL from InvoiceId', [
                    'invoice_id' => $paymentId
                ]);
                
                // Get portal URL from MyFatoorah configuration
                $isTest = $this->mfConfig['isTest'];
                $vcCode = $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU';
                $countries = MyFatoorah::getMFCountries();
                
                if (isset($countries[$vcCode])) {
                    $portalBase = $isTest ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];
                    // Build invoice URL: https://portal.myfatoorah.com/pay/{InvoiceId}
                    // Or: https://portal.myfatoorah.com/invoice/{InvoiceId}
                    // Try both formats
                    $invoiceURL = rtrim($portalBase, '/') . '/pay/' . $paymentId;
                    
                    Log::info('MyFatoorah executePayment: Built invoiceURL', [
                        'invoice_url' => $invoiceURL,
                        'portal_base' => $portalBase,
                        'invoice_id' => $paymentId,
                        'is_test' => $isTest,
                        'vc_code' => $vcCode,
                        'country_config' => $countries[$vcCode] ?? 'not_found'
                    ]);
                } else {
                    Log::warning('MyFatoorah executePayment: Could not build invoiceURL - country not found', [
                        'vc_code' => $vcCode,
                        'available_countries' => array_keys($countries ?? []),
                        'is_test' => $isTest
                    ]);
                    
                    // Fallback: use default portal URL
                    if ($isTest) {
                        $invoiceURL = 'https://test.myfatoorah.com/pay/' . $paymentId;
                    } else {
                        $invoiceURL = 'https://portal.myfatoorah.com/pay/' . $paymentId;
                    }
                    
                    Log::info('MyFatoorah executePayment: Using fallback invoiceURL', [
                        'invoice_url' => $invoiceURL
                    ]);
                }
            }
            
            // If we have invoiceURL, redirect user to it for OTP/3D Secure
            if ($invoiceURL && !empty(trim($invoiceURL))) {
                Log::info('MyFatoorah executePayment: Invoice URL found/built, redirecting user for OTP/3D Secure', [
                    'invoice_url' => $invoiceURL,
                    'has_invoice_id' => isset($payment['InvoiceId']),
                    'invoice_id' => $paymentId,
                    'payment_keys' => is_array($payment) ? array_keys($payment) : 'not_array',
                    'url_source' => isset($payment['invoiceURL']) ? 'from_response' : 'built_from_invoice_id'
                ]);
                
                // If we have paymentId, save it for tracking
                if ($paymentId) {
                    $order->update([
                        'payment_reference' => $paymentId,
                        'notes' => 'في انتظار إتمام الدفع (OTP/3D Secure)'
                    ]);
                }
                
                // Return invoiceURL for frontend to redirect - this is the most important response
                // Frontend MUST redirect to this URL for OTP authentication
                return response()->json([
                    'success' => true,
                    'invoiceURL' => $invoiceURL,
                    'redirect' => true,
                    'paymentId' => $paymentId,
                    'message' => app()->getLocale() === 'ar' 
                        ? 'سيتم توجيهك إلى صفحة البنك لإتمام الدفع' 
                        : 'You will be redirected to complete payment'
                ]);
            }
            
            // If no paymentId and no invoiceURL, something went wrong
            if (!$paymentId) {
                Log::error('MyFatoorah executePayment: No paymentId and no invoiceURL found', [
                    'payment_response' => $payment,
                    'payment_keys' => is_array($payment) ? array_keys($payment) : 'not_array'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => app()->getLocale() === 'ar' 
                        ? 'لم يتم إنشاء رابط الدفع. يرجى المحاولة مرة أخرى.' 
                        : 'Payment link could not be created. Please try again.'
                ], 400);
            }
            
            Log::info('MyFatoorah executePayment: Payment ID extracted', [
                'payment_id' => $paymentId
            ]);
            
            // Save paymentId to order for tracking
            $order->update([
                'payment_reference' => $paymentId,
                'notes' => 'في انتظار تأكيد الدفع من MyFatoorah'
            ]);
            
            // Wait a moment for payment to process (3D Secure, OTP, etc.)
            sleep(3);
            
            // Check payment status from MyFatoorah - poll multiple times until payment is actually charged
            // We will wait up to 60 seconds (20 attempts * 3 seconds) for payment to be processed
            $maxRetries = 20;
            $retryDelay = 3; // seconds
            $paymentStatus = null;
            $invoiceStatus = 'Unknown';
            
            Log::info('MyFatoorah executePayment: Starting payment status polling', [
                'payment_id' => $paymentId,
                'max_retries' => $maxRetries,
                'retry_delay' => $retryDelay
            ]);
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $mfStatusObj = new MyFatoorahPaymentStatus($this->mfConfig);
                    $paymentStatus = $mfStatusObj->getPaymentStatus($paymentId, 'PaymentId');
                    
                    // Convert object to array if needed (MyFatoorah may return stdClass objects)
                    if (is_object($paymentStatus)) {
                        $paymentStatus = json_decode(json_encode($paymentStatus), true);
                    }
                    
                    $invoiceStatus = $paymentStatus['InvoiceStatus'] ?? 'Unknown';
                    
                    Log::info('MyFatoorah executePayment: Payment status checked', [
                        'payment_id' => $paymentId,
                        'attempt' => $attempt,
                        'max_attempts' => $maxRetries,
                        'invoice_status' => $invoiceStatus,
                        'payment_status_data' => $paymentStatus
                    ]);
                    
                    // If payment is paid, break immediately - payment is successfully charged
                    if (strtolower($invoiceStatus) === 'paid') {
                        Log::info('MyFatoorah executePayment: Payment confirmed as Paid', [
                            'payment_id' => $paymentId,
                            'attempt' => $attempt
                        ]);
                        break;
                    }
                    
                    // If payment failed, break the loop
                    if (strtolower($invoiceStatus) === 'failed') {
                        Log::info('MyFatoorah executePayment: Payment failed', [
                            'payment_id' => $paymentId,
                            'attempt' => $attempt
                        ]);
                        break;
                    }
                    
                    // If still pending and not the last attempt, wait before retrying
                    if ($attempt < $maxRetries) {
                        sleep($retryDelay);
                    }
                } catch (\Exception $e) {
                    Log::error('MyFatoorah executePayment: Error checking payment status', [
                        'payment_id' => $paymentId,
                        'attempt' => $attempt,
                        'error' => $e->getMessage()
                    ]);
                    
                    // If it's the last attempt, we'll handle it below
                    if ($attempt < $maxRetries) {
                        sleep($retryDelay);
                    }
                }
            }
            
            // Only proceed if payment is actually paid
            if ($paymentStatus && strtolower($invoiceStatus) === 'paid') {
                // Payment is successful, update order
                $paymentMethod = $paymentStatus['PaymentMethod'] ?? 'MyFatoorah';
                if (stripos($paymentMethod, 'myfatoorah') === false && $paymentMethod !== 'MyFatoorah') {
                    $paymentMethod = 'MyFatoorah';
                }
                
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $paymentId,
                    'status' => 'confirmed',
                    'notes' => 'تم الدفع بنجاح عبر ماي فاتورة'
                ]);
                
                $order->refresh();
                
                Log::info('MyFatoorah executePayment: Order updated successfully', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method
                ]);
                
                // Send email to admin
                try {
                    $adminEmail = SettingsHelper::contactEmail();
                    Mail::to($adminEmail)->send(new OrderCreatedMail($order->fresh()->load('orderItems.service')));
                    Log::info('Order paid email sent successfully to: ' . $adminEmail);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send order paid email: ' . $emailException->getMessage());
                }
                
                return response()->json([
                    'success' => true,
                    'paymentId' => $paymentId,
                    'message' => 'Payment successful',
                    'order_id' => $orderId
                ]);
            } elseif ($paymentStatus && strtolower($invoiceStatus) === 'failed') {
                // Payment failed
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $paymentId,
                    'status' => 'cancelled',
                    'notes' => 'فشل في الدفع: ' . ($paymentStatus['InvoiceError'] ?? 'Unknown error')
                ]);
                
                Log::info('MyFatoorah executePayment: Payment failed', [
                    'payment_id' => $paymentId,
                    'invoice_status' => $invoiceStatus
                ]);
                
                $locale = app()->getLocale();
                $message = $locale === 'ar'
                    ? 'فشل الدفع. يرجى المحاولة مرة أخرى.'
                    : 'Payment failed. Please try again.';
                
                return response()->json([
                    'success' => false,
                    'paymentId' => $paymentId,
                    'status' => 'failed',
                    'message' => $message,
                    'redirect' => true,
                    'callbackUrl' => route('myfatoorah.callback', ['paymentId' => $paymentId])
                ]);
            } else {
                // Payment is still pending after all retries - don't redirect yet
                // Return a status that tells frontend to continue polling
                $invoiceStatus = $paymentStatus['InvoiceStatus'] ?? 'Pending';
                
                Log::info('MyFatoorah executePayment: Payment still pending after ' . $maxRetries . ' attempts', [
                    'payment_id' => $paymentId,
                    'invoice_status' => $invoiceStatus,
                    'total_wait_time' => ($maxRetries * $retryDelay) . ' seconds'
                ]);
                
                // Don't redirect yet - payment hasn't been charged
                // Return status that tells frontend to continue polling
                // Use direct URL path to avoid locale issues
                $locale = app()->getLocale();
                $pollUrl = ($locale === 'en') ? '/en/myfatoorah/check-payment-status/' . $paymentId : '/myfatoorah/check-payment-status/' . $paymentId;
                
                $message = $locale === 'ar'
                    ? 'جاري معالجة الدفع. يرجى الانتظار...'
                    : 'Payment is still being processed. Please wait...';
                
                return response()->json([
                    'success' => false,
                    'paymentId' => $paymentId,
                    'status' => $invoiceStatus,
                    'message' => $message,
                    'redirect' => false,
                    'keepPolling' => true,
                    'pollUrl' => $pollUrl
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('MyFatoorah executePayment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'session_id' => $request->input('sessionId'),
                'order_id' => $request->input('orderId')
            ]);
            
            $locale = app()->getLocale();
            $errorMessage = $locale === 'ar'
                ? 'حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى.'
                : 'An error occurred processing the payment. Please try again.';
            
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $errorMessage,
                'error_type' => 'general_error'
            ], 500);
        }
    }

    /**
     * Check payment status by paymentId
     * Used for polling payment status from frontend
     * 
     * @param string $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus($paymentId) {
        try {
            if (!$paymentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment ID is required'
                ], 400);
            }

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get payment status'
                ], 404);
            }

            // Convert object to array if needed
            if (is_object($data)) {
                $data = json_decode(json_encode($data), true);
            }

            $invoiceStatus = $data['InvoiceStatus'] ?? 'Unknown';
            $orderId = $data['UserDefinedField'] ?? null;

            // If payment is paid, update order and return success
            if (strtolower($invoiceStatus) === 'paid' && $orderId) {
                $order = Order::find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $paymentMethod = $data['PaymentMethod'] ?? 'MyFatoorah';
                    if (stripos($paymentMethod, 'myfatoorah') === false && $paymentMethod !== 'MyFatoorah') {
                        $paymentMethod = 'MyFatoorah';
                    }
                    
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_method' => $paymentMethod,
                        'payment_reference' => $paymentId,
                        'status' => 'confirmed',
                        'notes' => 'تم الدفع بنجاح عبر ماي فاتورة'
                    ]);

                    // Send email to admin
                    try {
                        $adminEmail = SettingsHelper::contactEmail();
                        Mail::to($adminEmail)->send(new OrderCreatedMail($order->fresh()->load('orderItems.service')));
                        Log::info('Order paid email sent successfully to: ' . $adminEmail);
                    } catch (\Exception $emailException) {
                        Log::error('Failed to send order paid email: ' . $emailException->getMessage());
                    }
                }
            }

            return response()->json([
                'success' => strtolower($invoiceStatus) === 'paid',
                'status' => $invoiceStatus,
                'paymentId' => $paymentId,
                'orderId' => $orderId,
                'isPaid' => strtolower($invoiceStatus) === 'paid',
                'isFailed' => strtolower($invoiceStatus) === 'failed',
                'isPending' => strtolower($invoiceStatus) !== 'paid' && strtolower($invoiceStatus) !== 'failed'
            ]);
        } catch (\Exception $e) {
            Log::error('MyFatoorah checkPaymentStatus error: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Example on how to Display the enabled gateways at your MyFatoorah account to be displayed on the checkout page
     * Provide the checkout method with the order id to display its total amount and currency
     * 
     * @return View
     */
    public function checkout() {
        try {
            //You can get the data using the order object in your system
            $orderId = request('oid') ?: null;
            if (!$orderId) {
                throw new Exception('Order ID is required');
            }

            $order = Order::with('orderItems.service')->find($orderId);
            if (!$order) {
                throw new Exception('Order not found');
            }

            // Log configuration for debugging
            Log::info('MyFatoorah checkout: Configuration check', [
                'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 20) . '...',
                'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                'api_key_full' => $this->mfConfig['apiKey'] ?? 'EMPTY', // Log full key for debugging (remove in production)
                'is_test' => $this->mfConfig['isTest'] ?? null,
                'country_code' => $this->mfConfig['countryCode'] ?? null,
                'vc_code' => $this->mfConfig['vcCode'] ?? null,
                'mf_config_keys' => array_keys($this->mfConfig),
                'mf_config_full' => $this->mfConfig, // Log full config for debugging
                'order_id' => $orderId,
                'order_total' => $order->total_amount,
                'env_api_key' => env('MYFATOORAH_API_KEY') ? substr(env('MYFATOORAH_API_KEY'), 0, 20) . '...' : 'not_set',
                'env_test_mode' => env('MYFATOORAH_TEST_MODE'),
                'config_api_key' => config('myfatoorah.api_key') ? substr(config('myfatoorah.api_key'), 0, 20) . '...' : 'not_set',
                'config_test_mode' => config('myfatoorah.test_mode'),
                'config_country_iso' => config('myfatoorah.country_iso')
            ]);

            // Validate API key
            if (empty($this->mfConfig['apiKey']) || strlen($this->mfConfig['apiKey']) < 20) {
                Log::error('MyFatoorah checkout: Invalid API key', [
                    'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                    'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 10)
                ]);
                throw new Exception('مفتاح API غير صالح. يرجى التحقق من إعدادات MyFatoorah.');
            }

            //You can replace this variable with customer Id in your system
            $customerId = request('customerId');

            //You can use the user defined field if you want to save card
            $userDefinedField = config('myfatoorah.save_card') && $customerId ? "CK-$customerId" : '';

            //Get the enabled gateways at your MyFatoorah acount to be displayed on checkout page
            try {
                // Log the exact config being passed to MyFatoorahPaymentEmbedded
                Log::info('MyFatoorah checkout: Creating MyFatoorahPaymentEmbedded with config', [
                    'config_keys' => array_keys($this->mfConfig),
                    'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                    'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 30) . '...',
                    'is_test' => $this->mfConfig['isTest'],
                    'country_code' => $this->mfConfig['countryCode'] ?? null,
                    'vc_code' => $this->mfConfig['vcCode'] ?? null,
                    'order_total' => $order->total_amount,
                    'currency' => 'SAR'
                ]);
                
                $mfObj          = new MyFatoorahPaymentEmbedded($this->mfConfig);
                $paymentMethods = $mfObj->getCheckoutGateways($order->total_amount, 'SAR', config('myfatoorah.register_apple_pay'));
                
                Log::info('MyFatoorah checkout: getCheckoutGateways success', [
                    'payment_methods_count' => isset($paymentMethods['all']) ? count($paymentMethods['all']) : 0,
                    'has_cards' => isset($paymentMethods['cards']),
                    'cards_count' => isset($paymentMethods['cards']) ? count($paymentMethods['cards']) : 0,
                    'has_google_pay' => isset($paymentMethods['gp']),
                    'has_apple_pay' => isset($paymentMethods['ap'])
                ]);
                
                // If no cards are returned from getCheckoutGateways, try to get them from initiatePayment
                if (empty($paymentMethods['cards']) || !isset($paymentMethods['cards'])) {
                    Log::info('MyFatoorah checkout: No cards found in getCheckoutGateways, trying initiatePayment');
                    try {
                        $mfPaymentObj = new MyFatoorahPayment($this->mfConfig);
                        $allPaymentMethods = $mfPaymentObj->initiatePayment($order->total_amount, 'SAR');
                        
                        // Convert object to array if needed
                        if (is_object($allPaymentMethods)) {
                            $allPaymentMethods = json_decode(json_encode($allPaymentMethods), true);
                        }
                        
                        // Filter for credit card payment methods (Visa, Mastercard, etc.)
                        if (isset($allPaymentMethods['Data']['PaymentMethods']) && is_array($allPaymentMethods['Data']['PaymentMethods'])) {
                            $cardMethods = [];
                            foreach ($allPaymentMethods['Data']['PaymentMethods'] as $method) {
                                // Convert object to array if needed
                                if (is_object($method)) {
                                    $method = json_decode(json_encode($method), true);
                                }
                                
                                $paymentMethodCode = $method['PaymentMethodCode'] ?? '';
                                // Check if it's a credit card method (Visa, Mastercard, etc.)
                                // Common codes: 'VISA', 'MASTERCARD', 'AMEX', 'MADA', etc.
                                if (in_array(strtoupper($paymentMethodCode), ['VISA', 'MASTERCARD', 'AMEX', 'MADA', 'DINERS', 'DISCOVER', 'JCB']) || 
                                    stripos($method['PaymentMethodEn'] ?? '', 'card') !== false ||
                                    stripos($method['PaymentMethodAr'] ?? '', 'بطاقة') !== false) {
                                    
                                    // Calculate gateway data for this method
                                    $gatewayData = [
                                        'GatewayTotalAmount' => $order->total_amount,
                                        'GatewayCurrency' => 'SAR'
                                    ];
                                    
                                    // Add GatewayData to method
                                    $method['GatewayData'] = $gatewayData;
                                    
                                    $cardMethods[] = $method;
                                }
                            }
                            
                            if (!empty($cardMethods)) {
                                // Initialize cards array if not exists
                                if (!isset($paymentMethods['cards'])) {
                                    $paymentMethods['cards'] = [];
                                }
                                
                                // Merge with existing cards (avoid duplicates)
                                $existingIds = array_column($paymentMethods['cards'], 'PaymentMethodId');
                                foreach ($cardMethods as $cardMethod) {
                                    if (!in_array($cardMethod['PaymentMethodId'] ?? null, $existingIds)) {
                                        $paymentMethods['cards'][] = $cardMethod;
                                    }
                                }
                                
                                Log::info('MyFatoorah checkout: Added credit card methods from initiatePayment', [
                                    'cards_added' => count($cardMethods),
                                    'total_cards' => count($paymentMethods['cards'])
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('MyFatoorah checkout: Failed to get cards from initiatePayment', [
                            'error' => $e->getMessage()
                        ]);
                        // Continue without cards - don't fail the checkout
                    }
                }
            } catch (\Exception $e) {
                Log::error('MyFatoorah checkout: getCheckoutGateways failed', [
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'config' => [
                        'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 30) . '...',
                        'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                        'api_key_full' => $this->mfConfig['apiKey'] ?? 'EMPTY', // Log full key for debugging
                        'is_test' => $this->mfConfig['isTest'] ?? null,
                        'country_code' => $this->mfConfig['countryCode'] ?? null,
                        'vc_code' => $this->mfConfig['vcCode'] ?? null,
                        'config_keys' => array_keys($this->mfConfig)
                    ],
                    'trace' => $e->getTraceAsString()
                ]);
                throw new Exception('Failed to get payment methods: ' . $e->getMessage());
            }

            if (empty($paymentMethods['all'])) {
                throw new Exception('noPaymentGateways');
            }

            //Generate MyFatoorah session for embedded payment
            $mfSession = $mfObj->getEmbeddedSession($userDefinedField);

            //Get Environment url
            $isTest = $this->mfConfig['isTest'];
            $vcCode = $this->mfConfig['countryCode'];

            $countries = MyFatoorah::getMFCountries();
            $jsDomain  = ($isTest) ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];

            return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField', 'order'));
        } catch (Exception $ex) {
            $exMessage = $ex->getMessage();
            
            // Check if it's a token validation error
            $isTokenError = stripos($exMessage, 'token') !== false || 
                           stripos($exMessage, 'expired') !== false ||
                           stripos($exMessage, 'invalid') !== false ||
                           stripos($exMessage, 'غير صالح') !== false ||
                           stripos($exMessage, 'منتهي') !== false;
            
            if ($isTokenError) {
                $exMessage = 'مفتاح API غير صالح أو منتهي الصلاحية. يرجى التحقق من: ' . 
                           '1) أن مفتاح API في ملف .env صحيح ' .
                           '2) أن مفتاح API يتطابق مع وضع الاختبار/الإنتاج (MYFATOORAH_TEST_MODE) ' .
                           '3) أن مفتاح API نشط في حساب MyFatoorah. ' .
                           'الخطأ الأصلي: ' . $exMessage;
            }
            
            // Try to translate the message
            $translatedMessage = __('myfatoorah.' . $exMessage);
            if ($translatedMessage === 'myfatoorah.' . $exMessage) {
                $translatedMessage = $exMessage;
            }
            
            Log::error('MyFatoorah checkout error', [
                'error' => $ex->getMessage(),
                'is_token_error' => $isTokenError,
                'order_id' => request('oid'),
                'config' => [
                    'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 20) . '...',
                    'is_test' => $this->mfConfig['isTest'] ?? null,
                    'vc_code' => $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? null
                ]
            ]);
            
            return view('myfatoorah.error', ['exMessage' => $translatedMessage]);
        }
    }

    /**
     * Example on how the webhook is working when MyFatoorah try to notify your system about any transaction status update
     */
    public function webhook(Request $request) {
        try {
            //Validate webhook_secret_key
            $secretKey = config('myfatoorah.webhook_secret_key');
            if (empty($secretKey)) {
                return response(null, 404);
            }

            //Validate MyFatoorah-Signature
            $mfSignature = $request->header('MyFatoorah-Signature');
            if (empty($mfSignature)) {
                return response(null, 404);
            }

            //Validate input
            $body  = $request->getContent();
            $input = json_decode($body, true);
            if (empty($input['Data']) || empty($input['EventType']) || $input['EventType'] != 1) {
                return response(null, 404);
            }

            //Validate Signature
            if (!MyFatoorah::isSignatureValid($input['Data'], $secretKey, $mfSignature, $input['EventType'])) {
                return response(null, 404);
            }

            //Update Transaction status on your system
            $result = $this->changeTransactionStatus($input['Data']);

            return response()->json($result);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }

    private function changeTransactionStatus($inputData) {
        //1. Check if orderId is valid on your system.
        $orderId = $inputData['CustomerReference'];

        //2. Get MyFatoorah invoice id
        $invoiceId = $inputData['InvoiceId'];

        //3. Check order status at MyFatoorah side
        if ($inputData['TransactionStatus'] == 'SUCCESS') {
            $status = 'Paid';
            $error  = '';
        } else {
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($invoiceId, 'InvoiceId');

            // Convert object to array if needed (MyFatoorah may return stdClass objects)
            if (is_object($data)) {
                $data = json_decode(json_encode($data), true);
            }

            $status = $data['InvoiceStatus'] ?? 'Unknown';
            $error  = $data['InvoiceError'] ?? '';
        }

        $message = $this->getTestMessage($status, $error);

        //4. Update order transaction status on your system
        return ['IsSuccess' => true, 'Message' => $message, 'Data' => $inputData];
    }

    private function getTestMessage($status, $error) {
        if ($status == 'Paid') {
            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
        return 'Payment status: ' . $status;
    }

    /**
     * Clean phone number for MyFatoorah
     * Removes country code, spaces, and non-digit characters
     * Validates and ensures proper format for Saudi Arabia (9 digits)
     * 
     * @param string $phoneNumber
     * @param string $countryCode
     * @return string Cleaned phone number (digits only, 8-9 digits for Saudi)
     */
    private function cleanPhoneNumber($phoneNumber, $countryCode = '+966') {
        if (empty($phoneNumber)) {
            return '';
        }

        // Remove all non-digit characters except + at the beginning
        $phoneNumber = trim($phoneNumber);
        
        // Remove spaces, dashes, parentheses, dots, and other special characters
        $phoneNumber = preg_replace('/[\s\-\(\)\.\/]/', '', $phoneNumber);
        
        // Remove country code if it exists at the beginning
        $countryCodeWithoutPlus = ltrim($countryCode, '+');
        
        // Check for different country code formats
        if (strpos($phoneNumber, $countryCode) === 0) {
            $phoneNumber = substr($phoneNumber, strlen($countryCode));
        } elseif (strpos($phoneNumber, $countryCodeWithoutPlus) === 0) {
            $phoneNumber = substr($phoneNumber, strlen($countryCodeWithoutPlus));
        } elseif (strpos($phoneNumber, '00' . $countryCodeWithoutPlus) === 0) {
            // Handle 00966 format
            $phoneNumber = substr($phoneNumber, strlen('00' . $countryCodeWithoutPlus));
        } elseif (preg_match('/^\+?966/', $phoneNumber)) {
            // Handle any variation of +966 or 966
            $phoneNumber = preg_replace('/^\+?966/', '', $phoneNumber);
        }
        
        // Remove leading zeros
        $phoneNumber = ltrim($phoneNumber, '0');
        
        // Ensure it contains only digits
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // For Saudi Arabia: validate length (should be 9 digits)
        // MyFatoorah typically requires 8-9 digits for Saudi numbers
        if (strlen($phoneNumber) > 9) {
            // If longer than 9, take last 9 digits
            $phoneNumber = substr($phoneNumber, -9);
        }
        
        return $phoneNumber;
    }
    
    /**
     * Validate phone number for MyFatoorah
     * Ensures phone number meets MyFatoorah requirements
     * Note: This function expects an already cleaned phone number
     * 
     * @param string $cleanedPhoneNumber Already cleaned phone number (digits only)
     * @param string $countryCode
     * @return bool
     */
    private function isValidPhoneNumber($cleanedPhoneNumber, $countryCode = '+966') {
        if (empty($cleanedPhoneNumber)) {
            return false;
        }
        
        // For Saudi Arabia: phone should be 8-9 digits
        // MyFatoorah requires minimum 8 digits
        $length = strlen($cleanedPhoneNumber);
        
        if ($length < 8 || $length > 9) {
            return false;
        }
        
        // Check if it's all digits
        if (!ctype_digit($cleanedPhoneNumber)) {
            return false;
        }
        
        // Saudi mobile numbers typically start with 5
        // But we'll allow other formats for flexibility
        if ($countryCode === '+966' && !preg_match('/^5/', $cleanedPhoneNumber)) {
            // Log warning but don't fail validation
            Log::info('MyFatoorah: Phone number does not start with 5 (Saudi mobile format)', [
                'phone' => $cleanedPhoneNumber
            ]);
        }
        
        return true;
    }

    // Admin Dashboard Methods
    public function adminIndex()
    {
        // Get comprehensive payment statistics
        $stats = [
            'total_payments' => Order::where('payment_status', 'paid')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'failed_payments' => Order::where('payment_status', 'failed')->count(),
            'refunded_payments' => Order::where('payment_status', 'refunded')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'today_revenue' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'total_refunded' => Order::where('payment_status', 'refunded')->sum('refund_amount'),
            'average_order_value' => Order::where('payment_status', 'paid')->avg('total_amount'),
        ];

        // Get payment methods statistics
        $payment_methods_stats = Order::where('payment_status', 'paid')
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Get recent payments
        $recent_payments = Order::with('orderItems.service')
            ->whereNotNull('payment_reference')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Get daily revenue for the last 30 days
        $daily_revenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.myfatoorah.index', compact('stats', 'recent_payments', 'payment_methods_stats', 'daily_revenue'));
    }

    public function transactions(Request $request)
    {
        $query = Order::with('orderItems.service')
            ->whereNotNull('payment_reference');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('updated_at', 'desc')->paginate(20);

        return view('admin.myfatoorah.transactions', compact('transactions'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.service');
        
        // Get payment details from MyFatoorah if available
        $paymentDetails = null;
        if ($order->payment_reference) {
            try {
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                $paymentDetails = $mfObj->getPaymentStatus($order->payment_reference, 'PaymentId');
                
                // Convert object to array if needed (MyFatoorah may return stdClass objects)
                if (is_object($paymentDetails)) {
                    $paymentDetails = json_decode(json_encode($paymentDetails), true);
                }
            } catch (\Exception $e) {
                // Handle error silently
            }
        }

        return view('admin.myfatoorah.show', compact('order', 'paymentDetails'));
    }

    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->total_amount,
            'reason' => 'required|string|max:255'
        ]);

        try {
            // TODO: Implement refund functionality with correct MyFatoorah method
            // $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            
            // For now, return error as refund API needs to be properly implemented
            return back()->with('error', 'وظيفة الاسترداد غير متوفرة حالياً. يرجى التواصل مع الدعم الفني.');
            
            /*
            $refundData = [
                'Key' => $order->payment_reference,
                'KeyType' => 'PaymentId',
                'RefundChargeOnCustomer' => false,
                'ServiceChargeOnCustomer' => false,
                'Amount' => $request->amount,
                'Comment' => $request->reason
            ];

            $refundResult = $mfObj->makeRefund($refundData);
            */

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء استرداد المبلغ: ' . $e->getMessage());
        }
    }

    public function testConnection()
    {
        try {
            // Get API key directly from env and config for comparison
            $envApiKey = env('MYFATOORAH_API_KEY');
            $configApiKey = config('myfatoorah.api_key');
            
            // Log the current configuration for debugging
            Log::info('MyFatoorah Test Connection - Full Debug', [
                'api_key_from_env' => $envApiKey ? substr($envApiKey, 0, 20) . '...' : 'NOT_SET',
                'api_key_from_config' => $configApiKey ? substr($configApiKey, 0, 20) . '...' : 'NOT_SET',
                'api_key_from_mfConfig' => $this->mfConfig['apiKey'] ? substr($this->mfConfig['apiKey'], 0, 20) . '...' : 'NOT_SET',
                'api_key_length_env' => $envApiKey ? strlen($envApiKey) : 0,
                'api_key_length_config' => $configApiKey ? strlen($configApiKey) : 0,
                'api_key_length_mfConfig' => strlen($this->mfConfig['apiKey'] ?? ''),
                'is_test' => $this->mfConfig['isTest'],
                'vc_code' => $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU',
                'env_test_mode' => env('MYFATOORAH_TEST_MODE'),
                'config_test_mode' => config('myfatoorah.test_mode')
            ]);
            
            if (!$this->mfConfig['apiKey'] || strlen($this->mfConfig['apiKey']) < 20) {
                return response()->json([
                    'success' => false,
                    'message' => 'مفتاح API غير صالح أو غير موجود',
                    'debug' => [
                        'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                        'api_key_from_env' => $envApiKey ? 'yes' : 'no',
                        'api_key_from_config' => $configApiKey ? 'yes' : 'no',
                        'env_key_value' => $envApiKey ? substr($envApiKey, 0, 15) . '...' : 'empty',
                        'config_key_value' => $configApiKey ? substr($configApiKey, 0, 15) . '...' : 'empty'
                    ]
                ]);
            }
            
            // Validate API key format
            if (!preg_match('/^SK_[A-Z]{3}_/', $this->mfConfig['apiKey'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'تنسيق مفتاح API غير صحيح. يجب أن يبدأ بـ SK_XXX_',
                    'api_key_prefix' => substr($this->mfConfig['apiKey'], 0, 10)
                ]);
            }
            
            // Validate vcCode before making the call
            $validVcCodes = ['KWT', 'SAU', 'ARE', 'QAT', 'BHR', 'OMN', 'JOR', 'EGY'];
            $vcCode = $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU';
            if (!in_array($vcCode, $validVcCodes)) {
                Log::error('Invalid vcCode: ' . $vcCode);
                return response()->json([
                    'success' => false,
                    'message' => 'رمز البلد غير صحيح: ' . $vcCode . '. يجب أن يكون واحداً من: ' . implode(', ', $validVcCodes)
                ]);
            }
            
            // Use standard MyFatoorahPayment with initiatePayment()
            $mfObj = new MyFatoorahPayment($this->mfConfig);
            
            try {
                // Try without parameters first
                $result = $mfObj->initiatePayment();
                Log::info('MyFatoorah testConnection: initiatePayment() without parameters - success');
            } catch (\Exception $e) {
                Log::warning('MyFatoorah testConnection: initiatePayment() without parameters failed', [
                    'error' => $e->getMessage()
                ]);
                
                // Try with parameters
                try {
                    $result = $mfObj->initiatePayment(15, 'SAR');
                    Log::info('MyFatoorah testConnection: initiatePayment() with parameters - success');
                } catch (\Exception $e2) {
                    Log::error('MyFatoorah testConnection: Both methods failed', [
                        'error1' => $e->getMessage(),
                        'error2' => $e2->getMessage(),
                        'config' => [
                            'is_test' => $this->mfConfig['isTest'],
                            'vc_code' => $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU',
                            'api_key_prefix' => substr($this->mfConfig['apiKey'], 0, 15)
                        ]
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'فشل في الاتصال مع MyFatoorah: ' . $e2->getMessage(),
                        'error_details' => [
                            'error_without_params' => $e->getMessage(),
                            'error_with_params' => $e2->getMessage(),
                            'suggestion' => 'يرجى التحقق من: 1) صحة مفتاح API 2) أن المفتاح مفعل في حساب MyFatoorah 3) أن وضع الاختبار يطابق نوع المفتاح'
                        ]
                    ]);
                }
            }
            
            // Convert object to array if needed
            if (is_object($result)) {
                $result = json_decode(json_encode($result), true);
            }
            
            Log::info('MyFatoorah testConnection: Response received', [
                'is_success' => $result['IsSuccess'] ?? 'not_set',
                'message' => $result['Message'] ?? 'no_message',
                'has_payment_methods' => isset($result['Data']['PaymentMethods'])
            ]);
            
            if (isset($result['IsSuccess']) && $result['IsSuccess'] && 
                isset($result['Data']['PaymentMethods']) && !empty($result['Data']['PaymentMethods'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح مع MyFatoorah',
                    'payment_methods_count' => count($result['Data']['PaymentMethods']),
                    'payment_methods' => array_map(function($method) {
                        return [
                            'id' => $method['PaymentMethodId'] ?? null,
                            'name_ar' => $method['PaymentMethodAr'] ?? null,
                            'name_en' => $method['PaymentMethodEn'] ?? null
                        ];
                    }, $result['Data']['PaymentMethods'])
                ]);
            } else {
                $errorMsg = $result['Message'] ?? $result['message'] ?? 'فشل في جلب طرق الدفع';
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                    'response' => $result,
                    'suggestion' => 'يرجى التحقق من مفتاح API في حساب MyFatoorah'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('MyFatoorah Test Connection Error: ' . $e->getMessage(), [
                'config' => [
                    'is_test' => $this->mfConfig['isTest'],
                    'vc_code' => $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU',
                    'api_key_length' => strlen($this->mfConfig['apiKey'] ?? ''),
                    'api_key_prefix' => substr($this->mfConfig['apiKey'] ?? '', 0, 15)
                ],
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'suggestion' => 'يرجى التحقق من سجلات Laravel للحصول على تفاصيل أكثر'
            ]);
        }
    }

    public function settings()
    {
        $config = [
            'api_key' => SettingsHelper::get('myfatoorah_api_key', ''),
            'is_test' => SettingsHelper::get('myfatoorah_is_test', '1') == '1',
            'country_iso' => SettingsHelper::get('myfatoorah_country_iso', 'SA'),
            'currency_iso' => SettingsHelper::get('myfatoorah_currency', 'SAR'),
        ];
        
        // Add debug information
        $debug = [
            'current_vccode' => $this->mfConfig['vcCode'] ?? $this->mfConfig['countryCode'] ?? 'SAU',
            'country_mapping' => [
                'SA' => 'SAU', 'AE' => 'ARE', 'KW' => 'KWT', 'BH' => 'BHR',
                'QA' => 'QAT', 'OM' => 'OMN', 'JO' => 'JOR', 'EG' => 'EGY'
            ]
        ];
        
        return view('admin.myfatoorah.settings', compact('config', 'debug'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'is_test' => 'boolean',
            'country_iso' => 'required|string|size:2',
            'currency_iso' => 'required|string|size:3',
        ]);

        // Update settings in database
        \App\Models\Setting::set('myfatoorah_api_key', $request->api_key, 'string', 'مفتاح API لبوابة الدفع MyFatoorah');
        \App\Models\Setting::set('myfatoorah_is_test', $request->has('is_test') ? '1' : '0', 'boolean', 'وضع الاختبار لبوابة الدفع');
        \App\Models\Setting::set('myfatoorah_country_iso', $request->country_iso, 'string', 'رمز البلد');
        \App\Models\Setting::set('myfatoorah_currency', $request->currency_iso, 'string', 'العملة الافتراضية');

        // Clear settings cache
        \App\Models\Setting::clearCache();

        return redirect()->route('admin.myfatoorah.settings')
            ->with('success', 'تم تحديث إعدادات بوابة الدفع بنجاح');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $query = Order::with('orderItems.service')
            ->whereNotNull('payment_reference');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        if ($format === 'excel') {
            return $this->exportToExcel($transactions);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($transactions);
        }
        
        // Default CSV for backward compatibility
        return $this->exportToCSV($transactions);
    }
    
    private function exportToExcel($transactions)
    {
        return Excel::download(new class($transactions) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithTitle {
            private $transactions;
            
            public function __construct($transactions) {
                $this->transactions = $transactions;
            }
            
            public function collection() {
                $data = collect();
                foreach ($this->transactions as $transaction) {
                    $data->push([
                        'رقم الطلب' => $transaction->order_number,
                        'اسم العميل' => $transaction->customer_name,
                        'البريد الإلكتروني' => $transaction->customer_email,
                        'رقم الهاتف' => $transaction->customer_phone,
                        'المبلغ' => $transaction->total_amount,
                        'طريقة الدفع' => $transaction->payment_method ?? 'غير محدد',
                        'حالة الدفع' => $this->getPaymentStatusArabic($transaction->payment_status),
                        'تاريخ الطلب' => $transaction->created_at->format('Y-m-d H:i:s'),
                        'تاريخ الدفع' => $transaction->updated_at->format('Y-m-d H:i:s'),
                        'مرجع الدفع' => $transaction->payment_reference ?? '',
                    ]);
                }
                return $data;
            }
            
            public function headings(): array {
                return [
                    'رقم الطلب',
                    'اسم العميل',
                    'البريد الإلكتروني',
                    'رقم الهاتف',
                    'المبلغ',
                    'طريقة الدفع',
                    'حالة الدفع',
                    'تاريخ الطلب',
                    'تاريخ الدفع',
                    'مرجع الدفع'
                ];
            }
            
            public function title(): string {
                return 'معاملات MyFatoorah';
            }
            
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                return [
                    1 => ['font' => ['bold' => true, 'size' => 12]],
                ];
            }
            
            private function getPaymentStatusArabic($status) {
                $statuses = [
                    'pending' => 'في الانتظار',
                    'paid' => 'مدفوع',
                    'failed' => 'فشل',
                ];
                return $statuses[$status] ?? $status;
            }
        }, 'myfatoorah-transactions-' . date('Y-m-d') . '.xlsx');
    }
    
    private function exportToPDF($transactions)
    {
        return PdfService::download(
            'admin.reports.myfatoorah-pdf',
            compact('transactions'),
            'myfatoorah-transactions-' . date('Y-m-d') . '.pdf',
            [
                'format' => 'A4-L', // Landscape
                'orientation' => 'L',
            ]
        );
    }
    
    private function exportToCSV($transactions)
    {
        $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم العميل',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'المبلغ',
                'طريقة الدفع',
                'حالة الدفع',
                'تاريخ الطلب',
                'تاريخ الدفع',
                'مرجع الدفع'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->order_number,
                    $transaction->customer_name,
                    $transaction->customer_email,
                    $transaction->customer_phone,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    $transaction->payment_status,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s'),
                    $transaction->payment_reference
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function retry(Order $order)
    {
        try {
            if ($order->payment_status === 'paid') {
                return back()->with('error', 'هذا الطلب مدفوع بالفعل');
            }

            // Format phone number for MyFatoorah
            $countryCode = $order->country_code ?? '+966';
            $mobileCountryCode = ltrim($countryCode, '+'); // Remove + sign
            
            // Clean customer phone number
            $originalPhone = $order->customer_phone ?? '';
            $customerPhone = $this->cleanPhoneNumber($originalPhone, $countryCode);
            $isValidPhone = $this->isValidPhoneNumber($customerPhone, $countryCode);
            
            // If customer phone is empty or invalid, use company phone as fallback
            if (!$isValidPhone || empty($customerPhone) || strlen($customerPhone) < 8) {
                $companyPhone = SettingsHelper::contactPhone();
                $customerPhone = $this->cleanPhoneNumber($companyPhone, '+966');
                $isValidPhone = $this->isValidPhoneNumber($customerPhone, '+966');
                
                Log::info('MyFatoorah retry: Using company phone as fallback', [
                    'order_id' => $order->id,
                    'original_customer_phone' => $originalPhone,
                    'company_phone_raw' => SettingsHelper::contactPhone(),
                    'cleaned_company_phone' => $customerPhone,
                    'is_valid' => $isValidPhone
                ]);
            }
            
            // Final validation: ensure phone is not empty and valid
            if (empty($customerPhone) || !$isValidPhone || strlen($customerPhone) < 8) {
                $customerPhone = '559229980'; // Default company phone without country code
                Log::warning('MyFatoorah retry: Using hardcoded default phone number', [
                    'order_id' => $order->id,
                    'original_phone' => $originalPhone
                ]);
            }
            
            // Final check: ensure phone length is correct (8-9 digits for Saudi)
            if (strlen($customerPhone) < 8) {
                $customerPhone = '5' . str_pad($customerPhone, 8, '0', STR_PAD_LEFT);
                Log::warning('MyFatoorah retry: Phone number was too short, padded it', [
                    'order_id' => $order->id,
                    'final_phone' => $customerPhone
                ]);
            }

            // Create new payment session
            $paymentData = [
                'CustomerName' => $order->customer_name,
                'CustomerEmail' => $order->customer_email,
                'CustomerMobile' => $customerPhone,
                'InvoiceValue' => $order->total_amount,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $mobileCountryCode,
                'CustomerAddress' => [
                    'Address' => $order->customer_address,
                    'City' => 'الرياض',
                    'Country' => 'SA'
                ],
                'InvoiceItems' => $order->orderItems->map(function ($item) {
                    return [
                        'ItemName' => $item->service->name_ar,
                        'Quantity' => $item->quantity,
                        'UnitPrice' => $item->price,
                        'Weight' => 0,
                        'Width' => 0,
                        'Height' => 0,
                        'Depth' => 0
                    ];
                })->toArray(),
                'CallBackUrl' => route('payment.callback'),
                'ErrorUrl' => route('payment.error'),
                'Language' => 'ar',
                'CustomerReference' => $order->order_number,
                'UserDefinedField' => $order->id
            ];
            
            $mfObj = new MyFatoorahPayment($this->mfConfig);
            // According to MyFatoorah PHP library docs: getInvoiceURL($postFields, $paymentMethodId)
            // $paymentMethodId = 0 means redirect to MyFatoorah invoice page
            $payment = $mfObj->getInvoiceURL($paymentData, 0);
            
            // Convert object to array if needed (MyFatoorah may return stdClass objects)
            if (is_object($payment)) {
                $payment = json_decode(json_encode($payment), true);
            }
            
            $paymentUrl = $payment['invoiceURL'];
            
            return redirect($paymentUrl);
                
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إعادة محاولة الدفع: ' . $e->getMessage());
        }
    }
}
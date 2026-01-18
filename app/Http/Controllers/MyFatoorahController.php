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
        $countryIso = is_callable(config('myfatoorah.country_iso')) ? config('myfatoorah.country_iso')() : config('myfatoorah.country_iso');
        
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
        
        $this->mfConfig = [
            'apiKey'      => is_callable(config('myfatoorah.api_key')) ? config('myfatoorah.api_key')() : config('myfatoorah.api_key'),
            'isTest'      => is_callable(config('myfatoorah.test_mode')) ? config('myfatoorah.test_mode')() : config('myfatoorah.test_mode'),
            'vcCode'      => $vcCodeMap[$countryIso] ?? 'SAU', // Default to Saudi Arabia
        ];
    }

    /**
     * Redirect to MyFatoorah Invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function index() {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 0;
            $sessionId = request('sid') ?: null;

            $orderId = request('oid');
            
            if (!$orderId) {
                throw new Exception('Order ID is required. Please provide the order ID in the URL parameter (oid).');
            }
            
            $curlData = $this->getPayLoadData($orderId);

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

            // Convert object to array if needed (MyFatoorah may return stdClass objects)
            if (is_object($payment)) {
                $payment = json_decode(json_encode($payment), true);
            }

            return redirect($payment['invoiceURL']);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            // If translation doesn't exist, use the original message
            if ($exMessage === 'myfatoorah.' . $ex->getMessage()) {
                $exMessage = $ex->getMessage();
            }
            return response()->json(['IsSuccess' => 'false', 'Message' => $exMessage]);
        }
    }

    /**
     * Map order data to MyFatoorah payload
     * 
     * @param int|string $orderId
     * 
     * @return array
     */
    private function getPayLoadData($orderId = null) {
        $callbackURL = route('myfatoorah.callback');

        $order = Order::with('orderItems.service')->find($orderId);
        
        if (!$order) {
            throw new Exception('Order not found');
        }

        return [
            'CustomerName'         => $order->customer_name,
            'InvoiceValue'         => $order->total_amount,
            'DisplayCurrencyIso'   => 'SAR',
            'CustomerEmail'        => $order->customer_email,
            'CallBackUrl'          => $callbackURL,
            'ErrorUrl'             => $callbackURL,
            'MobileCountryCode'    => $order->country_code ?? '+966',
            'CustomerMobile'       => $order->customer_phone, // Phone without country code
            'Language'             => 'ar',
            'CustomerReference'    => $order->order_number,
            'UserDefinedField'     => $order->id,
            'CustomerAddress'      => [
                'Address' => $order->customer_address,
                'City' => 'الرياض',
                'Country' => 'SA'
            ],
            'InvoiceItems'         => $order->orderItems->map(function ($item) {
                return [
                    'ItemName' => $item->service->name_ar,
                    'Quantity' => $item->quantity,
                    'UnitPrice' => $item->unit_price,
                    'Weight' => 0,
                    'Width' => 0,
                    'Height' => 0,
                    'Depth' => 0
                ];
            })->toArray(),
            'SourceInfo'           => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }

    /**
     * Execute payment using sessionId and get paymentId
     * This is called when MyFatoorah returns sessionId instead of paymentId
     * According to MyFatoorah docs, we need to use sessionId to create invoice
     * 
     * @return \Illuminate\Http\JsonResponse
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
                
                // Pass sessionId as the 4th parameter to getInvoiceURL
                // This will create an invoice using the existing session
                // Note: getInvoiceURL signature is: getInvoiceURL($data, $paymentMethodId, $orderId, $sessionId)
                $payment = $mfObj->getInvoiceURL($curlData, 0, $orderId, $sessionId);
                
                // Convert object to array if needed (MyFatoorah may return stdClass objects)
                if (is_object($payment)) {
                    $payment = json_decode(json_encode($payment), true);
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
                    'error_type' => 'invoice_creation_error'
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
                $vcCode = $this->mfConfig['vcCode'];
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
     * Get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback() {
        try {
            $paymentId = request('paymentId');

            if (!$paymentId) {
                Log::warning('MyFatoorah callback: Missing paymentId', [
                    'request_data' => request()->all(),
                    'url' => request()->fullUrl()
                ]);
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الدفع. يرجى التواصل معنا.');
            }

            Log::info('MyFatoorah callback: Processing payment', [
                'payment_id' => $paymentId,
                'session_id' => request()->session()->getId()
            ]);

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            if (!$data) {
                Log::error('MyFatoorah callback: Failed to get payment status', [
                    'payment_id' => $paymentId,
                    'config' => $this->mfConfig
                ]);
                return redirect()->route('home')
                    ->with('error', 'فشل في التحقق من حالة الدفع. يرجى التواصل معنا.');
            }

            // Convert object to array if needed (MyFatoorah may return stdClass objects)
            if (is_object($data)) {
                $data = json_decode(json_encode($data), true);
            }

            $invoiceStatus = $data['InvoiceStatus'] ?? 'Unknown';
            
            Log::info('MyFatoorah callback: Payment status retrieved', [
                'payment_id' => $paymentId,
                'invoice_status' => $invoiceStatus,
                'payment_method' => $data['PaymentMethod'] ?? 'Unknown',
                'all_data_keys' => array_keys($data),
                'invoice_status_type' => gettype($invoiceStatus),
                'invoice_status_lowercase' => strtolower($invoiceStatus)
            ]);

            $orderId = $data['UserDefinedField'] ?? null;
            
            if (!$orderId) {
                Log::error('MyFatoorah callback: Missing order ID in UserDefinedField', [
                    'payment_id' => $paymentId,
                    'data' => $data
                ]);
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الطلب. يرجى التواصل معنا.');
            }

            $order = Order::with('orderItems.service')->find($orderId);
            
            if (!$order) {
                Log::error('MyFatoorah callback: Order not found', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId
                ]);
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على الطلب. يرجى التواصل معنا.');
            }
            
            // Log order state before update
            Log::info('MyFatoorah callback: Order state before update', [
                'order_id' => $orderId,
                'current_payment_status' => $order->payment_status,
                'current_payment_method' => $order->payment_method,
                'current_status' => $order->status
            ]);

            // تحديث حالة الطلب بناءً على حالة الدفع
            // Check for 'Paid' status (case-insensitive)
            if (strtolower($invoiceStatus) === 'paid') {
                // Ensure payment_method is set to 'MyFatoorah' if not already set
                $paymentMethod = $data['PaymentMethod'] ?? 'MyFatoorah';
                // Normalize payment method name
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
                
                // Refresh order to ensure changes are saved
                $order->refresh();
                
                Log::info('MyFatoorah callback: Order updated successfully', [
                    'order_id' => $orderId,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'payment_reference' => $order->payment_reference,
                    'status' => $order->status
                ]);
                
                // إرسال إيميل للإدارة عند الدفع الناجح
                try {
                    $adminEmail = SettingsHelper::contactEmail();
                    Mail::to($adminEmail)->send(new OrderCreatedMail($order->fresh()->load('orderItems.service')));
                    Log::info('Order paid email sent successfully to: ' . $adminEmail);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send order paid email: ' . $emailException->getMessage());
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
                
                // Save session data and ensure it's persisted
                $session = request()->session();
                $session->put('order_confirmation', $orderData);
                $session->save();
                
                Log::info('MyFatoorah callback: Payment successful', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'order_status' => $order->status,
                    'session_id' => request()->session()->getId()
                ]);
                
                // Double-check that order is visible in admin panel
                // Order should be visible if: payment_method = 'MyFatoorah' AND payment_status = 'paid'
                $orderAfterUpdate = Order::find($orderId);
                if ($orderAfterUpdate) {
                    $willBeVisible = ($orderAfterUpdate->payment_method === 'MyFatoorah' && $orderAfterUpdate->payment_status === 'paid');
                    Log::info('MyFatoorah callback: Order visibility check', [
                        'order_id' => $orderId,
                        'order_number' => $orderAfterUpdate->order_number,
                        'payment_method' => $orderAfterUpdate->payment_method,
                        'payment_status' => $orderAfterUpdate->payment_status,
                        'status' => $orderAfterUpdate->status,
                        'will_be_visible' => $willBeVisible,
                        'payment_method_check' => ($orderAfterUpdate->payment_method === 'MyFatoorah'),
                        'payment_status_check' => ($orderAfterUpdate->payment_status === 'paid')
                    ]);
                    
                    // If order should be visible but isn't, log warning
                    if (!$willBeVisible) {
                        Log::warning('MyFatoorah callback: Order may not be visible in admin panel', [
                            'order_id' => $orderId,
                            'order_number' => $orderAfterUpdate->order_number,
                            'payment_method' => $orderAfterUpdate->payment_method,
                            'payment_status' => $orderAfterUpdate->payment_status,
                            'expected_payment_method' => 'MyFatoorah',
                            'expected_payment_status' => 'paid'
                        ]);
                    }
                }
                
                // Ensure session is saved before redirect
                $session = request()->session();
                $session->save();
                
                // Use direct URL path to avoid route resolution issues
                // The route is /orders/confirmation for Arabic (default) and /en/orders/confirmation for English
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('success', 'تم الدفع بنجاح! شكراً لك على دعمك لمشروع وسيلة الخيري.');
                    
            } elseif ($data['InvoiceStatus'] === 'Failed') {
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $paymentId,
                    'status' => 'cancelled',
                    'notes' => 'فشل في الدفع: ' . ($data['InvoiceError'] ?? 'Unknown error')
                ]);
                
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
                
                // Save session data
                $session = request()->session();
                $session->put('order_confirmation', $orderData);
                $session->save();
                
                Log::info('MyFatoorah callback: Payment failed', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'error' => $data['InvoiceError'] ?? 'Unknown error',
                    'session_id' => request()->session()->getId()
                ]);
                
                // Ensure session is saved before redirect
                $session = request()->session();
                $session->save();
                
                // Use direct URL path to avoid route resolution issues
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('error', 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى أو التواصل معنا.');
                    
            } else {
                // حالة أخرى (مثل Pending)
                // Use 'confirmed' status with payment_status 'pending' instead of 'payment_pending'
                $order->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $paymentId,
                    'status' => 'confirmed',
                    'notes' => 'في انتظار تأكيد الدفع'
                ]);
                
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
                    'payment_status' => 'pending'
                ];
                
                // Save session data
                $session = request()->session();
                $session->put('order_confirmation', $orderData);
                $session->save();
                
                Log::info('MyFatoorah callback: Payment pending', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'invoice_status' => $data['InvoiceStatus'],
                    'session_id' => request()->session()->getId()
                ]);
                
                // Ensure session is saved before redirect
                $session = request()->session();
                $session->save();
                
                // Use direct URL path to avoid route resolution issues
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('info', 'تم استلام طلبك بنجاح. في انتظار تأكيد الدفع.');
            }
                
        } catch (Exception $ex) {
            Log::error('MyFatoorah callback error: ' . $ex->getMessage(), [
                'request_data' => request()->all(),
                'trace' => $ex->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'حدث خطأ في معالجة الدفع. يرجى التواصل معنا مع رقم الطلب إذا كان متوفراً.');
        }
    }

    /**
     * Display MyFatoorah checkout page with payment methods
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function checkout() {
        try {
            $orderId = request('oid');
            
            if (!$orderId) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الطلب.');
            }

            $order = Order::with('orderItems.service')->find($orderId);
            
            if (!$order) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على الطلب.');
            }

            // Prepare order data for MyFatoorah
            $orderData = [
                'total' => $order->total_amount,
                'currency' => 'SAR'
            ];

            // Get customer ID for card saving
            $customerId = $order->customer_email; // Using email as customer identifier

            // Use user defined field for order tracking
            $userDefinedField = $order->id;

            // Get the enabled gateways at your MyFatoorah account
            $mfObj = new MyFatoorahPaymentEmbedded($this->mfConfig);
            $paymentMethods = $mfObj->getCheckoutGateways((float)$orderData['total'], $orderData['currency'], config('myfatoorah.register_apple_pay'));

            if (empty($paymentMethods['all'])) {
                throw new Exception('noPaymentGateways');
            }

            // Generate MyFatoorah session for embedded payment
            $mfSession = $mfObj->getEmbeddedSession($userDefinedField);

            // Get Environment URL
            $isTest = $this->mfConfig['isTest'];
            $vcCode = $this->mfConfig['vcCode'];

            $countries = MyFatoorah::getMFCountries();
            $jsDomain = ($isTest) ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];

            return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField', 'order'));
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return view('myfatoorah.error', compact('exMessage'));
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

    private function getTestOrderData($orderId) {
        return [
            'total'    => 15,
            'currency' => 'KWD'
        ];
    }

    private function getTestMessage($status, $error) {
        if ($status == 'Paid') {
            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
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
            // Log the current configuration for debugging
            Log::info('MyFatoorah Test Connection - Config:', $this->mfConfig);
            
            if (!$this->mfConfig['apiKey']) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى إدخال مفتاح API أولاً'
                ]);
            }
            
            // Validate vcCode before making the call
            $validVcCodes = ['KWT', 'SAU', 'ARE', 'QAT', 'BHR', 'OMN', 'JOR', 'EGY'];
            if (!in_array($this->mfConfig['vcCode'], $validVcCodes)) {
                Log::error('Invalid vcCode: ' . $this->mfConfig['vcCode']);
                return response()->json([
                    'success' => false,
                    'message' => 'رمز البلد غير صحيح: ' . $this->mfConfig['vcCode'] . '. يجب أن يكون واحداً من: ' . implode(', ', $validVcCodes)
                ]);
            }
            
            $mfObj = new MyFatoorahPaymentEmbedded($this->mfConfig);
            $result = $mfObj->getCheckoutGateways(15, 'SAR', false);
            
            if ($result && isset($result['all']) && !empty($result['all'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح مع MyFatoorah',
                    'payment_methods_count' => count($result['all']),
                    'payment_methods' => $result['all']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في الاتصال مع MyFatoorah'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('MyFatoorah Test Connection Error: ' . $e->getMessage(), [
                'config' => $this->mfConfig,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال: ' . $e->getMessage()
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
            'current_vccode' => $this->mfConfig['vcCode'],
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

            // Create new payment session
            $paymentData = [
                'CustomerName' => $order->customer_name,
                'CustomerEmail' => $order->customer_email,
                'CustomerMobile' => $order->customer_phone,
                'InvoiceValue' => $order->total_amount,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => '+966',
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
            $payment = $mfObj->getInvoiceURL($paymentData, 0, $order->id);
            
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
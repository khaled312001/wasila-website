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
        $this->mfConfig = [
            'apiKey'      => config('myfatoorah.api_key'),
            'isTest'      => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
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

            $orderId  = request('oid') ?: 147;
            $curlData = $this->getPayLoadData($orderId);

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

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
        $order = $this->getTestOrderData($orderId);

        return [
            'CustomerName'       => 'FName LName',
            'InvoiceValue'       => $order['total'],
            'DisplayCurrencyIso' => $order['currency'],
            'CustomerEmail'      => 'test@test.com',
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+965',
            'CustomerMobile'     => '12345678',
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
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

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            $message = $this->getTestMessage($data->InvoiceStatus, $data->InvoiceError);

            $response = ['IsSuccess' => true, 'Message' => $message, 'Data' => $data];
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            $response  = ['IsSuccess' => 'false', 'Message' => $exMessage];
        }
        return response()->json($response);
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
            $orderId = request('oid') ?: 147;
            $order   = $this->getTestOrderData($orderId);

            //You can replace this variable with customer Id in your system
            $customerId = request('customerId');

            //You can use the user defined field if you want to save card
            $userDefinedField = config('myfatoorah.save_card') && $customerId ? "CK-$customerId" : '';

            //Get the enabled gateways at your MyFatoorah acount to be displayed on checkout page
            $mfObj          = new MyFatoorahPaymentEmbedded($this->mfConfig);
            $paymentMethods = $mfObj->getCheckoutGateways($order['total'], $order['currency'], config('myfatoorah.register_apple_pay'));

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

            return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField'));
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

            $status = $data->InvoiceStatus;
            $error  = $data->InvoiceError;
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

    public function showTransaction(Order $order)
    {
        $order->load('orderItems.service');
        
        // Get payment details from MyFatoorah if available
        $paymentDetails = null;
        if ($order->payment_reference) {
            try {
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                $paymentDetails = $mfObj->getPaymentStatus($order->payment_reference, 'PaymentId');
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
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            
            $refundData = [
                'Key' => $order->payment_reference,
                'KeyType' => 'PaymentId',
                'RefundChargeOnCustomer' => false,
                'ServiceChargeOnCustomer' => false,
                'Amount' => $request->amount,
                'Comment' => $request->reason
            ];

            $refundResult = $mfObj->makeRefund($refundData);

            if ($refundResult && $refundResult['IsSuccess']) {
                // Update order status
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded',
                    'refund_amount' => $request->amount,
                    'refund_reason' => $request->reason,
                    'refund_reference' => $refundResult['Data']['RefundReference']
                ]);

                return redirect()->route('admin.myfatoorah.show', $order)
                    ->with('success', 'تم استرداد المبلغ بنجاح');
            } else {
                return back()->with('error', 'فشل في استرداد المبلغ: ' . ($refundResult['Message'] ?? 'خطأ غير معروف'));
            }

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء استرداد المبلغ: ' . $e->getMessage());
        }
    }

    public function testConnection()
    {
        try {
            if (!$this->mfConfig['apiKey']) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى إدخال مفتاح API أولاً'
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
            'currency_iso' => SettingsHelper::get('myfatoorah_currency', 'SAR'),
        ];
        return view('admin.myfatoorah.settings', compact('config'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'is_test' => 'boolean',
            'currency_iso' => 'required|string|size:3',
        ]);

        // Update settings in database
        \App\Models\Setting::set('myfatoorah_api_key', $request->api_key, 'string', 'مفتاح API لبوابة الدفع MyFatoorah');
        \App\Models\Setting::set('myfatoorah_is_test', $request->has('is_test') ? '1' : '0', 'boolean', 'وضع الاختبار لبوابة الدفع');
        \App\Models\Setting::set('myfatoorah_currency', $request->currency_iso, 'string', 'العملة الافتراضية');

        // Clear settings cache
        \App\Models\Setting::clearCache();

        return redirect()->route('admin.myfatoorah.settings')
            ->with('success', 'تم تحديث إعدادات بوابة الدفع بنجاح');
    }

    public function exportTransactions(Request $request)
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

        $transactions = $query->orderBy('created_at', 'desc')->get();

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

    public function retryPayment(Order $order)
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
            $paymentUrl = $payment['invoiceURL'];
            
            return redirect($paymentUrl);
                
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إعادة محاولة الدفع: ' . $e->getMessage());
        }
    }
}
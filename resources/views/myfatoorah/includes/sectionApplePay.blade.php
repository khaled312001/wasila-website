@php
    $hasApplePay = false;
    $apGatewayData = null;
    
    if (isset($paymentMethods['ap'])) {
        if (is_object($paymentMethods['ap'])) {
            $apArray = json_decode(json_encode($paymentMethods['ap']), true);
            $apGatewayData = $apArray['GatewayData'] ?? null;
        } else {
            $apGatewayData = $paymentMethods['ap']['GatewayData'] ?? null;
        }
        
        // Convert GatewayData to array if it's an object
        if (is_object($apGatewayData)) {
            $apGatewayData = json_decode(json_encode($apGatewayData), true);
        }
        
        $hasApplePay = !empty($apGatewayData);
    }
@endphp
@if($hasApplePay)
<!-- Apple Pay Payment Section -->
<div class="mf-payment-section mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        {{ app()->getLocale() === 'ar' ? 'الدفع عبر Apple Pay' : 'Apple Pay Payment' }}
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <div id="mf-ap-element" class="mb-4"></div>
    </div>
</div>

<script>
    // Polyfill for browser object to prevent ReferenceError
    if (typeof browser === 'undefined') {
        window.browser = {
            runtime: {
                getURL: function(path) { return path; },
                sendMessage: function() { return Promise.resolve(); },
                onMessage: { addListener: function() {} }
            }
        };
    }
</script>
<script src="{{$jsDomain}}/applepay/v2/applepay.js"></script>
<script>
var mfApConfig = {
    sessionId: "{{$mfSession->SessionId}}", // Here you add the "SessionId" you receive from the InitiateSession endpoint.
    countryCode: "{{$mfSession->CountryCode}}", // Here, add your country code.
    amount: "{{$apGatewayData['GatewayTotalAmount'] ?? ''}}", // Add the invoice amount.
    currencyCode: "{{$apGatewayData['GatewayCurrency'] ?? 'SAR'}}", // Here, add your currency code.
    cardViewId: "mf-ap-element",
    callback: function(response) {
        console.log('MyFatoorah Apple Pay callback:', response);
        
        // Validate response
        if (!response) {
            console.error('MyFatoorah Apple Pay: Invalid response - response is null or undefined', response);
            const errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
            alert(errorMsg);
            if (typeof hideLoadingOverlay === 'function') {
                hideLoadingOverlay();
            }
            return;
        }
        
        // Validate paymentId
        if (!response.paymentId || response.paymentId === '' || response.paymentId === null || response.paymentId === undefined) {
            console.error('MyFatoorah Apple Pay: Invalid response - paymentId is missing or invalid', response);
            const errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
            alert(errorMsg);
            if (typeof hideLoadingOverlay === 'function') {
                hideLoadingOverlay();
            }
            return;
        }
        
        // Ensure callback is called with proper delay for OTP/success messages
        if (typeof mfCallback === 'function') {
            mfCallback(response);
        } else {
            console.warn('MyFatoorah Apple Pay: mfCallback function not found, redirecting directly');
            // Fallback: show loading and redirect
            if (typeof showLoadingOverlay === 'function') {
                showLoadingOverlay('{{ app()->getLocale() === "ar" ? "جاري معالجة الدفع..." : "Processing payment..." }}');
            }
            setTimeout(function() {
                window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + response.paymentId;
            }, 2000);
        }
    }
};

myFatoorahAP.init(mfApConfig);
</script>
@endif

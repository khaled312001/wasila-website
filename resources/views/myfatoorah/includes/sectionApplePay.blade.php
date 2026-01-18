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
    amount: "{{$paymentMethods['ap']->GatewayData['GatewayTotalAmount']}}", // Add the invoice amount.
    currencyCode: "{{$paymentMethods['ap']->GatewayData['GatewayCurrency']}}", // Here, add your currency code.
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
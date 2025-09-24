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
<script src="{{$jsDomain}}/googlepay/v1/googlepay.js"></script>
<script>
var mfGpConfig = {
    sessionId: "{{$mfSession->SessionId}}", // Here you add the "SessionId" you receive from the InitiateSession endpoint.
    countryCode: "{{$mfSession->CountryCode}}", // Here, add your country code.
    amount: "{{$paymentMethods['gp']->GatewayData['GatewayTotalAmount']}}", // Add the invoice amount.
    currencyCode: "{{$paymentMethods['gp']->GatewayData['GatewayCurrency']}}", // Here, add your currency code.
    cardViewId: "mf-gp-element",
    isProduction: {{Config::get('myfatoorah.test_mode')? 'false' : 'true'}},
    callback: mfCallback
};

myFatoorahGP.init(mfGpConfig);
</script>

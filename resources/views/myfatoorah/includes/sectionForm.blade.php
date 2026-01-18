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
<script src="{{$jsDomain}}/cardview/v2/session.js"></script>
<script>
    var config = {
        countryCode: "{{$mfSession->CountryCode}}", // Here, add your Country Code.
        sessionId: "{{$mfSession->SessionId}}", // Here you add the "SessionId" you receive from InitiateSession Endpoint.
        cardViewId: "mf-form-element",
        // The following style is optional.
        style: {
            hideCardIcons: false,
            direction: "{{App::isLocale('ar') ? 'rtl' : 'ltr'}}",
            cardHeight: {{$userDefinedField ? 190 : 130}},
            tokenHeight: 160,
            input: {
                color: "black",
                fontSize: "13px",
                fontFamily: "sans-serif",
                inputHeight: "32px",
                inputMargin: "0px",
                borderColor: "c7c7c7",
                borderWidth: "1px",
                borderRadius: "8px",
                boxShadow: "",
                placeHolder: {
                    holderName:   "{{__('myfatoorah.holderName')}}",
                    cardNumber:   "{{__('myfatoorah.cardNumber')}}",
                    expiryDate:   "{{__('myfatoorah.expiryDate')}}",
                    securityCode: "{{__('myfatoorah.securityCode')}}",
                }
            },
            label: {
                display: false,
                color: "black",
                fontSize: "13px",
                fontWeight: "normal",
                fontFamily: "sans-serif",
                text: {
                    holderName:   "{{__('myfatoorah.cardHolderNameLabel')}}",
                    cardNumber:   "{{__('myfatoorah.cardNumberLabel')}}",
                    expiryDate:   "{{__('myfatoorah.expiryDateLabel')}}",
                    securityCode: "{{__('myfatoorah.securityCodeLabel')}}",
                },
            },
            error: {
                borderColor: "red",
                borderRadius: "8px",
                boxShadow: "0px",
            },
            text: {
                saveCard: "{{__('myfatoorah.saveCard')}}",
                addCard:  "{{__('myfatoorah.addCard')}}",
                deleteAlert: {
                    tilte:   "{{__('myfatoorah.deleteAlert.title')}}",
                    message: "{{__('myfatoorah.deleteAlert.message')}}",
                    confirm: "{{__('myfatoorah.deleteAlert.confirm')}}",
                    cancel:  "{{__('myfatoorah.deleteAlert.cancel')}}"
                }
            }
        },
    };
    myFatoorah.init(config);

    function submit() {
        // Show loading message
        const submitButton = document.querySelector('.mf-pay-now-btn');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="mf-pay-now-span">{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}</span>';
            
            // Re-enable button after 5 seconds if no response
            setTimeout(function() {
                if (submitButton.disabled) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            }, 5000);
        }
        
        myFatoorah.submit()
            // On success
            .then(function (response) {
                console.log('MyFatoorah submit response:', response);
                
                // Check if response is valid
                if (!response) {
                    throw new Error('{{ app()->getLocale() === "ar" ? "استجابة غير صالحة من بوابة الدفع - لا توجد استجابة" : "Invalid response from payment gateway - no response" }}');
                }
                
                if (!response.paymentId) {
                    throw new Error('{{ app()->getLocale() === "ar" ? "استجابة غير صالحة من بوابة الدفع - معرف الدفع مفقود" : "Invalid response from payment gateway - payment ID missing" }}');
                }
                
                // Call callback function
                if (typeof mfCallback === 'function') {
                    mfCallback(response);
                } else {
                    console.warn('MyFatoorah: mfCallback function not found, redirecting directly');
                    // Fallback: redirect directly if callback is not defined
                    window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + response.paymentId;
                }
            })
            // In case of errors
            .catch(function (error) {
                // Re-enable button
                if (submitButton) {
                    submitButton.disabled = false;
                    const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                    submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
                }
                
                // Show user-friendly error message
                let errorMessage = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع" : "An error occurred processing the payment" }}';
                
                if (error && typeof error === 'string') {
                    errorMessage += ': ' + error;
                } else if (error && error.message) {
                    errorMessage += ': ' + error.message;
                }
                
                alert(errorMessage);
                console.error('MyFatoorah payment error:', error);
            });
    }
</script>

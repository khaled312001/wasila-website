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
                console.log('MyFatoorah submit response type:', typeof response);
                console.log('MyFatoorah submit response keys:', response ? Object.keys(response) : 'null');
                
                // Log all response values for debugging
                if (response) {
                    console.log('MyFatoorah submit response values:', Object.keys(response).map(key => key + ': ' + JSON.stringify(response[key])));
                }
                
                // Check if response is valid
                if (!response) {
                    throw new Error('{{ app()->getLocale() === "ar" ? "استجابة غير صالحة من بوابة الدفع - لا توجد استجابة" : "Invalid response from payment gateway - no response" }}');
                }
                
                // MyFatoorah embedded payment may return different response formats
                // 1. If payment is successful, it should return paymentId or InvoiceId
                // 2. If only card token is returned (sessionId, cardToken), payment is still processing
                
                let paymentId = response.paymentId || response.PaymentId || response.InvoiceId || response.invoiceId || 
                               response.payment_id || response.Payment_ID || 
                               response.InvoiceID || response.Invoice_ID;
                
                // Check all keys for potential ID values
                if (!paymentId) {
                    const keys = Object.keys(response);
                    for (let i = 0; i < keys.length; i++) {
                        const key = keys[i];
                        const value = response[key];
                        // Check if key contains 'id', 'Id', 'ID' and value is not empty
                        if ((key.toLowerCase().includes('id') || key.toLowerCase().includes('payment') || key.toLowerCase().includes('invoice')) && 
                            value && value !== '' && value !== null && value !== undefined) {
                            // Skip sessionId as it's not the paymentId
                            if (key.toLowerCase() === 'sessionid') {
                                continue;
                            }
                            // If value is a number or string, use it
                            if (typeof value === 'string' || typeof value === 'number') {
                                paymentId = String(value);
                                console.log('MyFatoorah: Found potential paymentId in key "' + key + '":', paymentId);
                                break;
                            }
                            // If value is an object, check its properties
                            else if (typeof value === 'object' && value !== null) {
                                const nestedId = value.paymentId || value.PaymentId || value.InvoiceId || value.id || value.Id || value.ID;
                                if (nestedId) {
                                    paymentId = String(nestedId);
                                    console.log('MyFatoorah: Found potential paymentId in nested object "' + key + '":', paymentId);
                                    break;
                                }
                            }
                        }
                    }
                }
                
                // If still no paymentId, check if response is a string (some versions return just the ID)
                if (!paymentId && typeof response === 'string') {
                    paymentId = response;
                }
                
                // If still no paymentId, check if response has a data property
                if (!paymentId && response.data) {
                    paymentId = response.data.paymentId || response.data.PaymentId || response.data.InvoiceId || 
                               response.data.id || response.data.Id || response.data.ID;
                }
                
                // If we only have sessionId/cardToken, this means payment is still processing
                // In MyFatoorah Embedded Payment, when payment is successful, it should return paymentId directly
                // If we only get sessionId/cardToken, the payment might be pending or there's an issue
                if (!paymentId && (response.sessionId || response.cardToken || response.cardIdentifier)) {
                    console.warn('MyFatoorah: Payment response contains only card token, not paymentId. This might indicate payment is still processing.');
                    console.log('MyFatoorah full response:', JSON.stringify(response, null, 2));
                    
                    // In MyFatoorah Embedded Payment, successful payment should return paymentId
                    // If we only get sessionId, it might mean:
                    // 1. Payment is still processing (3D Secure, OTP, etc.)
                    // 2. Payment failed but didn't throw error
                    // 3. Configuration issue
                    
                    // Show user-friendly message
                    const errorMsg = '{{ app()->getLocale() === "ar" ? "لم يكتمل الدفع بعد. يرجى التحقق من حالة الدفع أو المحاولة مرة أخرى. إذا استمرت المشكلة، يرجى التواصل معنا." : "Payment has not completed yet. Please check payment status or try again. If the problem persists, please contact us." }}';
                    alert(errorMsg);
                    
                    // Re-enable button
                    if (submitButton) {
                        submitButton.disabled = false;
                        const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                        submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
                    }
                    
                    hideLoadingOverlay();
                    return;
                }
                
                // Validate paymentId
                if (!paymentId || paymentId === '' || paymentId === null || paymentId === undefined) {
                    console.error('MyFatoorah: Could not extract paymentId from response', {
                        response: response,
                        responseType: typeof response,
                        responseKeys: Object.keys(response || {}),
                        allValues: response ? Object.keys(response).reduce((acc, key) => {
                            acc[key] = response[key];
                            return acc;
                        }, {}) : {}
                    });
                    throw new Error('{{ app()->getLocale() === "ar" ? "استجابة غير صالحة من بوابة الدفع - معرف الدفع مفقود أو غير صالح. يرجى المحاولة مرة أخرى." : "Invalid response from payment gateway - payment ID missing or invalid. Please try again." }}');
                }
                
                // Normalize response to have paymentId
                if (!response.paymentId) {
                    response.paymentId = paymentId;
                }
                
                console.log('MyFatoorah: Extracted paymentId:', paymentId);
                
                // Call callback function
                if (typeof mfCallback === 'function') {
                    mfCallback(response);
                } else {
                    console.warn('MyFatoorah: mfCallback function not found, redirecting directly');
                    // Fallback: redirect directly if callback is not defined
                    window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + paymentId;
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

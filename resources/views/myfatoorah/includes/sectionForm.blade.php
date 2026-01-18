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

    // Function to poll payment status until it's paid or failed
    function pollPaymentStatus(pollUrl, paymentId, attempt) {
        const maxAttempts = 40; // Poll for up to 2 minutes (40 * 3 seconds)
        const pollInterval = 3000; // 3 seconds
        
        if (attempt >= maxAttempts) {
            console.error('Payment polling timeout after', maxAttempts, 'attempts');
            if (typeof hideLoadingOverlay === 'function') {
                hideLoadingOverlay();
            }
            alert('{{ app()->getLocale() === "ar" ? "انتهت مهلة الانتظار. يرجى التحقق من حالة الدفع لاحقاً أو التواصل معنا." : "Polling timeout. Please check payment status later or contact us." }}');
            // Redirect to callback as fallback
            window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + paymentId;
            return;
        }
        
        console.log('Polling payment status, attempt:', attempt + 1, 'of', maxAttempts);
        
        fetch(pollUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment status poll response:', data);
            
            if (data.success && data.isPaid) {
                // Payment is paid! Redirect to confirmation
                console.log('Payment confirmed as paid, redirecting to confirmation');
                
                if (typeof hideLoadingOverlay === 'function') {
                    hideLoadingOverlay();
                }
                
                // Redirect to callback which will then redirect to confirmation
                window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + paymentId;
            } else if (data.isFailed) {
                // Payment failed
                console.log('Payment failed');
                
                if (typeof hideLoadingOverlay === 'function') {
                    hideLoadingOverlay();
                }
                
                // Redirect to callback to show error
                window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + paymentId;
            } else if (data.isPending) {
                // Still pending, continue polling
                setTimeout(() => {
                    pollPaymentStatus(pollUrl, paymentId, attempt + 1);
                }, pollInterval);
            } else {
                // Unknown status, continue polling
                setTimeout(() => {
                    pollPaymentStatus(pollUrl, paymentId, attempt + 1);
                }, pollInterval);
            }
        })
        .catch(error => {
            console.error('Error polling payment status:', error);
            
            // On error, continue polling (might be temporary network issue)
            if (attempt < maxAttempts - 5) {
                setTimeout(() => {
                    pollPaymentStatus(pollUrl, paymentId, attempt + 1);
                }, pollInterval);
            } else {
                // Too many errors, redirect to callback
                console.error('Too many polling errors, redirecting to callback');
                if (typeof hideLoadingOverlay === 'function') {
                    hideLoadingOverlay();
                }
                window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + paymentId;
            }
        });
    }
    
    // Function to execute payment using sessionId
    function executePaymentWithSessionId(sessionId, orderId, originalResponse) {
        console.log('Executing payment with sessionId:', sessionId, 'orderId:', orderId);
        
        fetch('{{ route("myfatoorah.execute-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sessionId: sessionId,
                orderId: orderId
            })
        })
        .then(async response => {
            console.log('Execute payment HTTP response:', response);
            if (!response.ok) {
                // Try to get error message from response
                let errorMessage = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع." : "An error occurred processing the payment." }}';
                try {
                    const errorData = await response.json();
                    if (errorData.message) {
                        errorMessage = errorData.message;
                    } else if (errorData.error && errorData.error.message) {
                        errorMessage = errorData.error.message;
                    }
                } catch (e) {
                    // If response is not JSON, use status text
                    errorMessage = response.statusText || errorMessage;
                }
                throw new Error(errorMessage);
            }
            return response.json();
        })
        .then(data => {
            console.log('Execute payment response:', data);
            
            if (data.success && data.paymentId) {
                // Payment is successful, use the paymentId
                const responseWithPaymentId = {
                    ...originalResponse,
                    paymentId: data.paymentId
                };
                
                console.log('Payment successful, calling mfCallback with paymentId:', data.paymentId);
                
                // Call mfCallback with the paymentId
                if (typeof mfCallback === 'function') {
                    mfCallback(responseWithPaymentId);
                } else {
                    // Fallback: redirect to callback
                    console.log('mfCallback not found, redirecting to callback');
                    window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + data.paymentId;
                }
            } else if (data.keepPolling && data.pollUrl) {
                // Payment is still pending - poll status until it's paid
                console.log('Payment is still pending, starting polling:', data.pollUrl);
                
                // Show message to user
                if (typeof showLoadingOverlay === 'function') {
                    showLoadingOverlay('{{ app()->getLocale() === "ar" ? "جاري انتظار تأكيد الدفع من البنك... يرجى الانتظار" : "Waiting for bank confirmation... Please wait" }}');
                }
                
                // Start polling payment status
                pollPaymentStatus(data.pollUrl, data.paymentId, 0);
            } else if (data.redirect && data.callbackUrl) {
                // Payment failed or needs redirect
                console.log('Payment needs redirect:', data.callbackUrl);
                
                // Show message to user
                if (typeof showLoadingOverlay === 'function') {
                    showLoadingOverlay('{{ app()->getLocale() === "ar" ? "جاري التحقق من حالة الدفع..." : "Verifying payment status..." }}');
                }
                
                // Redirect to callback URL after a brief delay
                setTimeout(() => {
                    window.location.href = data.callbackUrl;
                }, 1000);
            } else if (data.error) {
                // Payment error occurred (e.g., invoice creation error)
                if (typeof hideLoadingOverlay === 'function') {
                    hideLoadingOverlay();
                }
                
                const errorMsg = data.message || '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
                alert(errorMsg);
                
                // Re-enable button
                const submitButton = document.querySelector('.mf-pay-now-btn');
                if (submitButton) {
                    submitButton.disabled = false;
                    const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                    submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
                }
            } else {
                // Payment failed or unknown status
                if (typeof hideLoadingOverlay === 'function') {
                    hideLoadingOverlay();
                }
                
                const errorMsg = data.message || '{{ app()->getLocale() === "ar" ? "لم يكتمل الدفع بعد. يرجى المحاولة مرة أخرى." : "Payment has not completed yet. Please try again." }}';
                alert(errorMsg);
                
                // Re-enable button
                const submitButton = document.querySelector('.mf-pay-now-btn');
                if (submitButton) {
                    submitButton.disabled = false;
                    const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                    submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
                }
            }
        })
        .catch(error => {
            console.error('Error executing payment:', error);
            if (typeof hideLoadingOverlay === 'function') {
                hideLoadingOverlay();
            }
            
            // Default error message
            let errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
            
            // If error is a string (from throw new Error), use it directly
            if (typeof error === 'string') {
                errorMsg = error;
            } else if (error && error.message) {
                // If error has a message, use it
                errorMsg = error.message;
            }
            
            // Show error message
            alert(errorMsg);
            
            // Re-enable button
            const submitButton = document.querySelector('.mf-pay-now-btn');
            if (submitButton) {
                submitButton.disabled = false;
                const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
            }
        });
    }
    
    function submit() {
        // Show loading message
        const submitButton = document.querySelector('.mf-pay-now-btn');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="mf-pay-now-span">{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}</span>';
            
            // Re-enable button after 10 seconds if no response
            setTimeout(function() {
                if (submitButton.disabled) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            }, 10000);
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
                
                // Don't try to extract paymentId from other fields
                // If paymentId is not present, it means payment hasn't completed yet
                // We should use sessionId to execute payment and get paymentId
                
                // If still no paymentId, check if response is a string (some versions return just the ID)
                if (!paymentId && typeof response === 'string') {
                    paymentId = response;
                }
                
                // If still no paymentId, check if response has a data property
                if (!paymentId && response.data) {
                    paymentId = response.data.paymentId || response.data.PaymentId || response.data.InvoiceId || 
                               response.data.id || response.data.Id || response.data.ID;
                }
                
                // If we only have sessionId/cardToken, we need to execute payment to get paymentId
                // In MyFatoorah Embedded Payment, when payment is successful, it should return paymentId directly
                // If we only get sessionId, we need to use it to create invoice and get paymentId
                if (!paymentId && (response.sessionId || response.cardToken || response.cardIdentifier)) {
                    console.log('MyFatoorah: Payment response contains sessionId, executing payment to get paymentId...');
                    console.log('MyFatoorah full response:', JSON.stringify(response, null, 2));
                    
                    // Show loading message
                    if (typeof showLoadingOverlay === 'function') {
                        showLoadingOverlay('{{ app()->getLocale() === "ar" ? "جاري التحقق من حالة الدفع مع MyFatoorah..." : "Verifying payment status with MyFatoorah..." }}');
                    }
                    
                    // Get order ID from URL or page
                    const orderId = @if(isset($order) && $order){{ $order->id }}@else null @endif;
                    let finalOrderId = orderId;
                    if (!finalOrderId) {
                        const urlParams = new URLSearchParams(window.location.search);
                        finalOrderId = urlParams.get('oid');
                    }
                    
                    if (finalOrderId && response.sessionId) {
                        executePaymentWithSessionId(response.sessionId, finalOrderId, response);
                        return;
                    }
                    
                    // If we can't get order ID or sessionId, show error
                    if (typeof hideLoadingOverlay === 'function') {
                        hideLoadingOverlay();
                    }
                    const errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
                    alert(errorMsg);
                    
                    // Re-enable button
                    if (submitButton) {
                        submitButton.disabled = false;
                        const originalText = submitButton.querySelector('.mf-pay-now-span')?.textContent || '{{__("myfatoorah.payNow")}}';
                        submitButton.innerHTML = '<span class="mf-pay-now-span">' + originalText + '</span>';
                    }
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

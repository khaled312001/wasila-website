@if(isset($paymentMethods['cards']))
@foreach($paymentMethods['cards'] as $mfCard)
@php
    // Convert object to array if needed
    if (is_object($mfCard)) {
        $mfCardArray = json_decode(json_encode($mfCard), true);
    } else {
        $mfCardArray = $mfCard;
    }
    
    // Handle both array and object formats
    $mfCardTitle = App::isLocale('ar') 
        ? ($mfCardArray['PaymentMethodAr'] ?? '')
        : ($mfCardArray['PaymentMethodEn'] ?? '');
    $paymentMethodCode = $mfCardArray['PaymentMethodCode'] ?? '';
    $paymentMethodId = $mfCardArray['PaymentMethodId'] ?? '';
    $imageUrl = $mfCardArray['ImageUrl'] ?? '';
    $gatewayData = $mfCardArray['GatewayData'] ?? [];
    
    // Convert GatewayData to array if it's an object
    if (is_object($gatewayData)) {
        $gatewayData = json_decode(json_encode($gatewayData), true);
    }
    
    $gatewayTotalAmount = $gatewayData['GatewayTotalAmount'] ?? '';
    $gatewayCurrency = $gatewayData['GatewayCurrency'] ?? 'SAR';
@endphp
<div class="mf-card-container mf-div-{{$paymentMethodCode}}" onclick="mfCardSubmit('{{$paymentMethodId}}')">
    <div class="mf-row-container">
        <img class="mf-payment-logo" src="{{$imageUrl}}" alt="{{$mfCardTitle}}">
        <span class="mf-payment-text mf-card-title">{{$mfCardTitle}}</span>
    </div>
    <span class="mf-payment-text">
        {{ $gatewayTotalAmount }} {{ $gatewayCurrency }}
    </span>
</div>
@endforeach
@endif

<script>
    function mfCardSubmit(pmid){
        var orderId = @if(isset($order) && $order){{ $order->id }}@else null @endif;
        if (!orderId) {
            // Try to get order ID from URL parameter
            var urlParams = new URLSearchParams(window.location.search);
            orderId = urlParams.get('oid');
        }
        if (!orderId) {
            alert('{{ app()->getLocale() === "ar" ? "خطأ: لم يتم العثور على معرف الطلب" : "Error: Order ID not found" }}');
            return;
        }
        window.location.href = "{{url('myfatoorah')}}?pmid=" + pmid + "&oid=" + orderId;
    }
</script>

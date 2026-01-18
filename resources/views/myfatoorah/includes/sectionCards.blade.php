@if(isset($paymentMethods['cards']) && is_array($paymentMethods['cards']))
@foreach($paymentMethods['cards'] as $mfCard)
@php
    // Handle both array and object formats
    $mfCardTitle = App::isLocale('ar') 
        ? ($mfCard['PaymentMethodAr'] ?? $mfCard->PaymentMethodAr ?? '')
        : ($mfCard['PaymentMethodEn'] ?? $mfCard->PaymentMethodEn ?? '');
    $paymentMethodCode = $mfCard['PaymentMethodCode'] ?? $mfCard->PaymentMethodCode ?? '';
    $paymentMethodId = $mfCard['PaymentMethodId'] ?? $mfCard->PaymentMethodId ?? '';
    $imageUrl = $mfCard['ImageUrl'] ?? $mfCard->ImageUrl ?? '';
    $gatewayData = $mfCard['GatewayData'] ?? $mfCard->GatewayData ?? [];
    $gatewayTotalAmount = is_array($gatewayData) ? ($gatewayData['GatewayTotalAmount'] ?? '') : ($gatewayData->GatewayTotalAmount ?? '');
    $gatewayCurrency = is_array($gatewayData) ? ($gatewayData['GatewayCurrency'] ?? 'SAR') : ($gatewayData->GatewayCurrency ?? 'SAR');
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

@foreach($paymentMethods['cards'] as $mfCard)
@php($mfCardTitle = App::isLocale('ar') ? $mfCard->PaymentMethodAr : $mfCard->PaymentMethodEn)
<div class="mf-card-container mf-div-{{$mfCard->PaymentMethodCode}}" onclick="mfCardSubmit('{{$mfCard->PaymentMethodId}}')">
    <div class="mf-row-container">
        <img class="mf-payment-logo" src="{{$mfCard->ImageUrl}}" alt="{{$mfCardTitle}}">
        <span class="mf-payment-text mf-card-title">{{$mfCardTitle}}</span>
    </div>
    <span class="mf-payment-text">
        {{ $mfCard->GatewayData['GatewayTotalAmount'] }} {{ $mfCard->GatewayData['GatewayCurrency'] }}
    </span>
</div>
@endforeach

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

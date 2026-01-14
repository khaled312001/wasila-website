@php
    $isCustomer = $message->sender_type === 'customer';
    $senderName = $isCustomer ? __('messages.you') : ($message->admin->name ?? __('messages.admin'));
@endphp

<div class="message-wrapper {{ $message->sender_type }}" data-message-id="{{ $message->id }}">
    <div class="message-bubble message-{{ $message->sender_type }}">
        <div class="message-header">
            <span class="message-sender">{{ $senderName }}</span>
            @if($message->order)
            <span class="message-order-ref">• {{ __('messages.order') }} #{{ $message->order->order_number }}</span>
            @endif
        </div>
        @if($message->message)
        <p class="message-content">{{ $message->message }}</p>
        @endif
        
        @if($message->file_path)
            @if($message->isImage())
            <div class="file-preview">
                <img src="{{ $message->file_url }}" alt="{{ $message->file_name }}" class="rounded-lg cursor-pointer" onclick="ChatMessages.openImageModal('{{ $message->file_url }}')">
            </div>
            @else
            <div class="file-attachment">
                <i class="fas {{ $message->isVideo() ? 'fa-video' : ($message->isAudio() ? 'fa-music' : 'fa-file') }}"></i>
                <div class="flex-1">
                    <div class="font-semibold">{{ $message->file_name }}</div>
                    <div class="text-xs opacity-70">{{ $message->formatted_file_size }}</div>
                </div>
                <a href="{{ $message->file_url }}" download class="text-primary-medium hover:text-primary-dark transition-colors">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            @endif
        @endif
        
        <p class="message-time">
            {{ $message->created_at->format('H:i') }}
            @if($isCustomer)
                @if($message->is_read)
                    <i class="fas fa-check-double read"></i>
                @else
                    <i class="fas fa-check"></i>
                @endif
            @endif
        </p>
    </div>
</div>


@php
    $isCustomer = $message->sender_type === 'customer';
    $senderName = $isCustomer ? __('messages.you') : ($message->admin->name ?? __('messages.admin'));
@endphp

<div class="flex {{ $isCustomer ? 'justify-end' : 'justify-start' }}">
    <div class="message-bubble message-{{ $message->sender_type }} p-4">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-semibold opacity-80">{{ $senderName }}</span>
            @if($message->order)
            <span class="text-xs opacity-60">• {{ __('messages.order') }} #{{ $message->order->order_number }}</span>
            @endif
        </div>
        @if($message->message)
        <p class="text-sm mb-2">{{ $message->message }}</p>
        @endif
        
        @if($message->file_path)
            @if($message->isImage())
            <div class="file-preview">
                <img src="{{ $message->file_url }}" alt="{{ $message->file_name }}" class="rounded-lg cursor-pointer" onclick="openImageModal('{{ $message->file_url }}')">
            </div>
            @else
            <div class="file-attachment">
                <i class="fas {{ $message->isVideo() ? 'fa-video' : ($message->isAudio() ? 'fa-music' : 'fa-file') }}"></i>
                <div class="flex-1">
                    <div class="font-semibold">{{ $message->file_name }}</div>
                    <div class="text-xs opacity-70">{{ $message->formatted_file_size }}</div>
                </div>
                <a href="{{ $message->file_url }}" download class="text-primary-medium hover:text-primary-dark">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            @endif
        @endif
        
        <p class="message-time">{{ $message->created_at->format('H:i') }}</p>
    </div>
</div>


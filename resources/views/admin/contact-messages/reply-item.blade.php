<div class="message-bubble {{ $reply->sender_type === 'admin' ? 'message-admin' : 'message-customer' }}">
    <div class="p-4">
        @if($reply->message)
        <div class="{{ $reply->sender_type === 'admin' ? 'text-white' : 'text-gray-800' }} whitespace-pre-wrap">{{ $reply->message }}</div>
        @endif
        
        @if($reply->file_path)
            @if($reply->is_image)
            <div class="file-preview">
                <img src="{{ $reply->file_url }}" alt="{{ $reply->file_name }}" onclick="openImageModal('{{ $reply->file_url }}')" class="cursor-pointer">
            </div>
            @elseif($reply->is_video)
            <div class="file-preview">
                <video controls class="w-full max-h-64 rounded-lg">
                    <source src="{{ $reply->file_url }}" type="{{ $reply->mime_type }}">
                </video>
            </div>
            @elseif($reply->is_audio)
            <div class="file-preview">
                <audio controls class="w-full">
                    <source src="{{ $reply->file_url }}" type="{{ $reply->mime_type }}">
                </audio>
            </div>
            @else
            <div class="file-attachment">
                <i class="fas fa-file"></i>
                <div>
                    <div class="font-semibold">{{ $reply->file_name }}</div>
                    <a href="{{ $reply->file_url }}" download class="text-primary-medium hover:underline">تحميل</a>
                </div>
            </div>
            @endif
        @endif
        
        <div class="message-time {{ $reply->sender_type === 'admin' ? 'text-white/70' : 'text-gray-500' }}">
            {{ $reply->created_at->format('Y-m-d H:i') }}
        </div>
    </div>
</div>


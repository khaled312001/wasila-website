@extends('customer.layouts.app')

@section('title', __('messages.messages'))
@section('page-title', __('messages.messages'))
@section('page-subtitle', __('messages.communicate_with_admin'))

@push('styles')
<style>
    .message-bubble {
        max-width: 70%;
        word-wrap: break-word;
    }
    .message-customer {
        background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
        color: white;
        margin-left: auto;
        border-radius: 1rem 1rem 0 1rem;
    }
    .message-admin {
        background: #f3f4f6;
        color: #1f2937;
        margin-right: auto;
        border-radius: 1rem 1rem 1rem 0;
    }
</style>
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Messages List -->
    <div class="lg:col-span-2">
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.conversation') }}</h2>
            
            @if(request('order_id'))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    {{ __('messages.filtering_by_order') }}: 
                    <a href="{{ route('customer.orders.show', request('order_id')) }}" class="font-semibold underline">
                        #{{ \App\Models\Order::find(request('order_id'))->order_number ?? request('order_id') }}
                    </a>
                </p>
            </div>
            @endif

            <div class="space-y-4 max-h-96 overflow-y-auto mb-6" id="messagesContainer">
                @forelse($messages as $message)
                <div class="flex {{ $message->sender_type === 'customer' ? 'justify-end' : 'justify-start' }}">
                    <div class="message-bubble message-{{ $message->sender_type }} p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-semibold opacity-80">
                                @if($message->sender_type === 'admin')
                                    {{ $message->admin->name ?? __('messages.admin') }}
                                @else
                                    {{ __('messages.you') }}
                                @endif
                            </span>
                            @if($message->order)
                            <span class="text-xs opacity-60">• {{ __('messages.order') }} #{{ $message->order->order_number }}</span>
                            @endif
                        </div>
                        <p class="text-sm">{{ $message->message }}</p>
                        <p class="text-xs opacity-60 mt-2">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                    <p class="text-gray-600">{{ __('messages.no_messages_yet') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Send Message Form -->
            <form id="messageForm" class="border-t border-gray-200 pt-4">
                @csrf
                <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                <div class="flex gap-3">
                    <textarea 
                        name="message" 
                        id="messageInput"
                        rows="3"
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                        placeholder="{{ __('messages.type_your_message') }}"
                        required></textarea>
                    <button type="submit" class="btn-primary px-6 py-2 self-end">
                        {{ __('messages.send') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Sidebar -->
    <div>
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.filter_by_order') }}</h2>
            <div class="space-y-2">
                <a href="{{ route('customer.messages.index') }}" 
                   class="block px-4 py-2 rounded-lg {{ !request('order_id') ? 'bg-primary-medium text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.all_messages') }}
                </a>
                @foreach(auth('customer')->user()->orders as $order)
                <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" 
                   class="block px-4 py-2 rounded-lg {{ request('order_id') == $order->id ? 'bg-primary-medium text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __('messages.order') }} #{{ $order->order_number }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    const messageText = messageInput.value.trim();
    
    if (!messageText) return;
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = '{{ __('messages.sending') }}...';
    
    try {
        const response = await fetch('{{ route("customer.messages.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Add new message to container
            const messagesContainer = document.getElementById('messagesContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex justify-end';
            messageDiv.innerHTML = `
                <div class="message-bubble message-customer p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold opacity-80">{{ __('messages.you') }}</span>
                    </div>
                    <p class="text-sm">${messageText}</p>
                    <p class="text-xs opacity-60 mt-2">${new Date().toLocaleString('ar-SA')}</p>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            messageInput.value = '';
        } else {
            alert(data.message || '{{ __('messages.error_occurred') }}');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('{{ __('messages.error_sending_message') }}');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// Auto-scroll to bottom
document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;
</script>
@endpush


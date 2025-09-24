@extends('admin.layouts.app')

@section('title', 'عرض الرسالة')
@section('page-title', 'عرض الرسالة')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center">
        <a href="{{ route('admin.contact-messages.index') }}" 
           class="inline-flex items-center text-primary-medium hover:text-primary-dark transition-colors duration-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            العودة إلى قائمة الرسائل
        </a>
    </div>

    <!-- Message Details -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Message Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-primary-medium flex items-center justify-center">
                            <span class="text-white font-medium text-lg">
                                {{ substr($contactMessage->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="mr-4">
                        <h1 class="text-xl font-semibold text-gray-900">{{ $contactMessage->name }}</h1>
                        <p class="text-sm text-gray-600">{{ $contactMessage->email }}</p>
                        @if($contactMessage->phone)
                        <p class="text-sm text-gray-600">{{ $contactMessage->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-4 space-x-reverse">
                    <div class="text-sm text-gray-500">
                        <div>{{ $contactMessage->formatted_created_at }}</div>
                        <div>{{ $contactMessage->time_ago }}</div>
                    </div>
                    @if($contactMessage->is_read)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        مقروءة
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        غير مقروءة
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="px-6 py-6">
            @if($contactMessage->subject)
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-2">الموضوع</h2>
                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $contactMessage->subject }}</p>
            </div>
            @endif

            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-2">محتوى الرسالة</h2>
                <div class="text-gray-700 bg-gray-50 p-6 rounded-lg whitespace-pre-wrap leading-relaxed">
                    {{ $contactMessage->message }}
                </div>
            </div>
        </div>

        <!-- Message Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center space-x-4 space-x-reverse mb-4 sm:mb-0">
                    @if($contactMessage->is_read)
                    <button onclick="markAsUnread({{ $contactMessage->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md shadow-sm text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        تمييز كغير مقروءة
                    </button>
                    @else
                    <button onclick="markAsRead({{ $contactMessage->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تمييز كمقروءة
                    </button>
                    @endif
                    
                    <a href="mailto:{{ $contactMessage->email }}" 
                       class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        الرد بالبريد الإلكتروني
                    </a>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <button onclick="deleteMessage({{ $contactMessage->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        حذف الرسالة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات الرسالة</h3>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">تاريخ الإرسال</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $contactMessage->formatted_created_at }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">وقت الإرسال</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $contactMessage->time_ago }}</dd>
            </div>
            @if($contactMessage->read_at)
            <div>
                <dt class="text-sm font-medium text-gray-500">تاريخ القراءة</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $contactMessage->read_at->format('Y-m-d H:i') }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500">حالة الرسالة</dt>
                <dd class="mt-1">
                    @if($contactMessage->is_read)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        مقروءة
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        غير مقروءة
                    </span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد الحذف</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف هذه الرسالة؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    حذف
                </button>
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let messageToDelete = null;

function markAsRead(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAsUnread(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteMessage(messageId) {
    messageToDelete = messageId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    messageToDelete = null;
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (messageToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/contact-messages/${messageToDelete}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush

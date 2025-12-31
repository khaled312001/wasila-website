@extends('admin.layouts.app')

@section('title', 'رسائل العميل')
@section('page-title', 'رسائل العميل: ' . $customer->name)

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-primary-dark">رسائل {{ $customer->name }}</h2>
                <p class="text-gray-600">{{ $customer->email }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-medium hover:text-primary-dark">
                العودة للعميل
            </a>
        </div>
    </div>
    
    <div class="p-6 space-y-4">
        @forelse($messages as $message)
        <div class="border border-gray-200 rounded-lg p-4 {{ $message->sender_type === 'admin' ? 'bg-blue-50' : 'bg-gray-50' }}">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="font-semibold">{{ $message->sender_type === 'admin' ? 'الإدارة' : $customer->name }}</p>
                    <p class="text-sm text-gray-600">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <p class="text-gray-900">{{ $message->message }}</p>
        </div>
        @empty
        <p class="text-center text-gray-500 py-8">لا توجد رسائل</p>
        @endforelse
    </div>
    
    @if($messages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection


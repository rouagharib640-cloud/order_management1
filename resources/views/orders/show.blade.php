@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Order Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            
            <div class="flex space-x-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                    @elseif($order->status == 'processing') bg-purple-100 text-purple-800
                    @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
                
                @if($order->isPending())
                    <form id="confirm-form-detail" 
                          action="{{ route('orders.confirm', $order) }}" 
                          method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" 
                                onclick="confirmAction('Are you sure you want to confirm this order? This will send a confirmation email to the customer.', 'confirm-form-detail')"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-check-circle mr-2"></i>Confirm Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg border">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                                    <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">${{ number_format($item->price, 2) }} each</p>
                                    <p class="font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Order Summary -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="space-y-2">
                        <p><strong class="text-gray-700">Name:</strong> {{ $order->customer_name }}</p>
                        <p><strong class="text-gray-700">Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong class="text-gray-700">Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h2>
                    <div class="space-y-3">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-1">Shipping Address</h3>
                            <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 mb-1">Billing Address</h3>
                            <p class="text-sm text-gray-600">{{ $order->billing_address }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="font-bold text-gray-900">Total Amount</span>
                            <span class="font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                @if($order->notes)
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Order Notes</h2>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('orders.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection
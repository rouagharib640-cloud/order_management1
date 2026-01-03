@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('orders.index') }}" 
                   class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order Details</h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Complete information for order {{ $order->order_number }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @php
                $statusConfig = [
                    'pending' => ['color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300', 'icon' => 'fas fa-clock'],
                    'confirmed' => ['color' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300', 'icon' => 'fas fa-check-circle'],
                    'processing' => ['color' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300', 'icon' => 'fas fa-cog'],
                    'shipped' => ['color' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300', 'icon' => 'fas fa-shipping-fast'],
                    'delivered' => ['color' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300', 'icon' => 'fas fa-box-check'],
                    'cancelled' => ['color' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300', 'icon' => 'fas fa-times-circle'],
                ];
                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
            @endphp
            
            <span class="status-badge {{ $config['color'] }} px-4 py-2">
                <i class="{{ $config['icon'] }}"></i>
                {{ ucfirst($order->status) }}
            </span>
            
            @if($order->isPending())
                <form id="confirm-form-detail" action="{{ route('orders.confirm', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" 
                            onclick="confirmAction('Confirm this order? A confirmation email will be sent.', 'confirm-form-detail')"
                            class="btn-primary flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Confirm Order
                    </button>
                </form>
            @endif
            
            <button class="btn-secondary flex items-center gap-2">
                <i class="fas fa-print"></i>
                Print
            </button>
        </div>
    </div>
    
    <!-- Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-card">
            <div class="text-sm text-gray-600 dark:text-gray-400">Order Value</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($order->total_amount, 2) }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-card">
            <div class="text-sm text-gray-600 dark:text-gray-400">Items</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $order->items->count() }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-card">
            <div class="text-sm text-gray-600 dark:text-gray-400">Order Date</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $order->created_at->format('M d') }}</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-card">
            <div class="text-sm text-gray-600 dark:text-gray-400">Customer Since</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $order->created_at->format('Y') }}</div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-shopping-basket text-primary-500"></i>
                        Order Items ({{ $order->items->count() }})
                    </h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total: ${{ number_format($order->total_amount, 2) }}</span>
                </div>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-gray-800 dark:to-gray-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-primary-500"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">SKU: {{ strtoupper(substr(md5($item->product_name), 0, 8)) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-4">
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900 dark:text-white">${{ number_format($item->price, 2) }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">each</div>
                                    </div>
                                    <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900 dark:text-white">× {{ $item->quantity }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">qty</div>
                                    </div>
                                    <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg text-gray-900 dark:text-white">${{ number_format($item->subtotal, 2) }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">subtotal</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-6">
                    <i class="fas fa-history text-primary-500"></i>
                    Order Timeline
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="absolute top-10 bottom-0 left-1/2 w-0.5 bg-gray-200 dark:bg-gray-700 transform -translate-x-1/2"></div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">Order Placed</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">Order {{ $order->order_number }} was successfully placed.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 {{ $order->status !== 'pending' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-100 dark:bg-gray-800' }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $order->status !== 'pending' ? 'fa-check-circle text-blue-600 dark:text-blue-400' : 'fa-clock text-gray-400' }}"></i>
                            </div>
                            <div class="absolute top-10 bottom-0 left-1/2 w-0.5 bg-gray-200 dark:bg-gray-700 transform -translate-x-1/2"></div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">Order Confirmed</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->status !== 'pending' ? 'Confirmed' : 'Pending confirmation' }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $order->status !== 'pending' ? 'Order was confirmed and confirmation email sent.' : 'Awaiting confirmation...' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Add more timeline items as needed -->
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                    <i class="fas fa-user text-primary-500"></i>
                    Customer Information
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-blue-500 rounded-full flex items-center justify-center text-white text-lg font-medium">
                            {{ substr($order->customer_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer_email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</label>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_phone ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer ID</label>
                            <p class="font-medium text-gray-900 dark:text-white">CUST-{{ strtoupper(substr(md5($order->customer_email), 0, 8)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                    <i class="fas fa-truck text-primary-500"></i>
                    Shipping Information
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 block">Shipping Address</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 block">Billing Address</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $order->billing_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-6">
                    <i class="fas fa-receipt text-primary-500"></i>
                    Order Summary
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="font-medium">${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_cost, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                        <span class="font-medium">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tax</span>
                        <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            @if($order->notes)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-2xl shadow-card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                        <i class="fas fa-sticky-note text-yellow-600"></i>
                        Order Notes
                    </h2>
                    <p class="text-gray-700 dark:text-gray-300 italic">"{{ $order->notes }}"</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .order-timeline::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #e5e7eb, #9ca3af, #e5e7eb);
        z-index: 0;
    }
    
    .dark .order-timeline::before {
        background: linear-gradient(to bottom, #374151, #6b7280, #374151);
    }
</style>
@endsection
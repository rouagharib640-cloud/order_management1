@extends('layouts.app')

@section('title', 'Order Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Order Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and track all customer orders in one place</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('queue.dashboard') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition-all duration-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fas fa-tasks"></i>
                Queue Monitor
            </a>
        </div>
    </div>
    
    <!-- Search and Filter Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 hover:shadow-card-hover transition-shadow">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-filter text-primary-500"></i>
                Search & Filter
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Find orders quickly with advanced filters</p>
        </div>
        
        <form method="GET" action="{{ route('orders.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Order ID, Name, Email..."
                           class="pl-10 w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <select name="status" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Range -->
                <div>
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                
                <div>
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 hover:shadow-lg flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Apply Filters
                </button>
                
                <button type="button" 
                        onclick="resetFilters()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition-all duration-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-redo"></i>
                    Reset All
                </button>
            </div>
        </form>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Card -->
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card hover:shadow-card-hover transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Pending
                    </p>
                    <p class="text-2xl font-bold mt-2 dark:text-white">{{ App\Models\Order::where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 p-3 rounded-xl">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
        
        <!-- Confirmed Card -->
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card hover:shadow-card-hover transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Confirmed
                    </p>
                    <p class="text-2xl font-bold mt-2 dark:text-white">{{ App\Models\Order::where('status', 'confirmed')->count() }}</p>
                </div>
                <div class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>
        
        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card hover:shadow-card-hover transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Revenue
                    </p>
                    <p class="text-2xl font-bold mt-2 dark:text-white">${{ number_format(App\Models\Order::sum('total_amount'), 2) }}</p>
                </div>
                <div class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 p-3 rounded-xl">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
        </div>
        
        <!-- Average Card -->
        <div class="bg-gradient-to-br from-purple-50 to-violet-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card hover:shadow-card-hover transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Average
                    </p>
                    <p class="text-2xl font-bold mt-2 dark:text-white">
                        ${{ number_format(App\Models\Order::avg('total_amount') ?? 0, 2) }}
                    </p>
                </div>
                <div class="bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 p-3 rounded-xl">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Showing {{ $orders->firstItem() }}-{{ $orders->lastItem() }} of {{ $orders->total() }} orders</p>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Order ID
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                        {{ substr($order->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->customer_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-lg text-gray-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status === 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($order->status === 'confirmed')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <i class="fas fa-check-circle mr-1"></i>Confirmed
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        <i class="fas fa-cog mr-1"></i>Processing
                                    </span>
                                @elseif($order->status === 'shipped')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                        <i class="fas fa-shipping-fast mr-1"></i>Shipped
                                    </span>
                                @elseif($order->status === 'delivered')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        <i class="fas fa-box-check mr-1"></i>Delivered
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        <i class="fas fa-times-circle mr-1"></i>Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($order->isPending())
                                        <form id="confirm-form-{{ $order->id }}" 
                                              action="{{ route('orders.confirm', $order) }}" 
                                              method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" 
                                                    onclick="confirmAction('Confirm order {{ $order->order_number }}? This will send a confirmation email.', 'confirm-form-{{ $order->id }}')"
                                                    class="p-2 rounded-lg bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 transition-colors"
                                                    title="Confirm Order">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                        <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No orders found</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                    </div>
                    
                    <div class="flex items-center gap-2">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Add this import
use Illuminate\Support\Facades\DB;   // Optional but good to have

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        
        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // This is correct for Laravel 10
        $orders = $query->paginate(15)->withQueryString();
        
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        return view('orders.index', compact('orders', 'statuses'));
    }
    
    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }
    
    public function confirm(Order $order)
    {
        // Check if order is already confirmed
        if ($order->status === 'confirmed') {
            return back()->with('error', 'Order is already confirmed.');
        }
        
        // Update order status
        $order->update(['status' => 'confirmed']);
        
        // Send confirmation email
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            
            return back()->with('success', 'Order confirmed successfully and confirmation email sent.');
        } catch (\Exception $e) {
            // Log the error but still confirm the order
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
            
            return back()->with('warning', 'Order confirmed but failed to send email.');
        }
    }
}
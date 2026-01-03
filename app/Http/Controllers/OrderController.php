<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use App\Jobs\SendOrderConfirmationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;   

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
        // Vérifier si la commande est déjà confirmée
        if ($order->status === 'confirmed') {
            return back()->with('error', 'Order is already confirmed.');
        }
        
        // Vérifier si la commande peut être confirmée
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Only pending or processing orders can be confirmed.');
        }
        
        // Mettre à jour le statut de la commande
        $order->update(['status' => 'confirmed']);
        
        try {
            // Dispatch le job dans la queue
            SendOrderConfirmationEmail::dispatch($order)
                // ->onQueue('emails') // Optionnel : spécifier une queue dédiée
                ->delay(now()->addSeconds(5)); // Optionnel : délai avant envoi
            
            // Message de succès
            return back()->with('success', 'Order confirmed successfully! Confirmation email is being sent.');
            
        } catch (\Exception $e) {
            // Log l'erreur mais la commande reste confirmée
            Log::error('Failed to dispatch confirmation email job: ' . $e->getMessage());
            
            return back()->with('warning', 'Order confirmed but email system encountered an issue.');
        }
    }
}
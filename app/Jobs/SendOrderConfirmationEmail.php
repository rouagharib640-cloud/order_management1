<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $tries = 3;
    public $retryAfter = 60;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        try {
            // Testez d'abord avec un email simple
            Mail::raw('Order Confirmation Test', function($message) {
                $message->to($this->order->customer_email)
                        ->subject('Order Confirmation - ' . $this->order->order_number);
            });
            
            Log::info('Email sent to: ' . $this->order->customer_email);
            
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
            throw $e; // Relancer l'exception pour les retries
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Job failed for order ' . $this->order->order_number . ': ' . $exception->getMessage());
    }
}
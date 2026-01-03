<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::resource('orders', OrderController::class)->only(['index', 'show']);
Route::patch('/orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
// Dashboard des queues
Route::get('/queue/dashboard', function() {
    // Statistiques des queues
    $stats = [
        'pending_jobs' => \DB::table('jobs')->count(),
        'failed_jobs' => \DB::table('failed_jobs')->count(),
        'queue_connection' => config('queue.default'),
    ];
    
    // Derniers jobs
    $recentJobs = \DB::table('jobs')
        ->select('id', 'queue', 'attempts', 'created_at')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
        
    // Derniers échecs
    $recentFailures = \DB::table('failed_jobs')
        ->select('id', 'connection', 'queue', 'failed_at')
        ->orderBy('failed_at', 'desc')
        ->limit(10)
        ->get();
        
    return view('queue.dashboard', compact('stats', 'recentJobs', 'recentFailures'));
})->name('queue.dashboard');

// Routes pour les actions sur les queues
Route::post('/queue/retry-failed', function() {
    \Artisan::call('queue:retry', ['id' => 'all']);
    return back()->with('success', 'All failed jobs have been retried.');
})->name('queue.retry-failed');

Route::post('/queue/clear', function() {
    \DB::table('jobs')->truncate();
    return back()->with('success', 'Queue cleared.');
})->name('queue.clear');
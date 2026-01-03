@extends('layouts.app')

@section('title', 'Queue Dashboard')

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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Queue Dashboard</h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Monitor background jobs and email processing</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <p class="text-xs text-gray-500 dark:text-gray-400">Connection</p>
                <p class="font-semibold text-lg">{{ strtoupper($stats['queue_connection']) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending Jobs -->
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Pending Jobs
                    </p>
                    <p class="text-3xl font-bold mt-2 dark:text-white">{{ $stats['pending_jobs'] }}</p>
                </div>
                <div class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 p-3 rounded-xl">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
        
        <!-- Failed Jobs -->
        <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Failed Jobs
                    </p>
                    <p class="text-3xl font-bold mt-2 dark:text-white">{{ $stats['failed_jobs'] }}</p>
                </div>
                <div class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 p-3 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>
        
        <!-- Queue Status -->
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 shadow-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        Queue Status
                    </p>
                    <p class="text-3xl font-bold mt-2 dark:text-white">
                        {{ $stats['pending_jobs'] > 0 ? 'Active' : 'Idle' }}
                    </p>
                </div>
                <div class="bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 p-3 rounded-xl">
                    <i class="fas fa-server text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Queue Actions</h2>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('queue.retry-failed') }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Retry all failed jobs?')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 hover:shadow-lg flex items-center gap-2">
                    <i class="fas fa-redo mr-2"></i>Retry All Failed Jobs
                </button>
            </form>
            
            <form method="POST" action="{{ route('queue.clear') }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Clear all pending jobs? This cannot be undone.')"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 hover:shadow-lg flex items-center gap-2">
                    <i class="fas fa-trash mr-2"></i>Clear All Pending Jobs
                </button>
            </form>
            
            <a href="http://localhost:8025" target="_blank"
               class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 hover:shadow-lg flex items-center gap-2">
                <i class="fas fa-envelope mr-2"></i>Open Mailpit
            </a>
        </div>
    </div>
    
    <!-- Recent Jobs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Jobs</h2>
        </div>
        
        @if($recentJobs->isEmpty())
            <div class="px-6 py-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 dark:text-gray-400">No pending jobs</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Queue</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Attempts</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($recentJobs as $job)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">#{{ $job->id }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $job->queue ?? 'default' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <span class="{{ $job->attempts > 0 ? 'text-yellow-600' : 'text-green-600' }}">
                                        {{ $job->attempts }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($job->created_at)->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    <!-- Failed Jobs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Failed Jobs</h2>
        </div>
        
        @if($recentFailures->isEmpty())
            <div class="px-6 py-8 text-center">
                <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                <p class="text-gray-600 dark:text-gray-400">No failed jobs</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Queue</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Failed At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($recentFailures as $failure)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">#{{ $failure->id }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        {{ $failure->queue ?? 'default' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($failure->failed_at)->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    <!-- Queue Worker Instructions -->
    <div class="bg-gradient-to-br from-purple-50 to-violet-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-card p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-terminal"></i>
            Queue Worker Instructions
        </h2>
        
        <div class="space-y-4">
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-green-400">$</span>
                    <button onclick="copyToClipboard('php artisan queue:work')" 
                            class="text-gray-400 hover:text-white">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <div>php artisan queue:work</div>
                <div class="text-gray-500 text-xs mt-2">Start processing jobs</div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded-lg">
                    <div class="font-medium text-gray-900 dark:text-white mb-1">Common Commands:</div>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li><code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">queue:work --once</code> - Process one job</li>
                        <li><code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">queue:retry all</code> - Retry all failed jobs</li>
                        <li><code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">queue:failed</code> - List failed jobs</li>
                    </ul>
                </div>
                
                <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded-lg">
                    <div class="font-medium text-gray-900 dark:text-white mb-1">Monitoring:</div>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li><i class="fas fa-external-link-alt text-xs mr-1"></i> Mailpit: <a href="http://localhost:8025" target="_blank" class="text-blue-500 hover:underline">localhost:8025</a></li>
                        <li><i class="fas fa-database text-xs mr-1"></i> Jobs table: <code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">jobs</code></li>
                        <li><i class="fas fa-bug text-xs mr-1"></i> Failed jobs: <code class="bg-gray-200 dark:bg-gray-800 px-1 rounded">failed_jobs</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Copied to clipboard: ' + text);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endsection
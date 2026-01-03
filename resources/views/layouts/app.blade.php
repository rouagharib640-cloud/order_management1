<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OrderFlow Pro')</title>
    
    <!-- Tailwind CSS avec configuration moderne -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                    boxShadow: {
                        'card': '0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01)',
                        'card-hover': '0 20px 40px -10px rgba(0, 0, 0, 0.1), 0 10px 20px -5px rgba(0, 0, 0, 0.04)',
                    },
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.7);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-primary {
            @apply bg-primary-500 hover:bg-primary-600 text-white font-medium py-2.5 px-5 rounded-lg transition-all duration-200 hover:shadow-lg;
        }
        
        .btn-secondary {
            @apply bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 px-5 rounded-lg transition-all duration-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="glass-effect border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="gradient-bg w-10 h-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                    <div>
                        <a href="{{ route('orders.index') }}" class="text-xl font-bold bg-gradient-to-r from-primary-600 to-blue-500 bg-clip-text text-transparent">
                            OrderFlow Pro
                        </a>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Management System</p>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Orders</p>
                            <p class="font-semibold text-lg">{{ App\Models\Order::count() }}</p>
                        </div>
                        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                            <p class="font-semibold text-lg text-warning">{{ App\Models\Order::where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-moon text-gray-600 dark:text-yellow-300"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 animate-fade-in">
                    <div class="bg-success/10 border border-success/20 text-success-800 rounded-xl p-4 flex items-center gap-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 animate-fade-in">
                    <div class="bg-danger/10 border border-danger/20 text-danger-800 rounded-xl p-4 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="mb-6 animate-fade-in">
                    <div class="bg-warning/10 border border-warning/20 text-warning-800 rounded-xl p-4 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span class="font-medium">{{ session('warning') }}</span>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- JavaScript -->
    <script>
        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
        
        // Load saved theme
        if (localStorage.getItem('theme') === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Confirm action with sweet alert style
        function confirmAction(message, formId) {
            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
        }
        
        // Reset filters
        function resetFilters() {
            document.querySelectorAll('input[type="text"], input[type="date"], select').forEach(element => {
                element.value = '';
            });
            window.location.href = window.location.pathname;
        }
        
        // Animate table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('animate-slide-up');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar-gradient text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold text-white">Votação</h1>
                <p class="text-gray-300 text-sm">Conecte-se com amigos</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('friends.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('friends.*') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Amigos</span>
                </a>
                <a href="{{ route('groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('groups.*') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="users-2" class="w-5 h-5"></i>
                    <span>Grupos</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-gray-300 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-red-300">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i data-lucide="bell" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
    <!-- Guest Layout -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-800">Votação</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Registrar</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-4 mt-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 mt-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
    @endauth

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

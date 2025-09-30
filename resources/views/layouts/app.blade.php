<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Choicefy')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1); }
        
        /* Remove outline padrão dos inputs */
        input:focus, textarea:focus, select:focus {
            outline: none !important;
        }

        /* Remove outline dos botões */
        button:focus {
            outline: none !important;
        }

        /* Publish button hold animation */
        #publishButton {
            position: relative;
            overflow: hidden;
        }

        #progressBar {
            background: linear-gradient(90deg, #10b981, #059669);
            transition: transform 2s ease-out;
        }

        #publishButton:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <div class="flex min-h-screen">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-900 h-screen fixed left-0 top-0 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Choicefy" class="w-16">
                </div>
                
                <!-- Close button for mobile -->
                <button id="close-sidebar" class="lg:hidden absolute right-4 top-6 p-2 text-gray-400 hover:text-white">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="px-4 py-4">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input placeholder="Search" class="w-full pl-10 pr-4 py-2 bg-gray-800 border-0 rounded-lg text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500" type="text">
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 px-4 py-2">
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors cursor-pointer {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="ri-dashboard-line text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('friends.index') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors cursor-pointer {{ request()->routeIs('friends.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="ri-user-line text-lg"></i>
                        <span class="font-medium">Amigos</span>
                    </a>
                    <a href="{{ route('groups.index') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors cursor-pointer {{ request()->routeIs('groups.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="ri-group-line text-lg"></i>
                        <span class="font-medium">Grupos</span>
                    </a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                            <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <button class="p-1 text-gray-400 hover:text-white">
                        <i class="ri-more-2-line text-lg"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-gray-800 hover:text-red-300 cursor-pointer">
                        <i class="ri-logout-box-line text-lg"></i>
                        <span class="font-medium">Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-100 px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button id="open-sidebar" class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                            <i class="ri-menu-line text-xl"></i>
                        </button>
                        <h2 class="text-lg lg:text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex flex-1 max-w-md">
                            <div class="relative">
                                <i class="ri-search-line absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input placeholder="Search your course..." class="w-full pl-12 pr-4 py-3 bg-gray-50 border-0 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white" type="text">
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 lg:space-x-4">
                            <button class="p-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="ri-notification-3-line text-xl"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="ri-settings-3-line text-xl"></i>
                            </button>
                            <div class="hidden lg:flex items-center space-x-3 cursor-pointer">
                                <div class="w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-start space-x-3">
                        <i class="ri-check-circle-line text-green-600 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="font-medium text-green-800">Sucesso!</h4>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start space-x-3">
                        <i class="ri-error-warning-line text-red-600 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="font-medium text-red-800">Erro!</h4>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
    <!-- Guest Layout -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-graduation-cap-fill text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Choicefy</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 text-sm">Entrar</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-4 py-2 text-sm">Registrar</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="bg-gray-50 min-h-screen">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-start space-x-3 mx-4 mt-4">
                <i class="ri-check-circle-line text-green-600 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-medium text-green-800">Sucesso!</h4>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start space-x-3 mx-4 mt-4">
                <i class="ri-error-warning-line text-red-600 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-medium text-red-800">Erro!</h4>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    @endauth

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Open sidebar
            if (openSidebarBtn) {
                openSidebarBtn.addEventListener('click', openSidebar);
            }

            // Close sidebar
            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking on navigation links (mobile)
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>

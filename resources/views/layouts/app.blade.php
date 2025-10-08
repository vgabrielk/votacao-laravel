<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Choicefy')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100vw !important;
        }
        html {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100vw !important;
        }
        
        /* FORÇAR QUE NÃO TENHA SCROLL HORIZONTAL NO BODY */
        * {
            box-sizing: border-box;
        }
        
        body, html, main, div {
            overflow-x: hidden !important;
        }
        
        /* EXCEÇÃO PARA A TABELA - APENAS ELA PODE TER SCROLL HORIZONTAL */
        div[style*="overflow-x: auto"] {
            overflow-x: auto !important;
        }
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

        /* Modal Styles */
        .modal {
            backdrop-filter: blur(4px);
        }
        
        /* Table scroll fix - SOLUÇÃO DEFINITIVA */
        .overflow-x-auto {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        /* Garantir que o body não tenha scroll horizontal */
        body, html {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
        
        /* Garantir que o main não cause overflow */
        main {
            overflow-x: hidden !important;
            max-width: 100% !important;
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
                    <a href="{{ route('polls.index') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors cursor-pointer {{ request()->routeIs('polls.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="ri-survey-line text-lg"></i>
                        <span class="font-medium">Enquetes</span>
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
                   
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8 overflow-y-auto overflow-x-hidden max-w-full">
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

        // Global Modal Functions
        function openConfirmationModal(title, message, confirmText, confirmAction) {
            const modal = document.getElementById('globalConfirmationModal');
            const modalTitle = modal.querySelector('#modalTitle');
            const modalMessage = modal.querySelector('#modalMessage');
            const confirmButton = modal.querySelector('#confirmButton');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            confirmButton.textContent = confirmText;
            
            // Store the action to execute
            confirmButton.onclick = function() {
                if (confirmAction) confirmAction();
                closeConfirmationModal();
            };
            
            modal.classList.remove('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('globalConfirmationModal').classList.add('hidden');
        }

        function openSuccessModal(title, message) {
            const modal = document.getElementById('globalSuccessModal');
            const modalTitle = modal.querySelector('#successModalTitle');
            const modalMessage = modal.querySelector('#successModalMessage');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            
            modal.classList.remove('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('globalSuccessModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.id === 'globalConfirmationModal') {
                closeConfirmationModal();
            }
            if (event.target.id === 'globalSuccessModal') {
                closeSuccessModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeConfirmationModal();
                closeSuccessModal();
            }
        });
    </script>

    <!-- Global Confirmation Modal -->
    <div id="globalConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ri-alert-line text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Confirmar Ação</h3>
                        <p class="text-sm text-gray-600">Esta ação não pode ser desfeita</p>
                    </div>
                </div>
                <p id="modalMessage" class="text-gray-700 mb-6">Tem certeza que deseja realizar esta ação?</p>
                <div class="flex space-x-3">
                    <button onclick="closeConfirmationModal()" class="flex-1 inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 text-sm">
                        Cancelar
                    </button>
                    <button id="confirmButton" class="flex-1 inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-red-600 text-white hover:bg-red-700 px-4 py-2 text-sm">
                        <i class="ri-delete-bin-line mr-2"></i>Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Success Modal -->
    <div id="globalSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 modal">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ri-check-line text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 id="successModalTitle" class="text-lg font-semibold text-gray-900">Sucesso!</h3>
                        <p class="text-sm text-gray-600">Operação realizada com sucesso</p>
                    </div>
                </div>
                <p id="successModalMessage" class="text-gray-700 mb-6">A ação foi executada com sucesso.</p>
                <div class="flex justify-end">
                    <button onclick="closeSuccessModal()" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-green-600 text-white hover:bg-green-700 px-6 py-2 text-sm">
                        <i class="ri-check-line mr-2"></i>Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

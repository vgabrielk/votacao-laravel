@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Section - Register Form -->
    <div class="w-full lg:w-3/5 bg-white flex flex-col justify-center px-0 sm:px-0 lg:px-16">
        <!-- Header -->
        <div class="mb-8 px-4 sm:px-8">
            <!-- Logo -->
           
        </div>

        <!-- Main Content -->
        <div class=" lg:max-w-md w-full px-4 sm:px-8">
             <div class="flex items-center justify-center md:justify-start mb-6">
                <img src="/images/logo.png" alt="Logo" class="w-24 h-24 object-contain" style="filter: invert(1);">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Criar Conta</h1>
            <p class="text-gray-600 mb-8">Crie sua conta para participar de enquetes e dar sua opinião</p>

            <!-- Social Login Buttons -->
            <div class="space-y-3 mb-6">
                <!-- Google Button -->
                <button type="button" onclick="googleSignIn()" class="social-btn w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition-all duration-300">
                    <svg class="w-5 mr-3" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="text-gray-700 font-medium">Cadastrar com Google</span>
                </button>


            </div>

            <!-- Separator -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">ou</span>
                </div>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Nome Completo</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="form-input w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                           placeholder="Digite seu nome completo" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="form-input w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                           placeholder="Digite seu email" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-2">Senha</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="form-input w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                           placeholder="Digite sua senha">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">Confirmar Senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="form-input w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Confirme sua senha">
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="login-btn w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300">
                    Criar Conta
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Já tem uma conta? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Entrar
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Right Section - Night Sky Illustration -->
    <div class="hidden lg:flex lg:w-3/5 night-sky relative">
        <!-- Stars -->
        <div class="stars"></div>
        
        <!-- Moon -->
        <div class="moon"></div>
        
        <!-- Mountains -->
        <div class="mountains">
            <div class="mountain"></div>
            <div class="mountain"></div>
            <div class="mountain"></div>
            <div class="mountain-reflection"></div>
        </div>
        
        <!-- Text Overlay -->
        <div class="absolute inset-0 flex flex-col justify-center items-center text-white px-16">
            <h2 class="text-4xl font-bold mb-4 text-center">Junte-se à comunidade</h2>
            <p class="text-lg text-center opacity-90 max-w-md">
                Faça parte de uma plataforma onde sua opinião é valorizada. Crie sua conta e comece a participar.
            </p>
        </div>
        
        <!-- Pagination Dots -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <div class="pagination-dots">
                <div class="dot"></div>
                <div class="dot active"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>
</div>
@endsection

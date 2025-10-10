<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\Services\FriendService;
use App\Services\PollService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FriendService::class);
        $this->app->singleton(PollService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar rotas de broadcasting para autenticação de canais privados
        Broadcast::routes(['middleware' => ['web', 'auth']]);
    }
}

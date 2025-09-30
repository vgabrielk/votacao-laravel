<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GroupService;
use App\Services\FriendService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GroupService::class);
        $this->app->singleton(FriendService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

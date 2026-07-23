<?php

namespace App\Providers;

use App\Contracts\Services\AuthServiceContract;
use App\Contracts\Services\MediaServiceContract;
use App\Services\AuthService;
use App\Services\MediaService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(MediaServiceContract::class, MediaService::class);
    }

    public function boot(): void
    {
        //
    }
}

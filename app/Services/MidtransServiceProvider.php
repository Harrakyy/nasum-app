<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MidtransService;

class MidtransServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService();
        });
    }

    public function boot(): void
    {
        //
    }
}
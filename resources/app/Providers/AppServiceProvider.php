<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register Midtrans config
        $this->app->singleton('midtrans', function ($app) {
            // Set Midtrans configuration
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$clientKey = config('services.midtrans.client_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
            
            return new Snap();
        });
    }
    
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/midtrans.php' => config_path('midtrans.php'),
        ]);
    }
}
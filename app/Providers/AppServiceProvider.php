<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClientService;
use App\Services\SellerService;
/* New Providers include here */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientService::class, function () {
            return new ClientService();
        });

        $this->app->bind(SellerService::class, function () {
            return new SellerService();
        });

        /* New Providers here  */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

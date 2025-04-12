<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\VendorOrderRepositoryInterface;
use App\Repositories\VendorOrderRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VendorOrderRepositoryInterface::class, VendorOrderRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Repositories\Implementations\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Implementations\OrderRepository;
use App\Repositories\Interfaces\OrderItemInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Implementations\OrderItemRepository;
use Illuminate\Support\Facades\Route;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            OrderItemInterface::class,
            OrderItemRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
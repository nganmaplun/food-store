<?php

namespace App\Providers;

use App\Repositories\FoodDayRepository;
use App\Repositories\FoodDayRepositoryEloquent;
use App\Repositories\FoodOrderRepository;
use App\Repositories\FoodOrderRepositoryEloquent;
use App\Repositories\FoodRepository;
use App\Repositories\FoodRepositoryEloquent;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryEloquent;
use App\Repositories\TableRepository;
use App\Repositories\TableRepositoryEloquent;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetRepositoryEloquent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TimesheetRepository::class, TimesheetRepositoryEloquent::class);
        $this->app->bind(FoodRepository::class, FoodRepositoryEloquent::class);
        $this->app->bind(FoodDayRepository::class, FoodDayRepositoryEloquent::class);
        $this->app->bind(TableRepository::class, TableRepositoryEloquent::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryEloquent::class);
        $this->app->bind(FoodOrderRepository::class, FoodOrderRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        View::composer('*', function($view) {
            if (Auth::check()) {
                $view->with('user', Auth::user());
            }
            $view->with('route', Route::current()->getName());
        });
    }
}

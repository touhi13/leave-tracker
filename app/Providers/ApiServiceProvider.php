<?php

namespace App\Providers;

use App\Repositories\Auth\AuthInterface;
use App\Repositories\Auth\AuthRepo;
use App\Repositories\LeaveRequest\LeaveRequestRepo;
use App\Repositories\LeaveRequest\LeaveRequestInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthInterface::class,
            AuthRepo::class
        );
        $this->app->bind(
            LeaveRequestInterface::class,
            LeaveRequestRepo::class
        );
        $this->app->bind(
            UserInterface::class,
            UserRepo::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}

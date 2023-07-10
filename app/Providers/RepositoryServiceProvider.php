<?php

namespace App\Providers;

use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\ProductInterface;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Interfaces\ActivityInterface;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ActivityRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(ActivityInterface::class, ActivityRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

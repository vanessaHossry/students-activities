<?php

namespace App\Providers;

use App\Interfaces\ActivityInterface;
use App\Interfaces\UserInterface;
use App\Repositories\ActivityRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(ActivityInterface::class, ActivityRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

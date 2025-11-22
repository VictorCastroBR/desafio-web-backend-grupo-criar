<?php

namespace App\Providers;

use App\Domain\State\Repositories\StateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentStateRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            StateRepositoryInterface::class,
            EloquentStateRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

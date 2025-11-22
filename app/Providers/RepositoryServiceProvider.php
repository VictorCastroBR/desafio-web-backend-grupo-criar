<?php

namespace App\Providers;

use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;
use App\Domain\State\Repositories\StateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentClusterRepository;
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
        $this->app->bind(
           ClusterRepositoryInterface::class,
           EloquentClusterRepository::class
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

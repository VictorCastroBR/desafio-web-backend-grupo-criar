<?php

namespace App\Providers;

use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;
use App\Domain\City\Repositories\CityRepositoryInterface;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;
use App\Domain\Discount\Repositories\DiscountRepositoryInterface;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\State\Repositories\StateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCityRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCampaignRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentClusterRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentDiscountRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductRepository;
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
        $this->app->bind(
           CityRepositoryInterface::class,
           EloquentCityRepository::class
        );
        $this->app->bind(
            CampaignRepositoryInterface::class,
            EloquentCampaignRepository::class
        );
        $this->app->bind(
            DiscountRepositoryInterface::class,
            EloquentDiscountRepository::class
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
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

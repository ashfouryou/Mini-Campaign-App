<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CampaignRepository;
use App\Repositories\CampaignRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

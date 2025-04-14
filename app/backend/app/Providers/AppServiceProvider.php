<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\RickAndMortyClientInterface;
use App\Services\RickAndMortyClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RickAndMortyClientInterface::class, RickAndMortyClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}

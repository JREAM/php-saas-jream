<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Non-Production
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            // @TODO BELOW: AppTrait Not Found?
    		// $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}

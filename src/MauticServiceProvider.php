<?php

namespace Gentor\Mautic;


use Illuminate\Support\ServiceProvider;

class MauticServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mautic', function ($app) {
            return new MauticService($app['config']['mautic']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mautic'];
    }

}
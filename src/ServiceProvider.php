<?php

namespace AylesSoftware\ZohoDesk;

use AylesSoftware\ZohoDesk\Facades\ZohoDesk as ZohoDeskFacade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );

        $this->app->singleton('ZohoDesk', function () {
            return new ZohoDeskHandler();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/zoho-desk.php' => config_path('zoho-desk.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ZohoDeskFacade::class,
        ];
    }
}

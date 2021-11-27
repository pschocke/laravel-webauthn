<?php

namespace Pschocke\LaravelWebauthn;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Pschocke\LaravelWebauthn\Contracts\CredentialRepository;
use Pschocke\LaravelWebauthn\Services\Webauthn;


class LaravelWebauthnServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/webauthn.php' => config_path('webauthn.php'),
            ], 'webauthn-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'webauthn-migrations');

            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/webauthn'),
            ], 'webauthn-assets');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/webauthn.php', 'webauthn'
        );

        $this->app->singleton(CredentialRepository::class, \Pschocke\LaravelWebauthn\Services\Webauthn\CredentialRepository::class);
        $this->app->singleton(Webauthn::class, Webauthn::class);
    }
}

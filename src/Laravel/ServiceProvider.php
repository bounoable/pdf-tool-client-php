<?php

namespace PdfTools\Laravel;

use PdfTools\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider implements DeferrableProvider
{
    public function provides(): array
    {
        return [Client::class, 'pdf-tools'];
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'pdf-tools');

        $this->app->singleton(Client::class, function (Application $app) {
            $config = $app->get('config');

            return Client::create(
                $config->get('pdf-tools.baseURI', 'http://localhost:3001'),
                $config->get('pdf-tools.guzzleConfig', [])
            );
        });

        $this->app->alias(Client::class, 'pdf-tools');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('pdf-tools.php'),
        ]);
    }
}

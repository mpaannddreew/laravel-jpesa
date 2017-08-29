<?php

namespace FannyPack\JPesa;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class JPesaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/jpesa.php' => config_path('jpesa.php'),
            ], 'jpesa-config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JPesaProcessor::class, function($app){
            return new JPesaProcessor(new Client(
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'verify' => false
                ]
            ),$app);
        });
    }
    
    public function provides()
    {
        return [JPesaProcessor::class];
    }
}

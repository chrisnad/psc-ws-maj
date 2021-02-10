<?php

namespace App\Bschmitt\Amqp;

use App\Bschmitt\Amqp\Consumer;
use App\Bschmitt\Amqp\Publisher;
use Illuminate\Support\ServiceProvider;

class AmqpServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Amqp', 'App\Bschmitt\Amqp\Amqp');
        if (!class_exists('Amqp')) {
            class_alias('App\Bschmitt\Amqp\Facades\Amqp', 'Amqp');
        }

        $this->publishes([
            __DIR__ . '/../config/amqp.php' => config_path('amqp.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Bschmitt\Amqp\Publisher', function ($app) {
            return new Publisher(config());
        });
        $this->app->singleton('App\Bschmitt\Amqp\Consumer', function ($app) {
            return new Consumer(config());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Amqp', 'App\Bschmitt\Amqp\Publisher', 'App\Bschmitt\Amqp\Consumer'];
    }
}

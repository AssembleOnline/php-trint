<?php

namespace Assemble\PHPTrint;

use Illuminate\Support\ServiceProvider;

class TrintServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/trint.php' => config_path('trint.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/trint.php', 'trint'
        );
        $this->app->singleton(\Assemble\PHPTrint\Client::class, function ($app) {
            return new \Assemble\PHPTrint\Client(config('trint'));
        });
        $this->app->alias(\Assemble\PHPTrint\Client::class, 'trint');
    }
}

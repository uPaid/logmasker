<?php

namespace Upaid\Logmasker\Providers;

use Illuminate\Support\ServiceProvider;
use Upaid\Logmasker\Services\Logmasker;

class LogmaskerProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/logmasker.php' => config_path('logmasker.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('Logmasker', function () {
            return new Logmasker($this->app['config']);
        });
    }
}

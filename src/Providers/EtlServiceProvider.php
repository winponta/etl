<?php

namespace Winponta\ETL\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * EtlServiceProvider
 *
 * @author ademir.mazer.jr@gmail.com
 */
class EtlServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->initializeResources();
    }

    private function initializeResources() {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('etl', function ($app) {
            return new \Winponta\ETL\Etl($app);
        });
    }

}

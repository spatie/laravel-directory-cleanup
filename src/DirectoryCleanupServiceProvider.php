<?php

namespace Spatie\DirectoryCleanup;

use Illuminate\Support\ServiceProvider;

class DirectoryCleanupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-directory-cleanup.php' => config_path('laravel-directory-cleanup.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-directory-cleanup.php', 'laravel-directory-cleanup');

        $this->app->bind('command.clean:directories', DirectoryCleanupCommand::class);

        $this->commands([
            'command.clean:directories',
        ]);
    }
}

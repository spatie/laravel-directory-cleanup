<?php

namespace Spatie\DirectoryCleanup;

use Illuminate\Support\ServiceProvider;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;
use Spatie\DirectoryCleanup\Policies\DeleteEverything;

class DirectoryCleanupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-directory-cleanup.php' => config_path('laravel-directory-cleanup.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-directory-cleanup.php', 'laravel-directory-cleanup');

        $this->app->bind('command.clean:directories', DirectoryCleanupCommand::class);

        $this->app->bind(
            CleanupPolicy::class,
            config('laravel-directory-cleanup.cleanup_policy', DeleteEverything::class)
        );

        $this->commands([
            'command.clean:directories',
        ]);
    }
}

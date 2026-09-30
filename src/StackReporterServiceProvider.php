<?php

namespace GrayLoon\StackReporter;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class StackReporterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/stackreporter.php' => config_path('grayloon_stack_reporter.php'),
            ], 'config');
        }

        AboutCommand::add('StackReporter', [
            'Version' => fn () => InstalledVersions::getPrettyVersion('grayloon/stack-reporter-laravel'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/stackreporter.php',
            'grayloon_stack_reporter'
        );
    }
}

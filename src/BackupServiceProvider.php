<?php

namespace Spatie\Backup;

use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Commands\BackupCommand;
use Spatie\Backup\Commands\CleanupCommand;
use Spatie\Backup\Commands\ListCommand;
use Spatie\Backup\Commands\MonitorCommand;
use Spatie\Backup\Helpers\ConsoleOutput;

class BackupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-backup.php' => config_path('laravel-backup.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-backup.php', 'laravel-backup');

        $this->handleDeprecatedConfigValues();

        $this->app['events']->subscribe(\Spatie\Backup\Notifications\EventHandler::class);

        $this->app->bind('command.backup:run', BackupCommand::class);
        $this->app->bind('command.backup:clean', CleanupCommand::class);
        $this->app->bind('command.backup:list', ListCommand::class);
        $this->app->bind('command.backup:monitor', MonitorCommand::class);

        $this->commands([
            'command.backup:run',
            'command.backup:clean',
            'command.backup:list',
            'command.backup:monitor',
        ]);

        $this->app->singleton(ConsoleOutput::class);
    }

    protected function handleDeprecatedConfigValues()
    {
        /*
         * Earlier versions of the package used filesystems instead of disks
         */
        if (config('laravel-backup.destination.filesystems')) {
            config(['laravel-backup.destination.disks' => config('laravel-backup.destination.filesystems')]);
        }

        if (config('laravel-backup.monitorBackups.filesystems')) {
            config(['laravel-backup.monitorBackups.disks' => config('laravel-backup.monitorBackups.filesystems')]);
        }
    }
}

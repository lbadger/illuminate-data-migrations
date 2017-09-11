<?php

namespace Lbadger\Database\Providers;

use Illuminate\Support\ServiceProvider;
use Lbadger\Database\Console\DataMigrations\MigrateCommand;
use Lbadger\Database\Console\DataMigrations\MigrateMakeCommand;
use Lbadger\Database\Console\DataMigrations\ResetCommand as MigrateResetCommand;
use Lbadger\Database\Console\DataMigrations\StatusCommand as MigrateStatusCommand;
use Lbadger\Database\Console\DataMigrations\InstallCommand as MigrateInstallCommand;
use Lbadger\Database\Console\DataMigrations\RefreshCommand as MigrateRefreshCommand;
use Lbadger\Database\Console\DataMigrations\RollbackCommand as MigrateRollbackCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migrate' => 'command.data-migrate',
        'MigrateInstall' => 'command.data-migrate.install',
        'MigrateRefresh' => 'command.data-migrate.refresh',
        'MigrateReset' => 'command.data-migrate.reset',
        'MigrateRollback' => 'command.data-migrate.rollback',
        'MigrateStatus' => 'command.data-migrate.status',
        'MigrateMake' => 'command.data-migrate.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands(
            $this->commands
        );
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.data-migrate', function ($app) {
            return new MigrateCommand($app['data-migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateInstallCommand()
    {
        $this->app->singleton('command.data-migrate.install', function ($app) {
            return new MigrateInstallCommand($app['data-migration.repository']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.data-migrate.make', function ($app) {
            // Once we have the data-migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the data-migrations, and may be extended by these developers.
            $creator = $app['data-migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton('command.data-migrate.refresh', function () {
            return new MigrateRefreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton('command.data-migrate.reset', function ($app) {
            return new MigrateResetCommand($app['data-migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('command.data-migrate.rollback', function ($app) {
            return new MigrateRollbackCommand($app['data-migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton('command.data-migrate.status', function ($app) {
            return new MigrateStatusCommand($app['data-migrator']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }
}

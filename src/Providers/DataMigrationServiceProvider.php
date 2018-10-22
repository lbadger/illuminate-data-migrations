<?php

namespace Lbadger\Database\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\MigrationCreator;
use Lbadger\Database\Console\DataMigrations\DataMigrationCreator;
use Lbadger\Database\Migrations\DatabaseDataMigrationRepository;

class DataMigrationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();

        $this->registerMigrator();

        $this->registerCreator();
    }

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('data-migration.repository', function ($app) {
            $table = $app['config']['database.data-migrations'];

            return new DatabaseDataMigrationRepository($app['db'], $table);
        });
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('data-migrator', function ($app) {
            $repository = $app['data-migration.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('data-migration.creator', function ($app) {
            return new DataMigrationCreator($app['files']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'data-migrator', 'data-migration.repository', 'data-migration.creator',
        ];
    }
}

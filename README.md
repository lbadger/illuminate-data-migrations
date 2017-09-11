# illuminate-data-migrations
Creating data migrations for Laravel. This keeps data out of migrations and leaves it for schema changes only.

## Install ##
composer require lbadger/illuminate-data-migrations

## config/database.php ##
    'data-migrations' => 'data_migrations',
    
## config/app.php ##
        \Lbadger\Database\Providers\ArtisanServiceProvider::class,
        \Lbadger\Database\Providers\DataMigrationServiceProvider::class,

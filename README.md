# illuminate-data-migrations
Creating data migrations for Laravel. This keeps data out of migrations and leaves it for schema changes only.

## Install ##
composer require lbadger/illuminate-data-migrations

## Data Migration Folder ##
Create a folder called "data_migrations" in the /database folder
    
# Laravel #

## config/database.php ##
    'data-migrations' => 'data_migrations',
    
## config/app.php ##
    \Lbadger\Database\Providers\ArtisanServiceProvider::class,
    \Lbadger\Database\Providers\DataMigrationServiceProvider::class,


# Lumen #

## bootstrap/app.php ##
    $app['config']['database.data-migrations'] = 'data_migrations';
    $app->register(\Lbadger\Database\Providers\ArtisanServiceProvider::class);
    $app->register(\Lbadger\Database\Providers\DataMigrationServiceProvider::class);


* Note: You cannot have the same data migration class name as a normal migration

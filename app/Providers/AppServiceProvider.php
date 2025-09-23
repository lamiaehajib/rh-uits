<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Add this line

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add this code block
        Schema::defaultStringLength(191);

        // Add the ENUM type registration
        if (Schema::hasTable('taches') && config('database.default') === 'mysql') {
            \Doctrine\DBAL\Types\Type::addType('enum', 'Doctrine\DBAL\Types\StringType');
            \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enum');
        }
    }
}
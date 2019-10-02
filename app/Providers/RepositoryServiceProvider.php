<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Contracts\Core\ModuleRepository::class, \App\Repositories\Core\ModuleRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\RouteRepository::class, \App\Repositories\Core\RouteRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\MenuItemRepository::class, \App\Repositories\Core\MenuItemRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\MenuRepositoryRepository::class, \App\Repositories\Core\MenuRepositoryRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\PermissionRepositoryRepositoryRepository::class, \App\Repositories\Core\PermissionRepositoryRepositoryRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\RoleRepositoryRepositoryRepository::class, \App\Repositories\Core\RoleRepositoryRepositoryRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\Core\PlantZoneRepository::class, \App\Repositories\Core\PlantZoneRepositoryEloquent::class);
        //:end-bindings:
    }
}

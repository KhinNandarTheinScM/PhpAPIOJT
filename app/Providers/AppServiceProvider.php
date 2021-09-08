<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Dao Registration
        $this->app->bind('App\Contracts\Dao\User\UserDaoInterface', 'App\Dao\User\UserDao');

        // Business logic registration
        $this->app->bind('App\Contracts\Services\User\UserServiceInterface', 'App\Services\User\UserService');

        // Dao Registration
        $this->app->bind('App\Contracts\Dao\Posts\PostsDaoInterface', 'App\Dao\Posts\PostsDao');

        // Business logic registration
        $this->app->bind('App\Contracts\Services\Posts\PostsServiceInterface', 'App\Services\Posts\PostsService');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

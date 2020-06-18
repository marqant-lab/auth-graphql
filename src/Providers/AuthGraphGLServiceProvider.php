<?php

namespace Marqant\AuthGraphGL\Providers;


use Illuminate\Support\ServiceProvider;

/**
 * Class AuthGraphGLServiceProvider
 *
 * @package Marqant\AuthGraphGL\Providers
 */
class AuthGraphGLServiceProvider extends ServiceProvider
{

    public function boot()
    {
        ////////////
        // config //
        ////////////

        // make config file publishable
        $this->publishes([
            __DIR__ . '/../../config' => config_path(),
        ], 'config');

        // merge package configuration with published configuration file
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/authentication.php',
            'auth');
    }

    public function register()
    {
        //////////////////////////////////
        // Custom Queries //
        //////////////////////////////////

        $this->registerQueries();

        //////////////////////////////////
        // Custom Mutations //
        //////////////////////////////////

        $this->registerMutations();
    }

    public function registerQueries()
    {
        config([
            'lighthouse.namespaces.queries' => array_merge((array) config('lighthouse.namespaces.queries'),
                (array) 'Marqant\\AuthGraphGL\\GraphQL\\Queries'),
        ]);
    }

    public function registerMutations()
    {
        config([
            'lighthouse.namespaces.mutations' => array_merge((array) config('lighthouse.namespaces.mutations'),
                (array) 'Marqant\\AuthGraphGL\\GraphQL\\Mutations'),
        ]);
    }
}

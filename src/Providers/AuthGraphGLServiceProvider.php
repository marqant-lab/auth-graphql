<?php

namespace Marqant\AuthGraphQL\Providers;


use Illuminate\Support\ServiceProvider;

/**
 * Class AuthGraphQLServiceProvider
 *
 * @package Marqant\AuthGraphQL\Providers
 */
class AuthGraphQLServiceProvider extends ServiceProvider
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
                (array) 'Marqant\\AuthGraphQL\\GraphQL\\Queries'),
        ]);
    }

    public function registerMutations()
    {
        config([
            'lighthouse.namespaces.mutations' => array_merge((array) config('lighthouse.namespaces.mutations'),
                (array) 'Marqant\\AuthGraphQL\\GraphQL\\Mutations'),
        ]);
    }
}

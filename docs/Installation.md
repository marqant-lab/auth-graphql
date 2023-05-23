## Installation

Please make sure that you install before 
[Lighthouse](https://lighthouse-php.com/master/getting-started/installation.html#installation)
 to your project.  

This package uses config `auth.providers.users.model` for base authentication.  

Require the package through composer:

```shell script
composer require marqant-lab/auth-graphql
```

For Laravel 7.x install v1.0.0:

```shell script
composer require marqant-lab/auth-graphql:1.0.0
```


Which package tag use with your Laravel version:

|  Laravel version  |     package tag     |
|:-----------------:|:-------------------:|
|        7.x        |       v1.0.0        |
|        8.x        | v2.0.0 (and upper)  |
|     9.x/10.x      | v3.0.0 (and upper)  |


After add this trait to the User model: `use HasApiTokens;`

Execute:
```
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
and
```
php artisan migrate
```

And add (or change) this to your 'config/lighthouse.php':

```php
...
    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
...
    */

    'guard' => 'sanctum',
...
```

After this add import to your `schema.graphql`

```graphql
#import ../vendor/marqant-lab/auth-graphql/graphql/*.graphql
```

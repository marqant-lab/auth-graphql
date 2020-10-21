## Installation

Please make sure that you install before 
[Lighthouse](https://lighthouse-php.com/master/getting-started/installation.html#installation)
 to your project.  

This package uses config `auth.providers.users.model` for base authentication.  

Require the package through composer.

```shell script
composer require marqant-lab/auth-graphql
```

After this add this trait to User model: `use HasApiTokens;`

Execute:
```
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
and
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

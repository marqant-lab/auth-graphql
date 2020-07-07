# "Auth GraphQL" package for authentication

## What is it?

This package contains GraphQL queries and mutations for User authentication with Laravel Sanctum.

## Installation

Please make sure that you install before 
[Lighthouse](https://lighthouse-php.com/master/getting-started/installation.html#installation)
 to your project.  

This package uses config `auth.providers.users.model` for authentication.  

Require the package through composer.

```shell script
$ compsoer require marqant-lab/auth-graphql
```

After this add this trait to User model: `use HasApiTokens;`

Execute:
```
$ php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
and
$ php artisan migrate
```

And add this to your 'config/lighthouse.php':

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

## Mutations

| Mutation | Requires input | Returns |
| ------  | ----- | ----- |
| register | RegisterUserInput | ResponseUser |
| authenticate | AuthenticateInput | ResponseUser |

## Queries

| Query | Requires input | Returns |
| ------  | ----- | ----- |
| me |  | User |

## Tests

To run tests, you first need to set up a sqlite database that we use to get snapshots of the database state. Run the
 following command from within your project root to create the sqlite database.
 
```shell script
$ touch database/database.sqlite
```

If you want to execute package tests add this to the project composer.json:  
```
...
"autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/",
            "Marqant\\AuthGraphQL\\Tests\\": "vendor/marqant-lab/auth-graphql/tests/"
        }
    },
...
```
And this to the phpunit.xml

```xml
        <testsuite name="AuthGraphQL">
            <directory suffix="Test.php">./vendor/marqant-lab/auth-graphql/tests</directory>
        </testsuite>
```

Add to project `Tests\TestCase` this trait `use MakesGraphQLRequests;`:

```php
<?php

namespace Tests;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
}

```

And after you can check it by executing:
```shell script
$ php artisan test --group=AuthGraphQL
or
$ phpunit --group=AuthGraphQL
```


## Demo data

If you need demo users, just execute:

```shell script
$ php artisan db:seed --class=Marqant\\AuthGraphQL\\Seeds\\UserSeeder
```

This seeder will create `'demo@demo.com'` and `'admin@admin.com'` Users 
with password `'Password123$'`.

## Translations

Started to add translations to the package.  
Now it is only one message:  
 - 'en' 'Wrong username or password.'
 - 'de' 'Falscher Nutzername oder Passwort.'
 
You can publish translations to your project and override this message or add more languages.  
Path after publish is `'lang/vendor/auth-graphq'`

```shell script
$ php artisan vendor:publish
```


## Tests

To run tests, you first need to set up a sqlite database that we use to get snapshots of the database state. Run the
 following command from within your project root to create the sqlite database.
 
```shell script
touch database/database.sqlite
```

If you want to execute package tests add this to the phpunit.xml

```xml
        <testsuite name="AuthGraphQL">
            <directory suffix="Test.php">./vendor/marqant-lab/auth-graphql/tests</directory>
        </testsuite>
```

And after you can check it by executing:
```shell script
php artisan test --group=AuthGraphQL
or
phpunit --group=AuthGraphQL
```

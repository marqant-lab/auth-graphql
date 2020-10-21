
## Make any Model Authenticatable

It is very easy to do, just simply follow these steps. 

For example you create Model 'Worker' (table `worker`) and want to authenticate with this model.

#### 1st step

The Model should have at least one unique field.  
In best case it is `id` but it can be any other unique field.  
For example `key`.  
And it should have one field filled with Laravel Hash.  
For example it can be `secret` field.  

```php
$Worker->secret = Hash::make('Password123$');
```

#### 2nd step

You should add these traits (HasApiTokens, Authenticatable and Authorizable) and
 implementations to the Model.  
Example:

```php
...
/**
 * Class Worker
 *
 * @package App\Models
 */
class Worker extends Model implements
    AuthenticatableContract, // Added to make it authenticatable
    AuthorizableContract // Added to make it authenticatable
{
    use HasApiTokens;
    use Authenticatable, Authorizable; // Added to make it authenticatable
...
```

#### 3rd step

At your project's 'config/auth.php' section 'User Providers' you need
 to add provider config for new Model (don't forget about 'key_field' and 'secret_field').  
Example:

```php
...
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        'workers' => [
            'driver'       => 'eloquent',
            'model'        => App\Models\Worker::class,
            'key_field'    => 'key', // unique key (can be `id`)
            'secret_field' => 'secret', // hashed field
        ],
    ],
...
```

#### 5th step

Also at your project's 'config/auth.php' section 'Authentication Guards' you need
 to add guard config for new Model.  
Example:

```php
...
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
...
        'worker-api' => [
            'driver' => 'sanctum',
            'provider' => 'workers',
            'hash' => false,
        ],
    ],
...
```

!!! Important.  
If you will get error like 'Auth guard [worker-api] is not defined.'  
just clear config with `php artisan config:clear` and it should solve the issue.

#### That's it

After these steps you should be able to execute auth mutation.  
Example:

 - mutation
```graphql
mutation AuthenticateModel($input: AuthModelInput!) {
  authenticateModel(input: $input) {
    accessToken
  }
}
```

 - variables
```json
{
  "input": {
    "tableName": "workers",
    "keyValue": "some unique key",
    "secretValue": "Password123$"
  }
}
```

 - response
```json
{
  "data": {
    "authenticateModel": {
      "accessToken": "1|9EGyoCVHPxYjxGSksl3mBKY..."
    }
  }
}
```

And of course you can add guard to your queries and mutations
 (don't forget to add Bearer token to the headers).  
Example (guarder with "worker-api" query to get data for authenticated Model):

 - schema
```graphql
type Worker {
    id: ID!
    name: String
    key: String!
}

extend type Query @guard(with: "worker-api") {
    WorkerMe: Worker @auth
}
```

 - query
```graphql
query WorkerMe {
  WorkerMe {
    id
    name
    key
  }
}
```

 - response
```json
{
  "data": {
    "WorkerMe": {
      "id": "1",
      "name": "Tim Kook",
      "key": "some unique key"
    }
  }
}
```

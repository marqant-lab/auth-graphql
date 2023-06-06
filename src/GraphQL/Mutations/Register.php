<?php

namespace Marqant\AuthGraphQL\GraphQL\Mutations;

use Throwable;
use Illuminate\Support\Facades\Hash;
use Marqant\AuthGraphQL\Exceptions\ClientSaveInternalGraphQLException;

/**
 * Class Register
 *
 * @package Marqant\AuthGraphQL\GraphQL\Mutations
 */
class Register
{
    /**
     * @param       $rootValue
     * @param array $args
     *
     * @return array
     *
     * @throws Throwable
     */
    public function __invoke($rootValue, array $args)
    {
        try {
            $model = app(config('auth.providers.users.model'));
            // get 'name' attribute first
            $name  = $args['name'] ?? null;
            // lok for 'firstName' and 'lastName' if 'name' was empty
            if (empty($name)) {
                $name = (empty($args['lastName'])) ? $args['firstName'] : "{$args['firstName']} {$args['lastName']}";
            }

            $input = collect($args)
                ->except('password_confirmation')
                ->toArray();
            $input['name'] = $name;
            $input['password'] = Hash::make($input['password']);
            $model->fill($input);
            $model->save();
            return [
                'accessToken' => $model->createToken($model->id)->plainTextToken,
                'user' => $model,
            ];
        } catch (Throwable $exception) {
            throw new ClientSaveInternalGraphQLException($exception);
        }
    }
}

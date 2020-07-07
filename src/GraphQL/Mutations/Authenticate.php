<?php

namespace Marqant\AuthGraphQL\GraphQL\Mutations;


use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class Authenticate
 *
 * @package Marqant\AuthGraphQL\GraphQL\Mutations
 */
class Authenticate
{
    /**
     * @param       $rootValue
     * @param array $args
     *
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args)
    {
        $model = app(config('auth.providers.users.model'));

        try {
        $user = $model
            ->where(config('auth.username'), $args['email'])
            ->firstOrFail();
        } catch (\Exception $exception) {
            throw new \Exception(__('Wrong username or password.'));
        }

        if (!$user || !Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('Wrong username or password.')],
            ]);
        }

        return [
            'accessToken' => $user->createToken($user->id)->plainTextToken,
            'user'        => $user,
        ];
    }
}

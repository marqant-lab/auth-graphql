<?php

namespace Marqant\AuthGraphQL\GraphQL\Mutations;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Marqant\AuthGraphQL\Exceptions\ClientSaveInternalGraphQLException;
use Marqant\AuthGraphQL\Exceptions\ClientSaveValidationGraphQLException;

/**
 * Class AuthenticateModel
 *
 * @package Marqant\AuthGraphQL\GraphQL\Mutations
 */
class AuthenticateModel
{
    /**
     * @param       $rootValue
     * @param array $args
     *
     * @return array
     *
     * @throws Exception
     */
    public function __invoke($rootValue, array $args)
    {
        try {
            $table_name = $args['tableName'];
            $model = app(config("auth.providers.$table_name.model"));
            try {
                $key_field = config("auth.providers.$table_name.key_field");
                $Model = $model->where($key_field, $args['keyValue'])->firstOrFail();
            } catch (Exception $exception) {
                throw new Exception(__('Wrong key field or it value.'));
            }

            $secret_field = config("auth.providers.$table_name.secret_field");
            if (!$Model || empty($Model->{$secret_field}) || !Hash::check($args['secretValue'], $Model->{$secret_field})) {
                throw ValidationException::withMessages([
                    'email' => [__('Wrong secret field or it value.')],
                ]);
            }

            return [
                'accessToken' => $Model->createToken($Model->id)->plainTextToken,
            ];
        } catch (ValidationException $validationException) {
            throw new ClientSaveValidationGraphQLException($validationException);
        } catch (Exception $exception) {
            throw new ClientSaveInternalGraphQLException($exception);
        }
    }
}

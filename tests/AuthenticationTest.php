<?php

namespace Marqant\AuthGraphGL\tests;


use Tests\TestCase;

/**
 * Class AuthenticationTest
 *
 * @package Marqant\AuthGraphGL\tests
 */
class AuthenticationTest extends TestCase
{
    /**
     * @group       AuthGraphGL
     *
     * @param array $userInput
     *
     * @dataProvider getRegisterUserData
     *
     * @test
     */
    public function testRegistrationSuccessfully(array $userInput): void
    {
        // register user
        $registerResponse = $this->postGraphQL([
            "query" => 'mutation($input: RegisterUserInput!) {
                register(input: $input) {
                    accessToken
                    user {
                        email
                        name
                        roles {
                           name
                        }
                    }
                }
            }',
            "variables" => $userInput,
        ]);

        // get accessToken (also check if it is exists)
        $access_token = $registerResponse->json("data.register.accessToken");
        $registerResponse->assertGraphQLValidationPasses()
            ->assertJson([
                'data' => [
                    'register' => [
                        'accessToken' => $access_token,
                        'user' => [
                            'email' => $userInput['input']['email'],
                            'name'  => "{$userInput['input']['firstName']} {$userInput['input']['lastName']}",
                            'roles' => [],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @group       AuthGraphGL
     *
     * @param array $userInput
     *
     * @dataProvider getRegisterUserData
     *
     * @test
     */
    public function testAuthenticateSuccessfully(array $userInput): void
    {
        // register user
        $this->postGraphQL([
            "query" => 'mutation($input: RegisterUserInput!) {
                register(input: $input) {
                    accessToken
                    user {
                        email
                        name
                        roles {
                           name
                        }
                    }
                }
            }',
            "variables" => $userInput,
        ]);

        $name = "{$userInput['input']['firstName']} {$userInput['input']['lastName']}";
        unset(
            $userInput['input']['password_confirmation'],
            $userInput['input']['firstName'],
            $userInput['input']['lastName']
        );

        // authenticate user
        $authenticateResponse = $this->postGraphQL([
            "query" => 'mutation($input: AuthenticateInput!) {
                authenticate(input: $input) {
                    accessToken
                    user {
                        email
                        name
                        roles {
                           name
                        }
                    }
                }
            }',
            "variables" => $userInput,
        ]);

        // get accessToken (also check if it is exists)
        $access_token = $authenticateResponse->json("data.authenticate.accessToken");
        // check response
        $authenticateResponse->assertJson([
            'data' => [
                'authenticate' => [
                    'accessToken' => $access_token,
                    'user' => [
                        'email' => $userInput['input']['email'],
                        'name'  => $name,
                        'roles' => [],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @group       AuthGraphGL
     *
     * @param array $userInput
     *
     * @dataProvider getRegisterUserData
     *
     * @test
     */
    public function testGetUserInfo(array $userInput): void
    {
        // register user
        $this->postGraphQL([
            "query" => 'mutation($input: RegisterUserInput!) {
                register(input: $input) {
                    accessToken
                    user {
                        email
                        name
                        roles {
                           name
                        }
                    }
                }
            }',
            "variables" => $userInput,
        ]);

        // get user from database
        $model = app(config('auth.providers.users.model'));
        $user = $model->where(config('auth.username'), $userInput['input']['email'])
            ->firstOrFail();

        // get user info through GraphQL
        $meResponse = $this->postGraphQL([
            "query" => 'query Me {
                me {
                    name
                    email
                    roles {
                        name
                    }
                }
              }',
        ], [
            'Authorization' => 'Bearer ' . $user->createToken($user->id)->plainTextToken,
        ]);

        // check response
        $meResponse->assertJson([
            'data' => [
                'me' => [
                    'email' => $userInput['input']['email'],
                    'name'  => "{$userInput['input']['firstName']} {$userInput['input']['lastName']}",
                    'roles' => [],
                ]
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getRegisterUserData(): array
    {
        return [
            'demoUser' => [
                0 => [
                    "input" => [
                        "email"                 => "demo@demo.com",
                        "password"              => "12345678",
                        "password_confirmation" => "12345678",
                        "firstName"             => "Max",
                        "lastName"              => "Mustermann",
                    ]
                ]
            ],
        ];
    }
}

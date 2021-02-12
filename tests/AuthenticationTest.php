<?php

namespace Marqant\AuthGraphQL\tests;

use Tests\TestCase;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

/**
 * Class AuthenticationTest
 *
 * @package Marqant\AuthGraphQL\tests
 */
class AuthenticationTest extends TestCase
{
    use MakesGraphQLRequests;

    /**
     * @group       AuthGraphQL
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
                    }
                }
            }',
            "variables" => $userInput,
        ]);

        // get accessToken (also check if it is exists)
        $access_token = $registerResponse->json("data.register.accessToken");

        // check if we received token
        $this->assertNotEmpty($access_token);

        // check response
        $registerResponse->assertOk()
            ->assertGraphQLValidationPasses()
            ->assertJsonStructure([
                'data' => [
                    'register' => [
                        'accessToken',
                        'user' => [
                            'email',
                            'name',
                        ],
                    ],
                ],
            ])
            ->assertJson([
                'data' => [
                    'register' => [
                        'accessToken' => $access_token,
                        'user' => [
                            'email' => $userInput['input']['email'],
                            'name'  => "{$userInput['input']['firstName']} {$userInput['input']['lastName']}",
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @group       AuthGraphQL
     *
     * @param array $userInput
     *
     * @dataProvider getRegisterUserData
     *
     * @test
     */
    public function testAuthenticateSuccessfully(array $userInput): void
    {
        // create a User
        $User = app(config('auth.providers.users.model'));
        $User->name = "{$userInput['input']['firstName']} {$userInput['input']['lastName']}";
        $User->email = $userInput['input']['email'];
        $User->password = Hash::make($userInput['input']['password']);
        $User->save();

        // authenticate user
        $authenticateResponse = $this->postGraphQL([
            "query" => 'mutation($input: AuthenticateInput!) {
                authenticate(input: $input) {
                    accessToken
                    user {
                        email
                        name
                    }
                }
            }',
            "variables" => [
                "input" => [
                    "email" => $User->email,
                    "password" => $userInput['input']['password'],
                ]
            ],
        ]);

        // get accessToken (also check if it is exists)
        $access_token = $authenticateResponse->json("data.authenticate.accessToken");

        // check if we received token
        $this->assertNotEmpty($access_token);

        // check response
        $authenticateResponse->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'authenticate' => [
                        'accessToken',
                        'user' => [
                            'email',
                            'name',
                        ],
                    ],
                ],
            ])
            ->assertJson([
                'data' => [
                    'authenticate' => [
                        'accessToken' => $access_token,
                        'user' => [
                            'email' => $User->email,
                            'name'  => $User->name,
                        ],
                    ],
                ],
        ]);
    }

    /**
     * @group AuthGraphQL
     *
     * @test
     */
    public function testGetUserInfo(): void
    {
        // create a User
        $user_model_class = config('auth.providers.users.model');
        /** @var HasApiTokens|Model $User */
        $User = $user_model_class::factory()->create();

        // get user info through GraphQL
        $meResponse = $this->postGraphQL([
            "query" => 'query Me {
                me {
                    name
                    email
                }
              }',
        ], [
            'Authorization' => 'Bearer ' . $User->createToken($User->id)->plainTextToken,
        ]);

        // check response
        $meResponse->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'me' => [
                        'email',
                        'name',
                    ],
                ],
            ])
            ->assertJson([
                'data' => [
                    'me' => [
                        'email' => $User->email,
                        'name'  => $User->name,
                    ],
                ],
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

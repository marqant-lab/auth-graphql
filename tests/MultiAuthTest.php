<?php


namespace Marqant\AuthGraphQL\tests;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Marqant\AuthGraphQL\Models\AuthCriteria;
use Tests\TestCase;


/**
 * Unit tests for the logic to authenticate any model.
 *
 * Class MultiAuthTest
 * @package Marqant\AuthGraphQL\tests
 */
class MultiAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the polymorphic relationship.
     *
     * If this test fails, add the following trait to the User model:
     * use Marqant\AuthGraphQL\Traits\MultiAuth;
     * If it still fails, then the problem lies elsewhere.
     */
    public function testUserCanHaveCriteria()
    {
        /** @var User|AuthCriteria $User */
        $User = factory(User::class)->create();

        $random_criteria = Str::random(16);

        // Attach AuthCriteria to User
        $AuthCriteria = $User->setCriteria($random_criteria);

        // Check database entry exists
        $this->assertDatabaseHas($AuthCriteria->getTable(), $AuthCriteria->getAttributes());

        // Check that only one exists
        $this->assertDatabaseCount($AuthCriteria->getTable(), 1);
    }

    /**
     * Test whether the model can be correctly authenticated.
     */
    public function testCheckCredentials()
    {
        /** @var User|AuthCriteria $User */
        $User = factory(User::class)->create();

        /** @var AuthCriteria $AuthCriteria */
        $AuthCriteria = AuthCriteria::create([
            'id' => Str::random(64),
            'credentials' => '123456',
        ]);

        // Attach AuthCriteria to User
        $User->authCriteria()->save($AuthCriteria);

        /** @var User $User */
        $User = User::first();

        // Check if correct credentials deliver 'true'
        self::assertTrue(
            $User->checkCredentials('123456')
        );

        // Check if incorrect credentials deliver 'false'
        self::assertFalse(
            $User->checkCredentials(null)
        );
    }
}

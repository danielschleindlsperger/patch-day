<?php

namespace Tests\Feature\User;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAdminTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $admin;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $this->admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        Passport::actingAs($this->admin);
    }

    /** @test */
    public function an_admin_can_create_a_user_account()
    {
        $response = $this->json('POST', '/users', [
            'name' => 'Fake User',
            'email' => 'test',
            'password' => '123'
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'email' => [],
                'password' => [],
            ]);

        $response = $this->json('POST', '/users', [
            'name' => 'Fake User',
            'email' => 'hello@example.com',
            'password' => 'password',
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
    }
}

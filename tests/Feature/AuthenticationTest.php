<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'name' => 'Fake User',
            'email' => 'fake@example.com',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function a_user_can_login()
    {
        // unauthenticated
        $request = $this->json('GET', '/users/me');
        $request->assertStatus(401);

        // authenticated
        $this->actingAs($this->user);
        $request = $this->json('GET', '/users/me');
        $request->assertStatus(200);
        $request->assertJsonFragment([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'role' => $this->user->role,
        ]);
    }

    /** @test */
    public function a_user_can_logout()
    {
        // unauthenticated
        $this->assertTrue(Auth::guest());
        $this->json('GET', '/users/me')->assertStatus(401);

        // "logging in"
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // authenticated
        $this->assertFalse(Auth::guest());
        $this->assertEquals($user->id, Auth::user()->id);
        $this->json('GET', '/users/me')->assertStatus(200);

        // logging out
        $this->json('POST', '/logout')
             ->assertStatus(200)
             ->assertJson([
                 'success' => true,
             ]);

        // unauthenticated again
        $this->assertTrue(Auth::guest());
        $this->json('GET', '/users/me')->assertStatus(401);
    }

    /** @test */
    public function a_user_cannot_access_a_protected_route()
    {
        $response = $this->json('GET', '/companies');
        $response
            ->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthenticated.'
            ]);
    }
}

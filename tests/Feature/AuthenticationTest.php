<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    public function a_user_can_login()
    {
        // TODO: write test
    }

    public function a_user_can_logout()
    {
        // TODO: write test
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

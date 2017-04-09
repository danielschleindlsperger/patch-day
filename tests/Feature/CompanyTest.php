<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_create_company()
    {
        $response = $this->json('POST', '/company', [
            'name' => 'Example Company'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);
    }
}

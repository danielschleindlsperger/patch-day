<?php

namespace Tests\Feature;

use App\Protocol;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_see_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'comment' => 'It was good.',
            'done' => true,
            'due_date' =>Carbon::now()->toDateTimeString()
        ]);

        // request to non existing id
        $response = $this->json('GET', '/protocol/9543');
        $response
            ->assertStatus(404)
            ->assertSee('Specified protocol not found.');

        // request to actual id
        $response = $this->json('GET', '/protocol/'.$protocol->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                    'comment' => 'It was good.',
                    'done' => true,
            ]);
    }
}

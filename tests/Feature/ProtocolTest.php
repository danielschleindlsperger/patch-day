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
            'due_date' => Carbon::now()->toDateTimeString()
        ]);

        // request to non existing id
        $response = $this->json('GET', '/protocols/9543');
        $response
            ->assertStatus(404)
            ->assertSee('Specified protocol not found.');

        // request to actual id
        $response = $this->json('GET', '/protocols/' . $protocol->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'comment' => 'It was good.',
                'done' => true,
            ]);
    }

    /** @test */
    public function user_can_create_a_protocol()
    {
        $response = $this->json('POST', '/protocols', [
            'done' => 'yes',
            'due_date' => 'never'
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'due_date' => [
                    'The due date is not a valid date.'
                ],
                'done' => [
                    'The done field must be true or false.'
                ]
            ]);

        $response = $this->json('POST', '/protocols', [
            'comment' => 'It was good.',
            'done' => true,
            'due_date' => (new Carbon('now + 21 days'))->toDateTimeString()
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ]);
    }

    /** @test */
    public function user_can_edit_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'due_date' => Carbon::now()->toDateTimeString()
        ]);

        $this->assertFalse($protocol->done);
        $this->assertNull($protocol->comment);

        $response = $this->json('PUT', '/protocols/'.$protocol->id, [
            'comment' => '<p>It was good.</p>',
            'done' => true,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'updated' => true
            ]);

        $updatedProtocol = Protocol::find($protocol->id);

        $this->assertEquals('<p>It was good.</p>', $updatedProtocol->comment);
        $this->assertTrue($updatedProtocol->done);
    }
}

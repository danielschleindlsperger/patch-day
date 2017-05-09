<?php

namespace Tests\Feature;

use App\PatchDay;
use App\Project;
use App\User;
use App\Protocol;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolAdminTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_can_see_a_protocol()
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
    public function admin_can_create_a_protocol()
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
    public function admin_can_edit_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'due_date' => Carbon::now()->toDateTimeString(),
            'done' => false,
            'comment' => null,
        ]);

        $this->assertFalse($protocol->done);
        $this->assertNull($protocol->comment);

        $response = $this->json('PUT', '/protocols/' . $protocol->id, [
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

    /** @test */
    public function admin_can_see_upcoming_patch_days()
    {
        $project = factory(Project::class)->create();

        $protocols = factory(Protocol::class, 3)->create([
            'done' => false,
        ])
            ->each(function ($protocol, $index) use ($project) {
        $patchDay = factory(PatchDay::class)->create([
            'project_id' => $project->id,
        ]);

        $protocol->due_date = Carbon::now()->addWeek($index)->toDateString();
        $protocol->patch_day_id = $patchDay->id;
        $protocol->save();
    });

        $response = $this->json('GET', '/protocols/upcoming?limit=3');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'done',
                    'due_date',
                    'comment',
                    'patch_day' => [
                        'project'
                    ]
                ],
                [
                    'done',
                    'due_date',
                    'comment',
                    'patch_day' => [
                        'project'
                    ]
                ],
                [
                    'done',
                    'due_date',
                    'comment',
                    'patch_day' => [
                        'project'
                    ]
                ],
            ])
            // assert order
            ->assertJson([
                [
                    'id' => $protocols[0]->id
                ],
                [
                    'id' => $protocols[1]->id
                ],
                [
                    'id' => $protocols[2]->id
                ],
            ]);
    }
}

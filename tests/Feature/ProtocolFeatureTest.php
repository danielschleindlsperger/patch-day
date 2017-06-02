<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\User;
use App\Protocol;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolFeatureTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $company;
    protected $project;
    protected $patch_day;

    public function setUp()
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $this->patch_day = factory(PatchDay::class)->create();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function can_see_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'project_id' => $this->project->id,
            'patch_day_id' => $this->patch_day->id,
            'comment' => 'It was good.',
            'done' => true,
        ]);

        // request to non existing id
        $response = $this->json('GET', '/protocols/9543');
        $response
            ->assertStatus(404);

        // request to actual id
        $response = $this->json('GET', '/protocols/' . $protocol->id);
        $response
            ->assertStatus(200)
            ->assertJson([
                'comment' => 'It was good.',
                'done' => true,
                'price' => $this->project->base_price,
            ]);
    }

    /** @test */
    public function can_edit_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'project_id' => $this->project->id,
            'patch_day_id' => $this->patch_day->id,
            'comment' => null,
            'done' => false,
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
    public function can_see_upcoming_protocols()
    {
        $project = $this->project;

        $patch_days = factory(PatchDay::class, 3)->create()
            ->each(function ($patch_day, $index) {
                $patch_day->date = Carbon::now()->addWeek($index + 1)
                    ->toDateString();
                $patch_day->save();
            });

        $protocols = factory(Protocol::class, 3)->create([
            'done' => false,
            'comment' => null,
        ])
            ->each(function ($protocol, $index) use ($project, $patch_days) {
                $protocol->project_id = $project->id;
                $protocol->patch_day_id = $patch_days[$index]->id;
                $protocol->save();
            });

        $response = $this->json('GET', '/protocols/upcoming?limit=3');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'done',
                    'date',
                    'comment',
                ],
                [
                    'done',
                    'date',
                    'comment',
                ],
                [
                    'done',
                    'date',
                    'comment',
                ],
            ])
            // assert order
            ->assertJson([
                [
                    'date' => $patch_days[0]->date
                ],
                [
                    'date' => $patch_days[1]->date
                ],
                [
                    'date' => $patch_days[2]->date
                ],
            ]);
    }
}
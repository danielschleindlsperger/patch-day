<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Protocol;
use App\Technology;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayFeatureTest extends TestCase
{
    use DatabaseMigrations;

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
    public function admin_can_create_new_patch_day()
    {
        $this->json('POST', '/patch-days', [])
            ->assertStatus(422)
            ->assertJson([
                'date' => []
            ]);

        $this->json('POST', '/patch-days', [
            'date' => 'asdf'
        ])
            ->assertStatus(422)
            ->assertJson([
                'date' => []
            ]);

        $response = $this->json('POST', '/patch-days', [
            'date' => '2017-03-30',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'date' => '2017-03-30',
            ]);
    }

    /** @test */
    public function admin_can_update_patch_day()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $response = $this->json('PUT', '/patch-days/' . $patch_day->id, [
            'date' => 'asdf',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'date' => [],
                'status' => []
            ]);

        $response = $this->json('PUT', '/patch-days/' . $patch_day->id, [
            'date' => '2017-03-29',
            'status' => 'in_progress',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

        $patch_day = $patch_day->fresh();
        $this->assertEquals('2017-03-29', $patch_day->date);
    }

    /** @test */
    public function admin_can_see_patch_day_with_all_protocols_and_projects()
    {
        $company = factory(Company::class)->create();

        $projects = factory(Project::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $patch_day = factory(PatchDay::class)->create([
            'date' => Carbon::now()->addWeeks(2)->toDateString(),
        ]);

        $protocol_1 = factory(Protocol::class)->create([
            'project_id' => $projects[0]->id,
            'patch_day_id' => $patch_day->id,
        ]);

        $protocol_2 = factory(Protocol::class)->create([
            'project_id' => $projects[1]->id,
            'patch_day_id' => $patch_day->id,
        ]);

        $response = $this->json('GET', "/patch-days/{$patch_day->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'date',
                'id',
                'protocols' => [
                    [
                        'date',
                        'done',
                        'price',
                        'project_id',
                        'project' => [
                            'name',
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function can_see_all_patch_days()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $patch_day_2 = PatchDay::create([
            'date' => '2017-04-27',
        ]);

        $patch_day_3 = PatchDay::create([
            'date' => '2017-05-25',
        ]);

        $response = $this->json('GET', '/patch-days');
        $response->assertStatus(200)
            ->assertJson([
                [
                    'date' => '2017-05-25',
                    'id' => $patch_day_3->id,
                ],
                [
                    'date' => '2017-04-27',
                    'id' => $patch_day_2->id,
                ],
                [
                    'date' => '2017-03-30',
                    'id' => $patch_day->id,
                ],
            ]);
    }

    /** @test */
    public function can_see_todo_protocols_for_patch_day()
    {
        $patch_day = factory(PatchDay::class)->create();
        $project = factory(Project::class)->create();
        $protocols = factory(Protocol::class, 3)->create([
            'patch_day_id' => $patch_day->id,
            'project_id' => $project->id,
            'done' => false,
        ]);
        $protocols->first()->done = true;
        $protocols->first()->comment = 'Fake comment';
        $protocols->first()->save();

        $response = $this->json('GET', "/patch-days/{$patch_day->id}?todo=true");
        $response->assertStatus(200)
            ->assertJson([
                'protocols' => [
                    [
                        'id' => $protocols[1]->id,
                    ],
                    [
                        'id' => $protocols[2]->id,
                    ],
                ]
            ])
            ->assertJsonMissing([
                [
                    'id' => $protocols->first()->id,
                    'done' => true,
                    'comment' => 'Fake comment',
                ]
            ]);
    }

    /** @test */
    public function admin_can_delete_patch_day()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $response = $this->json('DELETE', '/patch-days/' . $patch_day->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $patch_day = $patch_day->fresh();

        $this->assertNull($patch_day);
        $this->assertNotInstanceOf(PatchDay::class, $patch_day);
    }

    /** @test */
    public function admin_can_see_upcoming_patch_days()
    {
        $patch_day_1 = factory(PatchDay::class)->create([
            'status' => 'done',
            'date' => Carbon::now()->subMonths(1)->toDateString(),
        ]);

        $patch_day_2 = factory(PatchDay::class)->create([
            'status' => 'in_progress',
            'date' => Carbon::now()->toDateString(),
        ]);

        $patch_day_3 = factory(PatchDay::class)->create([
            'status' => 'upcoming',
            'date' => Carbon::now()->addMonths(1)->toDateString(),
        ]);

        $patch_day_4 = factory(PatchDay::class)->create([
            'status' => 'upcoming',
            'date' => Carbon::now()->addMonths(2)->toDateString(),
        ]);

        $response = $this->json('GET', '/patch-days/upcoming');
        // only patch days that aren't done are returned
        // ordered by ascending date
        $response->assertStatus(200)
            ->assertJson([
                [
                    'id' => $patch_day_2->id,
                ],
                [
                    'id' => $patch_day_3->id,
                ],
                [
                    'id' => $patch_day_4->id,
                ],
            ]);
    }
}
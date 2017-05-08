<?php

namespace Tests\Feature\Project;

use App\Company;
use App\Protocol;
use App\User;
use App\PatchDay;
use App\Project;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectAdminTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $company;

    public function setUp()
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_can_view_project_overview()
    {
        $projects = factory(Project::class, 2)->create();

        $response = $this->json('GET', '/projects');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $projects->all()[0]->name,
            ],
                [
                    'name' => $projects->all()[1]->name,
                ]
            );
    }

    /** @test */
    public function admin_can_create_project()
    {
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
    }

    /** @test */
    public function admin_cannot_create_project_without_providing_a_name()
    {
        $response = $this->json('POST', '/projects', [
            '' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'name', 'company_id'
            ]);
    }

    /** @test */
    public function admin_can_upate_a_project_and_associated_patch_day()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $patchDay = factory(PatchDay::class)->create([
            'interval' => 3,
            'cost' => 30000,
            'active' => false,
            'start_date' => Carbon::now()->addWeek(2)->toDateString(),
        ]);
        $patchDay->project()->associate($project);
        $patchDay->save();

        $this->assertEquals('Test Project', $project->name);
        $this->assertInstanceOf(PatchDay::class, $project->patchDay);

        $response = $this->json('PUT', '/projects/' . $project->id, [
            'name' => 'Updated Project',
            'patch_day' => [
                'cost' => 20000,
                'active' => true,
                'interval' => 4,
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                [
                    'success' => true
                ]
            ]);

        $updatedProject = Project::with('patchDay')->find($project->id);

        $this->assertEquals('Updated Project', $updatedProject->name);
        $this->assertEquals($updatedProject->patchDay->id, $patchDay->id);
        $this->assertEquals($updatedProject->patchDay->cost, 20000);
        $this->assertEquals($updatedProject->patchDay->active, true);
        $this->assertEquals($updatedProject->patchDay->interval, 4);
    }

    /** @test */
    public function admin_can_view_specific_project_with_associated_patch_day()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        // associated patch-day
        $patchDay = factory(PatchDay::class)->create(['cost' => 300]);
        $patchDay->project()->associate($project);
        $patchDay->save();

        $response = $this->json('GET', '/projects/' . $project->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'name',
                    'patch_day' => [
                        'cost', 'start_date', 'interval', 'active',
                    ]
                ]
            )
            ->assertJsonFragment([
                'name' => 'Test Project',
            ]);
    }

    /** @test */
    public function admin_can_delete_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $this->assertEquals('Test Project', $project->name);

        $response = $this->json('DELETE', '/projects/' . $project->id);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                [
                    'success' => true
                ]
            ]);

        $response = $this->json('GET', '/projects/' . $project->id);
        $response->assertStatus(404);
    }

    /** @test */
    public function admin_can_see_projects_protocols()
    {
        // create project/patch-day and associate
        $project = factory(Project::class)->create();
        $patchDay = factory(PatchDay::class)->create(['cost' => 300]);
        $patchDay->project()->associate($project);
        $patchDay->save();

        // create protocols and associate
        $protocol = factory(Protocol::class)->create();
        $protocol->patchDay()->associate($patchDay);
        $protocol->save();
        $protocol2 = factory(Protocol::class)->create();
        $protocol2->patchDay()->associate($patchDay);
        $protocol2->save();

        $response = $this->json('GET', '/projects/' . $project->id . '/protocols');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    [
                        'id', 'comment', 'done', 'due_date'
                    ],
                    [
                        'id', 'comment', 'done', 'due_date'
                    ]
                ]
            );
    }
}

<?php

namespace Tests\Feature\Project;

use App\Company;
use App\User;
use App\PatchDay;
use App\Project;
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
    public function admin_can_upate_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $this->assertEquals('Test Project', $project->name);

        $response = $this->json('PUT', '/projects/' . $project->id, [
            'name' => 'Updated Project'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                [
                    'success' => true
                ]
            ]);
    }

    /** @test */
    public function admin_can_view_specific_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $response = $this->json('GET', '/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $project->name,
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
    public function admin_can_see_projects_patch_day()
    {
        $project = factory(Project::class)->create();
        $patchDay = factory(PatchDay::class)->create(['cost' => 300]);

        $patchDay->project()->associate($project);

        $patchDay->save();

        $response = $this->json('GET', '/projects/' . $project->id . '/patch-day');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                    'cost' => 300
                ]
            );
    }
}

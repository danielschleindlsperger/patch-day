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

class ProjectClientTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $client;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $this->client = factory(User::class)->create();
        $this->actingAs($this->client);
    }

    /** @test */
    public function client_cannot_view_project_overview()
    {
        $projects = factory(Project::class, 2)->create();

        $response = $this->json('GET', '/projects');
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_create_project()
    {
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_update_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $response = $this->json('PUT', '/projects/' . $project->id, [
            'name' => 'Updated Project'
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_view_specific_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);
        $company = factory(Company::class)->create();

        $this->client->company()->associate($company);
        $this->client->save();

        // project doesn't belong to users company, so this should fail
        $response = $this->json('GET', '/projects/' . $project->id);
        $response->assertStatus(403);

        $project->company()->associate($company);
        $project->save();

        $response = $this->json('GET', '/projects/' . $project->id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $project->name,
            ]);
    }

    /** @test */
    public function client_cannot_delete_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $response = $this->json('DELETE', '/projects/' . $project->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_see_projects_patch_day()
    {
        $company = factory(Company::class)->create();
        $project = factory(Project::class)->create();
        $patchDay = factory(PatchDay::class)->create(['cost' => 300]);

        $patchDay->project()->associate($project);
        $patchDay->save();

        $this->client->company()->associate($company);
        $this->client->save();

        // project not associated with users company, should fail
        $response = $this->json('GET', '/projects/' . $project->id . '/patch-day');
        $response->assertStatus(403);

        $project->company()->associate($company);
        $project->save();

        $response = $this->json('GET', '/projects/' . $project->id . '/patch-day');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'cost' => 300,
            ]);
    }
}

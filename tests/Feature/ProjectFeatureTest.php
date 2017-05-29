<?php

namespace Tests\Feature\Project;

use App\Company;
use App\Protocol;
use App\Technology;
use App\User;
use App\PatchDay;
use App\Project;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectFeatureTest extends TestCase
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
    public function can_view_project_overview()
    {
        $projects = factory(Project::class, 2)->create();

        $response = $this->json('GET', '/projects');

        $response->assertStatus(200)
            ->assertJson([
                [
                    'name' => $projects->all()[0]->name,
                ],
                [
                    'name' => $projects->all()[1]->name,
                ]
            ]);
    }

    /** @test */
    public function can_create_project()
    {
        // bad request
        $response = $this->json('POST', '/projects', []);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'name',
                'company_id'
            ]);

        // barebones project without additional data
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
        $project = Project::orderBy('id', 'desc')->first();

        $this->assertNotNull($project);
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Example Project', $project->name);

        // project with all data provided
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project 2',
            'base_price' => 40000,
            'penalty' => 15000,
            'company_id' => $this->company->id,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
        $project_2 = Project::orderBy('id', 'desc')->first();

        $this->assertNotNull($project_2);
        $this->assertInstanceOf(Project::class, $project_2);
        $this->assertEquals('Example Project 2', $project_2->name);
        $this->assertEquals(40000, $project_2->base_price);
        $this->assertEquals(15000, $project_2->penalty);
    }

    /** @test */
    public function admin_can_create_project_with_default_technologies()
    {
        $latestLaravel = Technology::create([
            'name' => 'Laravel',
            'version' => '5.4.23',
        ]);

        $latestVue = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.4.12',
        ]);

        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
            'technologies' => [
                'asdf',
                'asdf'
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'technologies.0',
                'technologies.1',
            ]);

        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
            'technologies' => [
                $latestLaravel->id,
                $latestVue->id,
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);

        $project = Project::orderBy('id', 'desc')->first();

        $technologies = $project->technology_history;

        $this->assertContainsOnlyInstancesOf(Technology::class, $technologies);
        $this->assertCount(2, $technologies);
        $this->assertEquals('Laravel', $technologies[0]->name);
        $this->assertEquals('5.4.23', $technologies[0]->version);
        $this->assertEquals('Vue.js', $technologies[1]->name);
        $this->assertEquals('2.4.12', $technologies[1]->version);
    }

    /** @test */
    public function cannot_create_project_without_providing_a_name()
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
    public function can_upate_a_project_and_associated_patch_day()
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
    public function can_view_project_with_patch_day_and_protocols()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $response = $this->json('GET', '/projects/' . $project->id);
        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Test Project',
                'id' => $project->id,
            ]);

        // associated patch-day
        $patchDay = factory(PatchDay::class)->create([
            'cost' => 300,
            'project_id' => $project->id,
        ]);

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

        // associate protocols
        factory(Protocol::class, 5)->create([
            'patch_day_id' => $patchDay->id,
        ]);
        $response = $this->json('GET', '/projects/' . $project->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'patch_day' => [
                    'cost',
                    'start_date',
                    'interval',
                    'active',
                    'protocols' => [
                        [
                            'comment', 'done'
                        ]
                    ],
                ],
            ]);
    }

    /** @test */
    public function can_delete_a_project()
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
}

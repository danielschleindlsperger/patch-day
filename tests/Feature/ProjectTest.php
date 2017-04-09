<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_view_project_overview()
    {
        $projects = factory(Project::class, 2)->create();

        $response = $this->json('GET', '/project');

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
    public function user_can_create_project()
    {
        $response = $this->json('POST', '/project', [
            'name' => 'Example Project'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
    }

    /** @test */
    public function user_cannot_create_project_without_providing_a_name()
    {
        $response = $this->json('POST', '/project', [
            '' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                [
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    /** @test */
    public function user_can_upate_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $this->assertEquals('Test Project', $project->name);

        $response = $this->json('PUT', '/project/' . $project->id, [
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
    public function user_can_view_specific_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $response = $this->json('GET', '/project/' . $project->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $project->name,
            ]);
    }

    /** @test */
    public function user_can_delete_a_project()
    {
        $project = factory(Project::class)->create([
            'name' => 'Test Project',
        ]);

        $this->assertEquals('Test Project', $project->name);

        $response = $this->json('DELETE', '/project/' . $project->id);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                [
                    'success' => true
                ]
            ]);

        $response = $this->json('GET', '/project/' . $project->id);

        $response->assertStatus(404);
    }
}

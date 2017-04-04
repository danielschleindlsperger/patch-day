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
        $projects = factory(Project::class, 5)->create();

        $response = $this->json('GET', '/projects');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $projects->all()[0]->name,
            ]);
    }

    /** @test */
    public function user_can_create_project()
    {
        $response = $this->json('POST', '/projects/create', [
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
        $response = $this->json('POST', '/projects/create', [
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
}

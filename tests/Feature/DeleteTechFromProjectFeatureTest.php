<?php

namespace Tests\Feature;

use App\Project;
use App\Technology;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTechFromProjectFeatureTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $project;
    protected $laravel;
    protected $vue;


    function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['role' => 'admin']);
        $this->actingAs($this->user);

        $this->laravel = factory(Technology::class)->create([
            'name' => 'laravel',
            'version' => '5.4.23',
        ]);

        $this->vue = factory(Technology::class)->create([
            'name' => 'Vue.js',
            'version' => '2.4.2',
        ]);

        $this->project = factory(Project::class)->create();

        $this->project->technologies()->attach([
            $this->laravel->id => ['action' => 'default'],
            $this->vue->id => ['action' => 'default'],
        ]);

    }

    /** @test */
    function can_delete_tech_from_project()
    {
        $response = $this->json('DELETE', "/projects/{$this->project->id}/delete-technology", [
            'tech' => $this->laravel->id
        ]);

        $response->assertStatus(200);

        $this->project = $this->project->fresh();

        $this->assertCount(1, $this->project->currentTechnologies);
        $this->assertEquals('Vue.js', $this->project->currentTechnologies->first()->name);
    }
}

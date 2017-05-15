<?php

namespace Tests\Feature;

use App\PatchDay;
use App\Project;
use App\Technology;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TechnologyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $project;
    protected $patchDay;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }


    public function admin_can_create_project_with_default_technologies()
    {
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
            'patch_day' => [
                'cost' => 15000,
                'active' => false,
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);

        $project = Project::all()->last();
        $patchDay = PatchDay::all()->last();

        $this->assertInstanceOf(PatchDay::class, $patchDay);
        $this->assertInstanceOf(PatchDay::class, $project->patchDay);
        $this->assertEquals($patchDay->project_id, $project->id);
        $this->assertEquals(15000, $patchDay->cost);
        $this->assertFalse($patchDay->active);
        $this->assertEmpty($patchDay->comment);
    }

    /** @test */
    public function admin_can_update_technologies()
    {
        $patchDay = factory(PatchDay::class)->create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
            'project_id' => $this->project->id,
        ]);

        $this->assertNotInstanceOf
        (Technology::class, $patchDay->technologies->first());

        $latestPhp = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.3.3',
        ]);

        $latestVue = Technology::create([
            'name' => 'php',
            'version' => '7.0.30',
        ]);

        $response = $this->json('PUT', '/projects/' . $this->project->id, [
            'patch_day' => [
                'technologies' => [
                    $latestPhp->id,
                    $latestVue->id,
                ],
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);

        $vue = $patchDay->technologies()->first();
        $this->assertInstanceOf(Technology::class, $vue);
        $this->assertEquals('Vue.js', $vue->name);
        $this->assertEquals('2.3.3', $vue->version);
    }
}
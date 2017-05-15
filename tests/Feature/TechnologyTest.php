<?php

namespace Tests\Feature;

use App\Company;
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

    protected $company;
    protected $project;
    protected $patchDay;

    public function setUp()
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_can_create_project_with_default_technologies()
    {
        $latestPhp = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.3.3',
        ]);

        $latestLaravel = Technology::create([
            'name' => 'Laravel',
            'version' => '5.4.23',
        ]);

        $latestVue = Technology::create([
            'name' => 'php',
            'version' => '7.0.30',
        ]);

        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project',
            'company_id' => $this->company->id,
            'patch_day' => [
                'cost' => 15000,
                'active' => false,
                'technologies' => [
                    $latestPhp->id,
                    $latestLaravel->id,
                    $latestVue->id,
                ]
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);

        $patchDay = PatchDay::all()->last();
        $technologies = $patchDay->technologies()->get();
        $updatedLaravel = $technologies->where('name', 'Laravel')->first();

        $this->assertContainsOnlyInstancesOf(Technology::class, $technologies);
        $this->assertCount(3, $technologies);
        $this->assertEquals('Laravel', $updatedLaravel->name);
        $this->assertEquals('5.4.23', $updatedLaravel->version);
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
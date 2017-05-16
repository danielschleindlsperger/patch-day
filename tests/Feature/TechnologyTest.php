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
    public function admin_can_update_a_projects_technologies()
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

    /** @test */
    public function admin_can_create_new_technology()
    {
        $this
            ->json('POST', '/technologies', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'name',
                'version'
            ]);

        $this
            ->json('POST', '/technologies', [
                'name' => 'Laravel',
                'version' => '5.5.1',
            ])
            ->assertStatus(200)
            ->assertJson(['created' => true]);

        $tech = Technology::all()->last();
        $this->assertInstanceOf(Technology::class, $tech);
        $this->assertEquals('Laravel', $tech->name);
        $this->assertEquals('5.5.1', $tech->version);
    }

    /** @test */
    public function admin_can_update_technology()
    {
        $tech = Technology::create([
            'name' => 'Laravel',
            'version' => '5.5.1',
        ]);

        $this
            ->json('PUT', '/technologies/' . $tech->id, [
                'name' => 'Laravel',
                'version' => '5.4.30',
            ])
            ->assertStatus(200)
            ->assertJson(['updated' => true]);

        $tech = Technology::find($tech->id);
        $this->assertEquals('Laravel', $tech->name);
        $this->assertEquals('5.4.30', $tech->version);
    }

    /** @test */
    public function can_see_all_technologies()
    {
        $vue = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.2.2',
        ]);
        $vue2 = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.2.1',
        ]);
        $vue3 = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.3.1',
        ]);

        $php = Technology::create([
            'name' => 'php',
            'version' => '7.1.4',
        ]);
        $php2 = Technology::create([
            'name' => 'php',
            'version' => '5.6.30',
        ]);
        $php3 = Technology::create([
            'name' => 'php',
            'version' => '7.0.10',
        ]);


        $this
            ->json('GET', '/technologies')
            ->assertStatus(200)
            // assert order (first alphabetical names, then version numbers)
            ->assertJson([
                [
                    'name' => 'php',
                    'version' => '7.1.4',
                ],
                [
                    'name' => 'php',
                    'version' => '7.0.10',
                ],
                [
                    'name' => 'php',
                    'version' => '5.6.30',
                ],
                [
                    'name' => 'Vue.js',
                    'version' => '2.3.1',
                ],
                [
                    'name' => 'Vue.js',
                    'version' => '2.2.2',
                ],
                [
                    'name' => 'Vue.js',
                    'version' => '2.2.1',
                ],
            ]);
    }

    /** @test */
    public function admin_can_delete_technology()
    {
        $technologies = factory(Technology::class, 50)->create();

        $faultyTech = factory(Technology::class)->create([
            'name' => 'Joomla',
            'version' => '3.6.10',
        ]);

        $count = Technology::all()->count();

        $this
            ->json('DELETE', '/technologies/' . $faultyTech->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $tech = Technology::find($faultyTech->id);

        $this->assertNull($tech);
        $this->assertNotInstanceOf(Technology::class, $tech);
        $this->assertEquals($count - 1, Technology::all()->count());
    }
}
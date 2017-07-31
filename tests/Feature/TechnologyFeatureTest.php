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

class TechnologyFeatureTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $company;
    protected $project;

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
            ->assertJson(
                [
                    'name' => 'Laravel',
                    'version' => '5.5.1',
                ]
            );

        $tech = Technology::orderBy('id', 'desc')->first();
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

    /** @test */
    public function can_see_all_versions_for_a_tech()
    {
        $php = factory(Technology::class)->create([
            'name' => 'php',
            'version' => '7.0.0',
        ]);
        $vue = factory(Technology::class)->create([
            'name' => 'Vue.js',
            'version' => '2.1.6',
        ]);
        $vue2 = factory(Technology::class)->create([
            'name' => 'Vue.js',
            'version' => '2.2.4',
        ]);
        $vue3 = factory(Technology::class)->create([
            'name' => 'Vue.js',
            'version' => '0.1.19',
        ]);

        $this
            ->json('GET', '/technologies/' . urlencode('Vue.js'))
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'Vue.js',
                    'version' => '2.2.4',
                ],
                [
                    'name' => 'Vue.js',
                    'version' => '2.1.6',
                ],
                [
                    'name' => 'Vue.js',
                    'version' => '0.1.19',
                ],
            ])
            ->assertDontSeeText('php');
    }
}
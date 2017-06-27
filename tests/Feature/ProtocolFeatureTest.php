<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Technology;
use App\User;
use App\Protocol;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolFeatureTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $company;
    protected $project;
    protected $patch_day;

    public function setUp()
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();

        $this->project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $this->patch_day = factory(PatchDay::class)->create();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function can_see_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'project_id' => $this->project->id,
            'patch_day_id' => $this->patch_day->id,
            'comment' => 'It was good.',
            'done' => true,
        ]);

        // request to non existing id
        $response = $this->json('GET', '/protocols/9543');
        $response
            ->assertStatus(404);

        // request to actual id
        $response = $this->json('GET', '/protocols/' . $protocol->id);
        $response
            ->assertStatus(200)
            ->assertJson([
                'comment' => 'It was good.',
                'done' => true,
                'price' => $this->project->base_price,
            ]);
    }

    /** @test */
    public function can_edit_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'project_id' => $this->project->id,
            'patch_day_id' => $this->patch_day->id,
            'comment' => null,
            'done' => false,
        ]);

        $this->assertFalse($protocol->done);
        $this->assertNull($protocol->comment);

        $response = $this->json('PUT', "/protocols/{$protocol->id}", [
            'comment' => '<p>It was good.</p>',
            'done' => true,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'updated' => true
            ]);

        $updatedProtocol = Protocol::find($protocol->id);

        $this->assertEquals('<p>It was good.</p>', $updatedProtocol->comment);
        $this->assertTrue($updatedProtocol->done);
    }

    /** @test */
    public function can_edit_a_protocols_tech_updates()
    {
        $protocol = factory(Protocol::class)->create([
            'project_id' => $this->project->id,
            'patch_day_id' => $this->patch_day->id,
            'comment' => null,
            'done' => false,
        ]);

        $tech = factory(Technology::class)->create([
            'name' => 'Laravel',
            'version' => '5.4.30',
        ]);

        $this->project->technologies()->attach([
            $tech->id => ['protocol_id' => $protocol->id]
        ]);

        $techs = $this->project->technologies()->get();

        $this->assertCount(1, $techs);
        $this->assertEquals('Laravel', $techs[0]->name);
        $this->assertEquals('5.4.30', $techs[0]->version);

        $updatedTech = factory(Technology::class)->create([
            'name' => 'Laravel',
            'version' => '5.5.0',
        ]);

        $response = $this->json('PUT', "/protocols/{$protocol->id}", [
            'technology_updates' => [
                $updatedTech->id,
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'updated' => true
            ]);

        $updatedTechs = $this->project->technologies()->get();

        $this->assertCount(1, $updatedTechs);
        $this->assertEquals('Laravel', $updatedTechs[0]->name);
        $this->assertEquals('5.5.0', $updatedTechs[0]->version);
    }
}

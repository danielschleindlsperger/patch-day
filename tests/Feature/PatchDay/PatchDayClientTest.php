<?php

namespace Tests\Feature\PatchDay;

use App\Company;
use App\User;
use App\Protocol;
use App\Project;
use App\PatchDay;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayClientTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $project;
    protected $patchDay;
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();
        $this->patchDay = factory(PatchDay::class)->create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
        ]);
        $this->patchDay->project()->associate($this->project);
        $this->patchDay->save();

        // Auth
        $this->client = factory(User::class)->create();
        Passport::actingAs($this->client);
    }

    /** @test */
    public function client_can_see_a_patchday()
    {
        // patch-day doesn't belong to client, should fail
        $response = $this->json('GET', 'patch-days/'.$this->patchDay->id);
        $response
            ->assertStatus(403);

        $company = factory(Company::class)->create();

        $this->client->company()->associate($company);
        $this->client->save();

        $this->project->company()->associate($company);
        $this->project->save();

        $response = $this->json('GET', 'patch-days/'.$this->patchDay->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'cost' => "200",
                'active' => "1",
                'project_id' => (string) $this->project->id,
            ]);
    }

    /** @test */
    public function client_cannot_create_a_patchday()
    {
        $response = $this->json('POST', 'patch-days', [
            'cost' => 200,
            'start_date' => (new Carbon('now +2 weeks'))->toDateString(),
            'active' => true,
            'project_id' => (string) $this->project->id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_edit_a_patchday()
    {
        // response with valid data
        $response = $this->json('PUT', 'patch-days/'.$this->patchDay->id, [
            'cost' => 500,
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_delete_a_patchday()
    {
        $patchDay = factory(PatchDay::class)->create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
        ]);
        $patchDay->project()->associate($this->project);
        $patchDay->save();

        $this->assertNotNull($patchDay);
        $this->assertInstanceOf(PatchDay::class, $patchDay);

        $response = $this->json('DELETE', '/patch-days/'.$patchDay->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_see_a_patchdays_protocols()
    {
        $protocol = factory(Protocol::class)->create();
        $protocol2 = factory(Protocol::class)->create();

        $protocol->patchDay()->associate($this->patchDay);
        $protocol2->patchDay()->associate($this->patchDay);
        $protocol->save();
        $protocol2->save();

        $response = $this->json('GET', '/patch-days/'.$this->patchDay->id.'/protocols');
        $response->assertStatus(403);

        $company = factory(Company::class)->create();

        $this->client->company()->associate($company);
        $this->client->save();

        $this->project->company()->associate($company);
        $this->project->save();

        $response = $this->json('GET', '/patch-days/'.$this->patchDay->id.'/protocols');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $protocol->id
        ]);
        $response->assertJsonFragment([
            'id' => $protocol2->id
        ]);
    }
}
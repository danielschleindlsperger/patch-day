<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Protocol;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDaySignupFeatureTest extends TestCase
{
    use DatabaseMigrations;

    protected $company;
    protected $client;
    protected $patch_day;

    public function setUp()
    {
        parent::setUp();

        $this->patch_day = PatchDay::create([
            'date' => Carbon::now()->subWeeks(1)->toDateString(),
        ]);

        $this->company = factory(Company::class)->create();

        $this->client = factory(User::class)->create([
            'role' => 'client',
            'company_id' => $this->company->id,
        ]);

        $this->actingAs($this->client);
    }

    /** @test */
    public function a_client_can_sign_his_project_up_for_a_patch_day()
    {
        $project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        // has to be in the future
        $response = $this->json('POST', '/projects/' . $project->id .
            '/patch-days', [
            'patch_day_id' => $this->patch_day->id,
        ]);
        $response->assertStatus(422);

        $this->patch_day->date = Carbon::parse($this->patch_day->date)
            ->addWeeks(2);
        $this->patch_day->save();

        $response = $this->json('POST', '/projects/' . $project->id .
            '/patch-days', [
            'patch_day_id' => $this->patch_day->id,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'patch_day_id' => $this->patch_day->id,
                'project_id' => $project->id,
                'date' => $this->patch_day->date,
                'price' => $project->base_price,
            ]);
    }

    /** @test */
    public function a_client_can_cancel_a_projects_patch_day()
    {
        $project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $patch_day = factory(PatchDay::class)->create([
            'date' => Carbon::now()->addWeeks(2)->toDateString(),
        ]);

        $response = $this->json('POST', '/projects/' . $project->id . '/patch-days', [
            'patch_day_id' => $patch_day->id,
        ]);


        $response->assertStatus(200);

        $protocols = Protocol::all();
        $protocol = $project->protocols()->first();

        $this->assertNotNull($protocols);
        $this->assertCount(1, $protocols);
        $this->assertTrue($patch_day->projects->contains($project));

        $response = $this->json('DELETE', '/protocols/' . $protocol->id)
            ->assertStatus(200);

        $protocols = Protocol::all();
        $protocol = $project->protocols()->first();

        $this->assertNull($protocol);
        $this->assertCount(0, $protocols);
        $this->assertFalse($patch_day->projects->contains($project));
    }
}

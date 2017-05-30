<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
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
            'date' => Carbon::now()->addWeeks(1)->toDateString(),
        ]);

        $this->company = factory(Company::class)->create();

        $this->client = factory(User::class)->create([
            'role' => 'client',
            'company_id' => $this->company->id,
        ]);

        $this->actingAs($this->client);
    }

    /** @test */
    public function a_client_can_his_project_up_for_a_patch_day()
    {
        $project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $response = $this->json('POST', '/projects/' . $project->id .
            '/patch-day-signup', [
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
}

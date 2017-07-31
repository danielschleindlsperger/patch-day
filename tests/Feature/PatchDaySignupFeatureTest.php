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

    public function setUp()
    {
        parent::setUp();

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

        $patch_day = PatchDay::create([
            'date' => Carbon::now()->subWeeks(1)->toDateString(),
        ]);

        // has to be in the future
        $response = $this->json('POST', "/projects/{$project->id}/signup", [
            'patch_day_id' => $patch_day->id,
        ]);
        $response->assertStatus(422);

        $patch_day->date = Carbon::parse($patch_day->date)->addWeeks(2);
        $patch_day->save();

        $response = $this->json('POST', "/projects/{$project->id}/signup", [
            'patch_day_id' => $patch_day->id,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'patch_day_id' => $patch_day->id,
                'project_id' => $project->id,
                'date' => $patch_day->date,
                'price' => $project->base_price,
            ]);
    }

    /** @test */
    function a_client_can_see_the_patch_days_their_project_is_signed_up_for()
    {
        $project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $patch_days = factory(PatchDay::class, 5)->create()
            ->each(function ($patch_day) use ($project) {
                Protocol::create([
                    'project_id' => $project->id,
                    'patch_day_id' => $patch_day->id,
                ]);
            });

        $patch_days_relevant_keys = $patch_days->map(function ($patch_day) {
            return collect($patch_day->toArray())
                ->only(['id', 'name'])
                ->all();
        })->toArray();

        $response = $this->json('GET', "/projects/{$project->id}/registered-patch-days");

        $response->assertStatus(200);
        $response->assertJson($patch_days_relevant_keys);
        $this->assertCount(5, $response->json());
    }

    /** @test */
    function a_client_can_see_the_patch_days_their_project_can_sign_up_for()
    {
        $project = factory(Project::class)->create([
            'company_id' => $this->company->id,
        ]);

        $patch_days = factory(PatchDay::class, 5)->create();

        $patch_days->slice(0, 2)->each(function ($patch_day, $index) use ($project) {
            Protocol::create([
                'project_id' => $project->id,
                'patch_day_id' => $patch_day->id,
            ]);
        });

        $response = $this->json('GET', "/projects/{$project->id}/signup");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $patch_days[4]->id,
        ]);
        $this->assertCount(3, $response->json());
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

        $protocol = Protocol::create([
            'project_id' => $project->id,
            'patch_day_id' => $patch_day->id,
        ]);

        $this->assertTrue($patch_day->projects->contains($project));

        Carbon::setTestNow(Carbon::now()->addWeeks(3));
        // has to be in the future
        $response = $this->json('DELETE', "/projects/{$project->id}/cancel", [
                'patch_day_id' => $patch_day->id,
            ]);
        $response->assertStatus(422);

        Carbon::setTestNow();
        $response = $this->json('DELETE',"/projects/{$project->id}/cancel", [
                'patch_day_id' => $patch_day->id,
            ])
            ->assertStatus(200);

        $this->assertFalse($patch_day->projects->contains($project));
    }
}

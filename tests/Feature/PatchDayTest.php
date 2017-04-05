<?php

namespace Tests\Feature;

use App\Project;
use App\PatchDay;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_see_a_patchday()
    {
        $project = factory(Project::class)->create();

        $patchDay = PatchDay::create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
        ]);
        $patchDay->project()->associate($project);
        $patchDay->save();

        $response = $this->json('GET', 'patch-day/'.$patchDay->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'cost' => "200",
                'active' => "1",
                'project_id' => (string) $project->id,
            ]);
    }

    /** @test */
    public function user_can_create_a_patchday()
    {
        $project = factory(Project::class)->create();

        $response = $this->json('POST', 'patch-day', [
            'cost' => 200,
            'start_date' => (new Carbon('now +2 weeks'))->toDateString(),
            'active' => true,
            'project_id' => (string) $project->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);
    }
}

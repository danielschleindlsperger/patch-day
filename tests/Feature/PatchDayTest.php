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

    protected $project;
    protected $patchDay;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();
        $this->patchDay = PatchDay::create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
        ]);
        $this->patchDay->project()->associate($this->project);
        $this->patchDay->save();
    }

    /** @test */
    public function user_can_see_a_patchday()
    {
        $response = $this->json('GET', 'patch-day/'.$this->patchDay->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'cost' => "200",
                'active' => "1",
                'project_id' => (string) $this->project->id,
            ]);
    }

    /** @test */
    public function user_can_create_a_patchday()
    {
        $response = $this->json('POST', 'patch-day', [
            'cost' => 200,
            'start_date' => (new Carbon('now +2 weeks'))->toDateString(),
            'active' => true,
            'project_id' => (string) $this->project->id,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);
    }
}

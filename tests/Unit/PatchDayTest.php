<?php

namespace Tests\Unit;

use App\Technology;
use Tests\TestCase;
use App\Project;
use App\PatchDay;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function patchday_has_a_project()
    {
        $project = Project::create([
            'name' => 'Test Project',
        ]);

        $patchDay = PatchDay::create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
            'project_id' => $project->id,
        ]);

        $this->assertNotNull($patchDay->project);
        $this->assertInstanceOf(Project::class, $patchDay->project);
    }

    /** @test */
    public function it_can_have_several_technologies()
    {
        $project = Project::create([
            'name' => 'Test Project',
        ]);

        $patchDay = PatchDay::create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
            'project_id' => $project->id,
        ]);

        $backendTechnology = Technology::create([
            'name' => 'php',
            'version' => '7.0.30',
        ]) ;

        $frontendTechnology = Technology::create([
            'name' => 'jQuery',
            'version' => '3.2.1',
        ]);

        // has no technologies
        $this->assertNotInstanceOf(Technology::class,
        $patchDay->technologies()->first());

        $patchDay->technologies()->attach($backendTechnology);
        $patchDay->technologies()->attach($frontendTechnology);

        // has technologies
        $patchDay = PatchDay::with('technologies')->find($patchDay->id);
        $this->assertNotNull($patchDay->technologies);
        $this->assertInstanceOf(Technology::class,
            $patchDay->technologies->first());

        $this->assertEquals('jQuery', $patchDay->technologies[1]->name);
    }
}

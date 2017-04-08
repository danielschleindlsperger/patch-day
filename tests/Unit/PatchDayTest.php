<?php

namespace Tests\Unit;

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
        ]);

        $this->assertNull($patchDay->project);
        $this->assertNotInstanceOf(Project::class, $patchDay->project);

        $patchDay->project()->associate($project);
        $patchDay->save();

        $this->assertNotNull($patchDay->project);
        $this->assertInstanceOf(Project::class, $patchDay->project);
    }
}

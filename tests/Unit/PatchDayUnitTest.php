<?php

namespace Tests\Unit;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayUnitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_patch_day_has_a_date()
    {
        try {
            //date required
            $patch_day = PatchDay::create();
        } catch (\Exception $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }

        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->assertNotNull($patch_day);
        $this->assertInstanceOf(PatchDay::class, $patch_day);
        $this->assertEquals('2017-03-30', $patch_day->date);
    }

    /** @test */
    public function a_patch_days_has_a_name()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->assertEquals('PatchDay March 2017', $patch_day->name);
    }

    /** @test */
    public function a_patch_day_has_a_status()
    {
        $patch_day = factory(PatchDay::class)->create();

        // fetch again from database to get default values
        $patch_day = $patch_day->fresh();

        $this->assertEquals('upcoming', $patch_day->status);
    }

    /** @test */
    public function a_patch_day_has_its_registered_projects()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $project = Project::create([
            'name' => 'Fake Project 1',
        ]);

        $protocol = Protocol::create([
            'patch_day_id' => $patch_day->id,
            'project_id' => $project->id,
        ]);

        $project_2 = Project::create([
            'name' => 'Fake Project 2',
        ]);

        $protocol_2 = Protocol::create([
            'patch_day_id' => $patch_day->id,
            'project_id' => $project_2->id,
        ]);

        $projects = $patch_day->projects;
        $this->assertNotNull($projects);
        $this->assertCount(2, $projects);
        $this->assertEquals('Fake Project 1', $projects[0]->name);
        $this->assertEquals('Fake Project 2', $projects[1]->name);
    }
}

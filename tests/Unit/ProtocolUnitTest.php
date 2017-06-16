<?php

namespace Tests\Unit;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Protocol;
use App\Technology;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolUnitTest extends TestCase
{
    use DatabaseMigrations;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = Project::create([
            'name' => 'Fake Project',
            'base_price' => 30000,
            'penalty' => 15000,
        ]);
    }

    /** @test */
    public function a_protocol_belongs_to_a_project()
    {
        $protocol = Protocol::create();

        $this->assertNull($protocol->project);
        $this->assertNotInstanceOf(Project::class, $protocol->project);

        $protocol->project()->associate($this->project);
        $protocol->save();

        $this->assertNotNull($protocol->project);
        $this->assertInstanceOf(Project::class, $protocol->project);

        // cleanup
        $protocol->project()->dissociate();
    }

    /** @test */
    public function a_protocol_has_attributes()
    {
        $protocol = Protocol::create([
            'project_id' => $this->project->id,
        ]);

        $this->assertNull($protocol->comment);
        $this->assertNotNull($protocol->done);
        $this->assertFalse($protocol->done);

        $protocol->update([
            'comment' => 'Test Comment',
            'done' => true,
        ]);

        $this->assertNotNull($protocol->comment);
        $this->assertEquals('Test Comment', $protocol->comment);
        $this->assertNotNull($protocol->done);
        $this->assertTrue($protocol->done);

        // cleanup
        $protocol->project()->dissociate();
    }

    /** @test */
    public function a_protocol_belongs_to_a_patch_day()
    {
        $protocol = Protocol::create([
            'project_id' => $this->project->id,
        ]);

        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->assertNull($protocol->patch_day);
        $this->assertNotInstanceOf(PatchDay::class, $protocol->patch_day);

        $protocol->patch_day()->associate($patch_day);
        $protocol->save();

        $this->assertNotNull($protocol->patch_day);
        $this->assertInstanceOf(PatchDay::class, $protocol->patch_day);

        // cleanup
        $protocol->project()->dissociate();
    }

    /** @test */
    public function a_protocol_has_its_patch_days_due_date()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $protocol = Protocol::create([
            'project_id' => $this->project->id,
            'patch_day_id' => $patch_day->id,
        ]);

        $this->assertEquals('2017-03-30', $protocol->date);
    }

    /** @test */
    public function
    a_protocol_has_a_price_that_increases_with_each_missed_patch_day()
    {
        $firstDate = '2017-01-30';

        $patch_day_1 = PatchDay::create([
            'date' => Carbon::parse($firstDate)->toDateString(),
        ]);
        $protocol_1 = Protocol::create([
            'done' => true,
            'comment' => 'easy update',
            'patch_day_id' => $patch_day_1->id,
            'project_id' => $this->project->id,
        ]);
        $this->assertEquals(30000, $protocol_1->price);

        $patch_day_2 = PatchDay::create([
            'date' => Carbon::parse($firstDate)->addMonths(1)
                ->toDateString(),
        ]);
        $protocol_2 = Protocol::create([
            'done' => false,
            'comment' => 'blabla',
            'patch_day_id' => $patch_day_2->id,
            'project_id' => $this->project->id,
        ]);
        $this->assertEquals(30000, $protocol_2->price);

        $patch_day_3 = PatchDay::create([
            'date' => Carbon::parse($firstDate)->addMonths(2)
                ->toDateString(),
        ]);
        $protocol_3 = Protocol::create([
            'done' => false,
            'comment' => 'blabla',
            'patch_day_id' => $patch_day_3->id,
            'project_id' => $this->project->id,
        ]);
        $this->assertEquals(45000, $protocol_3->price);

        $patch_day_4 = PatchDay::create([
            'date' => Carbon::parse($firstDate)->addMonths(3)
                ->toDateString(),
        ]);
        $protocol_4 = Protocol::create([
            'done' => true,
            'comment' => 'blabla',
            'patch_day_id' => $patch_day_4->id,
            'project_id' => $this->project->id,
        ]);
        $this->assertEquals(60000, $protocol_4->price);

        $patch_day_5 = PatchDay::create([
            'date' => Carbon::parse($firstDate)->addMonths(4)
                ->toDateString(),
        ]);
        $protocol_5 = Protocol::create([
            'done' => true,
            'comment' => 'blabla',
            'patch_day_id' => $patch_day_5->id,
            'project_id' => $this->project->id,
        ]);
        $this->assertEquals(30000, $protocol_5->price);

    }

    /** @test */
    public function a_protocol_has_technology_upgrades()
    {
        $company = factory(Company::class)->create();
        $project = factory(Project::class)->create([
            'company_id' => $company->id,
        ]);
        $patch_day = factory(PatchDay::class)->create();

        $tech_1 = Technology::create([
            'name' => 'Laravel',
            'version' => '5.3.12',
        ]);
        $tech_2 = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.2.1',
        ]);

        $protocol = factory(Protocol::class)->create([
            'project_id' => $project->id,
            'patch_day_id' => $patch_day->id,
        ]);

        // update projects technologies
        $project->technologies()->attach($tech_1->id, [
            'protocol_id' => $protocol->id,
        ]);
        $project->technologies()->attach($tech_2->id, [
            'protocol_id' => $protocol->id,
        ]);

        $this->assertNotNull($protocol->technology_updates);
        $this->assertCount(2, $protocol->technology_updates);

        $this->assertEquals('Laravel', $protocol->technology_updates[0]->name);
        $this->assertEquals('5.3.12', $protocol->technology_updates[0]->version);

        $this->assertEquals('Vue.js', $protocol->technology_updates[1]->name);
        $this->assertEquals('2.2.1', $protocol->technology_updates[1]->version);
    }
}

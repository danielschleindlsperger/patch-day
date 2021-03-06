<?php

namespace Tests\Unit;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Protocol;
use App\Technology;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Mockery\Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectUnitTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $company;
    protected $vue;
    protected $vue_2;
    protected $vue_3;
    protected $laravel;
    protected $laravel_2;
    protected $laravel_3;

    public function setUp()
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Fake Company',
        ]);

        $this->vue = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.4.0',
        ]);

        $this->vue_2 = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.4.12',
        ]);

        $this->vue_3 = Technology::create([
            'name' => 'Vue.js',
            'version' => '3.1.2',
        ]);

        $this->laravel = Technology::create([
            'name' => 'Laravel',
            'version' => '5.4.1',
        ]);

        $this->laravel_2 = Technology::create([
            'name' => 'Laravel',
            'version' => '5.4.14',
        ]);

        $this->laravel_3 = Technology::create([
            'name' => 'Laravel',
            'version' => '6.2.5',
        ]);
    }

    /** @test */
    public function project_has_a_name()
    {
        try {
            // name required
            $project = Project::create();
        } catch (MassAssignmentException $e) {
            $this->assertInstanceOf(MassAssignmentException::class, $e);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }

        $project = Project::create([
            'name' => 'Fake Project',
        ]);

        $this->assertNotNull($project);
        $this->assertInstanceOf(Project::class, $project);
        $this->assertNotNull($project->name);
        $this->assertEquals('Fake Project', $project->name);
    }

    /** @test */
    public function project_has_a_company()
    {
        $project = Project::create([
            'name' => 'Test Project',
        ]);

        $this->assertNull($project->company);
        $this->assertNotInstanceOf(Company::class, $project->company);

        $project->company()->associate($this->company);
        $project->save();
        $company = $this->company->fresh();

        $this->assertNotNull($project->company);
        $this->assertInstanceOf(Company::class, $project->company);
        $this->assertEquals('Fake Company', $project->company->name);
        $this->assertEquals('Test Project', $company->projects()->first()
            ->name);

        // cleanup
        $project->company()->dissociate();
    }

    /** @test */
    public function project_has_patch_day_details()
    {
        $project = Project::create([
            'name' => 'Test Project',
            'company_id' => $this->company->id,
            'base_price' => 30000,
            'penalty' => 10000,
        ]);

        $this->assertNotNull($project->base_price);
        $this->assertNotNull($project->penalty);

        $this->assertEquals($project->base_price, 30000);
        $this->assertEquals($project->penalty, 10000);
    }

    /** @test */
    public function project_has_technologies()
    {
        $project = Project::create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        $this->assertCount(0, $project->technologies()->get());

        $project->technologies()->attach([$this->vue->id, $this->laravel->id]);

        $this->assertNotNull($project->technologies()->get());
        $this->assertCount(2, $project->technologies()->get());

        $this->assertInstanceOf(Technology::class,
            $project->technologies()->get()[0]);
        $this->assertEquals('Laravel',
            $project->technologies()->get()[1]->name);
        $this->assertEquals('5.4.1',
            $project->technologies()->get()[1]->version);
    }

    /** @test */
    public function project_technology_has_a_date()
    {
        $project = Project::create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $protocol = Protocol::create([
            'price' => 30000,
            'comment' => 'donezo',
            'done' => true,
            'patch_day_id' => $patch_day->id,
        ]);

        $project->technologies()
            ->attach([$this->vue->id => ['protocol_id' => $protocol->id]]);

        $this->assertNotNull($project->technologies[0]->date);
        $this->assertEquals('2017-03-30', $project->technologies[0]->date);
    }

    /** @test */
    public function a_project_has_current_technologies()
    {
        $project = Project::create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        // base technologies
        $project->technologies()->attach([
            $this->vue->id => ['action' => 'default'],
            $this->laravel->id => ['action' => 'default']
        ]);

        $current_techs = $project->current_technologies;
        $this->assertCount(2, $current_techs);
        $this->assertContainsOnlyInstancesOf(Technology::class, $current_techs);
        $this->assertEquals('Laravel', $current_techs[0]->name);
        $this->assertEquals('5.4.1', $current_techs[0]->version);

        $this->assertInstanceOf(Technology::class, $current_techs[1]);
        $this->assertEquals('Vue.js', $current_techs[1]->name);
        $this->assertEquals('2.4.0', $current_techs[1]->version);

        // technologies get updated on patch-day
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $protocol = Protocol::create([
            'price' => 30000,
            'comment' => 'donezo',
            'done' => true,
            'patch_day_id' => $patch_day->id,
        ]);

        $project->technologies()->attach([
            $this->laravel_2->id => [
                'protocol_id' => $protocol->id,
                'action' => 'update',
            ],
        ]);

        $project->technologies()->attach([
            $this->vue_2->id => [
                'protocol_id' => $protocol->id,
                'action' => 'update',
            ],
        ]);

        $current_techs = $project->current_technologies;

        $this->assertCount(2, $current_techs);
        $this->assertInstanceOf(Technology::class, $current_techs[0]);
        $this->assertEquals('Laravel', $current_techs[0]->name);
        $this->assertEquals('5.4.14', $current_techs[0]->version);

        $this->assertInstanceOf(Technology::class, $current_techs[1]);
        $this->assertEquals('Vue.js', $current_techs[1]->name);
        $this->assertEquals('2.4.12', $current_techs[1]->version);
    }


    /** @test */
    public function a_project_has_a_technology_history()
    {
        $project = Project::create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        // projects base technologies
        $project->technologies()->attach([$this->vue->id, $this->laravel->id]);

        // project gets updated on patch-day_1
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $protocol = Protocol::create([
            'price' => 30000,
            'comment' => 'donezo',
            'done' => true,
            'patch_day_id' => $patch_day->id,
        ]);

        $project->technologies()->attach([
            $this->vue_2->id => ['protocol_id' => $protocol->id],
            $this->laravel_2->id => ['protocol_id' => $protocol->id],
        ]);

        // project gets updated again on patch-day_2
        $patch_day_2 = PatchDay::create([
            'date' => '2017-04-30',
        ]);

        $protocol_2 = Protocol::create([
            'price' => 30000,
            'comment' => 'donezo',
            'done' => true,
            'patch_day_id' => $patch_day_2->id,
        ]);

        $project->technologies()->attach([
            $this->vue_3->id => ['protocol_id' => $protocol_2->id],
            $this->laravel_3->id => ['protocol_id' => $protocol_2->id],
        ]);

        $history = $project->technology_history;
        $this->assertNotNull($history);
        $this->assertCount(6, $history);

        // assert the correct order: based on update time (recent first), then
        // alphabetical order of tech name
        $this->assertEquals($this->laravel_3->id, $history[0]->id);
        $this->assertEquals($this->vue_3->id, $history[1]->id);
        $this->assertEquals($this->laravel_2->id, $history[2]->id);
        $this->assertEquals($this->vue_2->id, $history[3]->id);
        $this->assertEquals($this->laravel->id, $history[4]->id);
        $this->assertEquals($this->vue->id, $history[5]->id);

        // assert relationships and properties
        $this->assertEquals($protocol->id, $history[3]->pivot->protocol_id);
        $this->assertEquals($protocol_2->id, $history[0]->pivot->protocol_id);

        $this->assertEquals('2017-03-30', $history[3]->date);
        $this->assertEquals('2017-04-30', $history[0]->date);
    }

    /** @test */
    public function a_project_has_patch_days()
    {
        $project = factory(Project::class)->create();
        $patch_days = factory(PatchDay::class, 5)
            ->create()->each(function ($patch_day) use ($project) {
                Protocol::create([
                    'project_id' => $project->id,
                    'patch_day_id' => $patch_day->id,
                ]);
            });

        $this->assertCount(5, $project->patchDays());
        $this->assertContainsOnlyInstancesOf(PatchDay::class,
            $project->patchDays());
    }

    /** @test */
    public function a_project_has_default_technologies()
    {
        $project = factory(Project::class)->create();
        $technology = factory(Technology::class)->create([
            'name' => 'Laravel',
            'version' => '5.4.3',
        ]);

        $patch_day = factory(PatchDay::class)->create();

        $updateTechnology = factory(Technology::class)->create([
            'name' => 'Laravel',
            'version' => '5.5.5',
        ]);

        $protocol = factory(Protocol::class)->create([
            'patch_day_id' => $patch_day->id,
            'project_id' => $project->id,
        ]);

        $project->technologies()->attach([
            $technology->id => ['action' => 'default']
        ]);

        $protocol->syncTechnologies([$updateTechnology->id]);

        // should only contain the default tech, without the updated one

        $this->assertNotNull($project->defaultTechnologies);
        $this->assertCount(1, $project->defaultTechnologies);
        $this->assertEquals($technology->id,
            $project->defaultTechnologies->first()->id);

    }
}

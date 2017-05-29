<?php

namespace Tests\Unit;

use App\Company;
use App\Project;
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

    public function setUp()
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Fake Company',
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
        $vue = Technology::create([
            'name' => 'Vue.js',
            'version' => '2.4.0',
        ]);
        $laravel = Technology::create([
            'name' => 'Laravel',
            'version' => '5.4.13',
        ]);

        $project = Project::create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        $this->assertCount(0, $project->technologies()->get());

        $project->technologies()->attach([$vue->id, $laravel->id]);

        $this->assertNotNull($project->technologies()->get());
        $this->assertInstanceOf(Technology::class,
            $project->technologies()->get()[0]);
        $this->assertEquals('Laravel',
            $project->technologies()->get()[1]->name);
        $this->assertEquals('5.4.13',
            $project->technologies()->get()[1]->version);
    }
}

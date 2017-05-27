<?php

namespace Tests\Unit;

use App\Company;
use App\Project;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
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
        // name required
        $this->expectException(MassAssignmentException::class);
        $this->expectException(QueryException::class);
        $project = Project::create();

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
}

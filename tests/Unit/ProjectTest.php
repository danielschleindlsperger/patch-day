<?php

namespace Tests\Unit;

use App\Company;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function project_has_a_company()
    {
        $company = Company::create([
            'name' => 'Fake company',
        ]);

        $project = Project::create([
            'name' => 'Test Project',
        ]);

        $this->assertNull($project->company);
        $this->assertNotInstanceOf(Company::class, $project->company);

        $project->company()->associate($company);
        $project->save();

        $this->assertNotNull($project->company);
        $this->assertInstanceOf(Company::class, $project->company);
    }
}

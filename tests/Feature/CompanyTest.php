<?php

namespace Tests\Feature;

use App\PatchDay;
use App\User;
use App\Company;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function can_create_company()
    {
        $response = $this->json('POST', '/companies', [
            'name' => 'Example company'
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);
    }

    /** @test */
    public function can_see_all_companies()
    {
        $companies = factory(Company::class, 2)->create();

        $response = $this->json('GET', '/companies');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $companies->all()[0]->name,
            ],
                [
                    'name' => $companies->all()[1]->name,
                ]
            );
    }

    /** @test */
    public function can_see_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->json('GET', '/companies/9543');
        $response->assertStatus(404);

        $response = $this->json('GET', '/companies/' . $company->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                    'name' => $company->name,
                ]
            );
    }

    /** @test */
    public function can_see_a_companies_projects()
    {
        $company = factory(Company::class)->create([
            'name' => 'Fake Company',
        ]);
        $project = factory(Project::class)->create([
            'name' => 'Fake Project',
            'company_id' => $company->id,
        ]);
        $patch_day = factory(PatchDay::class)->create([
            'project_id' => $project->id,
        ]);

        $project2 = factory(Project::class)->create([
            'name' => 'Fake Project 2',
            'company_id' => $company->id,
        ]);
        $patch_day2 = factory(PatchDay::class)->create([
            'project_id' => $project2->id,
        ]);

        $response = $this->json('GET', '/companies/' . $company->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'projects' => [
                    [
                        'name',
                        'patch_day' => [
                            'cost', 'start_date', 'interval', 'active',
                        ],
                    ],
                ],
            ])
            ->assertJson([
                'name' => 'Fake Company',
                'projects' => [
                    [
                        'name' => 'Fake Project',
                        'patch_day' => [],
                    ],
                    [
                        'name' => 'Fake Project 2',
                        'patch_day' => [],
                    ],
                ],
            ]);
    }

    /** @test */
    public function can_edit_a_company()
    {
        $company = factory(Company::class)->create(['name' => 'Fake company']);

        $response = $this->json('PUT', '/companies/' . $company->id, [
            'name' => 'Test Firm',
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ]);

        $editedCompany = Company::find($company->id);
        $this->assertEquals('Test Firm', $editedCompany->name);
    }

    /** @test */
    public function can_delete_a_company()
    {
        $company = factory(Company::class)->create(['name' => 'Fake company']);

        $this->assertNotNull($company);
        $this->assertInstanceOf(Company::class, $company);

        $response = $this->json('DELETE', '/companies/' . $company->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ]);

        $response = $this->json('GET', '/companies/' . $company->id);
        $response->assertStatus(404);
    }
}

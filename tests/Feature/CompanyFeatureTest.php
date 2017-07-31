<?php

namespace Tests\Feature;

use App\PatchDay;
use App\User;
use App\Company;
use App\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyFeatureTest extends TestCase
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
            ->assertJson([
                'name' => 'Example company',
            ]);
    }

    /** @test */
    public function can_see_all_companies()
    {
        $company = Company::create([
            'name' => 'Be Careful Inc.',
        ]);

        $company_2 = Company::create([
            'name' => 'A Random Co.',
        ]);

        $response = $this->json('GET', '/companies');
        $response
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'name' => $company_2->name,
                ],
                [
                    'name' => $company->name,
                ]
            );
    }

    /** @test */
    public function can_see_a_company_with_its_projects()
    {
        $company = factory(Company::class)->create();

        $project = factory(Project::class)->create([
            'name' => 'Fake Project',
            'company_id' => $company->id,
        ]);

        $project_2 = factory(Project::class)->create([
            'name' => 'Fake Project 2',
            'company_id' => $company->id,
        ]);

        $response = $this->json('GET', '/companies/9543');
        $response->assertStatus(404);

        $response = $this->json('GET', '/companies/' . $company->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'projects' => [
                    [
                        'name',
                        'base_price',
                        'penalty',
                    ]
                ]
            ])
            ->assertJson([
                    'name' => $company->name,
                    'projects' => [
                        [
                            'name' => 'Fake Project',
                        ],
                        [
                            'name' => 'Fake Project 2',
                        ]
                    ]
                ]
            );
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

    /** @test */
    public function can_create_company_with_logo()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('random-name.png');

        $response = $this->post('/companies', [
            'name' => 'Fake Company Inc.',
            'logo' => $logo,
        ], ['CONTENT_TYPE' => 'multipart/form-data']);

        $timestamp = (new \DateTime())->getTimestamp();

        Storage::disk('public')->assertExists("logos/fake-company-inc{$timestamp}.png");

        $company = Company::latest()->first();

        $this->assertEquals('Fake Company Inc.', $company->name);
        $this->assertEquals(Storage::url("logos/fake-company-inc{$timestamp}.png"),
        $company->logo);
    }

    /** @test */
    public function can_update_company_with_logo()
    {
        Storage::fake('public');

        $company = factory(Company::class)->create([
            'name' => 'Fake Company Inc.',
        ]);

        $logo = UploadedFile::fake()->image('random-name.png');

        $response = $this->put("/companies/{$company->id}", [
            'logo' => $logo,
        ], ['CONTENT_TYPE' => 'multipart/form-data']);

        $timestamp = (new \DateTime())->getTimestamp();

        Storage::disk('public')->assertExists("logos/fake-company-inc{$timestamp}.png");

        $company =$company->fresh();

        $this->assertEquals('Fake Company Inc.', $company->name);
        $this->assertEquals(Storage::url("logos/fake-company-inc{$timestamp}.png"),
            $company->logo);
    }
}

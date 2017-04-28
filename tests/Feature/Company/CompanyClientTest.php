<?php

namespace Tests\Feature\Company;

use App\User;
use App\Company;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyClientTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = factory(User::class)->create();
        $this->actingAs($this->client);
    }

    /** @test */
    public function clients_cannot_create_a_company()
    {
        $response = $this->json('POST', '/companies', [
            'name' => 'Example company'
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function clients_cannot_see_all_companies()
    {
        // TODO: might only return the client's company on this route
        $response = $this->json('GET', '/companies');
        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_see_a_company_he_belongs_to()
    {
        $company = factory(Company::class)->create();

        $response = $this->json('GET', '/companies/9543');
        $response->assertStatus(404);

        $response = $this->json('GET', '/companies/' . $company->id);
        $response->assertStatus(403);

        $this->client->company()->associate($company);
        $this->client->save();

        $response = $this->json('GET', '/companies/' . $company->id);
        $response->assertStatus(200);
    }

    /** @test */
    public function client_cannot_edit_a_company()
    {
        $company = factory(Company::class)->create(['name' => 'Fake company']);

        $response = $this->json('PUT', '/companies/' . $company->id, [
            'name' => 'Test Firm',
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_delete_a_company()
    {
        $company = factory(Company::class)->create(['name' => 'Fake company']);

        $this->assertNotNull($company);
        $this->assertInstanceOf(Company::class, $company);

        $response = $this->json('DELETE', '/companies/' . $company->id);
        $response->assertStatus(403);

        $NewlyRetrievedCompany = Company::find($company->id);
        $this->assertNotNull($NewlyRetrievedCompany);
        $this->assertInstanceOf(Company::class, $NewlyRetrievedCompany);
    }
}

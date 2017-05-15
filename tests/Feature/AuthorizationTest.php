<?php

namespace Tests\Feature;

use App\PatchDay;
use App\Protocol;
use App\User;
use App\Company;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorizationTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $client;
    protected $company;
    protected $project;
    protected $patch_day;
    protected $protocols;

    public function setUp()
    {
        parent::setUp();

        // auth setup
        $this->client = factory(User::class)->create([
            'role' => 'client',
        ]);
        $this->actingAs($this->client);

        $this->company = factory(Company::class)->create([
            'name' => 'Fake Company',
        ]);

        $this->project = factory(Project::class)->create([
            'name' => 'Fake Project',
            'company_id' => $this->company->id,
        ]);

        $this->patch_day = factory(PatchDay::class)->create([
            'project_id' => $this->project->id,
        ]);

        $this->protocols = factory(Protocol::class, 3)->create([
            'patch_day_id' => $this->patch_day->id,
        ]);
    }

    /** @test */
    public function clients_cannot_access_forbidden_company_routes()
    {

        // CANNOT CREATE COMPANY
        $response = $this->json('POST', '/companies', [
            'name' => 'Example company'
        ]);
        $response->assertStatus(403);


        // CANNOT SEE COMPANY INDEX
        // TODO: might only return the client's company on this route
        $response = $this->json('GET', '/companies');
        $response->assertStatus(403);


        // CANNOT UPDATE COMPANY
        $response = $this->json('PUT', '/companies/' . $this->company->id, [
            'name' => 'Test Firm',
        ]);
        $response->assertStatus(403);


        // CANNOT DELETE A COMPANY
        $response = $this->json('DELETE', '/companies/' . $this->company->id);
        $response->assertStatus(403);

        $NewlyRetrievedCompany = Company::find($this->company->id);
        $this->assertNotNull($NewlyRetrievedCompany);
        $this->assertInstanceOf(Company::class, $NewlyRetrievedCompany);
        $this->assertEquals('Fake Company', $NewlyRetrievedCompany->name);
    }

    /** @test */
    public function client_can_see_a_company_they_belong_to()
    {
        $company = factory(Company::class)->create();

        $response = $this->json('GET', '/companies/' . $company->id);
        $response->assertStatus(403);

        $this->client->company()->associate($company);
        $this->client->save();

        $response = $this->json('GET', '/companies/' . $company->id);
        $response->assertStatus(200);
    }
}

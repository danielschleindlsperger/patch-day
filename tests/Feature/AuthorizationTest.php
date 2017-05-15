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
    public function client_cannot_access_forbidden_company_routes()
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

    /** @test */
    public function client_cannot_access_forbidden_project_routes()
    {

        // CANNOT ACCESS PROJECTS INDEX
        $response = $this->json('GET', '/projects');
        $response->assertStatus(403);


        // CANNOT CREATE PROJECT
        $response = $this->json('POST', '/projects', [
            'name' => 'Example Project'
        ]);
        $response->assertStatus(403);


        // CANNOT UPDATE PROJECT
        $response = $this->json('PUT', '/projects/' . $this->project->id, [
            'name' => 'Updated Project'
        ])->assertStatus(403);
        $project = Project::find($this->project->id);
        $this->assertNotEquals('Updated Project', $project->name);


        // CANNOT DELETE PROJECT
        $response = $this->json('DELETE', '/projects/' . $this->project->id);
        $response->assertStatus(403);
        $project = Project::find($this->project->id);
        $this->assertNotNull($project);
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Fake Project', $project->name);
    }

    /** @test */
    public function client_can_access_their_companies_projects()
    {
        // project doesn't belong to users company, so this should fail
        $response = $this->json('GET', '/projects/' . $this->project->id);
        $response->assertStatus(403);

        $this->client->company()->associate($this->company);
        $this->client->save();

        $response = $this->json('GET', '/projects/' . $this->project->id);
        $response->assertStatus(200)
            ->assertJson([
                'name' => $this->project->name,
            ]);

        $this->client->company()->dissociate();
        $this->client->save();
    }
}

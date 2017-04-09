<?php

namespace Tests\Feature;

use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_create_company()
    {
        $response = $this->json('POST', '/companies', [
            'name' => 'Example Company'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);
    }

    /** @test */
    public function user_can_see_all_companies()
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
    public function user_can_see_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->json('GET', '/companies/9543');
        $response
            ->assertStatus(404)
            ->assertSee('Specified company was not found.');

        $response = $this->json('GET', '/companies/' . $company->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                    'name' => $company->name,
                ]
            );
    }
}

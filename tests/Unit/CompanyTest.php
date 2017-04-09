<?php

namespace Tests\Unit;

use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function can_create_company()
    {
        $company = Company::create([
           'name' => 'Example Company',
        ]);

        $this->assertNotNull($company);
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Example Company', $company->name);
    }
}

<?php

namespace Tests\Unit;

use App\Company;
use App\User;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyUnitTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function a_company_has_a_name()
    {
        try {
            // name required
            $company = Company::create();
        } catch (MassAssignmentException $e) {
            $this->assertInstanceOf(MassAssignmentException::class, $e);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }

        $company = factory(Company::class)->create([
            'name' => 'Example company',
        ]);

        $this->assertNotNull($company);
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Example company', $company->name);
    }

    /** @test */
    public function a_company_has_users()
    {
        $company = Company::create([
            'name' => 'Fake Company',
        ]);

        $user = factory(User::class)
            ->create([
                'name' => 'Fake User 1',
                'company_id' => $company->id,
            ]);

        $user2 = factory(User::class)
            ->create([
                'name' => 'Fake User 2',
                'company_id' => $company->id,
            ]);

        $this->assertNotNull($company->users()->get());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class,
            $company->users()->get());
        $this->assertInstanceOf(User::class, $company->users->first());
        $this->assertEquals('Fake User 2', $company->users->get(1)->name);
    }

    /** @test */
    public function a_company_has_a_logo()
    {
        $company = Company::create([
            'name' => 'Fake Company',
            'logo' => '/public/storage/logos/companies/fake-company.png',
        ]);

        $this->assertEquals('/public/storage/logos/companies/fake-company.png', $company->logo);
    }

    /** @test */
    public function a_company_has_a_default_logo()
    {
        $company = Company::create([
            'name' => 'Fake Company',
        ]);

        $this->assertEquals('/img/placeholder_logo.png', $company->logo);
    }
}

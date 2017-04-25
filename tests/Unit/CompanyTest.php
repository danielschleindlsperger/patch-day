<?php

namespace Tests\Unit;

use App\Company;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function a_company_has_a_name()
    {
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
        $company = factory(Company::class)->create();

        $user = factory(User::class)
            ->create([
                'name' => 'Fake User 1',
            ])
            ->company()->associate($company)
            ->save();

        $user2 = factory(User::class)
            ->create([
                'name' => 'Fake User 2',
            ])
            ->company()->associate($company)
            ->save();

        $this->assertNotNull( $company->users->first());
        $this->assertInstanceOf(User::class, $company->users->first());
        $this->assertEquals('Fake User 2', $company->users->get(1)->name);
    }
}

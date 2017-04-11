<?php

namespace Tests\Unit;

use App\Company;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_company()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create();

        $user->company()->associate($company);
        $user->save();

        $this->assertNotNull($user->company);
        $this->assertInstanceOf(Company::class, $user->company);
    }
}

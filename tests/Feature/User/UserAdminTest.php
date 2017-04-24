<?php

namespace Tests\Feature\User;

use App\Company;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAdminTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $admin;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $this->admin = factory(User::class)->create([
            'name' => 'Fake Name',
            'role' => 'admin',
        ]);
        Passport::actingAs($this->admin);
    }

    /** @test */
    public function a_user_can_see_their_account_details()
    {
        $response = $this->json('GET', '/users/me');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->admin->id,
            'email' => $this->admin->email,
            'name' => 'Fake Name',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function an_admin_can_create_a_user_account()
    {
        $response = $this->json('POST', '/users', [
            'name' => 'Fake User',
            'email' => 'test',
            'password' => '123'
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'email' => [],
                'password' => [],
            ]);

        $response = $this->json('POST', '/users', [
            'name' => 'Fake User',
            'email' => 'hello@example.com',
            'password' => 'password',
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'created' => true
            ]);
    }

    /** @test */
    public function an_admin_can_edit_a_user_account()
    {
        $user = factory(User::class)->create([
            'name' => 'Fake Guy',
            'email' => 'fake_guy@example.com',
            'password' => bcrypt('password')
        ]);
        $company = factory(Company::class)->create();

        $this->assertNull($user->company);

        // bad request
        $response = $this->json('PUT', '/users/' . $user->id, [
            'name' => 'Fake User',
            'email' => 'test',
            'password' => '123'
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'email' => [],
                'password' => [],
            ]);



        $response = $this->json('PUT', '/users/' . $user->id, [
            'name' => 'Fake User',
            'email' => 'hello@example.com',
            'password' => 'password',
            'company_id' => $company->id,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'updated' => true
            ]);

        $updatedUser = User::find($user->id);
        $this->assertNotNull($updatedUser->company);
        $this->assertInstanceOf(Company::class, $updatedUser->company);
        $this->assertEquals($company->id, $updatedUser->company->id);
    }
}

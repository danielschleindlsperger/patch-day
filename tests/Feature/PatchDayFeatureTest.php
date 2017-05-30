<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Technology;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayFeatureTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_can_create_new_patch_day()
    {
        $this->json('POST', '/patch-days', [])
            ->assertStatus(422)
            ->assertJson([
                'date' => []
            ]);

        $this->json('POST', '/patch-days', [
            'date' => 'asdf'
        ])
            ->assertStatus(422)
            ->assertJson([
                'date' => []
            ]);

        $response = $this->json('POST', '/patch-days', [
            'date' => '2017-03-30',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'date' => '2017-03-30',
            ]);
    }

    /** @test */
    public function admin_can_update_patch_day()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->json('POST', '/patch-days', [
            'date' => 'asdf'
        ])
            ->assertStatus(422)
            ->assertJson([
                'date' => []
            ]);

        $response = $this->json('PUT', '/patch-days/' . $patch_day->id, [
            'date' => '2017-03-29',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

        $patch_day = $patch_day->fresh();
        $this->assertEquals('2017-03-29', $patch_day->date);
    }

    /** @test */
    public function admin_can_see_patch_day_with_all_projects()
    {

    }

    /** @test */
    public function can_see_all_patch_days()
    {

    }

    /** @test */
    public function admin_can_delete_patch_day()
    {

    }
}
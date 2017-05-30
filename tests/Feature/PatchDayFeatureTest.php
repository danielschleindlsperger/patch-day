<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\Technology;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
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
        // first signup projects for patch-days
    }

    /** @test */
    public function can_see_all_patch_days()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $patch_day_2 = PatchDay::create([
            'date' => '2017-04-27',
        ]);

        $patch_day_3 = PatchDay::create([
            'date' => '2017-05-25',
        ]);

        $response = $this->json('GET', '/patch-days');
        $response->assertStatus(200)
            ->assertJson([
                [
                    'date' => '2017-05-25',
                    'id' => $patch_day_3->id,
                ],
                [
                    'date' => '2017-04-27',
                    'id' => $patch_day_2->id,
                ],
                [
                    'date' => '2017-03-30',
                    'id' => $patch_day->id,
                ],
            ]);
    }

    /** @test */
    public function admin_can_delete_patch_day()
    {
        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $response = $this->json('DELETE', '/patch-days/' . $patch_day->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $patch_day = $patch_day->fresh();

        $this->assertNull($patch_day);
        $this->assertNotInstanceOf(PatchDay::class, $patch_day);
    }
}
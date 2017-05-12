<?php

namespace Tests\Feature\PatchDay;

use App\User;
use App\Protocol;
use App\Project;
use App\PatchDay;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayAdminTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $project;
    protected $patchDay;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();
        $this->patchDay = factory(PatchDay::class)->create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
            'project_id' => $this->project->id,
        ]);

        // Auth
        $admin = factory(User::class)->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_can_see_a_patchday()
    {
        $response = $this->json('GET', 'patch-days/' . $this->patchDay->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'cost' => 200,
                'active' => true,
                'project_id' => $this->project->id,
            ]);
    }

    /** @test */
    public function admin_can_edit_a_patchday()
    {
        // response with invalid data
        $response = $this->json('PUT', 'patch-days/' . $this->patchDay->id, [
            'cost' => false,
        ]);
        $response
            ->assertStatus(422);


        // response with valid data
        $response = $this->json('PUT', 'patch-days/' . $this->patchDay->id, [
            'cost' => 500,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'updated' => true,
            ]);
        $patchDay = PatchDay::find($this->patchDay->id);

        $this->assertEquals(500, $patchDay->cost);
    }

    /** @test */
    public function admin_can_delete_a_patchday()
    {
        $patchDay = factory(PatchDay::class)->create([
            'cost' => 200,
            'start_date' => new Carbon('now +2 weeks'),
            'active' => true,
            'project_id' => $this->project->id,
        ]);

        $this->assertNotNull($patchDay);
        $this->assertInstanceOf(PatchDay::class, $patchDay);

        $response = $this->json('DELETE', '/patch-days/' . $patchDay->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'success' => true
        ]);
    }
}

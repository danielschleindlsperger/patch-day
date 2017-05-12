<?php

namespace Tests\Feature;

use App\PatchDay;
use App\Project;
use App\User;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateProtocolTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function an_inactive_project_does_not_get_updated()
    {
        $project = factory(Project::class)->create();
        $patchDay = factory(PatchDay::class)->create([
            'active' => false,
            'project_id' => $project->id,
        ]);

        // project has no protocols
        $protocols = $patchDay->protocols()->get();
        $this->assertEmpty($protocols);

        Artisan::call('protocols:generate');

        // still no protocols
        $protocols = $patchDay->protocols()->get();
        $this->assertEmpty($protocols);
    }

    /** @test */
    public function an_active_project_does_get_updated()
    {
        $project = factory(Project::class)->create();
        $patchDay = factory(PatchDay::class)->create([
            'active' => false,
            'project_id' => $project->id,
        ]);

        // project has no protocols
        $protocols = $patchDay->protocols()->get();
        $this->assertEmpty($protocols);

        // set project to active
        $patchDay->active = true;
        $patchDay->save();

        Artisan::call('protocols:generate');

        // project has protocols
        $protocols = $patchDay->protocols()->get();
        $this->assertNotEmpty($protocols);
        $this->assertEquals(2, $protocols->count());
        $this->assertInstanceOf(Protocol::class, $protocols->first());
    }

}

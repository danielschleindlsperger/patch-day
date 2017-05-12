<?php

namespace Tests\Unit;

use App\PatchDay;
use App\Project;
use App\Protocol;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();
    }

    /** @test */
    public function a_protocol_belongs_to_a_patch_day()
    {
        $patchDay = factory(PatchDay::class)->create([
            'project_id' => $this->project->id,
        ]);

        $protocol = factory(Protocol::class)->create();

        $this->assertNull($protocol->patchDay);
        $this->assertNotInstanceOf(PatchDay::class, $protocol->patchDay);
        $this->assertInstanceOf(PatchDay::class, $patchDay);

        $protocol->patchDay()->associate($patchDay);
        $protocol->save();

        $this->assertNotNull($protocol->patchDay);
        $this->assertInstanceOf(PatchDay::class, $protocol->patchDay);
    }

    /** @test */
    public function it_has_the_correctly_enumerated_number_inside_a_patch_day()
    {
        $patchDay = factory(PatchDay::class)->create([
            'project_id' => $this->project->id,
        ]);

        $protocol = $patchDay->protocols()->get()->last();
        $this->assertNotNull($protocol->protocol_number);
        $this->assertEquals($protocol->protocol_number, 2);
    }
}

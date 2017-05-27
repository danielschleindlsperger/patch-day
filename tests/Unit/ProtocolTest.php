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
    public function a_protocol_belongs_to_a_project()
    {
        $protocol = factory(Protocol::class)->create();

        $this->assertNull($protocol->project);
        $this->assertNotInstanceOf(Project::class, $protocol->project);

        $protocol->project()->associate($this->project);
        $protocol->save();

        $this->assertNotNull($protocol->project);
        $this->assertInstanceOf(Project::class, $protocol->project);

        // cleanup
        $protocol->project()->disassociate();
    }

    /** @test */
    public function a_protocol_has_attributes()
    {
        // TODO: price set on creation
        $protocol = Protocol::create([
            'project_id' => $this->project->id,
        ]);

        $this->assertNull($protocol->comment);
        $this->assertNotNull($protocol->done);
        $this->assertFalse($protocol->done);

        $protocol->update([
            'comment' => 'Test Comment',
            'done' => true,
        ]);

        $this->assertNotNull($protocol->comment);
        $this->assertEquals('Test Comment', $protocol->comment);
        $this->assertNotNull($protocol->done);
        $this->assertTrue($protocol->done);
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

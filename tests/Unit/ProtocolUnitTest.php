<?php

namespace Tests\Unit;

use App\PatchDay;
use App\Project;
use App\Protocol;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolUnitTest extends TestCase
{
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
        $protocol = Protocol::create();

        $this->assertNull($protocol->project);
        $this->assertNotInstanceOf(Project::class, $protocol->project);

        $protocol->project()->associate($this->project);
        $protocol->save();

        $this->assertNotNull($protocol->project);
        $this->assertInstanceOf(Project::class, $protocol->project);

        // cleanup
        $protocol->project()->dissociate();
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

        // cleanup
        $protocol->project()->dissociate();
    }

    /** @test */
    public function a_protocol_belongs_to_a_patch_day()
    {
        $protocol = Protocol::create([
            'project_id' => $this->project->id,
        ]);

        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->assertNull($protocol->patch_day);
        $this->assertNotInstanceOf(PatchDay::class, $protocol->patch_day);

        $protocol->patch_day()->associate($patch_day);
        $protocol->save();

        $this->assertNotNull($protocol->patch_day);
        $this->assertInstanceOf(PatchDay::class, $protocol->patch_day);

        // cleanup
        $protocol->project()->dissociate();
    }
}

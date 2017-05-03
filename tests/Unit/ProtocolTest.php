<?php

namespace Tests\Unit;

use App\PatchDay;
use App\Protocol;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /** @test */
    public function a_protocol_belongs_to_a_patch_day()
    {
        $patchDay = factory(PatchDay::class)->create();

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
        $patchDay = factory(PatchDay::class)->create();
        $protocols = factory(Protocol::class, 5)->create([
            'patch_day_id' => $patchDay->id,
        ]);

        $protocol = $protocols[4];
        $this->assertNotNull($protocol->protocol_number);
        $this->assertEquals($protocol->protocol_number, 5);
    }
}

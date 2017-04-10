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
}

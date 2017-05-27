<?php

namespace Tests\Unit;

use App\PatchDay;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function a_patch_day_has_a_date()
    {
        // date required
        $this->expectException(MassAssignmentException::class);
        $this->expectException(QueryException::class);
        $patch_day = PatchDay::create();

        $patch_day = PatchDay::create([
            'date' => '2017-30-03',
        ]);

        $this->assertNotNull($patch_day);
        $this->assertInstanceOf(PatchDay::class, $patch_day);
        $this->assertEquals('2017-30-03', $patch_day->date);
    }
}

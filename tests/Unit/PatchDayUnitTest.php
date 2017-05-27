<?php

namespace Tests\Unit;

use App\PatchDay;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PatchDayUnitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_patch_day_has_a_date()
    {
        try {
            //date required
            $patch_day = PatchDay::create();
        } catch (\Exception $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }

        $patch_day = PatchDay::create([
            'date' => '2017-03-30',
        ]);

        $this->assertNotNull($patch_day);
        $this->assertInstanceOf(PatchDay::class, $patch_day);
        $this->assertEquals('2017-03-30', $patch_day->date);
    }
}

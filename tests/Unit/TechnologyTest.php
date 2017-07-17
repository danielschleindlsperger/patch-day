<?php

namespace Tests\Unit;

use App\Company;
use App\Technology;
use App\User;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TechnologyTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /** @test */
    public function a_technology_must_have_a_name_and_a_version_number()
    {
        try {
            // name and version required
            $technology = Technology::create();
        } catch (MassAssignmentException $e) {
            $this->assertInstanceOf(MassAssignmentException::class, $e);
        } catch (QueryException $e) {
            $this->assertInstanceOf(QueryException::class, $e);
        }

        $technology = Technology::create([
            'name' => 'php',
            'version' => '7.0.30',
        ]);

        $this->assertEquals('php', $technology->name);
        $this->assertEquals('7.0.30', $technology->version);
    }

    /** @test */
    public function a_technology_has_a_canonical_name()
    {
        $technology = Technology::create([
            'name' => 'php',
            'version' => '7.0.30',
        ]);

        $this->assertEquals('php 7.0.30', $technology->canonical_name);
    }
}

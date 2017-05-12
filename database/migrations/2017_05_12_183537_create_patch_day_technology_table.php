<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatchDayTechnologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patch_day_technology', function (Blueprint $table) {
            $table->integer('patch_day_id')->unsigned();
            $table->integer('technology_id')->unsigned();

            $table->foreign('patch_day_id')->references('id')->on('patch_days');
            $table->foreign('technology_id')->references('id')->on('technologies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patch_day_technology');
    }
}

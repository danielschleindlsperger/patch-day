<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment')->nullable();
            $table->boolean('done')->default(false);
            $table->date('due_date');
            $table->smallInteger('protocol_number')->unsigned()->nullable();
            $table->integer('patch_day_id')->unsigned()->nullable();
            $table->foreign('patch_day_id')->references('id')->on('patch_days');
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
        Schema::dropIfExists('protocols');
    }
}

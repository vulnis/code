<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order'); //Root cause order, together with Hazard Id and PK for grouping logic
            $table->text('description'); // Root cause
            $table->unsignedInteger('hazard'); //FK to Hazards
            $table->unsignedInteger('category'); // Root cause category
            $table->timestamps();
            // Add a set of consequences
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('causes');
    }
}

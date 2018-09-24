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
            $table->text('description'); // Root cause
            $table->unsignedInteger('category_id'); // Root cause category
            $table->unsignedInteger('component_id')->nullable();
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

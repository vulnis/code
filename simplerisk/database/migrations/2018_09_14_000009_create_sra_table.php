<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hazard_id');
            $table->unsignedInteger('cause_id');
            $table->unsignedInteger('probability_id');
            $table->unsignedInteger('severity_id');
            $table->unsignedInteger('level_id');
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
        Schema::drop('sra');
    }
}

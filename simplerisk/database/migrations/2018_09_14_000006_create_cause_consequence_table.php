<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCauseConsequenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cause_consequence', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cause_id');
            $table->unsignedInteger('consequence_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cause_consequence');
    }
}

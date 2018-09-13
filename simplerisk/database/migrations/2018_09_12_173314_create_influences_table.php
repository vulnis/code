<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfluencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50); //f.i. Lowland, Mountainous, Friendly Territory, Enemy territory
            $table->unsignedInteger('category'); // f.i. landscape

            $table->unsignedInteger('integrity'); // On a scale of 0 to 10
            $table->unsignedInteger('confidentiality'); // On a scale of 0 to 10
            $table->unsignedInteger('availability'); // On a scale of 0 to 10
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
        Schema::drop('influences');
    }
}

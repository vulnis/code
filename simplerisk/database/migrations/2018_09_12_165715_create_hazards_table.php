<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200); // Safety item or hazard
            $table->date('occurance'); // Date on which the Hazard Occured
            $table->unsignedInteger('stage'); // Operational Stage; f.i. "Cruising"
            $table->unsignedInteger('category'); // Safety Hazard Category; f.i. "Active failure"
            $table->text('description');
            $table->timestamps();
            // Has multiple root causes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hazards');
    }
}

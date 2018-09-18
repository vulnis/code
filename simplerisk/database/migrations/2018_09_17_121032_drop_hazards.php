<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropHazards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('hazards');
        DB::statement("DELETE FROM source WHERE type='Hazard'");
        DB::statement("ALTER TABLE source MODIFY type ENUM('Risk', 'Cause') NOT NULL");
        DB::statement("DELETE FROM category WHERE type='Hazard'");
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200); // Safety item or hazard
            $table->date('occurance'); // Date on which the Hazard Occured
            $table->unsignedInteger('stage_id'); // Operational Stage; f.i. "Cruising"
            $table->unsignedInteger('category_id'); // Safety Hazard Category; f.i. "Active failure"
            $table->text('description');
            $table->timestamps();
            $table->unsignedInteger('source_id');
            DB::statement("ALTER TABLE source MODIFY type ENUM('Risk', 'Cause','Hazard') NOT NULL");
            DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause', 'Hazard') NOT NULL");
        });
    }
}

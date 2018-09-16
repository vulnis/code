<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCausesRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->dropColumn('hazard');
            $table->dropColumn('order');
            $table->renameColumn('category', 'category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->renameColumn('category_id', 'category');
            $table->unsignedInteger('hazard'); //FK to Hazards
            //$table->unsignedInteger('order'); //FK to Hazards
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHazardRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->renameColumn('category', 'category_id');
            $table->renameColumn('stage', 'stage_id');
            $table->renameColumn('source', 'source_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hazards', function (Blueprint $table) {
            $table->renameColumn('category_id', 'category');
            $table->renameColumn('stage_id', 'stage');
            $table->renameColumn('source_id', 'source');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInfluencesRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('influences', function (Blueprint $table) {
            $table->renameColumn('category', 'category_id');
        });
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause', 'Influence') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('influences', function (Blueprint $table) {
            $table->renameColumn('category_id', 'category');
        });
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause') NOT NULL");
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoryAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
        });
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause', 'Influence', 'Asset') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Cause', 'Influence') NOT NULL");
    }
}

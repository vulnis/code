<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoryEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Hazard', 'Cause') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM category WHERE type='Cause'");
        DB::statement("ALTER TABLE category MODIFY type ENUM('Risk', 'Hazard') NOT NULL");
    }
}

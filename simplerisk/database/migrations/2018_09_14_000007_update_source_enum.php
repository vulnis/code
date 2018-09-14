<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSourceEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE source MODIFY type ENUM('Risk', 'Hazard', 'Cause') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM source WHERE type='Cause'");
        DB::statement("ALTER TABLE source MODIFY type ENUM('Risk', 'Hazard') NOT NULL");
    }
}

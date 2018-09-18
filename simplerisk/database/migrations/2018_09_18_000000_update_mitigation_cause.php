<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMitigationCause extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE mitigations MODIFY last_update TIMESTAMP NULL");
        Schema::table('mitigations', function (Blueprint $table) {
            $table->unsignedInteger('cause_id');
            $table->unsignedInteger('assessment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitigations', function (Blueprint $table) {
            $table->dropColumn('cause_id');
            $table->dropColumn('assessment_id');
        });
    }
}

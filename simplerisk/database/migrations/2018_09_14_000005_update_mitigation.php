<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMitigation extends Migration
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
            $table->unsignedInteger('assessment_id')->nullable();
            $table->date('reassessment')->nullable(); //date at which this action should be reassessed
            $table->boolean('authorities')->nullable(); // Should authorities be informed or reported to?
            $table->boolean('report')->nullable(); // Should this be included in the management report?
            $table->timestamps();
            $table->enum('type',['CA','PA'])->default('CA'); // Corrective (CA) or Preventive (PA)?
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
            $table->dropColumn('assessment_id');
            $table->dropColumn('reassessment');
            $table->dropColumn('authorities');
            $table->dropColumn('report');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('type');
        });
    }
}

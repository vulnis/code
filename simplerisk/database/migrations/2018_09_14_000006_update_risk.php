<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRisk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->unsignedInteger('stage_id')->nullable();
            $table->dateTime('last_update')->nullable()->change();
			$table->dateTime('review_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn('stage_id');
            $table->dateTime('last_update')->change();
			$table->dateTime('review_date')->change();

        });
    }
}

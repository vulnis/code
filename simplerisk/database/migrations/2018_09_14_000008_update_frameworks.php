<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFrameworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frameworks', function (Blueprint $table) {
            $table->unsignedInteger('parent')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frameworks', function (Blueprint $table) {
            $table->unsignedInteger('parent')->change();
        });
    }
}

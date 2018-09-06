<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('email',200)->change();
            $table->unique('email','user_email_unique');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voiddesc migrati
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('user_email_unique');
            $table->binary('email', 65535)->change();
            $table->dropColumn('remember_token');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}

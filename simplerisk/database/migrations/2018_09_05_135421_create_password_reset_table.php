<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('password_reset', function(Blueprint $table)
		{
			$table->string('username', 200)->nullable();
			$table->string('token', 20);
			$table->integer('attempts')->default(0);
			$table->dateTime('timestamp')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('password_reset');
	}

}

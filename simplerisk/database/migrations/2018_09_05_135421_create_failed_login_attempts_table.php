<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFailedLoginAttemptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('failed_login_attempts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('expired')->nullable()->default(0);
			$table->unsignedInteger('user_id');
			$table->string('ip', 15)->nullable()->default('0.0.0.0');
			$table->dateTime('date')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('failed_login_attempts');
	}

}

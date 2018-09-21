<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMitigationAcceptUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mitigation_accept_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('risk_id');
			$table->unsignedInteger('user_id');
			$table->dateTime('created_at')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mitigation_accept_users');
	}

}

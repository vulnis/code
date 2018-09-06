<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuditLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audit_log', function(Blueprint $table)
		{
			$table->dateTime('timestamp')->useCurrent();
			$table->unsignedInteger('risk_id');
			$table->unsignedInteger('user_id');
			$table->text('message', 16777215);
			$table->string('log_type', 100);

			$table->foreign('risk_id')->references('id')->on('risks');
			$table->foreign('user_id')->references('value')->on('user');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audit_log');
	}

}

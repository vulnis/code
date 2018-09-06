<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClosuresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('closures', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('risk_id');
			$table->unsignedInteger('user_id');
			$table->dateTime('closure_date')->useCurrent();
			$table->integer('close_reason');
			$table->text('note', 16777215);

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
		Schema::drop('closures');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('risk_id');
			$table->dateTime('date')->useCurrent();
			$table->unsignedInteger('user');
			$table->text('comment', 16777215);

			$table->foreign('risk_id')->references('id')->on('risks');
			$table->foreign('user')->references('value')->on('user');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}

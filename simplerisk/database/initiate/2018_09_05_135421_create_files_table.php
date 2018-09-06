<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('risk_id')->nullable();
			$table->integer('view_type')->nullable()->default(1);
			$table->string('name', 100);
			$table->string('unique_name', 30);
			$table->string('type', 30);
			$table->integer('size');
			$table->dateTime('timestamp')->useCurrent();
			$table->unsignedInteger('user');
			$table->binary('content');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}

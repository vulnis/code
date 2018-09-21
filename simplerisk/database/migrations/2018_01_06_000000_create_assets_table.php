<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ip', 15)->nullable();
			$table->string('name', 200)->unique('name');
			$table->integer('value')->nullable()->default(5);
			$table->integer('location');
			$table->integer('team');
			$table->text('details')->nullable();
			$table->dateTime('created')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assets');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRiskLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('risk_levels', function(Blueprint $table)
		{
			$table->decimal('value', 3, 1);
			$table->string('name', 20);
			$table->string('color', 20);
			$table->string('display_name', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('risk_levels');
	}

}

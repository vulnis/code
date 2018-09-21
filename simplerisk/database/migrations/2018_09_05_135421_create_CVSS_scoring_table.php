<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCVSSScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CVSS_scoring', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('metric_name', 30);
			$table->string('abrv_metric_name', 3);
			$table->string('metric_value', 30);
			$table->string('abrv_metric_value', 3);
			$table->float('numeric_value', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('CVSS_scoring');
	}

}

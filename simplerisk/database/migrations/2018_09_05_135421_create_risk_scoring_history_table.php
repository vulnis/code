<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRiskScoringHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('risk_scoring_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('risk_id');
			$table->float('calculated_risk', 10, 0);
			$table->dateTime('last_update');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('risk_scoring_history');
	}

}

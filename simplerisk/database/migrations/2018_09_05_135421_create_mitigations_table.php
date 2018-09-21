<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMitigationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mitigations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('risk_id');
			$table->dateTime('submission_date')->useCurrent();
			$table->dateTime('last_update')->nullable();
			$table->integer('planning_strategy');
			$table->integer('mitigation_effort');
			$table->integer('mitigation_cost')->default(1);
			$table->integer('mitigation_owner');
			$table->integer('mitigation_team');
			$table->text('current_solution', 16777215);
			$table->text('security_requirements', 16777215);
			$table->text('security_recommendations', 16777215);
			$table->integer('submitted_by')->default(1);
			$table->date('planning_date');
			$table->integer('mitigation_percent');
			$table->text('mitigation_controls', 16777215)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mitigations');
	}

}

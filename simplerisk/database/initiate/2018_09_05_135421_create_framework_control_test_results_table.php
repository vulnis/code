<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrameworkControlTestResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('framework_control_test_results', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('test_audit_id');
			$table->string('test_result', 50);
			$table->text('summary', 65535);
			$table->date('test_date');
			$table->unsignedInteger('submitted_by');
			$table->dateTime('submission_date')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('framework_control_test_results');
	}

}

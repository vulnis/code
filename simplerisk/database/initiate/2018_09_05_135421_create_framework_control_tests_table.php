<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrameworkControlTestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('framework_control_tests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('tester');
			$table->integer('test_frequency')->default(0);
			$table->date('last_date');
			$table->date('next_date');
			$table->text('name', 16777215);
			$table->text('objective', 16777215);
			$table->text('test_steps', 16777215);
			$table->integer('approximate_time');
			$table->text('expected_results', 16777215);
			$table->integer('framework_control_id');
			$table->integer('desired_frequency')->nullable();
			$table->integer('status')->default(1);
			$table->date('created_at')->useCurrent();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('framework_control_tests');
	}

}

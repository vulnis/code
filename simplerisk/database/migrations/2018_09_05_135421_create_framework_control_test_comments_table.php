<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrameworkControlTestCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('framework_control_test_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('test_audit_id');
			$table->dateTime('date')->useCurrent();
			$table->unsignedInteger('user');
			$table->text('comment', 16777215);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('framework_control_test_comments');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePendingRisksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pending_risks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('assessment_id');
			$table->unsignedInteger('assessment_answer_id');
			$table->binary('subject', 65535);
			$table->float('score', 10, 0);
			$table->integer('owner')->nullable();
			$table->string('asset', 200)->nullable();
			$table->string('comment', 500)->nullable();
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
		Schema::drop('pending_risks');
	}

}

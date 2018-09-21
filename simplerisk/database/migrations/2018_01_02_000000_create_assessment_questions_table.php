<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssessmentQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assessment_questions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('assessment_id');
			$table->string('question', 1000);
			$table->integer('order')->default(999999);

			$table->foreign('assessment_id')->references('id')->on('assessments');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assessment_questions');
	}

}

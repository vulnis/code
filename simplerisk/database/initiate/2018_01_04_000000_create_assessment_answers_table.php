<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssessmentAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assessment_answers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('assessment_id');
			$table->unsignedInteger('question_id');
			$table->unsignedInteger('assessment_scoring_id');
			$table->string('answer', 200);
			$table->boolean('submit_risk')->default(0);
			$table->binary('risk_subject', 65535);
			$table->float('risk_score', 10, 0);
			$table->integer('risk_owner')->nullable();
			$table->string('assets', 200)->nullable();
			$table->integer('order')->default(999999);

			$table->foreign('assessment_id')->references('id')->on('assessments');
			$table->foreign('question_id')->references('id')->on('assessment_questions');
			$table->foreign('assessment_scoring_id')->references('id')->on('assessment_scoring');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assessment_answers');
	}

}

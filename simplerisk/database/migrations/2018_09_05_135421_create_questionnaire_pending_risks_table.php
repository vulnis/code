<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnairePendingRisksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_pending_risks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('questionnaire_tracking_id');
			$table->integer('questionnaire_scoring_id');
			$table->binary('subject', 65535);
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
		Schema::drop('questionnaire_pending_risks');
	}

}

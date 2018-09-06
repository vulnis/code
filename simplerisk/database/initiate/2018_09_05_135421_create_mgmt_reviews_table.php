<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMgmtReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mgmt_reviews', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('risk_id');
			$table->dateTime('submission_date')->useCurrent();
			$table->integer('review');
			$table->integer('reviewer');
			$table->integer('next_step');
			$table->text('comments', 16777215);
			$table->string('next_review', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mgmt_reviews');
	}

}

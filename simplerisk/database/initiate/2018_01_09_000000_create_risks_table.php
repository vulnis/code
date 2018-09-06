<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRisksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('risks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('status', 20);
			$table->string('subject', 300);
			$table->string('reference_id', 20)->default('');
			$table->integer('regulation')->nullable();
			$table->string('control_number', 20)->nullable();
			$table->integer('location');
			$table->integer('source');
			$table->unsignedInteger('category');
			$table->string('team', 500)->default('0');
			$table->string('technology', 500);
			$table->integer('owner');
			$table->integer('manager');
			$table->text('assessment');
			$table->text('notes');
			$table->dateTime('submission_date')->useCurrent();
			$table->dateTime('last_update');
			$table->dateTime('review_date');
			$table->integer('mitigation_id')->nullable();
			$table->integer('mgmt_review')->nullable();
			$table->integer('project_id')->default(0);
			$table->integer('close_id')->nullable();
			$table->integer('submitted_by')->default(1);
			$table->string('additional_stakeholders', 500);

			$table->foreign('category')->references('id')->on('category');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('risks');
	}

}

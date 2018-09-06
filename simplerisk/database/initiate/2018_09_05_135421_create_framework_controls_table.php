<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrameworkControlsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('framework_controls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_name', 100);
			$table->binary('long_name', 65535)->nullable();
			$table->binary('description', 65535)->nullable();
			$table->binary('supplemental_guidance', 65535)->nullable();
			$table->string('framework_ids')->nullable();
			$table->integer('control_owner')->nullable();
			$table->integer('control_class')->nullable();
			$table->integer('control_phase')->nullable();
			$table->string('control_number', 20)->nullable();
			$table->integer('control_priority')->nullable();
			$table->integer('family')->nullable();
			$table->dateTime('submission_date')->useCurrent();
			$table->date('last_audit_date')->nullable();
			$table->date('next_audit_date')->nullable();
			$table->integer('desired_frequency')->nullable();
			$table->integer('mitigation_percent')->default(0);
			$table->integer('status')->default(1);
			$table->boolean('deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('framework_controls');
	}

}

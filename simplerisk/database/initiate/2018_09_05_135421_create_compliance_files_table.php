<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComplianceFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('compliance_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('ref_id')->nullable();
			$table->string('ref_type', 100)->nullable();
			$table->string('name', 100);
			$table->string('unique_name', 30);
			$table->string('type', 30);
			$table->integer('size');
			$table->dateTime('timestamp')->useCurrent();
			$table->integer('user');
			$table->binary('content');
			$table->integer('version')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('compliance_files');
	}

}

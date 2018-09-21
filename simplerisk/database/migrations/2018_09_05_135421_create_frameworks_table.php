<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrameworksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('frameworks', function(Blueprint $table)
		{
			$table->increments('value');
			$table->integer('parent');
			$table->binary('name', 65535);
			$table->binary('description', 65535);
			$table->integer('status')->default(1);
			$table->integer('order');
			$table->date('last_audit_date')->nullable();
			$table->date('next_audit_date')->nullable();
			$table->integer('desired_frequency')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('frameworks');
	}

}

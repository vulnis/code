<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComponentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('component', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50);
			$table->unsignedInteger('parent_id')->nullable();
			$table->text('description')->nullable();
            $table->unsignedInteger('category_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('component');
	}

}

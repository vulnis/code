<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRisksToAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('risks_to_assets', function(Blueprint $table)
		{
			$table->unsignedInteger('risk_id')->nullable();
			$table->unsignedInteger('asset_id');
			$table->string('asset', 200);
			$table->unique(['risk_id','asset'], 'risk_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('risks_to_assets');
	}

}

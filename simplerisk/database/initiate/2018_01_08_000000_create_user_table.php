<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->increments('value');
			$table->boolean('enabled')->default(1);
			$table->boolean('lockout')->default(0);
			$table->string('type', 20)->default('simplerisk');
			$table->binary('username', 65535);
			$table->string('name', 50);
			$table->binary('email', 65535);
			$table->string('salt', 20)->nullable();
			$table->binary('password', 60);
			$table->dateTime('last_login')->nullable();
			$table->dateTime('last_password_change_date')->useCurrent();
			$table->string('teams', 4000)->default('none');
			$table->unsignedInteger('role_id');
			$table->string('lang', 5)->nullable();
			$table->boolean('governance')->default(0);
			$table->boolean('riskmanagement')->default(1);
			$table->boolean('compliance')->default(0);
			$table->boolean('assessments')->default(0);
			$table->boolean('asset')->default(0);
			$table->boolean('admin')->default(0);
			$table->boolean('review_veryhigh')->default(0);
			$table->boolean('review_high')->default(0);
			$table->boolean('accept_mitigation')->default(0);
			$table->boolean('review_medium')->default(0);
			$table->boolean('review_low')->default(0);
			$table->boolean('review_insignificant')->default(0);
			$table->boolean('submit_risks')->default(0);
			$table->boolean('modify_risks')->default(0);
			$table->boolean('plan_mitigations')->default(0);
			$table->boolean('close_risks')->default(1);
			$table->integer('multi_factor')->default(1);
			$table->boolean('change_password')->default(0);
			$table->string('custom_display_settings', 1000);
			$table->boolean('add_new_frameworks')->default(0);
			$table->boolean('modify_frameworks')->default(0);
			$table->boolean('delete_frameworks')->default(0);
			$table->boolean('add_new_controls')->default(0);
			$table->boolean('modify_controls')->default(0);
			$table->boolean('delete_controls')->default(0);
			$table->boolean('add_documentation')->default(0);
			$table->boolean('modify_documentation')->default(0);
			$table->boolean('delete_documentation')->default(0);
			$table->boolean('comment_risk_management')->default(0);
			$table->boolean('comment_compliance')->default(0);

			$table->foreign('role_id')->references('value')->on('role');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}

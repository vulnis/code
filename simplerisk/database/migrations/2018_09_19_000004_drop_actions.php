<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('actions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('action'); // The description of the action taken or to be taken
            $table->unsignedInteger('responsible'); // The person or role in the organisation responsible for taking the action
            $table->date('due'); //due date for the action
            $table->date('reassessment'); //date at which this action should be reassessed
            $table->boolean('authorities'); // Should authorities be informed or reported to?
            $table->boolean('report'); // Should this be included in the management report?
            $table->timestamps();
            $table->enum('type',['CA','PA']); // Corrective (CA) or Preventive (PA)?
        });
    }
}

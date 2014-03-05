<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fbf_competition_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('competition_id');
			$table->integer('user_id');
			$table->string('answer');
			$table->boolean('is_correct');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fbf_competition_entries');
	}

}

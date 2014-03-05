<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fbf_competitions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('main_image')->nullable();
			$table->string('main_image_alt')->nullable();
			$table->text('summary');
			$table->text('content');
			$table->string('question')->nullable();
			$table->string('correct_answer')->nullable();
			$table->string('incorrect_answer_1')->nullable();
			$table->string('incorrect_answer_2')->nullable();
			$table->string('incorrect_answer_3')->nullable();
			$table->string('incorrect_answer_4')->nullable();
			$table->boolean('requires_login');
			$table->boolean('multiple_entries');
			$table->boolean('is_sticky');
			$table->string('slug')->unique();
			$table->string('page_title');
			$table->text('meta_description');
			$table->text('meta_keywords');
			$table->enum('status', array('DRAFT', 'APPROVED'))->default('DRAFT');
			$table->dateTime('published_date')->nullable();
			$table->dateTime('closing_date')->nullable();
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
		Schema::drop('fbf_competitions');
	}

}

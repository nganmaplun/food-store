<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFoodTable.
 */
class CreateFoodTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food', function(Blueprint $table) {
            $table->increments('id');
            $table->string('vietnamese_name', 100);
            $table->string('japanese_name', 100);
            $table->string('english_name', 100);
            $table->string('short_name', 50);
            $table->integer('price');
            $table->string('category', 50);
            $table->text('image');
            $table->text('food_recipe');
            $table->text('description');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('food');
	}
}

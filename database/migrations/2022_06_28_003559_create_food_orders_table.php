<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFoodOrdersTable.
 */
class CreateFoodOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_order', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('food_id');
            $table->integer('order_num');
            $table->text('note')->nullable();
            $table->tinyInteger('is_delivered')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('food_order');
	}
}

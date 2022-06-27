<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOrdersTable.
 */
class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->integer('table_id');
            $table->string('customer_type');
            $table->integer('number_of_customers');
            $table->integer('total_price')->default(0);
            $table->tinyInteger('is_paid')->default(false);
            $table->text('description')->nullable();
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
		Schema::drop('order');
	}
}

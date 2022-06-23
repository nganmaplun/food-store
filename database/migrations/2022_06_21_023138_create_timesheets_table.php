<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTimesheetsTable.
 */
class CreateTimesheetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timesheets', function(Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('checkin_time', 100);
            $table->string('checkout_time', 100)->nullable();
            $table->string('working_date', 100)->nullable();
            $table->float('total_hours')->nullable();
            $table->tinyInteger('is_approved')->nullable();
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
		Schema::drop('timesheets');
	}
}

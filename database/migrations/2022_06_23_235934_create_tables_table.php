<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTablesTable.
 */
class CreateTablesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tables', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('max_seat');
            $table->string('floor', 100);
            $table->string('description')->nullable();
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
		Schema::drop('tables');
	}
}

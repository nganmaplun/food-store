<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_order', function(Blueprint $table) {
            $table->tinyInteger('is_sent')->default(false);
            $table->string('order_time', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_order', function(Blueprint $table) {
            $table->dropColumn('is_sent');
            $table->dropColumn('order_time');
        });
    }
};

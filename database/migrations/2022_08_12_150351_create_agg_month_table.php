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
        Schema::create('agg_month', function (Blueprint $table) {
            $table->id();
            $table->string("total_food", 10);
            $table->string("total_price", 10);
            $table->string("order_date", 10);
            $table->string("japanese_guest", 10)->default(0);
            $table->string("vietnamese_guest", 10)->default(0);
            $table->string("english_guest", 10)->default(0);
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
        Schema::dropIfExists('agg_month');
    }
};

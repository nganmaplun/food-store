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
            $table->tinyInteger('is_cancel')->default(0);
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
        Schema::table('food_order', function(Blueprint $table) {
            $table->dropColumn('is_cancel');
            $table->dropSoftDeletes();
        });
    }
};

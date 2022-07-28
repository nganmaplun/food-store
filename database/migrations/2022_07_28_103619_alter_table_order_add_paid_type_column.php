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
        Schema::table('order', function(Blueprint $table) {
            $table->string('paid_type', 20)->after('description')->nullable();
            $table->tinyInteger('is_draft')->after('paid_type')->default(true);
            $table->string('discount')->after('is_draft')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function(Blueprint $table) {
            $table->dropColumn('paid_type');
            $table->dropColumn('draft');
            $table->dropColumn('discount');
        });
    }
};

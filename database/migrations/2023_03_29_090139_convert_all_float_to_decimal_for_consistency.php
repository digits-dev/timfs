<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertAllFloatToDecimalForConsistency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->decimal('packaging_size', 18, 4)->nullable()->change();
            $table->decimal('qty', 18, 4)->nullable()->change();
            $table->decimal('prep_qty', 18, 4)->nullable()->change();
            $table->decimal('yield', 18, 4)->nullable()->change();
            $table->decimal('ttp', 18, 4)->nullable()->change();
            $table->decimal('cost', 18, 4)->nullable()->change();
            $table->decimal('total_cost', 18, 4)->nullable()->change();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('ingredient_total_cost', 18, 4)->nullable()->change();
            $table->decimal('portion_size', 18, 4)->nullable()->default('1')->change();
            $table->decimal('food_cost', 18, 4)->nullable()->change();
            $table->decimal('food_cost_percentage', 18, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            //
        });
    }
}

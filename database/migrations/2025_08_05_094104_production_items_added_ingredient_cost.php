<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemsAddedIngredientCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items', function (Blueprint $table) {
            $table->decimal('ingredient_cost', 10, 3)->nullable()->after('packaging_cost');  
        });
         Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->decimal('ingredient_cost', 10, 3)->nullable()->after('packaging_cost');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_items', function (Blueprint $table) { 
            $table->dropColumn(['ingredient_cost']); 
        });
         Schema::table('production_items_approvals', function (Blueprint $table) { 
            $table->dropColumn(['ingredient_cost']); 
        });
    }
}

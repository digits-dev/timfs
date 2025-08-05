<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemLinesUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->decimal('cost_contribution', 10, 3)->nullable()->after('time_labor');
            $table->decimal('qty_contribution', 10, 3)->nullable()->after('cost_contribution');
            $table->decimal('duration', 10, 3)->nullable()->after('qty_contribution');
            $table->decimal('actual_pack_uom', 10, 3)->nullable()->after('duration');
            $table->string('labor_yield_uom', 255)->nullable()->after('actual_pack_uom');
        });

        Schema::table('production_item_lines_approvals', function (Blueprint $table) {
         $table->decimal('cost_contribution', 10, 3)->nullable()->after('time_labor');
            $table->decimal('qty_contribution', 10, 3)->nullable()->after('cost_contribution');
            $table->decimal('duration', 10, 3)->nullable()->after('qty_contribution');
            $table->decimal('actual_pack_uom', 10, 3)->nullable()->after('duration');
            $table->string('labor_yield_uom', 255)->nullable()->after('actual_pack_uom');
        });


        Schema::table('production_items', function (Blueprint $table) { 
         //   $table->renameColumn('reference_number', 'item_code'); 
            $table->string('gas_costxfc', 255)->nullable()->after('gas_cost'); 
            $table->string('storage_costxfc', 255)->nullable()->after('storage_cost');
            $table->string('meralco', 255)->nullable()->after('storage_costxfc');
            $table->string('meralcoxfc', 255)->nullable()->after('meralco');
            $table->string('water', 255)->nullable()->after('meralcoxfc');
            $table->string('waterxfc', 255)->nullable()->after('water');

            $table->dropColumn('utilities');
        });

         Schema::table('production_items_approvals', function (Blueprint $table) { 
          //  $table->renameColumn('reference_number', 'item_code'); 
            $table->string('gas_costxfc', 255)->nullable()->after('gas_cost'); 
            $table->string('storage_costxfc', 255)->nullable()->after('storage_cost');
            $table->string('meralco', 255)->nullable()->after('storage_costxfc');
            $table->string('meralcoxfc', 255)->nullable()->after('meralco');
            $table->string('water', 255)->nullable()->after('meralcoxfc');
            $table->string('waterxfc', 255)->nullable()->after('water');

            $table->dropColumn('utilities');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->dropColumn(['cost_contribution', 'qty_contribution', 'duration', 'labor_yield_uom', 'actual_pack_uom']);
          //  $table->renameColumn('cost', 'landed_cost');
        });

        Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->dropColumn(['cost_contribution', 'qty_contribution', 'duration', 'labor_yield_uom', 'actual_pack_uom']);
           // $table->renameColumn('cost', 'landed_cost');
        });

        Schema::table('production_items', function (Blueprint $table) {
          //  $table->renameColumn('item_code', 'reference_number');
            $table->dropColumn(['gas_costxfc', 'storage_costxfc', 'meralco', 'meralcoxfc', 'water', 'waterxfc']);
            $table->string('utilities')->nullable();  // Assuming original was string nullable
        });

        Schema::table('production_items_approvals', function (Blueprint $table) {
         //   $table->renameColumn('item_code', 'reference_number');
            $table->dropColumn(['gas_costxfc', 'storage_costxfc', 'meralco', 'meralcoxfc', 'water', 'waterxfc']);
            $table->string('utilities')->nullable();  // Assuming original was string nullable
        });
    }
}

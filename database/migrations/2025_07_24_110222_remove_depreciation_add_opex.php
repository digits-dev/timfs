<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDepreciationAddOpex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items', function (Blueprint $table) {
             $table->dropColumn([
                'depreciation', 
            ]);
             $table->integer('opex')->nullable(); 
        });

         Schema::table('production_items_approvals', function (Blueprint $table) {
             $table->dropColumn([
                'depreciation', 
            ]);
             $table->integer('opex')->nullable(); 
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
            $table->integer('depreciation')->nullable();  
            $table->dropColumn('opex');  
        });

        Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->integer('depreciation')->nullable();  
            $table->dropColumn('opex');  
        });
    }
}

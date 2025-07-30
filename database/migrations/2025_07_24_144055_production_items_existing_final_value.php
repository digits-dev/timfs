<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemsExistingFinalValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items', function (Blueprint $table) { 
             $table->decimal('final_value_existing', 10, 3)->nullable()->after('markup_percentage');  
        });

         Schema::table('production_items_approvals', function (Blueprint $table) { 
            $table->decimal('final_value_existing', 10, 3)->nullable()->after('markup_percentage');  
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
            $table->dropColumn('final_value_existing');
        });

        Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->dropColumn('final_value_existing');
        });
    }
}

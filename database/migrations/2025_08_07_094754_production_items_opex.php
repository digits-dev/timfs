<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemsOpex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_items_opex',  function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('opex_description', 255); 
            $table->decimal('gas_cost', 10, 3)->nullable(); 
            $table->decimal('storage_cost', 10, 3)->nullable();
            $table->decimal('meralco', 10, 3)->nullable();
            $table->decimal('water', 10, 3)->nullable(); 
            $table->string('status')->length(20)->default('ACTIVE')->nullable(); 
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable(); 
            $table->timestamps();
        });

         Schema::table('production_items', function (Blueprint $table) { 

            $table->decimal('storage_costxfc', 10, 3)->nullable()->change(); 
            $table->decimal('meralco', 10, 3)->nullable()->change(); 
            $table->decimal('meralcoxfc', 10, 3)->nullable()->change(); 
            $table->decimal('water', 10, 3)->nullable()->change(); 
            $table->decimal('waterxfc', 10, 3)->nullable()->change();
        });

        Schema::table('production_items_approvals', function (Blueprint $table) { 

            $table->decimal('storage_costxfc', 10, 3)->nullable()->change(); 
            $table->decimal('meralco', 10, 3)->nullable()->change(); 
            $table->decimal('meralcoxfc', 10, 3)->nullable()->change(); 
            $table->decimal('water', 10, 3)->nullable()->change(); 
            $table->decimal('waterxfc', 10, 3)->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_items_opex');  
        Schema::table('production_items', function (Blueprint $table) { 

            $table->string('storage_costxfc', 255)->nullable()->change(); 
            $table->string('meralco', 255)->nullable()->change(); 
            $table->string('meralcoxfc', 255)->nullable()->change(); 
            $table->string('water', 255)->nullable()->change(); 
            $table->string('waterxfc', 255)->nullable()->change();
            
        });
          Schema::table('production_items_approvals', function (Blueprint $table) { 

            $table->string('storage_costxfc', 255)->nullable()->change(); 
            $table->string('meralco', 255)->nullable()->change(); 
            $table->string('meralcoxfc', 255)->nullable()->change(); 
            $table->string('water', 255)->nullable()->change(); 
            $table->string('waterxfc', 255)->nullable()->change();
            
        });
    }
}

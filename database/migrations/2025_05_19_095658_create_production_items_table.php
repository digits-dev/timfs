<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->integer('production_category')->nullable();
            $table->integer('production_location')->nullable();
            $table->integer('packaging_id')->nullable();
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('gas_cost', 10, 2)->default(0);
            $table->decimal('storage_cost', 10, 2)->default(0);
            $table->decimal('storage_multiplier', 10, 2)->default(1);
            $table->decimal('total_storage_cost', 10, 2)->default(0);
            $table->integer('storage_location')->nullable();
            $table->decimal('depreciation', 10, 2)->default(0);
            $table->decimal('raw_mast_provision', 10, 2)->default(5);
            $table->decimal('markup_percentage', 10, 2)->default(0);
            $table->decimal('final_value_vatex', 10, 2)->default(0);
            $table->decimal('final_value_vatinc', 10, 2)->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('production_items');
    }
}

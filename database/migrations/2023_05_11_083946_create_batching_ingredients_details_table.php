<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchingIngredientsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batching_ingredients_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('batching_ingredients_id')->length(10)->unsigned()->nullable();
            $table->integer('item_masters_id')->length(10)->unsigned()->nullable();
            $table->integer('new_ingredients_id')->length(10)->unsigned()->nullable();
            $table->integer('menu_as_ingredient_id')->length(10)->unsigned()->nullable();
            $table->integer('batching_as_ingredient_id')->length(10)->unsigned()->nullable();
            $table->string('ingredient_name')->length(100)->nullable();
            $table->integer('row_id')->length(10)->signed()->nullable();
            $table->integer('ingredient_group')->length(10)->signed()->nullable();
            $table->enum('is_primary', ['TRUE', 'FALSE'])->nullable();
            $table->enum('is_selected', ['TRUE', 'FALSE'])->nullable();
            $table->enum('is_existing', ['TRUE', 'FALSE'])->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->decimal('packaging_size', 18, 4)->unsigned()->nullable();
            $table->decimal('prep_qty', 18, 4)->unsigned()->nullable();
            $table->decimal('qty', 18, 4)->unsigned()->nullable();
            $table->integer('uom_id')->length(10)->unsigned()->nullable();
            $table->integer('menu_ingredients_preparations_id')->length(10)->unsigned()->nullable();
            $table->decimal('yield', 18, 4)->unsigned()->nullable();
            $table->decimal('ttp', 18, 4)->unsigned()->nullable();
            $table->decimal('cost', 18, 4)->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->length(10)->nullable();
            $table->integer('updated_by')->unsigned()->length(10)->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('batching_ingredients_details');
    }
}

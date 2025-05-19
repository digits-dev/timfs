<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionItemLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_item_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('production_item_id')->nullable();
            $table->integer('item_code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 3);
            $table->decimal('landed_cost', 10, 3);
            $table->boolean('is_alternative')->default(false);
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
        Schema::dropIfExists('production_item_lines');
    }
}

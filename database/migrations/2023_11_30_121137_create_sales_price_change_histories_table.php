<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPriceChangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_price_change_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tasteless_code')->length(10)->nullable();
            $table->decimal('sales_price', 18, 2)->unsigned()->nullable();
            $table->decimal('sales_price_change', 18, 2)->unsigned()->nullable();
            $table->timestamp('effective_date')->nullable();
            $table->string('status')->default('CREATED')->length(20)->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('sales_price_change_histories');
    }
}

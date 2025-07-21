<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemsTransferPriceCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
         Schema::create('transfer_price_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transfer_price_category_description')->nullable();
            $table->integer('transfer_price_category_markup')->nullable();
            $table->string('status')->length(20)->default('ACTIVE')->nullable();
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
         Schema::dropIfExists('transfer_price_category');
    }
}

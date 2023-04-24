<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMastersTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_masters_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creation_status')->length(50)->nullable();
            $table->integer('item_masters_id')->length(10)->nullable();
            $table->string('item_description')->nullable();
            $table->decimal('packaging_size', 16, 2)->unsigned()->nullable();
            $table->integer('uoms_id')->length(10)->unsigned()->nullable();
            $table->decimal('ttp', 16, 2)->unsigned()->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->decimal('created_by')->length(10)->unsigned()->nullable();
            $table->decimal('updated_by')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('item_masters_temp');
    }
}

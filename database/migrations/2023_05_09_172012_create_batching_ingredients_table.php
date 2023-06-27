<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchingIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batching_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bi_code')->nullable();
            $table->string('ingredient_description')->nullable();
            $table->integer('uoms_id')->length(10)->default(30)->unsigned()->nullable();
            $table->decimal('portion_size', 18, 4)->unsigned()->nullable();
            $table->decimal('ttp', 18, 4)->unsigned()->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('batching_ingredients');
    }
}

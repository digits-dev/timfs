<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMastersFasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_masters_fas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action_type',10)->nullable();
            $table->string('tasteless_code',15)->nullable();
            $table->string('item_description',15)->nullable();
            $table->integer('categories_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('subcategories_id', false, true)->length(10)->unsigned()->nullable();
            $table->decimal('cost', 18, 2)->nullable();
            $table->tinyInteger('approval_status', false, true)->length(3)->unsigned()->nullable();
            $table->integer('approved_by', false, true)->length(10)->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('item_masters_fas');
    }
}

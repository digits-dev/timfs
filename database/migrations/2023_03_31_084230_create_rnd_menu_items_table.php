<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRndMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rnd_menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_items_id')->length(10)->unsigned()->nullable();
            $table->string('rnd_menu_description')->nullable();
            $table->string('rnd_code')->length(30)->nullable();
            $table->decimal('portion_size', 18, 4)->unsigned()->nullable();
            $table->decimal('rnd_menu_srp', 18, 4)->unsigned()->nullable();
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
        Schema::dropIfExists('rnd_menu_items');
    }
}

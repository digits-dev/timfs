<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewItemsTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_item_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_type_description')->length(50)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->length(10)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->integer('new_item_types_id')->length(10)->unsigned()->nullable()->after('id');
        });

        Schema::table('new_packagings', function (Blueprint $table) {
            $table->integer('new_item_types_id')->length(10)->unsigned()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_items_types');

        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->dropColumn('new_item_types_id');
        });

        Schema::table('new_packagings', function (Blueprint $table) {
            $table->dropColumn('new_item_types_id');
        });
    }
}

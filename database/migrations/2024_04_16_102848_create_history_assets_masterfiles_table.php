<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAssetsMasterfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_assets_masterfiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tasteless_code',10)->nullable();
            $table->integer('item_id', false, true)->length(10)->unsigned()->nullable();
            $table->integer('brand_id', false, true)->length(10)->unsigned()->nullable();
            $table->decimal('old_cost',16,2)->nullable();
            $table->decimal('cost',16,2)->nullable();
            $table->string('action',191)->nullable();
            $table->longtext('details')->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('history_assets_masterfiles');
    }
}

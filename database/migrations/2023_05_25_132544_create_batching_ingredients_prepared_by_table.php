<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchingIngredientsPreparedByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batching_ingredients_prepared_by', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prepared_by')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->length(10)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->integer('batching_ingredients_prepared_by_id')->length(10)->unsigned()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batching_ingredients_prepared_by');

        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->dropColumn('batching_ingredients_prepared_by_id');
        });
    }
}

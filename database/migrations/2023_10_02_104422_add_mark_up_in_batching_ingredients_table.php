<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkUpInBatchingIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->decimal('mark_up_percent', 18, 2)->unsigned()->nullable()->after('ttp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->dropColumn('mark_up_percent');
        });
    }
}

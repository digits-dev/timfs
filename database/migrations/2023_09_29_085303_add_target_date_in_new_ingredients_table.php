<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetDateInNewIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->date('target_date')->after('comment')->nullable();
        });

        Schema::table('new_packagings', function (Blueprint $table) {
            $table->date('target_date')->after('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->dropColumn('target_date');
        });

        Schema::table('new_packagings', function (Blueprint $table) {
            $table->dropColumn('target_date');
        });
    }
}

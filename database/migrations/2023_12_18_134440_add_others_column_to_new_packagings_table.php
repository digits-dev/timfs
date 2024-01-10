<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOthersColumnToNewPackagingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->json('others')->nullable()->after('is_taggable');
        });

        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->json('others')->nullable()->after('is_taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->dropColumn('others');
        });

        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->dropColumn('others');
        });
    }
}

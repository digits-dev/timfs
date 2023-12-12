<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileColumnsToItemMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->string('filename_1')->nullable()->after('image_filename');
            $table->string('filename_2')->nullable()->after('filename_1');
            $table->string('filename_3')->nullable()->after('filename_2');
            $table->string('filename_4')->nullable()->after('filename_3');
            $table->string('filename_5')->nullable()->after('filename_4');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->string('filename_1')->nullable()->after('image_filename');
            $table->string('filename_2')->nullable()->after('filename_1');
            $table->string('filename_3')->nullable()->after('filename_2');
            $table->string('filename_4')->nullable()->after('filename_3');
            $table->string('filename_5')->nullable()->after('filename_4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->dropColumn('filename_1');
            $table->dropColumn('filename_2');
            $table->dropColumn('filename_3');
            $table->dropColumn('filename_4');
            $table->dropColumn('filename_5');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('filename_1');
            $table->dropColumn('filename_2');
            $table->dropColumn('filename_3');
            $table->dropColumn('filename_4');
            $table->dropColumn('filename_5');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsEditColumnInUserConceptAcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_concept_acess', function (Blueprint $table) {
            $table->enum('is_edit', ['TRUE', 'FALSE'])->nullable()->after('menu_segmentations_id')->default('FALSE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_concept_acess', function (Blueprint $table) {
            $table->dropColumn('is_edit');
        });
    }
}

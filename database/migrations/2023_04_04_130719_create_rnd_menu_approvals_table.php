<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRndMenuApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rnd_menu_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rnd_menu_items_id')->length(10)->unsigned()->nullable();
            $table->string('approval_status')->length(100)->nullable();
            $table->integer('published_by')->length(10)->unsigned()->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('packaging_updated_by')->length(10)->unsigned()->nullable();
            $table->timestamp('packaging_updated_at')->nullable();
            $table->integer('menu_created_by')->length(10)->unsigned()->nullable();
            $table->timestamp('menu_created_at')->nullable();
            $table->integer('costing_updated_by')->length(10)->unsigned()->nullable();
            $table->timestamp('costing_updated_at')->nullable();
            $table->integer('marketing_approved_by')->length(10)->unsigned()->nullable();
            $table->timestamp('marketing_approved_at')->nullable();
            $table->integer('accounting_approved_by')->length(10)->unsigned()->nullable();
            $table->timestamp('accounting_approved_at')->nullable();
            $table->integer('rejected_by')->length(10)->unsigned()->nullable();
            $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('rnd_menu_approvals');
    }
}

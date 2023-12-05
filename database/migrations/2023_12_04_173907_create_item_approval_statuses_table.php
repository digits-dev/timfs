<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemApprovalStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_approval_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_description')->length(20)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->nullable();
            $table->smallInteger('created_by')->unsigned()->nullable();
            $table->smallInteger('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('item_approval_statuses');
    }
}

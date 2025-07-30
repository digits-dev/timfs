<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemsComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
        Schema::create('production_items_comments', function (Blueprint $table) {
            $table->id(); // Primary key (optional but recommended)
            $table->integer('production_items_id')->length(10)->unsigned()->nullable();
            $table->text('comment_content')->nullable();
            $table->string('comment_id', 255)->nullable();
            $table->string('parent_id', 255)->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_items_comments');
    }
}

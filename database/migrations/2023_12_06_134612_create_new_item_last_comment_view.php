<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewItemLastCommentView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS new_items_last_comment;");

        DB::statement("
            CREATE VIEW new_items_last_comment AS
            SELECT
                new_items_comments.new_ingredients_id,
                new_items_comments.new_packagings_id,
                max(id) as new_items_comments_id
            from new_items_comments
            GROUP BY
                new_ingredients_id, new_packagings_id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS new_items_last_comment;");

    }
}

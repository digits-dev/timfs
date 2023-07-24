<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimaryIngredientsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // for menu items
        DB::statement("
            CREATE VIEW menu_primary_ingredients AS
                SELECT
                    subquery.id AS menu_ingredients_details_id,
                    menu_items.id as menu_items_id,
                    menu_items.tasteless_menu_code,
                    menu_items.menu_item_description,
                    COALESCE(
                        item_as_ingredient.tasteless_code,
                        menu_as_ingredient.tasteless_menu_code
                    ) AS tasteless_code,
                    COALESCE(
                        ingredient_view.full_item_description,
                        ingredient_view.menu_item_description,
                        ingredient_view.item_description,
                        ingredient_view.ingredient_description,
                        ingredient_view.ingredient_name
                    ) AS ingredient,
                    ingredient_view.prep_qty AS quantity,
                    COALESCE(
                        ingredient_view.packaging_description,
                        ingredient_view.uom_description
                    ) AS uom,
                    COALESCE(subquery.cost, 0) AS cost
                FROM menu_items
                    JOIN (
                        SELECT
                            mi.id AS menu_items_id,
                            COALESCE(sic.id, pic.id) AS id,
                            ig.ingredient_group,
                            SUM(COALESCE(sic.cost, pic.cost)) AS cost
                        FROM menu_items mi
                            JOIN (
                                SELECT
                                    menu_items_id,
                                    ingredient_group
                                FROM
                                    menu_ingredients_auto_compute
                                GROUP BY
                                    menu_items_id,
                                    ingredient_group
                            ) ig ON mi.id = ig.menu_items_id
                            JOIN menu_ingredients_auto_compute pic ON mi.id = pic.menu_items_id
                            AND pic.ingredient_group = ig.ingredient_group
                            AND pic.is_primary = 'TRUE'
                            AND pic.status = 'ACTIVE'
                            LEFT JOIN (
                                SELECT
                                    id,
                                    menu_items_id,
                                    ingredient_group,
                                    cost
                                FROM
                                    menu_ingredients_auto_compute
                                WHERE
                                    is_selected = 'TRUE'
                                    AND menu_ingredients_auto_compute.status = 'ACTIVE'
                            ) sic ON mi.id = sic.menu_items_id
                            AND sic.ingredient_group = ig.ingredient_group
                            AND (
                                sic.id IS NOT NULL
                                OR pic.id IS NOT NULL
                            )
                        GROUP BY
                            mi.id,
                            COALESCE(sic.id, pic.id),
                            ig.ingredient_group
                    ) subquery ON subquery.menu_items_id = menu_items.id
                    LEFT JOIN menu_ingredients_auto_compute ingredient_view ON ingredient_view.id = subquery.id
                    LEFT JOIN item_masters item_as_ingredient ON item_as_ingredient.id = ingredient_view.item_masters_id
                    LEFT JOIN menu_items menu_as_ingredient ON menu_as_ingredient.id = ingredient_view.menu_as_ingredient_id
                    LEFT JOIN batching_ingredients batching_as_ingredient ON batching_as_ingredient.id = ingredient_view.batching_ingredients_id        
        ");

        DB::statement("
            CREATE VIEW rnd_menu_primary_ingredients AS
                SELECT
                    subquery.id AS rnd_menu_ingredients_details_id,
                    rnd_menu_items.id as rnd_menu_items_id,
                    rnd_menu_items.rnd_code,
                    rnd_menu_items.rnd_menu_description,
                    COALESCE(
                        item_as_ingredient.tasteless_code,
                        menu_as_ingredient.tasteless_menu_code
                    ) AS tasteless_code,
                    COALESCE(
                        ingredient_view.full_item_description,
                        ingredient_view.menu_item_description,
                        ingredient_view.item_description,
                        ingredient_view.ingredient_description,
                        ingredient_view.ingredient_name
                    ) AS ingredient,
                    ingredient_view.prep_qty AS quantity,
                    COALESCE(
                        ingredient_view.packaging_description,
                        ingredient_view.uom_description
                    ) AS uom,
                    COALESCE(subquery.cost, 0) AS cost
                FROM rnd_menu_items
                    JOIN (
                        SELECT
                            mi.id AS rnd_menu_items_id,
                            COALESCE(sic.id, pic.id) AS id,
                            ig.ingredient_group,
                            SUM(COALESCE(sic.cost, pic.cost)) AS cost
                        FROM rnd_menu_items mi
                            JOIN (
                                SELECT
                                    rnd_menu_items_id,
                                    ingredient_group
                                FROM
                                    rnd_menu_ingredients_auto_compute
                                GROUP BY
                                    rnd_menu_items_id,
                                    ingredient_group
                            ) ig ON mi.id = ig.rnd_menu_items_id
                            JOIN rnd_menu_ingredients_auto_compute pic ON mi.id = pic.rnd_menu_items_id
                            AND pic.ingredient_group = ig.ingredient_group
                            AND pic.is_primary = 'TRUE'
                            AND pic.status = 'ACTIVE'
                            LEFT JOIN (
                                SELECT
                                    id,
                                    rnd_menu_items_id,
                                    ingredient_group,
                                    cost
                                FROM
                                    rnd_menu_ingredients_auto_compute
                                WHERE
                                    is_selected = 'TRUE'
                                    AND rnd_menu_ingredients_auto_compute.status = 'ACTIVE'
                            ) sic ON mi.id = sic.rnd_menu_items_id
                            AND sic.ingredient_group = ig.ingredient_group
                            AND (
                                sic.id IS NOT NULL
                                OR pic.id IS NOT NULL
                            )
                        GROUP BY
                            mi.id,
                            COALESCE(sic.id, pic.id),
                            ig.ingredient_group
                    ) subquery ON subquery.rnd_menu_items_id = rnd_menu_items.id
                    LEFT JOIN rnd_menu_ingredients_auto_compute ingredient_view ON ingredient_view.id = subquery.id
                    LEFT JOIN item_masters item_as_ingredient ON item_as_ingredient.id = ingredient_view.item_masters_id
                    LEFT JOIN menu_items menu_as_ingredient ON menu_as_ingredient.id = ingredient_view.menu_as_ingredient_id
                    LEFT JOIN batching_ingredients batching_as_ingredient ON batching_as_ingredient.id = ingredient_view.batching_ingredients_id
        ");

        DB::statement("
            CREATE VIEW batching_primary_ingredients AS 
                SELECT
                    subquery.id AS batching_ingredients_details_id,
                    batching_ingredients.id as batching_ingredients_id,
                    batching_ingredients.bi_code,
                    batching_ingredients.ingredient_description,
                    COALESCE(
                        item_as_ingredient.tasteless_code,
                        menu_as_ingredient.tasteless_menu_code
                    ) AS tasteless_code,
                    COALESCE(
                        ingredient_view.full_item_description,
                        ingredient_view.menu_item_description,
                        ingredient_view.item_description,
                        ingredient_view.ingredient_name,
                        ingredient_view.ingredient_description
                    ) AS ingredient,
                    ingredient_view.prep_qty AS quantity,
                    COALESCE(
                        ingredient_view.packaging_description,
                        ingredient_view.uom_description
                    ) AS uom,
                    COALESCE(subquery.cost, 0) AS cost
                FROM batching_ingredients
                    JOIN (
                        SELECT
                            mi.id AS batching_ingredients_id,
                            COALESCE(sic.id, pic.id) AS id,
                            ig.ingredient_group,
                            SUM(COALESCE(sic.cost, pic.cost)) AS cost
                        FROM
                            batching_ingredients mi
                            JOIN (
                                SELECT
                                    batching_ingredients_id,
                                    ingredient_group
                                FROM
                                    batching_ingredients_auto_compute
                                GROUP BY
                                    batching_ingredients_id,
                                    ingredient_group
                            ) ig ON mi.id = ig.batching_ingredients_id
                            JOIN batching_ingredients_auto_compute pic ON mi.id = pic.batching_ingredients_id
                            AND pic.ingredient_group = ig.ingredient_group
                            AND pic.is_primary = 'TRUE'
                            AND pic.status = 'ACTIVE'
                            LEFT JOIN (
                                SELECT
                                    id,
                                    batching_ingredients_id,
                                    ingredient_group,
                                    cost
                                FROM
                                    batching_ingredients_auto_compute
                                WHERE
                                    is_selected = 'TRUE'
                                    AND batching_ingredients_auto_compute.status = 'ACTIVE'
                            ) sic ON mi.id = sic.batching_ingredients_id
                            AND sic.ingredient_group = ig.ingredient_group
                            AND (
                                sic.id IS NOT NULL
                                OR pic.id IS NOT NULL
                            )
                        GROUP BY
                            mi.id,
                            COALESCE(sic.id, pic.id),
                            ig.ingredient_group
                    ) subquery ON subquery.batching_ingredients_id = batching_ingredients.id
                    LEFT JOIN batching_ingredients_auto_compute ingredient_view ON ingredient_view.id = subquery.id
                    LEFT JOIN item_masters item_as_ingredient ON item_as_ingredient.id = ingredient_view.item_masters_id
                    LEFT JOIN menu_items menu_as_ingredient ON menu_as_ingredient.id = ingredient_view.menu_as_ingredient_id
                    LEFT JOIN batching_ingredients batching_as_ingredient ON batching_as_ingredient.id = ingredient_view.batching_as_ingredient_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('primary_ingredients_view');
    }
}

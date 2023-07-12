<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMenuIngredientsAutoComputeSqlView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            DROP VIEW IF EXISTS menu_ingredients_auto_compute;
        ");

        DB::statement("
            CREATE VIEW
                menu_ingredients_auto_compute AS
            SELECT
                menu_ingredients_details.id,
                menu_ingredients_details.menu_items_id,
                menu_ingredients_details.item_masters_id,
                menu_ingredients_details.menu_as_ingredient_id,
                menu_ingredients_details.new_ingredients_id,
                menu_ingredients_details.batching_ingredients_id,
                item_masters.full_item_description,
                menu_items.menu_item_description,
                new_ingredients.item_description,
                batching_ingredients_computed_food_cost.ingredient_description,
                menu_ingredients_details.ingredient_name,
                menu_ingredients_details.ingredient_group,
                menu_ingredients_details.row_id,
                menu_ingredients_details.is_existing,
                menu_ingredients_details.is_primary,
                menu_ingredients_details.is_selected,
                menu_ingredients_details.prep_qty,
                menu_ingredients_details.uom_id,
                uoms.uom_description,
                menu_ingredients_details.menu_ingredients_preparations_id,
                packagings.packaging_description,
                menu_ingredients_details.yield,
                menu_ingredients_details.status,
                menu_items.food_cost AS food_cost,
                ROUND(
                    menu_ingredients_details.yield / 100,
                    4
                ) as converted_yield,
                1 as uom_qty,
                CASE
                    WHEN menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                    WHEN menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
                    WHEN menu_ingredients_details.batching_ingredients_id IS NOT NULL THEN batching_ingredients_computed_food_cost.ttp
                    WHEN menu_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
                    ELSE menu_ingredients_details.ttp
                END as ttp,
                CASE
                    WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                    WHEN batching_ingredients_computed_food_cost.quantity IS NOT NULL THEN batching_ingredients_computed_food_cost.quantity
                    WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
                    WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
                    ELSE 1
                END as packaging_size,
                ROUND(
                    prep_qty / (
                        ROUND(
                            menu_ingredients_details.yield / 100,
                            4
                        )
                    ),
                    4
                ) as ingredient_qty,
                ROUND(
                    1 / (
                        CASE
                            WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                            WHEN batching_ingredients_computed_food_cost.quantity IS NOT NULL THEN batching_ingredients_computed_food_cost.quantity
                            WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
                            WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
                            ELSE 1
                        END
                    ) * prep_qty / (
                        ROUND(
                            menu_ingredients_details.yield / 100,
                            4
                        )
                    ),
                    4
                ) as modifier,
                ROUND(
                    ROUND(
                        1 / (
                            CASE
                                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                                WHEN batching_ingredients_computed_food_cost.quantity IS NOT NULL THEN batching_ingredients_computed_food_cost.quantity
                                WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
                                WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
                                ELSE 1
                            END
                        ) * prep_qty / (
                            ROUND(
                                menu_ingredients_details.yield / 100,
                                4
                            )
                        ),
                        4
                    ) * CASE
                        WHEN menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                        WHEN menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
                        WHEN menu_ingredients_details.batching_ingredients_id IS NOT NULL THEN batching_ingredients_computed_food_cost.ttp
                        WHEN menu_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
                        ELSE menu_ingredients_details.ttp
                    END,
                    4
                ) as cost
            FROM menu_ingredients_details
                LEFT JOIN item_masters ON item_masters.id = menu_ingredients_details.item_masters_id
                LEFT JOIN menu_items ON menu_items.id = menu_ingredients_details.menu_as_ingredient_id
                LEFT JOIN new_ingredients ON new_ingredients.id = menu_ingredients_details.new_ingredients_id
                LEFT JOIN batching_ingredients_computed_food_cost ON batching_ingredients_computed_food_cost.id = menu_ingredients_details.batching_ingredients_id
                LEFT JOIN uoms ON uoms.id = COALESCE(
                    item_masters.uoms_id, menu_items.uoms_id, batching_ingredients_computed_food_cost.uoms_id, menu_ingredients_details.uom_id
                )
                LEFT JOIN packagings ON packagings.id = COALESCE(
                    item_masters.packagings_id, menu_items.uoms_id, batching_ingredients_computed_food_cost.uoms_id, menu_ingredients_details.uom_id
                );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRndComputedFoodCostView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW rnd_menu_computed_food_cost AS 
                SELECT
                    rnd_menu_items.id,
                    rnd_menu_items.rnd_menu_description,
                    rnd_menu_items.status,
                    rnd_menu_items.portion_size,
                    ROUND(SUM(subquery.cost), 4) as computed_ingredient_total_cost,
                    ROUND(
                        ROUND(SUM(subquery.cost), 4) / rnd_menu_items.portion_size,
                        4
                    ) AS computed_food_cost,
                    ROUND(
                        ROUND(
                            ROUND(SUM(subquery.cost), 4) / rnd_menu_items.portion_size,
                            4
                        ) / rnd_menu_items.rnd_menu_srp * 100,
                        2
                    ) AS computed_food_cost_percentage
                FROM rnd_menu_items
                    JOIN (
                        SELECT
                            mi.id AS rnd_menu_items_id,
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
                        GROUP BY
                            mi.id,
                            ig.ingredient_group
                    ) subquery ON subquery.rnd_menu_items_id = rnd_menu_items.id
                GROUP BY (subquery.rnd_menu_items_id)
            ; 
        
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS rnd_menu_computed_food_cost;");
    }
}

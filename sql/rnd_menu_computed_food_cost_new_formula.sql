SELECT
    rnd_menu_items.id,
    rnd_menu_items.rnd_menu_description,
    rnd_menu_items.status,
    rnd_menu_items.portion_size,
    subquery.computed_ingredient_total_cost,
    ROUND(
        subquery.computed_ingredient_total_cost * (
            1 + (rnd_menu_items.buffer / 100)
        ) / rnd_menu_items.portion_size,
        4
    ) AS computed_food_cost,
    ROUND(
        ROUND(
            subquery.computed_ingredient_total_cost * (
                1 + (rnd_menu_items.buffer / 100)
            ) / rnd_menu_items.portion_size,
            4
        ) / NULLIF( (
                ROUND( (
                        rnd_menu_items.rnd_menu_srp - COALESCE(
                            rnd_menu_computed_packaging_cost.computed_packaging_total_cost,
                            0
                        )
                    ) / 1.12,
                    4
                )
            ) * 100,
            0
        ),
        2
    ) AS computed_food_cost_percentage
FROM rnd_menu_items
    JOIN (
        SELECT
            rnd_menu_primary_ingredients.rnd_menu_items_id,
            SUM(
                rnd_menu_primary_ingredients.cost
            ) AS computed_ingredient_total_cost
        FROM
            rnd_menu_primary_ingredients
        GROUP BY
            rnd_menu_primary_ingredients.rnd_menu_items_id
    ) subquery ON subquery.rnd_menu_items_id = rnd_menu_items.id
    LEFT JOIN rnd_menu_computed_packaging_cost ON rnd_menu_computed_packaging_cost.id = rnd_menu_items.id
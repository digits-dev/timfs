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
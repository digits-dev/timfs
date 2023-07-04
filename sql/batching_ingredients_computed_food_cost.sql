SELECT
    batching_ingredients.id,
    batching_ingredients.ingredient_description,
    batching_ingredients.status,
    batching_ingredients.portion_size,
    batching_ingredients.ttp,
    ROUND(
        batching_ingredients.ttp / batching_ingredients.portion_size,
        4
    ) as portion_ttp,
    ROUND(SUM(subquery.cost), 4) as ingredient_total_cost,
    ROUND(
        ROUND(SUM(subquery.cost), 4) / batching_ingredients.portion_size,
        4
    ) AS food_cost
FROM batching_ingredients
    JOIN (
        SELECT
            mi.id AS batching_ingredients_id,
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
        GROUP BY
            mi.id,
            ig.ingredient_group
    ) subquery ON subquery.batching_ingredients_id = batching_ingredients.id
GROUP BY (
        subquery.batching_ingredients_id
    );
SELECT
    subquery.id AS menu_packagings_details_id,
    menu_items.id as menu_items_id,
    menu_items.tasteless_menu_code,
    menu_items.menu_item_description,
    item_as_packaging.tasteless_code AS tasteless_code,
    COALESCE(
        packaging_view.full_item_description,
        packaging_view.item_description,
        packaging_view.packaging_name
    ) AS packaging,
    packaging_view.prep_qty AS quantity,
    COALESCE(
        packaging_view.packaging_description,
        packaging_view.uom_description
    ) AS uom,
    COALESCE(subquery.cost, 0) AS cost
FROM menu_items
    JOIN (
        SELECT
            mi.id AS menu_items_id,
            COALESCE(sic.id, pic.id) AS id,
            ig.packaging_group,
            SUM(COALESCE(sic.cost, pic.cost)) AS cost
        FROM menu_items mi
            JOIN (
                SELECT
                    menu_items_id,
                    packaging_group
                FROM
                    menu_packagings_auto_compute
                GROUP BY
                    menu_items_id,
                    packaging_group
            ) ig ON mi.id = ig.menu_items_id
            JOIN menu_packagings_auto_compute pic ON mi.id = pic.menu_items_id
            AND pic.packaging_group = ig.packaging_group
            AND pic.is_primary = 'TRUE'
            AND pic.status = 'ACTIVE'
            LEFT JOIN (
                SELECT
                    id,
                    menu_items_id,
                    packaging_group,
                    cost
                FROM
                    menu_packagings_auto_compute
                WHERE
                    is_selected = 'TRUE'
                    AND menu_packagings_auto_compute.status = 'ACTIVE'
            ) sic ON mi.id = sic.menu_items_id
            AND sic.packaging_group = ig.packaging_group
            AND (
                sic.id IS NOT NULL
                OR pic.id IS NOT NULL
            )
        GROUP BY
            mi.id,
            COALESCE(sic.id, pic.id),
            ig.packaging_group
    ) subquery ON subquery.menu_items_id = menu_items.id
    LEFT JOIN menu_packagings_auto_compute packaging_view ON packaging_view.id = subquery.id
    LEFT JOIN item_masters item_as_packaging ON item_as_packaging.id = packaging_view.item_masters_id
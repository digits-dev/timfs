CREATE VIEW MENU_COMPUTED_PACKAGING_COST AS 
	SELECT
	    menu_items.id,
	    menu_items.menu_item_description,
	    menu_items.status,
	    ROUND(SUM(subquery.cost), 4) as computed_packaging_total_cost
	FROM menu_items
	    JOIN (
	        SELECT
	            mi.id AS menu_items_id,
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
	        GROUP BY
	            mi.id,
	            ig.packaging_group
	    ) subquery ON subquery.menu_items_id = menu_items.id
	GROUP BY (subquery.menu_items_id);
; 

;

;
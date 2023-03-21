CREATE VIEW ITEM_MASTERS_TTP AS 
	SELECT
	    id,
	    tasteless_code,
	    full_item_description,
	    round(
	        item_masters.ttp / item_masters.packaging_size,
	        4
	    ) as computed_ttp
	FROM
ITEM_MASTERS; 
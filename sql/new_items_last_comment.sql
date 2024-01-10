SELECT
    new_items_comments.new_ingredients_id,
    new_items_comments.new_packagings_id,
    max(id) as new_items_comments_id
from new_items_comments
GROUP BY
    new_ingredients_id, new_packagings_id;
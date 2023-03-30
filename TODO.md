# TODOS:

- [x] Show the SRP of the menu on the front end
- [x] Add new column `packaging_uom` in item_masters - should not be included on import QB template // Already exists (`packagings_id`)
- [x] Add 'Recipe' text
- [x] Change 'Ingredient SRP' to 'Ingredient Cost'
- [x] AutoFill the Ingredient UOM and Ingredient Cost
- [x] Implement the conversion // Not necessary because of the `packagings_id`
- [x] Implement cost vs. srp percentage
- [x] Implement option to add substitute ingredient
- [x] Add new column `ingredient_cost` in item_masters
- [x] brand searching
- [x] Fix the logic of the delete button
- [x] Add food cost on database
- [x] Add food cost when extracting menu items
- [x] Dashboard summary of Low cost and High cost per concept
- [x] When table data is clicked, it should redirect to a page of list of menu items
- [x] Update the localhost system like the one on production
- [x] Update the code uom_desc should be from the packagings_desc
- [x] Add experimental menu
- [x] Show the percentage of food cost vs srp on the list [new column]
- [x] Show the percentage of food cost vs srp on the details page of menu item
- [x] Add percentage on database and exporting
- [x] Add new column (No Cost)
- [x] fix the hacky way of cost filter (use form instead)
- [x] Use the `ttp` / `packaging_size` \* qty formula instead for cost
- [x] Update the view details page also
- [x] Implement the workflow of menu item
- [x] Show the version of the ingredient lists
- [x] Show when was the last price update
- [x] Update ingredient cost if imfs is updated
- [x] Implement the lastest formula
- [x] Turn the UOM to select
- [x] Add necessary inputs for the ingredients entered by users
- [x] Create a new migration file for new columns in database
- [x] Save the new fields to database
- [x] Render the new saved ingredients to the database
- [x] Improve the brand searching

---

## Workflow

- [x] Create various privileges (chef, marketing(approver), accounting(approver))

  - Only the chef can edit the ingredients

- [x] Create new column `temp_qty` and `temp_cost` in `menu_ingredients_details`
- [x] Create new table `menu_ingredients_approval`

  - Once the edit is saved, the qty and cost will be inserted to the temp cost and qty

- [x] Restrict the chef to edit again the menu items until approved by both or rejected by any or both of the approvers
- [x] Implement notification if any of the approvers approved or rejected the menu item.

  - Only marketing and accounting can approve or reject the edited menu items
  - The approval of the edit (by: marketing and accounting) can be done in any order
  - If both of the approvers approved the menu item, the food cost should be updated in menu items masterfile.

---

## Ingredient Cost Formula

- `uom_qty` = 1
- `packaging_size` = from the database || from the user if new || 1
- `ttp` = from the database || from the user if new
- `prep_qty` = from the user
- `yield` = from the user
- `ingredient_modifier` = `uom_qty` / `packaging_size` \* `prep_qty` \* (1 + (1 - `yield`))
- `ingredient_cost` = `ingredient_modifier` \* `ttp`
- `ingredient_qty` = `prep_qty` \* (1 + (1 - `yield`))

---

## Files that need to be copied to timfs-prod

- database\migrations\2023_03_17_085417_create_menu_ingredients_preparations_table.php
- database\migrations\2023_03_17_132659_add_columns_for_preparation_to_menu_ingredients_details.php
- database\migrations\2023_03_24_094654_add_packaging_size_column_in_menu_ingredients_details.php
- database\migrations\2023_03_20_160426_create_menu_ingredients_auto_compute_view.php
- app\Http\Controllers\AdminMenuIngredientsPreparationsController.php
- database\migrations\2023_03_24_154943_add_portion_size_to_menu_items_table.php
- database\migrations\2023_03_27_090335_create_menu_food_cost_view.php

---

## RND - Research and Development

- ### Tables

  - [ ] `rnd_menu_items`

    - `id`
    - `rnd_menu_description`
    - `rnd_code`
    - `rnd_tasteless_code`

  - [ ] `rnd_menu_ingredients_details`
    - `id`
    - `rnd_menu_items_id`
    - `item_masters_id`
    - `menu_as_ingredient_id`
    - `ingredient_name`
    - `ingredient_group`
    - `row_id`
    - `is_primary`
    - `is_selected`
    - `is_primary`
    - `is_existing`
    - `status`
    - `packaging_size`
    - `qty`
    - `prep-qty`
    - `uom_id`
    - `menu_ingredients_preparations_id`
    - `yield`
    - `ttp`
    - `cost`
    - `created_at`
    - `created_by`
    - `updated_at`
    - `updated_by`
    - `deleted_at`
    - `deleted_by`

---

- Author: Fillinor Gunio

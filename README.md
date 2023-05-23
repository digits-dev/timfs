# 📄 TIMFS - Tasteless Item Masterfile System

## ✅ TODOS:

- [x] Show the SRP of the menu on the front end
- [x] Add 'Recipe' text
- [x] Change 'Ingredient SRP' to 'Ingredient Cost'
- [x] AutoFill the Ingredient UOM and Ingredient Cost
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

## 🔄️ Workflow

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

## 🟰 Ingredient Cost Formula

- `uom_qty` = 1
- `packaging_size` = from the database || from the user if new || 1
- `ttp` = from the database || from the user if new
- `prep_qty` = from the user
- `yield` = from the user
- `ingredient_modifier` = `uom_qty` / `packaging_size` \* `prep_qty` \* (1 + (1 - `yield`))
- `ingredient_cost` = `ingredient_modifier` \* `ttp`
- `ingredient_qty` = `prep_qty` \* (1 + (1 - `yield`))

---

## 📁 Files that need to be copied to timfs-prod

- database\migrations\2023_03_17_085417_create_menu_ingredients_preparations_table.php
- database\migrations\2023_03_17_132659_add_columns_for_preparation_to_menu_ingredients_details.php
- database\migrations\2023_03_24_094654_add_packaging_size_column_in_menu_ingredients_details.php
- database\migrations\2023_03_20_160426_create_menu_ingredients_auto_compute_view.php
- app\Http\Controllers\AdminMenuIngredientsPreparationsController.php
- database\migrations\2023_03_24_154943_add_portion_size_to_menu_items_table.php
- database\migrations\2023_03_27_090335_create_menu_food_cost_view.php

---

## ✏️ RND - Research and Development

- ### ✅ TODOS:

  - [x] Add packaging page for marketing
  - [x] Remove the RND Menu SRP in the costing page
  - [x] Add the details of newly created menu in the costing page
  - [x] Add the details of newly created menu in the approver's page
  - [x] Restrict the chef to publish w/o ingredient(s)
  - [x] Restrict the marketing to publish w/o packaging(s)
  - [x] Add Final SRP with VAT (Dine In)
  - [x] Add Final SRP with VAT (Take Out)
  - [x] Add Final SRP with VAT (Delivery)
  - [x] Thicken the divider line in the table
  - [x] Switch the position of percentage and final srp(s)
  - [x] Add chat-box-like comment section
    - [x] Add delete button
    - Add to different pages
      - [x] Packaging page
      - [x] Costing Page
      - [x] Marketing Approver Page
      - [x] Accounting Approver Page
  - [x] Use Add page of Patrick for menu creation
  - [x] Use Edit page of Patrick for editing the menu
  - [x] Create new module for Purchasing
    - [x] Create page for adding new item
    - [x] Implement update to chef's ingredient when item is created
    - [x] Implement update to marketing's packaging when item is created
  - [x] Create page for the approval of accounting

    - [x] Restrict accounting to approve until all items have codes
    - [x] Change the approval status after approval

  ***

  - [x] Add **Food Tasting** button in chef dashboard
  - Create modules for new item
    - [x] New ingredients
      - [x] Add tagging of newly added item via `tasteless_code`
      - [x] After tagging, update all ingredients inputted by chef
    - [x] New Packagings
      - [x] Add tagging of newly added item via `tasteless_code`
      - [x] After tagging, update all packagings inputted by chef and/or marketing
  - [x] Create module for batching ingredients
    - [x] Adjust the page of adding ingredients page
    - [x] Adjust sql views
      - rnd ingredients details
      - ingredients auto compute
      - packaging auto compute
      - rnd menu costing
  - Create chat apps for various pages and priveleges
    - [x] for chef and purchasing
    - [x] for marketing and purchasing
    - [x] chef and marketing
  - [x] Copy all ingredients from rnd menu to menu item
    - [x] Adjust sql views
      - ingredients details
      - ingredients auto compute
      - computed food cost
    - [x] Adjust the front end (edit page, details page)
  - [x] Add page for marketing (Adding release date for uploading to POS)
  - [x] Change the color to red of input if there are items with no codes

  ***

  - [x] Show ingredients to marketing approver during food tasting and approval
  - [x] Add release date (required)
  - [x] Add end date (optional)

  ***

  #### For Phase 1

  - [ ] Add new field for New Items
    - Buy Out
    - Commisary
    - Direct
  - [ ] Add red font-color and background if **Total Cost** is higher than **Ideal Food Cost**
  - [ ] Tasteless Menu Item Code should be generated after approval of accounting
  - [ ] In Menu Creation Page, hide POS OLD ITEM CODES and Prices (Dine in, Take Out, Delivery)
  - [ ] In Menu Creation, make a submaster for the menu item status
  - [ ] Add Archive button to Chef's RND Menu Dashboard
  - [ ] For approvers (Marketing & Accoounting), add new button
    - Return to Chef
    - Return to Marketing | status: (FOR PACKAGING)
  - [ ] Tagging of User Account to Concepts

- ### 🟰 RND Menu Costing Formula

  |       |           Particulars           | Value / Input by / Formula | Default Value |         Input Type         |
  | :---: | :-----------------------------: | :------------------------: | :-----------: | :------------------------: |
  | **a** |          portion size           |       input by chef        |       1       |         manual set         |
  | **b** |   recipe cost without buffer    |       input by chef        |               | based on input ingredients |
  | **c** |             buffer              |     input by marketing     |     6.5%      |         adjustable         |
  | **d** |        final recipe cost        |    `(b * (1 + c)) / a`     |               |        auto compute        |
  | **e** |         packaging cost          | input by chef / marketing  |               |  based on input packaging  |
  | **f** |         ideal food cost         |     input by marketing     |      30%      |         adjustable         |
  | **g** |  suggested final srp with vat   |       `d / f * 1.12`       |               |        auto compute        |
  | **h** |      final srp without vat      |         `i / 1.12`         |               |        auto compute        |
  | **i** |  final srp with vat (dine in)   |     input by marketing     |               |         manual set         |
  | **j** |  final srp with vat (take out)  |     input by marketing     |               |         manual set         |
  | **k** |  final srp with vat (delivery)  |     input by marketing     |               |         manual set         |
  | **l** | % cost packaging from final srp |          `e / h`           |               |        auto compute        |
  | **m** |   % food cost from final srp    |          `d / h`           |               |        auto compute        |
  | **n** |          % total cost           |          `l + m`           |               |        auto compute        |

- ### 📅 Tables
  - `rnd_menu_items`
  - `rnd_meu_ingredients_details`
  - `rnd_menu_packagings_details`
  - `rnd_meu_approvals`
  - `rnd_menu_comments`
  - `batching_ingredients`
  - `batching_ingredients_details`
  - `new_ingredients`
  - `new_packagings`
  - `new_items_comments`
- ### 👁️ SQL Views
  - `batching_ingredients_auto_compute`
  - `batching_ingredients_computed_food_cost`
  - `menu_computed_food_cost`
  - `menu_ingredients_auto_compute`
  - `rnd_menu_computed_food_cost`
  - `rnd_menu_computed_packaging_cost`
  - `rnd_menu_costing`
  - `rnd_menu_ingredients_auto_compute`
  - `rnd_menu_packagings_auto_compute`
- ### 🔄️ RND Workflow

  - #### 🧑‍🍳 Chef

    - create rnd menu item
    - add / save ingredients
    - add / save packagings
    - add new ingredients to **New Ingredients** Module
    - create batching ingredients in **Batching Ingredients** Module
    - edit the rnd menu if they wish to
    - submit the rnd menu to next step (**publish**)

  - #### 💹 Marketing

    - edit and finalize the packagings that chef inputted
    - add new packagings to **New Packagings** Module
    - view the food cost value only
    - individual ingredients are hidden
    - create / udpate the menu
    - add the costing of the menu

  - #### 👍 Marketing Approver

    - can talk to chef via comment section during food tasting
    - can see ingredients
    - can approve or reject the rnd menu item
    - should add the release and end date after accounting approval

  - #### 💵 Purchasing (FOR ITEM CREATION) -> different module

    - should see all new items to be created
    - should not be able to see which menu the items are used
    - should create items in IMFS for new ingredients / packagings
    - should tag all new ingredients / packagings
    - after tagging, new ingredients and packagings should be updated

  - #### 🧾 Accounting
    - should not see the ingredients
    - approve or reject
    - should not be able to approve until all ingredients and packagings are in IMFS

---

## 🖋️ Author:

- Fillinor Gunio

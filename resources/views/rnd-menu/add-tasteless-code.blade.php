@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
@endpush
@extends('crudbooster::admin_template')
@section('content')


{{-- 
    A COPY OF INGREDIENT ENTRY!!! FOR CLONING!!
    THIS IS HIDDEN FROM THE DOM!!! --> {display: none}
--}}
<div class="ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="true">
        <div class="ingredient-inputs">
            <label class="ingredient-label">
                <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                <div>
                    <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                    <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required readonly/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" name="prep-quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <div>
                    <input type="text" class="form-control uom" name="uom[]" value="" style="display: none;"/>
                    <input type="text" class="form-control display-uom" value="" readonly>
                </div>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation" disabled>
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" name="yield[]" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP <span class="date-updated"></span>
                <input value="" name="ttp[]" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Qty
                <input value="" name="ing-qty[]" class="form-control ing-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Cost
                <input value="" name="cost[]" class="form-control cost" type="text" readonly required>
            </label>
        </div>
    </div>
</div>

<div class="new-ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="false">
        <div class="ingredient-inputs">
            <label class="ingredient-label">
                <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
                <div>
                    <input value="" type="text" name="ingredient_name[]" class="ingredient_name form-control" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Packaging Size
                <input value="" name="pack-size[]" class="form-control pack-size" type="number" required readonly>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" name="prep-quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <select class="form-control uom" disabled>
                    @foreach ($uoms as $uom)
                    <option {{$uom->uom_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->uom_description}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation" disabled>
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" name="yield[]" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP
                <input value="" name="ttp[]" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Qty
                <input value="" name="ing-qty[]" class="form-control ing-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Cost
                <input value="" name="cost[]" class="form-control cost" type="text" readonly required>
            </label>
        </div>
    </div>
</div>

<div class="substitute" style="display: none;" isExisting="true">
    <div class="ingredient-inputs">
        <label class="ingredient-label">
            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
            <div>
                <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" readonly required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" name="quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <div>
                <input type="text" class="form-control uom" name="uom[]" value="" style="display: none;"/>
                <input type="text" class="form-control display-uom" value="" readonly>
            </div>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation" disabled>
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" name="yield[]" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP <span class="date-updated"></span>
            <input value="" name="ttp[]" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Qty
            <input value="" name="ing-qty[]" class="form-control ing-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" name="cost[]" class="form-control cost" type="text" readonly required>
        </label>
    </div>
</div> 

<div class="new-substitute" style="display: none;" isExisting="false">
    <div class="ingredient-inputs">
        <label class="ingredient-label">
            <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
            <div>
                <input value="" type="text" name="ingredient_name[]" class="ingredient_name form-control" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Packaging Size
            <input value="" name="pack-size[]" class="form-control pack-size" type="number" required readonly>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" name="prep-quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <select class="form-control uom" disabled>
                @foreach ($uoms as $uom)
                <option {{$uom->uom_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->uom_description}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation" disabled>
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" name="yield[]" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP
            <input value="" name="ttp[]" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Qty
            <input value="" name="ing-qty[]" class="form-control ing-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" name="cost[]" class="form-control cost" type="text" readonly required>
        </label>
    </div>
</div> 

{{-- 
    END OF COPY
 --}}

 {{-- DOM STARTS HERE !!!! --}}

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> {{str_replace('get', '', CRUDBooster::getCurrentMethod())}} RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="form" class="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label"><span class="required-star">*</span> RND Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_menu_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label"><span class="required-star">*</span> RND Menu SRP</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input value="{{$item ? (float) $item->rnd_menu_srp : ''}}" type="number" class="form-control rnd_menu_srp" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_code : ''}}" type="text" class="form-control rnd_code" placeholder="RND-XXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Tasteless Menu Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->menu_items_code : ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Packaging Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input value="{{$item->packaging_cost ? (float) $item->packaging_cost : ''}}" type="text" class="form-control rnd_packaging_cost"readonly>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>Ingredients</b></h3>
                    </div>
                </div>
            </div>
            <section class="ingredient-section">
                <div class="no-ingredient-warning text-center">
                    No ingredients currently saved...
                </div>
            </section>
            <section class="section-footer">
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label"><span class="required-star">*</span> Portion Size</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>÷</strong></span>
                                </div>
                                <input value="{{$item ? (float) $item->portion_size : '1'}}" type="text" class="form-control portion" placeholder="Portion Size" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Total Cost</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <input type="text" class="form-control total-cost" placeholder="Total Cost" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Food Cost (<span class="percentage"></span>)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>₱</strong></span>
                                </div>
                                <input type="text" class="form-control food-cost" placeholder="Food Cost" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
		<button class="btn btn-success pull-right" id="submit-btn" style="margin-right: 10px"><i class="fa fa-upload" ></i> Submit</button>
    </div>
</div>

@endsection

@push('bottom')

<script>
    document.title = 'Add New RND Menu Item Description';
    $('body').addClass('sidebar-collapse');
    const savedIngredients = {!! json_encode($ingredients) !!} || [];
    const rndMenuItem = {!! json_encode($item) !!};
    const action = "{{$action}}";
    const privilege = "{{$privilege}}";
    $(document).ready(function() {

        const debounce = (func, wait, immediate)=> {
            let timeout;

            return function executedFunction() {
                const context = this;
                const args = arguments;    
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            }
        }

        $.fn.firstLoad = function() {
            if (savedIngredients) $('.no-ingredient-warning').hide();

            const entryCount = [...new Set([...savedIngredients.map(e => e.ingredient_group)])];
            const section = $('.ingredient-section');
            for (i of entryCount) {
                const groupedIngredients = savedIngredients.filter(e => e.ingredient_group == i);
                const wrapperTemplate = $(document.createElement('div'));
                wrapperTemplate.addClass('ingredient-wrapper')

                groupedIngredients.forEach(savedIngredient => {
                    let element;
                    if (savedIngredient.is_primary == 'TRUE') {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //primary and existing
                            element = $('.ingredient-wrapper .ingredient-entry').eq(0).clone();
                        } else {
                            //primary and new
                            element = $('.new-ingredient-wrapper .ingredient-entry').eq(0).clone();
                        }
                    } else {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //substitute and existing
                            element = $('.substitute').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                        } else {
                            //substitute and new
                            element = $('.new-substitute').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                        }
                    }
                    if (savedIngredient.menu_status == 'INACTIVE' || savedIngredient.item_status == 'INACTIVE') 
                        element.find('.label-danger').text('⚠️INACTIVE');

                    if (savedIngredient.item_masters_id && !savedIngredient.menu_as_ingredient_id)
                        element.find('.item-from').addClass('label label-info').text('IMFS');

                    if (savedIngredient.menu_as_ingredient_id && !savedIngredient.item_masters_id)
                        element.find('.item-from').addClass('label-warning').text('MIMF');

                    const ingredientInput = element.find('.ingredient');
                    ingredientInput.val(savedIngredient.item_masters_id || savedIngredient.menu_as_ingredient_id);
                    ingredientInput.attr({
                        cost: savedIngredient.ingredient_cost || savedIngredient.food_cost,
                        uom: savedIngredient.uom_id,
                        item_id: savedIngredient.item_masters_id,
                        menu_item_id: savedIngredient.menu_as_ingredient_id,
                    });

                    if (savedIngredient.item_masters_id) element.find('.date-updated').html(
                        savedIngredient.updated_at ? `${timeago.format(savedIngredient.updated_at)}` :
                        savedIngredient.created_at ? `${timeago.format(savedIngredient.created_at)}` :
                        ''
                    );
                    element.find('.display-ingredient').val(savedIngredient.full_item_description || savedIngredient.menu_item_description);
                    element.find('.ingredient_name').val(savedIngredient.ingredient_name);
                    element.find('.pack-size').val(parseFloat(savedIngredient.packaging_size));
                    element.find('.prep-quantity').val(parseFloat(savedIngredient.prep_qty) || 0)
                    element.find('.uom').val(savedIngredient.uom_id);
                    element.find('.uom_name').val(savedIngredient.uom_name);
                    element.find('.display-uom').val(savedIngredient.uom_description);
                    element.find('.preparation option').attr('selected', false);
                    element.find('.preparation').val(savedIngredient.menu_ingredients_preparations_id)
                    element.find('.yield').val(parseFloat(savedIngredient.yield) || 0);
                    element.find('.ttp').val(parseFloat(savedIngredient.ttp) || 0).attr('packaging_size', savedIngredient.packaging_size);

                    $.fn.computeIngredientCost(element);
                    element.css('display', '');
                    wrapperTemplate.append(element);
                });
                section.append(wrapperTemplate);
            }

            if (action == 'publish' || action == 'approve') {
                $('#form input').attr('readonly', true);
                $('#form select').attr('disabled', true);
                $('#form button').hide();
                $('#form .add-sub-btn, #form .new-add-sub-btn').hide();
            }
        }

        $.fn.reload = function() {
            if($('.ingredient-wrapper').length == 1) {
                $('.no-ingredient-warning').css('display', '')
            }

            $('.display-ingredient, .ingredient_name').keyup(debounce(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                const query = $(this).val().trim().toLowerCase().split(' ').filter(e => !!e);
                const itemList = entry.find('.item-list');
                let searchResult  = [];

                if (!query.length) {
                    $('.item-list').html('');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{route('search_ingredient')}}",
                    data: { content: JSON.stringify(query)},
                    success: function(response) {
                        searchResult = JSON.parse(response);
                        $.fn.renderSearchResult(entry, itemList, searchResult);
                    },
                    error: function(response) { 
                        console.log(response); 
                    }  
                });
            }, 750));
        }

        $.fn.sumCost = function() {
            const wrappers = jQuery.makeArray($('.ingredient-wrapper, .new-ingredient-wrapper'));
            const lowCost = Number(localStorage.getItem('lowCost')) || 30;
            const portionInput = $('.portion');
            const srpInput = $('.rnd_menu_srp');
            const srp = srpInput.val() || 0;
            const percentageText = $('.percentage');
            if (portionInput.val() <= 0) portionInput.val('1');
            const portionSize = portionInput.val();
            let sum = 0;
            wrappers.forEach(wrapper => {
                const primary = $(wrapper).find('.ingredient-entry');
                const substitute = jQuery.makeArray($(wrapper).find('.substitute, .new-substitute'));
                const markedSub = substitute.filter(e => $(e).attr('primary') == 'true');
                if (!!markedSub.length) {
                    sum += Number($(markedSub[0]).find('.cost').val().replace(/[^0-9.]/g, ''));
                } else {
                    sum += Number(primary.find('.cost').val().replace(/[^0-9.]/g, ''));
                }
            });
            sum = math.round(sum, 4);
            const foodCost = math.round(sum / portionSize, 4);
            $('.total-cost').val(sum);
            $('.food-cost').val(foodCost);
            
            const percentage = srp > 0 ? math.round(foodCost / srp * 100, 2) : 0;

            $(percentageText).text(`${percentage}%`);
            if (percentage > lowCost) {
                $(percentageText).css('color', 'red');
                $('.food-cost').css({'color': 'red', 'outline': '2px solid red', 'font-weight': 'bold',});
            } else {
                $(percentageText).css('color', '');
                $('.food-cost').css({'color': '', 'outline': '', 'font-weight': 'normal'});    
            }

            $.fn.formatNumbers();
        }

        $.fn.formatNumbers = function() {
            const costs = jQuery.makeArray($('#form .cost, #form .food-cost, #form .total-cost'));
            costs.forEach(cost => {
                cost = $(cost);
                const value = Number(cost.val().replace(/[^0-9.]/g, '')).toLocaleString(undefined, {maximumFractionDigits: 4});
                cost.val('₱ ' + value);
            });
        }

        $.fn.formatSelected = function() {
            const substitutes = jQuery.makeArray($('.substitute, .new-substitute'));
            substitutes.forEach(sub => {
                if ($(sub).attr('primary') == 'true') {
                    $(sub).css('background', '#ffe662');
                    $(sub).find('.set-primary').css('color', 'black');
                } else {
                    $(sub).css('background', '');
                    $(sub).find('.set-primary').css('color', '');
                }
            });
        }

        $.fn.renderSearchResult = function(entry, itemList, searchResult) {
            const current_ingredients = {item_id: [], menu_item_id: []};

            $('#form .ingredient').each(function(ingredientIndex) {
                const ingredient = $(this);
                if (ingredientIndex != $('#form .ingredient').index(entry.find('.ingredient'))) {
                    if (ingredient.attr('item_id'))  current_ingredients.item_id.push(ingredient.attr('item_id'));
                    if (ingredient.attr('menu_item_id')) current_ingredients.menu_item_id.push(ingredient.attr('menu_item_id'));
                }
            });

            const result = [...searchResult]
                .filter(ingredient => !current_ingredients.item_id.includes(ingredient.item_masters_id?.toString()) && !current_ingredients.menu_item_id.includes(ingredient.menu_item_id?.toString()))
                .sort((a, b) => (a.full_item_description || a.menu_item_description)
                ?.localeCompare(b.full_item_description || b.menu_item_description));

            if (!result.length) {
                result.push({full_item_description: 'No Item Found'});
            }

            $('.item-list').html('');
            
            itemList.fadeIn('fast');

            const ul = $(document.createElement('ul'));
            ul.addClass('dropdown-menu');
            ul.css({
                display: 'block',
                position: 'absolute',
            });
            result.forEach(e => {
                const li = $(document.createElement('li'));
                const a = $(document.createElement('a'));
                if (!e.item_masters_id && !e.menu_item_id) {
                    a.css('color', 'red !important');
                }
                li.addClass('list-item dropdown-item');
                li.attr({
                    item_id: e.item_masters_id,
                    ttp: parseFloat(e.ttp) || parseFloat(e.food_cost) || 0,
                    packaging_size: e.packaging_size || 1,
                    uom: e.packagings_id || e.uoms_id,
                    uom_desc: e.packaging_description || e.uom_description,
                    menu_item_id: e.menu_item_id,
                    food_cost_temp: e.food_cost_temp,
                    item_desc: e.full_item_description || e.menu_item_description,
                    date_updated: e.updated_at || e.created_at,
                });
                a.html(e.full_item_description && e.item_masters_id ? `<span class="label label-info">IMFS</span> ${e.full_item_description}`
                    : e.menu_item_description ? `<span class="label label-warning">MIMF</span> ${e.menu_item_description}` 
                    : 'No Item Found');
                li.append(a);
                ul.append(li);
            });
            itemList.append(ul);
        }

        $.fn.computeIngredientCost = function(entry) {
            const yieldInput = entry.find('.yield');
            const ingredientQuantityInput = entry.find('.ing-quantity');
            const packagingSizeInput = entry.find('.pack-size');
            const preperationQuantity = entry.find('.prep-quantity').val();
            const ttpInput = entry.find('.ttp');
            const ttp = ttpInput.val() || 0;
            const costInput = entry.find('.cost');
            const yieldPercent = math.round(yieldInput.val() / 100, 4) || 0;
            const uomQty = 1;
            const packagingSize = packagingSizeInput.val() || ttpInput.attr('packaging_size');
            const ingredientModifier = math.round(uomQty / packagingSize * preperationQuantity * (1 + (1 - yieldPercent)), 4);
            const ingredientCost = math.round(ingredientModifier * ttp, 4);
            const ingredientQty = math.round(preperationQuantity * (1 + (1 - yieldPercent)), 4);

            ingredientQuantityInput.val(ingredientQty);
            costInput.val(ingredientCost);
            $.fn.sumCost();
        }

        $.fn.submitForm = function(action = 'save') {
            const ingredientsArray = [];
            const ingredientGroups = jQuery.makeArray($('#form .ingredient-wrapper, #form .new-ingredient-wrapper'));
            ingredientGroups.forEach((ingredientGroup, groupIndex) => {
                const group = $(ingredientGroup);
                const ingredientArray = [];
                const ingredients = jQuery.makeArray(group.find('.ingredient-entry, .substitute, .new-substitute'));
                ingredients.forEach((ingredient, memberIndex) => {
                    const ingredientMember = $(ingredient);
                    const ingredientObject = {};
                    ingredientObject.is_existing = (ingredientMember.attr('isExisting') == 'true').toString().toUpperCase();
                    ingredientObject.is_primary = (ingredientMember.hasClass('ingredient-entry')).toString().toUpperCase();
                    ingredientObject.is_selected = (ingredientMember.attr('primary') == 'true').toString().toUpperCase();
                    ingredientObject.row_id = memberIndex;
                    ingredientObject.ingredient_group = groupIndex;
                    ingredientObject.item_masters_id = ingredientMember.find('.ingredient').attr('item_id');
                    ingredientObject.menu_as_ingredient_id = ingredientMember.find('.ingredient').attr('menu_item_id');
                    ingredientObject.ingredient_name = ingredientMember.find('.ingredient_name').val()?.trim().toUpperCase();
                    ingredientObject.packaging_size = ingredientMember.find('.pack-size').val();
                    ingredientObject.prep_qty = ingredientMember.find('.prep-quantity').val();
                    ingredientObject.uom_id = ingredientMember.find('.uom').val();
                    ingredientObject.uom_name = ingredientMember.find('.uom_name').val()?.trim().toUpperCase();
                    ingredientObject.menu_ingredients_preparations_id = ingredientMember.find('.preparation').val();
                    ingredientObject.yield = ingredientMember.find('.yield').val();
                    ingredientObject.ttp = ingredientMember.find('.ttp').val();
                    ingredientObject.qty = ingredientMember.find('.ing-quantity').val();
                    ingredientObject.cost = ingredientMember.find('.cost').val().replace(/[^0-9.]/g, '');
                    ingredientArray.push(ingredientObject);
                });
                if (ingredientArray.length) {
                    ingredientsArray.push(ingredientArray);
                }
            });
            const result = JSON.stringify(ingredientsArray);
            const form = $(document.createElement('form'))
                .attr('method', 'POST')
                .attr('action', action == 'save' ? "{{ route('edit_by_purchasing') }}" : "{{ route('submit_by_purchasing') }}")
                .css('display', 'none');

            const csrf = $(document.createElement('input'))
                .attr({
                    type: 'hidden',
                    name: '_token',
                }).val("{{ csrf_token() }}");

            const ingredientsData = $(document.createElement('input'))
                .attr('name', 'ingredients')
                .val(result);

            const rndMenuDescriptionData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_description')
                .val($('.rnd_menu_description').val().trim());

            const rndMenuIdData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_items_id')
                .val(rndMenuItem?.id);

            const srpData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_srp')
                .val($('.rnd_menu_srp').val());
            
            const portionData = $(document.createElement('input'))
                .attr('name', 'portion_size')
                .val($('.portion').val());

            form.append(
                csrf,
                ingredientsData,
                rndMenuDescriptionData,
                rndMenuIdData,
                srpData,
                portionData,
            );
            $('.panel-body').append(form);
            form.submit();
        }

        $(document).on('click', '#save-btn', function(event) {
            const formValues = $('.ingredient-section input, .ingredient-section select');
            const isValid = jQuery.makeArray(formValues).every(e => !!$(e).val()) &&
                jQuery.makeArray($('#form .cost')).every(e => !!$(e).val().replace(/[^0-9.]/g, '')) &&
                $('.portion').val() > 0 && $('.rnd_menu_description').val() && $('.rnd_menu_srp').val() > 0;
            if (isValid) {
                Swal.fire({
                    title: action == 'getAdd' ? 'Do you want to save this item?' : 'Do you want to save the changes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.fn.submitForm();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill out all fields!',
                }).then(() => {
                    $('.ingredient-section input:invalid, .ingredient-section select:invalid').css('outline', '2px solid red');
                    $('.ingredient-section .ingredient:invalid').parents('.ingredient-entry').find('.display-ingredient').css('outline', '2px solid red');
                    if ($('.portion').val() == 0) $('.portion').css('outline', '2px solid red');
					if (!$('.rnd_menu_description').val()) $('.rnd_menu_description').css('outline', '2px solid red');
					if (!$('.rnd_menu_srp').val() || $('.rnd_menu_srp').val() <= 0) $('.rnd_menu_srp').css('outline', '2px solid red');
                });
            }
        }); 

        $(document).on('click', '.list-item', function(event) {
            const item = $(this);
            const entry = item.parents('.substitute, .ingredient-entry, .new-substitute').attr('isExisting', true);
            const preparationQty = entry.find('.prep-quantity').val();
            const preparation = entry.find('.preparation').val();
            const yield = entry.find('.yield').val();
            const ingredientInputs = $('.ingredient-inputs').eq(0).clone();
            entry.append(ingredientInputs);
            item.parents('.ingredient-inputs').remove();
            
            const ingredient = entry.find('.ingredient');
            

            if (!item.attr('item_id') && !item.attr('menu_item_id')) return;
            if (item.attr('item_id') && !item.attr('menu_item_id')) {
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-info')
                    .text('IMFS');
            } else {
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-warning')
                    .text('MIMF')
            }
            
            entry.find('.label-danger').text('');
            entry.find('.date-updated').text('');
            ingredient.val(item.attr('item_id') || item.attr('menu_item_id'));
            ingredient.attr({
                cost: $(this).attr('cost'),
                food_cost_temp: $(this).attr('food_cost_temp'),
                uom: $(this).attr('uom'),
                item_id: $(this).attr('item_id'),
                menu_item_id: $(this).attr('menu_item_id'),
            });
            if (!item.attr('item_id')) ingredient.removeAttr('item_id');
            if (!item.attr('menu_item_id')) ingredient.removeAttr('menu_item_id');
            entry.find('.display-ingredient').val(item.attr('item_desc'));
            entry.find('.uom').val(item.attr('uom'));
            entry.find('.display-uom').val(item.attr('uom_desc'));
            entry.find('.ttp')
                .val(item.attr('ttp'))
                .attr('ttp', item.attr('ttp'))
                .attr('packaging_size', item.attr('packaging_size'));
            entry.find('.yield').val(yield);
            entry.find('.preparation').val(preparation);
            entry.find('.prep-quantity').val(preparationQty);
            if (item.attr('item_id')) {
                entry.find('.date-updated').text(
                    item.attr('date_updated') ?
                    `${timeago.format(item.attr('date_updated'))}` :
                    ''
                );
            }
            entry.find('.display-ingredient').attr('readonly', false);
            $('#form input:valid, #form select:valid').css('outline', 'none');
            $('.item-list').html('');  
            $('.item-list').fadeOut();
            $.fn.computeIngredientCost(entry);
            $.fn.reload();
        }); 

        $(document).on('click', '#submit-btn', function(event) {
            const entries = jQuery.makeArray($('#form .ingredient-entry, #form .substitute, #form .new-substitute'));
            const isValid = entries.every(entry => $(entry).attr('isExisting') == 'true');
            const invalids = entries.filter(entry => $(entry).attr('isExisting') != 'true');
            if (isValid) {
                Swal.fire({
                    width: '600px',
                    title: 'Do you want to submit this item?',
                    html:  `Doing so will turn the status of this item to <label class="label label-info">FOR APPROVAL (ACCOUNTING)</label>.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.fn.submitForm('submit');
                    }
                });
            } else {
                Swal.fire({
                    width: '600px',
                    icon: 'error',
                    title: 'Oops...',
                    html: '⚠️ Please make sure all ingredients are from ' +
                            `<label class="label label-info">IMFS</label> or <label class="label label-warning">MIMF</label>` + 
                            `<br/>📄 <strong>Direction:</strong> Create new item in item master for ingredients with label <label class="label label-purple">USER</label>,` +
                            ' go back to this page and overwrite the ingredient.',
                }).then(() => {

                    invalids.forEach(entry => {
                        $(entry).find('.ingredient_name').css('border', '2px solid red');
                    });
                });
            }
        });

        $('.loading-label').remove();
        $.fn.firstLoad();
        $.fn.reload();
        $.fn.formatSelected();
        $.fn.sumCost();
    });
</script>

@endpush
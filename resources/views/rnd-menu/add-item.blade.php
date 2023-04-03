@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<style type="text/css">
    .date-updated {
        font-size: 12px;
        color: gray;
        font-weight: 400;
        font-style: italic;
    }

    .label-secondary {
        background: #7e57c2;
    }

    .dropdown-menu {
        overflow-y: auto;
        max-height: 255px;
        max-width: 700px;
    }

    .ingredient-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ingredient-wrapper, .new-ingredient-wrapper {
        position: relative;
        margin-bottom: 10px;
        border: 2px solid grey;
        border-radius: 5px;
        padding: 20px;
    }

    .ingredient-entry {
        padding: 15px;
        position: relative;
        display: flex;
        gap: 5px;
    }

    .ingredient-entry .actions {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .ingredient-entry > *, .substitute > *, .new-substitute {
        display:inline-block;
    }

    .ingredient-inputs {
        display: flex;
        width: 100%;
        overflow: auto;
    }

    .ingredient-inputs::-webkit-scrollbar {
        height: 3px;
    }

    .ingredient-inputs > * {
        margin-right: 10px;
    }

    .required-star {
        color: red;
        font-size: 15px;
    }

    #add-row {
        margin-bottom: 10px;
    }

    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }

    .ingredient {
        display: none;
    }

    .item-list {
        position: absolute;
    }

    .ingredient-section label {
        margin-bottom: 10px;
        white-space: nowrap;
    }

    .label-wide {
        min-width: 110px;
    }

    .ingredient-label {
        min-width: 300px;
    }

    .menu-item-label {
        display: block;
    }

    .list-item a {
        color: #555 !important;
    }

    .list-item a:hover {
        background: #1E90FF !important;
        color: #eee !important;
    }

    .no-ingredient-warning {
        color: grey;
        font-style: italic;
        text-align: center;
        margin-bottom: 20px;
    }

    .section-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ingredient-section input, .ingredient-section button, .ingredient-section select {
        font-weight: normal;
        margin-top: 3px; 
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        -moz-appearance:textfield; /* Firefox */
    }
    
    .substitute, .new-substitute {
        border: 1px dashed grey;
        border-radius: 5px;
        display: flex;
        gap: 5px;
        align-items: center;
        padding: 10px;
        margin-left: 50px;
        margin-bottom: 10px;
    }

    .substitute .actions, .new-substitute .actions {
        display: flex;
        gap: 3px;
    }

    .substitute .actions > * {
        margin: 1px;
    }

    .add-sub-btn, .new-add-sub-btn {
        font-size: 14;
        height: 30px;
        width: 30px;
        border-radius: 50%;
        color: white;
        position: absolute;
        bottom: -15px;
        cursor: pointer;
        transition: 200ms;
        display: flex;
		justify-content: center;
		align-items: center;
    }

    .add-sub-btn {
		background-color: #367fa9;
        left: 10px;
    }

    .new-add-sub-btn {
        background-color: #008d4c;
        left: 50px;
    }

    .add-sub-btn:hover,  .new-add-sub-btn:hover {
        transform: scale(1.2);
        /* rotate: 90deg; */
        transition: 200ms;
    }

    .food-cost-label {
        font-size: 18px !important;
    }

    .swal2-html-container {
        line-height: 3rem;
    }

</style>
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
                    <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
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
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
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
                <input value="" name="pack-size[]" class="form-control pack-size" type="number" required>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" name="prep-quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <select class="form-control uom">
                    @foreach ($uoms as $uom)
                    <option {{$uom->uom_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->uom_description}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation">
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
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

<div class="substitute" style="display: none;" isExisting="true">
    <div class="ingredient-inputs">
        <label class="ingredient-label">
            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
            <div>
                <input value="" type="text" name="ingredient[]" class="ingredient form-control" required/>
                <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
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
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
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
            <input value="" name="pack-size[]" class="form-control pack-size" type="number" required>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" name="prep-quantity[]" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <select class="form-control uom">
                @foreach ($uoms as $uom)
                <option {{$uom->uom_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->uom_description}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation">
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
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
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
        <i class="fa fa-plus"></i><strong> Add RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="form" class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_menu_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">Tasteless Menu Item Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->menu_items_code : ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
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
                <div class="add-buttons">
                    <button class="btn btn-primary" id="add-existing" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add existing ingredient</button>
                    <button class="btn btn-success" id="add-new" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add new ingredient</button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Portion Size</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>÷</strong></span>
                                </div>
                                <input type="text" class="form-control portion" placeholder="Portion Size">
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
                            <label for="" class="control-label">Food Cost</label>
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
		<button class="btn btn-success pull-right" style="margin-right: 10px"><i class="fa fa-upload" ></i> Publish</button>
    </div>
</div>

@endsection

@push('bottom')

<script>
    document.title = 'Add New RND Menu Item Description';
    $('body').addClass('sidebar-collapse');
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

        $.fn.reload = function() {
            if($('.ingredient-wrapper').length == 1) {
                $('.no-ingredient-warning').css('display', '')
            }

            $('.display-ingredient').keyup(debounce(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute');
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

            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $('#form input, #form select').keyup(function() {
                $('#form input:valid, #form select:valid').css('outline', 'none');
            });

            $('.prep-quantity').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                $.fn.computeIngredientCost(entry);
            });

            $('.yield').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                $.fn.computeIngredientCost(entry);
            });

            $('.ttp').keyup(function() {
                const ttp = $(this);
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                const [int, dec] = ttp.val().split('.');
                if (dec && dec.length > 4) {
                    const value = `${int}.${dec.slice(0,4)}`;
                    ttp.val(value);
                }
                $.fn.computeIngredientCost(entry);
            });

            $('.cost').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                $.fn.computeIngredientCost(entry);
            });

            $('.pack-size').keyup(function() {
                const entry = $(this).parents('.ingredient-entry, .substitute, .new-substitute');
                const value = $(this).val();
                if (value && value > 0) {
                    entry.find('.prep-quantity').attr('readonly', false);
                    entry.find('.yield').attr('readonly', false);
                    entry.find('.ttp').attr('readonly', false);
                    $.fn.computeIngredientCost(entry);
                } else {
                    entry.find('.prep-quantity').attr('readonly', true);
                    entry.find('.yield').attr('readonly', true);
                    entry.find('.ttp').attr('readonly', true);
                }
            });

            $('.portion').keyup(function() {
                const value = $(this).val();
                if (value && value > 0) $.fn.sumCost();
                else return;
            });

            $('.ingredient_name').keyup(function() {
                const value = $(this).val();
                $(this).val(value.toUpperCase());
            });

            $('.rnd_menu_description').keyup(function() {
                const value = $(this).val();
                $(this).val(value.toUpperCase());
            });
        }

        $.fn.sumCost = function() {
            const wrappers = jQuery.makeArray($('.ingredient-wrapper, .new-ingredient-wrapper'));
            const lowCost = Number(localStorage.getItem('lowCost')) || 30;
            const portionInput = $('.portion');
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

        $.fn.submitForm = function() {
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
                .attr('action', "{{ route('add_new_rnd_menu') }}")
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
                .val($('.rnd_menu_description').val());
            
            const foodCostData = $(document.createElement('input'))
                .attr('name', 'food_cost')
                .val($('.food-cost').val().replace(/[^0-9.]/g, ''));
            
            const portionData = $(document.createElement('input'))
                .attr('name', 'portion_size')
                .val($('.portion').val());

            const totalCostData = $(document.createElement('input'))
                .attr('name', 'ingredient_total_cost')
                .val($('.total-cost').val().replace(/[^0-9.]/g, ''));

            form.append(
                csrf,
                ingredientsData,
                rndMenuDescriptionData,
                foodCostData,
                portionData,
                totalCostData,
            );
            $('.panel-body').append(form);
            form.submit();
        }

        $(document).on('click', '#save-btn', function(event) {
            const formValues = $('.ingredient-section input, .ingredient-section select');
            const isValid = jQuery.makeArray(formValues).every(e => !!$(e).val()) &&
                jQuery.makeArray($('#form .cost')).every(e => !!$(e).val().replace(/[^0-9.]/g, '')) &&
                $('.portion').val() > 0 && $('.rnd_menu_description').val();
            if (isValid) {
                Swal.fire({
                    title: 'Do you want to save the changes?',
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
                });
            }
        }); 

        $(document).on('click', '.list-item', function(event) {
            const item = $(this);
            const entry = item.parents('.substitute, .ingredient-entry');
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
            entry.find('.yield').val('100').attr('readonly', false);
            entry.find('.preparation').attr('disabled', false);
            entry.find('.ing-quantity').val('1');
            entry.find('.prep-quantity')
                .val('1')
                .attr('readonly', false);
            if (item.attr('item_id')) {
                entry.find('.date-updated').text(
                    item.attr('date_updated') ?
                    `${timeago.format(item.attr('date_updated'))}` :
                    ''
                );
            }
            $('#form input:valid, #form select:valid').css('outline', 'none');
            $('.item-list').html('');  
            $('.item-list').fadeOut();
            $.fn.computeIngredientCost(entry);
        });

        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const sibling = entry.prev()[0];
            if (!sibling) return;
            $(sibling).animate(
                {
                    top: `+=${$(entry).outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `-=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertBefore($(entry).prev());
                    }
                }
            );
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const sibling = entry.next()[0];
            if (!sibling) return;

            $(sibling).animate(
                {
                    top: `-=${$(entry).outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `+=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertAfter($(entry).next());
                    }
                }
            );
            
        });

        $(document).on('click', '#add-existing', function() {
            const section = $($('.ingredient-wrapper').eq(0).clone());
            section.find('input').val('');
            section.find('.ingredient').val('');
            section.find('.display-ingredient').val('');
            section.find('.ingredient').val('');
            section.find('.prep-quantity').val('');
            section.find('.uom').val('');
            section.find('.cost').val('');
            section.css('display', '');
            $('.ingredient-section').append(section);
            $('.item-list').fadeOut();
            $('.no-ingredient-warning').remove();
            $.fn.reload();
        });

        $(document).on('click', '#add-new', function() {
            const section = $($('.new-ingredient-wrapper').eq(0).clone());
            section.css('display', '');
            $('.ingredient-section').append(section);
            $('.item-list').fadeOut();
            $('.no-ingredient-warning').remove();
            $.fn.reload();
        });

        $(document).on('click', '.delete', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            entry.hide(300, function() {
                $(this).remove();
                $.fn.sumCost();
            });
        }); 

        $(document).on('click', '.add-sub-btn', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const substitute = $('.substitute').eq(0).clone();
            substitute.css('display', '');
            entry.append($(substitute));
            $.fn.reload();
        });

        $(document).on('click', '.new-add-sub-btn', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const substitute = $('.new-substitute').eq(0).clone();
            substitute.css('display', '');
            entry.append($(substitute));
            $.fn.reload();
        });

        $(document).on('click', '.set-primary', function(event) {
            const sub = $(this).parents('.substitute, .new-substitute');
            const ingredientWrapper = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper');
            const isPrimary = sub.attr('primary') == 'true';
            ingredientWrapper.find('.substitute, .new-substitute').attr('primary', false);
            if (!isPrimary) {
                sub.attr('primary', true);
            }
            $.fn.formatSelected();
            $.fn.sumCost();

        });

        $(document).on('click', '.delete-sub', function(event) {
            const subEntry = $(this).parents('.substitute, .new-substitute');
            subEntry.hide('fast', function() {
                $(this).remove();
                $.fn.sumCost();
            });
        });


        $('.loading-label').remove();
        $.fn.reload();
        $.fn.formatSelected();
        $.fn.sumCost();
    });
</script>

@endpush
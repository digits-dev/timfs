
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> 
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">



  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  


{{-- DOM STARTS HERE !!!! --}}
<style>
  
  .select2-container--default .select2-selection--single {
    height: 34px;
    border-radius: 0px !important; 
  }

  .add-sub-btn-pack {
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

.add-sub-btn-pack  {
    background-color: #367fa9;
    left: 10px;
}
.add-sub-btn-pack:hover  {
    transform: scale(1.2);
    /* rotate: 90deg; */
    transition: 200ms;
}
</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-dollar"></i><strong> Production Item</strong>
        </div>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let firstError =   @json($errors->first());
                if(firstError.indexOf("99999999.99")  >= 0)
                {
                    firstError = "Final Value(VATEX) and Final Value(VATINC) is too much, please check fields";
                }

                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: firstError,
                confirmButtonText: 'OK'
                });
            });
        </script>
        @endif
        <form action="{{ route('add-production-items-to-db') }}" method="POST" id="ProductionItems" enctype="multipart/form-data">
         @csrf   
        <div class="panel-body">
        <input name="id" value="{{$item->id}}" class="hide"/> 
        <div class="row"> 
            <div class="col-md-12">
                




                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label">Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                           <input type="text" value="{{$item->description}}" class="form-control rounded" name="description" placeholder="description" aria-describedby="basic-addon1" required />
                       </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Packaging Cost</label>
                        <div class="input-group">
                           <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                             <input type="text" value="" class="form-control rounded" name="packaging_cost" id="packaging_cost" placeholder="Packaging cost" aria-describedby="basic-addon1" />
                        </div>
                        </div>
                    </div>
                </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Ingredient Cost</label>
                        <div class="input-group">
                           <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                             <input type="text" value="" class="form-control rounded" name="ingredient_cost" id="ingredient_cost" placeholder="Ingredient cost" aria-describedby="basic-addon1" />
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Production Category</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                             <select class="form-control select" id="production_category" name="production_category" required>
                                    <option value=""  selected>Select Category</option>
                                    @foreach($production_category as $category)
                                        <option value="{{ $category->id }}" {{ old('production_category', $item->production_category) == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_description }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Production Location</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <select class="form-control select" id="production_location" name="production_location" required>
                                <option value="">Select Location</option>
                                @foreach($production_location as $location)
                                    <option value="{{ $location->id }}" {{ old('production_location', $item->production_location) == $location->id ? 'selected' : '' }}>
                                        {{ $location->production_location_description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Labor Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                             <input type="text" value="{{$item->labor_cost}}" class="form-control rounded" name="labor_cost" id="labor_cost" placeholder="Labor cost" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Storage Multiplier</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                             <input type="text" value="{{$item->storage_multiplier}}"  class="form-control rounded" name="storage_multiplier" id="storage_multiplier" placeholder="Storage multiplier" aria-describedby="basic-addon1" />
                           </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Storage Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input type="text" value="{{$item->storage_cost}}" class="form-control rounded" name="storage_cost" id="storage_cost" placeholder="Storage cost" aria-describedby="basic-addon1" />
                            </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Gas Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                             <input type="text" value="{{$item->gas_cost}}" class="form-control rounded" name="gas_cost" id="gas_cost" placeholder="Gas cost" aria-describedby="basic-addon1" />
                     </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Mark Up %</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                           <input type="text"  value="{{$item->markup_percentage}}" class="form-control rounded" name="markup_percentage" id="markup_percentage" placeholder="Mark up percentage" aria-describedby="basic-addon1" />
                </div>
                    </div>
                </div>
                 
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Depreciation</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                           <input type="text" value="{{$item->depreciation}}"  class="form-control rounded" name="depreciation" id="depreciation" placeholder="Depreciation" aria-describedby="basic-addon1" />
                           </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Raw Mast Provision</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                           <input type="text"  value="{{$item->raw_mast_provision}}" class="form-control rounded" name="raw_mast_provision" id="raw_mast_provision" value="5" placeholder="Raw mass provision" aria-describedby="basic-addon1" />
                          </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Total Storage Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input type="text" value="{{$item->total_storage_cost}}"  class="form-control rounded" name="total_storage_cost" id="total_storage_cost" placeholder="Total storage cost" aria-describedby="basic-addon1" readonly/>
                         </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Storage Location</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <select class="form-control select" class="form-control rounded" name="storage_location" id="storage_location" required>
                                    <option value="">Select Location</option>
                                    @foreach($storage_location as $location)
                                        <option value="{{ $location->id }}" {{ old('storage_location', $item->storage_location) == $location->id ? 'selected' : '' }}>
                                            {{ $location->storage_location_description }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                </div>



                <br>
                 <div class="col-md-12">
                     <div class="col-md-8"> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="control-label">Final Value(VATEX)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                              <input type="text" value="{{$item->final_value_vatex}}" class="form-control rounded" name="final_value_vatex" id="final_value_vatex" placeholder="Final value vatex" aria-describedby="basic-addon1" readonly />
                              </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="control-label">Final Value(VATINC)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sticky-note"></i>
                                </div>
                                  <input type="text"  value="{{$item->final_value_vatinc}}" class="form-control rounded" name="final_value_vatinc" id="final_value_vatinc" placeholder="Fina value vatinc" aria-describedby="basic-addon1" readonly />
                           </div>
                        </div>
                    </div>
                </div>
        <hr>
         <div class="row">
                
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>Packaging</b></h3>
                    </div>
                </div>
                </div>
                <div class="package-box" style="margin-bottom: 5px">
                    <div class="package-table w-100" style="width: 100%;">
                        <div id="package-tbody" name="package-added" >
                            <!-- Rows injected by JS -->
                        </div>
                    </div>
                    <div class="no-data-available text-center py-2" style="display: none;">
                        <i class="fa fa-table"></i> <span>No Packaging currently save</span>
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
                 <div class="ingredient-box" style="margin-bottom: 5px">
                    <div class="ingredient-table w-100" style="width: 100%;">
                        <div id="ingredient-tbody" name="ingredient-added" >
                            <!-- Rows injected by JS -->
                        </div>
                    </div> 
                     <div class="no-data-available-ingredient text-center py-2" style="display: none;">
                        <i class="fa fa-table"></i> <span>No ingredients currently save</span>
                    </div>
                </div>
                <br>
                <a class="btn btn-primary" id="add-Row"><i class="fa fa-plus"></i> Add New Packaging</a>
                <a class="btn btn-success" id="add-Row-ingredient"><i class="fa fa-plus"></i> Add New Ingredient</a>
            </div>
           
                
                
                
            </div>
    </div>

 
                <button type="submit" id="sumit-form-button" class="btn btn-success  hide">+ Save data</button>
            </form>
         
             <div class="panel-footer">
                @if($item->id != '')
                    <button id="save-datas" class="btn btn-success">+ Update data</button>
                @else
                    <button id="save-datas" class="btn btn-success">+ Create data</button>
                @endif
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-link'>← Back</a>
            </div>
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    
    $(document).ready(function() {
       
        retrieve_ingredients();
        is_noingredient = false;
         
        showNoData(); 
        let tableRow = 0;
        
        let click_raw = false;
        

        $('body').addClass('sidebar-collapse');
        $(`.select`).select2({
            width: '100%',
            height: '100%' 
        });
       
        $("#add-Row").click(function () { 
            tableRow++;
            console.log( tableRow + " dd-Row");
            const newRowHtml = generateRowHtml(tableRow,"","","");
             $(newRowHtml).appendTo('#package-tbody');


               

            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
        });

         $("#add-Row-ingredient").click(function () { 
            tableRow++;
            console.log( tableRow + " dd-Row");
            const newRowHtml = generateRowingredientHtml(tableRow,"","","");
            $(newRowHtml).appendTo('#ingredient-tbody'); 
            initAutocomplete(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
        });
        


        $(document).on('click', '.add-sub-btn', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("ingredient-entry")[1];  
            const newRowPackHtml = Sub_gen_ingredient_row(tableRow,"","","");  
            $(newRowPackHtml).appendTo(`.sub-ingredient${parentId}`);
            console.log(parentId);
            initAutocomplete(`#itemDesc${tableRow}`, tableRow); 
        });


        $(document).on('click', '.add-sub-btn-pack', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("packaging-entry")[1];  
            const newRowPackHtml = Sub_gen_pack_row(tableRow,"","","");  
            $(newRowPackHtml).appendTo(`.sub-pack${parentId}`);
            console.log(parentId);
            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow); 
        });

        function retrieve_ingredients()
        {
            let itemId = "{{ $item->id }}";
            $.ajax({
                url: `/admin/production_items/get-data/${itemId}`,
                type: "GET",
                dataType: "json",
                    success: function(data) {
                                const obj =  data.ingredients; 
                               // console.log(obj[0].description);

                            $.each(obj, function(index) {

                                //assign tableRow for ajax search
                                tableRow = index;
                                //console.log( tableRow + " retrieve_ingredients");

                                console.log(obj[index]);
                                //append retrieve ingredients from array base on reference
                                const newRowHtml = generateRowHtml(index, obj[index].description, obj[index].quantity, obj[index].landed_cost,  obj[index].item_code);
                                $(newRowHtml).appendTo('#ingredient-tbody');
                                initAutocomplete(`#itemDesc${index}`, index);
                                
                                showNoData();    
                            });
                    }
            });  
        }


        function PackagingSearchInit(selector, rowId) {
           const token = $("#token").val();   
            console.log(rowId + 'pogi');
             $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#tasteless_code${rowId}`).val('');
                $(`#ttp${rowId}`).val('');
                $(`#pack-size${rowId}`).val('');   
            });
            
            $(selector).autocomplete({
                source: function (request, response) {
                $.ajax({
                    url: "{{ route('packag-search') }}",
                    type: "POST",
                    dataType: "json",
                    data: { 
                    "_token": token, 
                    "search": request.term, 
                    values: $('[id*="itemDesc"], [id*="tasteless_code"]').map(function() {
                                if($(this).val() != "") {
                                    return $(this).val();
                                } else {
                                    return 'null';
                                }
                            }).get()
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.status_no == 1) {
                            $(`#ui-id-2${rowId}`).hide();
                            response($.map(data.items, item => ({
                                label: item.item_description,
                                value: item.item_description,
                                ...item
                            })));
                        } else {
                            $('.ui-menu-item').remove();
                            $('.addedLi').remove();
                            const $ui = $(`#ui-id-2${rowId}`).html(`<i class="fa fa-exclamation fa-bounce "></i> ${data.message}`);
                            $ui.toggle($('#itemDesc' + rowId).val().length > 0);
                        }
                    }
                });
            },
                select: function (event, ui) {
                  const curid = $(this).attr("id"); 
                  var id = curid.split("itemDesc")[1];
                    console.log(id);
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code);
                    $(`#itemDesc${id}`).val(ui.item.item_description); 
                    $(`#ttp${id}`).val(Number(ui.item.cost).toFixed(2)).attr('readonly', true);
                    $(`#pack-size${id}`).val(ui.item.packaging_size);  
                    $(`#quantity${rowId}`).val('1').trigger('change'); 
                    //packaging_size
                    
                    calculateFinalValues();
                    return false;
                },
                minLength: 1,
                autoFocus: true
            }); 
        }
    

        
        function initAutocomplete(selector, rowId) {
            const token = $("#token").val();   
            console.log(rowId + 'pogi');
                $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#tasteless_code${rowId}`).val('');
                $(`#ttp${rowId}`).val('');
                $(`#pack-size${rowId}`).val('');  
                $(`#ingredient-qty${rowId}`).val('');  
            });
 
            $(selector).autocomplete({
                source: function (request, response) {
                $.ajax({
                    url: "{{ route('item-search') }}",
                    type: "POST",
                    dataType: "json",
                    data: { 
                    "_token": token, 
                    "search": request.term, 
                    values: $('[id*="itemDesc"], [id*="tasteless_code"]').map(function() {
                                if($(this).val() != "") {
                                    return $(this).val();
                                } else {
                                    return 'null';
                                }
                            }).get()
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.status_no == 1) {
                            $(`#ui-id-2${rowId}`).hide();
                            response($.map(data.items, item => ({
                                label: item.item_description,
                                value: item.item_description,
                                ...item
                            })));
                        } else {
                            $('.ui-menu-item').remove();
                            $('.addedLi').remove();
                            const $ui = $(`#ui-id-2${rowId}`).html(`<i class="fa fa-exclamation fa-bounce "></i> ${data.message}`);
                            $ui.toggle($('#itemDesc' + rowId).val().length > 0);
                        }
                    }
                });
            },
                select: function (event, ui) {
                  const curid = $(this).attr("id"); 
                  var id = curid.split("itemDesc")[1];
                    console.log(id);
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code);
                    $(`#itemDesc${id}`).val(ui.item.item_description); 
                    $(`#ttp${id}`).val(Number(ui.item.cost).toFixed(2)).attr('readonly', true);
                    $(`#pack-size${id}`).val(ui.item.packaging_size); 
                     $(`#quantity${rowId}`).val('1').trigger('change'); 
                    //packaging_size
                   
                    calculateFinalValues();
                    return false;
                },
                minLength: 1,
                autoFocus: true
            }); 
        }
 



            function Sub_gen_ingredient_row(rowId, Packaging, Quantity, Cost, Item_code)
          {
            return `  
                <div class="substitute-packaging" id="ingredient-entry${rowId}">
                <div class="packaging-inputs">
                        <label class="packaging-label">
                            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                <input value="" type="text" id="tasteless_code${rowId}" class="packaging form-control hidden" required/>
                                <input value="" type="text" id="itemDesc${rowId}" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any"/>
                        </label> 
                       
                        <label class="label-wide">
                            <span class="required-star">*</span> Yield %
                            <input value="" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label>
                        <label class="label-wide">
                            <span class="required-star">*</span> TTP <span class="date-updated"></span>
                            <input value="" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        
                        <label>
                            <span class="required-star">*</span> Ingredient Qty
                            <input value="" class="form-control pack-quantity" id="ingredient-qty${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Ingredient Cost
                            <input value="" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label>
                </div>
                <div class="actions">
                    <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
                    <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
                </div>
            </div> 
            `;
          }

          function generateRowingredientHtml(rowId, Packaging, Quantity, Cost, Item_code) {
            return ` 
            
                <div class="packaging-wrapper" id="ingredient-entry${rowId}">
                <div class="packaging-entry" isExisting="true">
                    <div class="packaging-inputs">
                        <label class="packaging-label">
                            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                <input value="" type="text" id="tasteless_code${rowId}" class="packaging form-control hidden" required/>
                                <input value="" type="text" id="itemDesc${rowId}" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any"/>
                        </label> 
                       
                        <label class="label-wide">
                            <span class="required-star">*</span> Yield %
                            <input value="" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label>
                        <label class="label-wide">
                            <span class="required-star">*</span> TTP <span class="date-updated"></span>
                            <input value="" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        
                        <label>
                            <span class="required-star">*</span> Ingredient Qty
                            <input value="" class="form-control pack-quantity" id="ingredient-qty${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Ingredient Cost
                            <input value="" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label>
                    </div>
                    <div class="actions">
                        <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
                        <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
                        <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
                    </div>
                </div>
                <div class="sub-ingredient${rowId}">
                    

                </div>
                <div  class="add-sub-btn" style="background-color: green;" title="Add Substitute Ingredient">
                    <i class="fa fa-plus"></i>
                </div> 
            </div>
            `;
        }

  
         //generate sub for packaging
          function Sub_gen_pack_row(rowId, Packaging, Quantity, Cost, Item_code)
          {
            return `  
            <div class="substitute-packaging" id="packaging-entry${rowId}">
                <div class="packaging-inputs">
                      <label class="packaging-label">
                            <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                <input value="" type="text" id="tasteless_code${rowId}" class="packaging form-control hidden" required/>
                                <input value="" type="text" id="itemDesc${rowId}" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label>
                        <label class="label-wide hide">
                            <span class="required-star">*</span> TTP <span class="date-updated"></span>
                            <input value="" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any"/>
                        </label> 
                        <label class="label-wide hide"  >
                            <span class="required-star">*</span> Yield %
                            <input value="100" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label> 
                        <label style="display: none">
                            <span class="required-star">*</span> Packaging size
                            <input value="" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Packaging Cost
                            <input value="" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label>
                </div>
                <div class="actions">
                    <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
                    <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
                </div>
            </div> 
            `;
          }

          function generateRowHtml(rowId, Packaging, Quantity, Cost, Item_code) {
            return `  
                <div class="packaging-wrapper" id="packaging-entry${rowId}">
                <div class="packaging-entry" isExisting="true">
                    <div class="packaging-inputs">
                        <label class="packaging-label">
                            <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                <input value="" type="text" id="tasteless_code${rowId}" class="packaging form-control  hidden" required/>
                                <input value="" type="text" id="itemDesc${rowId}" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                </div>
                                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="${rowId}" id="ui-id-2${rowId}" style="display: none; top: 75px; width: 9%; color:red; padding:5px; left: 15px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                            </div>
                        </label>
                        <label class="label-wide hide">
                            <span class="required-star">*</span> TTP <span class="date-updated"></span>
                            <input value="" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
                        </label>  
                        <label class="label-wide hide">
                            <span class="required-star">*</span> Yield %
                             <input value="100" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label> 
                        <label style="display: none">
                            <span class="required-star">*</span> Packaging size
                            <input value="" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Packaging Cost
                            <input value="" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label>
                    </div>
                    <div class="actions">
                        <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
                        <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
                        <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
                    </div>
                </div>
                <div class="sub-pack${rowId}">
                    

                </div>
                <div  class="add-sub-btn-pack" title="Add Substitute Packaging">
                    <i class="fa fa-plus"></i>
                </div> 
            </div>
                <br>
            `;
        }










        function validateFields() {
            let isValid = true;

            $(".itemDesc, .digits_code").each(function () {
                const val = $(this).val();
                if (!val) {
                    showError("Please fill all Fields!");
                    isValid = false;
                    return false; // break out of loop
                }
            });

            return isValid;
        }

        function showError(message) {
            swal({
                type: "error",
                title: message,
                icon: "error",
                confirmButtonColor: "#367fa9",
            });
        }

       

        $(document).on("click", ".removeRow", function (e) {
            const $row = $(this).closest('.tr-border');
           // console.log($(this).closest('.tr-border').html());
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "This row will be removed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $row.addClass('slide-out-right');
                    // Remove row after animation ends
                    $row.on('animationend', function () {
                        $row.remove(); 
                       
                        showNoData(); // Update no data message
                         calculateFinalValues();
                    });
                }
            });
        });

        //to save data and list to Production Items List module
           $('#save-datas').on('click', function() {

             if(is_noingredient == true)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please add ingredients first, packaging can’t be empty!',
                    confirmButtonText: 'OK'
                    });
                } else
                {
                    let itemId = "{{ $item->id }}";
                    if(itemId == "")
                    {
                        Swal.fire({
                        title: 'Do you want to save this production item?',
                        html:  `Doing this will create new <span class="label label-info">Production Item</span>.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Save',
                        returnFocus: false,
                        }).then((result) => {
                            
                                if (result.isConfirmed) {
                                    $('#sumit-form-button').click();
                                }
                            
                        });
                    }else
                    {
                        Swal.fire({
                        title: 'Do you want to update this production item?',
                        html:  `Doing this will update Production item reference number <span class="label label-info"> {{ $item->reference_number }}</span>.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Save',
                        returnFocus: false,
                        }).then((result) => {
                        
                                if (result.isConfirmed) {
                                    $('#sumit-form-button').click();
                                }
                            
                            
                        });
                    }
                }
            
           
        });

         
        $('#ProductionItems').on('submit', function() {
        Swal.fire({
            title: 'Loading...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
            });
        });

         // Recalculate on any input change
        $(document).on('input', '.ingredient-quantity', calculateFinalValues);
        $(document).on('change', '.ingredient-input', calculateFinalValues);
        $(document).on('input change', '[id*="quantity"], [id*="yield"]', function() {
                  
                var id = $(this).attr('id');
                var lastChar = id.split("quantity")[1] || id.split("yield")[1];
                 console.log(lastChar);
                const yieldInput = $(`#yield${lastChar}`).val() || 0;
                console.log(yieldInput);


               /*
                const container = $(this).closest('.packaging-wrapper').attr('id');
                if(container.includes('ingredient-entry'))
                {
                    console.log('ingredient');  
                    
                }
                else
                {
                    console.log('package');     
                }
                */
               
                 if($(`#tasteless_code${lastChar}`).val() != '' && yieldInput  != '0')
                {
                    
                    const yieldPercent = math.round(yieldInput / 100, 4); 
                    console.log($(`#yield${lastChar}`).val());

                    const uomQty = 1; 

                    let preperationQuantity = $(`#quantity${lastChar}`).val() || 0; 

                    let packagingSize = $(`#pack-size${lastChar}`).val() || 0; 

                    let cost = $(`#ttp${lastChar}`).val() || 0; 

                    const ingredientModifier = math.round(uomQty / packagingSize * preperationQuantity  / yieldPercent, 4);
                        
                    const ingredientCost = math.round(ingredientModifier * cost, 4); 
                    const ingredientQty = math.round(preperationQuantity / yieldInput * 100, 4); 

                    $(`#cost${lastChar}`).val(ingredientCost).attr('readonly', true);
                    $(`#ingredient-qty${lastChar}`).val(ingredientQty).attr('readonly', true);
                    
                }        

                calculateFinalValues();
            });


             
        $('#ingredient_cost, #packaging_cost, #labor_cost, #gas_cost, #storage_cost, #storage_multiplier, #depreciation, #raw_mast_provision, #markup_percentage').on('input', function() {
            calculateTotalStorage();
            calculateFinalValues();
        });
       
        //check if user input raw_mast_provision if yes then dont apply + 5%
         $('#raw_mast_provision').on('input', function() {
            click_raw = true;
        });
        
        
    });





    //Added code



        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');

             
            const prevBr = entry.prevAll('br').first(); 
            let sibling = entry.prev();
            while (sibling.length && sibling.is('br')) {
                sibling = sibling.prev();
            }

            if (!sibling.length) return;

            
            $(sibling).animate(
                {
                    top: `+=${entry.outerHeight()}`,
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
                    top: `-=${sibling.outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                      
                        if (prevBr.length) {
                            entry.insertAfter(prevBr);
                        } else {
                            entry.insertBefore(sibling);
                        }
                    }
                }
            );
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');

           
            const nextBr = entry.nextAll('br').first();

             
            let sibling = entry.next();
            while (sibling.length && sibling.is('br')) {
                sibling = sibling.next();
            }

            if (!sibling.length) return;

            $(sibling).animate(
                {
                    top: `-=${entry.outerHeight()}`,
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
                    top: `+=${sibling.outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                    
                        if (nextBr.length) {
                            entry.insertBefore(nextBr);
                        } else {
                            entry.insertAfter(sibling);
                        }
                    }
                }
            );
        });

         $(document).on('click', '.delete', function(event) {
             
            const entry = $(this).parents(
                '.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper'
            );
            entry.hide(300, function() {
                entry.prevAll('br').first().remove(); 
                $(this).remove();
                showNoData();
                calculateFinalValues();
            });
          
        }); 


        $(document).on('click', '.delete-sub', function(event) {
               
            const subEntry = $(this).parents(`
                .substitute-ingredient, 
                .new-substitute-ingredient, 
                .substitute-packaging, 
                .new-substitute-packaging
            `);
            subEntry.hide('fast', function() {
                $(this).remove();
              calculateFinalValues();
            });
           
        });
 

         // Calculate total storage cost
        function calculateTotalStorage() { 
            const storageCost = parseFloat($('#storage_cost').val()) || 0;
            const storageMultiplier = parseFloat($('#storage_multiplier').val()) || 0;
            const totalStorage = storageCost * storageMultiplier;
            $('#total_storage_cost').val(totalStorage.toFixed(2));
        }

        $('#storage_cost, #storage_multiplier').on('input', calculateTotalStorage);

        // Calculate final values
        function calculateFinalValues() {
            let ingredientsCost = 0;
            let packagingsCost = 0;
            const rawMastProvision = 0; 

            
             $('[id*="quantity"]').each(function() {
                
                var id = $(this).attr('id');
                var lastChar = id.split("quantity")[1] || id.split("yield")[1];

                const cost = parseFloat($(`#cost${lastChar}`).val()) || 0;
                const quantity = parseFloat($(this).val()) || 0;  
                
                 
                    const container = $(this).closest('.packaging-wrapper').attr('id');
                    if(container.includes('ingredient-entry'))
                    {
                       ingredientsCost += cost; 
                    }
                    else
                    {
                       packagingsCost += cost; 
                    }
                }); 
                $('#packaging_cost').val(packagingsCost.toFixed(2));
                $('#ingredient_cost').val(ingredientsCost.toFixed(2));
          

           // const packagingcost = parseFloat($('#packaging_cost').val()) || 0;
           // const ingredientcost = parseFloat($('#ingredient_cost').val()) || 0;
           // const laborCost = parseFloat($('#labor_cost').val()) || 0;
          //  const gasCost = parseFloat($('#gas_cost').val()) || 0;
          //  const totalStorageCost = parseFloat($('#total_storage_cost').val()) || 0;
           // const depreciation = parseFloat($('#depreciation').val()) || 0;
          //  const rawMastProvisions = parseFloat($('#raw_mast_provision').val()) || 0;
          //  const markupPercentage = parseFloat($('#markup_percentage').val()) || 0;



        const packagingCost = parseFloat($('#packaging_cost').val()) || 0;
        const ingredientCost = parseFloat($('#ingredient_cost').val()) || 0;
        const labor = parseFloat($('#labor_cost').val()) || 0;
        const gas = parseFloat($('#gas_cost').val()) || 0;
        const totalStorage = parseFloat($('#total_storage_cost').val()) || 0;
        const depreciation = parseFloat($('#depreciation').val()) || 0;
        const rawMatsProvisionPercent = parseFloat($('#raw_mast_provision').val()) / 100 || 0; 
        const markupPercent = parseFloat($('#markup_percentage').val()) / 100 || 0; 

        
        const total_fields = packagingCost + ingredientCost + labor + gas;

        
        const total_row = packagingCost + ingredientCost;

         
        const total = total_fields + totalStorage + depreciation + (total_row * rawMatsProvisionPercent);

      
        const finalValueVATex = total * (1 + markupPercent);
         
           
            $('#final_value_vatex').val(finalValueVATex.toFixed(2));
            $('#final_value_vatinc').val((finalValueVATex * 1.12).toFixed(2) );
        }


         function showNoData() {
            const hasRows = $('[id*="packaging-entry"]').length; 
              console.log(hasRows + 'show');  
            if (hasRows === 0) {
                $('.no-data-available').show();
                is_noingredient = true;
            } else {
                $('.no-data-available').hide();
                is_noingredient = false;
            }
        }

         $(document).on('click', '.set-primary', function(event) {
            //for primary button tsaka na to
            console.log('setted primary');
        });
      
</script>
@endpush
@endsection
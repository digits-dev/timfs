
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/production-item/custom-item.css')}}">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
<style>

    .collapse-butn::before {
        content: "";
        display: inline-block;
        vertical-align: middle;
        width: 24px;      /* set desired width */
        height: 24px;     /* set desired height */
        margin-right: 6px;
        background-image: url('https://cdn-icons-png.flaticon.com/128/685/685388.png');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        transition: transform 0.2s ease;
    }

    .collapse-butn[aria-expanded="true"]::before {
    /* Example: you can change icon here for expanded state */
    background-image: url('https://cdn-icons-png.flaticon.com/128/685/685391.png'); /* another icon URL */
    }
    input::placeholder{
        font-style: italic;
    }
    select {
        border-radius: 0px 5px 5px 0px !important; 
    }
    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }
    .ui-autocomplete {
        max-height: 400px; /* Adjust height as needed */
        overflow-y: auto;  /* Enables vertical scroll */
        overflow-x: hidden; /* Optional: hide horizontal scroll */
        z-index: 10000 !important; /* Ensure it appears above other elements */
        width: auto;
    }

    .ui-state-focus {
        background: none !important;
        background-color: #367fa9 !important;
        border: 1px solid #fff !important;
        color: #fff !important;
    }
    
    .panel-heading{
        background-color: #3c8dbc !important;
        color: #fff !important;
    }

    .input-group-addon{
        border-color: #989797 !important; 
        border-radius: 5px 0px 0px 5px !important; 
    }
    .form-control{
        border-color: #989797 !important; 
    }
    .float-label{
        position: absolute;
        background: #fff;
        top: -10px;
        left: 45px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }
    
    .rounded{
        border-radius: 5px;
    }
    .ingredient-label, .collapse-butn{
        position: relative;
        background: #fff;
        top: 20px;
        left: 45px;
        padding-left: 5px;
        padding-right: 5px; 
    }

    .float-line-label {
        position: absolute;
        background: #fff;
        top: -10px;
        left: 20px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }

    .float-line-label-no-bg{
        position: absolute;
        top: -10px;
        left: 20px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }
    
    .ingredient-box{
        border: 1px solid #989797 !important; 
        border-radius: 5px;
        height: auto;
        padding: 20px;
        min-height: 70px;
        align-items: center;
    }
    .ingredient-label{
        font-size: 18px;
    }

    .ingredient-table input.form-control {
        height: 38px;
        margin-bottom: 0;
    }

    .ingredient-table td {
        vertical-align: middle;
    }

    .no-data-available {
        font-style: italic;
        color: #999;
    }
    .tr-border {
        border: 1px solid #989797 !important;
        border-radius: 10px;
        padding: 25px;
        background-color: #f9f9f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 6px;
        gap: 5px; 
    }

    @media (max-width: 768px) {
        .ingredient-table td {
            min-width: 100%;
        }

        .ingredient-label {
            font-size: 16px;
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-in-right {
        animation: slideInRight 0.2s ease-out forwards;
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-100%);
        }
    }

    .slide-out-right {
        animation: slideOutRight 0.2s ease-in forwards;
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
        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="description"><i class="fa fa-file"></i></span>
                        <label class="description float-label">Description</label>
                        <input type="text" value="{{$item->description}}" class="form-control rounded" name="description" placeholder="description" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="production_category"><i class="fa fa-check"></i></span>
                        <label class="production_category float-label">Production Category</label>
                        <select class="form-control select" id="production_category" name="production_category" required>
                            <option value="" selected>Select Category</option>
                            @foreach($production_category as $category)
                                <option value="{{ $category->id }}" {{ old('production_category', $item->production_category) == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>
                        <label class="production_location float-label">Production Location</label>
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
        </div>

        <div class="row"> 
            <div class="col-md-12">
                <label class="ingredient-label"> 
                    <input type="hidden" id="tasteless_code-pack">
                    <input type="text" class="hide" value="{{$item->code_name}}" name="packaging_id" id="packageid" >
                    <input type="text" placeholder="Search Packaging..." class="form-control rounded ingredient-input" id="itemDesc-pack" value="{{$item->packaging_id}}" required maxlength="100">
                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2-pack" style="display: none; top: 35px; width: 100%; color:red; padding:3px">
                        <li class="text-center">Loading...</li>
                    </ul>
                </label>
                <label class="collapse-butn btn collapsed" data-toggle="collapse" id="colbtn" aria-expanded="false" aria-controls="collapseTwo"></label>
                
                <div class="ingredient-box" style="margin-bottom: 5px">
                    <table class="ingredient-table w-100" style="width: 100%;">
                        <tbody id="ingredient-tbody" name="ingredient-added" >
                            <!-- Rows injected by JS -->
                        </tbody>
                    </table>
                    <div class="no-data-available text-center py-2" style="display: none;">
                        <i class="fa fa-table"></i> <span>No ingredients currently save</span>
                    </div>
                </div>
                <a class="btn btn-primary" id="add-Row"><i class="fa fa-plus"></i> Add New Ingredient</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="labor_cost float-label">Labor Cost</label>
                        <input type="text" value="{{$item->labor_cost}}" class="form-control rounded" name="labor_cost" id="labor_cost" placeholder="Labor cost" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="gas_cost float-label">Gas Cost</label>
                        <input type="text" value="{{$item->gas_cost}}" class="form-control rounded" name="gas_cost" id="gas_cost" placeholder="Gas cost" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="storage_cost float-label">Storage Cost</label>
                        <input type="text" value="{{$item->storage_cost}}" class="form-control rounded" name="storage_cost" id="storage_cost" placeholder="Storage cost" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="storage_multiplier float-label">Storage Multiplier</label>
                        <input type="text" value="{{$item->storage_multiplier}}"  class="form-control rounded" name="storage_multiplier" id="storage_multiplier" placeholder="Storage multiplier" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px; margin-bottom: 10px">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="total_storage_cost float-label">Total Storage Cost</label>
                        <input type="text" value="{{$item->total_storage_cost}}"  class="form-control rounded" name="total_storage_cost" id="total_storage_cost" placeholder="Total storage cost" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>
                        <label class="production_location float-label">Storage Location</label>
                        <select class="form-control select form-control rounded" name="storage_location" id="storage_location" required>
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
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="depreciation float-label">Depreciation</label>
                        <input type="text" value="{{$item->depreciation}}"  class="form-control rounded" name="depreciation" id="depreciation" placeholder="Depreciation" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 15px"> % </span>
                        <label class="raw_mast_provision float-label">Raw Mast Provision</label>
                        <input type="text"  value="{{$item->raw_mast_provision}}" class="form-control rounded" name="raw_mast_provision" id="raw_mast_provision" placeholder="Raw mass provision" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                        <label class="markup_percentage float-label">Mark Up %</label>
                        <input type="text"  value="{{$item->markup_percentage}}" class="form-control rounded" name="markup_percentage" id="markup_percentage" placeholder="Mark up percentage" aria-describedby="basic-addon1" required />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2" style="font-size: 20px"> ₱ </span>
                        <label class="final_value_vatex float-label" style="background-color: #EEEEEE !important; border-radius:5px;">Final Value(VATEX)</label>
                        <input type="text" value="{{$item->final_value_vatex}}" class="form-control rounded" name="final_value_vatex" id="final_value_vatex" placeholder="Final value vatex" aria-describedby="basic-addon1" readonly />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon3" style="font-size: 20px"> ₱ </span>
                        <label class="final_value_vatinc float-label" style="background-color: #EEEEEE !important; border-radius:5px">Final Value(VATINC)</label>
                        <input type="text"  value="{{$item->final_value_vatinc}}" class="form-control rounded" name="final_value_vatinc" id="final_value_vatinc" placeholder="Fina value vatinc" aria-describedby="basic-addon1" readonly />
                    </div>
                </div>
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
         PackagingSearchInit('#itemDesc-pack');
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
             $(newRowHtml).appendTo('#ingredient-tbody');


               

            initAutocomplete(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
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


         function PackagingSearchInit(selector) {
             $(`#itemDesc-pack`).on('input', function() {
                $(`#packageid`).val(''); 
            });
            const token = $("#token").val(); 
            $(selector).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('packag-search') }}",
                        type: "POST",
                        dataType: "json",
                          data: { 
                                "_token": token, 
                                "search": request.term, 
                                values: $('[id*="tasteless_code"], [id*="packageid"]').map(function() {
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
                                 $(`#ui-id-2-pack`).hide();
                                response($.map(data.items, item => ({
                                    label: item.item_description,
                                    value: item.item_description,
                                    ...item
                                })));
                            } else {
                                $('.ui-menu-item').remove();
                                $('.addedLi').remove();
                                const $ui = $(`#ui-id-2-pack`).html(`<i class="fa fa-exclamation fa-bounce "></i> ${data.message}`);
                                $ui.toggle($('#itemDesc-pack').val().length > 0);
                            }
                        }
                    });
                },
                minLength: 1,
                autoFocus: true,
                 select: function(event, ui) { 
                    $('#packageid').val(ui.item.tasteless_code); 
                }
            });
        }

  
        function initAutocomplete(selector, rowId) {
            const token = $("#token").val();   

             $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#tasteless_code${rowId}`).val('');
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
                    values: $('[id*="tasteless_code"], [id*="packageid"]').map(function() {
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
                    const id = $(this).data("id");
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code);
                    $(`#itemDesc${id}`).val(ui.item.item_description);
                    $(`#itemDesc${id}`).attr('data-cost', Number(ui.item.cost).toFixed(2));
                    $(`#cost${id}`).val(Number(ui.item.cost).toFixed(2)).attr('readonly', true);
                    calculateFinalValues();
                    return false;
                },
                minLength: 1,
                autoFocus: true
            }); 
        }



          function generateRowHtml(rowId, Packaging, Quantity, Cost, Item_code) {
            return `
                <tr class="tr-border slide-in-right ingredient-row" style="width: 100%; padding-top:10px;">
                <td class="packaging" style="width: 30%">
                    <div style="position: relative;">
                        <label>Ingredient</label>
                        <input type="hidden" name="ingredients[${rowId}][description]" value="${Item_code}" id="tasteless_code${rowId}">
                        <input type="text" placeholder="Search Item ..." class="form-control rounded ingredient-input" id="itemDesc${rowId}" data-id="${rowId}" value="${Packaging}" name="ingredients[${rowId}][description]" required maxlength="100">
                        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="${rowId}" id="ui-id-2${rowId}" style="display: none; top: 60px; width: 100%; color:red; padding:5px">
                            <li class="text-center">Loading...</li>
                        </ul>
                        <span class="error" id="display-error${rowId}"></span>
                    </div>
                </td>
                <td style="width: 20%">
                    <div style="position: relative;">
                        <label>Quantity</label>
                        <input type="text" class="form-control rounded ingredient-quantity" id="quantity${rowId}" name="ingredients[${rowId}][quantity]" value="${Quantity}" min="0" max="9999999999" step="any" onKeyPress="if(this.value.length==4) return false;" oninput="validity.valid;" required>
                    </div>
                </td>
                <td style="width: 20%">
                    <div style="position: relative;">
                        <label>Cost</label>
                        <input type="text" class="form-control rounded cost-input" id="cost${rowId}" name="ingredients[${rowId}][cost]" value="${Cost}" readonly style="background-color: #eee;" required>
                    </div>
                </td>
                <td style="width: 10%;">
                    <div style="position: relative;">
                        <label>Action</label><br>
                        <button id="deleteRow${rowId}" name="removeRow" data-id="${rowId}" class="btn btn-danger removeRow">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
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

        function showNoData() {
            const hasRows = $('.ingredient-table tbody tr').length;
            if (hasRows === 0) {
                $('.no-data-available').show();
                is_noingredient = true;
            } else {
                $('.no-data-available').hide();
                is_noingredient = false;
            }
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
            const rawMastProvision = 0; 

            $('[id*="quantity"]').each(function() {
                
                var id = $(this).attr('id');
                var lastChar = id.substring(id.length - 1);

                const cost = parseFloat($(`#cost${lastChar}`).val()) || 0;
                const quantity = parseFloat($(this).val()) || 0;  
                ingredientsCost += cost * quantity; 
                console.log(ingredientsCost+ ' Cossssssssssssst');
            }); 





            let valsss = parseFloat($('#raw_mast_provision').val()) || 0;  
             if(click_raw === false && valsss == '0'){
                 let ingredient_cost_plus_5percent = parseFloat(ingredientsCost) * 0.05;
                  const total = ingredientsCost + ingredient_cost_plus_5percent;
                 
                 $('#raw_mast_provision').val(total.toFixed(2));
            } 
           

            const laborCost = parseFloat($('#labor_cost').val()) || 0;
            const gasCost = parseFloat($('#gas_cost').val()) || 0;
            const totalStorageCost = parseFloat($('#total_storage_cost').val()) || 0;
            const depreciation = parseFloat($('#depreciation').val()) || 0;
            const rawMastProvisions = parseFloat($('#raw_mast_provision').val()) || 0;
            const markupPercentage = parseFloat($('#markup_percentage').val()) || 0;
            
            const totalCost = ingredientsCost + laborCost + gasCost + totalStorageCost + depreciation;
            const costWithProvision = totalCost * (1 + (rawMastProvisions / 100));
            const finalCost = costWithProvision * (1 + (markupPercentage / 100));
            
            // Round up to whole number
            const finalValueVatex = Math.ceil(finalCost);
            const finalValueVatinc = Math.ceil(finalCost * 1.12); // Assuming 12% VAT
            
            $('#final_value_vatex').val(finalValueVatex.toFixed(2));
            $('#final_value_vatinc').val(finalValueVatinc.toFixed(2));
        }


      


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
        $('#labor_cost, #gas_cost, #storage_cost, #storage_multiplier, #depreciation, #raw_mast_provision, #markup_percentage').on('input', function() {
            calculateTotalStorage();
            calculateFinalValues();
        });
       
        //check if user input raw_mast_provision if yes then dont apply + 5%
         $('#raw_mast_provision').on('input', function() {
            click_raw = true;
        });
        
        
    });
</script>
@endpush
@endsection
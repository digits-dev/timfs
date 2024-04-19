@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
    }

    th {
        width: 35%;
    }

    .photo-section {
        max-width: 400px;
        margin: 0 auto; 
    }

    .photo-section img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .swal2-html-container {
        line-height: 3rem !important;
    }


    .select2-container--default .select2-selection--single {
        border-radius: 0px !important
    }

    .select2-container .select2-selection--single {
        height: 35px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3190c7 !important;
        border-color: #367fa9 !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple{
        border-radius: 0px !important;
        width: 100% !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        padding: 0 !important;
        margin-top: -2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear{
        margin-right: 10px !important;
        padding: 0 !important;
    }

    .plus{
        font-size:20px;
    }
    #add-Row{
        border:none;
        background-color: #fff;
    }
    
    .iconPlus{
        background-color: #3c8dbc: 
    }
    
    .iconPlus:before {
        content: '';
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        /* border: 1px solid rgb(194, 193, 193); */
        font-size: 25px;
        color: white;
        background-color: #3c8dbc;

    }
    #bigplus{
        transition: transform 0.5s ease 0s;
    }
    #bigplus:before {
        content: '\FF0B';
        background-color: #3c8dbc: 
        font-size: 50px;
    }
    #bigplus:hover{
        /* cursor: default;
        transform: rotate(180deg); */
        -webkit-animation: infinite-spinning 1s ease-out 0s infinite normal;
            animation: infinite-spinning 1s ease-out 0s infinite normal;
        
    }

    @keyframes infinite-spinning {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')


<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="text-center text-bold">Asset Masterfile</h3>
    </div>
    <div class="panel-body">
        <form id="main-form" action="{{ $table == 'item_masters_fas_approvals' ? route('item_mater_fa_approvals_submit_edit') : route('item_maters_fa_submit_add_or_edit') }}" enctype="multipart/form-data" method="POST" class="form-main" autocomplete="off">
            @csrf
            <input value="{{ $item->tasteless_code }}" name="tasteless_code" type="text" class="tasteless_code hide">
            @if ($item_masters_approvals_id)
            <input type="text" name="item_masters_approvals_id" value="{{ $item_masters_approvals_id }}" class="hide">
            @endif
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            @if ($item->tasteless_code)
                            <tr>
                                <th>Tasteless Code</th>
                                <td><input value="{{ $item->tasteless_code}}" type="text" name="tasteless_code" id="tasteless_code" class="form-control" readonly></td>
                            </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Upc Code</th>
                                <td><input value="{{ $item->upc_code ?: '' }}" type="text" name="upc_code" id="upc_code" class="form-control" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input value="{{ $item->item_description ?: '' }}" type="text" name="item_description" id="item_description" class="form-control" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">{{ $item->tasteless_code && !$item->image_filename ? '*' : '' }}</span> Display Photo</th>
                                <td><input type="file" name="item_photo" id="item_photo" accept="image/*" class="form-control" max="2000000" {{ !$item->tasteless_code && !$item->image_filename ? 'required' : '' }} ></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  COA</th>
                                <td>
                                    <select name="categories_id" id="categories_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($coa as $account)
                                        <option value="{{ $account->id }}" {{ $account->id == $item->categories_id ? 'selected' : '' }}>{{ $account->description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Sub category</th>
                                <td>
                                    <select selected data-placeholder="Select Sub Category" class="form-control sub_category_id" name="subcategories_id" id="sub_category_id" required style="width:100%"> 
                                    
                                    </select>
                                </td>  
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Cost</th>
                                <td>
                                    <input value="{{ $item->cost }}" type="number" step="any" class="form-control" name="cost" id="cost" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Currency</th>
                                <td>
                                    <select name="currency_id" id="currency_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $currency->id == $item->currency_id ? 'selected' : '' }}>{{ $currency->currency_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table" id="second_div">
                        <tbody>
                            <tr>
                                <th> Supplier Item Code</th>
                                <td><input value="{{ $item->supplier_item_code ?: '' }}" type="text" name="supplier_item_code" id="supplier_item_code" class="form-control" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Brand Name</th>
                                <td>
                                    <select name="brand_id" id="brand_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($brands as $brand)
                                         <option value="{{ $brand->id }}" {{ $brand->id == $item->brand_id ? 'selected' : '' }}>{{ $brand->brand_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr class="tr-vendor">
                                <th><span class="required-star">*</span> Vendor 1 Name  <button class="red-tooltip" data-toggle="tooltip" data-placement="right" id="add-Row" name="add-Row" title="Add Vendor"><div class="iconPlus" id="bigplus"></div></button>
                                    <div id="display_error" style="text-align:left"></div></th>
                                <td><input value="{{ $item->vendor1_id ?: '' }}" type="text" name="vendor1_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            @if($item->vendor2_id)
                                <tr class="tr-vendor">
                                    <th><span class="required-star">*</span> Vendor 2 Name </th>
                                    <td><input value="{{ $item->vendor2_id ?: '' }}" type="text" name="vendor2_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()"></td>
                                </tr>
                            @endif
                            @if($item->vendor3_id)
                                <tr class="tr-vendor">
                                    <th><span class="required-star">*</span> Vendor 3 Name </th>
                                    <td><input value="{{ $item->vendor3_id ?: '' }}" type="text" name="vendor3_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()"></td>
                                </tr>
                            @endif
                            @if($item->vendor4_id)
                                <tr class="tr-vendor">
                                    <th><span class="required-star">*</span> Vendor 4 Name </th>
                                    <td><input value="{{ $item->vendor4_id ?: '' }}" type="text" name="vendor4_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()"></td>
                                </tr>
                            @endif
                            @if($item->vendor5_id)
                                <tr class="tr-vendor">
                                    <th><span class="required-star">*</span> Vendor 5 Name </th>
                                    <td><input value="{{ $item->vendor5_id ?: '' }}" type="text" name="vendor5_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()"></td>
                                </tr>
                            @endif
                            {{-- <tr class="tr-new-vendor">
                                <td style="text-align:center">
                                    <button class="red-tooltip" data-toggle="tooltip" data-placement="right" id="add-Row" name="add-Row" title="Add Vendor"><div class="iconPlus" id="bigplus"></div></button>
                                    <div id="display_error" style="text-align:left"></div>
                                </td>
                            </tr> --}}
                            <tr class="tr-new-vendor">
                                <th> Model</th>
                                <td><input value="{{ $item->model ?: '' }}" type="text" name="model" id="model" class="form-control" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Measurement</th>
                                <td><input value="{{ $item->size ?: '' }}" type="text" name="size" id="size" class="form-control" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Color</th>
                                <td><input value="{{ $item->color ?: '' }}" type="text" name="color" id="color" class="form-control" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
                
            </div>
            <button id="sumit-form-btn" class="btn btn-primary hide" type="submit">submit</button>
        </form>
        <div class="panel-footer">
            <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
            <button class="btn btn-primary pull-right _action="save" id="save-btn"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>
  
</div>

<script type="application/javascript">
    $(`#categories_id,#sub_category_id,#currency_id,#brand_id`).select2({
        width: '100%',
        height: '100%',
        placeholder: 'None selected...'
    });
    $('#sub_category_id').attr('disabled', true);
    $('#categories_id').change(function(){
        const id =  $(this).val();
        $.ajax({ 
            type: 'POST',
            url: '{{ route("fetch-categories") }}',
            data: {
                'id': id
            },
            success: function(result) {
                var i;
                var showData = [];
                showData[0] = '<option value="">Choose Sub Category</option>';
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[j] = `<option value='${result[i].id}'>${result[i].description}</option>`;
                }
                $('#sub_category_id').attr('disabled', false);
                $('#sub_category_id').html(showData); 
                $('#sub_category_id').val('').trigger('change');   

            }
        });
    });

    $('#save-btn').on('click', function() {
        if($('#item_photo').val() === ''){
            Swal.fire({
                type: 'error',
                title: 'Artworklink required!',
                icon: 'error',
                confirmButtonColor: "#367fa9",
            }); 
        }
        Swal.fire({
            title: 'Do you want to save this item?',
            html:  `Doing so will push this item to <span class="label label-info">ITEM FA FOR APPROVAL</span>.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#sumit-form-btn').click();
            }
        });
    });

    $('form input').on('keyup keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return;
        }
    });

    $('#main-form').on('submit', function() {
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

    $('#cost').on('input', function() {
        restrictDecimals($(this), 2);
    });

    function restrictDecimals(jqueryElement, number) {
        const [int, dec] = jqueryElement.val().split('.');
        number = parseInt(number);
        if (dec && dec.length > number) {
            const value = `${int}.${dec.slice(0,number)}`
            jqueryElement.val(value);
        }
    }

    $('#item_description').on('keyup', function() {
        limitText(this, 30)
    });

    function limitText(field, maxChar){
        var ref = $(field),
            val = ref.val();
        if ( val.length >= maxChar ){
            ref.val(function() {
                console.log(val.substr(0, maxChar))
                return val.substr(0, maxChar);       
            });
        }
    }
    var tableRow = 0;
    $("#add-Row").click(function() {
        event.preventDefault();
        tableRow++;
        var deleteRow = $('#countRow').val();
        var rowCount = $('#item-sourcing-options tr').length - 1 - deleteRow;
        var rowCountVendor = $('#second_div tr').length - 4;
        if(rowCountVendor > 5){
            $('#add-Row').prop("disabled", true);
            $('#display_error').html("<span id='notif' class='label label-danger'> More than 5 Vendors not allowed!</span>")
        }else{
            $('#add-Row').prop("disabled", false);
            $('#display_error').html("");
            var newrow =
            `<tr class="tr-vendor">
                <th><span class="required-star">*</span> <span class="vendor-name"> Vendor ${rowCountVendor} Name </span></th>
                <td>
                    <div style="display:flex;align-content: flex-center;">
                        <input value="{{ $item->vendor_id ?: '' }}" type="text" name="vendor${rowCountVendor}_id" id="vendor_id" class="form-control vendor" required oninput="this.value = this.value.toUpperCase()">
                        <button id="deleteRow" name="removeRow" class="btn btn-danger removeRow" style="margin-left:5px"><i class="glyphicon glyphicon-trash"></i></button> 
                    </div>
                </td>
            </tr>`;
            $('#second_div tbody tr.tr-new-vendor').before(newrow);
        }
    });

    $(document).on('click', '.removeRow', function() {
        if ($('#second_div tbody tr').length != 1) { 
            tableRow--;
            $(this).closest('tr').remove();
            resetVendorSequence();
            var rowCount = $('#second_div tbody tr').length-5;
            if(rowCount > 5){
                $('#add-Row').prop("disabled", true)
            }else{
                $('#add-Row').prop("disabled", false)
                $('#display_error').html("");
            }
            return false;
        }
    });

    function resetVendorSequence(){
        const trVendor = $('.tr-vendor').get();
        const inputVendor = $('.vendor').get();
        trVendor.forEach((tr,key) => {
            $(tr).find('th').find('span.vendor-name').text(`Vendor ${key + 1} Name`);
        });

        inputVendor.forEach((input,key) => {
            $(input).attr('name', `vendor${key + 1}_id`);
        });
    }
</script>
@endsection
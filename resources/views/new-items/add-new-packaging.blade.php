@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
    }

    .comment-textarea {
        width: 100%;
        min-height: 250px;
        resize: none;
        padding: 12px;
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
    th {
        width: 35%;
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
        <i class="fa fa-pencil"></i><strong> Add {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <form method="POST" action="{{CRUDBooster::mainPath('add-save')}}" name="form-main" id="form-main" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input type="text" name="item_description" class="form-control" required placeholder="Item Description" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            {{-- <tr>
                                <th><span class="required-star">*</span> Item Type</th>
                                <td>
                                    <select name="new_item_types_id" id="new_item_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($new_item_types as $new_item_type)
                                        <option value="{{$new_item_type->id}}">{{$new_item_type->item_type_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr> --}}
                            <tr>
                                <th><span class="required-star">*</span>  Packaging Size</th>
                                <td><input type="number" step="any" name="packaging_size" class="form-control" required placeholder="Packaging Size"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  UOM</th>
                                <td>
                                    <select name="uoms_id" id="uoms_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($uoms as $uom)
                                        <option value="{{$uom->id}}">{{$uom->uom_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> SRP</th>
                                <td><input type="number" step="any" name="ttp" class="form-control" required placeholder="SRP" min="0"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Target Date</th>
                                <td><input type="date" step="any" name="target_date" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sourcing Category</th>
                                <td>
                                    <select name="packaging_types_id" id="packaging_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_types as $packaging_type)
                                        <option value="{{$packaging_type->id}}" description="{{$packaging_type->description}}">{{$packaging_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="stickerTypeRow" hidden>
                                <th><span class="required-star">*</span> Sticker Material</th>
                                <td>
                                    <select name="sticker_types_id" id="sticker_types_id" class="form-control" >
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_stickers as $packaging_sticker)
                                        <option value="{{$packaging_sticker->id}}" >{{$packaging_sticker->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6"> 
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Sourcing Usage</th>
                                <td>
                                    <select name="packaging_uses_id" id="packaging_uses_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_uses as $packaging_use)
                                        <option value="{{$packaging_use->id}}" description="{{$packaging_use->description}}">{{$packaging_use->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="beverageTypeRow" hidden>
                                <th><span class="required-star">*</span>  Beverage Type</th>
                                <td>
                                    <select name="packaging_beverage_types_id" id="packaging_beverage_types_id" class="form-control" >
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_beverage_types as $packaging_beverage_type)
                                        <option value="{{$packaging_beverage_type->id}}" >{{$packaging_beverage_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Material Type</th>
                                <td>
                                    <select name="packaging_material_types_id" id="packaging_material_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_material_types as $packaging_material_type)
                                        <option value="{{$packaging_material_type->id}}" description="{{$packaging_material_type->description}}">{{$packaging_material_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="paperTypeRow" hidden>
                                <th><span class="required-star">*</span> Paper Type</th>
                                <td>
                                    <select name="packaging_paper_types_id" id="packaging_paper_types_id" class="form-control" >
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_paper_types as $packaging_paper_type)
                                        <option value="{{$packaging_paper_type->id}}" >{{$packaging_paper_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Design Type</th>
                                <td>
                                    <select name="packaging_design_types_id" id="packaging_design_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_designs as $packaging_design)
                                        <option value="{{$packaging_design->id}}" description="{{$packaging_design->description}}">{{$packaging_design->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>File 1</th>
                                <td><input type="file" name="filename_1" id="filename_1" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>File 2</th>
                                <td><input type="file" name="filename_2" id="filename_2" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>File 3</th>
                                <td><input type="file" name="filename_3" id="filename_3" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>File 4</th>
                                <td><input type="file" name="filename_4" id="filename_4" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>File 5</th>
                                <td><input type="file" name="filename_5" id="filename_5" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Size</th>
                                <td><input type="number" step="any" name="size" class="form-control" required placeholder="SRP" min="0"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Budget Range</th>
                                <td><input type="text" name="budget_range" class="form-control" required placeholder="Budget Range"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Initial Qty Needed</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" step="any" name="initial_qty_needed" class="form-control" required placeholder="Initial Qty Needed" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="initial_qty_uoms_id" id="initial_qty_uoms_id" class="form-control" required >
                                                <option value="" disabled selected>None selected...</option>
                                                @foreach ($new_ingredient_uoms as $new_ingredient_uom)
                                                    <option value="{{$new_ingredient_uom->id}}" selected>{{$new_ingredient_uom->uom_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Forecast Qty Needed Per Month</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" step="any" name="forecast_qty_needed" class="form-control" required placeholder="Forecast Qty Needed" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="forecast_qty_uoms_id" id="forecast_qty_uoms_id" class="form-control" required >
                                                <option value="" disabled >None selected...</option>
                                                @foreach ($new_ingredient_uoms as $new_ingredient_uom)
                                                    <option value="{{$new_ingredient_uom->id}}" selected>{{$new_ingredient_uom->uom_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h3 class="text-center"><span class="required-star">*</span> COMMENTS</h3>
                    <textarea class="comment-textarea" name="comment" id="comment" form="form-main" required recommend placeholder="Type your comment here..."></textarea>
                </div>
            </div>
            <button type="submit" class="hide" id="submit-btn">Submit</button>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
    </div>
</div>

<script>
    $('#new_ingredients_segmentation').select2({
        width:'100%',
    });
    
    $('#packaging_types_id').change(function () {
        const selectedValue = $(this).val();
        const selectedOption = $(this).find(`option[value="${selectedValue}"]`).attr('description');
        console.log(selectedOption);
        if (selectedOption === 'STICKER LABEL') {
            $('#stickerTypeRow').show();
            $('#sticker_types_id').attr('required', true);
        } else {
            $('#stickerTypeRow').hide();
            $('#sticker_types_id').attr('required', false);
        }
    });
    $('#packaging_types_id').change(function(){
        $('#sticker_types_id').val('');
    });

    $('#packaging_uses_id').change(function () {
        const selectedValue = $(this).val();
        const selectedOption = $(this).find(`option[value="${selectedValue}"]`).attr('description');
        console.log(selectedOption);
        if (selectedOption === 'BEVERAGE') {
            $('#beverageTypeRow').show();
            $('#packaging_beverage_types_id').attr('required', true);
        } else {
            $('#beverageTypeRow').hide();
            $('#packaging_beverage_types_id').attr('required', false);
        }
    });
    $('#packaging_uses_id').change(function(){
        $('#packaging_beverage_types_id').val('');
    });

    $('#packaging_material_types_id').change(function () {
        const selectedValue = $(this).val();
        const selectedOption = $(this).find(`option[value="${selectedValue}"]`).attr('description');
        console.log(selectedOption);
        if (selectedOption === 'PAPER') {
            $('#paperTypeRow').show();
            $('#packaging_paper_types_id').attr('required', true);
        } else {
            $('#paperTypeRow').hide();
            $('#packaging_paper_types_id').attr('required', false);
        }
    });
    $('#packaging_material_types_id').change(function(){
        $('#packaging_paper_types_id').val('');
    });

    
    $('#save-btn').click(function() {
        Swal.fire({
            title: 'Do you want to save this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                
                $('#submit-btn').click();
            }
        });
    });
</script>


@endsection
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
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
        <i class="fa fa-pencil"></i><strong> Edit {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <div class="row">
            <h3 class="text-center">ITEM DETAILS</h3>
        </div>
        <form method="POST" action="{{ $table == 'new_ingredients' ? route('submit_edit_new_ingredient') : route('submit_edit_new_packaging')}}" id="form-main" autocomplete="off">
            @csrf
            <input type="text" name="new_items_id" class="hide" value="{{ $item->new_ingredients_id ?? $item->new_packagings_id }}">
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th>NWP Code</th>
                                <th>{{ $item->nwp_code}}</th>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input type="text" value="{{ $item->item_description }}" name="item_description" class="form-control" required placeholder="Item Description" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            {{-- <tr>
                                <th><span class="required-star">*</span>  Item Type</th>
                                <td>
                                    <select name="new_item_types_id" id="new_item_types_id" class="form-control" required>
                                        @foreach ($new_item_types as $new_item_type)
                                        <option value="{{$new_item_type->id}}" {{ $item->new_item_types_id == $new_item_type->id ? 'selected' : '' }}>{{$new_item_type->item_type_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr> --}}
                            <tr>
                                <th><span class="required-star">*</span>  Packaging Size</th>
                                <td><input type="number" value="{{ $item ? (float) $item->packaging_size : '' }}" step="any" name="packaging_size" class="form-control" required placeholder="Packaging Size"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  UOM</th>
                                <td>
                                    <select name="uoms_id" id="uoms_id" class="form-control" required>
                                        @foreach ($uoms as $uom)
                                        <option value="{{$uom->id}}" {{ $item->uoms_id == $uom->id ? 'selected' : '' }}>{{$uom->uom_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  SRP</th>
                                <td><input type="number" value="{{ $item ? (float) $item->ttp : '' }}" step="any" name="ttp" class="form-control" required placeholder="SRP" min="0"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Target Date</th>
                                <td><input type="date" value="{{ $item->target_date ? $item->target_date : '' }}" step="any" name="target_date" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sourcing Category</th>
                                <td>
                                    <select name="packaging_types_id" id="packaging_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_types as $packaging_type)
                                        <option value="{{$packaging_type->id}}" description="{{$packaging_type->description}}" {{ $item->packaging_types_id == $packaging_type->id ? 'selected' : '' }}>{{$packaging_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="stickerTypeRow" {{ $item->packaging_description  != 'STICKER LABEL' ? 'hidden' : '' }}>
                                <th><span class="required-star">*</span> Sticker Material</th>
                                <td>
                                    <select name="sticker_types_id" id="sticker_types_id" class="form-control" >
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_stickers as $packaging_sticker)
                                        <option value="{{$packaging_sticker->id}}" {{ $item->sticker_types_id == $packaging_sticker->id ? 'selected' : '' }}>{{$packaging_sticker->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Sourcing Usage</th>
                                <td>
                                    <select name="packaging_uses_id" id="packaging_uses_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_uses as $packaging_use)
                                        <option value="{{$packaging_use->id}}" description="{{$packaging_use->description}}" {{ $item->packaging_uses_id == $packaging_use->id ? 'selected' : '' }}>{{$packaging_use->description}}</option>
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
                                        <option value="{{$packaging_material_type->id}}" description="{{$packaging_material_type->description}}" {{ $item->packaging_material_types_id == $packaging_material_type->id ? 'selected' : '' }}>{{$packaging_material_type->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="paperTypeRow" {{ $item->packaging_material  != 'PAPER' ? 'hidden' : '' }}>
                                <th><span class="required-star">*</span> Paper Type</th>
                                <td>
                                    <select name="packaging_paper_types_id" id="packaging_paper_types_id" class="form-control" >
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packaging_paper_types as $packaging_paper_type)
                                        <option value="{{$packaging_paper_type->id}}" {{ $item->packaging_paper_types_id == $packaging_paper_type->id ? 'selected' : '' }}>{{$packaging_paper_type->description}}</option>
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
                                        <option value="{{$packaging_design->id}}" description="{{$packaging_design->description}}" {{ $item->packaging_design_types_id == $packaging_design->id ? 'selected' : '' }}>{{$packaging_design->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Size</th>
                                <td><input type="number" value="{{ (float) $item->size }}" step="any" name="size" class="form-control" required placeholder="size" min="0"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  SRP</th>
                                <td><input type="number" value="{{ (float) $item->ttp }}" step="any" name="ttp" class="form-control" required placeholder="SRP" min="0"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Budget Range</th>
                                <td><input type="text" value="{{  $item->budget_range }}" name="budget_range" class="form-control" required placeholder="Budget Range"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reference Links</th>
                                <td><input type="text" name="reference_link" value="{{ $item->reference_link }}" class="form-control" required placeholder="Reference Links" ></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Initial Qty Needed</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" value="{{ (float) $item->initial_qty_needed }}" step="any" name="initial_qty_needed" class="form-control" required placeholder="Initial Qty Needed" min="0">
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
                                            <input type="number" step="any" value="{{ (float) $item->forecast_qty_needed }}" name="forecast_qty_needed" class="form-control" required placeholder="Forecast Qty Needed" min="0">
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
            <button type="submit" class="hide" id="submit-btn">Submit</button>
        </form>
        <div class="row">
            <div class="col-md-6">
                <hr>
                <h3 class="text-center">COMMENTS</h3>
                <div class="chat-app">
                    @include('new-items/chat-app', $comments_data)
                </div>
            </div>
            <div class="col-md-6">
                <hr>
                <h3 class="text-center">ITEM USAGE</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Description</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$item_usages)
                            <tr><td class="text-center" style="font-style: italic; color: grey" colspan="3">This item is currently not in use...</td></tr>
                            @endif
                            @foreach ($item_usages as $item_usage)
                            <tr>
                                <td>{{ $item_usage->item_code }}</td>
                                <td>{{ $item_usage->item_description }}</td>
                                <td>{{ $item_usage->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
    </div>
</div>




@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        function showSwal() {
            Swal.fire({
                title: 'Do you want to save the changes?',
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
        }

        $('#save-btn').click(showSwal);

        $('input').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                showSwal();
            }
        });
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
    
</script>


@endpush
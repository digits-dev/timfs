@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
{{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
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
    td {
        width: 65%;
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
        <form method="POST" action="{{CRUDBooster::mainPath('add-save')}}" name="form-main" id="form-main" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="text" name="others" id="others" hidden>
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
                                <th><span class="required-star">*</span>  SRP</th>
                                <td><input type="number" step="any" name="ttp" class="form-control" required placeholder="SRP"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Display Photo</th>
                                <td><input type="file" name="display_photo" class="form-control" accept="image/*"  required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Target Date</th>
                                <td><input type="date" step="any" name="target_date" class="form-control" required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation</th>
                                <td>
                                    <select name="segmentations[]" class="segmentation_select" id="new_ingredients_segmentation" class="form-control" multiple="multiple" >
                                        @foreach ($segmentations as $segmentation)
                                        <option  class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reason</th>
                                <td>
                                    <select name="new_ingredient_reasons_id" id="new_ingredient_reasons_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($new_ingredient_reasons as $new_ingredient_reason)
                                        <option value="{{$new_ingredient_reason->id}}" description="{{$new_ingredient_reason->description}}">{{$new_ingredient_reason->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="existingIngredientRow" hidden>
                                <th><span class="required-star">*</span> Existing Ingredient</th>
                                <td>
                                    <select name="existing_ingredient" id="existing_ingredient" class="form-control" >
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
                                <th><span class="required-star">*</span> Recommended Brand 1</th>
                                <td><input type="text" name="recommended_brand_one" class="form-control" required placeholder="Required" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Recommended Brand 2</th>
                                <td><input type="text" name="recommended_brand_two" class="form-control"  required placeholder="Required" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th>Recommended Brand 3</th>
                                <td><input type="text" name="recommended_brand_three" class="form-control"  placeholder="Optional" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Initial Qty Needed</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" step="any" name="initial_qty_needed" class="form-control" required placeholder="Initial Qty Needed" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="initial_qty_uoms_id" id="initial_qty_uoms_id" class="form-control" required>
                                                <option value="" disabled selected>None selected...</option>
                                                @foreach ($new_ingredient_uoms as $new_ingredient_uom)
                                                    <option value="{{$new_ingredient_uom->id}}">{{$new_ingredient_uom->uom_code}}</option>
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
                                            <select name="forecast_qty_uoms_id" id="forecast_qty_uoms_id" class="form-control" required>
                                                <option value="" disabled selected>None selected...</option>
                                                @foreach ($new_ingredient_uoms as $new_ingredient_uom)
                                                    <option value="{{$new_ingredient_uom->id}}">{{$new_ingredient_uom->uom_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Budget Range</th>
                                <td><input type="text" name="budget_range" class="form-control" oninput="this.value = this.value.toUpperCase()" required placeholder="Budget Range"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reference Links</th>
                                <td><input type="text" name="reference_link" class="form-control" required placeholder="Reference Links" ></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Term</th>
                                <td>
                                    <select name="new_ingredient_terms_id" id="new_ingredient_terms_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($new_ingredient_terms as $new_ingredient_term)
                                        <option value="{{$new_ingredient_term->id}}">{{$new_ingredient_term->description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Duration</th>
                                <td><input type="text" name="duration" class="form-control" oninput="this.value = this.value.toUpperCase()" required placeholder="Duration" min="0"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h3 class="text-center"><span class="required-star">*</span> COMMENTS</h3>
                    <textarea class="comment-textarea" name="comment" id="comment" form="form-main" required placeholder="Type your comment here..."></textarea>
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
    function jsonifyOthers() {
        const selects = $('.other-input').get();
        const obj = {};
        selects.forEach(select => {
            const id = $(select).attr('for');
            const value = $(select).val();
            obj[id] = value;
        });
        $('#others').val(JSON.stringify(obj));
    }

    $('#new_ingredients_segmentation').select2({
        width:'100%',
    });
    
    $('#new_ingredient_reasons_id').change(function () {
        const selectedValue = $(this).val();
        const selectedOption = $(this).find(`option[value="${selectedValue}"]`).attr('description');
        
        console.log(selectedOption);
        if (selectedOption === 'REPLACEMENT OF INGREDIENT') {
            $('#existingIngredientRow').show();
            $('#existing_ingredient').attr('required', true);
        } else {
            $('#existingIngredientRow').hide();
            $('#existing_ingredient').attr('required', false);

        }
    });

    $('#new_ingredient_reasons_id').change(function(){
        $('#existing_ingredient').val('');
    });
    
    $('#save-btn').click(function() {
        jsonifyOthers();
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

        // $("#existing_ingredient").autocomplete({
        //     source: function (request, response) {
        //         $.ajax({
        //             url: "{{ route('suggest_existing_ingredients') }}",
        //             dataType: 'json',
        //             data: {
        //                 term: request.term
        //             },
        //             success: function (data) {
        //                 response(data);
        //             }
        //         });
        //     },
        //     minLength: 2,
        //     delay: 750 // adjust as needed
        // });

    $('#existing_ingredient').select2({
        ajax: {
            url: "{{ route('suggest_existing_ingredients') }}",
            dataType: 'json',
            processResults: function(data) {
                // Transform the response into the format Select2 expects
                return {
                    results: data
                };
            },
            cache: true
        },
        width:'200px',
    });

    $('select').on('change', function() {
        const value = $(this).find('option:selected').text();
        const td = $(this).parents('td');
        const name = $(this).attr('name');
        if (value === 'OTHER' || value === 'OTHERS') {
            const input = $('<input>')
                .addClass('form-control')
                .addClass('other-input')
                .attr('data-select', 'others')
                .attr('placeholder', 'Please specify...')
                .attr('for', name)
                .attr('oninput', "this.value = this.value.toUpperCase()")
                .attr('required', true)
                .css('margin-top', '3px');
            td.append(input);
        } else {
            td.find('input[data-select="others"]').remove();
        }
    });

    $('#form-main').on('submit', function() {
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

</script>


@endsection

@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/production-item/custom-item.css')}}">
<style>
    input::placeholder{
        font-style: italic;
    }
    select {
        border-radius: 0px 5px 5px 0px !important; 
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
    .ingredient-label{
        position: absolute;
        background: #fff;
        top: -14px;
        left: 45px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
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
        border-radius: 10px; /* Smooth rounded corners */
        margin-bottom: 15px; /* Space between rows */
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1); /* Optional subtle shadow */
        display: flex;
        align-items: center;
        justify-content: center
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

        <div class="panel-body">
            <div class="row" style="margin-top:20px">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-file"></i></span>
                            <label class="description float-label">Description</label>
                            <input type="text" class="form-control rounded" placeholder="description" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>
                            <label class="production_category float-label">Production Category</label>
                            <select class="form-control select" id="production_category" name="production_category" required>
                                <option value="">Select Category</option>
                                @foreach($productionCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('production_category') == $category->id ? 'selected' : '' }}>
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
                                @foreach($productionLocations as $location)
                                    <option value="{{ $location->id }}" {{ old('production_location') == $location->id ? 'selected' : '' }}>
                                        {{ $location->production_location_description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 15px; margin-bottom: 5px">
                <div class="col-md-12">
                    <label class="ingredient-label">Ingredients</label>
                    <div class="ingredient-box" style="margin-bottom: 5px">
                        <table class="ingredient-table w-100" style="width: 100%;">
                            <tbody id="ingredient-tbody">
                                <!-- Rows injected by JS -->
                            </tbody>
                        </table>
                        <div class="no-data-available text-center py-2" style="display: none;">
                            <i class="fa fa-table"></i> <span>No ingredients currently save</span>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="add-Row"><i class="fa fa-plus"></i> Add New Ingredients</button>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="labor_cost float-label">Labor Cost</label>
                            <input type="text" class="form-control rounded" placeholder="Labor cost" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="gas_cost float-label">Gas Cost</label>
                            <input type="text" class="form-control rounded" placeholder="Gas cost" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="storage_cost float-label">Storage Cost</label>
                            <input type="text" class="form-control rounded" placeholder="Storage cost" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="storage_multiplier float-label">Storage Multiplier</label>
                            <input type="text" class="form-control rounded" placeholder="Storage multiplier" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="total_storage_cost float-label">Total Storage Cost</label>
                            <input type="text" class="form-control rounded" placeholder="Total storage cost" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="storage_location float-label">Storage Location</label>
                            <input type="text" class="form-control rounded" placeholder="Storage location" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="depreciation float-label">Depreciation</label>
                            <input type="text" class="form-control rounded" placeholder="Depreciation" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="raw_mass_provision float-label">Raw Mass Provision</label>
                            <input type="text" class="form-control rounded" placeholder="Raw mass provision" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="mark_up_percentage float-label">Mark Up Percentage</label>
                            <input type="text" class="form-control rounded" placeholder="Mark up percentage" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="final_value_vatex float-label">Final Value(VATEX)</label>
                            <input type="text" class="form-control rounded" placeholder="Final value vatex" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" id="basic-addon1" style="font-size: 20px"> ₱ </span>
                            <label class="final_value_vatinc float-label">Final Value(VATINC)</label>
                            <input type="text" class="form-control rounded" placeholder="Fina value vatinc" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" class="btn btn-success">+ Save data</button>
            <a href="#" class="btn btn-link">← Back</a>
        </div>
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
     
    $(document).ready(function() {
        showNoData();
        $('body').addClass('sidebar-collapse');
        $(`.select`).select2({
            width: '100%',
            height: '100%',
            placeholder: 'None selected...'
        });
        var tableRow = 1;
        $("#add-Row").click(function () {
            // if (!validateFields()) return;
            tableRow++;

            const newRowHtml = generateRowHtml(tableRow);
             $(newRowHtml).appendTo('#ingredient-tbody');
            showNoData();
        });

        function generateRowHtml(rowId) {
            return `
                 <tr class="tr-border slide-in-right" style="width: 100%; padding-top:5px">
                    <td style="width: 30%; padding: 8px;">
                        <div style="position: relative;">
                            <label>Packaging</label>
                            <input type="text" placeholder="Search Item ..." class="form-control rounded itemDesc" id="itemDesc${rowId}" data-id="${rowId}" name="item_description[]" required maxlength="100">
                            <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" data-id="${rowId}" id="ui-id-2${rowId}" style="display: none;"></ul>
                            <div id="display-error${rowId}"></div>
                        </div>
                    </td>
                    <td style="width: 20%; padding: 8px;">
                        <div style="position: relative;">
                            <label>Quantity</label>
                            <input type="text" class="form-control rounded quantity-input" id="quantity${rowId}" name="quantity[]" readonly style="background-color: #eee;">
                        </div>
                    </td>
                    <td style="width: 20%; padding: 8px;">
                        <div style="position: relative;">
                            <label>Cost</label>
                            <input type="text" class="form-control rounded cost-input" id="cost${rowId}" name="cost[]" readonly style="background-color: #eee;">
                        </div>
                    </td>
                    <td style="width: 10%;">
                        <button id="deleteRow${rowId}" name="removeRow" data-id="${rowId}" class="btn btn-danger removeRow" style="margin-top: 20px;">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
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
            } else {
                $('.no-data-available').hide();
            }
        }

        $(document).on("click", ".removeRow", function (e) {
            const $row = $(this).closest('.tr-border');
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "This row will be removed.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $row.addClass('slide-out-right');

                    // Remove row after animation ends
                    $row.on('animationend', function () {
                        $row.remove();
                        showNoData(); // Update no data message
                    });
                }
            });
        });
     
    });
</script>
@endpush
@endsection
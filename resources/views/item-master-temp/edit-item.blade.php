@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-html-container {
        line-height: 3rem;
    }

    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }
    .select2-container .select2-selection--single {
        height: 34px;
    }

    .select2-selection__choice,
    .select2-selection__choice__remove {
        background-color: #3190c7 !important;
        border-color: #367fa9 !important;
        color: #fff !important;
    }

    .choices {
        display: flex;
        flex-direction: column;
    }

    .segmentation-section {
        max-width: 800px;
        margin: auto;
    }

    .radio-label {
        font-weight: normal;
    }

    
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data Item Master (Code Creation)
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> Create New Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="#form">
            <div class="row">
                <h3 class="text-center">ITEM DETAILS</h3>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">* Item Description</label>
                        <input type="text" id="item-description" class="form-control" placeholder="Item Description" value="{{$item->item_description}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">* Brand Description</label>
                        <select id="brand-description" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Tax Code</label>
                        <select name="" id="tax-code" class="form-control">
                            <option value=""></option>
                            @foreach($tax_codes as $tax_code)
                            <option value="{{$tax_code->id}}">{{$tax_code->tax_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Account</label>
                        <select name="" id="account" class="form-control">
                            <option value=""></option>
                            @foreach($accounts as $account)
                            <option value="{{$account->id}}">{{$account->group_short_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* COGS Account</label>
                        <select name="" id="cogs-account" class="form-control">
                            <option value=""></option>
                            @foreach($cogs_accounts as $cogs_account)
                            <option value="{{$cogs_account->id}}">{{$cogs_account->group_short_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Asset Account</label>
                        <select name="" id="asset-account" class="form-control">
                            <option value=""></option>
                            @foreach($asset_accounts as $asset_account)
                            <option value="{{$asset_account->id}}">{{$asset_account->group_short_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Fulfillment Type</label>
                        <select name="" id="fulfillment-type" class="form-control">
                            <option value=""></option>
                            @foreach($fulfillment_types as $fulfillment_type)
                            <option value="{{$fulfillment_type->id}}">{{$fulfillment_type->fulfillment_method}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* U/M</label>
                        <select name="" id="uom" class="form-control">
                            <option value=""></option>
                            @foreach($uoms as $uom)
                            <option value="{{$uom->id}}">{{$uom->uom_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* U/M Set</label>
                        <select name="" id="uom-set" class="form-control">
                            <option value=""></option>
                            @foreach($uoms_set as $uom_set)
                            <option value="{{$uom_set->id}}">{{$uom_set->uom_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Currency</label>
                        <select name="" id="currency" class="form-control">
                            @foreach($currencies as $currency)
                            <option value=""></option>
                            <option value="{{$currency->id}}">{{$currency->currency_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Supplier Cost</label>
                        <input type="number" value="{{$item->ttp}}" id="supplier-cost" class="form-control" placeholder="Supplier Cost">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Landed Cost</label>
                        <input type="number" id="landed-cost" class="form-control" placeholder="Landed Cost">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">* Preferred Vendor</label>
                        <select name="" id="preferred-vendor" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Reorder Pt (Min)</label>
                        <input type="number" id="reorder-pt-min" class="form-control" placeholder="Reorder Pt (Min)">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Group</label>
                        <select name="" id="group" class="form-control">
                            <option value=""></option>
                            @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->group_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Category Description</label>
                        <select name="" id="category-description" class="form-control">
                            <option value=""></option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Subcategory Description</label>
                        <select name="" id="subcategory-description" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dimension</label>
                        <input type="text" id="dimension" class="form-control" placeholder="Dimension">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* Packaging Size</label>
                        <input type="number" id="packaging-size" class="form-control" placeholder="Packaging Size"value="{{(float) $item->packaging_size}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Supplier Item Code</label>
                        <input type="text" id="supplier-item-code" class="form-control" placeholder="Supplier Item Code">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">* MOQ Store</label>
                        <input type="number" id="moq-store" class="form-control" placeholder="MOQ Store">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="text-center">STORE SEGMENTATIONS</h3>
                    @foreach($segmentations as $segmentation)
                    <div class="col-sm-3">
                        <h4 class="text-bold">* {{$segmentation->segment_column_description}}</h4>
                        <div class="choices per-store" column-name="{{$segmentation->segment_column_name}}">
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="CORE"> CORE</label>
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="DEPLETION"> DEPLETION</label>
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="NON CORE"> NON CORE</label>
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="PERISHABLE"> PERISHABLE</label>
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="ALTERNATIVE"> ALTERNATIVE</label>
                            <label class="radio-label"><input type="radio" name="{{$segmentation->segment_column_name}}" value="X"> X</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
        <button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
    </div>
</div>

<script type="text/javascript">
    const tempItem = {!! json_encode($item) !!};
    function loadSelect2() {
        $("#brand-description").select2({
            ajax: {
                url: "{{ route('search_imfs_data') }}",
                dataType: 'json',
                delay: 250,
                type: "POST",
                data: function (term) {
                    return {
                        term: term,
                        to_search: 'brands',
                        _token: "{{ csrf_token() }}",
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.brand_description,
                                id: item.id
                            }
                        })
                    };
                },
            },
            placeholder: 'None Selected',
            width: '100%',
        });
        $('#tax-code').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#account').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#cogs-account').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#asset-account').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#fulfillment-type').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#uom').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#uom-set').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#currency').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#preferred-vendor').select2({
            ajax: {
                url: "{{ route('search_imfs_data') }}",
                dataType: 'json',
                delay: 250,
                type: "POST",
                data: function (term) {
                    return {
                        term: term,
                        to_search: 'preferred_vendors',
                        _token: "{{ csrf_token() }}",
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.last_name,
                                id: item.id
                            }
                        })
                    };
                },
            },
            placeholder: 'None Selected',
            width: '100%',
        });
        $('#group').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        });
        $('#category-description').select2({
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            width: '100%',
        }).on('change', function() {
            const id = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('search_imfs_data') }}",
                data: {
                    id: id,
                    to_search: 'subcategories',
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('#subcategory-description').html('');
                    const subcategoriesResult = JSON.parse(response);
                    subcategories = subcategoriesResult.map(e => ({id: e.id, text: e.subcategory_description}));
                    subcategoriesResult.map(e => {
                        $('#subcategory-description').append(`<option value="${e.id}">${e.subcategory_description}</option>`);
                    });
                },
                error: function(response) { 
                    console.log(response); 
                }  
            })
        });
        $('#subcategory-description').select2({
            placeholder: 'None Selected',
            width: '100%',
        });
    }

    function checkFormValidity(itemDetails, segmentation) {
        for (const itemDetail in itemDetails) {
            if (itemDetail == 'packaging_dimension' || itemDetail == 'supplier_item_code') {
                continue;
            }
            if (!itemDetails[itemDetail]) {
                return [false, itemDetail];
            }
        }

        for (const segmentationKey in segmentation) {
            if (!segmentation[segmentationKey]) {
                return [false, segmentationKey];
            }
        }

        return [true, true]
    }

    function submitForm() {
        const itemDescription = $('#item-description').val() || null;
        const brandDescription = $('#brand-description').val() || null;
        const taxCode = $('#tax-code').val() || null;
        const account = $('#account').val() || null;
        const cogsAcccount = $('#cogs-account').val() || null;
        const assetAccount = $('#asset-account').val() || null;
        const fulfillmentType = $('#fulfillment-type').val() || null;
        const uom = $('#uom').val() || null;
        const uomSet = $('#uom-set').val() || null;
        const currency = $('#currency').val() || null;
        const supplierCost = $('#supplier-cost').val() || null;
        const landedCost = $('#landed-cost').val() || null;
        const preferredVendor = $('#preferred-vendor').val() || null;
        const reorderPt = $('#reorder-pt-min').val() || null;
        const group = $('#group').val() || null;
        const categoryDescription = $('#category-description').val() || null;
        const subcategoryDescription = $('#subcategory-description').val() || null;
        const dimension = $('#dimension').val() || null;
        const packagingSize = $('#packaging-size').val() || null;
        const supplierItemCode = $('#supplier-item-code').val() || null;
        const moqStore = $('#moq-store').val() || null;
        const stores = $('.per-store').get();
        const segmentation = {};

        stores.forEach(store => {
            const columnName = $(store).attr('column-name');
            const selected = $(store).find(`input[name="${columnName}"]:checked`);
            const selectedValue = selected.val();
            segmentation[columnName] = selectedValue;
        });

        const itemDetails = {
            full_item_description: itemDescription,
            purchase_description: itemDescription,
            brands_id: brandDescription,
            tax_codes_id: taxCode,
            tax_status: taxCode,
            accounts_id: account,
            cogs_accounts_id: cogsAcccount,
            asset_accounts_id: assetAccount,
            fulfillment_type_id: fulfillmentType,
            uoms_id: uom,
            uoms_set_id: uomSet,
            packagings_id: uomSet,
            currencies_id: currency,
            purchase_price: supplierCost,
            ttp: supplierCost,
            landed_cost: landedCost,
            suppliers_id: preferredVendor,
            reorder_pt: reorderPt,
            groups_id: group,
            categories_id: categoryDescription,
            subcategories_id: subcategoryDescription,
            packaging_dimension: dimension,
            packaging_size: packagingSize,
            supplier_item_code: supplierItemCode,
            moq_store: moqStore,
        }

        const [isValid, field] = checkFormValidity(itemDetails, segmentation);
        if (!isValid) {
            console.log(field);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill out all required fields!',
            });
            return;
        }

        const form = $(document.createElement('form'))
            .attr('action', "{{ route('save_new_item') }}")
            .attr('method', 'POST')
            .hide();

        const csrf = $(document.createElement('input'))
            .attr('name', '_token')
            .val("{{ csrf_token() }}")

        const itemData = $(document.createElement('input'))
            .attr('name', 'item_data')
            .val(JSON.stringify(itemDetails))

        const segmentationData = $(document.createElement('input'))
            .attr('name', 'segmentation')
            .val(JSON.stringify(segmentation));

        const itempItemId =  $(document.createElement('input'))
            .attr('name', 'item_masters_temp_id')
            .val(tempItem.id);
        
        form.append(
            csrf,
            itemData,
            segmentationData,
            itempItemId,
        );

        $('.panel-body').append(form);
        form.submit();
    }
    
    $('#save-btn').on('click', function() {
        Swal.fire({
            title: 'Do you want to save this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save'
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm();
            }
        });
    });

    loadSelect2();

</script>
@endsection

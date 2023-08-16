@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>

    .edited-field {
        color: red;
        outline: 2px solid red;
    }
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
        <h3 class="text-center text-bold">Item Masterfile</h3>
    </div>
    <div class="panel-body">
        <form action="{{ route('item_maters_approve_or_reject') }}" method="POST" class="form-main" autocomplete="off">
            <input type="text" class="hide" id="action-selected" name="action">
            <input type="text" class="hide" value="{{ $item->id }}" id="item_master_approvals_id" name="item_master_approvals_id">
            <h3 class="text-center text-bold">ITEM DETAILS</h3>
            @csrf
            <input value="{{ $item->tasteless_code }}" name="tasteless_code" type="text" class="tasteless_code hide" readonly>
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
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input value="{{ $item->full_item_description ?: '' }}" title="{{ $item->full_item_description }}" type="text" name="full_item_description" id="full_item_description" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Brand Description</th>
                                <td>
                                    <select value="{{ $item->brands_id }}" name="brands_id" id="brands_id" class="form-control" required disabled>
                                        <option value="" selected>{{ $brand }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Tax Code</th>
                                <td>
                                    <select name="tax_codes_id" id="tax_codes_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($tax_codes as $tax_code)
                                        <option value="{{ $tax_code->id }}" {{ $tax_code->id == $item->tax_codes_id ? 'selected' : '' }}>{{ $tax_code->tax_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Account</th>
                                <td>
                                    <select name="accounts_id" id="accounts_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $account->id == $item->accounts_id ? 'selected' : '' }}>{{ $account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  COGS Account</th>
                                <td>
                                    <select name="cogs_accounts_id" id="cogs_accounts_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($cogs_accounts as $cogs_account)
                                        <option value="{{ $cogs_account->id }}" {{ $cogs_account->id == $item->cogs_accounts_id ? 'selected' : '' }}>{{ $cogs_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Asset Account</th>
                                <td>
                                    <select name="asset_accounts_id" id="asset_accounts_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($asset_accounts as $asset_account)
                                        <option value="{{ $asset_account->id }}" {{ $asset_account->id == $item->asset_accounts_id ? 'selected' : '' }}>{{ $asset_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Purchase Description</th>
                                <td>
                                    <input type="text" value="{{ $item->purchase_description }}" class="form-control" name="purchase_description" id="purchase_description" readonly>
                                </td>
                            </tr>
                            @if ($item->tasteless_code)
                            <tr>
                                <th>Accumulated Depreciation</th>
                                <td>
                                    <input type="text" value="{{ $item->accumulated_depreciation }}" class="form-control" name="accumulated_depreciation" id="accumulated_depreciation" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Quantity On Hand</th>
                                <td>
                                    <input type="text" value="{{ $item->quantity_on_hand }}" class="form-control" name="quantity_on_hand" id="quantity_on_hand" readonly>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Fulfillment Type</th>
                                <td>
                                    <select name="fulfillment_type_id" id="fulfillment_type_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($fulfillment_types as $fulfillment_type)
                                        <option value="{{ $fulfillment_type->id }}" {{ $fulfillment_type->id == $item->fulfillment_type_id ? 'selected' : '' }}>{{ $fulfillment_type->fulfillment_method }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M</th>
                                <td>
                                    <select name="uoms_id" id="uoms_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($uoms as $uom)
                                        <option value="{{ $uom->id }}" {{ $uom->id == $item->uoms_id ? 'selected' : '' }}>{{ $uom->uom_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M Set</th>
                                <td>
                                    <select name="uoms_set_id" id="uoms_set_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($uom_sets as $uom_set)
                                        <option value="{{ $uom_set->id }}" {{ $uom_set->id == $item->uoms_set_id ? 'selected' : '' }}>{{ $uom_set->uom_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Currency</th>
                                <td>
                                    <select name="currencies_id" id="currencies_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $currency->id == $item->currencies_id ? 'selected' : '' }}>{{ $currency->currency_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Supplier Cost</th>
                                <td>
                                    <input value="{{ $item->purchase_price }}" type="number" step="any" class="form-control" name="purchase_price" id="purchase_price" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sales Price</th>
                                <td>
                                    <input value="{{ $item->ttp }}"  type="number" step="any" class="form-control" name="ttp" id="ttp" required readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            @if ($item->tasteless_code)
                            <tr>
                                <th>Sales Price Change</th>
                                <td>
                                    <input value="{{ $item->ttp_price_change }}"  type="number" step="any" class="form-control" name="ttp_price_change" id="ttp_price_change" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Sales Price Effective Date</th>
                                <td>
                                    <input value="{{ $item->ttp_price_effective_date }}"  type="date" step="any" class="form-control" name="ttp_price_effective_date" id="ttp_price_effective_date" readonly>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Commi Margin</th>
                                <td>
                                    <input value="{{ $item->ttp_percentage }}" type="number" step="any" class="form-control" name="ttp_percentage" id="ttp_percentage" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Landed Cost</th>
                                <td>
                                    <input value="{{ $item->landed_cost }}" type="number" step="any" class="form-control" name="landed_cost" id="landed_cost" required readonly>
                                </td>
                                <tr>
                                    <th><span class="required-star">*</span> Preferred Vendor</th>
                                    <td>
                                        <select name="suppliers_id" id="suppliers_id" class="form-control" required disabled>
                                            <option value="" selected>{{ $supplier }}</option>
                                        </select>
                                    </td>
                                </tr>
                                @if ($item->tasteless_code)
                                <tr>
                                    <th>Tax Agency</th>
                                    <td>
                                        <input value="{{ $item->tax_agency }}" type="number" step="any" class="form-control" name="tax_agency" id="tax_agency" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>MPN</th>
                                    <td>
                                        <input value="{{ $item->mpn }}" type="number" step="any" class="form-control" name="mpn" id="mpn" readonly>
                                    </td>
                                </tr>
                                @endif
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reorder Pt (Min)</th>
                                <td>
                                    <input value="{{ $item->reorder_pt }}" type="number" step="any" class="form-control" name="reorder_pt" id="reorder_pt" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Group</th>
                                <td>
                                    <select name="groups_id" id="groups_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" {{ $group->id == $item->groups_id ? 'selected' : '' }}>{{ $group->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Category Description</th>
                                <td>
                                    <select name="categories_id" id="categories_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $item->categories_id ? 'selected' : '' }}>{{ $category->category_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Subcategory Description</th>
                                <td>
                                    <select name="subcategories_id" id="subcategories_id" class="form-control" required disabled>
                                        <option value="" selected>{{ $subcategory }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Specifications</th>
                                <td>
                                    <input value="{{ $item->packaging_dimension }}" type="text" class="form-control" name="packaging_dimension" id="packaging_dimension" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Packaging Size</th>
                                <td>
                                    <input value="{{ $item->packaging_size }}" type="number" step="any" class="form-control" name="packaging_size" id="packaging_size" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Packaging UOM</th>
                                <td>
                                    <select name="packagings_id" id="packagings_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packagings as $packaging)
                                        <option value="{{ $packaging->id }}" {{ $packaging->id == $item->packagings_id ? 'selected' : '' }}>{{ $packaging->packaging_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Supplier Item Code</th>
                                <td>
                                    <input value="{{ $item->supplier_item_code }}" type="text" class="form-control" name="supplier_item_code" id="supplier_item_code" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> MOQ Store</th>
                                <td>
                                    <input value="{{ $item->moq_store }}" type="number" step="any" class="form-control" name="moq_store" id="moq_store" required readonly>
                                </td>
                            </tr>
                            @if ($item->tasteless_code)
                            <tr>
                                <th><span class="required-star">*</span> SKU Status</th>
                                <td>
                                    <select name="sku_statuses_id" id="sku_statuses_id" class="form-control" required disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($sku_statuses as $sku_status)
                                        <option value="{{ $sku_status->id }}" {{ $sku_status->id == $item->sku_statuses_id ? 'selected' : '' }}>{{ $sku_status->sku_status_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3 class="text-center text-bold">SEGMENTATIONS</h3>
                    <input type="text" class="hide" name="segmentations" id="segmentations">
                    <table class="table table-reponsive">
                    @php $selected = [] @endphp
                    @foreach ($sku_legends as $sku_legend)
                        @php
                            $value = $sku_legend->sku_legend;
                            $id_name = str_replace(' ', '_', $value);
                            $id_name = strtolower($id_name);
                            $selected[$id_name] = [];
                            if ($item) {
                                foreach ($segmentations as $segmentation) {
                                    $column = $segmentation->segment_column_name;
                                    $item_value = $item->{$column};
                                    if ($item_value == $value) {
                                        $selected[$id_name][] = $column;
                                    }
                                }
                            }

                        @endphp
                        <tr>
                            <th>
                                Segmentation ({{ $value }})
                            </th>
                            <td>

                                @foreach ($segmentations as $segmentation)
                                    @if (in_array($segmentation->segment_column_name, $selected[$id_name]))
                                    <span class="label label-info">{{ $segmentation->segment_column_description }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if (in_array($value, $segmentation_differences))
                                🔴
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
                @if ($item->image_filename)
                <div class="col-md-6">
                    <div class="photo-section">
                        <h3 class="text-center text-bold">ITEM PHOTO</h3>
                        <img src="{{ asset('/img/item-master/' . $item->image_filename) }}" alt="Item Photo">
                    </div>
                </div>
                @endif
            </div>
            <button id="sumit-form-btn" class="btn btn-primary hide" type="submit">submit</button>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button _action="approve" class="btn btn-success pull-right action-btn" id="approve-btn"><i class="fa fa-thumbs-up"></i> Approve</button>
		<button _action="reject" class="btn btn-danger pull-right action-btn" id="reject-btn" style="margin-right: 10px;"><i class="fa fa-thumbs-down"></i> Reject</button>
    </div>
</div>

<script type="application/javascript">
    let differences = {!! json_encode($differences) !!} || {};
    differences = Object.keys(differences);
    console.log(differences);

    $('input, select').each(function() {
        const name = $(this).attr('name')
        if (differences.includes(name)) {
            $(this).addClass('edited-field');
        }
    });

    $('.action-btn').on('click', function() {
        const action = $(this).attr('_action');
        $('#action-selected').val(action);
        Swal.fire({
            title: `Do you want to ${action} this item?`,
            html:  action == 'approve' ? `🟢 Doing so will update the Item Masterfile.`
                : `🔴 Doing so will turn the status of this item to <span class="label label-danger">REJECTED</span>.`,
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

    disableSelected();
</script>
@endsection
@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
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
        <h3 class="text-center text-bold">Item Masterfile</h3>
    </div>
    <div class="panel-body">
        <form action="" class="form-main" autocomplete="off">
            <h4 class="text-center text-bold">ITEM DETAILS</h4>
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input type="text" name="full_item_description" id="full_item_description" class="form-control" required placeholder="Item Description" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Brand Description</th>
                                <td>
                                    <select name="brands_id" id="brands_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Tax Code</th>
                                <td>
                                    <select name="tax_codes_id" id="tax_codes_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Account</th>
                                <td>
                                    <select name="accounts_id" id="accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  COGS Account</th>
                                <td>
                                    <select name="cogs_accounts_id" id="cogs_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Asset Account</th>
                                <td>
                                    <select name="assets_accounts_id" id="assets_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Purchase Description</th>
                                <td>
                                    <input type="text" class="form-control" name="purchase_description" id="purchase_description" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Fulfillment Type</th>
                                <td>
                                    <select name="fulfillment_type_id" id="fulfillment_type_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M</th>
                                <td>
                                    <select name="uoms_id" id="uoms_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M Set</th>
                                <td>
                                    <select name="uoms_set_id" id="uoms_set_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Currency</th>
                                <td>
                                    <select name="currencies_id" id="currencies_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Supplier Cost</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="purchase_price" id="purchase_price">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sales Price</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="ttp" id="ttp">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Commi Margin</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="ttp_percentage" id="ttp_percentage">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Landed Cost</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="landed_cost" id="landed_cost">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Preferred Vendor</th>
                                <td>
                                    <select name="suppliers_id" id="suppliers_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right _action="save" id="save-btn"><i class="fa fa-save"></i> Save</button>
    </div>
</div>

@endsection

@push('bottom')
<script>
</script>
@endpush
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
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input type="text" value="{{ $item->item_description }}" name="item_description" class="form-control" required placeholder="Item Description" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Item Type</th>
                                <td>
                                    <select name="new_item_types_id" id="new_item_types_id" class="form-control" required>
                                        @foreach ($new_item_types as $new_item_type)
                                        <option value="{{$new_item_type->id}}" {{ $item->new_item_types_id == $new_item_type->id ? 'selected' : '' }}>{{$new_item_type->item_type_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
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
                                <td><input type="number" value="{{ $item ? (float) $item->ttp : '' }}" step="any" name="ttp" class="form-control" required placeholder="SRP"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Target Date</th>
                                <td><input type="date" value="{{ $item->target_date ? $item->target_date : '' }}" step="any" name="target_date" class="form-control" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th>{{$table == 'new_ingredients' ? 'NWI Code' : 'NWP Code'}}</th>
                                <th>{{$item->nwi_code ?? $item->nwp_code}}</th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <td>{{$item->created_name}}</td>
                            </tr>
                            <tr>
                                <th>Created Date</th>
                                <td>{{$item->created_at}}</td>
                            </tr>
                            <tr>
                                <th>Updated by</th>
                                <td>{{$item->updated_name}}</td>
                            </tr>
                            <tr>
                                <th>Updated Date</th>
                                <td>{{$item->updated_at}}</td>
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
                confirmButtonText: 'Save'
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
</script>


@endpush
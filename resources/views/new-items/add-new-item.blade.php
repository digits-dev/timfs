@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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
                            <tr>
                                <th><span class="required-star">*</span>  Item Type</th>
                                <td>
                                    <select name="new_item_types_id" id="new_item_types_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($new_item_types as $new_item_type)
                                        <option value="{{$new_item_type->id}}">{{$new_item_type->item_type_description}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Packaging Size</th>
                                <td><input type="number" step="any" name="packaging_size" class="form-control" required placeholder="Pakcaging Size"></td>
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
                                <th><span class="required-star">*</span>  TTP</th>
                                <td><input type="number" step="any" name="ttp" class="form-control" required placeholder="TTP"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th>{{$table == 'new_ingredients' ? 'NWI' : 'NWP'}} Code</th>
                                <th><input type="text" class="form-control" readonly placeholder="{{$table == 'new_ingredients' ? 'NWI' : 'NWP'}}-XXXXX"></th>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <td><input type="text" value="{{$created_by->name}}" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th>Created Date    </th>
                                <td><input type="text" class="form-control" readonly value="{{$created_at}}"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <hr>
                    <div class="new-fields">
                        <H4>Please copy, paste, and fill out these fields to your comment.</H4>
                        @foreach ($comment_templates as $field)
                        🔵 {{ $field }}: 
                        <br />
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <hr>
                    <h3 class="text-center"><span class="required-star">*</span> COMMENTS</h3>
                    <textarea class="comment-textarea" name="comment" id="comment" form="form-main" required required placeholder="Type your comment here..."></textarea>
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




@endsection

@push('bottom')
<script>
    $(document).ready(function() {

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
    });
</script>


@endpush
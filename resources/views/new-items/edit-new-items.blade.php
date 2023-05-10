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
        <form method="POST" action="{{ $table == 'new_ingredients' ? route('edit_new_ingredients') : route('edit_new_packagings')}}" id="form-main" autocomplete="off">
            @csrf
            <input type="text" name="new_items_id" class="hide" value="{{ $item->new_ingredients_id ?? $item->new_packagings_id }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label"><span class="required-star">*</span> Tasteless Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input id="tasteless-code" type="number" name="tasteless_code" class="form-control tasteless-code" placeholder="Enter Item Master Tasteless Code" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th>Item Description</th>
                                <td>{{$item->item_description}}</td>
                            </tr>
                            <tr>
                                <th>Packaging Size</th>
                                <td>{{$item->packaging_size}}</td>
                            </tr>
                            <tr>
                                <th>UOM</th>
                                <td>{{$item->uom_description}}</td>
                            </tr>
                            <tr>
                                <th>TTP</th>
                                <td>{{$item->ttp}}</td>
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
                                <td>{{$item->name}}</td>
                            </tr>
                            <tr>
                                <th>Created Date</th>
                                <td>{{$item->created_at}}</td>
                            </tr>
                            <tr>
                                <th># of RND Menu with this item</th>
                                <td>{{$rnd_count}}</td>
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
            const tastelessCode = $('#tasteless-code').val().trim();
            if (!tastelessCode) {
                $('#submit-btn').click();
                return;
            }
            Swal.fire({
                title: 'Uploading...',
                html: 'Please wait...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('search_item_for_tagging') }}",
                data: { tasteless_code:  tastelessCode, _token: "{{ csrf_token() }}",},
                success: function(response) {
                    const data = JSON.parse(response) || {};
                    Swal.close();
                    Swal.fire({
                        title: 'Do you want to tag to this item?',
                        html: data.full_item_description ? `<strong>Item:</strong> ${data.full_item_description}` : undefined,
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
                },
                error: function(response) { 
                    console.log(response);
                    Swal.close();
                    $('#submit-btn').click();
                }  
            });
        }

        $('#save-btn').click(showSwal);

        $('#tasteless-code').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                showSwal();
            }
        });
    });
</script>


@endpush
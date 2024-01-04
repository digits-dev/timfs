@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
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
    <div class="panel-heading">Edit Data</div>
    <div class="panel-body">
        <form action="{{ CRUDBooster::mainpath('edit-save') }}/{{ $item->id }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="box-body"></div>
            @csrf
            <input type="text" class="hide" name="approver_ids" id="approver_ids">
            <div class="row form-group">
                <label for="" class="control-label col-sm-2 text-right">Requestor <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="requestor_id" id="requestor_id" required>
                        @foreach ($users as $user)
                            @if ($user->id == $item->requestor_id )
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="control-label col-sm-2 text-right">Approver <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" id="select_approvers" multiple="multiple">
                        @foreach ($users as $user)
                            <option {{ in_array($user->id, $approver_ids) ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="control-label col-sm-2 text-right">Status <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="status" id="status" class="form-control status">
                        @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ $status == $item->status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="hide" id="real-submit"></button>
        </form>
    </div>
    <div class="panel-footer">
        <div class="col-sm-2"></div>
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
        <button class="btn btn-success" id="save-btn">Save</button>
    </div>
</div>

<script>
    $('#requestor_id, #select_approvers, #status').select2({
        with: '100%',
    });
    $('#save-btn').on('click', function() {
        let approver_ids = $('#select_approvers option:selected').get().map(e => e.value);
        approver_ids = approver_ids.join(',');
        $('#approver_ids').val(approver_ids);  
        $('#real-submit').click();
    });
</script>
@endsection
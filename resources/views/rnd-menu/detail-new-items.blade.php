@push('head')
<style>
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
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th style="width: 25%">Tasteless Code</th>
                        <td>{{$item->tasteless_code}}</td>
                    </tr>
                    <tr>
                        <th>{{$table == 'new_ingredients' ? 'NWI Code' : 'NWP Code'}}</th>
                        <td>{{$item->nwi_code ?? $item->nwp_code}}</td>
                    </tr>
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
                    <tr>
                        <th>Created by</th>
                        <td>{{$item->creator_name}}</td>
                    </tr>
                    <tr>
                        <th>Created Date</th>
                        <td>{{$item->created_at}}</td>
                    </tr>
                    <tr>
                        <th>Tagged by</th>
                        <td>{{$item->tagger_name}}</td>
                    </tr>
                    <tr>
                        <th>Tagged Date</th>
                        <td>{{$item->tagged_at}}</td>
                    </tr>
                    <tr>
                        <th>View Item Masters Details</th>
                        <td><a href="{{CRUDBooster::adminPath('item_masters/detail/' . $item->item_masters_id)}}">View Details</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>




@endsection

@push('bottom')
<script>
</script>


@endpush
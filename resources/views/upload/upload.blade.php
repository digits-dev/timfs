@extends('crudbooster::admin_template')

@section('content')

<div id='box_main' class="box box-primary">
    <div class="box-header with-border text-center">
        <h3 class="box-title"><b>Item Master Import Modules</b></h3>       
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                <th scope="col" style="text-align: center">#</th>
                <th scope="col" style="text-align: center">Uploader</th>
                <th scope="col" style="text-align: center">Description</th>
                <th scope="col" style="text-align: center">Action</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <th scope="row">1</th>
                    <td>Item Master Fulfilment Type bulk import (Update)</td>
                    <td>Existing item master Fulfillment Type bulk update</td>
                    <td style="text-align: center">
                        <a href="{{ route('getUpdateItems') }}" target="_parent"><button class="btn btn-primary" style="width:80%">Update Item Fulfilment Type Import</button></a>
                    </td>
                </tr>

                <tr>
                    <th scope="row">2</th>
                    <td>Sales price bulk import (Update)</td>
                    <td>Scheduled sales price bulk update</td>
                    <td style="text-align: center">
                        <a href="{{ route('getUpdateItemsPrice') }}" target="_parent"><button class="btn btn-primary" style="width:80%">Sales Price Import</button></a>
                    </td>
                </tr>

                <tr>
                    <th scope="row">3</th>
                    <td>SKU legend bulk import (Update)</td>
                    <td>SKU legend bulk update</td>
                    <td style="text-align: center">
                        <a href="{{ route('getUpdateItemsSkuLegend') }}" target="_parent"><button class="btn btn-primary" style="width:80%">SKU Legend Import</button></a>
                    </td>
                </tr>

                <tr>
                    <th scope="row">4</th>
                    <td>Cost price bulk import (Update)</td>
                    <td>Cost price bulk update</td>
                    <td style="text-align: center">
                        <a href="{{ route('getUpdateItemsCostPrice') }}" target="_parent"><button class="btn btn-primary" style="width:80%">Cost Price Import</button></a>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>

    <div class="box-footer">
            
        <a href="{{ CRUDBooster::mainpath() }}" class='btn btn-default pull-left'>Cancel</a>
        
    </div><!-- /.box-footer-->

    
</div><!-- /.box -->

@endsection

@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<style>
    td:first-child {
        max-width: 50px !important;
        min-width: 30px !important;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Detail RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="text-left text-bold">RND Menu Item Code</td>
                            <td>{{$item->rnd_code}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Menu Item Description</td>
                            <td>{{$item->rnd_menu_description}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Tasteless Menu Code</td>
                            <td>{{$item->tasteless_menu_code}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Portion Size</td>
                            <td>{{(float) $item->portion_size}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Recipe Cost Without Buffer</td>
                            <td>{{(float) $item->recipe_cost_wo_buffer}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Buffer</td>
                            <td>{{(float) $item->buffer}}%</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Final Recipe Cost</td>
                            <td>{{(float) $item->final_recipe_cost}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Packaging Cost</td>
                            <td>{{(float) $item->packaging_cost}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Ideal Food Cost</td>
                            <td>{{(float) $item->ideal_food_cost}}%</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Suggested Final SRP with VAT</td>
                            <td>{{(float) $item->suggested_final_srp_w_vat}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Final SRP without VAT</td>
                            <td>{{(float) $item->final_srp_wo_vat}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Final SRP with VAT</td>
                            <td>{{(float) $item->final_srp_w_vat}}</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Cost Packaging From Final SRP</td>
                            <td>{{(float) $item->cost_packaging_from_final_srp}}%</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Food Cost From Final SRP</td>
                            <td>{{(float) $item->food_cost_from_final_srp}}%</td>
                        </tr>
                        <tr>
                            <td class="text-left text-bold">Total Cost</td>
                            <td>{{(float) $item->total_cost}}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-2"><strong>Published By: </strong></div>
            <div class="col-md-3">{{$item->published_by}} </div>
            <div class="col-md-2"><strong>Published At: </strong></div>
            <div class="col-md-3">{{$item->published_at}} </div>
        </div>
        @if ($item->marketing_approved_at)
        <hr>
        <div class="row">
            <div class="col-md-2"><strong>Approved by (Marketing):</strong></div>
            <div class="col-md-3">{{$item->marketing_approver}}</div>
            <div class="col-md-2"><strong>Approved at (Marketing):</strong></div>
            <div class="col-md-3">{{$item->marketing_approved_at}}</div>
        </div>
        @endif
        @if ($item->purchasing_approved_at)
        <hr>
        <div class="row">
            <div class="col-md-2"><strong>Approved by (Purchasing):</strong></div>
            <div class="col-md-3">{{$item->purchasing_approver}}</div>
            <div class="col-md-2"><strong>Approved at (Purchasing):</strong></div>
            <div class="col-md-3">{{$item->purchasing_approved_at}}</div>
        </div>
        @endif
        @if ($item->accounting_approved_at)
        <hr>
        <div class="row">
            <div class="col-md-2"><strong>Approved by (Accounting):</strong></div>
            <div class="col-md-3">{{$item->accounting_approver}}</div>
            <div class="col-md-2"><strong>Approved at (Accounting):</strong></div>
            <div class="col-md-3">{{$item->accounting_approved_at}}</div>
        </div>
        @endif
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>
@endsection

@push('bottom')
<script>
    $(document).ready(function() {
       
    });
    
</script>
@endpush
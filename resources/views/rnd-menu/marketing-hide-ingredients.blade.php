@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<style>
    td, th {
        text-align: center;
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
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">RND Menu Item Code</th>
                        <th scope="col">RND Menu Item Description</th>
                        <th scope="col">RND Menu SRP</th>
                        <th scope="col">Total Ingredient Cost</th>
                        <th scope="col">Portion Size</th>
                        <th scope="col">Food Cost</th>
                        <th scope="col">Food Cost Percentage</th>
                        <th scope="col">Packaging Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$item->rnd_code}}</td>
                        <td>{{$item->rnd_menu_description}}</td>
                        <td class="peso">{{'₱ ' . (float) $item->rnd_menu_srp}}</td>
                        <td class="peso">{{'₱ ' . (float) $item->computed_ingredient_total_cost}}</td>
                        <td>{{(float) $item->portion_size}}</td>
                        <td class="food-cost">{{$item->computed_food_cost ? '₱ ' . (float) $item->computed_food_cost : '0'}}</td>
                        <td class="food-cost-percentage">{{$item->computed_food_cost_percentage ? (float) $item->computed_food_cost_percentage . '%' : '0%'}}</td>
                        <td>{{$item->packaging_cost ? '₱ ' . (float) $item->packaging_cost : '0'}}</td>
                    </tr>
                </tbody>
            </table>
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
        @if ($item->marketing_approved_at)
        <hr>
        <div class="row">
            <div class="col-md-2"><strong>Approved by (Purchasing):</strong></div>
            <div class="col-md-3">{{$item->purchasing_approver}}</div>
            <div class="col-md-2"><strong>Approved at (Purchasing):</strong></div>
            <div class="col-md-3">{{$item->purchasing_approved_at}}</div>
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
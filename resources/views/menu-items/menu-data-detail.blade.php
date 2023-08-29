@extends('crudbooster::admin_template')
@section('content')
<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Details Menu Item</strong>
    </div>
    <div class="panel-body">
        <h3 class="text-center">MENU ITEM DETAILS</h3>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td class="text-bold" style="width: 250px;">Tasteless Menu Code</td>
                    <td>{{ $item->tasteless_menu_code }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Menu Item Description</td> 
                    <td>{{ $item->menu_item_description }}</td>
                </tr>
                @foreach ($menu_items_data->old_codes as $index => $old_code)
                <tr>
                    <td class="text-bold">POS Old Item Code {{$index + 1}}</td> 
                    <td>{{ $menu_items_data->{$old_code->menu_old_code_column_name} }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-bold">POS Old Description</td> 
                    <td>{{$menu_items_data->pos_old_item_description}}</td>
                </tr>
                <tr>
                    <td class="text-bold">Product Type</td> 
                    <td>{{ $menu_items_data->menu_product_types_name ?? $menu_items_data->menu_product_type_description }}</td>
                </tr>
                @foreach ($menu_items_data->menu_choice_groups as $index => $choice_group)
                <tr>
                    <td class="text-bold">Choices Group {{$index + 1}}</td> 
                    <td>{{ $menu_items_data->{'choices_' . $choice_group->menu_choice_group_column_name} }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Choices Group {{$index + 1}} SKU</td> 
                    <td>
                    @foreach ($menu_items_data->{'choices_sku' . $choice_group->menu_choice_group_column_name} as $sku)
                    <label class="label label-primary">{{$sku}}</label>
                    @endforeach
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-bold">Menu Type</td> 
                    <td>{{ $menu_items_data->menu_type_description }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Main Category</td> 
                    <td>{{ $menu_items_data->category_description }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Sub Category</td> 
                    <td>{{ $menu_items_data->subcategory_description }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Price - Dine in</td> 
                    <td>{{ $menu_items_data->menu_price_dine }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Price - Delivery</td> 
                    <td>{{ $menu_items_data->menu_price_dlv }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Price - Take Out</td> 
                    <td>{{ $menu_items_data->menu_price_take }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Food Cost</td> 
                    <td>{{ $menu_items_data->food_cost }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Food Cost Percentage</td> 
                    <td>{{ (float) $menu_items_data->food_cost_percentage }}%</td>
                </tr>
                <tr>
                    <td class="text-bold">Original Concept</td> 
                    <td>{{ $menu_items_data->original_concept }}</td>
                </tr>
                <tr>
                    <td class="text-bold">Store Segmentation</td>
                    <td>
                    @foreach ($menu_items_data->menu_segmentations as $segmentation)
                    <label class="label label-primary">{{$segmentation}}</label>
                    @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>



@endsection
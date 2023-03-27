<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
  <style>
    table tbody tr td:first-child{
      font-weight: bold;
    }
  </style>
@endpush
@section('content')
  <!-- Your html goes here -->
  <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Add Menu Item</a></p>

  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Form</div>
      <div class='panel-body'>      
        <div class='form-group'>
            <div class="box-body" id="parent-form-area">
              <div class='table-responsive'>
                <table id='table-detail' class='table table-striped' style="width:100%">
                  <tbody>
                    <tr>
                      <td style="width:30%">TASTELESS MENU CODE</td>
                      <td>{{ $row->tasteless_menu_code }}</td>
                    </tr>
                    <tr>
                      <td>POS OLD ITEM CODE 1</td>
                      <td>{{ $row->old_code_1 }}</td>
                    </tr>
                    <tr>
                      <td>POS OLD ITEM CODE 2</td>
                      <td>{{ $row->old_code_2 }}</td>
                    </tr>
                    <tr>
                      <td>POS OLD ITEM CODE 3</td>
                      <td>{{ $row->old_code_3 }}</td>
                    </tr>
                    <tr>
                      <td>POS OLD DESCRIPTION</td>
                      <td>{{ $row->pos_old_item_description }}</td>
                    </tr>
                    <tr>
                      <td>MENU DESCRIPTION</td>
                      <td>{{ $row->menu_item_description }}</td>
                    </tr>
                      @php
                        $i=0;
                      @endphp
                    @foreach ($groups as $key=>$value)
                      @php
                        $choices_grp = 'choices_group_'.strval($i+1);
                        $i++;
                      @endphp
                      <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $row->$choices_grp }}</td>
                      </tr>
                      <tr>
                        <td>{{ $key }} SKU</td>
                        <td>{{ $value }}</td>
                      </tr>
                    @endforeach
                    <tr> 
                      <td>MENU TYPE</td>
                      <td>{{ $row->menu_type }}</td>
                    </tr>
                    <tr>
                      <td>MAIN CATEGORY</td>
                      <td>{{ $row->main_category }}</td>
                    </tr>
                    <tr>
                      <td>SUB CATEGORY</td>
                      <td>{{ $row->sub_category }}</td>
                    </tr>
                    <tr>
                      <td>PRICE DELIVERY</td>
                      <td>{{ $row->menu_price_dlv }}</td>
                    </tr>
                    <tr>
                      <td>PRICE DINE IN</td>
                      <td>{{ $row->menu_price_dine }}</td>
                    </tr>
                    <tr>
                      <td>PRICE TAKE OUT</td>
                      <td>{{ $row->menu_price_take }}</td>
                    </tr>
                    <tr>
                      <td>ORIGINAL CONCEPT</td>
                      <td>{{ $row->original_concept }}</td>
                    </tr>
                    <tr>
                      <td>STATUS</td>
                      <td>{{ $row->status }}</td>
                    </tr>
                    @foreach ($menu_segmentations as $concept)
                      @if (in_array($concept->menu_segment_column_name, $user_menu_segment))
                        <tr>
                          <td>+ {{ $concept->menu_segment_column_description }}</td>
                          <td><span class='badge'>X</span> </td>
                        </tr> 
                      @else
                        <tr>
                          <td>+ {{ $concept->menu_segment_column_description }}</td>
                          <td></span> </td>
                        </tr>                  
                      @endif
                    @endforeach
                  </tbody>

                </table>
            </div>
          </div>
            <div class="box-footer" style="background: #F5F5F5">
                <div class="form-group">
                    <label class="control-label col-sm-2"></label>
                    <div class="col-sm-10">
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
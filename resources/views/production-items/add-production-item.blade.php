
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/production-item/custom-item.css')}}">
<style>
    input::placeholder{
        font-style: italic;
    }
    select {
        border-radius: 0px 5px 5px 0px !important; 
    }
    
    .panel-heading{
        background-color: #3c8dbc !important;
        color: #fff !important;
    }

    .input-group-addon{
        border-color: #989797 !important; 
        border-radius: 5px 0px 0px 5px !important; 
    }
    .form-control{
        border-color: #989797 !important; 
    }
    .float-label{
        position: absolute;
        background: #fff;
        top: -10px;
        left: 45px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }
    .rounded{
        border-radius: 5px;
    }
    .ingredient-label{
        position: absolute;
        background: #fff;
        top: -14px;
        left: 45px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }
    .ingredient-box{
        border: 1px solid #989797 !important; 
        border-radius: 5px;
        height: auto;
        padding: 15px;
    }
    .ingredient-label{
        font-size: 18px;
    }
</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-dollar"></i><strong> Production Item</strong>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-file"></i></span>
                        <label class="description float-label">Description</label>
                        <input type="text" class="form-control rounded" placeholder="description" aria-describedby="basic-addon1" />
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>
                        <label class="production_category float-label">Production Category</label>
                        <select class="form-control select" id="production_category" name="production_category" required>
                            <option value="">Select Category</option>
                            @foreach($productionCategories as $category)
                                <option value="{{ $category->id }}" {{ old('production_category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-check"></i></span>
                        <label class="production_location float-label">Production Location</label>
                        <select class="form-control select" id="production_location" name="production_location" required>
                            <option value="">Select Location</option>
                            @foreach($productionLocations as $location)
                                <option value="{{ $location->id }}" {{ old('production_location') == $location->id ? 'selected' : '' }}>
                                    {{ $location->production_location_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
          </div>

          <div class="row" style="margin-top: 15px">
            <div class="col-md-12">
                <label class="ingredient-label ingredient-label">Ingredients</label>
                <div class="ingredient-box">
                    <table>

                    </table>
                </div>
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Ingredients</button>
            </div>
          </div>
        </div>

        <div class="panel-footer">
            <button type="submit" class="btn btn-success">+ Save data</button>
            <a href="#" class="btn btn-link">← Back</a>
        </div>
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
     
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');
        $(`.select`).select2({
            width: '100%',
            height: '100%',
            placeholder: 'None selected...'
        });
     
    });
</script>
@endpush
@endsection
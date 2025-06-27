
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/production-item/custom-item.css')}}">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
<style>
    input::placeholder{
        font-style: italic;
    }
    select {
        border-radius: 0px 5px 5px 0px !important; 
    }
    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }
    .ui-autocomplete {
        max-height: 400px; /* Adjust height as needed */
        overflow-y: auto;  /* Enables vertical scroll */
        overflow-x: hidden; /* Optional: hide horizontal scroll */
        z-index: 10000 !important; /* Ensure it appears above other elements */
        width: auto;
    }

    .ui-state-focus {
        background: none !important;
        background-color: #367fa9 !important;
        border: 1px solid #fff !important;
        color: #fff !important;
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

    .float-line-label {
        position: absolute;
        background: #fff;
        top: -10px;
        left: 20px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }

    .float-line-label-no-bg{
        position: absolute;
        top: -10px;
        left: 20px;
        padding-left: 5px;
        padding-right: 5px;
        z-index: 100 !important;
    }
    
    .ingredient-box{
        border: 1px solid #989797 !important; 
        border-radius: 5px;
        height: auto;
        padding: 20px;
        min-height: 70px;
        align-items: center;
    }
    .ingredient-label{
        font-size: 18px;
    }

    .ingredient-table input.form-control {
        height: 38px;
        margin-bottom: 0;
    }

    .ingredient-table td {
        vertical-align: middle;
    }

    .no-data-available {
        font-style: italic;
        color: #999;
    }
    .tr-border {
        border: 1px solid #989797 !important;
        border-radius: 10px;
        padding: 25px;
        background-color: #f9f9f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 6px;
        gap: 5px; 
    }

    @media (max-width: 768px) {
        .ingredient-table td {
            min-width: 100%;
        }

        .ingredient-label {
            font-size: 16px;
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-in-right {
        animation: slideInRight 0.2s ease-out forwards;
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-100%);
        }
    }

    .slide-out-right {
        animation: slideOutRight 0.2s ease-in forwards;
    }

</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-dollar"></i><strong> Production Item ss</strong>
        </div>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let firstError =   @json($errors->first());
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: firstError,
                confirmButtonText: 'OK'
                });
            });
        </script>
        @endif
        <form action="{{route('add-production-location-to-db')}}" method="POST" id="ProductionItems" enctype="multipart/form-data">
         @csrf   

                <div class="panel-body">
                     <input name="id" value="{{$location->id}}" class="hide"/>
                      <div style="display:flex; align-items:center; gap:12px;">
                          <label style="min-width:120px; margin-bottom:0;">Product location description</label>
                          <input style="width:50%;" type="text" style="flex:1;" value="{{$location->production_location_description}}" class="form-control"   name="production_location_description" required />
                      </div>
                </div>
 
                <button type="submit" id="sumit-form-button" class="btn btn-success  hide">+ Save data</button>
            </form>
         
             <div class="panel-footer">
                <button id="save-datas" class="btn btn-success">+ Save datas</button>
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-link'>← Back</a>
            </div>
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    
    $(document).ready(function() {
        
        //to save data and list to Production Items List module
           $('#save-datas').on('click', function() {
           Swal.fire({
                title: 'Do you want to save this production location?',
                html:  `Doing so will create new production location.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#sumit-form-button').click();
                }
            });
        });


 
    });
</script>
@endpush
@endsection
@extends('crudbooster::admin_template')
@section('content')
<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">Detail Data</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                @foreach ($rows as $key => $value)
                    <tr>
                    <th style="with: 25%">{{ $key }}</th>
                    @if (!is_array($value))
                    <td>{{ $value }}</td>
                    @else
                        @php
                        $str = '';
                        foreach ($value as $e) {
                            $str .= "\n<label class='label label-info'>$e</label>";
                        }
                        @endphp
                        <td>{!!$str !!}</td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainPath() }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
</div>
@endsection
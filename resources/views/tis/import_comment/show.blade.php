@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ImportStudent {{ $importstudent->id }}</h3>
                    @can('view-'.str_slug('import-comment'))
                        <a class="btn btn-success pull-right" href="{{ url('admin/selection/import-student') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> @lang('message.back')</a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $importstudent->id }}</td>
                            </tr>
                            <tr><th> Fiscalyear Id </th><td> {{ $importstudent->fiscalyear_id }} </td></tr><tr><th> Scholarshipsource Id </th><td> {{ $importstudent->scholarshipsource_id }} </td></tr><tr><th> Scholarshiptypes Id </th><td> {{ $importstudent->scholarshiptypes_id }} </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

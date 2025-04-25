@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">report21own_import {{ $report21own_import->id }}</h3>
                    @can('view-'.str_slug('report21own_import'))
                        <a class="btn btn-success pull-right" href="{{ url('/report21own_import/report21own_import') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th>ID</th>
                                  <td>{{ $report21own_import->id }}</td>
                              </tr>
                              <tr><th> ID </th><td> {{ $report21own_import->ID }} </td></tr><tr><th> State </th><td> {{ $report21own_import->state }} </td></tr><tr><th> Created By </th><td> {{ $report21own_import->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $report21own_import->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $report21own_import->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $report21own_import->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($report21own_import->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $report21own_import->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($report21own_import->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

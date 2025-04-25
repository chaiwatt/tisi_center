@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">report_export {{ $report_export->id }}</h3>
                    @can('view-'.str_slug('report_export'))
                        <a class="btn btn-success pull-right" href="{{ url('/report_export/report_export') }}">
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
                                  <td>{{ $report_export->id }}</td>
                              </tr>
                              <tr><th> ID </th><td> {{ $report_export->ID }} </td></tr><tr><th> State </th><td> {{ $report_export->state }} </td></tr><tr><th> Created By </th><td> {{ $report_export->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $report_export->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $report_export->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $report_export->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($report_export->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $report_export->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($report_export->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

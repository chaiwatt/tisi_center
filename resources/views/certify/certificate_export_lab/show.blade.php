@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ออกใบรับรอง (LAB) {{ $certificateexportlab->id }}</h3>
                    @can('view-'.str_slug('certificateexportlab'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/certificate-export-l-a-b') }}">
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
                                  <td>{{ $certificateexportlab->id }}</td>
                              </tr>
                              <tr><th> Title </th><td> {{ $certificateexportlab->title }} </td></tr><tr><th> State </th><td> {{ $certificateexportlab->state }} </td></tr><tr><th> Created By </th><td> {{ $certificateexportlab->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $certificateexportlab->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $certificateexportlab->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $certificateexportlab->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($certificateexportlab->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $certificateexportlab->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($certificateexportlab->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

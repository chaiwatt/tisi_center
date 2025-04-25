@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">control_check {{ $control_check->id }}</h3>
                    @can('view-'.str_slug('control_check'))
                        <a class="btn btn-success pull-right" href="{{ url('/control_check/control_check') }}">
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
                                  <td>{{ $control_check->id }}</td>
                              </tr>
                              <tr><th> ID </th><td> {{ $control_check->ID }} </td></tr><tr><th> State </th><td> {{ $control_check->state }} </td></tr><tr><th> Created By </th><td> {{ $control_check->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $control_check->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $control_check->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $control_check->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($control_check->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $control_check->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($control_check->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

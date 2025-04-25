@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดหมวดหมู่รายการสอบเทียบ {{ $calibration_group->id }}</h3>
                    @can('view-'.str_slug('calibration_group'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/calibration_group') }}">
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
                                  <td>{{ $calibration_group->id }}</td>
                              </tr>
                              <tr>
                                <th> หมวดหมู่รายการสอบเทียบ: </th>
                                <td> {{ $calibration_group->title }} </td>
                              </tr>
                              <tr>
                                <th> มาตรฐาน: </th>
                                <td> {{ $calibration_group->formula->title }} </td>
                              </tr>
                              <tr>
                                <th> สาขาการสอบเทียบ: </th>
                                <td> {{ $calibration_group->calibration_branch->title }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $calibration_group->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $calibration_group->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($calibration_group->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $calibration_group->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($calibration_group->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

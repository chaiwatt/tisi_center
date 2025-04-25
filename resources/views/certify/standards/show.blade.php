@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดทำมาตรฐานรับรอง {{ $standard->id }}</h3>
                    @can('view-'.str_slug('certifystandard'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/standards') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr><th> ประเภทมาตรฐาน </th>
                              <td> {{ $standard->standard_type->title }} </td>
                              </tr>
                              <tr><th> เลขมาตรฐาน </th><td> {{ $standard->std_no }} </td></tr>
                              <tr><th> เล่ม </th><td> {{ $standard->std_book }} </td></tr>
                              <tr><th> ปีมาตรฐาน </th><td> {{ $standard->std_year }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $standard->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $standard->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($standard->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $standard->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($standard->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

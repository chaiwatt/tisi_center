@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ประเภทมาตรฐานการรับรอง {{ $standardtype->id }}</h3>
                    @can('view-'.str_slug('standardtypes'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/standardtypes') }}">
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
                                  <td>{{ $standardtype->id }}</td>
                              </tr>
                              <tr><th> ประเภทมาตรฐาน </th><td> {{ $standardtype->title }} </td></tr>
                              <tr><th> ประเภทข้อมูลเสนอ </th><td> {{ $standardtype->offertype }} </td></tr>
                              <tr><th> ประเภทข้อมูลเสนอ (Eng) </th><td> {{ $standardtype->offertype_eng }} </td></tr>
                              <tr><th> กลุ่มผู้ใช้งานที่รับผิดชอบ </th><td> {{   !empty($standardtype->department_to->depart_name)? $standardtype->department_to->depart_name:'' }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $standardtype->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ @$standardtype->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($standardtype->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ @$standardtype->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($standardtype->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ประเภทมาตรฐาน {{ $standard_type->id }}</h3>
                    @can('view-'.str_slug('standard_type'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/standard_type') }}">
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
                                <td>{{ $standard_type->id }}</td>
                              </tr>
                              <tr><th> ชื่อประเภทมาตรฐาน(TH) </th><td> {{ $standard_type->title }} </td></tr>
                              <tr><th> ชื่อประเภทมาตรฐาน(EN) </th><td> {{ $standard_type->title_en }} </td></tr>
                              <tr><th> ตัวย่อ </th><td> {{ $standard_type->acronym }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $standard_type->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $standard_type->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($standard_type->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $standard_type->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($standard_type->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

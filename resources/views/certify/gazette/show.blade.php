@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">gazette {{ $gazette->id }}</h3>
                    @can('view-'.str_slug('gazette'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/gazette') }}">
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
                                  <td>{{ $gazette->id }}</td>
                              </tr>
                              <tr><th> Title </th><td> {{ $gazette->title }} </td></tr><tr><th> State </th><td> {{ $gazette->state }} </td></tr><tr><th> Created By </th><td> {{ $gazette->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $gazette->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $gazette->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $gazette->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($gazette->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $gazette->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($gazette->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

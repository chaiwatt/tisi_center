@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดผู้ใช้งานเว็บเซอร์วิส {{ $web_service->id }}</h3>
                    @can('view-'.str_slug('web_service'))
                        <a class="btn btn-success pull-right" href="{{ url('/ws/web_service') }}">
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
                                  <td>{{ $web_service->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อผู้ใช้งาน Web Service: </th>
                                <td> {{ $web_service->title }} </td>
                              </tr>
                              <tr>
                                <th> อีเมลติดต่อ: </th>
                                <td> {{ $web_service->email }} </td>
                              </tr>
                              <tr>
                                <th> app_name: </th>
                                <td> {{ $web_service->app_name }} </td>
                              </tr>
                              <tr>
                                <th> API ที่เปิดให้ใช้งาน: </th>
                                <td>
                                  @foreach (json_decode($web_service->ListAPI) as $api)
                                    <div>{{ HP_API::APILists()[$api]['detail'] }}</div>
                                  @endforeach
                                </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $web_service->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $web_service->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($web_service->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $web_service->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($web_service->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

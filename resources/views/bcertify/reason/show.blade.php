@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เหตุผลและความจำเป็น</h3>
                    @can('view-'.str_slug('bcertify-reason'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/reason') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                <th> เหตุผลและความจะเป็น </th>
                                <td> {{ $reason->title }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $reason->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> เงื่อนไขอ้างอิง </th>
                                <td> {!! $reason->condition=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td>     {!! !empty($reason->user_created->FullName)?$reason->user_created->FullName:auth()->user()->Fullname !!} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($reason->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td>      {!! !empty($reason->user_updated->FullName)?$reason->user_updated->FullName:null !!}  </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($reason->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

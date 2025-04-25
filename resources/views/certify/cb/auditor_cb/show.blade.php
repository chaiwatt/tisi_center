@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แต่งตั้งคณะผู้ตรวจประเมิน (CB) {{ $auditorcb->id }}</h3>
                    @can('view-'.str_slug('auditorcb'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/auditor-cb') }}">
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
                                  <td>{{ $auditorcb->id }}</td>
                              </tr>
                              <tr><th> Title </th><td> {{ $auditorcb->title }} </td></tr><tr><th> State </th><td> {{ $auditorcb->state }} </td></tr><tr><th> Created By </th><td> {{ $auditorcb->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $auditorcb->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $auditorcb->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $auditorcb->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($auditorcb->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $auditorcb->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($auditorcb->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

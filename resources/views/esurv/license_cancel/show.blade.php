@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดการยกเลิกใบอนุญาต {{ $license_cancel->id }}</h3>
                    @can('view-'.str_slug('license_cancel'))
                        <a class="btn btn-success pull-right" href="{{ url('/esurv/license_cancel') }}">
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
                                  <td>{{ $license_cancel->id }}</td>
                              </tr>
                              <tr><th> Tb3 Tisno </th><td> {{ $license_cancel->tb3_Tisno }} </td></tr><tr><th> Cancel Date </th><td> {{ $license_cancel->cancel_date }} </td></tr><tr><th> Reason Type </th><td> {{ $license_cancel->reason_type }} </td></tr><tr><th> Reason Other </th><td> {{ $license_cancel->reason_other }} </td></tr><tr><th> Remark </th><td> {{ $license_cancel->remark }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $license_cancel->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $license_cancel->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($license_cancel->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $license_cancel->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($license_cancel->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดสถานะผู้ตรวจประเมิน {{ $status_auditor->id }}</h3>
                    @can('view-'.str_slug('status_auditor'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/status_auditor') }}">
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
                                  <td>{{ $status_auditor->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อสถานะผู้ตรวจประเมิน: </th>
                                <td> {{ $status_auditor->title }} </td>
                              </tr>
                              <tr>
                                <th> ประเภทสถานะผู้ตรวจประเมิน: </th>
                                <td> {{ HP::AuditorKinds()[$status_auditor->kind] }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $status_auditor->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $status_auditor->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($status_auditor->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $status_auditor->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($status_auditor->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

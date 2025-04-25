@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดสถานะการดำเนินงาน {{ $status_progress->id }}</h3>
                    @can('view-'.str_slug('status_progress'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/status_progress') }}">
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
                                <td>{{ $status_progress->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อสถานะการดำเนินงาน: </th>
                                <td> {{ $status_progress->title }} </td>
                              </tr>
                              <tr>
                                <th> ประเภทผู้ยื่น: </th>
                                <td>@foreach ($status_progress->applicant_type_list as $key => $applicant_type)@if($key!=0), @endif{{ HP::CertifyApplicantTypes()[$applicant_type->applicant_type] }}@endforeach</td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $status_progress->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $status_progress->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($status_progress->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $status_progress->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($status_progress->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

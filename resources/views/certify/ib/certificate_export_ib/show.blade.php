@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">CertificateExportIB {{ $certificateexportib->id }}</h3>
                    @can('view-'.str_slug('CertificateExportIB'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/certificate-export-i-b') }}">
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
                                  <td>{{ $certificateexportib->id }}</td>
                              </tr>
                              <tr><th> Titile </th><td> {{ $certificateexportib->titile }} </td></tr><tr><th> State </th><td> {{ $certificateexportib->state }} </td></tr><tr><th> Created By </th><td> {{ $certificateexportib->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $certificateexportib->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $certificateexportib->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $certificateexportib->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($certificateexportib->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $certificateexportib->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($certificateexportib->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

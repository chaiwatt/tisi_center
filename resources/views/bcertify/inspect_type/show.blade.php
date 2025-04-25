@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ประเภทการตรวจ (IB) {{ $inspect_type->id }}</h3>
                    @can('view-'.str_slug('inspect_type'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/inspect_type') }}">
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
                                  <td>{{ $inspect_type->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อประเภทการตรวจ (TH): </th>
                                <td> {{ $inspect_type->title }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อประเภทการตรวจ (EN): </th>
                                <td> {{ $inspect_type->title_en }} </td>
                              </tr>
                              <tr>
                                <th> คำอธิบาย: </th>
                                <td> {{ $inspect_type->description }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ: </th>
                                <td> {!! $inspect_type->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง: </th>
                                <td> {{ $inspect_type->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง: </th>
                                <td> {{ HP::DateTimeThai($inspect_type->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข: </th>
                                <td> {{ $inspect_type->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข: </th>
                                <td> {{ HP::DateTimeThai($inspect_type->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

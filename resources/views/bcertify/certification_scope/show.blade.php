@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดขอบข่ายการรับรอง {{ $certification_scope->id }}</h3>
                    @can('view-'.str_slug('certification_scope'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/certification_scope') }}">
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
                                  <td>{{ $certification_scope->id }}</td>
                              </tr>
                              <tr>
                                <th> สาขาการรับรอง: </th>
                                <td> {{ $certification_scope->certification_branch->title }} </td>
                              </tr>
                              <tr>
                                <th> ขอบข่ายการรับรอง: </th>
                                <td> {{ $certification_scope->scope_type }} </td>
                              </tr>
                              <tr>
                                <th> วันที่เริ่มใช้: </th>
                                <td> {{ HP::DateThai($certification_scope->start_date) }} </td>
                              </tr>
                              <tr>
                                <th> วันที่สิ้นสุด: </th>
                                <td> {{ HP::DateThai($certification_scope->end_date) }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $certification_scope->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $certification_scope->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($certification_scope->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $certification_scope->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($certification_scope->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

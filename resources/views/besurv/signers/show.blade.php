@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลผู้ลงนาม {{ $signer->id }}</h3>
                    @can('view-'.str_slug('signers'))
                        <a class="btn btn-success pull-right" href="{{ url('/besurv/signers') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th>ไอดี</th>
                                  <td>{{ $signer->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อผู้ลงนาม </th>
                                <td> {{ $signer->name }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อผู้ลงนาม (ENG) </th>
                                <td> {{ $signer->name_eng }} </td>
                              </tr>
                              <tr>
                                <th> ตำแหน่ง </th>
                                <td> {{ $signer->position }} </td>
                              </tr>
                              <tr>
                                <th> สังกัดกลุ่มงานหลัก </th>
                                <td> {!! $signer->DepartmentName !!} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $signer->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $signer->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($signer->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $signer->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($signer->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

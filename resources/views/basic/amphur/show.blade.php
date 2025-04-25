@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">อำเภอ {{ $amphur->getKey() }}</h3>
                    @can('view-'.str_slug('amphur'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/amphur') }}">
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
                                  <td>{{ $amphur->getKey() }}</td>
                              </tr>
                              <tr>
                                <th> รหัสอำเภอ </th>
                                <td> {{ $amphur->AMPHUR_CODE }} </td>
                              </tr>
                              <tr>
                                  <th> ชื่ออำเภอ </th>
                                  <td> {{ $amphur->AMPHUR_NAME }} </td>
                              </tr>
                              <tr>
                                  <th> จังหวัด </th>
                                  <td> {{ @$amphur->province->PROVINCE_NAME }} </td>
                              </tr>
                              <tr>
                                  <th> รหัสไปรษณีย์ </th>
                                  <td> {{ $amphur->POSTCODE }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $amphur->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ @$amphur->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($amphur->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $amphur->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($amphur->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

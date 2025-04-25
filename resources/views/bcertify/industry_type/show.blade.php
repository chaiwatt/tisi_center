@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดประเภทอุตสาหกรรม {{ $industry_type->id }}</h3>
                    @can('view-'.str_slug('industry_type'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/industry_type') }}">
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
                                  <td>{{ $industry_type->id }}</td>
                              </tr>
                              <tr>
                                <th> รหัส ISIC: </th>
                                <td> {{ $industry_type->code }} </td>
                              </tr>
                              <tr>
                                <th> ชื่ออุตสาหกรรม ISIC (TH): </th>
                                <td> {{ $industry_type->title }} </td>
                              </tr>
                              <tr>
                                <th> ชื่ออุตสาหกรรม ISIC (EN): </th>
                                <td> {{ $industry_type->title_en }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $industry_type->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $industry_type->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($industry_type->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $industry_type->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($industry_type->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

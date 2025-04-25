@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด IAF {{ $iaf->id }}</h3>
                    @can('view-'.str_slug('iaf'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/iaf') }}">
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
                                  <td>{{ $iaf->id }}</td>
                              </tr>
                              <tr>
                                <th> รหัส IAF: </th>
                                <td> {{ $iaf->code }} </td>
                              </tr>
                              <tr>
                                <th> รายละเอียดอุตสาหกรรม (TH): </th>
                                <td> {{ $iaf->title }} </td>
                              </tr>
                              <tr>
                                <th> รายละเอียดอุตสาหกรรม (EN): </th>
                                <td> {{ $iaf->title_en }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $iaf->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $iaf->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($iaf->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $iaf->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($iaf->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

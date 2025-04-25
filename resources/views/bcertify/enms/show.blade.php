@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด Enms {{ $enm->id }}</h3>
                    @can('view-'.str_slug('enms'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/enms') }}">
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
                                  <td>{{ $enm->id }}</td>
                              </tr>
                              <tr>
                                <th> Enms (TH): </th>
                                <td> {{ $enm->title }} </td>
                              </tr>
                              <tr>
                                <th> Enms (EN): </th>
                                <td> {{ $enm->title_en }} </td>
                              </tr>
                              <tr>
                                <th> รหัสประเภทอุตสาหกรรม (ISIC): </th>
                                <td>
                                    @foreach ($enm->industry_type_list as $key => $industry_type)
                                        {{ $industry_type->industry_type->code }}
                                    @endforeach
                                </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $enm->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $enm->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($enm->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $enm->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($enm->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

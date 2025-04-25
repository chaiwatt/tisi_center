@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">GHG {{ $ghg->id }}</h3>
                    @can('view-'.str_slug('ghg'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/ghg') }}">
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
                                  <td>{{ $ghg->id }}</td>
                              </tr>
                              <tr>
                                <th> สาขาและขอบข่าย (TH): </th>
                                <td> {{ $ghg->title }} </td>
                              </tr>
                              <tr>
                                <th> สาขาและขอบข่าย (EN): </th>
                                <td> {{ $ghg->title_en }} </td>
                              </tr>
                              <tr>
                                <th> เลขมาตรฐาน: </th>
                                <td> {{ $ghg->formula->title }} </td>
                              </tr>
                              <tr>
                                <th> ประเภท: </th>
                                <td> {{ HP::GHGKinds()[$ghg->kind] }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $ghg->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $ghg->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($ghg->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $ghg->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($ghg->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดเอกสารแนบ {{ $config_attach->id }}</h3>
                    @can('view-'.str_slug('config_attach'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/config_attach') }}">
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
                                  <td>{{ $config_attach->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อเอกสารแนบ: </th>
                                <td> {{ $config_attach->title }} </td>
                              </tr>
                              <tr>
                                <th> แบบฟอร์ม: </th>
                                <td>@foreach ($config_attach->form_list as $key => $form)@if($key!=0), @endif{{ HP::Forms()[$form->form] }}@endforeach</td>
                              </tr>
                              <tr>
                                <th> จำเป็นต้องแนบ: </th>
                                <td> {!! $config_attach->essential=='1'?'<span class="label label-success">ใช่</span>':'<span class="label label-danger">ไม่</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $config_attach->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $config_attach->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($config_attach->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $config_attach->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($config_attach->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">assign_product {{ $assign_product->id }}</h3>
                    @can('view-'.str_slug('assign_product'))
                        <a class="btn btn-success pull-right" href="{{ url('/assign_product/assign_product') }}">
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
                                  <td>{{ $assign_product->id }}</td>
                              </tr>
                              <tr><th> ID </th><td> {{ $assign_product->ID }} </td></tr><tr><th> State </th><td> {{ $assign_product->state }} </td></tr><tr><th> Created By </th><td> {{ $assign_product->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $assign_product->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $assign_product->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $assign_product->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($assign_product->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $assign_product->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($assign_product->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการประมาณค่าใช้จ่าย (CB) {{ $estimatedcostcb->id }}</h3>
                    @can('view-'.str_slug('estimatedcostcb'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/estimated-cost-c-b') }}">
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
                                  <td>{{ $estimatedcostcb->id }}</td>
                              </tr>
                              <tr><th> Title </th><td> {{ $estimatedcostcb->title }} </td></tr><tr><th> State </th><td> {{ $estimatedcostcb->state }} </td></tr><tr><th> Created By </th><td> {{ $estimatedcostcb->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $estimatedcostcb->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $estimatedcostcb->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $estimatedcostcb->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($estimatedcostcb->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $estimatedcostcb->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($estimatedcostcb->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

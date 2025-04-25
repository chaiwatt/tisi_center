@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">EstimatedCostIB {{ $estimatedcostib->id }}</h3>
                    @can('view-'.str_slug('EstimatedCostIB'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/estimated-cost-i-b') }}">
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
                                  <td>{{ $estimatedcostib->id }}</td>
                              </tr>
                              <tr><th> Titler </th><td> {{ $estimatedcostib->titler }} </td></tr><tr><th> State </th><td> {{ $estimatedcostib->state }} </td></tr><tr><th> Created By </th><td> {{ $estimatedcostib->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $estimatedcostib->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $estimatedcostib->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $estimatedcostib->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($estimatedcostib->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $estimatedcostib->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($estimatedcostib->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดรายการทดสอบ {{ $test_item->id }}</h3>
                    @can('view-'.str_slug('test_item'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/test_item') }}">
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
                                  <td>{{ $test_item->id }}</td>
                              </tr>
                              <tr>
                                <th> รายการทดสอบ (TH): </th>
                                <td> {{ $test_item->title }} </td>
                              </tr>
                              <tr>
                                <th> รายการทดสอบ (EN): </th>
                                <td> {{ $test_item->title_en }} </td>
                              </tr>
                              <tr>
                                <th> มาตรฐาน: </th>
                                <td> {{ $test_item->formula->title }} </td>
                              </tr>
                              <tr>
                                <th> สาขาการทดสอบ: </th>
                                <td> {{ $test_item->test_branch->title }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $test_item->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $test_item->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($test_item->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $test_item->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($test_item->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

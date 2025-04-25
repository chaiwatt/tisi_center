@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">SaveAssessmentIb {{ $saveassessmentib->id }}</h3>
                    @can('view-'.str_slug('SaveAssessmentIb'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/save-assessment-ib') }}">
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
                                  <td>{{ $saveassessmentib->id }}</td>
                              </tr>
                              <tr><th> Title </th><td> {{ $saveassessmentib->title }} </td></tr><tr><th> State </th><td> {{ $saveassessmentib->state }} </td></tr><tr><th> Created By </th><td> {{ $saveassessmentib->created_by }} </td></tr><tr><th> Updated By </th><td> {{ $saveassessmentib->updated_by }} </td></tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $saveassessmentib->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $saveassessmentib->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($saveassessmentib->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $saveassessmentib->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($saveassessmentib->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

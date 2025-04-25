@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">สาขาการสอบเทียบ {{ $calibration_branch->id }}</h3>
        @can('view-'.str_slug('calibration_branch'))
        <a class="btn btn-success pull-right" href="{{ url('/bcertify/calibration_branch') }}">
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
                <td>{{ $calibration_branch->id }}</td>
              </tr>
              <tr>
                <th> ชื่อสาขาการสอบเทียบ (TH)</th>
                <td> {{ $calibration_branch->title }} </td>
              </tr>
              <tr>
                <th> ชื่อสาขาการสอบเทียบ (EN) </th>
                <td> {{ $calibration_branch->title_en }} </td>
              </tr>
              <tr>
                <th> มาตรฐาน </th>
                <td> {{ $calibration_branch->formula->title.' ('.$calibration_branch->formula->title_en.')' }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $calibration_branch->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $calibration_branch->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($calibration_branch->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $calibration_branch->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($calibration_branch->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

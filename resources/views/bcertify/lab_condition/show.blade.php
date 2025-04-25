@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">สถานภาพห้องปฏิบัติการ {{ $lab_condition->id }}</h3>
        @can('view-'.str_slug('lab_condition'))
        <a class="btn btn-success pull-right" href="{{ url('/bcertify/lab_condition') }}">
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
                <td>{{ $lab_condition->id }}</td>
              </tr>
              <tr>
                <th> ชื่อสถานภาพ (TH) </th>
                <td> {{ $lab_condition->title }} </td>
              </tr>
              <tr>
                <th> ชื่อสถานภาพ (EN) </th>
                <td> {{ $lab_condition->title_en }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $lab_condition->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $lab_condition->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($lab_condition->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $lab_condition->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($lab_condition->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

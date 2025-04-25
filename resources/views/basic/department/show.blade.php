@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">หน่วยงาน {{ $department->id }}</h3>
        @can('view-'.str_slug('department'))
        <a class="btn btn-success pull-right" href="{{ url('/basic/department') }}">
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
                <td>{{ $department->id }}</td>
              </tr>
              <tr>
                <th> ชื่อหน่วยงาน </th>
                <td> {{ $department->title }} </td>
              </tr>
              <tr>
                <th> ที่อยู่ </th>
                <td> {{ $department->address }} </td>
              </tr>
              <tr>
                <th> จังหวัด </th>
                <td> {{ $department->province->PROVINCE_NAME }} </td>
              </tr>
              <tr>
                <th> อำเภอ/เขต </th>
                <td> {{ $department->amphur->AMPHUR_NAME }} </td>
              </tr>
              <tr>
                <th> ตำบล/แขวง </th>
                <td> {{ $department->district->DISTRICT_NAME }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $department->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $department->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($department->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $department->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($department->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">ตำบล {{ $district->getKey() }}</h3>
        @can('view-'.str_slug('district'))
        <a class="btn btn-success pull-right" href="{{ url('/basic/district') }}">
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
                <td>{{ $district->getKey() }}</td>
              </tr>
              <tr>
                <th> รหัสตำบล </th>
                <td> {{ $district->DISTRICT_CODE }} </td>
              </tr>
              <tr>
                <th> ชื่อตำบล </th>
                <td> {{ $district->DISTRICT_NAME }} </td>
              </tr>
              <tr>
                <th> อำเภอ </th>
                <td> {{ $district->amphur->AMPHUR_NAME }} </td>
              </tr>
              <tr>
                <th> จังหวัด </th>
                <td> {{ $district->province->PROVINCE_NAME }} </td>
              </tr>

              <tr>
                <th> สถานะ </th>
                <td> {!! $district->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $district->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($district->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $district->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($district->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

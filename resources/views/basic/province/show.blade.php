@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">province {{ $province->getKey() }}</h3>
        @can('view-'.str_slug('province'))
        <a class="btn btn-success pull-right" href="{{ url('/basic/province') }}">
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
                <td>{{ $province->getKey() }}</td>
              </tr>
              <tr>
                <th> รหัสจังหวัด </th>
                <td> {{ $province->PROVINCE_CODE }} </td>
              </tr>
              <tr>
                <th> ชื่อจังหวัด </th>
                <td> {{ $province->PROVINCE_NAME }} </td>
              </tr>
              <tr>
                <th> ภาค </th>
                <td> {{ $province->geography->GEO_NAME }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $province->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ @$province->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($province->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ @$province->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($province->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

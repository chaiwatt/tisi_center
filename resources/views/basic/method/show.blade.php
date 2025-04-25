@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">วิธีจัดทำ {{ $method->id }}</h3>
        @can('view-'.str_slug('method'))
        <a class="btn btn-success pull-right" href="{{ url('/basic/method') }}">
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
                <td>{{ $method->id }}</td>
              </tr>
              <tr>
                <th> วิธีจัดทำ </th>
                <td> {{ $method->title }} </td>
              </tr>
              <tr>
                <th> รายละเอียดย่อย </th>
                <td> {{ $method->details }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $method->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $method->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($method->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $method->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($method->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

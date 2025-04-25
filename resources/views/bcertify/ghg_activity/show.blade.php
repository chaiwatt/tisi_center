@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">รายละเอียดกิจกรรมของ GHG {{ $ghg_activity->id }}</h3>
        @can('view-'.str_slug('ghg_activity'))
        <a class="btn btn-success pull-right" href="{{ url('/bcertify/ghg_activity') }}">
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
                <td>{{ $ghg_activity->id }}</td>
              </tr>
              <tr>
                <th> กิจกรรม (TH): </th>
                <td> {{ $ghg_activity->title }} </td>
              </tr>
              <tr>
                <th> กิจกรรม (EN): </th>
                <td> {{ $ghg_activity->title_en }} </td>
              </tr>
              <tr>
                <th> ประเภท: </th>
                <td> {{ HP::GHGKinds()[$ghg_activity->kind] }} </td>
              </tr>
              <tr>
                <th> สาขาและขอบข่าย: </th>
                <td> {{ $ghg_activity->ghg->title }} </td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $ghg_activity->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $ghg_activity->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($ghg_activity->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $ghg_activity->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($ghg_activity->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

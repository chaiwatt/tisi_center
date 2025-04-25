@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">รายละเอียดหน่วยงานตรวจ {{ $inspector->id }}</h3>
        @can('view-'.str_slug('inspector'))
        <a class="btn btn-success pull-right" href="{{ url('/besurv/inspector') }}">
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
                <td>{{ $inspector->id }}</td>
              </tr>
              <tr>
                <th> ชื่อหน่วยงาน </th>
                <td> {{ $inspector->title }} </td>
              </tr>
              <tr>
                <th> ประเภทหน่วยงานตรวจ </th>
                <td>@foreach ($inspector->inspector_type_list as $key => $inspector_types)@if($key!=0), @endif{{ HP::InspectorTypes()[$inspector_types->inspector_type_id] }}@endforeach</td>
              </tr>
              <tr>
                <th> สถานะ </th>
                <td> {!! $inspector->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $inspector->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($inspector->created_at) }} </td>
              </tr>
              <tr>
                <th> ผู้แก้ไข </th>
                <td> {{ $inspector->updatedName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่แก้ไข </th>
                <td> {{ HP::DateTimeThai($inspector->updated_at) }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

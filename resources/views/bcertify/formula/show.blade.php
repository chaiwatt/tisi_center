@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดมาตรฐาน {{ $formula->id }}</h3>
                    @can('view-'.str_slug('formula'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/formula') }}">
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
                                  <td>{{ $formula->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อมาตรฐาน (TH) </th>
                                <td> {{ $formula->title }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อมาตรฐาน (EN) </th>
                                <td> {{ $formula->title_en }} </td>
                              </tr>
                              <tr>
                                <th> ประเภทผู้ยื่น </th>
                                <td> {{ HP::CertifyApplicantTypes()[$formula->applicant_type] }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $formula->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $formula->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($formula->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $formula->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> ข้อกำหนดมาตรฐาน (TH) </th>
                                <td> {{ $formula->condition_th ?? null }} </td>
                              </tr>
                              <tr>
                                <th> ข้อกำหนดมาตรฐาน (EN) </th>
                                <td> {{ $formula->condition_en ?? null }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($formula->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

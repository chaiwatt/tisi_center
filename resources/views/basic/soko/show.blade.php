@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลผู้ประกอบการ (สก.) {{ $soko->id }}</h3>
                    @can('view-'.str_slug('soko'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/soko') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr><th> ประเภทผู้ประกอบการ </th>
                                  <td> {{ $soko->trader_type }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อผู้ประกอบการ </th>
                                <td> {{ $soko->trader_operater_name }} </td>
                              </tr>
                              <tr>
                                <th> เลขประจำตัวผู้เสียภาษี </th>
                                <td> {{ $soko->trader_id }} </td>
                              </tr>
                              <tr>
                                <th> วันที่จดทะเบียนนิติบุคคล </th>
                                <td> {{ HP::DateThaiFull($soko->trader_id_register) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้มีอำนาจลงนาม </th>
                                <td> {{ $soko->trader_boss }} </td>
                              </tr>
                              <tr>
                                <th> ที่ตั้งสำนักงาน </th>
                                <td> {{ $soko->trader_address }}
                                     @if(!empty($soko->trader_address_soi)) ซอย{{ $soko->trader_address_soi }} @endif
                                     @if(!empty($soko->trader_address_road)) ถนน{{ $soko->trader_address_road }} @endif
                                     @if(!empty($soko->trader_address_moo)) หมู่{{ $soko->trader_address_moo }} @endif
                                     {{ $soko->trader_address_tumbol }}
                                     {{ $soko->trader_address_amphur }}
                                     {{ $soko->trader_provinceID }}
                                     {{ $soko->trader_address_poscode }}
                                </td>
                              </tr>
                              <tr>
                                <th> เบอร์โทรศัพท์ </th>
                                <td> {{ $soko->trader_phone }} </td>
                              </tr>
                              <tr>
                                <th> เบอร์มือถือ </th>
                                <td> {{ $soko->trader_mobile }} </td>
                              </tr>
                              <tr>
                              <tr>
                                <th> อีเมล </th>
                                <td> {{ $soko->agent_email }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($soko->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($soko->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

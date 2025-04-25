@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลผู้ประกอบการ {{ $trader->id }}</h3>
                    @can('view-'.str_slug('trader'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/trader') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr><th> ประเภทผู้ประกอบการ </th>
                                  <td> {{ $trader->trader_type }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อผู้ประกอบการ </th>
                                <td> {{ $trader->trader_operater_name }} </td>
                              </tr>
                              <tr>
                                <th> เลขประจำตัวผู้เสียภาษี </th>
                                <td> {{ $trader->trader_id }} </td>
                              </tr>
                              <tr>
                                <th> วันที่จดทะเบียนนิติบุคคล </th>
                                <td> {{ HP::DateThaiFull($trader->trader_id_register) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้มีอำนาจลงนาม </th>
                                <td> {{ $trader->trader_boss }} </td>
                              </tr>
                              <tr>
                                <th> ที่ตั้งสำนักงาน </th>
                                <td> {{ $trader->trader_address }}
                                     @if(!empty($trader->trader_address_soi)) ซอย{{ $trader->trader_address_soi }} @endif
                                     @if(!empty($trader->trader_address_road)) ถนน{{ $trader->trader_address_road }} @endif
                                     @if(!empty($trader->trader_address_moo)) หมู่{{ $trader->trader_address_moo }} @endif
                                     {{ $trader->trader_address_tumbol }}
                                     {{ $trader->trader_address_amphur }}
                                     {{ $trader->trader_provinceID }}
                                     {{ $trader->trader_address_poscode }}
                                </td>
                              </tr>
                              <tr>
                                <th> เบอร์โทรศัพท์ </th>
                                <td> {{ $trader->trader_phone }} </td>
                              </tr>
                              <tr>
                                <th> เบอร์มือถือ </th>
                                <td> {{ $trader->trader_mobile }} </td>
                              </tr>
                              <tr>
                              <tr>
                                <th> อีเมล </th>
                                <td> {{ $trader->agent_email }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($trader->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($trader->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

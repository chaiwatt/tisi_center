@extends('layouts.master')
@push('css')

    <style>

        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin: 0 0 1rem 0;
            }

            tr:nth-child(odd) {
                background: #eee;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                /* Now like a table header */
                /*position: absolute;*/
                /* Top/left values mimic padding */
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }

            /*
            Label the data
        You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
            */
            /*td:nth-of-type(1):before { content: "Column Name"; }*/

        }

        fieldset {
            padding: 20px;
        }
    </style>

@endpush
@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left"> รายละเอียดระบบตั้งค่ารายการผลทดสอบผลิตภัณฑ์ </h3>
                        <a class="btn btn-success pull-right" href="{{ url('/resurv/results_product') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    <div class="clearfix" style="margin-bottom: 20px;"></div>

                    <div class="table-responsive" >
                        <table class="table table" >
                            <tbody>
                              <tr>
                                  <th class="col-sm-2"> ID : </th>
                                  <td class="col-sm-12"> {{ $results_product->id }} </td></tr>
                              <tr>
                              <tr>
                                  <th class="col-sm-2"> มาตรฐาน : </th>
                                  <td class="col-sm-12"> {{'มอก. '.@$results_product->tis_standard.' '.@$results_product->tis->tb3_TisThainame }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> รายการผลทดสอบ : </th>
                                  <td class="col-sm-12">
                                        {{-- <div class="col-sm-6"><b>ชื่อรายการผลทดสอบ</b></div>
                                        <div class="col-sm-6"><b>ประเภทข้อมูล</b></div>
                                      @foreach ($results_product->detail as $name)
                                        <div class="col-sm-6"><span>{{ $name->name_result  }}</span></div>
                                        <div class="col-sm-6"><span>{{ $name->type_result  }}</span></div>
                                       
                                      @endforeach --}}
                                        @if(count($results_product->detail) > 0)
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                
                                                    </thead>
                                                    <tbody>
                                                        <tr class="text-center">
                                                            <td style="width: 60%;"><b>ชื่อรายการผลทดสอบ</b></td>
                                                            <td style="width: 40%;"><b>ประเภทข้อมูล</b></td>
                                                        </tr>  
                                                        @foreach ($results_product->detail as $name)   
                                                        <tr>
                                                            <td style="width: 60%;">{{ $name->name_result  }}</td>
                                                            <td style="width: 40%;">{{ $name->type_result  }}</td>
                                                        </tr>
                                                        @endforeach 
                                                    </tbody>
                                                </table>
                                        @endif
                                  </td>
                              </tr>
                                <th class="col-sm-2"> สถานะ : </th>
                                <td class="col-sm-12"> {!! $results_product->status=='1'?'<span class="label label-success">ใช้งาน</span>':'<span class="label label-danger">ยกเลิก</span>' !!} </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2"> ผู้สร้าง : </th>
                                <td class="col-sm-12"> {{ $results_product->user_create }} </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2"> วันเวลาที่สร้าง : </th>
                                <td class="col-sm-12"> {{ HP::DateTimeThai($results_product->created_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

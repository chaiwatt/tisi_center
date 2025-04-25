@extends('layouts.master')

@push('css')

    <style>

        .label-filter{
            margin-top: 7px;
        }

        /* แนวตั้ง */
        .verticalTableHeader {
            text-align:left;
            white-space:nowrap;
            g-origin:50% 50%;
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            width: 20px;
            padding-top: 20px;
        }
        .verticalTableHeader p {
            margin:0 -100% ;
            display:inline-block;
        }
        .verticalTableHeader p:before{
            content:'';
            width:0;
            padding-top:150%;/* takes width as reference, + 10% for faking some extra padding */
            display:inline-block;
            vertical-align:middle;
        }
        table{
            text-align:center;
            table-layout : fixed;
            width:100%
        }

        .verticalTableHeader p{
          font-size: 80%;
        }

        /* แนวนอน */
        .horizontalTableHeader div{
          font-size:70%;
          white-space: nowrap;
        }
        .horizontalTableContent div{
          font-size:75%;
          white-space: nowrap;
        }
        .content-number{
          font-size:75%;
          white-space: nowrap;
          text-align: right;
        }


        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media
        only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px)  {

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
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: bold; font-weight: ;
        }
        .wrapper-label {
            font-size: 26px;
            color: #0a0a0a;
        }
        .wrapper-label-small {
            color: #6c757d;
        }
        .card-collaps {
            border: 1px solid;
            margin-top: 70px;
        }

        th {
            text-align: center;
        }
        td {
            text-align: center;
        }
        .panel .panel-body {
            padding: 10px !important;
        }

    </style>

@endpush

@section('content')

    @php
      $tis_list = HP::TisList();
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานแผน - ผล การปฏิบัติงาน (งาน, เงิน)</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/report_performance', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อมอก.']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group  pull-left">
                                    <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                                </div>
                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div>
                            </div><!-- /.col-lg-1 -->
                            <div class="col-lg-5">
                                <div class="form-group col-md-7">
                                    <div class="col-md-12">
                                        {!! Form::select('filter_status', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                                        </div>
                                    </div>
                            </div><!-- /.col-lg-5 -->
                        </div><!-- /.row -->

                    	<div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                         {!! Form::label('filter_standard_format', 'การกำหนด', ['class' => 'col-md-4 control-label label-filter']) !!}
                                          <div class="col-md-8">
                                              {!! Form::select('filter_standard_format', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-']); !!}
                                          </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_start_quarter', 'ไตรมาส', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                          <div class="input-group">
                                            {!! Form::select('filter_start_quarter', ['1' => 'ไตรมาส 1', '2' => 'ไตรมาส 2', '3' => 'ไตรมาส 3', '4' => 'ไตรมาส 4'], null, ['class' => 'form-control', 'placeholder' => '-เลือก-']); !!}
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            {!! Form::select('filter_end_quarter', ['1' => 'ไตรมาส 1', '2' => 'ไตรมาส 2', '3' => 'ไตรมาส 3', '4' => 'ไตรมาส 4'], null, ['class' => 'form-control', 'id' => 'filter_end_quarter', 'placeholder' => '-เลือก-']); !!}
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_start_month', 'เดือน', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                          <div class="input-group">
                                            {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder' => '-เลือก-']); !!}
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'id' => 'filter_end_month', 'placeholder' => '-เลือก-']); !!}
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_operation', 'กิจกรรม', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_operation', App\Models\Basic\StatusOperation::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือก-']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_method', 'วิธีจัดทำ', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_method', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือก-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_made', 'จัดทำโดย', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_made', HP::Mades(), null, ['class' => 'form-control', 'placeholder' => '-เลือก สมอ., SDO-']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_plan', 'ปีที่เสนอเข้าแผน', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_plan', HP::Years(), null, ['class' => 'form-control', 'placeholder' => '-เลือกปีที่เสนอเข้าแผน-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_year', 'ปี มอก. ', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_year', HP::Years(), null, ['class' => 'form-control', 'placeholder'=>'-ปี มอก.-']); !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                        <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $items->total() .' รายการ'}}</span>
                    <div class="pull-right">
                        {!! Form::model($filter, ['url' => '/tis/report_performance/export_excel', 'method' => 'get', 'id' => 'myFilter']) !!}
                            {!! Form::select('filter_year', HP::Years(), null, ['class' => 'form-control hidden', 'placeholder'=>'-ปี-']); !!}
                            {!! Form::select('filter_plan', HP::Years(), null, ['class' => 'form-control hidden', 'placeholder'=>'-ปีที่เสนอเข้าแผน-']); !!}
                            {!! Form::select('filter_status', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกสถานะ-']); !!}
                            {!! Form::select('filter_standard_format', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-ทั้งหมด-']); !!}
                            {!! Form::select('filter_start_quarter', ['1' => 'ไตรมาส 1', '2' => 'ไตรมาส 2', '3' => 'ไตรมาส 3', '4' => 'ไตรมาส 4'], null, ['class' => 'form-control hidden', 'placeholder' => '-เลือก-']); !!}
                            {!! Form::select('filter_end_quarter', ['1' => 'ไตรมาส 1', '2' => 'ไตรมาส 2', '3' => 'ไตรมาส 3', '4' => 'ไตรมาส 4'], null, ['class' => 'form-control hidden', 'placeholder' => '-เลือก-']); !!}
                            {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control hidden', 'placeholder' => '-เลือก-']); !!}
                            {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control hidden', 'placeholder' => '-เลือก-']); !!}
                            {!! Form::select('filter_operation', App\Models\Basic\StatusOperation::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder' => '-เลือก-']); !!}
                            {!! Form::select('filter_methods', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกวิธีจัดทำ-']); !!}
                            {!! Form::select('filter_made', HP::Mades(), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกวิธีจัดทำ-']); !!}
                            <button type="submit" formtarget="_blank" class="btn btn-success btn-sm waves-effect waves-light">
                                Export Excel
                            </button>
                        {!! Form::close() !!}
                    </div>
                    {{-- <div class="pull-right">
                        <button type="button" onclick="$('#myFilter').prop('action', '{{ url('tis/report_performance/export_excel') }}'); this.form.submit();" class="btn btn-success btn-sm waves-effect waves-light" formtarget="_blank">
                            <i class="mdi mdi-file-excel"></i>
                                Export Excel
                        </button>
                    </div> --}}
                    <div class="clearfix"></div>

                       <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <caption class="text-center">
                                <label class="wrapper-label">รายงานแผน–ผลการปฎิบัติงาน (งาน,เงิน)</label><br>
                                <label class="wrapper-label-small">ข้อมูล ณ วันที่ {{HP::DateTimeFullThai(date('Y-m-d H:i:s'))}}</label>
                            </caption>
                              <thead>
                                <tr style="background-color: azure">
                                  <th class="verticalTableHeader" rowspan="3"><p>ลำดับ</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>ปีที่เสนอเข้าแผน</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>หมายเลขมอก.</p></th>
                                  <th rowspan="3" style="width: 100px;" class="horizontalTableHeader">
                                    <div>ชื่อมาตรฐานภาษาไทย<br/>ภาษาอังกฤษ</div>
                                  </th>
                                  <th class="verticalTableHeader" rowspan="3"><p>กว./อนุ กว.</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>ประเภท มอก.</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>สถานะ (บังคับ/ทั่วไป)</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>สถานะ (ใหม่/ทบทวน)</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>สถานภาพ มอก.</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>สาขา</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>S-Curve</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>reference</p></th>
                                  <th class="verticalTableHeader" rowspan="3"><p>เลขาฯ</p></th>
                                  <th colspan="2" rowspan="2" style="width:100px;" class="horizontalTableHeader"><div>แผน/ผล</div></th>
                                  <th colspan="4" class="horizontalTableHeader"><div>ไตรมาสที่ 1</div></th>
                                  <th colspan="4" class="horizontalTableHeader"><div>ไตรมาสที่ 2</div></th>
                                  <th colspan="4" class="horizontalTableHeader"><div>ไตรมาสที่ 3</div></th>
                                  <th colspan="4" class="horizontalTableHeader"><div>ไตรมาสที่ 4</div></th>
                                  <th rowspan="2" style="width: 60px;" class="horizontalTableHeader"><div><u>รวมทั้งหมด</u></div></th>
                                  <th rowspan="2" style="width: 60px;" class="horizontalTableHeader"><div>ตัวอย่าง/<br/>ค่าทดสอบ</div></th>
                              </tr>
                              <tr style="background-color: azure">
                                <th class="horizontalTableHeader"><div>ต.ค.</div></th>
                                <th class="horizontalTableHeader"><div>พ.ย.</div></th>
                                <th class="horizontalTableHeader"><div>ธ.ค.</div></th>
                                <th class="horizontalTableHeader">
                                  <div>รวม</div>
                                </th>

                                <th class="horizontalTableHeader"><div>ม.ค.</div></th>
                                <th class="horizontalTableHeader"><div>ก.พ.</div></th>
                                <th class="horizontalTableHeader"><div>มี.ค.</div></th>
                                <th class="horizontalTableHeader">
                                  <div>รวม</div>
                                </th>

                                <th class="horizontalTableHeader"><div>เม.ย.</div></th>
                                <th class="horizontalTableHeader"><div>พ.ค.</div></th>
                                <th class="horizontalTableHeader"><div>มิ.ย.</div></th>
                                <th class="horizontalTableHeader">
                                  <div>รวม</div>
                                </th>

                                <th class="horizontalTableHeader"><div>ก.ค.</div></th>
                                <th class="horizontalTableHeader"><div>ส.ค.</div></th>
                                <th class="horizontalTableHeader"><div>ก.ย.</div></th>
                                <th class="horizontalTableHeader">
                                  <div>รวม</div>
                                </th>

                              </tr>
                              <tr style="background-color: azure">
                                <th class="horizontalTableHeader"><div>แผน/ผล</div></th>
                                <th class="horizontalTableHeader"><div>ค่าเบี้ย/<br/>อาหาร</div></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="horizontalTableHeader">
                                  <div><u style="font-size:70%">ครั้งที่ประชุม</u></div>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="horizontalTableHeader">
                                  <div><u style="font-size:70%">ครั้งที่ประชุม</u></div>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="horizontalTableHeader">
                                  <div><u style="font-size:70%">ครั้งที่ประชุม</u></div>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="horizontalTableHeader">
                                  <div><u style="font-size:70%">ครั้งที่ประชุม</u></div>
                                </th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>
                            @foreach ($items as $key => $item)

                              @php

                                //แผนเงินเบี้ยประชุม แผนเงินค่าอาหาร, ผลเงินเบี้ยประชุม ผลเงินค่าอาหาร
                                $plan_allowances = $plan_foods = $result_allowances = $result_foods =  [1=>[],
                                                                                                    2=>[],
                                                                                                    3=>[],
                                                                                                    4=>[],
                                                                                                    5=>[],
                                                                                                    6=>[],
                                                                                                    7=>[],
                                                                                                    8=>[],
                                                                                                    9=>[],
                                                                                                    10=>[],
                                                                                                    11=>[],
                                                                                                    12=>[]
                                                                                                   ];
                                //ผลรวมตามไตรมาส
                                $plan_allowance = $plan_food = $result_allowance = $result_food = [1=>0, 2=>0, 3=>0, 4=>0];

                                //แผน
                                $plans = [];
                                foreach ($item->set_standard_plan as $plan) {
                                  $plans[$plan->quarter][] = $plan;
                                }

                                //ผล
                                $results = [];
                                foreach ($item->set_standard_result as $result) {
                                  $results[$result->quarter][] = $result;
                                }
                              @endphp

                              <tr>
                                <td rowspan="8">{{ $key+1 }}</td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->plan_year }}</p></td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->tis_no.($item->start_year?' - '.$item->start_year:'') }}</p></td>
                                <td rowspan="8">{{ @$item->title }}</td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->appoint->board_position }}</p></td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->standard_type->title }}</p></td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->standard_format->title }}</p></td>
                                <td rowspan="8" class="verticalTableHeader">
                                  <p>
                                    @if($item->review_status=='1')
                                      กำหนดใหม่
                                    @elseif($item->review_status=='2')
                                      ทบทวน
                                    @endif
                                  </p>
                                </td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->method->title." ".$item->MethodDetailName }}</p></td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->product_group->title }}</p></td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ @$item->industry_target->title }}</p></td>
                                <td rowspan="8" class="verticalTableHeader">
                                  @php
                                    $decode_refers = !empty($item->refer)?json_decode($item->refer):'';
                                  @endphp
                                  @if($decode_refers)
                                  <p>
                                    @foreach ($decode_refers as $key => $refer)@if($key!=0),@endif {{ $refer }}@endforeach
                                  </p>
                                    @endif
                                </td>
                                <td rowspan="8" class="verticalTableHeader"><p>{{ $item->secretary }}</p></td>
                                <td colspan="2">แผน</td>

                                @php
                                  //แผน ไตรมาส 1
                                  $operations = [10=>[], 11=>[], 12=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 10 11 12
                                  $meeting1 = '';
                                  if(array_key_exists(1, $plans)){

                                    foreach ($plans[1] as $key_plan => $plan) {

                                      $status_operation = $plan->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $plan->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $plan->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 10){//ถ้าคาบเกี่ยวเดือนที่ 10
                                        $operations[10][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 11 || ($startmonth == 10 && $endmonth >= 11)){//ถ้าคาบเกี่ยวเดือนที่ 11
                                        $operations[11][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 12){//ถ้าคาบเกี่ยวเดือนที่ 12
                                        $operations[12][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 10){//ถ้ามีกิจกรรมเดือน 1 ด้วย

                                        $plan_allowances[10][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[10][] = $plan->sum_attendees;

                                      }elseif($startmonth == 11 || ($startmonth == 10 && $endmonth >= 11)){//ถ้ามีกิจกรรมเดือน 2 ด้วย

                                        $plan_allowances[11][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[11][] = $plan->sum_attendees;

                                      }elseif($endmonth == 12){//ถ้ามีกิจกรรมเดือน 3 ด้วย

                                        $plan_allowances[12][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[12][] = $plan->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting1++;
                                      }

                                    }

                                  }
                                @endphp

                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[10]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[11]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[12]) }}</div></td>
                                <td class="content-number">{{ $meeting1 }}</td>

                                @php
                                  //แผน ไตรมาส 2
                                  $operations = [1=>[], 2=>[], 3=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 1 2 3
                                  $meeting2 = '';
                                  if(array_key_exists(2, $plans)){

                                    foreach ($plans[2] as $key_plan => $plan) {

                                      $status_operation = $plan->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $plan->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $plan->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 1){//ถ้าคาบเกี่ยวเดือนที่ 1
                                        $operations[1][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 2 || ($startmonth == 1 && $endmonth >= 2)){//ถ้าคาบเกี่ยวเดือนที่ 2
                                        $operations[2][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 3){//ถ้าคาบเกี่ยวเดือนที่ 3
                                        $operations[3][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 1){//ถ้ามีกิจกรรมเดือน 1 ด้วย

                                        $plan_allowances[1][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[1][] = $plan->sum_attendees;

                                      }elseif($startmonth == 2 || ($startmonth == 1 && $endmonth >= 2)){//ถ้ามีกิจกรรมเดือน 2 ด้วย

                                        $plan_allowances[2][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[2][] = $plan->sum_attendees;

                                      }elseif($endmonth == 3){//ถ้ามีกิจกรรมเดือน 3 ด้วย

                                        $plan_allowances[3][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[3][] = $plan->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting2++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[1]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[2]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[3]) }}</div></td>
                                <td class="content-number">{{ $meeting2 }}</td>

                                @php
                                  //แผน ไตรมาส 3
                                  $operations = [4=>[], 5=>[], 6=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 4 5 6
                                  $meeting3 = '';
                                  if(array_key_exists(3, $plans)){

                                    foreach ($plans[3] as $key_plan => $plan) {

                                      $status_operation = $plan->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $plan->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $plan->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 4){//ถ้าคาบเกี่ยวเดือนที่ 4
                                        $operations[4][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 5 || ($startmonth == 4 && $endmonth >= 5)){//ถ้าคาบเกี่ยวเดือนที่ 5
                                        $operations[5][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 6){//ถ้าคาบเกี่ยวเดือนที่ 6
                                        $operations[6][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 4){//ถ้ามีกิจกรรมเดือน 4 ด้วย

                                        $plan_allowances[4][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[4][] = $plan->sum_attendees;

                                      }elseif($startmonth == 5 || ($startmonth == 4 && $endmonth >= 5)){//ถ้ามีกิจกรรมเดือน 5 ด้วย

                                        $plan_allowances[5][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[5][] = $plan->sum_attendees;

                                      }elseif($endmonth == 6){//ถ้ามีกิจกรรมเดือน 6 ด้วย

                                        $plan_allowances[6][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[6][] = $plan->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting3++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[4]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[5]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[6]) }}</div></td>
                                <td class="content-number">{{ $meeting3 }}</td>

                                @php
                                  //แผน ไตรมาส 4
                                  $operations = [7=>[], 8=>[], 9=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 7 8 9
                                  $meeting4 = '';
                                  if(array_key_exists(4, $plans)){

                                    foreach ($plans[4] as $key_plan => $plan) {

                                      $status_operation = $plan->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $plan->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $plan->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 7){//ถ้าคาบเกี่ยวเดือนที่ 7
                                        $operations[7][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 8 || ($startmonth == 7 && $endmonth >= 8)){//ถ้าคาบเกี่ยวเดือนที่ 8
                                        $operations[8][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 9){//ถ้าคาบเกี่ยวเดือนที่ 9
                                        $operations[9][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 7){//ถ้ามีกิจกรรมเดือน 7 ด้วย

                                        $plan_allowances[7][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[7][] = $plan->sum_attendees;

                                      }elseif($startmonth == 8 || ($startmonth == 7 && $endmonth >= 8)){//ถ้ามีกิจกรรมเดือน 8 ด้วย

                                        $plan_allowances[8][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[8][] = $plan->sum_attendees;

                                      }elseif($endmonth == 9){//ถ้ามีกิจกรรมเดือน 9 ด้วย

                                        $plan_allowances[9][] = $plan->sum_g + $plan->sum_subg;
                                        $plan_foods[9][] = $plan->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting4++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[7]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[8]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[9]) }}</div></td>
                                <td class="content-number">{{ $meeting4 }}</td>

                                <td class="content-number">{{ (int)$meeting1+(int)$meeting2+(int)$meeting3+(int)$meeting4 }}</td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="2">ผล</td>

                                @php
                                  //ผล ไตรมาส 1
                                  $operations = [10=>[], 11=>[], 12=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 10 11 12
                                  $meeting1 = '';
                                  if(array_key_exists(1, $results)){

                                    foreach ($results[1] as $key_result => $result) {

                                      $status_operation = $result->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $result->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $result->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 10){//ถ้าคาบเกี่ยวเดือนที่ 10
                                        $operations[10][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 11 || ($startmonth == 10 && $endmonth >= 11)){//ถ้าคาบเกี่ยวเดือนที่ 11
                                        $operations[11][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 12){//ถ้าคาบเกี่ยวเดือนที่ 12
                                        $operations[12][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 10){//ถ้ามีกิจกรรมเดือน 10 ด้วย

                                        $result_allowances[10][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[10][] = $result->sum_attendees;

                                      }elseif($startmonth == 11 || ($startmonth == 10 && $endmonth >= 11)){//ถ้ามีกิจกรรมเดือน 11 ด้วย

                                        $result_allowances[11][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[11][] = $result->sum_attendees;

                                      }elseif($endmonth == 12){//ถ้ามีกิจกรรมเดือน 12 ด้วย

                                        $result_allowances[12][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[12][] = $result->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting1++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[10]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[11]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[12]) }}</div></td>
                                <td class="content-number">{{ $meeting1 }}</td>

                                @php
                                  //ผล ไตรมาส 2
                                  $operations = [1=>[], 2=>[], 3=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 1 2 3
                                  $meeting2 = '';
                                  if(array_key_exists(2, $results)){

                                    foreach ($results[2] as $key_result => $result) {

                                      $status_operation = $result->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $result->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $result->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 1){//ถ้าคาบเกี่ยวเดือนที่ 1
                                        $operations[1][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 2 || ($startmonth == 1 && $endmonth >= 2)){//ถ้าคาบเกี่ยวเดือนที่ 2
                                        $operations[2][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 3){//ถ้าคาบเกี่ยวเดือนที่ 3
                                        $operations[3][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 1){//ถ้ามีกิจกรรมเดือน 1 ด้วย

                                        $result_allowances[1][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[1][] = $result->sum_attendees;

                                      }elseif($startmonth == 2 || ($startmonth == 1 && $endmonth >= 2)){//ถ้ามีกิจกรรมเดือน 2 ด้วย

                                        $result_allowances[2][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[2][] = $result->sum_attendees;

                                      }elseif($endmonth == 3){//ถ้ามีกิจกรรมเดือน 3 ด้วย

                                        $result_allowances[3][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[3][] = $result->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting2++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[1]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[2]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[3]) }}</div></td>
                                <td class="content-number">{{ $meeting2 }}</td>

                                @php
                                  //ผล ไตรมาส 3
                                  $operations = [4=>[], 5=>[], 6=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 4 5 6
                                  $meeting3 = '';
                                  if(array_key_exists(3, $results)){

                                    foreach ($results[3] as $key_result => $result) {

                                      $status_operation = $result->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $result->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $result->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 4){//ถ้าคาบเกี่ยวเดือนที่ 4
                                        $operations[4][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 5 || ($startmonth == 4 && $endmonth >= 5)){//ถ้าคาบเกี่ยวเดือนที่ 5
                                        $operations[5][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 6){//ถ้าคาบเกี่ยวเดือนที่ 6
                                        $operations[6][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 4){//ถ้ามีกิจกรรมเดือน 4 ด้วย

                                        $result_allowances[4][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[4][] = $result->sum_attendees;

                                      }elseif($startmonth == 5 || ($startmonth == 4 && $endmonth >= 5)){//ถ้ามีกิจกรรมเดือน 5 ด้วย

                                        $result_allowances[5][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[5][] = $result->sum_attendees;

                                      }elseif($endmonth == 6){//ถ้ามีกิจกรรมเดือน 6 ด้วย

                                        $result_allowances[6][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[6][] = $result->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting3++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[4]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[5]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[6]) }}</div></td>
                                <td class="content-number">{{ $meeting3 }}</td>

                                @php
                                  //ผล ไตรมาส 4
                                  $operations = [7=>[], 8=>[], 9=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 7 8 9
                                  $meeting4 = '';
                                  if(array_key_exists(4, $results)){

                                    foreach ($results[4] as $key_plan => $result) {

                                      $status_operation = $result->status_operation;//สถานะการดำเนินงาน

                                      $startmonth = explode('-', $result->startdate)[1];//เดือนเริ่ม
                                      $endmonth = explode('-', $result->enddate)[1];//เดือนสิ้นสุด

                                      //เก็บอักษรย่อของกิจกรรม
                                      if($startmonth == 7){//ถ้าคาบเกี่ยวเดือนที่ 7
                                        $operations[7][] = $status_operation->acronym;
                                      }

                                      if($startmonth == 8 || ($startmonth == 7 && $endmonth >= 8)){//ถ้าคาบเกี่ยวเดือนที่ 8
                                        $operations[8][] = $status_operation->acronym;
                                      }

                                      if($endmonth == 9){//ถ้าคาบเกี่ยวเดือนที่ 9
                                        $operations[9][] = $status_operation->acronym;
                                      }

                                      //เก็บค่าอาหาร ค่าเบี้ยประชุม
                                      if($startmonth == 7){//ถ้ามีกิจกรรมเดือน 4 ด้วย

                                        $result_allowances[7][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[7][] = $result->sum_attendees;

                                      }elseif($startmonth == 8 || ($startmonth == 7 && $endmonth >= 8)){//ถ้ามีกิจกรรมเดือน 5 ด้วย

                                        $result_allowances[8][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[8][] = $result->sum_attendees;

                                      }elseif($endmonth == 9){//ถ้ามีกิจกรรมเดือน 6 ด้วย

                                        $result_allowances[9][] = $result->sum_g + $result->sum_subg;
                                        $result_foods[9][] = $result->sum_attendees;

                                      }

                                      //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
                                      if($status_operation->budget_state=='1'){
                                        $meeting4++;
                                      }

                                    }

                                  }
                                @endphp
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[7]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[8]) }}</div></td>
                                <td class="horizontalTableContent"><div>{{ implode(', ', $operations[9]) }}</div></td>
                                <td class="content-number">{{ $meeting4 }}</td>

                                <td class="content-number">{{ (int)$meeting1+(int)$meeting2+(int)$meeting3+(int)$meeting4 }}</td>
                                <td></td>
                              </tr>
                              <tr>
                                <td rowspan="2">แผนเงิน</td>
                                <td class="horizontalTableContent"><div>เบี้ยประชุม</div></td>

                                <!-- แผนเบี้ยประชุม ไตรมาสที่ 1 -->
                                <td class="content-number">
                                  {{ count($plan_allowances[10])>0 ? number_format(array_sum($plan_allowances[10])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[11])>0 ? number_format(array_sum($plan_allowances[11])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[12])>0 ? number_format(array_sum($plan_allowances[12])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_allowances[10])>0 || count($plan_allowances[11])>0 || count($plan_allowances[12])>0)
                                    @php $plan_allowance[1] = array_sum($plan_allowances[10]) + array_sum($plan_allowances[11]) + array_sum($plan_allowances[12]); @endphp
                                    {{ number_format($plan_allowance[1]) }}
                                  @endif
                                </td>

                                <!-- แผนเบี้ยประชุม ไตรมาสที่ 2 -->
                                <td class="content-number">
                                  {{ count($plan_allowances[1])>0 ? number_format(array_sum($plan_allowances[1])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[2])>0 ? number_format(array_sum($plan_allowances[2])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[3])>0 ? number_format(array_sum($plan_allowances[3])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_allowances[1])>0 || count($plan_allowances[2])>0 || count($plan_allowances[3])>0)
                                    @php $plan_allowance[2] = array_sum($plan_allowances[1]) + array_sum($plan_allowances[2]) + array_sum($plan_allowances[3]); @endphp
                                    {{ number_format($plan_allowance[2]) }}
                                  @endif
                                </td>

                                <!-- แผนเบี้ยประชุม ไตรมาสที่ 3 -->
                                <td class="content-number">
                                  {{ count($plan_allowances[4])>0 ? number_format(array_sum($plan_allowances[4])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[5])>0 ? number_format(array_sum($plan_allowances[5])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[6])>0 ? number_format(array_sum($plan_allowances[6])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_allowances[4])>0 || count($plan_allowances[5])>0 || count($plan_allowances[6])>0)
                                    @php $plan_allowance[3] = array_sum($plan_allowances[4]) + array_sum($plan_allowances[5]) + array_sum($plan_allowances[6]); @endphp
                                    {{ number_format($plan_allowance[3]) }}
                                  @endif
                                </td>

                                <!-- แผนเบี้ยประชุม ไตรมาสที่ 4 -->
                                <td class="content-number">
                                  {{ count($plan_allowances[7])>0 ? number_format(array_sum($plan_allowances[7])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[8])>0 ? number_format(array_sum($plan_allowances[8])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_allowances[9])>0 ? number_format(array_sum($plan_allowances[9])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_allowances[7])>0 || count($plan_allowances[8])>0 || count($plan_allowances[9])>0)
                                    @php $plan_allowance[4] = array_sum($plan_allowances[7]) + array_sum($plan_allowances[8]) + array_sum($plan_allowances[9]); @endphp
                                    {{ number_format($plan_allowance[4]) }}
                                  @endif
                                </td>

                                <td class="content-number"><b><u>{{ number_format(array_sum($plan_allowance)) }}</u></b></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td class="horizontalTableContent"><div>ค่าอาหารว่าง</div></td>

                                <!--แผนค่าอาหารว่าง ไตรมาสที่ 1 -->
                                <td class="content-number">
                                  {{ count($plan_foods[10])>0 ? number_format(array_sum($plan_foods[10])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[11])>0 ? number_format(array_sum($plan_foods[11])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[12])>0 ? number_format(array_sum($plan_foods[12])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_foods[10])>0 || count($plan_foods[11])>0 || count($plan_foods[12])>0)
                                    @php $plan_food[1] = array_sum($plan_foods[10]) + array_sum($plan_foods[11]) + array_sum($plan_foods[12]); @endphp
                                    {{ number_format($plan_food[1]) }}
                                  @endif
                                </td>

                                <!--แผนค่าอาหารว่าง ไตรมาสที่ 2 -->
                                <td class="content-number">
                                  {{ count($plan_foods[1])>0 ? number_format(array_sum($plan_foods[1])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[2])>0 ? number_format(array_sum($plan_foods[2])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[3])>0 ? number_format(array_sum($plan_foods[3])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_foods[1])>0 || count($plan_foods[2])>0 || count($plan_foods[3])>0)
                                    @php $plan_food[2] = array_sum($plan_foods[1]) + array_sum($plan_foods[2]) + array_sum($plan_foods[3]); @endphp
                                    {{ number_format($plan_food[2]) }}
                                  @endif
                                </td>

                                <!-- แผนค่าอาหารว่าง ไตรมาสที่ 3 -->
                                <td class="content-number">
                                  {{ count($plan_foods[4])>0 ? number_format(array_sum($plan_foods[4])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[5])>0 ? number_format(array_sum($plan_foods[5])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[6])>0 ? number_format(array_sum($plan_foods[6])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_foods[4])>0 || count($plan_foods[5])>0 || count($plan_foods[6])>0)
                                    @php $plan_food[3] = array_sum($plan_foods[4]) + array_sum($plan_foods[5]) + array_sum($plan_foods[6]); @endphp
                                    {{ number_format($plan_food[3]) }}
                                  @endif
                                </td>

                                <!-- แผนค่าอาหารว่าง ไตรมาสที่ 4 -->
                                <td class="content-number">
                                  {{ count($plan_foods[7])>0 ? number_format(array_sum($plan_foods[7])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[8])>0 ? number_format(array_sum($plan_foods[8])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($plan_foods[9])>0 ? number_format(array_sum($plan_foods[9])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($plan_foods[7])>0 || count($plan_foods[8])>0 || count($plan_foods[9])>0)
                                    @php $plan_food[4] = array_sum($plan_foods[7]) + array_sum($plan_foods[8]) + array_sum($plan_foods[9]); @endphp
                                    {{ number_format($plan_food[4]) }}
                                  @endif
                                </td>

                                <td class="content-number"><b><u>{{ number_format(array_sum($plan_food)) }}</u></b></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td rowspan="2">ผลเงิน</td>
                                <td class="horizontalTableContent"><div>เบี้ยประชุม</div></td>

                                <!-- ผลเบี้ยประชุม ไตรมาสที่ 1 -->
                                <td class="content-number">
                                  {{ count($result_allowances[10])>0 ? number_format(array_sum($result_allowances[10])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[11])>0 ? number_format(array_sum($result_allowances[11])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[12])>0 ? number_format(array_sum($result_allowances[12])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_allowances[10])>0 || count($result_allowances[11])>0 || count($result_allowances[12])>0)
                                    @php $result_allowance[1] = array_sum($result_allowances[10]) + array_sum($result_allowances[11]) + array_sum($result_allowances[12]); @endphp
                                    {{ number_format($result_allowance[1]) }}
                                  @endif
                                </td>

                                <!-- ผลเบี้ยประชุม ไตรมาสที่ 2 -->
                                <td class="content-number">
                                  {{ count($result_allowances[1])>0 ? number_format(array_sum($result_allowances[1])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[2])>0 ? number_format(array_sum($result_allowances[2])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[3])>0 ? number_format(array_sum($result_allowances[3])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_allowances[1])>0 || count($result_allowances[2])>0 || count($result_allowances[3])>0)
                                    @php $result_allowance[2] = array_sum($result_allowances[1]) + array_sum($result_allowances[2]) + array_sum($result_allowances[3]); @endphp
                                    {{ number_format($result_allowance[2]) }}
                                  @endif
                                </td>

                                <!-- ผลเบี้ยประชุม ไตรมาสที่ 3 -->
                                <td class="content-number">
                                  {{ count($result_allowances[4])>0 ? number_format(array_sum($result_allowances[4])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[5])>0 ? number_format(array_sum($result_allowances[5])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[6])>0 ? number_format(array_sum($result_allowances[6])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_allowances[4])>0 || count($result_allowances[5])>0 || count($result_allowances[6])>0)
                                    @php $result_allowance[3] = array_sum($result_allowances[4]) + array_sum($result_allowances[5]) + array_sum($result_allowances[6]); @endphp
                                    {{ number_format($result_allowance[3]) }}
                                  @endif
                                </td>

                                <!-- ผลเบี้ยประชุม ไตรมาสที่ 4 -->
                                <td class="content-number">
                                  {{ count($result_allowances[7])>0 ? number_format(array_sum($result_allowances[7])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[8])>0 ? number_format(array_sum($result_allowances[8])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_allowances[9])>0 ? number_format(array_sum($result_allowances[9])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_allowances[7])>0 || count($result_allowances[8])>0 || count($result_allowances[9])>0)
                                    @php $result_allowance[4] = array_sum($result_allowances[7]) + array_sum($result_allowances[8]) + array_sum($result_allowances[9]); @endphp
                                    {{ number_format($result_allowance[4]) }}
                                  @endif
                                </td>

                                <td class="content-number"><b><u>{{ number_format(array_sum($result_allowance)) }}</u></b></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td class="horizontalTableContent"><div>ค่าอาหารว่าง</div></td>

                                <!-- ผลค่าอาหารว่าง ไตรมาสที่ 1 -->
                                <td class="content-number">
                                  {{ count($result_foods[10])>0 ? number_format(array_sum($result_foods[10])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[11])>0 ? number_format(array_sum($result_foods[11])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[12])>0 ? number_format(array_sum($result_foods[12])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_foods[10])>0 || count($result_foods[11])>0 || count($result_foods[12])>0)
                                    @php $result_food[1] = array_sum($result_foods[10]) + array_sum($result_foods[11]) + array_sum($result_foods[12]); @endphp
                                    {{ number_format($result_food[1]) }}
                                  @endif
                                </td>

                                <!-- ผลค่าอาหารว่าง ไตรมาสที่ 2 -->
                                <td class="content-number">
                                  {{ count($result_foods[1])>0 ? number_format(array_sum($result_foods[1])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[2])>0 ? number_format(array_sum($result_foods[2])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[3])>0 ? number_format(array_sum($result_foods[3])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_foods[1])>0 || count($result_foods[2])>0 || count($result_foods[3])>0)
                                    @php $result_food[2] = array_sum($result_foods[1]) + array_sum($result_foods[2]) + array_sum($result_foods[3]); @endphp
                                    {{ number_format($result_food[2]) }}
                                  @endif
                                </td>

                                <!-- ผลค่าอาหารว่าง ไตรมาสที่ 3 -->
                                <td class="content-number">
                                  {{ count($result_foods[4])>0 ? number_format(array_sum($result_foods[4])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[5])>0 ? number_format(array_sum($result_foods[5])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[6])>0 ? number_format(array_sum($result_foods[6])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_foods[4])>0 || count($result_foods[5])>0 || count($result_foods[6])>0)
                                    @php $result_food[3] = array_sum($result_foods[4]) + array_sum($result_foods[5]) + array_sum($result_foods[6]); @endphp
                                    {{ number_format($result_food[3]) }}
                                  @endif
                                </td>

                                <!-- ผลค่าอาหารว่าง ไตรมาสที่ 4 -->
                                <td class="content-number">
                                  {{ count($result_foods[7])>0 ? number_format(array_sum($result_foods[7])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[8])>0 ? number_format(array_sum($result_foods[8])) : '' }}
                                </td>
                                <td class="content-number">
                                  {{ count($result_foods[9])>0 ? number_format(array_sum($result_foods[9])) : '' }}
                                </td>
                                <td class="content-number">
                                  @if(count($result_foods[7])>0 || count($result_foods[8])>0 || count($result_foods[9])>0)
                                    @php $result_food[4] = array_sum($result_foods[7]) + array_sum($result_foods[8]) + array_sum($result_foods[9]); @endphp
                                    {{ number_format($result_food[4]) }}
                                  @endif
                                </td>

                                <td class="content-number"><b><u>{{ number_format(array_sum($result_food)) }}</u></b></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="2">หมายเหตุ</td>
                                <td colspan="3"></td>
                                <td></td>
                                <td colspan="3"></td>
                                <td></td>
                                <td colspan="3"></td>
                                <td></td>
                                <td colspan="3"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="2">ค่าใช้จ่ายที่เหลือ</td>

                                @php
                                  $remain[1] = (array_sum($plan_allowances[1])+array_sum($plan_foods[1])) - (array_sum($result_allowances[1])+array_sum($result_foods[1]));
                                  $remain[2] = (array_sum($plan_allowances[2])+array_sum($plan_foods[2])) - (array_sum($result_allowances[2])+array_sum($result_foods[2]));
                                  $remain[3] = (array_sum($plan_allowances[3])+array_sum($plan_foods[3])) - (array_sum($result_allowances[3])+array_sum($result_foods[3]));
                                  $remain[4] = (array_sum($plan_allowances[4])+array_sum($plan_foods[4])) - (array_sum($result_allowances[4])+array_sum($result_foods[4]));
                                  $remain[5] = (array_sum($plan_allowances[5])+array_sum($plan_foods[5])) - (array_sum($result_allowances[5])+array_sum($result_foods[5]));
                                  $remain[6] = (array_sum($plan_allowances[6])+array_sum($plan_foods[6])) - (array_sum($result_allowances[6])+array_sum($result_foods[6]));
                                  $remain[7] = (array_sum($plan_allowances[7])+array_sum($plan_foods[7])) - (array_sum($result_allowances[7])+array_sum($result_foods[7]));
                                  $remain[8] = (array_sum($plan_allowances[8])+array_sum($plan_foods[8])) - (array_sum($result_allowances[8])+array_sum($result_foods[8]));
                                  $remain[9] = (array_sum($plan_allowances[9])+array_sum($plan_foods[9])) - (array_sum($result_allowances[9])+array_sum($result_foods[9]));
                                  $remain[10] = (array_sum($plan_allowances[10])+array_sum($plan_foods[10])) - (array_sum($result_allowances[10])+array_sum($result_foods[10]));
                                  $remain[11] = (array_sum($plan_allowances[11])+array_sum($plan_foods[11])) - (array_sum($result_allowances[11])+array_sum($result_foods[11]));
                                  $remain[12] = (array_sum($plan_allowances[12])+array_sum($plan_foods[12])) - (array_sum($result_allowances[12])+array_sum($result_foods[12]));
                                  $remain_1 = $remain[10] + $remain[11] + $remain[12];
                                  $remain_2 = $remain[1] + $remain[2] + $remain[3];
                                  $remain_3 = $remain[4] + $remain[5] + $remain[6];
                                  $remain_4 = $remain[7] + $remain[8] + $remain[9];
                                @endphp

                                <!-- ไตรมาสที่ 1-->
                                <td class="content-number">
                                    {{ $remain[10]!=0 ? number_format($remain[10]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[11]!=0 ? number_format($remain[11]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[12]!=0 ? number_format($remain[12]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain_1!=0 ? number_format($remain_1) : '' }}
                                </td>

                                <!-- ไตรมาสที่ 2-->
                                <td class="content-number">
                                    {{ $remain[1]!=0 ? number_format($remain[1]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[2]!=0 ? number_format($remain[2]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[3]!=0 ? number_format($remain[3]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain_2!=0 ? number_format($remain_2) : '' }}
                                </td>

                                <!-- ไตรมาสที่ 3-->
                                <td class="content-number">
                                    {{ $remain[4]!=0 ? number_format($remain[4]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[5]!=0 ? number_format($remain[5]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[6]!=0 ? number_format($remain[6]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain_3!=0 ? number_format($remain_3) : '' }}
                                </td>

                                <!-- ไตรมาสที่ 4-->
                                <td class="content-number">
                                    {{ $remain[7]!=0 ? number_format($remain[7]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[8]!=0 ? number_format($remain[8]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain[9]!=0 ? number_format($remain[9]) : '' }}
                                </td>
                                <td class="content-number">
                                    {{ $remain_4!=0 ? number_format($remain_4) : '' }}
                                </td>

                                <td class="content-number"><u><b>{{ number_format($remain_1 + $remain_2 + $remain_3 + $remain_4) }}</b></u></td>
                                <td></td>
                              </tr>
                            @endforeach

                          </table>

                            <div class="pagination-wrapper">
                              {!!
                                  $items->appends(['search' => Request::get('search'),
                                                          'sort' => Request::get('sort'),
                                                          'direction' => Request::get('direction'),
                                                          'perPage' => Request::get('perPage'),
                                                          'filter_status' => Request::get('filter_status'),
                                                          'filter_year' => Request::get('filter_year'),
                                                          'filter_start_quarter' => Request::get('filter_start_quarter'),
                                                          'filter_end_quarter' => Request::get('filter_end_quarter'),
                                                          'filter_start_month' => Request::get('filter_start_month'),
                                                          'filter_end_month' => Request::get('filter_end_month'),
                                                          'filter_operation' => Request::get('filter_operation'),
                                                          'filter_method' => Request::get('filter_method'),
                                                          'filter_made' => Request::get('filter_made'),
                                                          'filter_standard_format' => Request::get('filter_standard_format'),
                                                          'filter_search' => Request::get('filter_search'),
                                                          'filter_plan' => Request::get('filter_plan'),
                                                         ])->render()
                              !!}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script type="text/javascript">

        $(document).ready(function () {

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').select2("val", "");
                $('#filter_year').select2("val", "");
                // $('#filter_standard_format').val('').select2();
                $('#filter_standard_format').select2("val", "");
                $("#filter_start_quarter").select2("val", "");
                $("#filter_end_quarter").select2("val", "");
                $('#filter_start_month').select2("val", "");
                $('#filter_end_month').select2("val", "");
                $('#filter_operation').select2("val", "");
                $('#filter_method').select2("val", "");
                $('#filter_made').select2("val", "");
                // $('#filter_plan').val('').select2();
                $('#filter_plan').select2("val", "");

                window.location.assign("{{url('/tis/report_performance')}}");
            });

            if($('#filter_year').val()!="" || $('#filter_standard_format').val()!="" ||
              $('#filter_start_quarter').val()!="" || $('#filter_end_quarter').val()!="" ||
              $('#filter_start_month').val()!="" || $('#filter_end_month').val()!="" ||
              $('#filter_operation').val()!="" || $('#filter_method').val()!="" ||
              $('#filter_made').val()!="" || $('#filter_plan').val()!=""
            ){
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });

        });

    </script>
@endpush

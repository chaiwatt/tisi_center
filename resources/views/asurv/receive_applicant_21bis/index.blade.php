@extends('layouts.master')

@push('css')

    <style>


        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #FFF2CC;
        }

        .label-filter{
            margin-top: 7px;
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
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ)</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <fieldset class="row">
                        <div class="white-box">
                            <div class="form-group">
                                {!! Form::model($filter, ['url' => '/asurv/receive_applicant_21bis', 'method' => 'get', 'id' => 'myFilter']) !!}

                                <div class="col-md-3" style="margin-bottom: 5px">
                                    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 5px">
                                    {!! Form::label('filter_start_month', 'วันที่ยื่น:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-5">
                                        {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::select('filter_start_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 5px">
                                    {!! Form::label('filter_end_month', 'ถึงวันที่:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-5">
                                        {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::select('filter_end_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light pull-right m-r-30">
                                    แสดง
                                </button>
                                <div class="col-md-4" style="margin-bottom: 5px">
                                    {!! Form::label('filter_notify', 'สถานะการแจ้ง:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_notify', array('1' => 'เปิด', '0' => 'ปิด'),'-เลือกสถานะการแจ้ง-' , ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะการแจ้ง-', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 5px">
                                    {!! Form::label('filter_request', 'สถานะคำขอ:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_request', array('1' => 'ยื่นคำขอ', '2 ' => 'อยู่ระหว่างดำเนินการ ', '3' => 'เอกสารไม่ครบถ้วน', '4' => 'อนุมัติ', '5' => 'ไม่อนุมัติ'),'-เลือกสถานะคำขอ-' , ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะคำขอ-', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('filter_created_by', 'search:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::input('filter_elicense_detail', null, null, ['class' => 'form-control',  'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 3%;">เลขที่คำขอ อ้างอิง</th>
                                    <th style="width: 5%;">ผู้ยื่น</th>
                                    <th style="width: 8%;">ชื่อผลิตภัณฑ์</th>
                                    <th style="width: 8%;">ระยะเวลาที่ผลิต</th>
                                    <th style="width: 6%;">วันที่ยื่น</th>
                                    <th style="width: 6%;">ตรวจสอบ เอกสาร</th>
                                    <th style="width: 6%;">สถานะคำขอ</th>
                                    <th style="width: 4%;">สถานะการแจ้ง</th>
                                    <th style="width: 4%;">ผู้รับมอบหมาย</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $list)
                                    <tr>
                                        <td>{{$temp_num++}}.</td>
                                        <td>{{$list->ref_no}}</td>
                                        <td>{{HP::get_name_4($list->created_by)}}</td>
                                        <td>{{$list->title}}</td>
                                        <td>{{ HP::DateThai($list->start_date) }} – {{ HP::DateThai($list->end_date) }}</td>
                                        <td>{{ HP::DateThai($list->created_at) }}</td>
                                        <td>
                                            @if($list->state === 1 ||$list->state === 2 ||$list->state === 3)
                                                <a href="{{url("/asurv/receive_applicant_21bis/$list->id/edit")}}"><span class="glyphicon glyphicon-search btn-lg"></span></a>
                                            @else
                                                <a href="{{url("/asurv/receive_applicant_21bis/$list->id")}}"><i class="fa fa-check-square fa-2x" style="color: orange"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($list->state === 1)
                                                ยื่นคำขอ
                                            @elseif($list->state === 2)
                                                อยู่ระหว่างดำเนินการ
                                            @elseif($list->state === 3)
                                                เอกสารไม่ครบถ้วน
                                            @elseif($list->state === 4)
                                                อนุมัติ
                                            @elseif($list->state === 5)
                                                ไม่อนุมัติ
                                            @endif
                                        </td>
                                        <td>
                                            @if($list->state_check === null)
                                                -
                                            @elseif($list->state_check === 1)
                                                <a href="{{url("/asurv/receive_applicant_21bis/update_status/$list->id/$list->state_check")}}"> <i class="fa fa-check-circle fa-2x" style="color: #1ec01e"></i></a>
                                            @elseif($list->state_check === 0)
                                                <a href="{{url("/asurv/receive_applicant_21bis/update_status/$list->id/$list->state_check")}}"><i class="fa fa-times-circle fa-2x" style="color: red"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($list->officer_export === null)
                                                -
                                            @else
                                                {{HP::get_create_4($list->officer_export)}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                {!!
                                    $items->appends(['search' => Request::get('search'),
                                                            'sort' => Request::get('sort'),
                                                            'direction' => Request::get('direction'),
                                                            'perPage' => Request::get('perPage'),
                                                            'filter_state' => Request::get('filter_state')
                                                           ])->links()
                                !!}
                            </div>
                        </div>
                    </fieldset>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>

    </script>

@endpush

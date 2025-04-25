@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img{
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
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

        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบรายงานสรุปคำขอรับบริการ</h3>

                    <div class="clearfix"></div>
                    <hr>
                    <div class="white-box">
                        <h3 class="box-title">เงื่อนไขการแสดงรายงาน</h3>
                        <hr>
                        {!! Form::model($filter, ['url' => 'certify/request-service/list', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="col-md-3 m-b-10">
                            {!! Form::label('perPage', 'Show:') !!}
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control','placeholder'=>'- ทั้งหมด -']); !!}
                        </div>

                        <div class="col-md-3 m-b-10">
                            <label for="filter_type">ประเภทการตรวจประเมิน:</label>
                            <?php $assess = ['CB','IB','LAB ทดสอบ','LAB สอบเทียบ']?>
                            <select name="filter_type" id="filter_type" class="form-control">
                                <option value="" selected>- ประเภทการตรวจประเมิน -</option>
                                @foreach ($assess as $as)
                                    <option value="{{$loop->iteration}}" {{isset($_GET['filter_type']) == true && $_GET['filter_type'] == $loop->iteration ? 'selected':''}}>{{$as}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 m-b-10">
                            <label for="formula_select">มาตรฐาน:</label>
                            <input type="hidden" name="formula_text" id="formula_text">
                            <select name="formula_select" id="formula_select" class="form-control" onchange="$('#formula_text').val($(this).find('option:selected').text())">
                                @if (isset($_SESSION['formula_text']))
                                    <option value="{{$_GET['formula_select']}}" selected>{{$_SESSION['formula_text']}}</option>
                                @else
                                    <option value="" selected>- เลือกมาตรฐาน -</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 m-b-10">
                            <label for="branch_select">สาขา:</label>
                            <input type="hidden" name="branch_text" id="branch_text">
                            <select name="branch_select" id="branch_select" class="form-control" onchange="$('#branch_text').val($(this).find('option:selected').text())">
                                @if (isset($_SESSION['branch_text']))
                                    <option value="{{$_GET['branch_select']}}" selected>{{$_SESSION['branch_text']}}</option>
                                    @else
                                    <option value="" selected>- เลือกสาขา -</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-2 m-b-10">
                            {!! Form::label('filter_start_date', 'วันที่ยื่น:')!!}
                            {!! Form::text('filter_start_date', null, ['class' => 'form-control mydatepicker']) !!}
                        </div>

                        <div class="col-md-2 m-b-10">
                            {!! Form::label('filter_end_date', 'ถึงวันที่:') !!}
                            {!! Form::text('filter_end_date', null, ['class' => 'form-control mydatepicker']) !!}
                        </div>

                        <div class="col-md-3 m-b-10">
                            <label for="status_select">สถานะ:</label>
                            <?php
                            $statusArr = [
                                '0'=>'ฉบับร่าง','1'=>'รอดำเนินการตรวจสอบ','2'=>'อยู่ระหว่างการตรวจสอบ','3'=>'ขอเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ','6'=>'ผ่านการตรวจสอบ','7'=>'รอชำระค่าธรรมเนียม','8'=>'แจ้งชำระค่าธรรมเนียม',
                                '9'=>'รับคำขอ','10'=>'ประมาณค่าใช้จ่าย','11'=>'ขอความเห็นประมาณค่าใช้จ่าย','12'=>'แต่งตั้งคณะผู้ตรวจประเมิน','13'=>'ขอความเห็นแต่งตั้งคณะผู้ตรวจประเมิน','14'=>'แจ้งรายละเอียดค่าตรวจประเมิน','15'=>'ชำระเงินค่าตรวจประเมิน',
                                '16'=>'ตรวจสอบการชำระค่าตรวจประเมิน','17'=>'ตรวจประเมิน','18'=>'สรุปรายงานและเสนออนุกรรมการฯ','19'=>'แจ้งรายละเอียดการชำระค่าใบรับรอง','20'=>'ชำระเงินค่าใบรับรอง','21'=>'ตรวจสอบการชำระค่าใบรับรอง','22'=>'ออกใบรับรอง',
                                '23'=>'ยืนยันความถูกต้อง','24'=>'แก้ไขใบรับรอง'];
                            ?>
                            <select name="status_select" id="status_select" class="form-control">
                                <option value="" selected>- เลือกสถานะ -</option>
                                @foreach ($statusArr as $key => $status)
                                    <option value="{{$key}}" {{isset($_GET['status_select']) &&  $_GET['status_select'] != null && $_GET['status_select'] == $key ? 'selected':''}}>{{$status}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 m-b-10">
                            {!! Form::label('search_text', 'ค้นหา:') !!}
                            <input type="text" name="search_text" id="search_text" class="form-control" placeholder="เลขที่คำขอ" value="{{isset($_GET['search_text']) ? $_GET['search_text']:''}}">
                        </div>

                        <div class="col-md-2 m-b-10">
                            <label>&emsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">ค้นหา</button>
                        </div>

                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                    </div>

                    <div class="text-center m-r-25">
                        <?php
                            $months = ["0"=>"",
                                "1"=>"มกราคม",
                                "2"=>"กุมภาพันธ์",
                                "3"=>"มีนาคม",
                                "4"=>"เมษายน",
                                "5"=>"พฤษภาคม",
                                "6"=>"มิถุนายน",
                                "7"=>"กรกฎาคม",
                                "8"=>"สิงหาคม",
                                "9"=>"กันยายน",
                                "10"=>"ตุลาคม",
                                "11"=>"พฤศจิกายน",
                                "12"=>"ธันวาคม"];
                            $date = \Carbon\Carbon::now();
                            $showDate = $date->day;
                            $showMonth = $months[$date->month];
                            $showYear = $date->year + 543;
                            $clock = $date->format('H:i');
                        ?>
                        <h3>ระบบสรุปรายงานสรุปคำขอรับบริการ</h3>
                            @if (isset($_GET['filter_type']) || isset($_SESSION['formula_text']))
                                <span style="font-size: 16px;text-decoration: underline">ประเภทการตรวจ:</span>
                                <span style="font-size: 15px;">&nbsp;{{$_GET['filter_type'] != null ? $assess[$_GET['filter_type']-1] ?? '-':'-'}}&emsp;
                                <span style="font-size: 15px;text-decoration: underline">มาตรฐาน:</span>&nbsp;{{isset($_SESSION['formula_text']) ? $_SESSION['formula_text'] != null ? $_SESSION['formula_text'] ?? '-' : '-' : '-'}}</span>
                            @endif
                        <p style="font-size: 16px">ข้อมูล ณ วันที่ {{$showDate.' '.$showMonth.' '.$showYear.' เวลา '.$clock.' น.'}}</p>
                    </div>

                    <div class="table-responsive m-t-30">

                        <table class="table table-bordered" id="myTable">
                            <thead class="bg-primary">
                            <tr>
                                <th class="text-center text-white">เลขที่คำขอ</th>
                                <th class="text-center text-white">หน่วยงานผู้ยื่นคำขอ</th>
                                @if (empty($_GET['filter_type']))
                                    <th class="text-center text-white">ประเภทการตรวจ</th>
                                @endif
                                @if (empty($_SESSION['formula_text']))
                                    <th class="text-center text-white">มาตรฐาน</th>
                                @endif
                                <th class="text-center text-white">สาขา</th>
                                <th class="text-center text-white">วันที่ยื่นคำขอ</th>
                                <th class="text-center text-white">สถานะ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (!empty($request_service) && $request_service->count() > 0)
                                @foreach ($request_service as $service)
                                    <tr>
                                        <td class="text-center">{{$service->app_no ?? '-'}}</td>
                                        <td class="text-center">{{$service->trader->trader_operater_name ?? '-'}}</td>
                                        @if (empty($_GET['filter_type']))
                                            <td class="text-center">{{$service->assessment_type() ?? '-'}}</td>
                                        @endif
                                        @if (empty($_SESSION['formula_text']))
                                            <td class="text-center">{{$service->get_standard->title ?? '-'}}</td>
                                        @endif
                                        <td class="text-center">{{$service->get_branch()->title ?? '-'}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($service->created_at)->format('d/m/Y') ?? '-'}}</td>
                                        <td class="text-center">{{$service->getStatus() ?? '-'}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if (isset($_GET['perPage']) and $_GET['perPage'] != null)
                            <div class="pagination-wrapper">
                                {!!
                                    $request_service->appends(['perPage' => Request::get('perPage'),
                                                            'filter_type' => Request::get('filter_type'),
                                                            'formula_select' => Request::get('formula_select'),
                                                            'branch_select' => Request::get('branch_select'),
                                                            'filter_start_date' => Request::get('filter_start_date'),
                                                            'filter_end_date' => Request::get('filter_end_date'),
                                                            'status_select' => Request::get('status_select'),
                                                            'search_text' => Request::get('search_text')
                                                           ])->render()
                                !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script>
        var assignment = null;
        $(document).ready(function () {

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            // $('.mydatepicker').datepicker().on('changeDate',function () {
            //     if ($('#filter_end_date').val() !== '' && $('#filter_start_date').val() !== ''){
            //         $('#myFilter').submit();
            //     }
            //     if ($('#filter_end_date_exp').val() !== '' && $('#filter_start_date_exp').val() !== ''){
            //         $('#myFilter').submit();
            //     }
            // });

            $('#filter_type').on('change',function () {
                let assessment_type = $(this).find('option:selected').val();
                assignment = assessment_type;
                if (assessment_type !== ''){
                    getApplicantTypeAjax(assessment_type);
                }else{
                    clearNumberStandard();
                    clearBranch();
                }
            });

            $('#formula_select').on('change',function () {
                if ($(this).find('option:selected').val() !== ''){
                    getBranchAjax(assignment);
                }
            });

        });

        function getApplicantTypeAjax(assessment_type) {
            if (assessment_type === '3' || assessment_type === '4'){
                assessment_type = '3';
            }
            $.ajax({
                url: '{!! url('certificate/api/getApplicantType.api') !!}',
                method: "POST",
                data: {assessment_type: assessment_type,_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.parse(JSON.stringify(msg)));
                let number_stan = $('#formula_select');
                if (data.status === true) {
                    number_stan.empty();
                    number_stan.append('<option value="">- เลือกมาตรฐาน -</option>');
                    number_stan.val('').change();
                    $.each(data.formula, function (key,val) {
                        number_stan.append('<option value="'+val.id+'">'+val.title+" ("+val.title_en+")"+'</option>');
                    });
                    number_stan.prop('disabled',false);
                }else{
                    alert('ไม่พบข้อมูลเลขมาตรฐาน');
                    clearNumberStandard();
                }
            });
        }

        function clearNumberStandard() {
            let number_stan = $('#formula_select');
            number_stan.empty();
            number_stan.append('<option value="">- เลือกประเภทการตรวจประเมินอีกครั้ง -</option>');
            number_stan.val('').change();
            number_stan.prop('disabled',true);
        }

        function clearBranch() {
            let branch = $('#branch_select');
            branch.empty();
            branch.append('<option value="">- เลือกประเภทการตรวจประเมินอีกครั้ง -</option>');
            branch.val('').change();
            branch.prop('disabled',true);
        }

        function getBranchAjax(assessment_type) {
            $.ajax({
                url: '{!! url('certificate/api/getBranch.api') !!}',
                method: "POST",
                data: {assessment_type: assessment_type,_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.parse(JSON.stringify(msg)));
                let branch = $('#branch_select');
                if (data.status === true) {
                    branch.empty();
                    branch.append('<option value="">- เลือกสาขา -</option>');
                    branch.val('').change();
                    $.each(data.branch, function (key,val) {
                        branch.append('<option value="'+val.id+'">'+val.title+" ("+val.title_en+")"+'</option>')
                    });
                    branch.prop('disabled',false);
                }else{
                    alert('ไม่พบข้อมูลสาขา');
                    clearBranch();
                }
            });
        }


    </script>

@endpush

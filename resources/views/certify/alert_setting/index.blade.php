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
                    <h3 class="box-title pull-left">ระบบแจ้งเตือนข้อมูลใบรับรอง</h3>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => 'certify/alert/check/expire/date', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="col-md-3 m-b-10">
                        {!! Form::label('perPage', 'Show:') !!}
                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control','placeholder'=>'- ทั้งหมด -']); !!}
                    </div>

                    <div class="col-md-3 m-b-10">
                        {!! Form::label('alert_level', 'ระดับการแจ้งเตือน:') !!}
                        {!! Form::select('alert_level', ['green'=>'เขียว','yellow'=>'เหลือง','red'=>'แดง'], null, ['class' => 'form-control', 'placeholder'=>'- ทั้งหมด -']); !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('filter_type', 'ประเภทการตรวจ:') !!}
                        {!! Form::select('filter_type', ['1'=>'CB','2'=>'IB','3'=>'LAB'], null, ['class' => 'form-control', 'placeholder'=>'- ทั้งหมด -']); !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('cerNumber_search', 'ค้นหา:') !!}
                        <input type="text" name="cerNumber_search" id="cerNumber_search" class="form-control" placeholder="เลขที่ใบรับรอง">
                    </div>
                    <div class="col-md-1">
                        <label>&emsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">ค้นหา</button>
                    </div>

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive m-t-20">

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">เลขที่ใบรับรอง</th>
                                <th class="text-center">ประเภทการตรวจ</th>
                                <th class="text-center">หน่วยงาน</th>
                                <th class="text-center">วันที่ใบรับรองหมดอายุ</th>
                                <th class="text-center">จำนวนวันก่อนหมดอายุ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($certificates)
                                @foreach ($certificates as $certificate)
                                    @if ($certificate->certified_exp)
                                        <tr>
                                            <?php
                                            $arr = explode('$',$certificate->checkExpire());
                                            $color = $arr[1];
                                            $date = $arr[0];
                                            if ($date == 0){
                                                $date = 'หมดอายุวันนี้';
                                                $color = 'bg-danger';
                                            }elseif ($date < 0){
                                                $date = 'หมดอายุแล้ว'.' ('.number_format(abs($date)).' วัน)';
                                                $color = 'bg-danger';
                                            }else{
                                                $date = number_format($date).' วัน';
                                            }
                                            ?>
                                            <td class="text-center">
                                                @if (isset($_GET['perPage']))
                                                    {{ (($certificates->currentPage() - 1 ) * $certificates->perPage() ) + $loop->iteration }}
                                                @else
                                                    {{$loop->iteration}}
                                                @endif
                                            </td>
                                            <td>{{$certificate->certificate_file_number ?? '-'}}</td>
                                            <td class="text-center">{{$certificate->assessment_type() ?? '-'}}</td>
                                            <td>{{$certificate->unit_name ?? '-'}}</td>
                                            <td class="text-center">{{\Carbon\Carbon::parse($certificate->certified_exp)->format('d/m/Y') ?? '-'}}</td>
                                            <td class="{{$color ?? ''}} text-center">{{$date ?? null}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="pagination-wrapper">
                            {!!
                                $certificates->appends([
                                                        'perPage' => Request::get('perPage'),
                                                        'alert_level' => Request::get('alert_level'),
                                                        'filter_type' => Request::get('filter_type'),
                                                        'cerNumber_search' => Request::get('filter_standard')
                                                       ])->render()
                            !!}
                        </div>
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

            $('.mydatepicker').datepicker().on('changeDate',function () {
                if ($('#filter_end_date').val() !== '' && $('#filter_start_date').val() !== ''){
                    $('#myFilter').submit();
                }
                if ($('#filter_end_date_exp').val() !== '' && $('#filter_start_date_exp').val() !== ''){
                    $('#myFilter').submit();
                }
            });

        });


    </script>

@endpush

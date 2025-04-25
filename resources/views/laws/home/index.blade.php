@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/morrisjs/morris.css')}}" rel="stylesheet">
    <style>
        .info-box .info-count {
            margin-top: 0px !important;
        }
  
    </style>
@endpush

{{-- @section('content')
 
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 col-sm-12">
                    <h1 align="center">Welcome to Dashboard Law</h1>
                </div>
            </div>
        </div>


@endsection --}}

@section('content')
<div class="container-fluid">
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title pull-left"> Dashboard : ระบบงานคดีผลิตภัณฑ์อุตสาหกรรม</h3>

                <div class="pull-right">
                    <a class="btn btn-success pull-right" href="{{url('/law/dashboard')}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                </div>

                <div class="clearfix"></div>
                <hr>

                {!! Form::model($filter_year, ['url' => 'law/dashboard', 'method' => 'get', 'id' => 'myFilter']) !!}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            {{-- {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นหา ผู้ได้รับใบอนุญาต']); !!} --}}
                            <span>จำนวนงานคดีสะสมทั้งหมด {{ $count_lawcase_status_all }}</span>
                        </div><!-- /form-group -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-2">
                        {{-- <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
                                </div> --}}
                    </div>
                    <div class="col-lg-2">
                        {{-- <div class="form-group  pull-left">
                                    <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                                </div>
                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div> --}}
                    </div><!-- /.col-lg-1 -->
                    <div class="col-lg-5">
                        <div class="form-group col-md-4">

                        </div>
                        <div class="form-group col-md-8">
                            {!! Form::label('filter_year', 'ปีงบประมาณ', ['class' => 'col-md-5 control-label label-filter']) !!}
                            <div class="col-md-7">
                                {!! Form::select('filter_year', $option_offend_date, !empty($filter_year)?$filter_year:date('Y'), ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_year', 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>
                    </div><!-- /.col-lg-5 -->
                </div><!-- /.row -->

                {!! Form::close() !!}

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <span>ปีงบประมาณ {{ !empty($filter_year)?$filter_year+543:'n/a' }}</span>
                        </div><!-- /form-group -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-2">

                    </div>
                    <div class="col-lg-2">

                    </div><!-- /.col-lg-1 -->
                    <div class="col-lg-5">
                        <div class="form-group col-md-4">

                        </div>
                        <div class="form-group col-md-8">
                            <span>ข้อมูล ณ {{ HP::FullDateTimeThai(date('Y-m-d H:m:s')) }}</span>
                        </div>
                    </div><!-- /.col-lg-5 -->
                </div><!-- /.row -->

                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12">

                            <a href="{{ url('/law/dashboard') }}">
                                <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                    <div class="media bg-dashboard3">
                                        <div class="media-body">
                                            <span class="" style="font-size:65px;"><i class="mdi mdi-playlist-check"></i></span>
                                            <h2 class="info-count pull-right" style="color:#fff">ส่งเรื่องงานคดีสำเร็จ<br />
                                                <span class="pull-right" style="font-size:25px;">{{ $count_lawcase_status_success }}</span>
                                            </h2>
                                            <br class="clearfix">
                                            <hr>
                                            <p class="info-text font-12">
                                                <div><span class="pull-left">หน่วยงานภายใน {{ $count_depart_type_in }}<span></div>
                                                <div><span class="pull-right">หน่วยงานภายนอก {{ $count_depart_type_out }}<span></div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ url('/law/dashboard') }}">
                                <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                    <div class="media bg-dashboard5">
                                        <div class="media-body">
                                            <span class="" style="font-size:65px;"><i class="mdi mdi-gavel"></i></span>
                                            <h2 class="info-count pull-right" style="color:#fff">อยู่ระหว่างดำเนินการ<br />
                                                <span class="pull-right" style="font-size:25px;">{{ $count_lawcase_status_process }}</span>
                                            </h2>
                                            <br class="clearfix">
                                            <hr>
                                            <p class="info-text font-12">
                                                <div><span class="pull-left">เปรียบเทียบปรับ {{ $count_prosecute_compare }}<span></div>
                                                <div><span class="pull-right">ดำเนินคดี {{ $count_prosecute_prosecute }}<span></div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ url('/law/dashboard') }}">
                                <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                    <div class="media bg-dashboard2">
                                        <div class="media-body">
                                            <span class="" style="font-size:65px;"><i class="mdi mdi-paper-cut-vertical"></i></span>
                                            <h2 class="info-count pull-right" style="color:#fff">ปิดงานคดี<br />
                                                <span class="pull-right" style="font-size:25px;">{{ $count_lawcase_status_close }}</span>
                                            </h2>
                                            <br class="clearfix">
                                            <hr>
                                            <p class="info-text font-12">
                                                <div><span class="pull-left">รอปิดงาน {{ $count_lawcase_status_close_wait }}<span></div>
                                                <div><span class="pull-right">&nbsp;<span></div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            &nbsp
                        </div>
                    </div>

                    <div class="col-lg-12">

                        <div class="form-group">
                            <div class="col-md-5 col-lg-5 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">สถิติสาเหตุที่พบการกระทำความผิด</h3>
                                    <div id="morris-donut-chart"></div>
                                    <br>
                                    <span id="show_chart_title"></span>
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-7 col-xs-12">
                                <div class="white-box">
                                    <h3 class="box-title">สถิติกลุ่มผลิตภัณฑ์ที่มีการกระทำความผิด 5 อันดับแรก</h3>
                                    <div id="morris-bar-chart"></div>
                                    <br>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.row -->

                {{-- @include('layouts.partials.right-sidebar')			 --}}

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    <!--Morris JavaScript -->
    <script src="{{asset('plugins/components/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('plugins/components/morrisjs/morris.js')}}"></script>

    <script>
        $(document).ready(function () {


         /*   Morris.Donut({
            element: 'morris-donut-chart',
            resize: true,
            data: [
                {value: 70, label: 'foo', formatted: 'at least 70%' },
                {value: 15, label: 'bar', formatted: 'approx. 15%' },
                {value: 10, label: 'baz', formatted: 'approx. 10%' },
                {value: 5, label: 'A really really long label', formatted: 'at most 5%' }
            ],
            formatter: function (x, data) { return data.formatted; }
            }); */

          /*  new Morris.Donut({
                element: 'morris-donut-chart',
                resize: true,
                data: [{
                    label: "ตรวจควบคุม",
                    value: 20,

                }, {
                    label: "ตรวจประเมินควบคุมคุณภาพ",
                    value: 20
                }, {
                    label: "แจ้งนำเข้ามาใช้เอง",
                    value: 30
                }, {
                    label: "พบเจอผลิตภัณฑ์ไม่ได้มาตรฐาน",
                    value: 30
                }],
                resize: true,
                colors: ['#2ecc71', '#00bbd9', '#4a23ad','orange','red','yellow']
            }); */

            var data_arr = {!! $data_arr !!};

            Morris.Donut({
                element: 'morris-donut-chart',
                data: data_arr,
                resize: false,
                colors: ['#2ecc71', '#00bbd9', '#4a23ad','orange','red','yellow'],
                formatter: function (x,data) { return data.value }
                }).on('click', function(i, row){
                console.log(i, row);
                $("#show_chart_title").text(row.label);
            });

             // $("div#morris-donut-chart svg text").find('tspan').attr("style", "color: #000000 !important");
                      
            // Morris bar chart
        /*    new Morris.Bar({
                element: 'morris-bar-chart',
                data: [{
                    y: 'คอนกรีต',
                    a: 5,
                
                }, {
                    y: 'ไฟฟ้ากำลัง',
                    a: 4,
                
                }, {
                    y: 'ยานยนต์',
                    a: 3,
                
                }, {
                    y: "เครื่องใช้ไฟฟ้า",
                    a: 2,
                
                }, {
                    y: 'เหล็กแท่ง',
                    a: 1,
                
                }],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['A'],
                barColors: ['#2ecc71'],
                hideHover: 'auto',
                gridLineColor: '#e0e0e0',
                resize: true
            }); */

            var data_arr2 = {!! $data_arr2 !!};

            // Morris bar chart
            new Morris.Bar({
                element: 'morris-bar-chart',
                data: data_arr2,
                xkey: 'y',
                ykeys: ['a'],
                labels: ['จำนวน'],
                barColors: ['#2ecc71'],
                hideHover: 'auto',
                gridLineColor: '#e0e0e0',
                resize: false,
                xLabelAngle: 60,
                resize: true,
                       //     hoverCallback: function (index, options, default_content, row) {
             //   var data = options.data[index];
      //  $("#show_chart_title").text('<div>Custom label: ' + default_content + '</div>');
 // },
       
            });

          //  $("div#morris-bar-chart svg").attr("style", "padding: -80px;");

        });
    </script>
@endpush
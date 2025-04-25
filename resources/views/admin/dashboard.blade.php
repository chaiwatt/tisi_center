@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/chartist-js/dist/chartist.min.css?20190616')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css?20190616')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/morrisjs/morris.css')}}" rel="stylesheet">
    <style type="text/css">
        .ct-label.ct-vertical{
            width: 26px !important;
        }
    </style>
@endpush

@section('content')

  @php
    $config = HP::getConfig();
  @endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title" style="margin: 1px 0 12px;">
                  <i class="mdi mdi-view-dashboard"></i> Dashboard
                  <span class="pull-right" style="font-size: 80%; font-weight: 100;"> {{ HP::DateTimeFullThai(date('Y-m-d H:i')) }} </span>
                </h3>
                <hr style="margin-top: 0px;" />

                <div class="row">

                  <div class="col-md-12 col-sm-12">
                      <div class="row">

                        <div class="col-sm-12"> <h4><b> จำนวนผู้ประกอบการที่ได้รับใบอนุญาตมาตรฐานผลิตภัณฑ์อุตสาหกรรม (มอก.) </b></h4> </div>

                          <div class="col-sm-3">
                              <div class="white-box small-box-widget">
                                  <div class="p-t-10 p-b-10">
                                      <div class="icon-box bg-primary">
                                          <i class="icon-badge"></i>
                                      </div>
                                      <div class="detail-box">
                                          <h4>
                                            แสดง
                                            <span class="pull-right text-primary font-22 font-normal user_count" data-elicense-type="ส">-</span>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar progress-bar-primary" role="progressbar"
                                                   aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"
                                                   style="width: 100%">
                                                  <span class="sr-only">100%</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="white-box small-box-widget">
                                  <div class="p-t-10 p-b-10">
                                      <div class="icon-box bg-success">
                                          <i class="icon-home"></i>
                                      </div>
                                      <div class="detail-box">
                                          <h4>
                                            ทำ
                                            <span class="pull-right text-success font-22 font-normal user_count" data-elicense-type="ท">-</span>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar progress-bar-success" role="progressbar"
                                                   aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"
                                                   style="width: 100%">
                                                  <span class="sr-only">100%</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="white-box small-box-widget">
                                  <div class="p-t-10 p-b-10">
                                      <div class="icon-box bg-warning">
                                          <i class="icon-plane"></i>
                                      </div>
                                      <div class="detail-box">
                                          <h4>
                                            นำเข้า
                                            <span class="pull-right text-warning font-22 font-normal user_count" data-elicense-type="น">-</span>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar progress-bar-warning" role="progressbar"
                                                   aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"
                                                   style="width: 100%">
                                                  <span class="sr-only">100%</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="white-box small-box-widget">
                                  <div class="p-t-10 p-b-10">
                                      <div class="icon-box bg-danger">
                                          <i class="icon-handbag"></i>
                                      </div>
                                      <div class="detail-box">
                                          <h4 style="font-size: 13px;">
                                            นำเข้าเฉพาะครั้ง
                                            <span class="pull-right text-danger font-22 font-normal user_count" data-elicense-type="นค">-</span>
                                          </h4>
                                          <div class="progress">
                                              <div class="progress-bar progress-bar-danger" role="progressbar"
                                                   aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"
                                                   style="width: 100%">
                                                  <span class="sr-only">100%</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box stat-widget">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h4 class="box-title">สถิติการนำเข้า (ระบบ NSW)</h4>
                                </div>
                                <div class="col-sm-8">
                                    {{-- <select class="custom-select">
                                        <option value="1">มิถุนายน 2562</option>
                                        <option value="2">กรกฎาคม 2562</option>
                                        <option value="3">สิงหาคม 2562</option>
                                    </select> --}}
                                    @php $month_years = HP::month_years(); @endphp
                                    {!! Form::select('filter_nsw', $month_years, array_key_last($month_years), ['class' => 'custom-select', 'id'=>'filter_nsw']); !!}
                                    <ul class="list-inline">
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-success"></i>มีใบอนุญาต</h6>
                                        </li>
                                        <li>
                                            <h6 class="font-15"><i class="fa fa-circle m-r-5 text-primary"></i>ไม่มีใบอนุญาต</h6>
                                        </li>
                                    </ul>
                                </div>
                                <div class="stat chart-pos"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="white-box">

                          <div class="col-sm-4">
                              <h4 class="box-title">สถิติการรับรองระบบงาน</h4>
                          </div>
                          <div class="col-sm-8">
                              <ul class="list-inline">
                                  <li>
                                      <h6 class="font-15"><i class="fa fa-circle m-r-5 text-success"></i>CB</h6>
                                  </li>
                                  <li>
                                      <h6 class="font-15"><i class="fa fa-circle m-r-5 text-primary"></i>IB</h6>
                                  </li>
                                  <li>
                                      <h6 class="font-15"><i class="fa fa-circle m-r-5 text-warning"></i>Lab</h6>
                                  </li>
                              </ul>
                          </div>
                            <div class="clearfix"></div>

                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('js')

    <!-- Chartist JavaScript -->
    <script src="{{asset('plugins/components/chartist-js/dist/chartist.min.js')}}"></script>
    <script src="{{asset('plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>

    <!-- Morris JavaScript -->
    <script src="{{asset('plugins/components/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('plugins/components/morrisjs/morris.js')}}"></script>

    <!-- Function -->
    <script src="{{asset('js/function.js')}}"></script>

    <script type="text/javascript">

      $(document).ready(function() {

        //NSW
        $('#filter_nsw').change(function(event) {
          draw_nsw();
        });

        draw_elicense(); //e-License
        draw_nsw(); //NSW แจ้งนำเข้า
        certify_draw(); //ระบบรับรองระบบงาน

        //Set Interval Refresh
        var multiples = { H:1000*60*60, M:1000*60 }; //ตัวคูณ
        setInterval(function(){
          draw_elicense(); //e-License
          draw_nsw(); //NSW แจ้งนำเข้า
          certify_draw(); //ระบบรับรองระบบงาน
        }, multiples.{{ $config->refresh_dashboard_unit }} * {{ $config->refresh_dashboard_value }});

    });

    //e-License
    function draw_elicense(){

        $.ajax({
            url: '{{ url('dashboard/elicense') }}',
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {

                $.each(data, function(index, item) {
                  $('.user_count[data-elicense-type="'+item.tbl_licenseType+'"]').text(addCommas(item.user_count));
                });
            }
        });

    }

    //NSW แจ้งนำเข้า
    function draw_nsw(){

        $.ajax({
            url: '{{ url('dashboard/nsw/') }}/'+$('#filter_nsw').val(),
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {

                var labels = data.labels;
                var datas = [
                    data.nhave_licenses,
                    data.have_licenses
                ];

                var chart1 = new Chartist.Line('.stat', {
                    labels: labels,
                    series: datas
                }, {
                    high: data.max,
                    low: 0,
                    height: '278px',
                    showArea: false,
                    fullWidth: true,
                    axisY: {
                        onlyInteger: true,
                        showGrid: false,
                        offset: 20,
                    },
                    plugins: [
                        Chartist.plugins.tooltip()
                    ]
                });

            }
        });

    }

    //รับรองระบบงาน
    function certify_draw(){

        $.ajax({
            url: '{{ url('dashboard/certify') }}',
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {

                $("#morris-bar-chart").empty();
                Morris.Bar({
                    element: 'morris-bar-chart',
                    data: data,
                    xkey: 'year',
                    ykeys: ['cb', 'ib', 'lab'],
                    labels: ['CB', 'IB', 'Lab'],
                    barColors: ['#2ecc71', '#0283cc', '#ffb136'],
                    hideHover: 'auto',
                    gridLineColor: '#e0e0e0',
                    resize: true
                });

            }
        });

    }

    </script>

@endpush

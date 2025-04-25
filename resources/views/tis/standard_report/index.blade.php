@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet" />

    <style>

        .label-filter {
            margin-top: 7px;
        }

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

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: bold;
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
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">ระบบรายงานข้อมูลมาตรฐานที่เปิดใช้ในปัจจุบัน</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/standard_report', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อมอก., หมายเหตุ']); !!}
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
                                        {!! Form::label('filter_year', 'ปี', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_year', HP::TenYearThaiListReport(), null, ['class' => 'form-control', 'placeholder'=>'- เลือกปี -']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_standard_formats', 'รูปแบบมาตรฐาน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_standard_formats', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกมาตรฐาน -']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_standard_types', 'ประเภท', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_standard_types', App\Models\Basic\StandardType::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภท -']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_product_groups', 'สาขา', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_product_groups', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกสาขา -']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_set_formats', 'รูปแบบการกำหนด', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_set_formats', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกรูปแบบการกำหนด -']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_industry_targets', 'อุตสาหกรรมเป้าหมาย', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_industry_targets', App\Models\Basic\IndustryTarget::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย -']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_methods', 'วิธีจัดทำ', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_methods', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกวิธีจัดทำ -']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">

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
                        {!! Form::model($filter, ['url' => '/tis/standard_report/export_excel', 'method' => 'get', 'id' => 'myFilter']) !!}
                            {!! Form::select('filter_year', HP::TenYearThaiListReport(), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกปี-']); !!}
                            {!! Form::select('filter_standard_formats', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control hidden','placeholder'=>'-เลือกมาตรฐาน-']); !!}
                            {!! Form::select('filter_standard_types', App\Models\Basic\StandardType::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกประเภท-']); !!}
                            {!! Form::select('filter_product_groups', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกสาขา-']); !!}
                            {!! Form::select('filter_set_formats', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกรูปแบบการกำหนด-']); !!}
                            {!! Form::select('filter_industry_targets', App\Models\Basic\IndustryTarget::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกอุตสาหกรรมเป้าหมาย-']); !!}
                            {!! Form::select('filter_methods', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกวิธีจัดทำ-']); !!}
                            <button type="submit" formtarget="_blank" class="btn btn-success btn-sm waves-effect waves-light">
                                Export Excel
                            </button>
                        {!! Form::close() !!}
                    </div>
                    <div class="clearfix"></div>

                        <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <caption class="text-center">
                                <label class="wrapper-label">รายงานข้อมูลมาตรฐานที่เปิดใช้ในปัจจุบัน</label><br>
                                <label class="wrapper-label-small">ข้อมูล ณ วันที่ {{HP::DateTimeFullThai(date('Y-m-d H:i:s'))}}</label>
                            </caption>
                                <thead>
                                    <tr style="background-color: azure">
                                        <th>No.</th>
                                        <th>ปีที่เริ่ม</th>
                                        <th>เลขที่ มอก.</th>
                                        <th>ชื่อ มอก.</th>
                                        <th>รูปแบบมาตรฐาน</th>
                                        <th>ประเภท</th>
                                        <th>วันที่ประกาศใช้/วันที่บังคับใช้</th>
                                        <th>กลุ่มผลิตภัณฑ์/สาขา</th>
                                        <th>รูปแบบการกำหนด</th>
                                        <th>อุตสาหกรรมเป้าหมาย</th>
                                        <th>วิธีจัดทำ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($items as $key => $item)
                                            <tr>
                                                <td class="text-top">{{ $items->perPage()*($items->currentPage()-1)+$loop->iteration }}</td>
                                                <td>{{ $item->tis_year }}</td>
                                                <td>{{ $item->tis_no.!empty($item->tis_book)?"เล่ม".$item->tis_book:''."-".$item->tis_year }}</td>
                                                <td>{{ $item->title }} <br> {{ $item->title_en }}</td>
                                                <td>{{ $item->StandardFormatName }}</td>
                                                <td>{{ $item->StandardTypeName }}</td>
                                                <td>{{ HP::DateThai($item->issue_date) }}</td>
                                                <td>{{ $item->ProductGroupName }}</td>
                                                <td>{{ $item->SetFormatName }}</td>
                                                <td>{{ $item->InductryTargetName }}</td>
                                                <td>{{ $item->MethodName }}</td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>

                            <div class="pagination-wrapper"></div>
                            {!!
                                $items->appends(['sort' => Request::get('sort'),
                                                'direction' => Request::get('direction'),
                                                'perPage' => Request::get('perPage'),
                                                'filter_search' => Request::get('filter_search'),
                                                'filter_status' => Request::get('filter_status'),
                                                'filter_year' => Request::get('filter_year'),
                                                'filter_standard_formats' => Request::get('filter_standard_formats'),
                                                'filter_standard_types' => Request::get('filter_standard_types'),
                                                'filter_product_groups' => Request::get('filter_product_groups'),
                                                'filter_set_formats' => Request::get('filter_set_formats'),
                                                'filter_industry_targets' => Request::get('filter_industry_targets'),
                                                'filter_methods' => Request::get('filter_methods')
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
    <script src="{{asset('plugins/components/bootstrap-tagsinput/src/bootstrap-tagsinput.js')}}"></script>


    <script>
        $(document).ready(function () {

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_year').val('');
                $('#filter_standard_formats').val('').select2();
                $('#filter_standard_types').val('').select2();
                $('#filter_product_groups').val('').select2();
                $('#filter_set_formats').val('').select2();
                $('#filter_industry_targets').val('').select2();
                $('#filter_methods').val('').select2();

                window.location.assign("{{url('/tis/standard_report')}}");
            });

            if($('#filter_year').val()!="" || $('#filter_standard_formats').val()!="" ||
              $('#filter_standard_types').val()!="" || $('#filter_product_groups').val()!="" ||
              $('#filter_set_formats').val()!="" || $('#filter_industry_targets').val()!="" ||
              $('#filter_methods').val()!=""
            ){
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });

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

            //เลือกทั้งหมด
            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.cb').prop('checked', true);
                } else {
                    $('#myTable').find('input.cb').prop('checked', false);
                }

            });

        });

        function Delete() {

            if ($('#myTable').find('input.cb:checked').length > 0) {//ถ้าเลือกแล้ว
                if (confirm_delete()) {
                    $('#myTable').find('input.cb:checked').appendTo("#myForm");
                    $('#myForm').submit();
                }
            } else {//ยังไม่ได้เลือก
                alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
            }

        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function UpdateState(state) {

            if ($('#myTable').find('input.cb:checked').length > 0) {//ถ้าเลือกแล้ว
                $('#myTable').find('input.cb:checked').appendTo("#myFormState");
                $('#state').val(state);
                $('#myFormState').submit();
            } else {//ยังไม่ได้เลือก
                if (state == '1') {
                    alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
                } else {
                    alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
                }
            }

        }

        $(document).ready(function () {
            $('#myTable').each(function () {
                var sum = 0
                var sum2 = 0
                var sum3 = 0
                $(this).find('.volume_1').each(function () {
                    var total = $(this).text();
                    if (total.length !== 0) {
                        sum += parseFloat(total);
                    }
                })
                $(this).find('.volume_2').each(function () {
                    var total = $(this).text();
                    if (total.length !== 0) {
                        sum2 += parseFloat(total);
                    }
                })
                $(this).find('.volume_3').each(function () {
                    var total = $(this).text();
                    if (total.length !== 0) {
                        sum3 += parseFloat(total);
                    }
                })

                $(this).find('#total_volume1').html(sum);
                $(this).find('#total_volume2').html(sum2);
                $(this).find('#total_volume3').html(sum3);
            })

        });
    </script>

@endpush

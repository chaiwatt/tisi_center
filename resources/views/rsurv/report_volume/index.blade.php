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
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px) {

        /* Force table to not be like tables anymore */
        table,
        thead,
        tbody,
        th,
        td,
        tr {
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
    }

    th {
        text-align: center;
    }

    td {
        text-align: center;
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
                        <h1 class="box-title">ระบบรายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต</h1>
                        <hr class="hr-line bg-primary">
                    </div>
                </div>

                <div class="panel-group" id="accordion">
                    <div class="panel card-collaps">
                        {!! Form::model($filter, ['url' => '/rsurv/report_volume', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="panel-heading" style="border-bottom: solid 1px silver;">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="font-weight: bold; font-size: 18px; "> เงื่อนไขการแสดงรายงาน </a>
                            </h4>
                        </div>

                        <div id="collapse1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                {!! Form::model($filter, ['url' => '/rsurv/report_volume', 'method' => 'get', 'id' => 'myFilter']) !!}
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_created_by', 'ผู้ประกอบการ :', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('filter_created_by', App\Models\Sso\User::pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ประกอบการ-', 'onchange'=>'this.form.submit()']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_tb3_Tisno', 'มอก. :', ['class' => 'col-md-2 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('filter_tb3_Tisno', HP::TisList(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-', 'onchange'=>'this.form.submit()']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-md-5">
                                        {!! Form::label('filter_start_month', 'วันที่ผลิต :', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-5">
                                            {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::select('filter_start_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        {!! Form::label('filter_end_month', 'ถึงวันที่ :', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-5">
                                            {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::select('filter_end_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button id="filter_clear" type="button" class="btn btn-warning waves-effect waves-light pull-right" style="margin-left: 5px;">
                                            ล้าง
                                        </button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">
                                            แสดงรายงาน
                                        </button>

                                    </div>

                                </div>
                                <div class="alert alert-danger col-md-6" role="alert">
                                    * หากต้องการค้นหาวันที่ผลิตและถึงวันที่ กรุณาเลือกทั้งเดือนและปี
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                        <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                    </div>
                    <hr>
                    <div class="wrapper">
                        <label class="wrapper-label">
                            รายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต
                        </label>
                        <label class="wrapper-label-small">
                            ข้อมูล ณ วันที่ {{HP::DateTimeFullThai(date('Y-m-d H:i:s'))}}
                        </label>

                    </div>
                    <div class="col-md-3">
                        {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <div class="col-md-10"></div>
                    <div class="" align="right" style="margin-top: 20px">
                        {!! Form::model($filter, ['url' => '/rsurv/export_excel', 'method' => 'get', 'id' => 'myFilter']) !!}
                        {!! Form::select('filter_created_by', App\Models\Sso\User::pluck('name', 'id'), null, ['class' => 'form-control hidden', 'placeholder'=>'-เลือกผู้ประกอบการ-', 'onchange'=>'this.form.submit()']); !!}
                        {!! Form::select('filter_tb3_Tisno', HP::TisList(), null, ['class' => 'form-control hidden','placeholder'=>'-เลือกผู้ประกอบการ-']); !!}
                        <input type="text" value="{{$detail}}" name="filter_elicense_detail" hidden />
                        {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control hidden', 'placeholder'=>'-เดือน-']); !!}
                        {!! Form::select('filter_start_year', HP::YearListReport(), null, ['class' => 'form-control hidden', 'placeholder'=>'-ปี-']); !!}
                        {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control hidden', 'placeholder'=>'-เดือน-']); !!}
                        {!! Form::select('filter_end_year', HP::YearListReport(), null, ['class' => 'form-control hidden', 'placeholder'=>'-ปี-']); !!}
                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control hidden']); !!}
                        <button type="submit" formtarget="_blank" class="btn btn-success waves-effect waves-light">
                            EXCEL
                        </button>
                    <input type="hidden" name="total_page" value="{{ $total_page }}">
                        {!! Form::close() !!}

                    </div>
                    {{-- {{ $total_page }} --}}
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr bgcolor="#0283cc">
                                    <th style="width: 2%;color: white">No.</th>
                                    <th style="width: 13%;color: white">ผู้ประกอบการ</th>
                                    <th style="width: 23%;color: white">มอก.</th>
                                    <th style="width: 15%;color: white">เลขที่ใบอนุญาต</th>
                                    <th style="width: 7%;color: white">วันที่ยื่น</th>
                                    <th style="width: 5%;color: white">เดือนที่ยื่น</th>
                                    <th style="width: 5%;color: white">ปีที่ยื่น</th>
                                    <th style="width: 6%;color: white">จำนวนผลิต (แสดง)</th>
                                    <th style="width: 6%;color: white">จำนวนผลิต (ไม่แสดง)</th>
                                    <th style="width: 6%;color: white">รวม (จำนวนผลิต)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($report_volume))
                                @foreach($report_volume as $item)
                                <tr>
                                    <td class="text-top">{{ $temp_num++ }}</td>
                                    {{-- <td>{{ HP::get_Create_name_trader($item->inform_volume_license_id) }}</td> --}}
                                    <td class="text-top">{{ $item->CreatedName }} <br> {{ $item->TraderIdName }} </td>
                                    {{-- <td>{{ HP::get_tb3_Tisno($item->inform_volume_license_id) . ' ('.HP::get_tb3_TisThainame($item->inform_volume_license_id).')' }}</td> --}}
                                    <td class="text-top">มอก.{{ @$item->tis->tb3_Tisno }} {{ @$item->tis->tb3_TisThainame }}</td>
                                    {{-- <td>{{ $item->LicenseNo }}</td> --}}
                                    <td class="text-top">
                                        <div>{{ $item->tbl_licenseNo }}</div>
                                    </td>
                                    {{-- <td>{{ HP::DateThai(HP::get_created_at($item->inform_volume_license_id)) }}</td> --}}
                                    <td class="text-top">{{ HP::DateThai($item->created_at) }}</td>
                                    {{-- <td>{{ HP::MonthConvertList(HP::get_inform_month($item->inform_volume_license_id)) }}</td> --}}
                                    <td class="text-top">{{ HP::MonthList()[$item->inform_month] }}</td>
                                    {{-- <td>{{ HP::get_inform_year($item->inform_volume_license_id)+543 }}</td> --}}
                                    <td class="text-top">{{ $item->inform_year+543 }}</td>
                                    <td class="text-top volume_1">@if($item->sum_volume1!=null){{ $item->sum_volume1 }}@elseif($item->sum_volume2!=null){{ $item->sum_volume2 }}@endif</td>
                                    <td class="text-top volume_2">{{ $item->sum_volume3 }}</td>
                                    <td class="text-top volume_3">
                                        @if($item->sum_volume1!=null)
                                        {{ HP::get_sum_row_volume($item->sum_volume1,$item->sum_volume3) }}
                                        @elseif($item->sum_volume2!=null)
                                        {{ HP::get_sum_row_volume($item->sum_volume2,$item->sum_volume3) }}
                                        @endif
                                    </td>
                                </tr>

                                @endforeach
                                <?php $count = 1 ?>
                                @if(isset($data))
                                <?php $count = 0 ?>
                                @endif
                                @if($count==1)
                                <td colspan="7" align="center">รวมปริมาณการผลิต</td>
                                <td id="total_volume1"></td>
                                <td id="total_volume2"></td>
                                <td id="total_volume3"></td>
                                @endif
                                @endif
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                            {!!
                            $report_volume->appends(['search' => Request::get('search'),
                            'sort' => Request::get('sort'),
                            'direction' => Request::get('direction'),
                            'perPage' => Request::get('perPage'),
                            'filter_state' => Request::get('filter_state'),
                            'filter_created_by' => Request::get('filter_created_by'),
                            'filter_tb3_Tisno' => Request::get('filter_tb3_Tisno'),
                            'filter_start_month' => Request::get('filter_start_month'),
                            'filter_start_year' => Request::get('filter_start_year'),
                            'filter_end_month' => Request::get('filter_end_month'),
                            'filter_end_year' => Request::get('filter_end_year'),
                            ])->links()
                            !!}
                        </div>

                    </div>
                    <div id="test"></div>

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
    $(document).ready(function() {

    $("#filter_created_by").select2({minimumInputLength: 2});
    $("#filter_tb3_Tisno").select2({minimumInputLength: 2});

    $( "#filter_clear" ).click(function() {
        $('#filter_created_by').val('').select2();
        $('#filter_tb3_Tisno').val('').select2();
        $('#filter_start_month').val('').select2();
        $('#filter_start_year').val('').select2();
        $('#filter_end_month').val('').select2();
        $('#filter_end_year').val('').select2();
            window.location.assign("{{url('/rsurv/report_volume')}}");
    });

        @if(\Session::has('flash_message'))
        $.toast({
            heading: 'Success!',
            position: 'top-center',
            text: '{{session()->get('
            flash_message ')}}',
            loaderBg: '#70b7d6',
            icon: 'success',
            hideAfter: 3000,
            stack: 6
        });
        @endif

        //เลือกทั้งหมด
        $('#checkall').change(function(event) {

            if ($(this).prop('checked')) { //เลือกทั้งหมด
                $('#myTable').find('input.cb').prop('checked', true);
            } else {
                $('#myTable').find('input.cb').prop('checked', false);
            }

        });

    });

    function Delete() {

        if ($('#myTable').find('input.cb:checked').length > 0) { //ถ้าเลือกแล้ว
            if (confirm_delete()) {
                $('#myTable').find('input.cb:checked').appendTo("#myForm");
                $('#myForm').submit();
            }
        } else { //ยังไม่ได้เลือก
            alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
        }

    }

    function confirm_delete() {
        return confirm("ยืนยันการลบข้อมูล?");
    }

    function UpdateState(state) {

        if ($('#myTable').find('input.cb:checked').length > 0) { //ถ้าเลือกแล้ว
            $('#myTable').find('input.cb:checked').appendTo("#myFormState");
            $('#state').val(state);
            $('#myFormState').submit();
        } else { //ยังไม่ได้เลือก
            if (state == '1') {
                alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
            } else {
                alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
            }
        }

    }

    $(document).ready(function() {
        $('#myTable').each(function() {
            var sum = 0
            var sum2 = 0
            var sum3 = 0
            $(this).find('.volume_1').each(function() {
                var total1 = $.trim($(this).text());
                if (total1.length !== 0) {
                    sum += parseFloat(total1);
                }
            })
            $(this).find('.volume_2').each(function() {
                var total2 = $.trim($(this).text());
                if (total2.length !== 0) {
                    sum2 += parseFloat(total2);
                }
            })
            $(this).find('.volume_3').each(function() {
                var total3 = $.trim($(this).text());
                if (total3.length !== 0) {
                    sum3 += parseFloat(total3);
                }
            })

            $('#total_volume1').text(sum);
            $('#total_volume2').text(sum2);
            $('#total_volume3').text(sum3);
        })

    });
</script>

@endpush

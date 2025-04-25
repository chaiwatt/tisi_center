@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

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
            td:nth-of-type(1):before {
                content: "No.:";
            }

            td:nth-of-type(2):before {
                content: "เลือก:";
            }

            td:nth-of-type(3):before {
                content: "ชื่อ-สกุล:";
            }

            td:nth-of-type(4):before {
                content: "เลขประจำตัวประชาชน:";
            }

            td:nth-of-type(5):before {
                content: "หน่วยงาน:";
            }

            td:nth-of-type(6):before {
                content: "สาขา:";
            }

            td:nth-of-type(7):before {
                content: "ประเภทของคณะกรรมการ:";
            }

            td:nth-of-type(8):before {
                content: "ผู้สร้าง:";
            }

            td:nth-of-type(9):before {
                content: "วันที่สร้าง:";
            }

            td:nth-of-type(10):before {
                content: "สถานะ:";
            }

            td:nth-of-type(11):before {
                content: "จัดการ:";
            }

        }
    </style>

@endpush

@section('content')
    <div class="container-fluid" id="app_check_certificate">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการตรวจประเมิน</h3>

                    <div class="pull-right">
                        @can('assign_work-'.str_slug('check-assessment'))
                            @include('certify.includes.modal-add', [
                                'title'=> 'ระบบการตรวจประเมิน',
                                'users' => $select_users,
                                'route' => route('check_assessment.assign')
                            ])
                        @endcan
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => route('check_assessment.index'), 'method' => 'get', 'id' => 'myFilter']) !!}
                    <div class="col-md-4">
                        {!! Form::label('perPage', 'Show:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-5">
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('at', 'ความสามารถห้องปฏิบัติ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                            {!! Form::select('at', [1=>'CB',2=>'IB',3=>'ทดสอบ',4=>'สอบเทียบ'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกความสามารถห้องปฏิบัติ-', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('s', 'สถานะ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                            {!! Form::select('s', $arrStatus, null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('c', 'เจ้าหน้าที่ตรวจสอบ', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                            {!! Form::select('c', $select_users, null, ['class' => 'form-control', 'placeholder'=>'-เลือกเจ้าหน้าที่-', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('filter_start_date', 'วันที่มีคำสั่ง:', ['class' => 'col-md-4 control-label label-filter', ]) !!}
                        <div class="col-md-8">
                            {!! Form::text('filter_start_date', null, ['class' => 'form-control mydatepicker', 'autocomplete'=> 'off']) !!}
                        </div>
                    </div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('filter_end_date', 'ถึง:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                            {!! Form::text('filter_end_date', null, ['class' => 'form-control mydatepicker', 'autocomplete'=> 'off']) !!}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('q', 'search', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                            {!! Form::text('q', null, ['class' => 'form-control', 'placeholder'=>'search', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    @if (request()->query('at'))
                        <div class="col-md-4 m-t-15">
                            {!! Form::label('b', 'สาขา:', ['class' => 'col-md-4 control-label label-filter']) !!}
                            <div class="col-md-8">
                                {!! Form::select('b', $branches, null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-', 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>
                    @endif


                    <input type="hidden" name="sort" value="{{ Request::get('sort') }}"/>
                    <input type="hidden" name="direction" value="{{ Request::get('direction') }}"/>

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        {!! Form::open(['url' => 'certify/auditor/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['url' => 'certify/auditor/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state"/>
                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th class="text-center">@sortablelink('app_no', 'เลขที่คำขอ')</th>
                                <th>หน่วยงาน</th>
                                <th class="text-center">@sortablelink('lab_type', 'ห้องปฏิบัติการ')</th>
                                <th class="text-center">สาขา</th>
                                <th class="text-center">@sortablelink('created_at', 'วันที่ยื่น')</th>
                                <th>@sortablelink('status', 'สถานะคำขอ')</th>
                                <th class="text-center">เจ้าหน้าที่รับผิดชอบ</th>
                                <th class="text-center">รายละเอียด</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($apps as $app)
                                    <tr>
                                        <td>{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $apps->perPage() ) }}</td>
                                        <td>
                                            @if (!$app->assessment)
                                                <input type="checkbox" name="cb[]" class="cb" value="{{ $app->id }}">
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $app->app_no }}</td>
                                        <td>{{ $app->name }}</td>
                                        <td class="text-center">{{ $app->assessment_type("th") }}</td>
                                        <td class="text-center">
                                            @if($app->lab_type == 3)
                                            {{ $app->BranchTitle ?? '' }}
                                            @elseif($app->lab_type==4)
                                            {{ $app->ClibrateBranchTitle ?? '' }}
                                            @else
                                                {{-- {{ $app->get_branch() ? $app->get_branch()->title : '' }} --}}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $app->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $app->getStatus() }}</td>
                                        <td class="text-center">
                                            {{ !empty($app->assessment->checker->FullName)    ? $app->assessment->checker->FullName : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($app->assessment)
                                                <a href="{{ route('check_assessment.show', ['ca' => $app->assessment->id]) }}" class="btn btn-light">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                            {!!
                                $apps->appends([
                                    'at' => Request::get('at'),
                                    'b' => Request::get('b'),
                                    's' => Request::get('s'),
                                    'c' => Request::get('c'),
                                    'filter_start_date' => Request::get('filter_start_date'),
                                    'filter_end_date' => Request::get('filter_end_date'),
                                    'q' => Request::get('q'),
                                    'perPage' => Request::get('perPage'),
                                    'sort' => Request::get('sort'),
                                    'direction' => Request::get('direction'),
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
    <script src="{{ asset('js/app.js') }}" defer></script>

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
                format: 'dd/mm/yyyy'
            });

            $('.mydatepicker').datepicker().on('changeDate',function () {
                $('#myFilter').submit();
            });

            //เลือกทั้งหมด
            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.cb').prop('checked', true);
                } else {
                    $('#myTable').find('input.cb').prop('checked', false);
                }

            });

            $('.cb').on('change', function () {
                changeSelectAll();
            });

            $('#form_assign').on('submit', function (e) {
                let cbs = $('input.cb:checked');
                if (cbs.length === 0) {
                    e.preventDefault();
                    return;
                }

                let form = $(this);
                form.children('input.apps').remove();
                cbs.each(function () {
                    let value = $(this).val();
                    console.log(value);
                    let input = $('<input type="hidden" name="apps[]" class="apps" value="'+value+'" />');
                    input.appendTo(form);
                });
            })
        });

        function changeSelectAll() {
            let checkboxes = $('.cb');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    checkedCount++;
                }
            });

            if (checkedCount === checkboxes.length && checkboxes.length > 0) {
                $('#checkall').prop('checked', true);
            } else {
                $('#checkall').prop('checked', false);
            }
        }

        function Delete() {

            let size = $('#myTable').find('input.cb:checked').length;
            if (size > 0) {//ถ้าเลือกแล้ว
                if (confirm_delete(size)) {
                    $('#myTable').find('input.cb:checked').appendTo("#myForm");
                    $('#myForm').submit();
                }
            } else {//ยังไม่ได้เลือก
                alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
            }

        }

        function confirm_delete(size = 0) {
            return confirm("ยืนยันการลบข้อมูล "+size+" รายการ?");
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

    </script>

@endpush

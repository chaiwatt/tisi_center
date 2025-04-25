@extends('layouts.master')

@push('css')

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
    <div class="container-fluid" id="app_estimated_cost">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการประมาณการค่าใช้จ่าย</h3>

                    <div class="pull-right">
                        <a href="{{ route('estimated_cost.create', ['app' => $app ? $app->id : '']) }}" class="btn btn-success btn-sm"><i class="icon-plus"></i> เพิ่ม</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="Delete();">
                            <i class="fa fa-trash-o"></i> ลบ
                        </button>
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => route('estimated_cost.index', ['app' => $app ? $app->id : '']), 'method' => 'get', 'id' => 'myFilter']) !!}
                    <div class="row">
                      <div class="col-md-3 form-group">
                            {!! Form::label('filter_tb3_Tisno', 'สถานะ:', ['class' => 'col-md-2 control-label label-filter']) !!}
                            <div class="form-group col-md-10">
                            {!! Form::select('s', ['0' => 'ฉบับร่าง', '1' => 'เห็นชอบ', '2' => 'ไม่เห็นชอบ', '3' => 'ยกเลิกประมาณค่าใชจ่าย'], null, ['class' => 'form-control',
                            'placeholder'=>'-เลือกสถานะ-']); !!}
                           </div>
                      </div><!-- /form-group -->
                      <div class="col-md-2">
                          <div class="form-group">
                              <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                  <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                              </button>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group  pull-left">
                              <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                          </div>

                          <div class="form-group  pull-left m-l-15">
                              <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                  ล้าง
                              </button>
                          </div>
                      </div><!-- /.col-lg-1 -->
                      <div class="col-md-5">
                             {!! Form::label('filter_tb3_Tisno', 'search:', ['class' => 'col-md-2 control-label label-filter']) !!}
                               <div class="form-group col-md-5">
                                {!! Form::text('q', null, ['class' => 'form-control', 'placeholder'=>'search']); !!}
                              </div>
                              <div class="form-group col-md-5">
                                  {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                      {!! Form::select('perPage',
                                      ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100',
                                       '500'=>'500'], null, ['class' => 'form-control']); !!}
                                  </div>
                              </div>
                      </div><!-- /.col-lg-5 -->
                  </div><!-- /.row -->

                  <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">

                            <div class="row">
                              <div class="form-group col-md-6">
                                {!! Form::label('filter_tb3_Tisno', 'ความสามารถห้องปฏิบัติ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('at', [1=>'CB',2=>'IB',3=>'ทดสอบ',4=>'สอบเทียบ'], null, ['class' => 'form-control','id'=>'at', 'placeholder'=>'-เลือกความสามารถห้องปฏิบัติ-']); !!}
                                </div>
                              </div>
                              <div class="form-group col-md-6">
                                {!! Form::label('c', 'เจ้าหน้าที่ตรวจสอบ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('c', $select_users, null, ['class' => 'form-control', 'placeholder'=>'-เลือกเจ้าหน้าที่-','id'=>'c']); !!}
                                </div>
                              </div>

                            </div>

                            <div class="row">
                              @if (request()->query('at'))
                              <div class="form-group col-md-6">
                                {!! Form::label('b', 'สาขา:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('b', $branches, null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-','id'=>'b']); !!}
                                </div>
                              </div>
                               @endif
                            </div>
                        </div>
                    </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

						{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        {!! Form::open(['url' => 'certify/auditor/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state"/>
                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="3%"><input type="checkbox" id="checkall"></th>
                                <th class="text-center" width="15%">@sortablelink('app_no', 'เลขที่คำขอ')</th>
                                <th width="20%">หน่วยงาน</th>
                                <th class="text-center" width="10%">ห้องปฏิบัติการ</th>
                                <th class="text-center" width="10%">สาขา</th>
                                <th class="text-center" width="10%">สถานะ</th>
                                <th class="text-center" width="10%">@sortablelink('created_at', 'วันที่บันทึก')</th>
                                <th class="text-center" width="10%">ผู้บันทึก</th>
                                <th class="text-center" width="10%">รายละเอียด</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($costs as $cost)
                                    @php
                                        $appCost = $cost->applicant;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $costs->perPage() ) }}</td>
                                        <td>
                                            @if($cost->draft != 1)
                                            <input type="checkbox" name="cb[]" class="cb" value="{{ $cost->id }}">
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $appCost->app_no ?? '' }}</td>
                                        <td>{{ $appCost->BelongsInformation->name  ?? ''}}</td>
                                        <td class="text-center">{{ !empty($appCost) ? $appCost->assessment_type("th") : ''  }}</td>
                                        <td class="text-center">
                                             @if (!empty($appCost))
                                                @if($appCost->lab_type == 3)
                                                    {{ $appCost->BranchTitle ?? '' }}
                                                @elseif($appCost->lab_type==4)
                                                    {{ $appCost->ClibrateBranchTitle ?? '' }}
                                                @else
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $cost->getStatus() }}</td>
                                        <td class="text-center">
                                           {{ !empty($appCost->created_at) ? HP::DateThai($appCost->created_at) : ''  }}
                                        </td>
                                        <td class="text-center">
                                            {{  !empty($cost->checker->full_name) ?  @$cost->checker->full_name : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('estimated_cost.edit', ['ec' => $cost, 'app' => $app ? $app->id : '']) }}" class="btn btn-light btn-primary">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @if($cost->draft != 1 && $cost->draft != 3)
                                            <form action="{{ route('estimated_cost.destroy', ['ec' => $cost]) }}" method="post"onsubmit="return confirm('ต้องการลบใช่หรือไม่')" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn   btn-light btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                            {!!
                                $costs->appends([
                                    'at' => Request::get('at'),
                                    'b' => Request::get('b'),
                                    's' => Request::get('s'),
                                    'c' => Request::get('c'),
                                    'q' => Request::get('q'),
                                    'perPage' => Request::get('perPage'),
                                    'app' => Request::get('app'),
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

    {!! Form::open(['url' => route('estimated_cost.destroy.multiple', ['app' => $app ? $app->id : '']), 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

    {!! Form::close() !!}
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        $(document).ready(function () {
            $( "#filter_clear" ).click(function() {
                $('#s').val('').select2();
                $('#q').val('');

                $('#at').val('').select2();
                $('#c').val('').select2();
                $('#b').val('').select2();
                window.location.assign("{{url('/certify/estimated_cost')}}");
            });

            if( checkNone($('#at').val())  ||  checkNone($('#c').val()) || checkNone($('#b').val())  ){
                // alert('มีค่า');
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
 

            @if(\Session::has('message_error'))
                $.toast({
                    heading: 'Error!',
                    position: 'top-center',
                    text: '{{session()->get('message_error')}}',
                    loaderBg: '#ff6849',
                    icon: 'error',
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
        });
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
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

            let size = $('input.cb:checked').length;
            if (size > 0) {//ถ้าเลือกแล้ว
                if (confirm_delete(size)) {
                    $('input.cb:checked').appendTo("#myForm");
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

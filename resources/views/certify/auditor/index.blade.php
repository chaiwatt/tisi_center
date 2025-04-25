@extends('layouts.master')

@push('css')
  {{-- <link href="{{asset('css/multiselect.css')}}" rel="stylesheet"> --}}
  <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
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
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบแต่งตั้งคณะผู้ตรวจประเมิน</h3>

                    <div class="pull-right">

                        {{-- @can('add-'.str_slug('board-auditor'))
                            <a class="btn btn-success btn-sm waves-effect waves-light"
                               href="{{ url('certify/auditor/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan --}}

                        @can('delete-'.str_slug('board-auditor'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>
                    {!! Form::model($filter, ['url' => 'certify/auditor', 'method' => 'get', 'id' => 'myFilter']) !!}
                    <div class="row">
                      {{-- <div class="col-md-3 form-group">
                            <div class="form-group col-md-12">
                                {!! Form::select('filter_state', ['1'=>'IB', '2'=>'CB'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภทการตรวจ-']); !!}
                           </div>
                      </div><!-- /form-group --> --}}

                      <div class="col-md-6">
                        {!! Form::label('filter_search', 'search:', ['class' => 'col-md-2 control-label label-filter']) !!}
                        <div class="form-group col-md-10">
                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'search']); !!}
                        </div>
                      </div>
                      <div class="col-md-2">
                        {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                             {!! Form::select('perPage', 
                            ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100','500'=>'500'],
                             null, 
                             ['class' => 'form-control']); !!}
                        </div>
                  </div><!-- /.col-lg-5 -->
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
                  </div><!-- /.row -->

                  <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">

                            <div class="row">
                              <div class="form-group col-md-6">
                                {!! Form::label('filter_product_group', 'สาขา:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('filter_product_group', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-']); !!}
                                </div>
                              </div>
                              <div class="form-group col-md-6">
                              {!! Form::label('filter_start_date', 'วันที่ตรวจ:', ['class' => 'col-md-3 control-label label-filter']) !!}
                              <div class="col-md-8">
                                <div class="input-daterange input-group" id="date-range">
                                  {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                  <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                  {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                                </div>
                              </div>
                            </div>
                            </div>

                        </div>
                    </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

						{!! Form::close() !!}

                    <div class="clearfix"></div>


                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center" width="1%">#</th>
                                <th class="text-center" width="2%"><input type="checkbox" id="checkall"></th>
                                <th class="text-center" width="10%">@sortablelink('prefix_name', 'เลขที่คำขอ')</th>
                                <th class="text-center" width="10%">@sortablelink('identity_number', 'วันที่ตรวจประเมิน')</th>
                                <th class="text-center" width="10%">ห้องปฏิบัติการ</th>
                                <th class="text-center" width="10%">สาขา</th>
                                <th class="text-center" width="10%">@sortablelink('หนังสือแต่งตั้ง')</th>
                                <th class="text-center" width="10%">สถานะคำขอ</th>
                                <th class="text-center" width="7%">@sortablelink('created_by', 'วันที่บันทึก')</th>
                                <th class="text-center" width="7%">@sortablelink('created_at', 'ผู้บันทึก')</th>
                                <th class="text-center" width="10%">@sortablelink('state', 'เครื่องมือ')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($boardAuditors as $ba)
                                @php
                                       $CertiLabs = $ba->CertiLabs;
                                 @endphp
                                <tr>
                                    <td>{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $boardAuditors->perPage() ) }}</td>
                                    <td>
                                        <input type="checkbox" name="cb[]" class="cb" value="{{ $ba->id }}">   
                                    </td>
                                    <td>
                                        <a href="{{ url('/certify/auditor/'.$ba->id) }}">{{ $ba->certi_no ?? '-' }}</a>
                                        @if (!is_null($ba->reason_cancel))
                                         <br> <span class="text-danger" title="{{$ba->reason_cancel  }}">ยกเลิกคณะผู้ตรวจ</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(count($ba->DataBoardAuditorDate) > 0)
                                          {!!  @$ba->DataBoardAuditorDateTitle ?? ''  !!}
                                        @else 
                                            {{  HP::DateThai($ba->check_date) ?? '' }}  ถึง     {{  HP::DateThai($ba->check_end_date) ?? '' }} 
                                        @endif
                                    </td>
                                    <td>    
                                        @if (!is_null($CertiLabs))
                                             @if($CertiLabs->lab_type == 3)
                                                {{  'ทดสอบ' }}
                                            @elseif($CertiLabs->lab_type==4)
                                                {{ 'สอบเทียบ' }}
                                            @endif
                                        @else
                                                -
                                        @endif
                                    </td>
                                    <td>
                                       @if (!is_null($CertiLabs))
                                            @if($CertiLabs->lab_type == 3)
                                            {{ $CertiLabs->BranchTitle ?? '' }}
                                            @elseif($CertiLabs->lab_type==4)
                                                {{ $CertiLabs->ClibrateBranchTitle ?? '' }}
                                            @endif
                                        @else
                                                -
                                        @endif
                        

                                    </td>
                                    <td align="center">
                                        <a href="{{ url('certify/check/files') . '/' . $ba->file }}" target="_blank">
                                            <i class="fa fa-file-pdf-o" style="font-size:38px; color:red"
                                               aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td>{{ !is_null($CertiLabs) ? $CertiLabs->StatusTitle : ''  }}</td>
                                    <td>{{  HP::DateThai($ba->created_at) ?? '' }}</td>
                                    <td>{{ $ba->user_created->fullname  ?? null }}</td>
                                    <td>
                            
                                        @can('edit-'.str_slug('board-auditor'))
                                           @if ($ba->status_cancel == 1 || $ba->status == 1 )
                                            <a href="{{ url('/certify/auditor/'.$ba->id.'/edit', ['app' => $app ? $app->id : '']) }}"
                                                title="View board auditor" class="btn btn-info ">
                                                <i class="fa fa-search"></i>
                                            </a>
                                            @elseif((!is_null($CertiLabs) && $CertiLabs->status == 10))
                                                <a href="{{ url('/certify/auditor/'.$ba->id.'/edit', ['app' => $app ? $app->id : '']) }}"
                                                    title="View board auditor" class="btn btn-success ">
                                                    <i class="fa fa-search"></i>
                                                </a> 
                                            @else
                                            {{-- {{$ba->messageRecordTransactions()->count()}} --}}
                                            @if ($ba->messageRecordTransactions()->count() != 0)
                                                @if ($ba->message_record_status == 1)
                                                        <a href="{{route('certify.create_lab_message_record',['id' => $ba->id])}}"
                                                            title="บันทึกแต่งตั้ง" class="btn btn-warning ">
                                                            <i class="fa fa-book" aria-hidden="true"> </i>
                                                        </a>
                                                    @elseif($ba->message_record_status == 2)

                                                        <a href="{{route('view.create_lab_message_record',['id' => $ba->id])}}"
                                                            title="บันทึกแต่งตั้ง" class="btn btn-info ">
                                                            <i class="fa fa-book" aria-hidden="true"> </i>
                                                        </a>
                                                @endif
                                                
                                            @endif
                                            
                                                <a href="{{ url('/certify/auditor/'.$ba->id.'/edit', ['app' => $app ? $app->id : '']) }}"
                                                    title="Edit board auditor" class="btn btn-primary ">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                </a>
                                            @endif
                                        @endcan

                                        @if (!empty($ba->CertiLabs) && $ba->CertiLabs->status < 15 && $ba->status_cancel != 1 || (auth()->user()->isAdmin() === true   && $ba->status_cancel != 1) )
                                            @can('delete-'.str_slug('board-auditor'))
                                                <button class="btn btn-danger" data-toggle="modal"
                                                                            data-target="#modalDelete{{$ba->id}}"
                                                >
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                                @include ('certify.auditor.modaldelete',['id'=>$ba->id])
                                            @endcan
                                        @endif
                                     
                                    

                                            {{-- {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/certify/auditor/'.$ba->id, 'app' => $app ? $app->id : ''],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger ',
                                                    'title' => 'Delete board auditor',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!} --}}
                                       
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                            {!!
                                $boardAuditors->appends([
                                                            'filter_start_date' => Request::get('filter_start_date'),
                                                            'filter_end_date' => Request::get('filter_end_date'),
                                                            'filter_search' => Request::get('filter_search'),
                                                            'filter_product_group' => Request::get('filter_product_group'),
                                                            'perPage' => Request::get('perPage'),
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
     <!-- input calendar thai -->
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
     <!-- thai extension -->
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>
        $(document).ready(function () {
            $( "#filter_clear" ).click(function() {
                $('#s').val('').select2();
                $('#q').val('');

                $('#at').val('').select2();
                $('#filter_start_date').val('');
                $('#filter_end_date').val('');
                $('#b').val('').select2();
                window.location.assign("{{url('/certify/auditor')}}");
            });

            if( checkNone($('#at').val()) ||  checkNone($('#filter_start_date').val()) || checkNone($('#filter_end_date').val()) ||  checkNone($('#b').val())  ){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
            
            //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
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

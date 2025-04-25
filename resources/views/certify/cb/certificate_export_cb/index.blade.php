@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <style>

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


        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ออกใบรับรอง (CB) </h3>

                    <div class="pull-right">

                        {{-- @can('add-'.str_slug('certificateexportcb'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/certificate-export-cb/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan --}}

                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/certify/certificate-export-cb', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">

                            <div class="col-md-4 ">
                                {!! Form::label('filter_tb3_Tisno', 'คำค้นหา:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                <div class=" col-md-8">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'','id'=>'filter_search']); !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('filter_tb3_Tisno', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter text-right']) !!}
                                <div class=" col-md-5">
                                    {!! Form::select('filter_status',   HP::get_certify_export_status(),  null, ['class' => 'form-control','id'=>'filter_status', 'placeholder'=>'-เลือกสถานะ-']) !!}
                                </div>
                                <div class=" col-md-4">
                                    {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('perPage',  ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                                    </div>
                                </div>
                            </div><!-- /.col-lg-5 -->

                            <div class="col-md-2">
                                <div class="  pull-left">
                                    <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                                </div>

                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div>
                            </div><!-- /.col-lg-1 -->

                        </div><!-- /.row -->

                        <div class="row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('start_date', 'วันที่ออกใบรับรอง:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                <div class="col-md-8">
                                    <div class="input-daterange input-group date-range">
                                        {!! Form::text('filter_start_date', null, ['class' => 'form-control date']) !!}
                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                        {!! Form::text('filter_end_date', null, ['class' => 'form-control date']) !!}
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
                                <th class="text-center" width="10%">เลขที่คำขอ</th>
                                <th class="text-center" width="10%">หน่วยงาน</th>
                                <th class="text-center" width="10%">มาตรฐาน/สาขา</th>
                                <th class="text-center" width="10%">เลขที่ใบรับรอง</th>
                                <th class="text-center" width="10%">สถานะ</th>
                                <th class="text-center" width="10%">วันที่บันทึก</th>
                                {{-- <th class="text-center" width="10%">ผู้บันทึก</th> --}}
                                <th class="text-center" width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( count($export_cb) == 0)
                                <tr><td class="text-center" colspan="9">ไม่พบข้อมูล</td></tr>  
                            @endif
                            @foreach($export_cb as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $export_cb->perPage() ) }}</td>
                                    <td>
                                        {{  $item->app_no ?? '-'  }}
                                    </td>
                                    
                                    <td>
                                
                                        {{  $item->CertiCbTo->name ?? '-'  }}
                                    </td>
                                    <td>
                                    {{  !empty($item->CertiCbTo->CertificationBranchTo->title) ?  $item->CertiCbTo->CertificationBranchTo->title : ''   }}
                                    </td>
                                    <td>
                                    {{  $item->certificate ?? '-'  }}
                                    </td>
                                    <td> 
                                        {{  array_key_exists($item->status,HP::get_certify_export_status()) ?  HP::get_certify_export_status()[$item->status]  : '-'}}
                                    </td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    {{-- <td>
                                        {{  $item->UserTo->FullName ?? '-'  }}  
                                    </td> --}}
                                    <td class="text-center" >
                                        <a href="{{ url('certify/certificate-export-cb/'.$item->id.'/edit') }}" class="btn  ">
                                            <img src="{{ asset('storage/uploads/certify/thailand.png') }}" height="30px"/>
                                        </a>
                                    </td>
                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-wrapper">
                          {!!
                              $export_cb->appends(['search' => Request::get('search'),
                                                      'sort' => Request::get('sort'),
                                                      'direction' => Request::get('direction'),
                                                      'perPage' => Request::get('perPage'),
                                                      'filter_search' => Request::get('filter_search'),
                                                      'filter_status' => Request::get('filter_status'),
                                                      'filter_start_date' => Request::get('filter_start_date'),
                                                      'filter_end_date' => Request::get('filter_end_date'),
                                                      'filter_state' => Request::get('filter_state')
                                                     ])->render()
                          !!}
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

            //ช่วงวันที่
            $('.date-range').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            $( "#filter_clear" ).click(function() {
                $('#filter_status').val('').select2();
                $('#filter_search').val('');
                window.location.assign("{{url('/certify/certificate-export-cb')}}");
            });
            @if(\Session::has('flash_message'))
                $.toast({
                    heading: 'Success!',
                    position: 'top-center',
                    text: '{{session()->get('flash_message')}}',
                    loaderBg: '#33ff33',
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
            $('#checkall').change(function(event) {

              if($(this).prop('checked')){//เลือกทั้งหมด
                $('#myTable').find('input.cb').prop('checked', true);
              }else{
                $('#myTable').find('input.cb').prop('checked', false);
              }

            });

        });

        function Delete(){

          if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
            if(confirm_delete()){
              $('#myTable').find('input.cb:checked').appendTo("#myForm");
              $('#myForm').submit();
            }
          }else{//ยังไม่ได้เลือก
            alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
          }

        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function UpdateState(state){

          if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
              $('#myTable').find('input.cb:checked').appendTo("#myFormState");
              $('#state').val(state);
              $('#myFormState').submit();
          }else{//ยังไม่ได้เลือก
            if(state=='1'){
              alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
            }else{
              alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
            }
          }

        }

    </script>

@endpush

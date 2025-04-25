@extends('layouts.master')
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

@push('css')

    <style>

        .label-filter{
            margin-top: 7px;
        }

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
                    <h3 class="box-title pull-left">ออกใบรับรอง (LAB)</h3>

                    <div class="pull-right">

                        {{-- @can('add-'.str_slug('certificateexportlab'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/certificate-export-lab/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>
 
                    {!! Form::model($filter, ['url' => 'certify/certificate-export-lab', 'method' => 'get', 'id' => 'myFilter']) !!}

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
                                    {!! Form::select('filter_status',  HP::get_certify_export_status(),  null, ['class' => 'form-control','id'=>'filter_status', 'placeholder'=>'-เลือกสถานะ-']) !!}
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

                    <div class="table-responsive">

                        <table class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">#</th>
                                    <th width="1%"><input type="checkbox" id="checkall"></th>
                                    <th width="8%" class="text-center">เลขที่ใบคำขอ</th>
                                    <th width="20%" class="text-center">หน่วยงาน</th>
                                    <th width="10%" class="text-center">ประเภทการตรวจ</th>
                                    <th width="10%" class="text-center">เลขที่ใบรับรอง</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">วันที่ออกใบรับรอง</th>
                                    <th width="5%" class="text-center" width="100px">ใบรับรอง</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if( count($certificates) == 0)
                                    <tr><td class="text-center" colspan="9">ไม่พบข้อมูล</td></tr>  
                                @endif
                                @if ($certificates and $certificates->count() > 0)
                                    @foreach ($certificates as $certificate)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><input type="checkbox" name="cb[]" class="cb" value="{{$certificate->token}}"></td>
                                            <td class="text-center">{{$certificate->request_number ?? '-'}}</td>
                                            <td class="text-center">{{$certificate->trader_name ?? '-'}}</td>
                                            <td class="text-center">{{$certificate->LabTypeTitle ?? '-'}}</td>
                                            <td class="text-center"><b>{{$certificate->certificate_no ?? '-'}}</b></td>
                                            <td class="text-center">
                                                {{  array_key_exists($certificate->status,HP::get_certify_export_status()) ?  HP::get_certify_export_status()[$certificate->status]  : '-'}}
                                            </td>
                                            <td class="text-center">{{ !empty($certificate->certificate_date_start)?\Carbon\Carbon::parse($certificate->certificate_date_start)->format("d/m/Y"):'-' }}</td>
                                            <td class="text-center">
                                                    <a href="{{ url('certify/certificate-export-lab/'.$certificate->id.'/edit') }}" class="btn  ">
                                                    <img src="{{ asset('storage/uploads/certify/thailand.png') }}" height="30px"/>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $certificates->appends(['search' => Request::get('search'),
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
 
                $('#filter_search').val('');
                $('#filter_status').val('').select2();
 
                window.location.assign("{{url('/certify/certificate-export-lab')}}");
            });


            //ช่วงวันที่
            $('.date-range').datepicker({
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

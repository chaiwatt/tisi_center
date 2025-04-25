{{-- work on Certify\CheckCertificateLabController --}}
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
        .check_api_pid {cursor: pointer;}
        .modalDelete {text-align: left;}
    </style>

@endpush
@php
    // $querystringArray = Illuminate\Support\Facades\Input::only(['q']); 
    // dump($querystringArray);
@endphp
@section('content')

    <div class="container-fluid" id="app_check_certificate_index">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" id="microservice_simulation" > จำลอง Micro Service 🐆
                </button>
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอใบรับรองห้องปฏิบัติการ (LAB)</h3>

                    <div class="pull-right">
                        @if(isset($select_users) && count($select_users) > 0)
                            @can('assign_work-'.str_slug('certificate'))
                                {{-- @include('certify.includes.modal-add',  [
                                'title' => 'รบบตรวจสอบคำขอใบรับรองห้องปฏิบัติการ (LAB)',
                                'users' => $select_users,
                                'route' => route('check_certificate.assign')
                            ]) --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> มอบหมาย
                            </button>
                            <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                            <div class="modal fade" id="exampleModal">
                                <div  class="modal-dialog modal-xl"  role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="exampleModalLabel1">ระบบตรวจสอบคำขอใบรับรองห้องปฏิบัติการ (LAB)</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form_assign" action="{{ route('check_certificate.assign') }}" method="post" >
                                                {{ csrf_field() }}
                                                <div class="white-box">
                                                    <div class="row form-group">
                                                        <div class="col-md-12">
                                                                <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                                                    {!! Form::label('checker', 'เลือกเจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                                                    <div class="col-md-6">
                                                                        {!! Form::select('',
                                                                          $select_users,
                                                                          null,
                                                                         ['class' => 'form-control',
                                                                         'id'=>"checker",
                                                                         'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-']); !!}
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_items">&nbsp; เลือก</button>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="row " id="div_checker">
                                                        <div class="col-md-12">
                                                                <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                                                    <div class="col-md-4"></div>
                                                                    <div class="col-md-8">
                                                                        <div class="table-responsive">
                                                                            <table class="table color-bordered-table info-bordered-table">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th class="text-center" width="2%">#</th>
                                                                                    <th class="text-center" width="88%">เจ้าหน้าที่ตรวจสอบคำขอ</th>
                                                                                    <th class="text-center" width="10%">ลบ</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="table_tbody">

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button"class="btn btn-primary"   onclick="submit_form('1');return false"><i class="icon-check"></i> บันทึก</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                        {!! __('ยกเลิก') !!}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan
                        @endif
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => route('check_certificate.index'), 'method' => 'get', 'id' => 'myFilter']) !!}
                    <div class="row">
                      <div class="col-md-3 form-group">
                            {!! Form::label('filter_tb3_Tisno', 'สถานะ:', ['class' => 'col-md-2 control-label label-filter']) !!}
                            <div class="form-group col-md-10">
                            {!! Form::select('s', App\Models\Certify\Applicant\CertiLabStatus::select('title','id')->whereNotIn('id',[0])->pluck('title','id'), null, ['class' => 'form-control',
                            'placeholder'=>'-เลือกสถานะ-']); !!}
                           </div>
                      </div><!-- /form-group -->
                       <div class="col-md-5">
                             {!! Form::label('filter_tb3_Tisno', 'search:', ['class' => 'col-md-2 control-label label-filter']) !!}
                               <div class="form-group col-md-5">
                                {!! Form::text('q', null, ['class' => 'form-control', 'placeholder'=>'ค้นหา เลขที่คำขอ, หน่วยงาน']); !!}
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
                                {!! Form::label('filter_tb3_Tisno', 'ความสามารถห้องปฏิบัติ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('at', [1=>'CB',2=>'IB',3=>'ทดสอบ',4=>'สอบเทียบ'], null, ['class' => 'form-control','id'=>'at', 'placeholder'=>'-เลือกความสามารถห้องปฏิบัติ-']); !!}
                                </div>
                              </div>
                              <div class="form-group col-md-6">
                              {!! Form::label('filter_start_date', 'วันที่ยื่น:', ['class' => 'col-md-3 control-label label-filter']) !!}
                              <div class="col-md-8">
                                <div class="input-daterange input-group" id="date-range">
                                  {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                  <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                  {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                                </div>
                              </div>
                            </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                  {!! Form::label('c', 'เจ้าหน้าที่ตรวจสอบ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-7">
                                      {!! Form::select('c', $select_users, null, ['class' => 'form-control', 'placeholder'=>'-เลือกเจ้าหน้าที่-','id'=>'c']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_name', 'หน่วยงาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                      {!! Form::select('filter_name', App\Models\Certify\Applicant\CertiLab::select('name')->whereNotNull('name')->groupBy('name')->pluck('name', 'name'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยงาน-','id'=>'filter_name']); !!}
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                @if (request()->query('at'))
                                <div class="form-group col-md-6">
                                  {!! Form::label('b', 'สาขา:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-7">
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

                        {!! Form::open(['url' => 'certify/auditor/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['url' => 'certify/auditor/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state"/>
                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center text-top" width="1%">#</th>
                                <th class="text-center text-top" width="1%"><input type="checkbox" id="checkall"></th>
                                <th class="text-center text-top" width="8%">@sortablelink('app_no', 'เลขที่คำขอ')</th>
                                <th class="text-center text-top" width="14%">@sortablelink('trader_id', 'ห้องปฏิบัตรการ')</th>
                                <th class="text-center text-top" width="10%">@sortablelink('lab_type', 'ความสามารถ')</th>
                                <th class="text-center text-top" width="10%">@sortablelink('lab_type', 'สาขา')</th>
                                <th class="text-center text-top" width="10%">@sortablelink('created_at', 'วันที่ยื่นคำขอ')</th>
                                <th class="text-center text-top" width="10%">@sortablelink('status', 'สถานะคำขอ')</th>
                                <th class="text-center text-top" width="14%">@sortablelink('id', 'เจ้าหน้าที่ตรวจสอบคำขอ')</th>
                                <th class="text-center text-top" width="8%">@sortablelink('id', 'รายละเอียด')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($apps as $app)
                                    <tr>
                                        <td class="text-top">{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $apps->perPage() ) }}</td>
                                        <td class="text-center text-top">
                                                @if(!in_array($app->status,['4']))
                                                @can('assign_work-'.str_slug('certificate'))
                                                <input type="checkbox" name="cb[]" class="cb" value="{{ $app->id }}">
                                                @endcan
                                                @endif
                                        </td>
                                        <td class="text-top">{{ $app->app_no }}</td>
                                        <td class="text-top">
                                            {{-- {{ @$app->trader->trader_operater_name }} --}}
                                              {{ @$app->lab_name }} 
                                              <p style="font-style:italic;font-size:14px" >{{@$app->purposeType->name}}</p>
                                        </td>
                                        <td class="text-center">{{ $app->assessment_type("th") }}</td>
                                        <td class="text-center text-top">
                                            @if($app->lab_type == 3)
                                            {{ $app->BranchTitle ?? '' }}
                                            @elseif($app->lab_type==4)
                                            {{ $app->ClibrateBranchTitle ?? '' }}
                                            @elseif($app->lab_type==1 || $app->lab_type==2)
                                                {{ $app->get_branch() ? $app->get_branch()->title : '' }}
                                            @endif

                                        </td>
                                        <td class="text-center text-top">{{ $app->StartDateShow }}</td>
                                        <td class="text-center text-top">
                                            <!-- icon  -->
                                            @if(in_array($app->status,["14","15","22","23"]) )
                                               <img src="{{asset('plugins/images/money01.png')}}" width="25px" height="25px">
                                            @endif
                                            @if(in_array($app->status,["16","24"]))
                                               <img src="{{asset('plugins/images/money02.png')}}" width="25px" height="25px">
                                             @endif
                                             @if(in_array($app->status,["17","25"]))
                                               <img src="{{asset('plugins/images/money03.png')}}"  width="25px" height="25px">
                                             @endif
                                             <!-- icon  -->


                                                @php
                                                     $status =  !empty($app->certi_lab_status_to->title)   ? $app->certi_lab_status_to->title : '-'  ;
                                                @endphp

                                                @if($app->status == 4)
                                                    <button style="border: none;background-color: #ffffff;" data-toggle="modal"
                                                                                   data-target="#actionFour{{$loop->iteration}}"
                                                                                   data-id="{{ $app->token }}"  >
                                                       <i class="fa fa-close text-danger"></i> {{ $status  }}
                                                    </button>
                                                      @include ('certify.check_certificate_lab.modal.modalstatus4',  array('id' => $loop->iteration,
                                                                                                                'desc' => !empty($app->desc_delete) ? $app->desc_delete : @$app->check->desc ,
                                                                                                                'token'=>$app->token,
                                                                                                                'file' => $app->check->files4,
                                                                                                                'delete_file' => $app->certiLab_delete_file
                                                                                                                ))
                                                  @elseif($app->status == 7  && count($app->certi_auditors) > 0) <!-- อยู่ระหว่างดำเนินการ  -->
                                                  <button style="border: none" data-toggle="modal"
                                                                                data-target="#TakeAction{{$loop->iteration}}"    >
                                                     <i class="mdi mdi-magnify"></i>     อยู่ระหว่างดำเนินการ
                                                 </button>

                                                    @include ('certify/check_certificate_lab/modal.modalstatus10',['id'=> $loop->iteration,
                                                                                                                     'auditors' => $app->certi_auditors,
                                                                                                                    ])

                                                @else
                                                    {{ $status }}
                                                @endif



                                        </td>
                                        <td class="text-top">

                                            @if( !empty($app->check) && is_null($app->check->checker_id))
                                            {!!    $app->check->FullName ??  '-' !!}
                                            @else
                                                 {{  !empty($app->check->checker_id) ? $app->check->getChecker->getFullNameAttribute() : '-' }}
                                            @endif

                                        </td>
                                        <td class="text-center text-top">

                                            @if($app->status == 1 &&
                                                HP_API_PID::check_api('check_api_certify_check_certificate') &&
                                                HP_API_PID::CheckDataApiPid($app, (new App\Models\Certify\Applicant\CertiLab)->getTable()) != '')

                                            {{-- <a onclick="return false">  --}}
                                                {{-- <span class="btn btn-info check_api_pid" data-id="{{$app->id}}" data-url="{{ route('check_certificate.show', ['cc' => $app->check->id]) }}" data-table="{!! (new App\Models\Certify\Applicant\CertiLab)->getTable() !!}">   <i class="fa fa-search"></i></span> --}}

                                             {{-- </a> --}}

                                            @endif
                                            @if ($app->check)
                                            {{-- {{dd($app->check)}} --}}
                                                <a href="{{ route('check_certificate.show', ['cc' => $app->check->id]) }}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                            @endif
                                            @php
                                                $model = str_slug('check-certificate','-');
                                            @endphp
                                            @if(@auth()->user()->can('delete-'.$model) && (@$app->status >= 0 && @$app->status <= 22) && @$app->status != 4)
                                                <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalDelete{{$app->id}}" data-no="{{ $app->app_no }}" data-id="{{ $app->token }}">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                                @include ('certify.check_certificate_lab.modal.modaldelete',['id'=>$app->id,
                                                                                                        'token'=>$app->token,
                                                                                                        'app_no'=>$app->app_no])
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
                                    'filter_name' => Request::get('filter_name'),
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
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            @if(session()->has('flash_message'))
                Swal.fire({
                        position: 'center',
                        title: '{!! session()->get('flash_message') !!}',
                        showConfirmButton: true,
                        width: 800
                });
            @endif

        });
    </script>
    <script>
          $(document).ready(function () {
            $( "#filter_clear" ).click(function() {
                $('#s').val('').select2();
                $('#q').val('');

                $('#at').val('').select2();
                $('#filter_start_date').val('');
                $('#filter_end_date').val('');
                $('#filter_department').val('').select2();
                $('#c').val('').select2();
                $('#b').val('').select2();
                window.location.assign("{{url('/certify/check_certificate')}}");
            });

            $(".check_api_pid").click(function() {
                var id =   $(this).data('id');
                var url =   $(this).data('url');
                var table =   $(this).data('table');

                    $.ajax({
                        type: 'get',
                        url: "{!! url('certify/function/check_api_pid') !!}" ,
                        data: {
                            id:id,
                            table:table,
                            type:'false'
                        },
                    }).done(function( object ) {
                        Swal.fire({
                            position: 'center',
                            html: object.message,
                            showConfirmButton: true,
                            width: 800
                        }).then((result) => {
                            if (result.value) {
                                window.location = url;
                            }
                        });
                    });

            });



            if( checkNone($('#at').val()) ||  checkNone($('#filter_start_date').val()) || checkNone($('#filter_end_date').val()) || checkNone($('#filter_department').val()) || checkNone($('#c').val()) || checkNone($('#b').val())  ){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });

        });


        $(document).ready(function () {
            $('#add_items').on('click',function () {
                 let row =$('#checker').val();
                if(row != ''){
                    $('#div_checker').show();
                    let checker = $('#checker').find('option[value="'+row+'"]').text();
                    let table_tbody = $('#table_tbody');
                        // table_tbody.empty();
                        table_tbody.append('<tr>\n' +
                    '                    <td class="text-center">1</td>\n' +
                    '                    <td class="text-left">'+checker+'</td>\n' +
                    '                    <td class="text-center">' +
                    '                    <input type="hidden" name="checker[]"   class="data_checker" value="'+ row+'">\n' +
                    '                    <button type="button" class="btn btn-danger btn-xs inTypeDelete" data-types="'+row+'" ><i class="fa fa-remove"></i></button></td>\n' +
                    '                </tr>');
                    $("#checker option[value=" + row + "]").prop('disabled', true); //  เปิดรายการ
                    ResetTableNumber();
                    $('#checker').val('').select2();
                }else{
                    Swal.fire('กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!');
                }

            });
             ResetTableNumber();
            $(document).on('click','.inTypeDelete',function () {
                let types = $(this).attr('data-types');
                $("#checker option[value=" + types + "]").prop('disabled', false); //  เปิดรายการ
                $(this).parent().parent().remove();
                ResetTableNumber();
            });



            //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
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
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
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

         //รีเซตเลขลำดับ
        function ResetTableNumber(){
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                (rows.length==0)?$('#div_checker').hide():$('#div_checker').show();
                rows.each(function(index, el) {
                    $(el).children().first().html(index+1);
                });
          }

        function submit_form() {
                var data_checker = $(".data_checker").length;
                let cbs = $('input.cb:checked').length;

                if(data_checker > 0 && cbs > 0){
                       // Text
                         $.LoadingOverlay("show", {
                             image       : "",
                             text        : "กำลังบันทึก กรุณารอสักครู่..."
                        });
                     $('#form_assign').submit();
                }else if(cbs <= 0){
                    Swal.fire(
                        'กรุณาเลือกเลขที่คำขอ !!',
                        '',
                        'info'
                     )
                }else{
                    Swal.fire(
                        'กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!',
                        '',
                        'info'
                     )
                }
        }

        $('#microservice_simulation').click(function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('run-all-schedule') }}",
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    alert('เกิดข้อผิดพลาด: ' + xhr.responseText);
                }
            });
        });

    </script>

@endpush

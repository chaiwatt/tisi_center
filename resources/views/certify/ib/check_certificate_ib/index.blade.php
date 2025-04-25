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

		/*
		Label the data
    You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
		*/
		/*td:nth-of-type(1):before { content: "Column Name"; }*/

	}
  .check_api_pid {cursor: pointer;}
  .modalDelete {text-align: left;}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอใบรับรองหน่วยตรวจ (IB)  </h3>

                    <div class="pull-right">
                      @if(isset($select_users) && count($select_users) > 0)
                          @can('assign_work-'.str_slug('checkcertificateib'))
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> มอบหมาย
                          </button>
                          <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                          <div class="modal fade" id="exampleModal">
                              <div class="modal-dialog modal-xl"  role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                                          </button>
                                          <h4 class="modal-title" id="exampleModalLabel1">ระบบตรวจสอบคำขอใบรับรองหน่วยตรวจสอบ (IB)</h4>
                                      </div>
                                      <div class="modal-body">
                                          <form id="form_assign" action="{{ route('check_certificate-ib.assign') }}" method="post" >
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

        {!! Form::model($filter, ['url' => '/certify/check_certificate-ib', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="row">
                        <div class="col-md-3 form-group">
                              {{-- {!! Form::label('filter_tb3_Tisno', 'สถานะ:', ['class' => 'col-md-2 control-label label-filter text-right']) !!} --}}
                              <div class="form-group col-md-12">
                                  {!! Form::select('filter_status',
                                    $status,
                                    null,
                                   ['class' => 'form-control',
                                   'id'=>'filter_status',
                                   'placeholder'=>'-เลือกสถานะ-']) !!}
                             </div>
                        </div><!-- /form-group -->
                        <div class="col-md-5">
                               {{-- {!! Form::label('filter_tb3_Tisno', 'เลขที่คำขอ:', ['class' => 'col-md-2 control-label label-filter text-right']) !!} --}}
                                 <div class="form-group col-md-7">
                                  {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>' เลขที่คำขอ ','id'=>'filter_search']); !!}
                                </div>
                                <div class="form-group col-md-5">
                                    {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('perPage',
                                        ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100','500'=>'500'],
                                          null,
                                         ['class' => 'form-control']) !!}
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
                                      {!! Form::label('filter_inspector', 'เจ้าหน้าที่ตรวจสอบ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                      <div class="col-md-7">
                                          {!! Form::select('filter_inspector', $select_users, null, ['class' => 'form-control', 'placeholder'=>'-เลือกเจ้าหน้าที่-','id'=>'filter_inspector']); !!}
                                      </div>
                                  </div>

                                  <div class="form-group col-md-6">
                                      {!! Form::label('filter_start_date', 'วันที่บันทึก:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                      <div class="col-md-7">
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
                                      {!! Form::label('filter_type_unit', 'หน่วยตรวจประเภท:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                      <div class="col-md-7">
                                          {!! Form::select('filter_type_unit',
                                          ['1'=>'A','2'=>'B','3'=>'C'],
                                          null,
                                          ['class' => 'form-control',
                                          'id'=>'filter_type_unit',
                                          'placeholder'=>'-เลือกหน่วยตรวจประเภท-']) !!}
                                      </div>
                                  </div>

                                  <div class="form-group col-md-6">
                                      {!! Form::label('filter_name', 'หน่วยงาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                      <div class="col-md-7">
                                          {!! Form::select('filter_name', App\Models\Certify\ApplicantIB\CertiIb::select('name')->whereNotNull('name')->groupBy('name')->pluck('name', 'name'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยงาน-','id'=>'filter_name']); !!}
                                      </div>
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
                                <th  class="text-center text-top" width="2%">#</th>
                                <th  class="text-center text-top" width="3%">
                                  @can('assign_work-'.str_slug('checkcertificateib'))
                                    <input type="checkbox" id="checkall">
                                  @endcan
                                </th>
                                <th  class="text-center text-top" width="15%">เลขที่คำขอ</th>
                                <th  class="text-center text-top" width="15%">หน่วยตรวจ</th>
                                <th  class="text-center text-top" width="10%">หน่วยตรวจประเภท</th>
                                <th  class="text-center text-top"  width="10%">วันที่ยื่นคำขอ</th>
                                <th  class="text-center text-top"  width="15%">สถานะ</th>
                                <th  class="text-center text-top"  width="10%">เจ้าหน้าที่ตรวจสอบคำขอ</th>
                                <th  class="text-center text-top"  width="5%">รายละเอียด</th>
                               </tr>
                            </thead>
                            <tbody>
                              @php
                              $type_unit =  ['1'=>'A','2'=>'B','3'=>'C'] ;
                           @endphp
                            @if(count($certiIbs) > 0)
                              @foreach($certiIbs as $item)
                              <tr>
                                  <td class="text-center text-top">{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $certiIbs->perPage() ) }}</td>
                                  <td class="text-center text-top">
                                    @can('assign_work-'.str_slug('checkcertificateib'))
                                      @if(!in_array($item->status,['4']))
                                          <input type="checkbox" name="ib[]" class="ib" value="{{ $item->id }}">
                                        @endif
                                     @endcan
                                </td>
                                 <td class="text-top">
                                    {{
                                        @$item->app_no
                                    }}
                                </td>
                                  <td class="text-top">
                                      {!! @$item->name_unit ?? '-' !!}
                                 </td>
                                 <td class="text-top">
                                    {{ array_key_exists($item->type_unit,$type_unit) ? $type_unit[$item->type_unit]  :'-' }}
                                  </td>
                                  <td>{{ $item->StartDateShow }}</td>
                                  <td class="text-top">

                                         @php
                                           $TitleStatus =  $item->TitleStatus->title ?? '-' ;
                                        @endphp

                                       <!-- icon  -->
                                        @if(in_array($item->status,["15"]))
                                            <img src="{{asset('plugins/images/money01.png')}}" width="25px" height="25px">
                                         @endif
                                        @if(in_array($item->status,["16"]))
                                            <img src="{{asset('plugins/images/money02.png')}}" width="25px" height="25px">
                                        @endif
                                         @if(in_array($item->status,["17"]))
                                            <img src="{{asset('plugins/images/money03.png')}}"  width="25px" height="25px">
                                         @endif
                                       <!-- icon  -->
                                         <!-- status  -->
                                       @if($item->status == 4)
                                       <button style="border: none;background-color: #ffffff;" data-toggle="modal"
                                                                      data-target="#actionFour{{$loop->iteration}}"
                                                                      data-id="{{ $item->token }}"  >
                                          <i class="fa fa-close text-danger"></i> {{ $TitleStatus  }}
                                       </button>
                                         @include ('certify/ib/check_certificate_ib/modal.modalstatus4', array('id' => $loop->iteration,
                                                                                                                'desc' => $item->desc_delete ?? null ,
                                                                                                                'token'=> $item->token,
                                                                                                                'files' => $item->FileAttach8
                                                                                                              ))
                                         @elseif($item->status == 5)
                                           <button style="border: none;background-color: #ffffff;" data-toggle="modal"
                                                                      data-target="#NotValidated{{$loop->iteration}}"
                                                                      data-id="{{ $item->token }}"  >
                                          <i class="fa fa-close text-danger"></i> {{ $TitleStatus  }}
                                          </button>
                                         @include ('certify/ib/check_certificate_ib/modal.modalstatus5', array('id' => $loop->iteration,
                                                                                                                'desc' => $item->desc_delete ?? null ,
                                                                                                                'token'=> $item->token,
                                                                                                                'files' => $item->FileAttach10
                                                                                                              ))
                                          @elseif($item->status == 10 ) <!-- อยู่ระหว่างดำเนินการ  -->
                                                  <button style="border: none" data-toggle="modal"
                                                                                data-target="#TakeAction{{$loop->iteration}}"
                                                                                data-id="{{ $item->token }}"  >
                                                     <i class="mdi mdi-magnify"></i>     อยู่ระหว่างดำเนินการ
                                                 </button>

                                                 @include ('certify/ib/check_certificate_ib/modal.modalstatus10',['id'=> $loop->iteration,
                                                                                                                  'auditors' => $item->CertiIBAuditorsMany,
                                                                                                                 ])
                                         @else
                                            {{ $TitleStatus }}
                                         @endif
                                           <!-- status  -->

                                  </td>
                                  <td class="text-top">

                                       {!! $item->FullName ?? '-' !!}
                                 </td>
                                  <td class="text-center text-top">
                                      @can('view-'.str_slug('checkcertificateib'))

                                          {{-- @if($item->status == 1 &&
                                              HP_API_PID::check_api('check_api_certify_check_certificate_ib') &&
                                              HP_API_PID::CheckDataApiPid($item, (new App\Models\Certify\ApplicantIB\CertiIb)->getTable()) != '')

                                          <a onclick="return false"> 
                                              <span class="btn  btn-info check_api_pid" data-id="{{$item->id}}" data-url="{{ url('/certify/check_certificate-ib/' . $item->token) }}" data-table="{!! (new App\Models\Certify\ApplicantIB\CertiIb)->getTable() !!}">   <i class="fa fa-search"></i></span>

                                          </a>

                                          @else
                                              <a href="{{ url('/certify/check_certificate-ib/' . $item->token) }}"
                                                title="View Applicantib" class="btn btn-info ">
                                                <i class="fa fa-search"></i>
                                              </a>
                                          @endif --}}
                                            <a href="{{ url('/certify/check_certificate-ib/' . $item->token) }}" title="View Applicantib" class="btn btn-xs btn-info ">
                                                <i class="fa fa-search"></i>
                                            </a>
                                      @endcan
                                        @php
                                            $model = str_slug('check-checkcertificateib','-');
                                        @endphp
                                        @if(@auth()->user()->can('delete-'.$model) && (@$item->status >= 0 && @$item->status <= 22) && @$item->status != 4)
                                            <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalDelete{{$item->id}}" data-no="{{ $item->app_no }}" data-id="{{ $item->token }}">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            @include ('certify/ib/check_certificate_ib/modal.modaldelete',['id'=>$item->id,
                                                                                                    'token'=>$item->token,
                                                                                                    'app_no'=>$item->app_no])
                                        @endif
                                  </td>
                              </tr>
                           @endforeach

                            @else
                            <tr>
                              <td colspan="9" class="text-center" >
                                <h3 style="color:red">
                                  <b>
                                    <i class="fa fa-exclamation-circle" style="color:rgb(255, 230, 0)"></i>
                                    ไม่มีคำขอใบรับรองหน่วยตรวจ (IB)
                                  </b>
                                </h3>
                              </td>
                            </tr>
                            @endif

                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $certiIbs->appends([
                                                    'search' => Request::get('search'),
                                                    'sort' => Request::get('sort'),
                                                    'direction' => Request::get('direction'),
                                                    'perPage' => Request::get('perPage'),
                                                    'filter_status' => Request::get('filter_status'),
                                                    'filter_search' => Request::get('filter_search'),
                                                    'filter_inspector' => Request::get('filter_inspector'),
                                                    'filter_start_date' => Request::get('filter_start_date'),
                                                    'filter_end_date' => Request::get('filter_end_date'),
                                                    'filter_type_unit' => Request::get('filter_type_unit'),
                                                    'filter_name' => Request::get('filter_name')
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
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
          <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>
        $(document).ready(function () {
            $( "#filter_clear" ).click(function() {
                $('#filter_status').val('').select2();
                $('#filter_search').val('');

                $('#filter_type_unit').val('').select2();
                $('#filter_start_date').val('');
                $('#filter_end_date').val('');
                window.location.assign("{{url('/certify/check_certificate-ib')}}");
            });

            if( checkNone($('#filter_type_unit').val()) ||  checkNone($('#filter_start_date').val()) || checkNone($('#filter_end_date').val())    ){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
            function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
             }
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

          //ช่วงวันที่
          jQuery('#date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
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
                    $('#myTable').find('input.ib').prop('checked', true);
                } else {
                    $('#myTable').find('input.ib').prop('checked', false);
                }
            });

            $('#form_assign').on('submit', function (e) {
                let ibs = $('input.ib:checked');
                if (ibs.length === 0) {
                    e.preventDefault();
                    return;
                }

                let form = $(this);
                form.children('input.apps').remove();
                ibs.each(function () {
                    let value = $(this).val();
                    let input = $('<input type="hidden" name="apps[]" class="apps" value="'+value+'" />');
                    input.appendTo(form);
                });
            })

        });

        function Delete(){

          if($('#myTable').find('input.ib:checked').length > 0){//ถ้าเลือกแล้ว
            if(confirm_delete()){
              $('#myTable').find('input.ib:checked').appendTo("#myForm");
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

          if($('#myTable').find('input.ib:checked').length > 0){//ถ้าเลือกแล้ว
              $('#myTable').find('input.ib:checked').appendTo("#myFormState");
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
                let ibs = $('input.ib:checked').length;

                if(data_checker > 0 && ibs > 0){
                       // Text
                        $.LoadingOverlay("show", {
                             image       : "",
                             text        : "กำลังบันทึก กรุณารอสักครู่..."
                        });
                     $('#form_assign').submit();
                }else if(ibs <= 0){
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
    </script>

@endpush

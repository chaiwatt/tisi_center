@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
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
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แจ้งข้อมูลใบอนุญาต</h3>


                    <div class="clearfix"></div>
                    <hr>
                    {!! Form::model($filter, ['url' => '/esurv/tisi_license_notification', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="row">
                      <div class="col-md-4 form-group">
                        <div class="col-md-12">
                          {!! Form::select('filter_tb3_tisno',
                           App\Models\Basic\Tis::select( DB::raw("CONCAT('มอก.', tb3_Tisno, ' ', ".(new  App\Models\Basic\Tis)->getTable().".tb3_TisThainame) AS name, ' ', tb3_Tisno"))->pluck('name', 'tb3_Tisno'),
                           null,
                           ['class' => 'form-control',
                            'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                        </div>
                    </div><!-- /form-group -->
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
                <div class="col-lg-4">
                    <div class="form-group col-md-7">
                        <div class="col-md-12">
                             {!! Form::select('filter_state',[ '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                        {!! Form::label('filter_start_month', 'วันที่แจ้งใบอนุญาต:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-5">
                          {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                        </div>
                        <div class="col-md-3">
                          {!! Form::select('filter_start_year', HP::FiveYearListMinus(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                      {!! Form::label('filter_end_month', 'ถึง:', ['class' => 'col-md-3 control-label label-filter']) !!}
                      <div class="col-md-5">
                        {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                      </div>
                      <div class="col-md-3">
                        {!! Form::select('filter_end_year', HP::FiveYearListMinus(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                      </div>
                  </div>
                   </div>

                    <div class="row">
                      <div class="form-group col-md-6">
                        {!! Form::label('filter_department', 'กลุ่มงานหลัก:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-8">
                          {!! Form::select('filter_department', App\Models\Besurv\Department::whereIn('did',[10,11,12])->pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-']); !!}
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                      {!! Form::label('filter_sub_department', 'กลุ่มงานย่อย:', ['class' => 'col-md-3 control-label label-filter']) !!}
                      <div class="col-md-8">
                        {!! Form::select('filter_sub_department', !empty($subDepartments)?$subDepartments:[], null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลักย่อย-']); !!}
                      </div>
                     </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            {!! Form::label('filter_created_by', 'ผู้รับใบอนุญาต:', ['class' => 'col-md-4 control-label label-filter']) !!}
                            <div class="col-md-8">
                                @php
                                    $sso_user_table = (new App\Models\Sso\User)->getTable();
                                @endphp
                                {!! Form::select('filter_created_by',
                                        App\Models\Esurv\LicenseNotification::select(DB::raw("CONCAT($sso_user_table.name) AS title"), "$sso_user_table.id")
                                            ->Join($sso_user_table, "$sso_user_table.id", '=', 'tisi_license_notifications.created_by')
                                            ->distinct('tisi_license_notifications.created_by')
                                            ->pluck('title','id'),
                                        null,
                                        ['class' => 'form-control', 'placeholder'=>'-เลือกผู้รับใบอนุญาต-']
                                    );
                                !!}
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

                      {!! Form::open(['url' => '/esurv/tisi-license-notification/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/esurv/tisi-license-notification/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                              <th class="text-center" width="2%">#</th>
                              <th  class="text-center" width="30%">มาตรฐาน</th>
                              <th  class="text-center" width="30%">รายละเอียด</th>
                              <th  class="text-center" width="17%">ชื่อผู้บันทึก</th>
                              <th  class="text-center" width="10%">สถานะ</th>
                              <th  class="text-center" width="10%">เครื่องมือ</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach($inform_volume as $item)
                              <tr>
                                  <td class="text-center">{{ $loop->iteration or $item->id }}</td>
                                  <td>มอก. {{ @$item->Basic_Tis->tb3_Tisno.' '.@$item->Basic_Tis->tb3_TisThainame }}</td>
                                  <td>{{ $item->detail }}</td>
                                  <td>{{ $item->CreatedName  }} <br> {{ $item->TraderIdName  }} </td>
                                  <td>
                                    @php
                                     $status_css = ['0'=>'label-warning','1'=>'label-info', '2'=>'label-success', '3'=>'label-danger'];
                                      $status_receive  =  ['0' => 'ฉบับร่าง', '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'];
                                    @endphp
                                    @if(array_key_exists($item->state,$status_css) && array_key_exists($item->state,$status_receive))
                                       <span class="label {{ $status_css[$item->state] }}">
                                          <b>{{ $status_receive[$item->state] }}</b>
                                      </span>
                                    @else
                                       <span class="label label-info">
                                          <b>รอดำเนินการ</b>
                                      </span>
                                    @endif
                                  </td>
                                  <td class="text-center">
                                    @if($item->state == 1 || $item->state == 2)
                                      @can('edit-'.str_slug('tisi-license-notification'))
                                      <a href="{{ url('/esurv/tisi_license_notification/' . $item->id.'/edit') }}"
                                          title="href Tisi License Notification"  class="btn btn-primary btn-xs">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                      </a>
                                    @endcan
                                    @else
                                    @can('view-'.str_slug('tisi-license-notification'))
                                    <a href="{{ url('/esurv/tisi_license_notification/' . $item->id) }}"
                                          title="View Tisi License Notification" class="btn btn-info btn-xs">
                                          <i class="fa fa-eye" aria-hidden="true"></i>
                                     </a>
                                   @endcan
                                    @endif

                                  </td>
                              </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $inform_volume->appends(['search' => Request::get('search'),
                                                      'sort' => Request::get('sort'),
                                                      'direction' => Request::get('direction'),
                                                      'perPage' => Request::get('perPage'),
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
<script src="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

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
            $( "#filter_clear" ).click(function() {
                // alert('sofksofk');
                $('#filter_tb3_tisno').val('').select2();
                $('#filter_state').val('').select2();
                $('#filter_start_month').val('').select2();
                $('#filter_start_year').val('').select2();
                $('#filter_end_month').val('').select2();
                $('#filter_end_year').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_sub_department').val('').select2();
                $('#filter_created_by').val('').select2();
                window.location.assign("{{url('/esurv/tisi_license_notification')}}");
            });

            if( $('#filter_start_month').val()!="" ||  $('#filter_end_month').val() != ""    ||  $('#filter_department').val() != ""  ||  $('#filter_sub_department').val() != ""  ||  $('#filter_created_by').val() != ""){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }
            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
            //เลือกทั้งหมด
            // $('#checkall').change(function(event) {

            //   if($(this).prop('checked')){//เลือกทั้งหมด
            //     $('#myTable').find('input.cb').prop('checked', true);
            //   }else{
            //     $('#myTable').find('input.cb').prop('checked', false);
            //   }

            // });

        });

        // function Delete(){

        //   if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
        //     if(confirm_delete()){
        //       $('#myTable').find('input.cb:checked').appendTo("#myForm");
        //       $('#myForm').submit();
        //     }
        //   }else{//ยังไม่ได้เลือก
        //     alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
        //   }

        // }

        // function confirm_delete() {
        //     return confirm("ยืนยันการลบข้อมูล?");
        // }

        // function UpdateState(state){

        //   if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
        //       $('#myTable').find('input.cb:checked').appendTo("#myFormState");
        //       $('#state').val(state);
        //       $('#myFormState').submit();
        //   }else{//ยังไม่ได้เลือก
        //     if(state=='1'){
        //       alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
        //     }else{
        //       alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
        //     }
        //   }

        // }

    </script>
<script type="text/javascript">
  $(document).ready(function() {

             $('#filter_department').change(function(){
                $('#filter_sub_department').html('<option value=""> -เลือกกลุ่มงานหลักย่อย- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('/esurv/follow_up/data_sub_department') !!}"+ "/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                        $('#filter_sub_department').append('<option    value="'+index+'">'+data+'</option>');
                        });
                    });
                }
            });

  });
</script>
@endpush

@extends('layouts.master')

@push('css')

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
                    <h3 class="box-title pull-left">การตรวจติดตามผล</h3>

                    <div class="pull-right">

                      {{-- @can('edit-'.str_slug('follow_up'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan --}}

                      @can('add-'.str_slug('follow_up'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/esurv/follow_up/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan
                       @can('delete-'.str_slug('follow_up'))
                        <button  class="btn btn-danger btn-sm waves-effect waves-light" type="button" id="bulk_delete">
                          <span class="btn-label"><i class="fa fa-trash"></i></span><b>ลบ</b>
                        </button>
                        @endcan


                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/esurv/follow_up', 'method' => 'get', 'id' => 'myFilter']) !!}
                    <div class="row">
                      <div class="col-md-3 form-group">
                              {!! Form::select('filter_check_status',
                                      ['0' => 'ฉบับร่าง',
                                        '1' => 'อยู่ระหว่าง ผก.รับรอง',
                                        '2' => 'ผก.รับรองแล้ว',
                                        '3' => 'อยู่ระหว่าง ผอ.รับรอง',
                                        '4' => 'ผอ.รับรองแล้ว',
                                        '5' => 'ปรับปรุงแก้ไข'
                                      ],
                                      null,
                                        ['class' => 'form-control',
                                        'placeholder'=>'-เลือกสถานะ-']); !!}
                        </div><!-- /form-group -->
                       <div class="col-md-3 form-group">
                        {!! Form::text('filter_reference_number', null, ['class' => 'form-control','placeholder' =>'เลขที่เอกสาร']) !!}
                       </div><!-- /form-group -->

                       <div class="col-md-2 form-group">
                          {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                           <div class="col-md-8">
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                        </div>
                      </div><!-- /form-group -->
                      <div class="col-lg-2">
                        <div class="form-group">
                          <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                              <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                          </button>
                         </div>
                       </div><!-- /.col-lg-1 -->
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
                  </div><!-- /.row -->

                  <div id="search-btn" class="panel-collapse collapse">
                    <div class="white-box" style="display: flex; flex-direction: column;">
                      <div class="row">
                        <div class="col-md-6 form-group">
                            {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-8">
                                    {!! Form::select('filter_tb3_Tisno',
                                      HP::TisList() ,
                                    null,
                                    ['class' => 'form-control',
                                      'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                          {!! Form::label('filter_trader_autonumber', 'ผู้รับใบอนุญาต:', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-8">
                            {{-- {!! Form::select('filter_trader_autonumber',
                               App\Models\Esurv\FollowUp::select(DB::raw("CONCAT(tb4_tisilicense.tbl_tradeName) AS titels"),'tb4_tisilicense.tbl_taxpayer  AS ids')
                                                    ->Join('tb4_tisilicense','tb4_tisilicense.tbl_taxpayer','=','esurv_follow_ups.trader_autonumber')
                                                    ->distinct('esurv_follow_ups.trader_autonumber')
                                                    ->pluck('titels','ids') ,
                                                    null,
                                ['class' => 'form-control', 'placeholder'=>'-เลือกผู้รับใบอนุญาต-']); !!} --}}
                              {!! Form::select('filter_trader_autonumber',
                              // App\Models\Basic\TisiLicense::select('tbl_tradeName AS title','tbl_taxpayer AS id')
                                                  //  ->where('tbl_taxpayer','<>','')
                                                  // ->where('tbl_tradeName','<>','')
                                                   // ->distinct('tbl_taxpayer')
                                                  //  ->pluck('title','id'),
                              // App\Models\Esurv\FollowUp::select('tradename AS title','trader_autonumber AS id')
                                //                    ->groupBy('tradename')
                                  //                  ->pluck('title','id'),
                                  HP::get_followup_tradename_oldname_orderlatest(),
                                                    null,
                                ['class' => 'form-control', 'placeholder'=>'-เลือกผู้รับใบอนุญาต-']); !!}
                          </div>
                        </div>
                        </div><!-- /.row -->

                      <div class="row">
                        <div class="col-md-6 form-group">
                          {!! Form::label('filter_department', 'กลุ่มงานหลัก:', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-8">
                            {!! Form::select('filter_department',
                             App\Models\Besurv\Department::pluck('depart_name', 'did'),
                            null,
                            ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-']); !!}
                          </div>
                      </div>
                      <div class="col-md-6 form-group">
                        {!! Form::label('filter_sub_department', 'กลุ่มงานย่อย:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-8">
                          {!! Form::select('filter_sub_department',
                            !empty($subDepartments)?$subDepartments:[],
                            null,
                            ['class' => 'form-control',
                            'placeholder'=>'-เลือกกลุ่มงานหลักย่อย-']); !!}
                        </div>
                      </div>
                    </div><!-- /.row -->


                    </div>
                </div>

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/esurv/follow_up/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/esurv/follow_up/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('traderName','ชื่อผู้รับใบอนุญาต')</th>
                                {{-- <th>ชื่อผู้รับใบอนุญาต</th> --}}
                                <th>@sortablelink('tb3_Tisno','มาตรฐาน')</th>
                                <th>@sortablelink('factory_name','ชื่อโรงงาน')</th>
                                {{-- <th>ที่ตั้งโรงงาน</th> --}}
                                <th>@sortablelink('created_by','ผู้สร้าง')</th>
																<th>@sortablelink('created_at', 'วันที่สร้าง')</th>
                                <th class="text-center">@sortablelink('state', 'สถานะ')</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($follow_up as $item)
                                <tr>
                                    {{-- <td>{{ $loop->iteration or $item->id }}</td> --}}
                                    <td class="text-top">{{ $follow_up->perPage()*($follow_up->currentPage()-1)+$loop->iteration }}</td>
                                    <td>
                                      @if($item->check_status == '0')
                                      <input type="checkbox" name="cb[]" class="cb" value="{{ $item->id}}">
                                      @else
                                          <input type="checkbox" disabled>
                                      @endif

                                    </td>
                                    <td>{{ $item->tradename }}</td>
                                    <td>{{ $item->tb3_Tisno }}</td>
                                    <td>{{ $item->factory_name }}</td>
                                    {{-- <td>{{ $item->factory_address }}</td> --}}
                                    <td>{{ $item->createdName }}</td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    <td class="text-center">{{ $item->CheckStatusName }}

                                      {{-- @can('edit-'.str_slug('follow_up'))

                                          {!! Form::open([
                                                'method'=>'PUT',
                                                'url' => ['/esurv/follow_up/update-state'],
                                                'style' => 'display:inline'
                                              ])
                                          !!}

                                          {!! Form::hidden('cb[]', $item->id) !!}

                                          @if($item->state=='1')

                                            {!! Form::hidden('state', 0) !!}

                                            <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="ปิดใช้งาน">
                                              <i class="fa fa-check-circle fa-lg text-success"></i>
                                            </a>

                                          @else

                                            {!! Form::hidden('state', 1) !!}

                                            <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="เปิดใช้งาน">
                                              <i class="fa fa-times-circle fa-lg text-danger"></i>
                                            </a>

                                          @endif

                                          {!! Form::close() !!}
                                      @endcan --}}

                                    </td>
                                    <td>
                                        @can('view-'.str_slug('follow_up'))
                                            <a href="{{ url('/esurv/follow_up/' . $item->id) }}"
                                               title="View follow_up" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('follow_up'))
                                            <a href="{{ url('/esurv/follow_up/' . $item->id . '/edit') }}"
                                               title="Edit follow_up" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('follow_up'))
                                        @if($item->check_status == '0')
                                            {!! Form::open([
                                              'method'=>'DELETE',
                                              'url' => ['/esurv/follow_up', $item->id],
                                              'style' => 'display:inline'
                                                  ]) !!}
                                                  {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                          'type' => 'submit',
                                                          'class' => 'btn btn-danger btn-xs',
                                                          'title' => 'Delete follow_up',
                                                          'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                                  )) !!}
                                                  {!! Form::close() !!}
                                            @else
                                                  <button class="btn btn-danger btn-xs" title="Delete follow_up" disabled><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            @endif

                                        @endcan

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          @php
                              $page = array_merge($filter, ['sort' => Request::get('sort'),
                                                            'direction' => Request::get('direction'),
                                                            'perPage' => Request::get('perPage')
                                                           ]);
                          @endphp
                          {!!
                              $follow_up->appends($page)->render()
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

    <script>
        $(document).ready(function () {

                $("#filter_trader_autonumber").select2({minimumInputLength: 2});
                $("#filter_tb3_Tisno").select2({minimumInputLength: 2});

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
                $('#filter_tb3_Tisno').val('').select2();
                $('#filter_trader_autonumber').val('').select2();
                $('#filter_check_status').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_sub_department').val('').select2();
                window.location.assign("{{url('/esurv/follow_up')}}");
            });

            if( $('#filter_tb3_Tisno').val()!="" || $('#filter_trader_autonumber').val() != "" || $('#filter_department').val() != "" || $('#filter_sub_department').val() != "" ){
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
        jQuery(document).ready(function() {
                $('#filter_department').change(function(){
                $('#filter_sub_department').html('<option value=""> -เลือกกลุ่มงานหลักย่อย- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('esurv/follow_up/data_sub_department') !!}" + "/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_sub_department').append('<option value="'+index+'">'+data+'</option>');
                        });

                    });
                }
                });
        });
    </script>
  <script>
    $(document).ready(function () {
                //เลือกลบ
                $(document).on('click', '#bulk_delete', function(){
                        var rowsSelect = $('.cb:checked').length;
                         if(confirm("คุณแน่ใจหรือว่าต้องการลบข้อมูลนี้ " + rowsSelect + " แถว นี้ ?"))
                         {
                              var id = [];
                             $('.cb:checked').each(function(){
                                 id.push($(this).val());
                             });
                             if(id.length > 0)
                             {
                                 $.ajax({
                                     type:"POST",
                                     url:  "{{ url('esurv/follow_up/list/delete') }}",
                                     data:{
                                      "_token": "{{ csrf_token() }}",
                                       id:id},
                                     success:function(data)
                                     {
                                        $('#checkall').prop('checked',false );
                                        window.location.href = "{{url('/esurv/follow_up')}}"
                                     }
                                 });
                             }
                             else
                             {
                                 alert("โปรดเลือกอย่างน้อย 1 รายการ");
                             }
                         }
                     });

    });
</script>
@endpush

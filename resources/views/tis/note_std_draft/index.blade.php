@extends('layouts.master')

@push('css')
    <link href="{{asset('js/magnific-popup/dist/magnific-popup.css')}}" rel="stylesheet">
<style>
    .image-popup-vertical-fit:hover{
        cursor: pointer;
    }

    .centerOfRow{
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .imageGrow{
        transition: all .4s;
        cursor: pointer;
    }
    .imageGrow:hover{
        -ms-transform: scale(1.08) translateZ(0);
        -webkit-transform: scale(1.08) translateZ(0);
    }

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

                    <h3 class="box-title pull-left">ระบบเวียนร่างและประกาศรับฟังความคิดเห็นร่างกฎกระทรวง</h3>
                    <div class="pull-right">

                        {{-- @can('delete-'.str_slug('public_draft'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                      @can('edit-'.str_slug('public_draft'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateStatus(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateStatus(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                     {!! Form::model($filter, ['url' => '/tis/note_std_draft', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา เลขมอก., ชื่อมาตรฐาน']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
                            <div class="col-lg-3">
                    
                                <div class="form-group  pull-left">
                                    <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                                </div>
                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-default waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div>
                                <div class="form-group  pull-left m-l-15 ">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                   <div class="form-group col-md-7">
                                        {!! Form::select('filter_status_publish', ['1'=>'เผยแพร่','0'=>'ยังไม่เผยแพร่'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกสถานะแสดงหน้าเว็บ -']); !!}
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
                                    {!! Form::label('filter_result_draft', 'ผลการเวียนร่าง', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    {!! Form::select('filter_result_draft', ['1'=>'แก้ไขมาตรฐาน','2'=>'ประกาศเป็นมาตรฐานบังคับ','w'=>'รอผลการเวียนร่าง'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกผลการเวียนร่าง -','id'=>'filter_result_draft']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">

                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">

                                </div>
                                <div class="form-group col-md-6">

                                </div>
                            </div>
                            <div class="row">

                            </div>

                            </div>
                        </div>
                        <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                        <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
                    {!! Form::close() !!}



                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $note_std_drafts->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/note_std_draft/all', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => 'tis/note_std_draft/update/status', 'method' => 'put', 'id' => 'myFormStatus', 'class'=>'hide']) !!}
                        <input type="hidden" name="status" id="status" />
                      {!! Form::close() !!}

                      <table class="table table-borderless" id="myTable">
                          <thead>
                          <tr>
                            <th class="text-center" width="1%">#</th>
                            {{-- <th class="text-center" width="4%"><input type="checkbox" id="checkall"></th> --}}
                            <th class="text-center" width="8%">เลข มอก.</th>
                            <th class="text-center" width="20%">ชื่อมาตรฐาน</th>
                            <th class="text-center" width="10%">ผลการเวียนร่าง</th>
                            <th class="text-center" width="14%">ชื่อเรื่องประกาศ</th>
                            <th class="text-center" width="10%">หน้าเว็บ</th>
                            <th class="text-center" width="10%">วันที่เผยแพร่</th>
                            <th class="text-center" width="7%">เครื่องมือ</th>
                          </tr>
                          <script>
                              var qrArr = [];
                              var the_url = '';
                              var type = '';
                          </script>
                          @foreach ($note_std_drafts as $item)
                            <tr>
                              <td class="text-center">{{ $note_std_drafts->perPage()*($note_std_drafts->currentPage()-1)+$loop->iteration }}</td>
                              {{-- <td class="text-center"><input type="checkbox" name="cb[]" class="cb" value="{{ $item->token }}"></td> --}}
                              <td>{{ $item->tis_no ?? 'n/a' }}</td>
                              <td>{{ $item->title ?? 'n/a' }}</td>
                              <td>{{ $item->ResultDraftName ?? 'n/a' }}</td>
                              <td >{{ $item->title_draft ??  ''  }}</td>
                              <td>{{ $item->StatusPublishName??'n/a' }}</td>
                              <td >
                                    {{    !empty($item->start_date) &&  !empty($item->end_date) ? HP::DateThai($item->start_date).' - '. HP::DateThai($item->end_date) : '' }}
      
 
                     <!--               {!! Form::open([
                                        'method'=>'PUT',
                                        'url' => ['tis/note_std_draft/update/status'],
                                        'style' => 'display:inline'
                                      ])
                                  !!}

                                    {!! Form::hidden('cb[]', $item->id) !!}
{{--                                    // เปิด--}}
                                    @if($item->state == 1)

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
                                    {!! Form::close() !!} -->
                             </td>
                              <td >
                                <a href="{{ url('/tis/note_std_draft/' . $item->id . '/edit') }}"
                                title="Edit note std draft" class="btn btn-warning btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                </a>
                                @if ($item->status_publish == 1 &&  date('Y-m-d')  >=  $item->start_date &&   date('Y-m-d')   <=   $item->end_date )
                                    <a href="{{ url('/tis/listen_std_draft/form').'/'.$item->id }}"
                                        title="link" class="btn btn-default btn-xs" target="_blank">
                                        <i class="fa fa-link" aria-hidden="true"></i>
                                    </a>
                                @endif
                                {{-- @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.str_slug('note_std_draft')))
                                  {!! Form::open([
                                                  'method'=>'DELETE',
                                                  'url' => ['/tis/note_std_draft/'.$item->id],
                                                  'style' => 'display:inline'
                                  ]) !!}
                                  {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn btn-danger btn-xs',
                                          'title' => 'Delete',
                                          'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                  )) !!}
                                  {!! Form::close() !!}
                                @endif --}}
                              </td>

                          </tr>
                          @endforeach
                      </table>
                      <div class="pagination-wrapper">
                        {!!
                            $note_std_drafts->appends(['perPage' => Request::get('perPage'),
                                                    'filter_search' => Request::get('filter_search'),
                                                    'filter_status_publish' => Request::get('filter_status_publish'),
                                                    'filter_result_draft' => Request::get('filter_result_draft')
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
{{--    pop up--}}
    <script src="{{asset('js/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('js/magnific-popup/meg.init.js')}}"></script>
{{--    QR CODE--}}
    <script type="text/javascript" src="{{asset('js/qrCode/jquery.qrcode.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/qrCode/qrcode.js')}}"></script>
    <script>
        $(document).ready(function () {

           $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status_publish').val('').select2();
                $('#filter_result_draft').val('');

                window.location.assign("{{url('/tis/note_std_draft')}}");
            });

            if($('#filter_search').val()!="" || $('#filter_status_publish').select2('data').length>0
               || $('#filter_result_draft').val()!=""
            ){

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

            qrGen();

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            $('.mydatepicker').datepicker().on('changeDate',function () {
                if ($('#filter_end_date').val() !== '' && $('#filter_start_date').val() !== ''){
                    $('#myFilter').submit();
                }
            });

            //เลือกทั้งหมด
            $('#checkall').change(function(event) {

                if($(this).prop('checked')){//เลือกทั้งหมด
                    $('#myTable').find('input.cb').prop('checked', true);
                }else{
                    $('#myTable').find('input.cb').prop('checked', false);
                }

            });

            $(document).on('change', '#filter_type', function () {
                $('#filter_format').val('').change();
                $('#filter_standard').val('').change();
                let val_type = $(this).val();
                if (checkNone(val_type)){
                    get_format(val_type);
                    get_Number_Standard(val_type);
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

        function UpdateStatus(state){

            if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
                $('#myTable').find('input.cb:checked').appendTo("#myFormStatus");
                $('#status').val(state);
                $('#myFormStatus').submit();
            }else{//ยังไม่ได้เลือก
                if(state=='1'){
                    alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
                }else{
                    alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
                }
            }

        }

        function qrGen() {
            $('body').append("<div id='divQR' style='display: none'></div>");
            $.each(qrArr,function (k,v) {
                $('#divQR').qrcode({
                    render: 'canvas',
                    text: v.url,
                    width: 300,
                    height: 300
                });
                var canvas =  $('#divQR canvas');
                var img = canvas[k].toDataURL("image/png");
                $('#'+v.id).attr('src',img).attr('href',img);
            });
        }



        function get_format(val) {
            let selected = $('#filter_format');
            $.ajax({
                url: '{!! url('tis/public_draft/api/getFormat.api') !!}',
                method: "POST",
                data: {val_type: val, _token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.stringify(msg));
                if (data.status === true) {
                    selected.empty();
                    selected.append('<option value="">- เลือกรูปแบบการกำหนด -</option>');
                    $.each(data.format, function (k, v) {
                        selected.append('<option value="' + v.id + '">' + v.title + '</option>');
                    });
                    selected.val('').change();
                } else {
                    alert('ไม่พบข้อมูลรูปแบบกำหนดมาตรฐาน');
                    selected.val('').change();
                }
            });
        }

        function get_Number_Standard(val) {
            let selected = $('#filter_standard');
            $.ajax({
                url: '{!! url('tis/public_draft/api/getNumberFormula.api') !!}',
                method: "POST",
                data: {val_type: val, _token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.stringify(msg));
                if (data.status === true) {
                    selected.empty();
                    selected.append('<option value="">- เลือกเลขมาตรฐาน -</option>');
                    $.each(data.number_formula, function (k, v) {
                        let year = v.tis_year !== undefined ? v.tis_year:v.start_year !== undefined ? v.start_year:'-';

                        selected.append('<option value="' + v.tis_no + '">' + v.tis_no+' - '+year + '</option>');
                    });
                    selected.val('').change();
                } else {
                    alert('ไม่พบข้อมูลเลขมาตรฐาน');
                    selected.val('').change();
                }
            });
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>

@endpush

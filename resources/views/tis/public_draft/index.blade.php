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

                    <h3 class="box-title pull-left">เผยแพร่/เวียนร่างมาตรฐาน</h3>
                    <div class="pull-right">

                        @can('add-'.str_slug('public_draft'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/public_draft/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('public_draft'))
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

                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                     {!! Form::model($filter, ['url' => '/tis/public_draft', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อเรื่อง, เลขหนังสือ']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
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
                            <div class="col-lg-5">
                                <div class="form-group col-md-7">
                                    <div class="col-md-12">
                                        {!! Form::select('filter_status', ['1'=>'เปิด','0'=>'ปิด'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกสถานะ -']); !!}
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
                                    {!! Form::label('filter_type', 'ประเภทการเวียน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    {!! Form::select('filter_type', ['0'=>'เวียนร่าง','1'=>'เวียนทบทวน'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทการเวียน -','id'=>'filter_type']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_format', 'รูปแบบการกำหนด', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                        @if (isset($_GET['filter_type']))
                                        @php
                                            $formatArr = null;
                                            if ($_GET['filter_type'] == 0){
                                                $formatArr = App\Models\Basic\SetFormat::whereIn('id',[1,2])->get();
                                            }elseif ($_GET['filter_type'] == 1){
                                                $formatArr = App\Models\Basic\SetFormat::where('id',2)->get();}
                                        @endphp
                                        <select name="filter_format" id="filter_format" class="form-control">
                                            <option value="" selected>- เลือกรูปแบบการกำหนด -</option>
                                            @foreach ($formatArr as $format)
                                                @if (isset($_GET['filter_format']) and $_GET['filter_format'] != null and $format->id == $_GET['filter_format'])
                                                    <option value="{{$format->id}}" selected>{{$format->title ?? '-'}}</option>
                                                @else
                                                    <option value="{{$format->id}}">{{$format->title ?? '-'}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @else
                                            <select name="filter_format" id="filter_format" class="form-control">
                                                <option value="" selected>- เลือกรูปแบบการกำหนด -</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_branch', 'สาขา', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    {!! Form::select('filter_branch', \App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกสาขา -']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_standard', 'เลขมาตรฐาน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    @if (isset($_GET['filter_type']) && ($_GET['filter_type'] == '0' || $_GET['filter_type'] == '1'))
                                        @php
                                            $standArr = null;
                                            if ($_GET['filter_type'] == '0'){
                                                $standArr = App\Models\Tis\SetStandard::where('state', 1)->get();
                                            }elseif ($_GET['filter_type'] == '1'){
                                                $standArr = App\Models\Tis\Standard::where('state', 1)->get();
                                            }
                                        @endphp
                                        <select name="filter_standard" id="filter_standard" class="form-control">
                                            <option value="" selected>- เลือกเลขมาตรฐาน -</option>
                                            @foreach ($standArr as $stand)
                                                @php $year = !empty($stand->tis_year) ? $stand->tis_year:!empty($stand->start_year) ? $stand->start_year:'-'; @endphp
                                                @if (isset($_GET['filter_standard']) and $_GET['filter_standard'] != null and $stand->tis_no == $_GET['filter_standard'])
                                                    <option value="{{$stand->tis_no}}" selected>{{$stand->tis_no.' - '.$year}}</option>
                                                @else
                                                    <option value="{{$stand->tis_no}}">{{$stand->tis_no.' - '.$year}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @else
                                            @php $standArr = App\Models\Tis\PublicDraft::where('status', 1)->get(); @endphp
                                            <select name="filter_standard" id="filter_standard" class="form-control">
                                                <option value="" selected>- เลือกเลขมาตรฐาน -</option>
                                                @foreach ($standArr as $stand)
                                                    @if (isset($_GET['filter_standard']) and $_GET['filter_type'] == '' and $stand->tis_no == $_GET['filter_standard'])
                                                            <option value="{{$stand->tis_no}}" selected>{{$stand->tis_no}}</option>
                                                    @else
                                                            <option value="{{$stand->tis_no}}">{{$stand->tis_no}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
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
                    <span class="small">{{ 'ทั้งหมด '. $publicdrafts->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/public_draft/all', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => 'tis/public-draft/update/status', 'method' => 'put', 'id' => 'myFormStatus', 'class'=>'hide']) !!}
                        <input type="hidden" name="status" id="status" />
                      {!! Form::close() !!}

                      <table class="table table-borderless" id="myTable">
                          <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"><input type="checkbox" id="checkall"></th>
                            <th class="text-center">ชื่อมาตรฐาน</th>
                            <th class="text-center">เลข มอก.</th>
                            <th class="text-center">สาขา</th>
                            <th class="text-center">ประเภทการเวียน</th>
                            <th class="text-center">รูปแบบการกำหนด</th>
                            <th class="text-center">วันที่เวียน</th>
                            <th class="text-center">กลุ่มงาน</th>
                            <th class="text-center">Qrcode</th>
                            <th class="text-center">ผู้บันทึก</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">เครื่องมือ</th>
                          </tr>
                          <script>
                              var qrArr = [];
                              var the_url = '';
                              var type = '';
                          </script>
                          @foreach ($publicdrafts as $item)
                            <tr>
                              <td class="text-center">{{ $publicdrafts->perPage()*($publicdrafts->currentPage()-1)+$loop->iteration }}</td>
                              <td class="text-center"><input type="checkbox" name="cb[]" class="cb" value="{{ $item->token }}"></td>
                              <td class="text-center">{{ $item->StandardName ?? '-' }}</td>
                              {{-- <td class="text-center">{{ $item->getNumberStandard()->tis_no ?? '-' }}</td> --}}
                              <td class="text-center">{{ $item->tis_no ?? '-' }}</td>
                              <td class="text-center">{{ $item->getStand_Branch()->title ?? '-' }}</td>
                              <td class="text-center">{{ $item->getDraft_type() ?? '-' }}</td>
                              <td class="text-center">{{ $item->getSetFormat->title ?? '-' }}</td>
                              <td class="text-center">{{ HP::Datethai($item->mask_date) ?? '-' }}</td>
                              <td class="text-center">
                                  @if ($item->getStaff())
                                      {{ $item->getStaff()->order.' - '.$item->getStaff()->title }}
                                      @else
                                      -
                                  @endif
                              </td>
                              <td class="text-center">
                                  <?php
                                    $anniversary_date = \Carbon\Carbon::parse($item->anniversary_date);
                                    $today = \Carbon\Carbon::today();
                                  ?>
                                  @if ($anniversary_date < $today && $item->lock_qr == 'locked')
                                      <span class="text-danger">ครบกำหนดแล้ว</span>
                                      @else
                                      <img class="image-popup-vertical-fit imageGrow" style="width: 50px;height:50px;object-fit: cover;"
                                           src="" alt="{{$loop->iteration.'qr'}}" href="" id="{{'qr'.$loop->iteration}}">
                                      <script>
                                          the_url = '';
                                          type = '{{$item->public_draft_type ?? ''}}';
                                          if (type == 1){ // เวียนทบทวน
                                              the_url = '{{env('APP_URL').'/tis/comment_standard_reviews/form/'.$item->token}}';
                                          }else if (type == 0){
                                              the_url = '{{env('APP_URL').'/tis/comment_standard_drafts/form/'.$item->token}}';
                                          }
                                          qrArr.push({
                                              id:'{{'qr'.$loop->iteration}}',
                                              url:the_url,
                                          });
                                      </script>
                                  @endif
                              </td>
                              <td class="text-center">{{ $item->user_FullName() ?? '-' }}</td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'method'=>'PUT',
                                        'url' => ['tis/public-draft/update/status'],
                                        'style' => 'display:inline'
                                      ])
                                  !!}

                                    {!! Form::hidden('cb[]', $item->token) !!}
{{--                                    // เปิด--}}
                                    @if($item->status == 1)

                                        {!! Form::hidden('status', 0) !!}

                                        <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="ปิดใช้งาน">
                                            <i class="fa fa-check-circle fa-lg text-success"></i>
                                        </a>

                                    @else
                                        {!! Form::hidden('status', 1) !!}
                                        <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="เปิดใช้งาน">
                                            <i class="fa fa-times-circle fa-lg text-danger"></i>
                                        </a>
                                    @endif
                                    {!! Form::close() !!}
                                </td>
                              <td class="text-center text-nowrap">
                                  @if ($anniversary_date < $today && $item->lock_qr == 'locked')
                                      @else
                                          @if ($item->public_draft_type == 1)
                                              <a href="{{env('APP_URL').'/tis/comment_standard_reviews/form/'.$item->token}}" title="link" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
                                          @else
                                              <a href="{{env('APP_URL').'/tis/comment_standard_drafts/form/'.$item->token}}" title="link" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
                                          @endif
                                  @endif
                                  <a href="{{ url('/tis/public_draft/'.$item->token) }}"
                                     title="View committee" class="btn btn-info btn-xs">
                                      <i class="fa fa-eye" aria-hidden="true"></i>
                                  </a>

                                @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('edit-'.str_slug('public_draft')))
                                  <a href="{{ url('/tis/public_draft/'.$item->token.'/edit') }}"
                                     title="Edit committee" class="btn btn-primary btn-xs">
                                      <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                  </a>
                                @endif

                                @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.str_slug('public_draft')))
                                  {!! Form::open([
                                                  'method'=>'DELETE',
                                                  'url' => ['/tis/public_draft/'.$item->token],
                                                  'style' => 'display:inline'
                                  ]) !!}
                                  {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn btn-danger btn-xs',
                                          'title' => 'Delete',
                                          'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                  )) !!}
                                  {!! Form::close() !!}
                                @endif
                              </td>

                          </tr>
                          @endforeach
                      </table>
                      <div class="pagination-wrapper">
                        {!!
                            $publicdrafts->appends(['perPage' => Request::get('perPage'),
                                                    'filter_search' => Request::get('filter_search'),
                                                    'filter_status' => Request::get('filter_status'),
                                                    'filter_type' => Request::get('filter_type'),
                                                    'filter_format' => Request::get('filter_format'),
                                                    'filter_branch' => Request::get('filter_branch'),
                                                    'filter_standard' => Request::get('filter_standard')
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
                $('#filter_status').val('').select2();
                $('#filter_type').val('');
                $('#filter_format').val('');
                $('#filter_branch').val('');
                $('#filter_standard').val('');

                window.location.assign("{{url('/tis/public_draft')}}");
            });

            if($('#filter_type').val()!="" || $('#filter_format').val()!="" ||
              $('#filter_branch').val()!="" || $('#filter_standard').val()!=""
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

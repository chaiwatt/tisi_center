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
		td:nth-of-type(1):before { content: "ลำดับ:"; }
		td:nth-of-type(2):before { content: "เลือก:"; }
		td:nth-of-type(3):before { content: "ประเภท:"; }
		td:nth-of-type(4):before { content: "ชื่อผู้ประกอบการ:"; }
		td:nth-of-type(5):before { content: "วันที่จดทะเบียน:"; }
		td:nth-of-type(6):before { content: "รหัสสาขา:"; }
		td:nth-of-type(7):before { content: "อีเมล (ชื่อผู้ใช้งาน):"; }
		td:nth-of-type(8):before { content: "วันที่ลงทะเบียน:"; }
		td:nth-of-type(9):before { content: "วันที่เข้าใช้งานล่าสุด:"; }
		td:nth-of-type(10):before { content: "สถานะ:"; }
		td:nth-of-type(11):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')

    @php
        $applicant_types = HP::applicant_types();
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดการผู้ประกอบการ (SSO)</h3>

                    <div class="pull-right">

                        @can('add-'.str_slug('user-sso'))
                            <a class="btn btn-success btn-sm waves-effect waves-light m-r-10" href="{{ url('/sso/user-sso/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('edit-'.str_slug('user-sso'))

                            <a class="btn btn-success btn-sm waves-effect waves-light" href="#" onclick="Unblock();">
                                <span class="btn-label"><i class="mdi mdi-account-check"></i></span><b> ยกเลิกบล็อก</b>
                            </a>

                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Block();">
                                <span class="btn-label"><i class="mdi mdi-account-remove"></i></span><b> บล็อก</b>
                            </a>

                            <a class="btn btn-primary btn-sm waves-effect waves-light" href="#" onclick="ConfirmStatus();">
                                <span class="btn-label"><i class="mdi mdi-account-star"></i></span><b> ยันยืนผู้ใช้งาน</b>
                            </a>

                        @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/sso/user-sso', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">

                                    <div class="col-lg-6 m-r-0 p-r-0">
                                        <div class="input-group">
                                            {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจากชื่อ เลขผู้เสียภาษี หรืออีเมล์']); !!}
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" href="#advance-box" data-toggle="collapse" id="advance-btn">
                                                    <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                                </button>
                                                <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_search">ค้นหา</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-1 m-l-0">
                                        <div class="form-group pull-left m-l-0">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                                ล้าง
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">

                                        <div class="col-md-12 form-group">
                                            {!! Form::label('state', 'สถานะยืนยัน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('state', [ '1' => 'รอยืนยันตัวตนทาง E-mail','2' => 'ยืนยันตัวตนแล้ว','3' => 'รอเจ้าหน้าที่เปิดใช้งาน' ], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()', 'placeholder' => '-เลือกสถานะใช้งาน-']); !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="advance-box" class="panel-collapse collapse">
                                            <div class="white-box" style="display: flex; flex-direction: column;">

                                                <div class="row">
                                                    <div class="col-md-5 form-group">
                                                        {!! Form::label('block', 'สถานะใช้งาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                        <div class="col-md-8">
                                                            {!! Form::select('block', ['0' => 'ใช้งาน','1' => 'บล็อค' ], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()', 'placeholder' => '-เลือกสถานะใช้งาน-']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7 form-group">
                                                        {!! Form::label('state', 'วันที่ลงทะเบียน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                        <div class="col-md-8">
                                                            <div class="input-daterange input-group date-range">
                                                                <div class="input-group">
                                                                    {!! Form::text('registerDate_start', null, ['class' => 'form-control datepicker', 'placeholder' => "dd/mm/yyyy", 'required' => false]) !!}
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                                <label class="input-group-addon bg-white b-0 control-label">ถึง</label>
                                                                <div class="input-group">
                                                                    {!! Form::text('registerDate_end', null, ['class' => 'form-control datepicker', 'placeholder' => "dd/mm/yyyy", 'required' => false]) !!}
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-5 form-group">
                                                        {!! Form::label('type', 'ประเภท:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                        <div class="col-md-8">
                                                            {!! Form::select('type', $applicant_types, null, ['class' => 'form-control', 'onchange'=>'this.form.submit()', 'placeholder' => '-เลือกประเภท-']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7 form-group">
                                                        {!! Form::label('state', 'วันที่เข้าใช้งานล่าสุด:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                        <div class="col-md-8">
                                                            <div class="input-daterange input-group date-range">
                                                                <div class="input-group">
                                                                    {!! Form::text('lastvisitDate_start', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false]) !!}
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                                <label class="input-group-addon bg-white b-0 control-label">ถึง</label>
                                                                <div class="input-group">
                                                                    {!! Form::text('lastvisitDate_end', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false]) !!}
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                </div>
                            </div>
                        </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $users->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                        {!! Form::open(['url' => '/sso/user-sso/unblock', 'method' => 'post', 'id' => 'form-unblock', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['url' => '/sso/user-sso/confirm-status', 'method' => 'post', 'id' => 'form-confirm-status', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('applicanttype_id', 'ประเภท')</th>
                                <th>@sortablelink('name', 'ชื่อผู้ประกอบการ/เลขผู้เสียภาษี')</th>
                                <th>@sortablelink('date_niti', 'วันที่จดทะเบียน')</th>
                                <th>@sortablelink('branch_code', 'รหัสสาขา')</th>
                                <th>@sortablelink('email', 'อีเมล (ชื่อผู้ใช้งาน)')</th>
                                <th>@sortablelink('registerDate', 'วันที่ลงทะเบียน')</th>
                                <th>@sortablelink('lastvisitDate', 'วันที่เข้าใช้งานล่าสุด')</th>
                                <th>@sortablelink('state', 'สถานะ')</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}" data-state="{{ $item->state }}"></td>
                                    <td>{!! array_key_exists($item->applicanttype_id, $applicant_types) ? $applicant_types[$item->applicanttype_id] : '<i class="text-muted">ไม่มีข้อมูล</i>' !!}</td>
                                    <td>{{ $item->name }}
                                        {!! $item->check_api==1 ? '<i class="fa fa-check-circle-o text-success" title="ตรวจสอบกับหน่วยงานที่เกี่ยวข้องแล้ว"></i>' : null !!}
                                        <br>
                                        ( {!! $item->tax_number !!} )
                                    </td>
                                    <td>
                                       @if ($item->applicanttype_id == 2)
                                             {{ HP::DateThaiFull($item->date_of_birth) }}
                                        @else
                                             {{ HP::DateThaiFull($item->date_niti) }}
                                       @endif
                                    </td>
                                    <td>{{ !empty($item->branch_code) ? $item->branch_code : '-' }}</td>
                                    <td>
                                        {{ $item->email }}
                                        <br>
                                        (<span class="text-info">{{ $item->username }}</span>)
                                    </td>
                                    <td>{{ HP::dateTimeFormatN($item->registerDate) }}</td>
                                    <td>{{ HP::dateTimeFormatN($item->lastvisitDate) }}</td>
                                    <td>
                                        {!! $item->StateNameHtml !!}
                                        <p>
                                            @if($item->state >= 2)
                                                {!! $item->block==0 ? '(<span class="text-success">ใช้งาน</span>)' : '(<span class="text-danger" title="วันที่:'.( !empty($history->created_at)?HP::revertDate($history->created_at):null ).' เนื่องจาก:'.( !empty($history->remark)?$history->remark:null ).'">บล็อค</span>)' !!}
                                            @endif

                                        </p>
                                    </td>
                                    <td>
                                        @can('view-'.str_slug('user-sso'))
                                            <a href="{{ url('/sso/user-sso/' . $item->getKey()) }}"
                                               title="View soko" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('user-sso'))
                                            <a href="{{ url('/sso/user-sso/' . $item->getKey() . '/edit') }}"
                                               title="Edit soko" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!! $users->appends($filter)->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {!! Form::open(['url' => '/sso/user-sso/unblock', 'method' => 'post', 'id' => 'form-unblock', 'class'=>'hide']) !!}

    {!! Form::close() !!}

    {!! Form::open(['url' => '/sso/user-sso/confirm-status', 'method' => 'post', 'id' => 'form-confirm-status', 'class'=>'hide']) !!}

    {!! Form::close() !!}


    <!-- Modal Block -->
    <div id="block-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">

                {!! Form::open(['url' => '/sso/user-sso/block', 'method' => 'post', 'id' => 'form-block']) !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">เหตุผลที่บล็อค</h4>
                    </div>
                    <div class="modal-body">

                            <div class="form-group">
                                <label for="message-text" class="control-label">เหตุผล:</label>
                                <textarea class="form-control" id="remark" name="remark" placeholder="กรุณากรอกเหตุผล" required></textarea>
                            </div>

                            <span class="hide-id hide">

                            </span>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger waves-effect waves-light">บันทึก</button>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ปิด</button>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection



@push('js')

    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <!-- datepicker -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

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

            // Date Picker Thai
            $('.datepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            //เลือกทั้งหมด
            $('#checkall').change(function(event) {

              if($(this).prop('checked')){//เลือกทั้งหมด
                $('#myTable').find('input.cb').prop('checked', true);
              }else{
                $('#myTable').find('input.cb').prop('checked', false);
              }

            });

            $('#btn_clean').click(function (e) {

                $('#myFilter').find('input').val('');
                $('#myFilter').find('select').val('').select2();
                $('#myFilter').submit();
            });

            //เมื่อแสดง ค้นหาชั้นสูง
            $('#advance-box').on('show.bs.collapse', function () {
                $("#advance-btn").addClass('btn-inverse').removeClass('btn-primary');
                $("#advance-btn > span").addClass('glyphicon-menu-up').removeClass('glyphicon-menu-down');
            });

            //เมื่อซ่อน ค้นหาชั้นสูง
            $('#advance-box').on('hidden.bs.collapse', function () {
                $("#advance-btn").addClass('btn-primary').removeClass('btn-inverse');
                $("#advance-btn > span").addClass('glyphicon-menu-down').removeClass('glyphicon-menu-up');
            });

            //เซตค่าแสดง/ซ่อน ค้นหาชั้นสูง ตอนโหลด
            $('#advance-box').find('select, input').each(function(index, el) {
                if($(el).val()!=''){
                    $('#advance-box').collapse('show');
                    return false;
                }
            });

        });

        function Block(){

            if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
                $('#block-modal').modal('show');

                $("#form-block").find('.hide-id').html('');//clear ค่าเดิม
                $('#myTable').find('input.cb:checked').clone().appendTo($("#form-block").find('.hide-id'));// clone ค่าที่เลือก

            }else{//ยังไม่ได้เลือก
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการบล็อค");
            }

        }

        function confirm_block() {
            return confirm("ยืนยันการบล็อคผู้ใช้งาน?");
        }

        function Unblock(){

            if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
                if(confirm_unblock()){
                    $('#myTable').find('input.cb:checked').appendTo("#form-unblock");
                    $('#form-unblock').submit();
                }
            }else{//ยังไม่ได้เลือก
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการยกเลิกการบล็อค");
            }

        }

        function confirm_unblock() {
            return confirm("ยืนยันการยกเลิกบล็อคผู้ใช้งาน?");
        }

        function ConfirmStatus(){

            if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว

                if($('#myTable').find('input.cb:checked[data-state!="3"]').length == 0){
                    if(confirm("ยืนยันเปิดใช้ผู้ใช้งาน?")){
                        $('#myTable').find('input.cb:checked').appendTo("#form-confirm-status");
                        $('#form-confirm-status').submit();
                    }
                }else{
                    alert('กรุณาเลือกเฉพาะผู้ใช้ที่สถานะ "รอเจ้าหน้าที่เปิดใช้งาน"');
                }

            }else{//ยังไม่ได้เลือก
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการยืนยัน");
            }

        }

    </script>

@endpush

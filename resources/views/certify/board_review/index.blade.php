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
    td:nth-of-type(1):before { content: "No.:"; }
		td:nth-of-type(2):before { content: "เลือก:"; }
		td:nth-of-type(3):before { content: "ชื่อ-สกุล:"; }
		td:nth-of-type(4):before { content: "เลขประจำตัวประชาชน:"; }
		td:nth-of-type(5):before { content: "หน่วยงาน:"; }
		td:nth-of-type(6):before { content: "สาขา:"; }
		td:nth-of-type(7):before { content: "ประเภทของคณะกรรมการ:"; }
		td:nth-of-type(8):before { content: "ผู้สร้าง:"; }
		td:nth-of-type(9):before { content: "วันที่สร้าง:"; }
		td:nth-of-type(10):before { content: "สถานะ:"; }
		td:nth-of-type(11):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบแต่งตั้งคณะทบทวนผลการตรวจประเมิน</h3>

                    <div class="pull-right">

                      {{-- @can('edit-'.str_slug('board'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan --}}

                      @can('add-'.str_slug('board_review'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('certify/board_review/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('board_review'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>



                        <div class="col-md-4">
                              {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                              <div class="col-md-9">
                                    {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                              </div>
                        </div>

                      <div class="col-md-4">
                          {!! Form::label('filter_product_group', 'สาขา:', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-9">
                            {!! Form::select('filter_product_group', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-', 'onchange'=>'this.form.submit()']); !!}
                          </div>
                      </div>

                      <div class="col-md-4">
                          {!! Form::label('filter_search', 'เลขที่คำขอ', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-9">
                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'search', 'onchange'=>'this.form.submit()']); !!}
                          </div>
                      </div>

											<div class="col-md-4">
												  {!! Form::label('type', 'ประเภทการตรวจ', ['class' => 'col-md-3 control-label label-filter']) !!}
												  <div class="col-md-9">
														{!! Form::select('type', ['1'=>'IB', '2'=>'CB'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภทการตรวจ-', 'onchange'=>'this.form.submit()']); !!}
												  </div>
											</div>

                      <div class="col-md-4">
                        <div class="form-group {{ $errors->has('datestart') ? 'has-error' : ''}}">
                            {!! Form::label('name_lastname', 'วันที่บรรจุแต่งตั้ง :', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                               <div class="input-group">
                                    <input type="text" name="datestart" id="datestart" class="form-control mydatepicker" required/>
                                    {!! $errors->first('datestart', '<p class="help-block">:message</p>') !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group {{ $errors->has('datestart') ? 'has-error' : ''}}">
                            {!! Form::label('name_lastname', 'ถึง', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                               <div class="input-group">
                                    <input type="text" name="datestart" id="datestart" class="form-control mydatepicker" required/>
                                    {!! $errors->first('datestart', '<p class="help-block">:message</p>') !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                      </div>

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />



                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => 'certify/board_review/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => 'certify/board_review/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('prefix_name', 'เลขที่คำขอ')</th>
                                <th>@sortablelink('identity_number', 'วันที่ทบทวนผลการตรวจ')</th>
                                <th>@sortablelink('ประเภทการตรวจ')</th>
                                <th>@sortablelink('สาขา')</th>
                                <th>@sortablelink('หนังสือแต่งตั้ง')</th>
                                <th>@sortablelink('created_by', 'วันที่บันทึก')</th>
                                <th>@sortablelink('created_at', 'ผู้บันทึก')</th>
                                <th>@sortablelink('state', 'เครื่องมือ')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($boardReviews as $ba)
                                <tr>
                                    <td>{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $boardReviews->perPage() ) }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $ba->id }}"></td>
                                    <td><a href="{{ url('/certify/board_review/'.$ba->id) }}">{{ $ba->taxid ?? '' }}</a></td>
                                    <td>{{ $ba->judgement_date->format('d/m/Y') }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td align="center">
                                        <a href="{{ $ba->other_attach ? url('certify/board_review/files/'.$ba->other_attach) : '#' }}" target="_blank">
                                            <i class="fa fa-file-pdf-o" style="font-size:38px; color:red"
                                               aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td>{{ $ba->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $ba->user_created->fullname }}</td>
                                    <td>
                                        <a href="{{ url('/certify/board_review/'.$ba->id.'/edit') }}"
                                           title="Edit board auditor" class="btn btn-primary btn-xs">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                        </a>
                                        @can('delete-'.str_slug('board'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/certify/board_review/'.$ba->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete board auditor',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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

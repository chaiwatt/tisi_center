@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img{
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
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
                    <h3 class="box-title pull-left">ระบบข้อมูลใบรับรองระบบงาน</h3>

                    <div class="pull-right">

                      @can('add-'.str_slug('committee'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('certificate/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('committee'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan

                      @can('edit-'.str_slug('committee'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                              <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                              <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => 'certificate', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="col-md-4">
                            {!! Form::label('perPage', 'Show:') !!}
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control','placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('filter_search', 'Search:') !!}
                            {!! Form::text('filter_search', null, ['class' => 'form-control','placeholder'=>'เลขใบคำขอ,เลขที่ใบรับรอง', 'onchange'=>'this.form.submit()']); !!}
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('filter_state', 'สถานะ:') !!}
                            {!! Form::select('filter_state', ['1'=>'เปิด','0'=>'ปิด'], null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}
                        </div>

                    <div class="clearfix"></div>

                        <div class="col-md-4 m-t-15">
                            {!! Form::label('filter_assessment', 'ประเภทการตรวจ:') !!}
                            {!! Form::select('filter_assessment', ['1'=>'CB','2'=>'IB','3'=>'LAB'], null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}
                        </div>

{{--                        <div class="col-md-4">--}}
{{--                            {!! Form::label('filter_department', 'เลขที่ใบรับรอง:', ['class' => 'col-md-4 control-label label-filter']) !!}--}}
{{--                            <div class="col-md-8">--}}
{{--                                {!! Form::select('filter_department', App\Models\Basic\Department::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('filter_start_date', 'วันที่ออกใบรับรอง:') !!}
                        {!! Form::text('filter_start_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                    </div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('filter_end_date', 'ถึง:') !!}
                        {!! Form::text('filter_end_date', null, ['class' => 'form-control mydatepicker']) !!}
                    </div>

                    <div class="clearfix"></div>

                        <div class="col-md-4 m-t-15">
                            {!! Form::label('filter_standard', 'เลขมาตรฐาน:') !!}
                            {!! Form::select('filter_standard', \App\Models\Bcertify\Formula::pluck('title_en','id'), null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}
                        </div>

                    <div class="col-md-4 m-t-15">
                        {!! Form::label('filter_start_date_exp', 'วันที่ใบรับรองหมดอายุ:') !!}
                        {!! Form::text('filter_start_date_exp', null, ['class' => 'form-control mydatepicker_exp']) !!}
                    </div>

                    <div class="m-t-15 {{isset($_GET['perPage']) == true ? 'col-md-3':'col-md-4'}}">
                        {!! Form::label('filter_end_date_exp', 'ถึง:') !!}
                        {!! Form::text('filter_end_date_exp', null, ['class' => 'form-control mydatepicker_exp']) !!}
                    </div>

{{--                        <div class="col-md-4 m-t-15">--}}
{{--                            {!! Form::label('filter_cerType', 'ข้อมูลใบรับรอง:', ['class' => 'col-md-4 control-label label-filter']) !!}--}}
{{--                            <div class="col-md-8">--}}
{{--                                {!! Form::select('filter_cerType', ['option1'=>'ในระบบ','option2'=>'นอกระบบ'], null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'onchange'=>'this.form.submit()']); !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    <div class="col-md-1 m-t-15 {{isset($_GET['perPage']) == true ? 'show':'hide'}}">
                        <label>&emsp;</label>
                        <a href="{{url('certificate')}}" class="btn btn-primary btn-block">คืนค่า</a>
                    </div>
                    <div class="col-md-4"></div>

                    <div class="clearfix"></div>

{{--                   			<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />--}}
{{--											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />--}}

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive m-t-20">

                        <form id="myForm" class="hide" action="{{route('certificate.destroy',['id'=>'all'])}}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>

                      {!! Form::open(['url' => 'certificate/update/state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th class="text-center">ชื่อหน่วยตรวจ/หน่วยรับรอง/ห้องปฏิบัติการ</th>
                                <th class="text-center">เลขที่ใบคำขอ</th>
                                <th class="text-center">ประเภทการตรวจ</th>
                                <th>เลขที่ใบรับรอง</th>
                                <th>เลขมาตรฐาน</th>
                                <th class="text-center">วันที่ออกใบรับรอง</th>
                                <th class="text-center">เอกสารใบรับรอง</th>
                                <th class="text-center">วันที่ใบรับรองหมดอายุ</th>
                                <th class="text-center text-nowrap">ผู้บันทึก</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center" width="100px">เครื่องมือ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($certificates and $certificates->count() > 0)
                                @foreach ($certificates as $certificate)
                                    <tr>
                                        <td>
                                            @if (isset($_GET['perPage']))
                                                {{ (($certificates->currentPage() - 1 ) * $certificates->perPage() ) + $loop->iteration }}
                                                @else
                                                {{$loop->iteration}}
                                            @endif
                                        </td>
                                        <td><input type="checkbox" name="cb[]" class="cb" value="{{$certificate->token}}"></td>
                                        <td class="text-center">{{$certificate->unit_name ?? '-'}}</td>
                                        <td class="text-center">{{$certificate->request_number ?? '-'}}</td>
                                        <td class="text-center">{{$certificate->assessment_type() ?? '-'}}</td>
                                        <td>{{$certificate->certificate_file_number ?? '-'}}</td>
{{--                                        <td>{{$certificate->certificate_number ?? '-'}}</td>--}}
                                        <td>{{$certificate->get_formulaTH_EN() ?? '-'}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($certificate->certified_date)->format('d/m/Y') ?? '-'}}</td>
                                        <td class="text-center">
                                            @if ($certificate->certificate_file)
                                                <a href="{{ url('certificate/files/'.$certificate->certificate_file) }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o" style="font-size:38px; color:red" aria-hidden="true"></i>
                                                </a>
                                                @else
                                                -
                                            @endif
                                        </td>
{{--                                        <td class="text-center text-nowrap">{{$certificate->get_certificateOption()}}</td>--}}
                                        <td class="text-center">{{\Carbon\Carbon::parse($certificate->certified_exp)->format('d/m/Y') ?? '-'}}</td>
                                        <td>{{$certificate->user_FullName()}}</td>
                                        <td class="text-center">
                                            {!! Form::open([
                                                'method'=>'PUT',
                                                'url' => ['certificate/update/state'],
                                                'style' => 'display:inline'
                                              ])
                                          !!}

                                            {!! Form::hidden('cb[]', $certificate->token) !!}

                                            @if($certificate->state=='1')

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
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('certificate.show',['token'=>$certificate->token]) }}"
                                               title="View committee" class="btn btn-info btn-xs">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('certificate.edit',['token'=>$certificate->token]) }}"
                                               title="Edit committee" class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['certificate/'.$certificate->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete committee',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $certificates->appends(['filter_start_date' => Request::get('filter_start_date'),
                                                      'filter_end_date' => Request::get('filter_end_date'),
                                                      'filter_start_date_exp' => Request::get('filter_start_date_exp'),
                                                      'filter_end_date_exp' => Request::get('filter_end_date_exp'),
                                                      'filter_search' => Request::get('filter_search'),
                                                      'perPage' => Request::get('perPage'),
                                                      'filter_state' => Request::get('filter_state'),
                                                      'filter_assessment' => Request::get('filter_assessment'),
                                                      'filter_standard' => Request::get('filter_standard'),
                                                      'filter_cerType' => Request::get('filter_cerType'),
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
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            $('.mydatepicker_exp').datepicker({
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

            $('.mydatepicker_exp').datepicker().on('changeDate',function () {
                if ($('#filter_start_date_exp').val() !== '' && $('#filter_end_date_exp').val() !== ''){
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

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
    td:nth-of-type(3):before { content: "สาขาการสอบเทียบ:"; }
    td:nth-of-type(4):before { content: "มาตรฐาน:"; }
    td:nth-of-type(5):before { content: "ผู้สร้าง:"; }
    td:nth-of-type(6):before { content: "วันที่สร้าง:"; }
    td:nth-of-type(7):before { content: "สถานะ:"; }
    td:nth-of-type(8):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่ารายการผลทดสอบผลิตภัณฑ์</h3>

                    <div class="pull-right">

                      @can('add-'.str_slug('calibration_branch'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/bcertify/calibration_branch/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('calibration_branch'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model(@$filter, ['url' => '/bcertify/calibration_branch', 'method' => 'get', 'id' => 'myFilter']) !!}

											<div class="col-md-4">
												  {!! Form::label('perPage', 'Show:', ['class' => 'col-md-4 control-label label-filter']) !!}
												  <div class="col-md-8">
														{!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
												  </div>
											</div>

											<div class="col-md-4">
												  {!! Form::label('title', 'สถานะ:', ['class' => 'col-md-4 control-label label-filter']) !!}
												  <div class="col-md-8">
														{!! Form::select('filter_state', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'onchange'=>'this.form.submit()']); !!}
												  </div>
											</div>

                      <div class="col-md-4">
                          {!! Form::label('filter_formula', 'มาตรฐาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                          <div class="col-md-8">
                            {!! Form::select('filter_formula', App\Models\Bcertify\Formula::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-', 'onchange'=>'this.form.submit()']); !!}
                          </div>
                      </div>

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/bcertify/calibration_branch/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/bcertify/calibration_branch/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>เลข มอก.</th>
                                <th>ชื่อมาตรฐาน</th>
                                <th>รายการผลทดสอบ</th>
                                <th>วันที่บันทึก</th>
                                <th>สถานะ</th>
                                <th>ผู้บันทึก</th>
                                <th>รายละเอียด</th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="1"></td>
                                    <td>2350 – 2551</td>
                                    <td>รถจักรยานยนต์ เฉพาะด้านความปลอดภัย : สารมลพิษจากเครื่องยนต์ ระดับที่ 6</td>
                                    <td>ค่าสารมลพิษ, ค่าฝุ่นละออง, ค่าคาร์บอนไดออกไซด์</td>
                                    <td>01-ม.ค.-2562</td>
                                    <td>ใช้งาน</td>
                                    <td>ศิริชัย ภู่ภักดี</td>
                                    <td>
                                        @can('view-'.str_slug('calibration_branch'))
                                            <a href="{{ url('/bcertify/calibration_branch/1') }}"
                                               title="View calibration_branch" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('calibration_branch'))
                                            <a href="{{ url('/bcertify/calibration_branch/1/edit') }}"
                                               title="Edit calibration_branch" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('calibration_branch'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/bcertify/calibration_branch', 1],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete calibration_branch',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endcan

                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="1"></td>
                                    <td>10-2529</td>
                                    <td>ฟิวส์ก้ามปู</td>
                                    <td>กระแสไฟฟ้าที่ไหลผ่าน, การหลอมละลาย</td>
                                    <td>01-ก.พ.-2562</td>
                                    <td>ยกเลิก</td>
                                    <td>ศิริชัย ภู่ภักดี</td>
                                    <td>
                                        @can('view-'.str_slug('calibration_branch'))
                                            <a href="{{ url('/bcertify/calibration_branch/1') }}"
                                               title="View calibration_branch" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('calibration_branch'))
                                            <a href="{{ url('/bcertify/calibration_branch/1/edit') }}"
                                               title="Edit calibration_branch" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('calibration_branch'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/bcertify/calibration_branch', 1],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete calibration_branch',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endcan

                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        <div class="pagination-wrapper">

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

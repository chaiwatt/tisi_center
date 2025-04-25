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
    td:nth-of-type(3):before { content: "เลข มอก.:"; }
    td:nth-of-type(4):before { content: "ชื่อมาตรฐาน:"; }
    td:nth-of-type(5):before { content: "หน่วยนับ:"; }
    td:nth-of-type(6):before { content: "วันที่บันทึก:"; }
    td:nth-of-type(7):before { content: "ผู้บันทึก:"; }
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
                    <h3 class="box-title pull-left">ตั้งค่าหน่วยนับของมาตรฐาน</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/besurv/tis_unit', 'method' => 'get', 'id' => 'myFilter']) !!}

											<div class="col-md-3">
												  {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
												  <div class="col-md-9">
														{!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
												  </div>
											</div>

											<div class="col-md-3">
												  {!! Form::label('title', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter']) !!}
												  <div class="col-md-9">
														{!! Form::select('filter_state', ['1'=>'ระบุหน่วยนับแล้ว', '0'=>'ยังไม่ได้ระบุหน่วยนับ'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'onchange'=>'this.form.submit()']); !!}
												  </div>
											</div>

                      <div class="col-md-6">
                          {!! Form::label('filter_tis', 'ชื่อมาตรฐาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('filter_tis', null, ['class' => 'form-control', 'placeholder'=>'ค้นจากชื่อมาตรฐาน']); !!}
                                <span class="input-group-btn">
                                  <button type="submit" class="btn waves-effect waves-light btn-success">
                                    <i class="fa fa-search"></i>
                                  </button>
                                </span>
                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          {!! Form::label('filter_unit_code', 'หน่วยนับ:', ['class' => 'col-md-3 control-label label-filter']) !!}
                          <div class="col-md-9">
                            {!! Form::select('filter_unit_code', App\Models\Basic\UnitCode::pluck('name_unit', 'id_unit'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยนับ-', 'onchange'=>'this.form.submit()']); !!}
                          </div>
                      </div>

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/besurv/tis_unit/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/besurv/tis_unit/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('tb3_Tisno', 'เลข มอก.')</th>
                                <th>@sortablelink('tb3_TisThainame', 'ชื่อมาตรฐาน')</th>
                                <th>@sortablelink('id_unit', 'หน่วยนับ')</th>
                                <th>@sortablelink('updated_at', 'วันที่บันทึก')</th>
                                <th>@sortablelink('updated_by', 'ผู้บันทึก')</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tis_unit as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td>{{ $item->tb3_Tisno }}</td>
                                    <td>{{ $item->tb3_TisThainame }}</td>
                                    <td>
                                      @php $name_unit = @$item->unit_code->name_unit; @endphp
                                      {!! !is_null($name_unit)?$name_unit:'<i class="text-danger">ยังไม่ระบุหน่วยนับ</i>' !!}
                                    </td>
																		<td>{{ $item->updatedName }}</td>
                                    <td>{{ HP::DateThai($item->updated_at) }}</td>
                                    <td>

                                        @can('edit-'.str_slug('tis_unit'))
                                            <a href="{{ url('/besurv/tis_unit/' . $item->getKey() . '/edit') }}"
                                               title="Edit tis_unit" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
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
                                                            'perPage' => Request::get('perPage'),
                                                           ]);
                          @endphp
                          {!!
                              $tis_unit->appends($page)->render()
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

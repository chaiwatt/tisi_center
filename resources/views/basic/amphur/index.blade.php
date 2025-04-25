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
		td:nth-of-type(1):before { content: "No."; }
		td:nth-of-type(2):before { content: "เลือก"; }
		td:nth-of-type(3):before { content: "รหัสอำเภอ"; }
		td:nth-of-type(4):before { content: "ชื่ออำเภอ"; }
		td:nth-of-type(5):before { content: "รหัสไปรษณีย์"; }
		td:nth-of-type(6):before { content: "จังหวัด"; }
		td:nth-of-type(7):before { content: "ผู้สร้าง"; }
		td:nth-of-type(8):before { content: "วันที่สร้าง"; }
		td:nth-of-type(9):before { content: "สถานะ"; }
		td:nth-of-type(10):before { content: "จัดการ"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">อำเภอ</h3>

                    <div class="pull-right">

                      @can('edit-'.str_slug('amphur'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan

                      @can('add-'.str_slug('amphur'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/basic/amphur/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('amphur'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/basic/amphur', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">

                            <div class="col-md-5 form-group">
                                {!! Form::label('search', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                <div class="col-md-10">
                                    {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจาก รหัสอำเภอหรือชื่ออำเภอ']); !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group pull-left">
                                    <button type="submit" class="btn btn-success pull-right">ค้นหา</button>
                                </div>

                                <div class="form-group pull-left m-l-15">
                                    <button type="button" class="btn btn-danger" id="btn-clear">ล้าง</button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" href="#advance-box" data-toggle="collapse" id="advance-btn">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-down"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="advance-box" class="panel-collapse collapse row">
                            <div class="white-box" style="display:flow-root;">

                                <div class="col-md-3">
                                    {!! Form::label('filter_state', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter']) !!}
        							<div class="col-md-9">
        							    {!! Form::select('filter_state', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
        							</div>
        						</div>

                                <div class="col-md-4">
                                    {!! Form::label('filter_province', 'จังหวัด:', ['class' => 'col-md-3 control-label label-filter']) !!}
        							<div class="col-md-9">
        							    {!! Form::select('filter_province', App\Models\Basic\Province::orderby('PROVINCE_NAME')->pluck('PROVINCE_NAME', 'PROVINCE_ID'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
        							</div>
        						</div>

                            </div>
                        </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/basic/amphur/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/basic/amphur/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('AMPHUR_CODE', 'รหัสอำเภอ')</th>
                                <th>@sortablelink('AMPHUR_NAME', 'ชื่ออำเภอ')</th>
                                <th>@sortablelink('POSTCODE', 'รหัสไปรษณีย์')</th>
                                <th>@sortablelink('PROVINCE_ID', 'จังหวัด')</th>
                                <th>@sortablelink('created_by', 'ผู้สร้าง')</th>
																<th>@sortablelink('created_at', 'วันที่สร้าง')</th>
                                <th>@sortablelink('state', 'สถานะ')</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($amphur as $item)
                                <tr>
                                    <td>{{ $loop->iteration or $item->getKey() }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->getKey() }}"></td>
                                    <td>{{ $item->AMPHUR_CODE }}</td>
                                    <td>{{ $item->AMPHUR_NAME }}</td>
                                    <td>{{ $item->POSTCODE }}</td>
                                    <td>{{ @$item->province->PROVINCE_NAME }} </td>
                                    <td>{{ @$item->createdName }}</td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    <td>

                                      @can('edit-'.str_slug('amphur'))

                                          {!! Form::open([
                                                'method'=>'PUT',
                                                'url' => ['/basic/amphur/update-state'],
                                                'style' => 'display:inline'
                                              ])
                                          !!}

                                          {!! Form::hidden('cb[]', $item->getKey()) !!}

                                          @if($item->state=='1' || is_null($item->state))

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
                                      @endcan

                                    </td>
                                    <td>
                                        @can('view-'.str_slug('amphur'))
                                            <a href="{{ url('/basic/amphur/' . $item->getKey()) }}"
                                               title="View amphur" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('amphur'))
                                            <a href="{{ url('/basic/amphur/' . $item->getKey() . '/edit') }}"
                                               title="Edit amphur" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('amphur'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/basic/amphur', $item->getKey()],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete amphur',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endcan

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $amphur->appends($filter)->render()
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

            //เคลียร์ค่าตัวค้น
            $('#btn-clear').click(function(event) {
                $('#myFilter').find('input').val('');
                $('#myFilter').find('select').val('');
                $('#myFilter').submit();
            });

            //เมื่อแสดง ค้นหาชั้นสูง
            $('#advance-box').on('show.bs.collapse', function () {
                $("#advance-btn").addClass('btn-success').removeClass('btn-primary');
                $("#advance-btn > span").addClass('glyphicon-menu-up').removeClass('glyphicon-menu-down');
            });

            //เมื่อซ่อน ค้นหาชั้นสูง
            $('#advance-box').on('hidden.bs.collapse', function () {
                $("#advance-btn").addClass('btn-primary').removeClass('btn-success');
                $("#advance-btn > span").addClass('glyphicon-menu-down').removeClass('glyphicon-menu-up');
            });

            //เซตค่าแสดง/ซ่อน ค้นหาชั้นสูง ตอนโหลด
            $('#advance-box').find('select, input').each(function(index, el) {
                if($(el).val()!=''){
                    $('#advance-box').collapse('show');
                    return false;
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

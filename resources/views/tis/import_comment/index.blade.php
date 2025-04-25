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

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบนำเข้าข้อมูล ความคิดเห็นต่อร่างกฎกระทรวง</h3>

                    <div class="pull-right">

                      @can('add-'.str_slug('import-comment'))
                        <a class="btn btn-info btn-sm btn-outline waves-effect waves-light" href="{{  asset('template-exel/temple-ImportComment.xlsx') }}">
                          <span class="btn-label"><i class="fa fa-download"></i></span><b>โหลดตัวอย่างไฟล์</b>
                        </a>
                      @endcan
{{-- 
                      @can('edit-'.str_slug('import-comment'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan --}}

                      @can('add-'.str_slug('import-comment'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/import_comment/create') }}" target="_blank">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan
{{-- 
                      @can('delete-'.str_slug('import-comment'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/import_comment', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="form-group col-md-6">
                            {!! Form::label('filter_start_date', 'วันที่บันทึก:', ['class' => 'col-md-3 control-label label-filter text-right  ']) !!}
                            <div class="col-md-8">
                              <div class="input-daterange input-group" id="date-range">
                                {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                              </div>
                            </div>
                            </div>
                             <div class="form-group col-md-2">
                                {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
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
                        </div>

                        {{-- <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อ-สกุล']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->

                            <div class="col-lg-5">
                                <div class="form-group col-md-7">
                                    <div class="col-md-12">
                                        {!! Form::select('filter_status', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                                        </div>
                                    </div>
                            </div><!-- /.col-lg-5 -->
                        </div><!-- /.row --> --}}

                    	{{-- <div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                              <div class="row">
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_department', 'หน่วยงาน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                      {!! Form::select('filter_department[]', App\Models\Basic\Department::pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder'=>'-เลือกหน่วยงาน-', 'id'=>'filter_department']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_product_group', 'สาขา', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                      {!! Form::select('filter_product_group[]', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder'=>'-เลือกสาขา-', 'id'=>'filter_product_group']); !!}
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_tel', 'เบอร์โทร', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                      {!! Form::text('filter_tel', null, ['class' => 'form-control', 'placeholder'=>'ค้นจากเบอร์โทร', 'onchange'=>'this.form.submit()']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_email', 'E-mail', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                      {!! Form::text('filter_email', null, ['class' => 'form-control', 'placeholder'=>'ค้นจาก E-mail', 'onchange'=>'this.form.submit()']); !!}
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div> --}}
                      <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
                    {!! Form::close() !!}



                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $import_comment->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/import_comment/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/import_comment/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th width="1%" >#</th>
                                <th width="1%" ><input type="checkbox" id="checkall"></th>
                                <th width="10%" class="text-center">วันที่บันทึก</th>
                                <th width="30%" class="text-center">ไฟล์</th>
                                <th width="30%" class="text-center">รายละเอียด</th>
                                <th width="15%" class="text-center">ผู้บันทึก</th>
                                <th  width="10%" class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>

                             @foreach($import_comment as $item)
                                <tr>
                                    {{-- <td>{{ $loop->iteration or $item->id }}</td> --}}
                                    <td class="text-top">{{ $import_comment->perPage()*($import_comment->currentPage()-1)+$loop->iteration }}</td>
                                    <td class="text-top"><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td class="text-top">{{  !empty($item->save_date) ? HP::DateThai($item->save_date) : null  }}</td>
                                    <td class="text-top">
                                      @if (!is_null($item->attach_excel))
                                       @php
                                          //ไฟล์แนบ
                                          $attach_excel = json_decode($item->attach_excel);
                                      @endphp   
                                      @if(@$attach_excel->file_name!='' && HP::checkFileStorage($attach_path.@$attach_excel->file_name))
                                      <a href="{{ HP::getFileStorage($attach_path.$attach_excel->file_name) }}" target="_blank" style="width: auto">  {{ $attach_excel->file_client_name }}</a>
                                        @endif
                                      @else
                                        
                                      @endif
                                    </td>
                                    <td class="text-top">{{ $item->description ?? null }}</td>
                                    <td class="text-top">{{ $item->CreatedName  ?? null }}</td>
      
                                    <td class="text-center">
                                        {{-- @can('view-'.str_slug('import_comment'))
                                            <a href="{{ url('/tis/import_comment/' . $item->id) }}"
                                               title="View import_comment" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan --}}
                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('edit-'.str_slug('import-comment')))
                                            <a href="{{ url('/tis/import_comment/' . $item->id . '/edit') }}"
                                               title="Edit import_comment" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan
{{--
                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.str_slug('import_comment')))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/tis/import_comment', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete import_comment',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endif --}}
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $import_comment->appends([
                                                'sort' => Request::get('sort'),
                                                'direction' => Request::get('direction'),
                                                'perPage' => Request::get('perPage'),
                                                'filter_search' => Request::get('filter_search'),
                                                'filter_department' => Request::get('filter_department'),
                                                'filter_tel' => Request::get('filter_tel'),
                                                'filter_email' => Request::get('filter_email'),
                                                'filter_status' => Request::get('filter_status'),
                                                'filter_product_group' => Request::get('filter_product_group')
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
      <!-- input calendar thai -->
      <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
      <!-- thai extension -->
      <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
      <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script>
        $(document).ready(function () {

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_product_group').val('').select2();
                $('#filter_tel').val('');
                $('#filter_email').val('');

                window.location.assign("{{url('/tis/import_comment')}}");
            });

            if($('#filter_department').select2('data').length>0 || $('#filter_product_group').select2('data').length>0 ||
              $('#filter_tel').val()!="" || $('#filter_email').val()!=""
            ){

                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
            //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
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

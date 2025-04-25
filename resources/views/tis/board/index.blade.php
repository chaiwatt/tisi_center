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
                    <h3 class="box-title pull-left">รายชื่อคณะกรรมการ</h3>

                    <div class="pull-right">

                      @can('edit-'.str_slug('board'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan

                      @can('add-'.str_slug('board'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/board/create') }}" target="_blank">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('board'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/board', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อ-สกุล']); !!}
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
                        </div><!-- /.row -->

                    	<div id="search-btn" class="panel-collapse collapse">
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
                        </div>
                      <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $board->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/board/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/board/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th width="20%" class="text-center">@sortablelink('first_name', 'ชื่อ-สกุล')</th>
                                <th width="15%" class="text-center">หน่วยงาน</th>
                                <th width="10%" class="text-center">@sortablelink('tel', 'เบอร์โทร')</th>
                                <th width="15%" class="text-center">@sortablelink('email', 'E-mail')</th>
                                <th width="15%" class="text-center">สาขา</th>
                                <th class="text-center">@sortablelink('state', 'สถานะ')</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>

                             @foreach($board as $item)
                                <tr>
                                    {{-- <td>{{ $loop->iteration or $item->id }}</td> --}}
                                    <td class="text-top">{{ $board->perPage()*($board->currentPage()-1)+$loop->iteration }}</td>
                                    <td class="text-top"><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td class="text-top">{{ ($item->prefix_name.$item->first_name.' '.$item->last_name) }}</td>
                                    <td class="text-top">
                                      @foreach ($item->work_list as $key => $work)
                                        <div>
                                          {{ @$work->department->title }}
                                        </div>
                                      @endforeach
                                    </td>
                                    <td class="text-top">{{ $item->tel }}</td>
                                    <td class="text-top" style="word-wrap: break-word;">{{ $item->email }}</td>
                                    <td class="text-top">
                                      @foreach ($item->product_group_list as $key => $product_group)
                                        <div>
                                          {{ @$product_group->product_group->title }}
                                        </div>
                                      @endforeach
                                    </td>
                                  {{-- <td>{{$item->BoardTypeListName}}</td> --}}
                                    <td class="text-center">
                                      @can('edit-'.str_slug('board'))

                                          {!! Form::open([
                                                'method'=>'PUT',
                                                'url' => ['/tis/board/update-state'],
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
                                      @endcan

                                    </td>
                                    <td class="text-center">
                                        @can('view-'.str_slug('board'))
                                            <a href="{{ url('/tis/board/' . $item->id) }}"
                                               title="View board" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan


                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('edit-'.str_slug('board')))
                                            <a href="{{ url('/tis/board/' . $item->id . '/edit') }}"
                                               title="Edit board" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.str_slug('board')))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/tis/board', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete board',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $board->appends([
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

    <script>
        $(document).ready(function () {

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_product_group').val('').select2();
                $('#filter_tel').val('');
                $('#filter_email').val('');

                window.location.assign("{{url('/tis/board')}}");
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

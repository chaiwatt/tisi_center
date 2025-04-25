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
                    <h3 class="box-title pull-left">ระบบสรุปผลความคิดเห็นต่อร่างกฎกระทรวง</h3>

                    <div class="pull-right">

                      {{-- @can('edit-'.str_slug('CommentStandardReviewsAndDraft'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan

                      @can('add-'.str_slug('CommentStandardReviewsAndDraft'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/comment-standard-reviews-and-drafts/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('CommentStandardReviewsAndDraft'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/comment-standard-reviews-and-drafts', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ผู้ให้ข้อคิดเห็น, เบอร์โทร, email']); !!}
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
                                          {!! Form::select('filter_department', \App\Models\Basic\Department::pluck('title', 'id'), null,
                                           ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยงาน-','id'=>'filter_department']); !!}
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
                                  {!! Form::label('filter_tis_no', 'เลข มอก.', ['class' => 'col-md-4 control-label label-filter text-right ']) !!}
                                  <div class="col-md-8">
                                  {!! Form::select('filter_tis_no', App\Models\Tis\NoteStdDraft::pluck('tis_no', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกเลขมอก.-']); !!}
                                  </div>
                                </div>
                                {{-- <div class="form-group col-md-6">
                                  {!! Form::label('filter_branch', 'สาขา', ['class' => 'col-md-4 control-label label-filter text-right ']) !!}
                                  <div class="col-md-8">
                                  {!! Form::select('filter_branch', \App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-']); !!}
                                  </div>
                                </div> --}}
                              </div>
                            </div>
                        </div>
                      <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $result->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/comment-standard-reviews-and-drafts/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/comment-standard-reviews-and-drafts/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">วันที่</th>
                              <th class="text-center">ผู้ให้ข้อคิดเห็น</th>
                              <th class="text-center">หน่วยงาน</th>
                              <th class="text-center">ชื่อมาตรฐาน</th>
                              <th class="text-center">เลข มอก.</th>
                              <th class="text-center">สาขา</th>
                              <th class="text-center">เบอร์โทร</th>
                              <th class="text-center">E-mail</th>
                              <th class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($result as $item)
                              @php
                                  $item =  (object)$item;
                              @endphp
                                <tr>
                                    <td class="text-top">{{ $result->perPage()*($result->currentPage()-1)+$loop->iteration }}</td>
                                    <td>{{ HP::DateThai($item->created_at) ?? null }}</td>
                                    <td>{{ $item->name  ??  "n/a" }}</td>
                                    <td>
                                       {{  !empty($item->DepartmentNameName) ?  $item->DepartmentNameName : null  }}   
                                    </td>
                                    <td>     
                                      {{  !empty($item->note_std_draft->title) ?  $item->note_std_draft->title : null  }}   
                                    </td>
                                    <td>
                                      {{  !empty($item->note_std_draft->tis_no) ?  $item->note_std_draft->tis_no : null  }}   
                                    </td>
                                    <td>
                                      {{  !empty($item->note_std_draft->ProductGroupName) ?  $item->note_std_draft->ProductGroupName : null  }}   
                                    </td>
                                    <td>{{ $item->tel }}</td> 
                                    <td>{{ $item->email }}</td>
                                    <td class="text-center">
                                           @can('view-'.str_slug('commentstandardreviewsanddrafts'))
                                            <a href="{{url('tis/listen_std_draft/show/'.$item->id)}}"                             
                                                 title="View CommentStandardReviews" class="btn btn-info btn-xs" target="_blank">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                          @endcan
                                    </td>  

                       
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                            $result->appends(['sort' => Request::get('sort'),
                                                                'direction' => Request::get('direction'),
                                                                'perPage' => Request::get('perPage'),
                                                                'filter_search' => Request::get('filter_search'),
                                                                'filter_status' => Request::get('filter_status'),
                                                                'filter_tis_no' => Request::get('filter_tis_no'),
                                                                'filter_department' => Request::get('filter_department'),
                                                                'filter_branch' => Request::get('filter_branch')
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
                $('#filter_tis_no').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_branch').val('').select2();

                window.location.assign("{{url('/tis/comment-standard-reviews-and-drafts')}}");
            });

            if($('#filter_tis_no').val()!="" 
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

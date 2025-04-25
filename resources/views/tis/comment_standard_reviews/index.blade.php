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
                    <h3 class="box-title pull-left">ระบบข้อมูลความคิดเห็นในการทบทวนมาตรฐาน</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/comment_standard_reviews', 'method' => 'get', 'id' => 'myFilter']) !!}
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
                                  {!! Form::label('filter_tis_no', 'เลข มอก.', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                  {!! Form::select('filter_tis_no', App\Models\Tis\PublicDraft::where('status', 1)->pluck('tis_no', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกเลขมอก.-']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_department', 'หน่วยงาน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    {!! Form::select('filter_department', \App\Models\Basic\Department::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยงาน-']); !!}
                                  </div>
											          </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_branch', 'สาขา', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                  {!! Form::select('filter_branch', \App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสาขา-']); !!}
                                  </div>
                                </div>
                                <div class="form-group col-md-6">

                                </div>
                              </div>
                            </div>
                        </div>
                      <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $comment_standard_reviews->total() .' รายการ'}}</span>
                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/comment_standard_reviews/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/comment_standard_reviews/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>วันที่</th>
                                <th>ผู้ให้ข้อคิดเห็น</th>
                                <th>หน่วยงาน</th>
                                <th>ชื่อมาตรฐาน</th>
                                <th>เลข มอก.</th>
                                <th>สาขา</th>
                                <th>เบอร์โทร</th>
                                <th>E-mail</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comment_standard_reviews as $item)
                                <tr>
                                    {{-- <td>{{ $loop->iteration or $item->id }}</td> --}}
                                    <td class="text-top">{{ $comment_standard_reviews->perPage()*($comment_standard_reviews->currentPage()-1)+$loop->iteration }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->departmentNameName }}</td>
                                    <td>{{ $item->public_draft->StandardName ?? "n/a" }}</td>
                                    <td>{{ $item->public_draft->tis_no ?? "n/a"}}</td>
								                  	<td>{{ !is_null($item->public_draft) ? ($item->public_draft->getStand_Branch()->title) : "n/a"}}</td>
                                    <td>{{ $item->tel }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td class="text-center">
                                        @can('view-'.str_slug('comment_standard_reviews'))
                                            <a href="{{ url('/tis/comment_standard_reviews/show/' . $item->id) }}"
                                               title="View CommentStandardReviews" class="btn btn-info btn-xs">
                                                  <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        {{-- @can('edit-'.str_slug('comment_standard_reviews'))
                                            <a href="{{ url('/tis/comment_standard_reviews/' . $item->id . '/edit') }}"
                                               title="Edit CommentStandardDraf" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-retweet" aria-hidden="true"> </i>
                                            </a>
                                        @endcan --}}

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $comment_standard_reviews->appends(['sort' => Request::get('sort'),
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
                $('#filter_status').val('').select2();
                $('#filter_tis_no').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_branch').val('').select2();

                window.location.assign("{{url('/tis/comment_standard_reviews')}}");
            });

            if($('#filter_tis_no').val()!="" ||
              $('#filter_department').val()!="" || $('#filter_branch').val()!=""
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

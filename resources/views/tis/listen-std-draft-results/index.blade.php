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
                    <h3 class="box-title pull-left">สรุปผลความคิดเห็นต่อร่างกฎกระทรวง</h3>

                    <div class="pull-right">

                      {{-- @can('edit-'.str_slug('ListenStdDraftResult'))

                          <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                            <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                          </a>

                          <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                            <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                          </a>

                      @endcan

                      @can('add-'.str_slug('ListenStdDraftResult'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/listen-std-draft-results/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('ListenStdDraftResult'))
                          <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                          </a>
                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/listen-std-draft-results', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นชื่อมาตรฐานหรือเลข มอก.']); !!}
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
                                        {!! Form::label('filter_result_draft', 'ผลการเวียนร่าง', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_result_draft', ['1'=>'แก้ไขมาตรฐาน','2'=>'ประกาศเป็นมาตรฐานบังคับ','w'=>'รอผลการเวียนร่าง'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกผลการเวียนร่าง -','id'=>'filter_result_draft']); !!}
                                    </div>
                                    </div>
                                    <div class="form-group col-md-6">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">

                                    </div>
                                    <div class="form-group col-md-6">

                                    </div>
                                </div>
                                <div class="row">

                                </div>

                            </div>
                        </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $listenstddraftresults->total() .' รายการ'}}</span>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/tis/listen-std-draft-results/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/listen-std-draft-results/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">#</th>
                                    {{-- <th><input type="checkbox" id="checkall"></th> --}}
                                    <th rowspan="2" class="text-center" width="20%">มาตรฐาน</th>
                                    <th rowspan="2" class="text-center" width="15%">ผลการเวียนร่าง</th>
                                    <th rowspan="2" class="text-center" width="15%">สถานะการเผยแพร่</th>
                                    <th colspan="4" class="text-center" >ความคิดเห็น</th>
                                    <th rowspan="2" class="text-center">รวม</th>
                                </tr>
                                <tr>
                                    <th class="text-center" width="10%">ยืนยันตามมาตรฐาน</th>
                                    <th class="text-center" width="10%">เห็นความแก้ไขปรับปรุงมาตรฐาน</th>
                                    <th class="text-center" width="10%">ยกเลิกมาตรฐาน</th>
                                    <th class="text-center" width="10%">ไม่เห็นข้อคิดเห็น</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($listenstddraftresults as $item)
                                @php
                                    $state = $item->StatusPublishName;

                                    $sum = 0;

                                    $confirm_standard = App\Models\Tis\ListenStdDraft::where('note_std_draft_id', $item->id  )->where('comment', 'confirm_standard' )->count();
                                    $sum += $confirm_standard;

                                    $revise_standard = App\Models\Tis\ListenStdDraft::where('note_std_draft_id', $item->id  )->where('comment', 'revise_standard' )->count();
                                    $sum += $revise_standard;

                                    $cancel_standard = App\Models\Tis\ListenStdDraft::where('note_std_draft_id', $item->id  )->where('comment', 'cancel_standard' )->count();
                                    $sum += $cancel_standard;

                                    $no_comment = App\Models\Tis\ListenStdDraft::where('note_std_draft_id', $item->id  )->where('comment', 'no_comment' )->count();
                                    $sum += $no_comment;

                                    $start_date = !empty($item->start_date)?HP::DateThai($item->start_date):null;
                                    $end_date = !empty($item->end_date)?HP::DateThai($item->end_date):null;



                                @endphp
                                <tr>
                                    <td class="text-center">{{ $listenstddraftresults->perPage()*($listenstddraftresults->currentPage()-1)+$loop->iteration }}</td>
                                    <td class="text-left">
                                        {!! !empty($item->tis_no)?$item->tis_no:null !!} {!! !empty($item->title)?':'.$item->title:null !!}
                                    </td>
                                    <td class="text-left">{!! !empty($item->ResultDraftName)?$item->ResultDraftName:null !!}</td>
                                    
                                    <td class="text-left">

                                        @if( !is_null($start_date) && !is_null($end_date) )
                                            {{ $state }} :  {{ $start_date }} - {{ $end_date }}
                                        @elseif( !is_null($start_date) && is_null($end_date) )
                                            {{ $state }} :  {{ $start_date }}
                                        @elseif( !is_null($start_date) && is_null($end_date) )
                                            {{ $state }} :  {{ $end_date }}
                                        @else
                                            {{ $state }} : N/A
                                        @endif
                                        
                                    </td>
                                    <td class="text-center">{{ $confirm_standard }}</td>
                                    <td class="text-center">{{ $revise_standard }}</td>
                                    <td class="text-center">{{ $cancel_standard }}</td>
                                    <td class="text-center">{{ $no_comment }}</td>
                                    <td class="text-center">{{ number_format($sum) }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $listenstddraftresults->appends(['search' => Request::get('search'),
                                                                'sort' => Request::get('sort'),
                                                                'direction' => Request::get('direction'),
                                                                'perPage' => Request::get('perPage'),
                                                                'filter_search' => Request::get('filter_search'),
                                                                'filter_status' => Request::get('filter_status'),
                                                                'filter_result_draft' => Request::get('filter_result_draft')
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

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
                    <h3 class="box-title pull-left">ตั้งค่าสาขาตามมาตรฐาน</h3>

                    <div class="pull-right">
                      {{-- @can('add-'.str_slug('standardformulas')) --}}
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/formulas/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      {{-- @endcan --}}
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/certify/formulas', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="row">
                      <div class="col-md-4 form-group">
                            {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-3 control-label label-filter text-right']) !!}
                            <div class="form-group col-md-9">
                                {!! Form::select('filter_search', 
                                 App\Models\Bcertify\Formula::where('applicant_type',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                                  null,
                                 ['class' => 'form-control',
                                 'id'=>'filter_search',
                                 'placeholder'=>'-เลือกมาตรฐาน-']) !!}
                           </div>
                      </div><!-- /form-group -->
                      <div class="col-md-6">
                             {!! Form::label('filter_tb3_Tisno', 'สาขา:', ['class' => 'col-md-2 control-label label-filter text-right']) !!}
                               <div class="form-group col-md-5">
                                {!! Form::text('filter_title', null, ['class' => 'form-control', 'placeholder'=>' สาขา ','id'=>'filter_title']); !!}
                              </div>
                              <div class="form-group col-md-5">
                                  {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                  <div class="col-md-8">
                                      {!! Form::select('perPage', 
                                      ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100','500'=>'500'], 
                                        null,
                                       ['class' => 'form-control']) !!}
                                  </div>
                              </div>
                      </div><!-- /.col-lg-5 -->
                    <div class="col-md-2">
                      <div class="form-group  pull-left">
                          <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                      </div>
                      <div class="form-group  pull-left m-l-15">
                          <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                              ล้าง
                          </button>
                      </div>
                     </div><!-- /.col-lg-1 -->
                  </div><!-- /.row -->

 
						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

						{!! Form::close() !!}

                    <div class="clearfix"></div>

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center" width="10%">สาขา</th>
                                <th class="text-center"  width="10%">มาตรฐาน</th>
                                <th class="text-center"  width="10%">วันที่บันทึก</th>
                                <th class="text-center"  width="10%">ผู้บันทึก</th>
                                <th class="text-center"  width="10%">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($standardformulas as $item)
                                <tr> 
                                    <td  class="text-center"> {{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $standardformulas->perPage() ) }}</td>
                                    <td>{{ @$item->title ?? null }}</td>
                                    <td>{{ @$item->FormulaTo->title ?? null }}</td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    <td>{{ @$item->user_created->FullName ?? null }}</td>
                                    <td>
                                        {{-- @can('view-'.str_slug('standardformulas'))
                                            <a href="{{ url('/certify/formulas/' . $item->id) }}"
                                               title="View standardformulas" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan --}}

                                        {{-- @can('edit-'.str_slug('standardformulas')) --}}
                                            <a href="{{ url('/certify/formulas/' . $item->id . '/edit') }}"
                                               title="Edit standardformulas" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        {{-- @endcan --}}

                                        @can('delete-'.str_slug('standardformulas'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/certify/formulas', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete standardformulas',
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
                              $standardformulas->appends(['search' => Request::get('search'),
                                                      'sort' => Request::get('sort'),
                                                      'direction' => Request::get('direction'),
                                                      'perPage' => Request::get('perPage'),
                                                      'filter_state' => Request::get('filter_state')
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
                $('#filter_status').val('').select2();
                $('#filter_search').val('');
 
                window.location.assign("{{url('/certify/formulas')}}");
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

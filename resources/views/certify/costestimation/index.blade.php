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
                    <h3 class="box-title pull-left">การประมาณค่าใช้จ่าย </h3>

                    <div class="pull-right">

 

                      @can('add-'.str_slug('costestimation'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/Cost-Estimation/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan
 
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/certify/Cost-Estimation', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="row">
                      <div class="col-md-3 form-group">
                            {!! Form::label('filter_tb3_Tisno', 'สถานะ:', ['class' => 'col-md-2 control-label label-filter test-right']) !!}
                            <div class="form-group col-md-10">
                            {!! Form::select('filter_search',
                            ['1'=>'LAB','2'=>'IB','3'=>'CB'], 
                            null, 
                            ['class' => 'form-control', 
                            'id' => 'filter_search',
                            'placeholder'=>'-เลือกสถานะ-']); !!}
                           </div>
                      </div><!-- /form-group -->

                      <div class="col-md-7">
                        {!! Form::label('filter_title', 'search:', ['class' => 'col-md-2 control-label label-filter test-right']) !!}
                          <div class="form-group col-md-6">
                           {!! Form::text('filter_title', null, ['class' => 'form-control','id'=>"filter_title", 'placeholder'=>'search']); !!}
                         </div>
                         <div class="form-group col-md-4">
                             {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                             <div class="col-md-8">
                                 {!! Form::select('perPage', 
                                 ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100',
                                  '500'=>'500'], null, ['class' => 'form-control']); !!}
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

                    <div class="table-responsive">
                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center" width="2%">#</th>
                                <th class="text-center"  width="20%">รายการ</th>
                                <th class="text-center" width="10%">กลุ่มหน่วยงาน</th>
                                <th class="text-center" width="10%">วันที่บันทึก</th>
                                <th class="text-center" width="10%">ผู้บันทึก</th>
                                <th class="text-center" width="10%">รายละเอียด</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($costestimation as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $costestimation->perPage() ) }}</td>
                                    <td>{{ $item->title }}</td> 
                                    <td>{{ $item->AgencyGroup ?? null }}</td> 
                                    <td>
                                        @if(!is_null($item->updated_by)) 
                                        <span  class="text-warning" >{{ HP::DateThai($item->updated_at) }}</span>  
                                        @else 
                                          {{ HP::DateThai($item->created_at) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($item->updated_by)) 
                                        <span  class="text-warning" >  {{ $item->UserUpdateTo->FullName ?? null }}</span>  
                                        @else 
                                           {{ $item->UserCreateTo->FullName ?? null }}
                                        @endif
                                    </td>
                                    <td>
                                        @can('edit-'.str_slug('costestimation'))
                                            <a href="{{ url('/certify/Cost-Estimation/' . $item->id . '/edit') }}"
                                               title="Edit costestimation" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('costestimation'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/certify/Cost-Estimation', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete costestimation',
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
                              $costestimation->appends(['search' => Request::get('search'),
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
                $('#filter_search').val('').select2();
                $('#filter_title').val('');

                window.location.assign("{{url('/certify/Cost-Estimation')}}");
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

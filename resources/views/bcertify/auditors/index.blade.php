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
                    <h3 class="box-title pull-left">ข้อมูลผู้ตรวจประเมิน</h3>
                    <div class="pull-right">
                      @can('add-'.str_slug('auditor'))
                        <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ route('bcertify.auditors.create') }}">
                        <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                        </a>
                      @endcan
                    </div>

                   <div class="clearfix"></div>
                    <hr>
                    {!! Form::model($filter, ['url' => '/bcertify/auditors', 'method' => 'get', 'id' => 'myFilter']) !!}
                   <div class="row">
                      <div class="col-md-4">
                              {!! Form::label('filter_search', 'search:', ['class' => 'col-md-3 control-label label-filter text-right']) !!}
                         <div class="form-group col-md-9">
                              {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'','id'=>'filter_search']); !!}
                           </div>
                      </div><!-- /.col-lg-5 -->
                      <div class="col-md-4 ">
                        {!! Form::label('filter_tb3_Tisno', 'หน่วยงาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="form-group col-md-8">
                          {!! Form::select('filter_department', 
                           App\Models\Basic\Department::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                            null,
                            ['class' => 'form-control',
                            'id'=>'filter_department',
                            'placeholder'=>'-เลือกหน่วยงานะ-']) !!}
                       </div>
                     </div>
                     <div class=" col-md-2">
                      {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                      <div class="col-md-8">
                          {!! Form::select('perPage', 
                          ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100',
                           '500'=>'500'], null, ['class' => 'form-control']); !!}
                      </div>
                    </div>
                      <div class="col-md-2">
                        <div class="form-group  pull-left">
                            <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                        </div>

                        <div class="   pull-left m-l-15">
                            <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                ล้าง
                            </button>
                        </div>
                      </div><!-- /.col-lg-1 -->
                  </div><!-- /.row -->
                  <div class="row">
                    <div class="col-md-6">
                              {!! Form::label('filter_formula', 'มาตราฐานเชี่ยวชาญ:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                         <div class="  col-md-8">
                             {!! Form::select('filter_formula', 
                              App\Models\Bcertify\Formula::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                               null,
                               ['class' => 'form-control',
                              'id'=>'filter_formula',
                              'placeholder'=>'-เลือกมาตราฐานเชี่ยวชาญ-']) !!}
                           </div>
                      </div><!-- /.col-lg-5 -->
                  </div><!-- /.row -->
                <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

						{!! Form::close() !!}
                   <div class="clearfix"></div>
                    <span class="small">{{ 'ทั้งหมด '. $auditors->total() .' รายการ'}}</span>
                    <div class="table-responsive">
                    
                        <table class="table table-borderless" id="myTable">
                            <thead>
                              <tr>
                                <th class="text-center">No.  </th>
                                <th class="text-center">ชื่อ - สกุล</th>
                                <th class="text-center">หน่วยงาน</th>
                                <th class="text-center">เบอร์โทร</th>
                                <th class="text-center" width="300px">มาตราฐานที่เชี่ยวชาญ</th>
                                <th class="text-center">วันที่บันทึก</th>
                                <th class="text-center">ผู้บันทึก</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center" width="100px">เครื่องมือ</th>
                             </tr>
                            </thead>
                            <tbody>
                             @foreach($auditors as $auditor)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ( ((request()->query('page') ?? 1) - 1) * $auditors->perPage() ) }}</td>
                                    <td>{{$auditor->title_th}}{{$auditor->fname_th}} {{$auditor->lname_th}}</td>
                                    <td>
                                    {{ !is_null($auditor->department) ? $auditor->department->title : '-' }}
                                    </td>
                                    <td>{{$auditor->tel}}</td>
                                    @php
                                        $standard = array();
                                        $expertises = \App\Models\Bcertify\AuditorExpertise::where('auditor_id',$auditor->id)->get();
                                        foreach ($expertises as $expertise){
                                            if (!in_array($expertise->formula->title,$standard)){
                                                array_push($standard,$expertise->formula->title);
                                            }
                                        }
                                    @endphp
                                    <td>
                                        {{implode(",",$standard)}}
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($auditor->created_at)->format('d/m/Y')}}</td>
                                    <td>{{@$auditor->user->reg_fname}} {{@$auditor->user->reg_lname}}</td>
                                    <td class="text-center">
                                        @if ($auditor->status == 1)
                                            <a href="{{ route('bcertify.auditors.update_status',['id'=>$auditor->id]) }}"><i class="mdi mdi-checkbox-marked-circle" style="color: green ; font-size: 20px" data-toggle="tooltip" title="Click to close"></i></a>
                                        @else
                                            <a href="{{ route('bcertify.auditors.update_status',['id'=>$auditor->id]) }}"><i class="mdi mdi-close-circle" style="color: red ; font-size: 20px"  data-toggle="tooltip" title="Click to open"></i></a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- <a class="btn btn-info btn-xs" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}"><i class="fa fa-eye " aria-hidden="true" data-toggle="tooltip" title="Information"></i></a> --}}
                                        <a class="btn btn-primary btn-xs" href="{{route('bcertify.auditors.edit',['token'=>base64_encode($auditor->id)])}}"><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></a>
                                        {{-- <a class="btn btn-danger btn-xs" href="{{route('bcertify.auditor.destroy',['token'=>$auditor->token])}}"><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></a> --}}
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                        <div class="pagination-wrapper">
                          {!!
                              $auditors->appends(['search' => Request::get('search'),
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
                
                $('#filter_search').val('');
                $('#filter_department').val('').select2();
                $('#filter_formula').val('').select2();
 
                window.location.assign("{{url('/bcertify/auditors')}}");
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

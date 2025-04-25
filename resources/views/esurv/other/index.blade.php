@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
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
    td:nth-of-type(2):before { content: "ประกอบการ:"; }
    td:nth-of-type(3):before { content: "วันที่แจ้ง:"; }
    td:nth-of-type(4):before { content: "ประเภทการแจ้ง:"; }
    td:nth-of-type(5):before { content: "เรื่อง:"; }
    td:nth-of-type(6):before { content: "รายละเอียด:"; }
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
                    <h3 class="box-title pull-left">ดูการแจ้งข้อมูลอื่นๆ</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/esurv/other', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">
                          <div class="col-md-3 form-group">
                                {!! Form::select('filter_inform_type', HP::OtherTypes(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภทการแจ้ง-']); !!}
                          </div><!-- /form-group -->
                          <div class="col-md-3 form-group">
                              {!! Form::label('filter_state', 'สถานะ', ['class' => 'col-md-4 control-label label-filter']) !!}
                              <div class="col-md-8">
                                {!! Form::select('filter_state', [ '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                            </div>
                          </div><!-- /form-group -->
                          <div class="col-lg-2">
                            <div class="form-group">
                              <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                  <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                              </button>
                            </div>
                          </div><!-- /.col-lg-1 -->
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
                         <div class="col-md-2 form-group">
                          {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                          <div class="col-md-8">
                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                        </div>
                      </div><!-- /form-group -->
                      </div><!-- /.row -->


                      <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">
                          <div class="row">
                            <div class="col-md-6 form-group">
                              {!! Form::label('filter_date_start', 'วันที่แจ้ง:', ['class' => 'col-md-4 control-label label-filter']) !!}
                              <div class="col-md-8">
                                <div class="input-daterange input-group" id="date-range">
                                  {!! Form::text('filter_date_start', null, ['class' => 'form-control']); !!}
                                  <span class="input-group-addon bg-info b-0 text-white"> to </span>
                                  {!! Form::text('filter_date_end', null, ['class' => 'form-control']); !!}
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 form-group">
                                {!! Form::label('filter_created_by', 'ผู้รับใบอนุญาต:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                <div class="col-md-8">
                                    @php
                                        $sso_user_table = (new App\Models\Sso\User)->getTable();
                                    @endphp
                                    {!! Form::select('filter_created_by',
                                            App\Models\Esurv\Other::select(DB::raw("CONCAT($sso_user_table.name) AS title"), "$sso_user_table.id")
                                                                ->Join($sso_user_table, "$sso_user_table.id", '=', 'esurv_others.created_by')
                                                                ->distinct('esurv_others.created_by')
                                                                ->pluck('title', 'id') ,
                                            null,
                                            ['class' => 'form-control', 'placeholder'=>'-เลือกผู้รับใบอนุญาต-']
                                        );
                                    !!}
                                </div>
                            </div>
                            </div><!-- /.row -->

                        </div>
                    </div>

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                      {!! Form::open(['url' => '/esurv/other/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/esurv/other/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center" width="2%">#</th>
                                <th class="text-center" width="10%">@sortablelink('created_by', 'ผู้ประกอบการ')</th>
                                <th class="text-center" width="10%">@sortablelink('created_at', 'วันที่แจ้ง')</th>
                                <th class="text-center" width="10%">@sortablelink('inform_type', 'ประเภทการแจ้ง')</th>
                                <th class="text-center" width="10%">@sortablelink('title', 'เรื่อง')</th>
                                <th class="text-center" width="10%">@sortablelink('detail', 'รายละเอียด')</th>
                                <th class="text-center" width="10%">@sortablelink('applicant_name', 'ผู้บันทึก')</th>
                                <th class="text-center" width="10%">สถานะ</th>
                                <th class="text-center" width="5%" >จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($other as $item)
                                <tr>
                                    <td class="text-top">{{ $loop->iteration or $item->id }}</td>
                                    <td class="text-top">{{ $item->CreatedName }} <br> {{ $item->TraderIdName }} </td>
                                    <td class="text-top">{{ HP::DateThai($item->created_at) }}</td>
                                    <td class="text-top">{{ HP::OtherTypes()[$item->inform_type] }}</td>
                                    <td class="text-top">{{ $item->title }}</td>
                                    <td class="text-top">{{ $item->detail }}</td>
                                    <td class="text-top">{{ $item->applicant_name }}</td>
                                    <td class="text-top">
                                      @php
                                      $status_css = ['0'=>'label-warning','1'=>'label-info', '2'=>'label-success', '3'=>'label-danger'];
                                      $status_receive  =  ['0' => 'ฉบับร่าง', '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'];
                                      @endphp
                                      @if(array_key_exists($item->state,$status_css) && array_key_exists($item->state,$status_receive))
                                         <span class="label {{ $status_css[$item->state] }}">
                                            <b>{{ $status_receive[$item->state] }}</b>
                                        </span>
                                      @else
                                         <span class="label label-info">
                                            <b>รอดำเนินการ</b>
                                        </span>
                                      @endif
                                    </td>
                                    <td class="text-top text-center">
                                      @if($item->state == 0  || $item->state == 1  || $item->state == 2)
                                         @can('edit-'.str_slug('other'))
                                            <a href="{{ url('/esurv/other/' . $item->id.'/edit') }}"
                                          title="href other"  class="btn btn-primary btn-xs">
                                          <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                              </a>
                                          @endcan
                                      @else
                                          @can('view-'.str_slug('other'))
                                          <a href="{{ url('/esurv/other/' . $item->id) }}"
                                            title="View other" class="btn btn-info btn-xs">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                          </a>
                                        @endcan
                                      @endif

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          @php
                              $page = array_merge($filter, ['sort' => Request::get('sort'),
                                                            'direction' => Request::get('direction'),
                                                            'perPage' => Request::get('perPage')
                                                           ]);
                          @endphp
                          {!!
                              $other->appends($page)->render()
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
    <script src="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

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
            $( "#filter_clear" ).click(function() {
                // alert('sofksofk');
                $('#filter_inform_type').val('').select2();
                $('#filter_created_by').val('').select2();
                $('#filter_state').val('').select2();
                $('#filter_date_start').val('');
                $('#filter_date_end').val('');
                window.location.assign("{{url('/esurv/other')}}");
            });

            var filter_date_start =  $('#filter_date_start').val() ;
            var filter_date_end =  $('#filter_date_end').val() ;

            // console.log($('#filter_date_end').val());
            if( $('#filter_created_by').val()!= "" || filter_date_start != ''){

                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });


            //เลือกทั้งหมด
            $('#checkall').change(function(event) {

              if($(this).prop('checked')){//เลือกทั้งหมด
                $('#myTable').find('input.cb').prop('checked', true);
              }else{
                $('#myTable').find('input.cb').prop('checked', false);
              }

            });

            //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
              format: 'dd/mm/yyyy',
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

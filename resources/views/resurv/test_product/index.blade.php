@extends('layouts.master')

@push('css')

<style>

    th {
        text-align: center;
    }

    td {
        text-align: center;
    }

    .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
        background-color: #FFF2CC;
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
    fieldset {
        padding: 20px;
    }
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบประเมินผลทดสอบ (จาก LAB)</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <fieldset class="row">
                    {!! Form::model($filter, ['url' => '/resurv/test_product', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นหา ผู้ได้รับใบอนุญาต']); !!}
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
                                        {!! Form::select('filter_status', [
                                                            '0' => 'ฉบับร่าง', 
                                                            '1' => 'อยู่ระหว่าง ผก.รับรอง',
                                                            '2' => 'ผก.รับรองแล้ว',
                                                            '3' => 'อยู่ระหว่าง ผอ.รับรอง',
                                                            '4' => 'ผอ.รับรองแล้ว',
                                                            '5' => 'ปรับปรุงแก้ไข' ], 
                                                            null,
                                                            ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                        {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('filter_tb3_Tisno', HP::TisListSample(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_department', 'กลุ่มงานหลัก', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_department', App\Models\Besurv\Department::whereIn('did',[10,11,12])->pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_sub_department', 'กลุ่มงานย่อย', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_sub_department', !empty($subDepartments)?$subDepartments:[], null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลักย่อย-']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_test_status', 'ผลการประเมิน', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_test_status', ['1'=>'ผ่าน','2'=>'ไม่ผ่าน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_status3', 'สถานะ ผก.', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_status3', ['1'=>'อยู่ระหว่าง ผก. รับรอง','2'=>'ผก. รับรองแล้ว','3'=>'ผก. ไม่เห็นด้วย'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}

                           <div class="clearfix"></div>

                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 2%;"><input type="checkbox" id="checkall"></th>
                                    <th style="width: 8%;">เลขที่อ้างอิง</th>
                                    <th style="width: 14%;">ผู้รับใบอนุญาต</th>
                                    <th style="width: 6%;">เลข มอก.</th>
                                    <th style="width: 18%;">ชื่อมาตรฐาน</th>
                                    <th style="width: 10%;">รูปแบบการตรวจ</th>
                                    <th style="width: 10%;">สถานะ</th>
                                    <th style="width: 10%;">ผลการประเมิน</th>
                                    <th style="width: 10%;">สถานะ ผก.ตรวจสอบ</th>
                                    <th style="width: 6%;">รายละเอียด</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($test_product as $item)
                                    <tr>
                                        <td>{{ $temp_num++ }}</td>
                                        <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                        <td> {{$item->no}}</td>
                                        <td>{{ $item->licensee }}</td>
                                        <td>{{ $item->tis_standard }}</td>
                                        <td>{{ $item->tis->tb3_TisThainame }}</td>
                                        <td>
                                            @if($item->type_send == 'all')
                                                ทุกรายการทดสอบ
                                            @elseif($item->type_send == 'some')
                                                บางรายการทดสอบ
                                            @endif
                                        </td>
                                        <td> 
                                            @if($item->status2 >= 2)
                                                ประเมินผลแล้ว
                                            @elseif($item->status2 == 1)
                                                รอประเมินผล
                                            @elseif($item->status == '3')
                                                รอมอบหมายงาน
                                            @endif
                                            {{-- {{ HP::status_save_example('status2',$item->status2) }} --}}
                                        </td>
                                        <td> {{ HP::status_save_example('test_status',$item->test_status) }}</td>
                                        <td> {{ HP::status_save_example('status3',$item->status3) }}</td>
                                        <td>
                                            <a href="{{url('/resurv/test_product/'.$item->id.'/edit')}}"
                                               class="btn btn-info "
                                               style="background-color: #5B9BD5; border: #5B9BD5">
                                                รายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="pagination-wrapper">
                                @php
                                    $page = array_merge($filter, ['sort' => Request::get('sort'),
                                                                  'direction' => Request::get('direction'),
                                                                  'perPage' => Request::get('perPage'),
                                                                  'filter_search' => Request::get('filter_search'),
                                                                  'filter_status' => Request::get('filter_status'),
                                                                  'filter_tb3_Tisno' => Request::get('filter_tb3_Tisno'),
                                                                  'filter_department' => Request::get('filter_department'),
                                                                  'filter_sub_department' => Request::get('filter_sub_department'),
                                                                  'filter_test_status' => Request::get('filter_test_status'),
                                                                  'filter_status3' => Request::get('filter_status3')
                                                                 ]);
                                @endphp
                                {!!
                                    $test_product->appends($page)->links()
                                !!}
                            </div>

                    </fieldset>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>

        $(document).ready(function () {
            $("#filter_tb3_Tisno").select2({minimumInputLength: 2});

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_tb3_Tisno').val('').select2();
                $('#filter_department').val('');
                $('#filter_sub_department').val('');
                $('#filter_test_status').val('');
                $('#filter_status3').val('');

                window.location.assign("{{url('/resurv/test_product')}}");
            });
            if($('#filter_tb3_Tisno').val()!="" || $('#filter_department').val()!="" || $('#filter_sub_department').val()!="" || $('#filter_test_status').val()!="" || $('#filter_status3').val()!=""){

                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }
            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });

            $('#filter_department').change(function(){
                //  alert('มาแล้ว');
                var department_id = $(this).val();
                if(department_id!=""){
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/add_sub_department')}}",
                        datatype: "html",
                        data: {
                            department_id: department_id,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            $("#filter_sub_department").html('');
                            var response = data;
                            var list = response.data;
                            var opt;
                            opt += "<option value=''>-เลือกกลุ่มงานหลักย่อย-</option>";
                            $.each(list, function (key, val) {
                                opt += "<option value='" + key + "'>" + val + "</option>";
                            });
                            $("#filter_sub_department").html(opt).trigger("change");
                        }
                    });
                }
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

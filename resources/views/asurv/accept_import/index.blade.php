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
        fieldset {
            padding: 20px;
        }
        .check_api_pid {cursor: pointer;}

    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                                    <h1 class="box-title">ระบบรับคำขอการทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (20 ทวิ)</h1>
                                    <hr class="hr-line bg-primary">



                                {!! Form::model($filter, ['url' => '/asurv/accept_import', 'method' => 'get', 'id' => 'myFilter']) !!}


                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                                <div class="col-md-12">
                                                    {!! Form::input('text', 'filter_title', null, ['class' => 'form-control', 'placeholder'=>'ค้นเลขที่คำขอ,ผู้ยื่น,ชื่อผลิตภัณฑ์', 'style'=>'font-size:15px;']); !!}
                                                </div>
                                        </div><!-- /form-group -->
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
                                                    {!! Form::select('filter_request', array('1' => 'ยื่นคำขอ', '2 ' => 'อยู่ระหว่างดำเนินการ ', '3' => 'เอกสารไม่ครบถ้วน', '4' => 'อนุมัติ', '5' => 'ไม่อนุมัติ'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะคำขอ-']); !!}
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
                                                {!! Form::label('filter_start_month', 'วันที่ผลิต:', ['class' => 'col-md-4 control-label label-filter text-right ']) !!}
                                                <div class="col-md-5">
                                                  {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                                </div>
                                                <div class="col-md-3">
                                                  {!! Form::select('filter_start_year', HP::FiveYearListMinus(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                              {!! Form::label('filter_end_month', 'ถึง:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                              <div class="col-md-5">
                                                {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                              </div>
                                              <div class="col-md-3">
                                                {!! Form::select('filter_end_year', HP::FiveYearListMinus(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                              </div>
                                          </div>
                                           </div>

                                            <div class="row">
                                              <div class="form-group col-md-6">
                                                {!! Form::label('filter_department', 'กลุ่มงานหลัก:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                                <div class="col-md-8">
                                                  {!! Form::select('filter_department', App\Models\Besurv\Department::pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-']); !!}
                                                </div>
                                              </div>
                                              <div class="form-group col-md-6">
                                              {!! Form::label('filter_sub_department', 'กลุ่มงานย่อย:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                              <div class="col-md-8">
                                                {!! Form::select('filter_sub_department',   App\Models\Basic\SubDepartment::where('did',$filter['filter_department'])->pluck('sub_departname', 'sub_id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลักย่อย-']); !!}
                                              </div>
                                             </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('filter_notify', 'มาตรฐานเลขที่:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::select('filter_tis',
                                                          App\Models\Basic\Tis::selectRaw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) As tb3_Tisno, tb3_TisAutono')->pluck('tb3_Tisno', 'tb3_TisAutono'),
                                                         null,
                                                         ['class' => 'form-control',
                                                         'placeholder' => '- เลือกมาตรฐานเลขที่ -',
                                                         'id' => 'filter_tis']) !!}
                                                    </div>
                                               </div>
                                              <div class="form-group col-md-6">
                                              {!! Form::label('filter_notify', 'สถานะการแจ้ง:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                              <div class="col-md-8">
                                                   {!! Form::select('filter_notify', array('1' => 'เปิด', '0' => 'ปิด'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะการแจ้ง-']); !!}
                                              </div>
                                           </div>
                                          </div>

                                        </div>
                                    </div>

                            {!! Form::close() !!}
                     <div class="clearfix"></div>

                    <div class="table-responsive">

                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 3%;">เลขที่คำขอ อ้างอิง</th>
                                    <th style="width: 5%;">ผู้ยื่น</th>
                                    <th style="width: 8%;">ชื่อผลิตภัณฑ์</th>
                                    <th style="width: 8%;">ระยะเวลาที่ผลิต</th>
                                    <th style="width: 6%;">วันที่ยื่น</th>
                                    <th style="width: 6%;">ตรวจสอบ เอกสาร</th>
                                    <th style="width: 6%;">สถานะคำขอ</th>
                                    <th style="width: 4%;">สถานะการแจ้ง</th>
                                    <th style="width: 4%;">ผู้รับมอบหมาย</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($accept_import as $list)

                                    <tr>
                                        <td>{{$temp_num++}}.</td>
                                        <td>{{$list->ref_no}}</td>
                                        <td>{{HP::get_name_4($list->created_by)}}</td>
                                        <td>{{$list->title}}</td>
                                        <td>{{ HP::DateThai($list->start_date) }} – {{ HP::DateThai($list->end_date) }}</td>
                                        <td>{{ HP::DateThai($list->created_at) }}</td>
                                        <td>
                                            @php
                                                if($list->state === 1 ||$list->state === 2 ||$list->state === 3){
                                                    $url = url("/asurv/accept_import/$list->id/edit");
                                                    $icon = '<span class="glyphicon glyphicon-search btn-lg"></span>';
                                                    $check_a = ( auth()->user()->isAdmin() === true || HP::CheckAsurvTis($list->different_no) == true)?true:false;
                                                }else{
                                                    $url = url("/asurv/accept_import/detail/$list->id");
                                                    $icon = '<i class="fa fa-check-square fa-2x" style="color: orange"></i>';
                                                    $check_a = true;
                                                }
                                            @endphp

                                            @if($check_a == true)
                                                <a href="{{ $url }}">{!! $icon !!}</a>
                                            @endif
                                            {{-- @if(HP_API_PID::check_api('check_api_asurv_accept_import') && HP_API_PID::CheckDataApiPid($list, (new App\Models\Asurv\EsurvBiss20)->getTable()) != '' &&  $list->state === 1)
                                                <span data-id="{{$list->id}}" data-url="{!! $url !!}" data-table="{!! (new App\Models\Asurv\EsurvBiss20)->getTable() !!}" class="check_api_pid glyphicon glyphicon-search btn-lg"></span>
                                            @else
                                                @if($check_a == true)
                                                    <a href="{{ $url }}">{!! $icon !!}</a>
                                                @endif
                                            @endif --}}
                                        </td>
                                        <td>
                                            @if($list->state === 1)
                                                ยื่นคำขอ
                                            @elseif($list->state === 2)
                                                อยู่ระหว่างดำเนินการ
                                            @elseif($list->state === 3)
                                                เอกสารไม่ครบถ้วน
                                            @elseif($list->state === 4)
                                                อนุมัติ
                                            @elseif($list->state === 5)
                                                ไม่อนุมัติ
                                            @endif
                                        </td>
                                        <td>
                                            @if($list->state_check === null)
                                                -
                                            @elseif($list->state_check === 1)
                                                <a href="{{url("/asurv/accept_import/update_status/$list->id/$list->state_check")}}"> <i class="fa fa-check-circle fa-2x" style="color: #1ec01e"></i></a>
                                            @elseif($list->state_check === 0)
                                                <a href="{{url("/asurv/accept_import/update_status/$list->id/$list->state_check")}}"><i class="fa fa-times-circle fa-2x" style="color: red"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($list->officer_export === null)
                                                -
                                            @else
                                                {{HP::get_create_4($list->officer_export)}}
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
                                    $accept_import->appends($page)->links()
                                !!}
                         </div>
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

        $(document).ready(function() {
            $( "#filter_clear" ).click(function() {
                // alert('sofksofk');
                $('#filter_created_by').val('').select2();
                $('#filter_state').val('').select2();
                $('#filter_start_month').val('').select2();
                $('#filter_start_year').val('').select2();
                $('#filter_end_month').val('').select2();
                $('#filter_end_year').val('').select2();
                $('#filter_department').val('').select2();
                $('#filter_sub_department').val('').select2();
                $('#filter_notify').val('').select2();
                $('#filter_tis').val('').select2();
                window.location.assign("{{url('/asurv/accept_import')}}");
            });

            $(".check_api_pid").click(function() {
                var id =   $(this).data('id');
                var url =   $(this).data('url');
                var table =   $(this).data('table');

                $.ajax({
                    type: 'get',
                    url: "{!! url('asurv/function/check_api_pid') !!}" ,
                    data: {
                        id:id,
                        table:table,
                        type:'false'
                    },
                }).done(function( object ) {
                    Swal.fire({
                        position: 'center',
                        html: object.message,
                        showConfirmButton: true,
                        width: 800
                    }) .then((result) => {
                        if (result.value) {
                            window.location = url;
                        }
                    });
                });

            });



            if( $('#filter_start_month').val()!="" ||  $('#filter_end_month').val() != ""    ||  $('#filter_department').val() != ""  ||  $('#filter_sub_department').val() != ""  ||  $('#filter_notify').val() != ""  ||  $('#filter_tis').val() != ""){
                // alert('มีค่า');
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

        });

    </script>
@endpush

@extends('layouts.master')

@push('css')

    <style>

        th {
            text-align: center;
        }



        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #FFF2CC;
        }

        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px) {

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
                            <h1 class="box-title">ระบบบันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <div class="pull-right">
                        @can('add-'.str_slug('control_freeze'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/csurv/control_freeze/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('control_freeze'))
                           <button class="btn btn-danger btn-sm waves-effect waves-light bulk_delete" type="button" id="bulk_delete">
                               <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                           </button>
                        @endcan
                    </div>

            <div class="clearfix"></div>

                    <fieldset class="row">
                        <div class="white-box">
                 
                                {!! Form::model($filter, ['url' => '/csurv/control_freeze', 'method' => 'get', 'id' => 'myFilter']) !!}


                           <div class="row">
                                <div class="col-md-3 form-group">
                                        {!! Form::input('text', 'filter_auto_id_doc', null, ['class' => 'form-control', 'placeholder'=>'ค้นจากเลขที่']); !!}
                                </div><!-- /form-group -->
                                <div class="col-md-3 form-group">
                                    {!! Form::input('text', 'filter_document_number', null, ['class' => 'form-control', 'placeholder'=>'ค้นจากเลขที่เอกสาร']); !!}
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
                              <div class="col-lg-2">
                                   {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                            </div><!-- /.col-lg-1 -->
                          </div><!-- /.row -->

                          <div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">

                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                        {!! Form::label('filter_start_month', 'วันที่ถอนยึด/อายัด:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                        <div class="col-md-4">
                                                            {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                                        </div>
                                                        <div class="col-md-4">
                                                            {!! Form::select('filter_start_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                                        </div>
                                            </div><!-- /form-group -->
                                    
                                            <div class="col-lg-6 form-group">
                                                    {!! Form::label('filter_end_month', 'ถึงวันที่:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                                    <div class="col-md-5">
                                                        {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! Form::select('filter_end_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                                                    </div>
                                            </div><!-- /.col-lg-5 -->  
                                        </div><!-- /.row -->
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
                                                {!! Form::label('filter_assessment', 'สถานะ', ['class' => 'col-md-3 control-label label-filter']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::select('filter_assessment', array('ยึด/อายัด' => 'ยึด/อายัด', 'ถอนยึด/อายัด' => 'ถอนยึด/อายัด'),
                                                     null ,
                                                     ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                            
                                            </div>
                                        </div>
                            </div>
                        </div>
                     {!! Form::close() !!}
                    

                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 2%;"><input type="checkbox" id="checkall"></th>
                                    <th style="width: 4%;">เลขที่</th>
                                    <th style="width: 4%;">เลขที่เอกสาร</th>
                                    <th style="width: 10%;">ผู้ได้รับใบอนุญาต</th>
                                    <th style="width: 6%;">สถานะ</th>
                                    <th style="width: 8%;">วันที่ถอนยึด/อายัด</th>
                                    <th style="width: 8%;">พนักงาน/เจ้าหน้าที่</th>
                                    <th style="width: 6%;"> เครื่องมือ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($control_freeze as $item)
                                    <tr>
                                        <td class="text-center">{{ $temp_num++}}.</td>
                                         <td class="text-center"><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                        <td>{{$item->auto_id_doc}}</td>
                                        <td>{{$item->document_number}}</td>
                                        <td>{{ HP::get_tb4_name_index($item->tradeName) }}</td>
                                        <td>{{$item->status}}</td>
                                        <td>{{  !empty($item->date_freeze) ?  HP::DateThai($item->date_freeze) : '' }}</td>
                                        <td>{{$item->check_officer}}</td>
                                        <td class="text-center">
                                            @if($item->status == 'ยึด/อายัด')
                                                <a href="{{url("/csurv/control_freeze/$item->id/edit")}}"
                                                   class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                </a>
                                            @else
                                                <a href="{{url("/csurv/control_freeze/$item->id")}}"
                                                   class="btn btn-info btn-xs">
                                                   <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            @if($item->status == 'ยกเลิก')
                                                @can('delete-'.str_slug('control_freeze'))
                                                    <a disabled="true" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('delete-'.str_slug('control_freeze'))
                                                    <a onclick="delete_status({{$item->id}})"
                                                    class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
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
                                    $control_freeze->appends($page)->links()
                                !!}
                            </div>
                        </div>
                    </fieldset>

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

                 // เลือกทั้งหมด checkbox
                  $('#checkall').on('click', function(e) {
                    if($(this).is(':checked',true))
                    {
                       $(".cb").prop('checked', true);
                    } else {
                       $(".cb").prop('checked',false);
                    }
                   });

              });
        //Clear search
        $( "#filter_clear" ).click(function() {
                // alert('sofksofk');
                $('#filter_auto_id_doc').val('');
                $('#filter_document_number').val('');

                $('#filter_assessment').val('').select2();
                $('#filter_start_month').val('').select2();
                $('#filter_start_year').val('').select2();
                $('#filter_end_month').val('').select2();
                $('#filter_end_year').val('').select2();

                $('#filter_department').val('').select2();
                  $('#filter_sub_department').val('').select2();
                window.location.assign("{{url('/csurv/control_freeze')}}");
            });
            if( $('#filter_start_month').val()!="" || $('#filter_end_month').val() != "" || $('#filter_department').val() != "" || $('#filter_sub_department').val() != "" || $('#filter_assessment').val() != ""){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }
            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
        function delete_status(id) {
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_freeze/delete_status')}}",
                datatype: "html",
                data: {
                    id: id,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    if (data.status == "success") {
                        window.location.reload()
                    }
                }
            });
        }
    </script>

@endpush

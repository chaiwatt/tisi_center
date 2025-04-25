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
                    <h3 class="box-title pull-left">ระบบบันทึกการตรวจประเมินระบบควบคุมคุณภาพ</h3>
                    <div class="pull-right">
                            @can('add-'.str_slug('control_performance'))
                                <a class="btn btn-success btn-sm waves-effect waves-light"
                                href="{{ url('/csurv/control_performance/create') }}">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                </a>
                            @endcan
                            @can('delete-'.str_slug('control_performance'))
                              <button  class="btn btn-danger btn-sm waves-effect waves-light" type="button" id="bulk_delete">
                                <span class="btn-label"><i class="fa fa-trash"></i></span><b>ลบ</b>
                              </button>
                            @endcan
                    </div>
                    <div class="clearfix"></div>
                     <hr>
                       @php
                        $conclude_result =   ['1' => 'เป็นไปตามข้อกำหนด', '2' => 'แก้ไขให้เป็นไปตามข้อกำหนด', '3' => 'ไม่เป็นไปตามข้อกำหนด ส่งให้กองกฏหมายดำเนินการ'] ;  
                        @endphp
                     {!! Form::model($filter, ['url' => '/csurv/control_performance', 'method' => 'get', 'id' => 'myFilter']) !!}
                     <div class="row">
                       <div class="col-md-3 form-group">
                               {!! Form::select('filter_status',
                                 [
                                 '0' => 'ฉบับร่าง', 
                                 '1' => 'อยู่ระหว่าง ผก.รับรอง',
                                 '2' => 'ผก.รับรองแล้ว',
                                 '3' => 'อยู่ระหว่าง ผอ.รับรอง',
                                 '4' => 'ผอ.รับรองแล้ว',
                                 '5' => 'ปรับปรุงแก้ไข'
                                ],
                               null, 
                               ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!} 
                        </div><!-- /form-group -->
                        <div class="col-md-3 form-group">
                         {!! Form::text('filter_title', null, ['class' => 'form-control','placeholder' =>'ค้นจากชื่อผู้รับใบอนุญาต']) !!}
                        </div><!-- /form-group -->
 
                        <div class="col-md-2 form-group">
                           {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                            <div class="col-md-8">
                             {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
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
                   </div><!-- /.row -->
 
                   <div id="search-btn" class="panel-collapse collapse">
                     <div class="white-box" style="display: flex; flex-direction: column;">
                       <div class="row">
                         <div class="col-md-6 form-group">
                             {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                             <div class="col-md-8">
                                     {!! Form::select('filter_tb3_Tisno',
                                       HP::TisList(), 
                                     null,
                                     ['class' => 'form-control',
                                       'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                             </div>
                         </div>
                         <div class="col-md-6 form-group">
                            {!! Form::label('filter_assessment', 'ผลการประเมิน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                             <div class="col-md-8">
                                {!! Form::select('filter_assessment',
                                    $conclude_result,
                                   null , 
                                 ['class' => 'form-control', 
                                 'placeholder'=>'-เลือกผลการประเมิน-' 
                                 ]); !!}
                            </div>
                         </div>
                        </div><!-- /.row -->
      
                       <div class="row">
                         <div class="col-md-6 form-group">
                            {!! Form::label('filter_start_month', 'วันที่ตรวจ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                            <div class="col-md-4">
                                {!! Form::select('filter_start_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('filter_start_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                            </div>
                       </div>
                       <div class="col-md-6 form-group">
                        {!! Form::label('filter_end_month', 'ถึงวันที่:', ['class' => 'col-md-4 control-label label-filter']) !!}
                        <div class="col-md-4">
                            {!! Form::select('filter_end_month', HP::MonthList(), null, ['class' => 'form-control', 'placeholder'=>'-เดือน-']); !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::select('filter_end_year', HP::YearListReport(), null, ['class' => 'form-control', 'placeholder'=>'-ปี-']); !!}
                        </div>
                       </div>
                     </div><!-- /.row -->
                     </div>
                 </div>

                  <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                  <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                 {!! Form::close() !!}

                     <div class="clearfix"></div>
                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 2%;"><input type="checkbox" id="checkall"></th>
                                    <th style="width: 7%;">เลขที่เอกสาร</th>
                                    <th style="width: 12%;">ผู้รับใบอนุญาต</th>
                                    <th style="width: 16%;">ชื่อมาตรฐาน</th>
                                    <th style="width: 8%;">เลข มอก.</th>
                                    <th style="width: 14%;">ผลการประเมิน</th>
                                    <th style="width: 10%;">สถานะ</th>
                                    <th style="width: 8%;">วันที่ตรวจ</th>
                                    <th style="width: 8%;">ผู้ตรวจ</th>
                                    <th style="width: 8%;">เครื่องมือ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($control_performance as $key =>  $item)
                                    <tr>
                                        <td class="text-top">{{ $control_performance->perPage()*($control_performance->currentPage()-1)+$loop->iteration }}</td>
                                        <td class="text-center">
                                            @if($item->status == '0')
                                                <input type="checkbox" name="cb[]" class="cb" value="{{ $item->id}}">
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>
                                        <td>{{$item->auto_id_doc}}</td>
                                        <td>{{ HP::get_tb4_name_index2($item->tradeName) }}</td>
                                        <td>{{ HP::get_tb3_tis_index($item->tbl_tisiNo) }}</td>
                                        <td>{{ $item->tbl_tisiNo }}</td>
                                        <td>
                                
                                            @if(array_key_exists($item->conclude_result,$conclude_result))
                                             {{
                                             $conclude_result[$item->conclude_result] 
                                             }}
                                             @else 
                                             @endif
                                        </td>
                                        <td>
                                            @php
                                            $status =   [
                                                        '0' => 'ฉบับร่าง', 
                                                        '1' => 'อยู่ระหว่าง ผก.รับรอง',
                                                        '2' => 'ผก.รับรองแล้ว',
                                                        '3' => 'อยู่ระหว่าง ผอ.รับรอง',
                                                        '4' => 'ผอ.รับรองแล้ว',
                                                        '5' => 'ปรับปรุงแก้ไข'
                                                        ] ;  
                                            @endphp
                                            @if(array_key_exists($item->status,$status))
                                             {{
                                             $status[$item->status] 
                                             }}
                                             @else 
                                             @endif
                                        </td>
                                        <td>{{ HP::DateThai($item->created_at) }}</td>
                                        <td>{{$item->status_check}}</td>
                                        <td class="text-center">
                                            {{-- @if($item->status == '0' ||  $item->status == '5' || (auth()->user()->RoleListName  != '7' &&   $item->status == '1')) --}}
                                            @if($item->status == '0' ||  $item->status == '5' || $item->status == '1')
                                                @can('edit-'.str_slug('control_performance'))
                                                    <a href="{{url("/csurv/control_performance/$item->id/edit")}}"
                                                    class="btn btn-primary btn-xs">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                    </a>
                                                @endcan
                                            @elseif($item->status == '2')
                                            <a href="{{url("/csurv/control_performance/detail/$item->id")}}"
                                                class="btn btn-info btn-xs">
                                                 <i class="fa fa-eye" aria-hidden="true"></i>
                                             </a>
                                            @else
                                                <a href="{{url("/csurv/control_performance/detail/$item->id")}}"
                                                   class="btn btn-primary btn-xs">
                                                   <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                </a>
                                            @endif
                                            @if($item->status == '0')
                                                @can('delete-'.str_slug('control_performance'))
                                                    <a onclick="confirm_delete({{$item->id}});"
                                                    class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('delete-'.str_slug('control_performance'))
                                                    <a disabled="true" class="btn btn-danger btn-xs">
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
                                    $control_performance->appends($page)->links()
                                !!}
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
                $('#filter_title').val('');

                $('#filter_tb3_Tisno').val('').select2();
                $('#filter_assessment').val('').select2();
                $('#filter_start_month').val('').select2();
                $('#filter_start_year').val('').select2();
                $('#filter_end_month').val('').select2();
                $('#filter_end_year').val('').select2();
                window.location.assign("{{url('/csurv/control_performance')}}");
            });

            if( $('#filter_tb3_Tisno').val()!="" || $('#filter_assessment').val() != "" || $('#filter_start_month').val() != "" || $('#filter_end_month').val() != "" ){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });
        });

        function remove_perfor(id) {
            var data = id;
            if (confirm('ต้องการลบการตรวจระบบควบคุมคุณภาพนี้?')) {
                window.location.href = "{{url('/csurv/control_performance/delete_status/')}}" + data
            }
        }

        function confirm_delete(id) {
            if (confirm('ต้องการลบการตรวจระบบควบคุมคุณภาพนี้?')) {
                $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_performance/delete_status')}}",
                datatype: "html",
                data: {
                    id: id,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/csurv/control_performance')}}"
                    } else if (data.status == "error") {
                        alert(data.message);
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });
            }
        }

        $(document).ready(function () {
            //เลือกทั้งหมด
            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.cb').prop('checked', true);
                } else {
                    $('#myTable').find('input.cb').prop('checked', false);
                }

            });

            //Clear search
            $('#btn-clear').click(function(event) {
                $('#myFilter').find('input, select').val('');
                $('#myFilter').submit();
            });

        });

        function getValue() {
            var checks = document.getElementsByClassName('cb');
            var str = '';

            for (var i = 0; checks[i]; i++) {
                if (checks[i].checked === true) {
                    str += checks[i].value + ',';
                }
            }
            $('#getID').html('<input id="id_del" name="id_user_reg" value="' + str + '" hidden>');
        }

        function assign_product() {
            var checks = document.getElementsByClassName('cb');
            var temp_true = 0;
            for (var i = 0; checks[i]; i++) {
                if (checks[i].checked === true) {
                    temp_true += 1;
                }
            }
            if (temp_true > 0) {
                getValue();
                var get_id = $('#id_del').val()
                $.ajax({
                    type: "POST",
                    url: "{{url('/csurv/control_performance/delete_status_all')}}",
                    datatype: "html",
                    data: {
                        id: get_id,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        if (data.status == "success") {
                            window.location.href = "{{url('/csurv/control_performance')}}"
                        } else if (data.status == "error") {
                            alert(data.message);
                        } else {
                            alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        }
                    }
                });
                $("#alert").empty();
            } else {
                $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ยังไม่มีข้อมูลที่ถูกเลือก! <br></div>');
            }

        }
    </script>
  <script>
    $(document).ready(function () {
                //เลือกลบ
                $(document).on('click', '#bulk_delete', function(){
                        var rowsSelect = $('.cb:checked').length;
                         if(confirm("คุณแน่ใจหรือว่าต้องการลบข้อมูลนี้ " + rowsSelect + " แถว นี้ ?"))
                         {
                              var id = [];
                             $('.cb:checked').each(function(){
                                 id.push($(this).val());
                             });
                             if(id.length > 0)
                             {
                                 $.ajax({
                                     type:"POST",
                                     url:  "{{ url('csurv/control_performance/delete') }}",
                                     data:{
                                      "_token": "{{ csrf_token() }}",
                                       id:id},
                                     success:function(data)
                                     {
                                        $('#checkall').prop('checked',false );
                                        window.location.href = "{{url('/csurv/control_performance')}}"
                                     }
                                 });
                             }
                             else
                             {
                                 alert("โปรดเลือกอย่างน้อย 1 รายการ");
                             }
                         }
                     });

    });
</script>
@endpush

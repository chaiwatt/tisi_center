@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กำหนดมาตรฐาน (มอก.)</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('set_standard'))

                            <button class="btn btn-success btn-sm btn-outline waves-effect waves-light"   type="button"
                            id="publish">
                                <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                            </button>

                            <button class="btn btn-danger btn-sm btn-outline waves-effect waves-light"  type="button"
                            id="no_publish">
                                <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                            </button>

                        @endcan

                        @can('add-'.str_slug('set_standard'))
                            <a class="btn btn-success btn-sm waves-effect waves-light"
                               href="{{ url('/tis/set_standard/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('set_standard'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    
                        <div class="row">
                            <div class="col-lg-4 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นชื่อมาตรฐานหรือเลข มอก.']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group  pull-left">
                                    <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                                </div>
                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div>
                            </div><!-- /.col-lg-1 -->
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                 {!! Form::select('filter_status', ['1'=>'เปิดใช้งาน', '2'=>'ปิดใช้งาน'], null, ['id'=>'filter_status', 'class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                            </div><!-- /.col-lg-1 -->
                            {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                                </div>
                           </div><!-- /.col-lg-1 --> --}}
                        </div><!-- /.row -->
 

                    	<div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
                                <div class="form-group col-md-6">
                                {!! Form::label('filter_plan_year', 'ปีที่เสนอเข้าแผน', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('filter_plan_year', HP::Years(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกปีที่เสนอเข้าแผน-']); !!}
                                </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_announce', 'สถานะ กมอ.', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_announce', ['y'=>'ผ่าน กมอ. แล้ว', 'n'=>'อยู่ระหว่างกำหนดมาตรฐาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ กมอ.-']); !!}
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                {!! Form::label('filter_standard_type', 'ประเภท มอก.', ['class' => 'col-md-4 control-label label-filter']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('filter_standard_type', App\Models\Basic\StandardType::selectRaw('CONCAT(title," (",acronym,")") As title, id')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภท มอก.-']); !!}
                                </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_standard_format', 'ทั่วไป/บังคับ', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_standard_format', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกทั่วไป/บังคับ-']); !!}
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_method_id', 'วิธีจัดทำ', ['class' => 'col-md-4 control-label label-filler']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_method_id', App\Models\Basic\Method::pluck('title', 'id'), null,['class' => 'form-control', 'placeholder' => '-เลือกวิธีจัดทำ-']); !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_method_detail', 'รายละเอียดย่อยของวิธีการจัดทำ', ['class' => 'col-md-4 control-label label-filler']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_method_detail', [], null,['class' => 'form-control', 'placeholder' => '-เลือกรายละเอียดย่อยของวิธีการจัดทำ-']); !!}
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                     {!! Form::label('filter_set_format', 'ใหม่/ทบทวน', ['class' => 'col-md-4 control-label label-filler']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_set_format', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือกใหม่/ทบทวน']); !!}
                                    </div>

                                </div>
                                 <div class="form-group col-md-6">
                                    {!! Form::label('filter_activity', 'กิจกรรม', ['class' => 'col-md-4 control-label label-fillter'])!!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_activity', App\Models\Basic\StatusOperation::select(DB::raw("CONCAT(title,' - ',acronym) AS title"), 'id')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือกกิจกรรม-']); !!}
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_product_group', 'กลุ่มผลิตภัณฑ์', ['class' => 'col-md-4 control-label label-fillter'])!!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_product_group', App\Models\Basic\ProductGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือกกลุ่มผลิตภัณฑ์-']); !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                  {!! Form::label('filter_secretary', 'ชื่อเลขา', ['class' => 'col-md-4 control-label label-filter']) !!}
                                  <div class="col-md-8">
                                    {!! Form::text('filter_secretary', null, ['class' => 'form-control', 'placeholder'=>'ค้นหาชื่อเลขา']); !!}
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_staff_group', 'กลุ่มที่', ['class' => 'col-md-4 control-label label-fillter'])!!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_staff_group', App\Models\Basic\StaffGroup::selectRaw('CONCAT(`order`," ",title) As title, id')->where('state',1)->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มที่-']); !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">

                                </div>
                              </div>
                            </div>
                        </div>
                    <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                    <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />
 

                        <div class="clearfix"></div>
                    
                        <div class="table-responsive">

 
                            <table class="table table-striped" id="myTable">
                                <thead >
                                <tr>
                                    <th  width="1%" class="text-center">#</th>
                                    <th  width="1%" class="text-center"><input type="checkbox" id="checkall"></th>
                                    <th  width="10%" class="text-center ">เลขที่ มอก.</th>
                                    <th  width="15%" class="text-center">ชื่อมาตรฐาน</th>
                                    <th  width="10%" class="text-center">ประเภท (ตัวย่อ)</th>
                                    <th  width="10%" class="text-center ">ชื่อเลขา</th>
                                    <th  width="10%" class="text-center ">กลุ่ม</th>
                                    <th  width="15%" class="text-center ">คณะ กว.</th>
                                    <th  width="15%" class="text-center ">ทั่วไป/บังคับ</th>
                                    <th  width="10%" class="text-center ">วิธีจัดทำ</th>
                                    <th  width="15%" class="text-center ">กลุ่มผลิตภัณฑ์</th>
                                    <th  width="10%" class="text-center ">กิจกรรม</th>
                                    <th  width="6%" class="text-center ">สถานะ</th>
                                    <th  width="10%" class="text-center ">จัดการ</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                            </table>

                            <div class="pagination-wrapper">
                              
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endsection



    @push('js')
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script>
    $(document).ready(function () {




           $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_standard_type').val('').select2();
                $('#filter_standard_format').val('').select2();
                $('#filter_method_id').val('').select2();
          
                $('#filter_set_format').val('').select2();
                $('#filter_product_group').val('').select2();
                $('#filter_staff_group').val('').select2();
                $('#filter_activity').val('').select2();
                $('#filter_announce').val('').select2();
                $('#filter_secretary').val('');
                $('#filter_plan_year').val('');
                $('#filter_method_detail').html('<option value="">-เลือกรายละเอียดย่อยของวิธีการจัดทำ-</option>');

                window.location.assign("{{url('/tis/set_standard')}}");
            });

            if($('#filter_standard_type').val()!="" || $('#filter_standard_format').val()!="" ||
              $('#filter_method_id').val()!="" || $('#filter_method_detail').val()!="" || $('#filter_set_format').val()!="" ||
              $('#filter_product_group').val()!="" || $('#filter_staff_group').val()!="" || $('#filter_secretary').val()!="" ||
              $('#filter_activity').val()!="" || $('#filter_announce').val()!="" || $('#filter_plan_year').val()!=""
            ){

                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });


            $('#filter_method_id').change(function(event) {
 
                $('#filter_method_detail').html('<option value="">-เลือกรายละเอียดย่อยของวิธีการจัดทำ-</option>');
                if($(this).val()!=''){
                    const url = "{{ url('tis/set_standard/filter_method_detail') }}/"+$(this).val();
                     $.ajax({
                        type: "GET",
                        url: url,
                        success: function (datas) {
                            if(datas.method_details.length > 0){
                                $.each(datas.method_details,function (index,value) {
                                 $('#filter_method_detail').append('<option value='+index+'  >'+value+'</option>');
                                 });
                            }
                  
                          
                        }
                    });
                }
         
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/tis/set_standard/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_standard_type = $('#filter_standard_type').val();
                        d.filter_standard_format = $('#filter_standard_format').val();
                        d.filter_method_id = $('#filter_method_id').val();
                        d.filter_method_detail = $('#filter_method_detail').val();
                        d.filter_tis_no = $('#filter_tis_no').val();
                        d.filter_set_format = $('#filter_set_format').val();
                        d.filter_product_group = $('#filter_product_group').val();
                        d.filter_staff_group = $('#filter_staff_group').val();
                        d.filter_secretary = $('#filter_secretary').val();
                        d.filter_activity = $('#filter_activity').val();
                        d.filter_announce = $('#filter_announce').val();
                        d.filter_plan_year = $('#filter_plan_year').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'tis_book', name: 'tis_book' },
                    { data: 'title_en', name: 'title_en' },
                    { data: 'standard_type_short_name', name: 'standard_type_short_name' },
                    { data: 'secretary', name: 'secretary' },
                    { data: 'staff_group_name', name: 'staff_group_name' },
                    { data: 'appoint_name', name: 'appoint_name' },
                    { data: 'standard_format_name', name: 'standard_format_name' },
                    { data: 'method_name', name: 'method_name' },
                    { data: 'product_group_name', name: 'product_group_name' },
                    { data: 'operation_result_name', name: 'operation_result_name' },
                    { data: 'state', name: 'state' },
                    { data: 'action', searchable: false, orderable: false}
                ],
                columnDefs: [
                    // { className: "text-center", targets:[0,-1,-2] }
                ],
                fnDrawCallback: function() {
                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);
                }
            });

            
           $( "#button_search" ).click(function() {
            table.draw();
            });

            //เลือกสถานะ เปิด-ปิด
             $('#myTable tbody').on('change', '.js-switch', function(){
                var id = $(this).val();
                var state = $(this).is(":checked")?1:0;

                $.ajax({
                    method: "POST",
                        url: "{{ url('/tis/set_standard/update_status') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "state": state
                    },
                    success : function (msg){
                        if (msg == "success") {
                        table.draw();

                        $.toast({
                            heading: 'Success!',
                            position: 'top-center',
                            text: 'บันทึกสำเร็จ',
                            loaderBg: '#70b7d6',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 6
                        });
  
                        }
                    }
                });

            });

                        //เลือกเปิด-ปิด
            $('#publish, #no_publish').on('click', function () {

                    if($(this).attr('id')=='publish'){
                        var text_alert = 'เปิด';
                        var state = 1;
                    }else if($(this).attr('id')=='no_publish'){
                        var text_alert = 'ปิด';
                        var state = 0;
                    }

                    var arrRowId = [];

                    //Iterate over all checkboxes in the table
                    table.$('.item_checkbox:checked').each(function (index, rowId) {
                        arrRowId.push(rowId.value);
                    });

                    if (arrRowId.length > 0) {

                        if (confirm("ยืนยันการ"+text_alert+" " + arrRowId.length + " แถว นี้ ?")) {

                            $.ajax({
                                method: "POST",
                                url: "{{ url('/tis/set_standard/update_publish') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id_publish": arrRowId,
                                    "state": state
                                },
                                success : function (msg){
                                    if (msg == "success") {
                                        table.draw();
                                        if(text_alert == 'เปิด'){
                             
                                        $.toast({
                                                heading: 'Success!',
                                                position: 'top-center',
                                                text: 'เปิดการใช้งาน !',
                                                loaderBg: '#70b7d6',
                                                icon: 'success',
                                                hideAfter: 3000,
                                                stack: 6
                                            });

                                        }else{
                                
                                            $.toast({
                                                heading: 'Success!',
                                                position: 'top-center',
                                                text: 'ปิดการใช้งาน !',
                                                loaderBg: '#70b7d6',
                                                icon: 'success',
                                                hideAfter: 3000,
                                                stack: 6
                                            });


                                        }
                                        $('#checkall').prop('checked',false );
                                    }
                                }
                            });
                        }

                    }else {
                        alert("โปรดเลือกอย่างน้อย 1 รายการ");
                    }
        });



            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
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
            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
                }

            });

 

    });

 
                function confirm_delete() {
                    return confirm("ยืนยันการลบข้อมูล?");
                }

 
       function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }
            </script>

    @endpush

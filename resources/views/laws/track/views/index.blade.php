@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/components/fullcalendar-v5/css/main.min.css')}}" />
    <style>

    
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left">
                        ติดตามงาน
                    </h3>

                    <div class="pull-right">

                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'เลขที่หนังสือ', '3' => 'ชื่อเรื่อง', '4'=>'ผู้รับผิดชอบ', '5'=> 'ผู้มอบหมาย'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                            ล้างค่า
                                        </button>
                                    </div>
                                    <div class="form-group pull-left m-l-15">
                                        <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status', App\Models\Law\Basic\LawStatusOperation::where('law_bs_category_operate_id', 1)->pluck('title','id')->all() , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">        

                                            
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_deperment_type', 'ประเภทหน่วยงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_deperment_type', [ 1 => 'หน่วยงานภายใน (สมอ.)', 2 => 'หน่วยงานภายนอก' ]  ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทหน่วยงาน -', 'required' => true ,'id'=>'filter_deperment_type' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_law_bs_deperment_id" style="display:none;">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_bs_deperment_id', 'หน่วยงานเจ้าของเรื่อง', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_bs_deperment_id',   App\Models\Law\Basic\LawDepartment::Where('state',1)->pluck('title', 'id') , null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงานเจ้าของเรื่อง -', 'required' => true ,'id'=>'filter_bs_deperment_id']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_sub_departments_id" style="display:none;">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_department_id', 'กลุ่มงานหลัก', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_department_id',  App\Models\Besurv\Department::pluck('depart_name', 'did') ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานหลัก -', 'required' => true , 'id'=>'filter_department_id' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_sub_departments_id" style="display:none;">
                                                    <div class="form-group" >
                                                        {!! Form::label('filter_sub_departments_id', 'กลุ่มงานย่อย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_sub_departments_id',App\Models\Basic\SubDepartment::pluck('sub_departname', 'did'),null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานย่อย -' , 'id'=>'filter_sub_departments_id' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_law_job_type_id', 'ประเภทงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_law_job_type_id', App\Models\Law\Basic\LawJobType::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_law_job_type_id', 'placeholder'=>'-เลือกประเภทงาน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_date', 'วันที่บันทึก', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_assign_start_date', 'วันที่มอบหมาย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_assign_start_date', null, ['class' => 'form-control','id'=>'filter_assign_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_assign_end_date', null, ['class' => 'form-control','id'=>'filter_assign_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_lawyer_start_date', 'วันที่ได้รับมอบหมาย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_lawyer_start_date', null, ['class' => 'form-control','id'=>'filter_lawyer_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_lawyer_end_date', null, ['class' => 'form-control','id'=>'filter_lawyer_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                   
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12"> 
                            <p class="m-l-1 text-primary"></p>
                            <div class="alert alert-bg-primary p-10">
                                 หมายเหตุ : การคำนวณรวมจำนวนวันที่ดำเนินงานจะหักลบ วันหยุดของ สมอ. / วันหยุดเสาร์-อาทิตย์ 

                                 <button type="button" class="m-l-5 btn btn-link btn_modal_calendar" data-toggle="modal" >
                                    ปฏิทินวันหยุด
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12"> 
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12"> 
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="11%">เลขที่อ้างอิง</th>
                                        <th class="text-center" width="11%">เลขที่หนังสือ<br>เลขรับ</th>
                                        <th class="text-center" width="11%">วันที่รับเรื่อง</th>
                                        <th class="text-center" width="10%">ประเภทงาน</th>
                                        <th class="text-center" width="15%">ชื่อเรื่อง</th>
                                        <th class="text-center" width="10%">หน่วยงาน<br>เจ้าของเรื่อง</th>
                                        <th class="text-center" width="10%">ผู้รับผิดชอบ</th>
                                        <th class="text-center" width="10%">ผู้มอบหมาย</th>
                                        <th class="text-center" width="13%">สถานะ</th>
                                        <th class="text-center" width="13%">ติดตาม</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>

    </div>

    @include('laws.track.views.modals.calendar')
    @include('laws.track.receive.modals.cancel')
@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script src="{{asset('plugins/components/fullcalendar-v5/js/main.min.js')}}"></script>
    <script src="{{asset('plugins/components/fullcalendar-v5/locales/locales-all.min.js')}}"></script>
   
    <script>
        var table = '';
        $(document).ready(function () {

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                autoclose: true,
            });


            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/track/views/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();

                        d.filter_deperment_type    = $('#filter_deperment_type').val();
                        d.filter_bs_deperment_id = $('#filter_bs_deperment_id').val();
                        d.filter_department_id   = $('#filter_department_id').val();
                        d.filter_sub_departments_id  = $('#filter_sub_departments_id').val();
                        d.filter_law_job_type_id   = $('#filter_law_job_type_id').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date   = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_lawyer_start_date = $('#filter_lawyer_start_date').val();
                        d.filter_lawyer_end_date = $('#filter_lawyer_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'reference_no', name: 'reference_no' },
                    { data: 'book_no', name: 'book_no' },
                    { data: 'receive_date', name: 'receive_date' },
                    { data: 'law_job_types', name: 'law_job_types' },
                    { data: 'title', name: 'title' },
                    { data: 'law_deparment', name: 'law_deparment' },
                    { data: 'assing', name: 'assing' },
                    { data: 'lawyer', name: 'lawyer' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },

                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);

                    ShowTime();
                }
            });


            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $('#btn_search,i.btn_search').click(function () {
                table.draw();
            });
            
            $('#filter_deperment_type').change(function (e) { 
                BoxDeparment();
                    
            });

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

            var sub_departments_id = $('#filter_sub_departments_id').html();
            $('#filter_department_id').change(function (e) {
            $('#filter_sub_departments_id').html('<option value=""> - เลือกกลุ่มงานย่อย - </option>');
                if($(this).val()!=""){//ดึงประเภทตามหมวดหมู่
                    $.ajax({
                        url: "{!! url('/law/funtion/get-sub-departments') !!}" + "?id=" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_sub_departments_id').append('<option value="'+data.sub_id+'">'+data.sub_departname+'</option>');
                        });
                        $('#filter_sub_departments_id').val('').trigger('change.select2');
                    });
                }else{
                    $('#filter_sub_departments_id').html(sub_departments_id);
                    $('#filter_sub_departments_id').val('').trigger('change.select2');
                }
            });
            

            $(document).on('click','.btn_modal_calendar', function () {
                $('#ModalCalender').modal('show');
                setTimeout(function() {
                    $('#ModalCalender').modal();
                }, 500);
            });
                
            $("body").on("click", ".cancel_modal", function() {                
                    $('#show_status_modal').text($(this).data('cancel_remark'));
                    $('#show_date_modal').text($(this).data('cancel_at'));
                    $('#actionFour').modal('show');
             });

        });

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('#show_time').text(object);
                }
            });
        }

        function BoxDeparment(){

        var type = $('#filter_deperment_type').val();
        var type1 = $('.box_sub_departments_id');
        var type2 = $('.box_law_bs_deperment_id');

            if( type == 1){

                type1.show();
                type1.find('#filter_department_id').prop('disabled', false);
                type1.find('#filter_department_id').prop('required', true);
                
                type2.hide();
                type2.find('select').prop('disabled', true);
                type2.find('select').prop('required', false);

            }else{

                type1.hide();
                type1.find('#filter_department_id').prop('disabled', true);
                type1.find('#filter_department_id').prop('required', false);

                type2.show();
                type2.find('select').prop('disabled', false);
                type2.find('select').prop('required', true);
            }

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

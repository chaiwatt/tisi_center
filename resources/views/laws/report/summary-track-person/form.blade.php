
@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>

    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <p class="h2 text-bold-500 text-center m-5">รายงานสรุปภาพรวมผลงาน</p>
        <p class="h3 text-center text-primary  m-5">{!! !empty($assign_users->StaffName)?$assign_users->StaffName:null !!}</p>
        <p class="h4 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
    </div>
</div>
<div class="clearfix"></div>

<br>

<div class="row box_filter">
    <div class="col-md-12">

        <div class="row">
            <div class="col-lg-7">
                <div class="form-group">
                    {!! Form::label('filter_search', 'ค้นหาจาก'.':', ['class' => 'col-md-2 control-label text-right']) !!}
                    <div class="col-md-4">
                        {!! Form::select('filter_condition_search', ['1'=>'เลขที่อ้างอิง','2'=>'เลขที่หนังสือ','3' => 'เลขรับ', '4' => 'ชื่อเรื่อง', '5' => 'หน่วยงานเจ้าของเรื่อง'  ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="inputWithIcon">
                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'กรอก']); !!}
                            <i class="fa fa-search btn_search"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <button type="button" class="btn btn-info waves-effect waves-light" id="btn_search">ค้นหา</button>
                    <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean"> ล้าง </button>
                </div>
            </div>
            <div class="col-lg-3">
                {!! Form::select('filter_status', App\Models\Law\Basic\LawStatusOperation::pluck('title','id')->all() , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="search-btn" class="panel-collapse collapse">
                    <div class="white-box" style="display: flex; flex-direction: column;">        

                        <div class="row">

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
                                    {!! Form::label('filter_sub_departments_id', 'หน่วยงานภายใน', ['class' => 'col-md-12 label-filter']) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('filter_sub_departments_id', App\Models\Besurv\Department::with('sub_department')->get()->pluck('sub_departments', 'depart_name') , null, ['class' => 'form-control', 'id'=> 'filter_sub_departments_id', 'placeholder'=>'-เลือกหน่วยงานภายใน-']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('filter_law_deperment_id', 'หน่วยงานภายนอก', ['class' => 'col-md-12 label-filter']) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('filter_law_deperment_id', App\Models\Law\Basic\LawDepartment::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_law_deperment_id', 'placeholder'=>'-เลือกหน่วยงานภายนอก-']); !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row m-t-10">
                            
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
                        </div>
                        
                        <div class="row m-t-10">   
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('filter_month_start', 'ช่วงเดือน-ปี ที่มอบหมาย', ['class' => 'col-md-12 label-filter']) !!}
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_assign_month_start', HP_Law::getMonthThais(), null,['class' => 'form-control' , 'id' => 'filter_assign_month_start', 'placeholder' => '-เดือน-']) !!}
                                            </div>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_assign_year_start',  HP::YearListReport(), null,['class' => 'form-control', 'id' => 'filter_assign_year_start', 'placeholder' => '-ปี-']) !!}
                                            </div>
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_assign_month_end', HP_Law::getMonthThais(), null,['class' => 'form-control', 'id' => 'filter_assign_month_end', 'placeholder' => '-เดือน-']) !!}
                                            </div>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_assign_year_end',  HP::YearListReport(), null,['class' => 'form-control', 'id' => 'filter_assign_year_end', 'placeholder' => '-ปี-']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('filter_month_start', 'ช่วงเดือน-ปี ที่บันทึก', ['class' => 'col-md-12 label-filter']) !!}
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_month_start', HP_Law::getMonthThais(), null,['class' => 'form-control', 'id' => 'filter_month_start', 'placeholder' => '-เดือน-']) !!}
                                            </div>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_year_start',  HP::YearListReport(), null,['class' => 'form-control', 'id' => 'filter_year_start', 'placeholder' => '-ปี-']) !!}
                                            </div>                               
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_month_end', HP_Law::getMonthThais(), null,['class' => 'form-control', 'id' => 'filter_month_end', 'placeholder' => '-เดือน-']) !!}
                                            </div>
                                            <div class="input-group-btn bg-white">
                                                {!! Form::select('filter_year_end',  HP::YearListReport(), null,['class' => 'form-control', 'id' => 'filter_year_end', 'placeholder' => '-ปี-']) !!}
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
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <button class="btn btn-success waves-effect waves-light m-l-5" type="button" name="btn_export" id="btn_export">Excel</button>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th width="2%" class="text-center">#</th>
                    <th width="10%" class="text-center">เลขรับ</th>
                    <th width="13%" class="text-center">ประเภทงาน</th>
                    <th width="13%" class="text-center">หน่วยงานต้นเรื่อง</th>
                    <th width="13%" class="text-center">วันที่รับงานเข้า</th>
                    <th width="13%" class="text-center">วันที่หมอบหมาย</th>
                    <th width="13%" class="text-center">วันที่ดำเนินการล่าสุด</th>
                    <th width="13%" class="text-center">รวมจำนวนวันที่ดำเนินงาน</th>
                    <th width="10%" class="text-center">สถานะ</th>

                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

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
                    url: '{!! url('/law/report/summary-track-person/data_track_receive_list') !!}',
                    data: function (d) {
                        d.filter_users_id = '{!! $assign_users->user_id !!}';

                        d.filter_condition_search   = $('#filter_condition_search').val();
                        d.filter_search             = $('#filter_search').val();
                        d.filter_status             = $('#filter_status').val();
                        d.filter_law_job_type_id    = $('#filter_law_job_type_id').val();
                        d.filter_sub_departments_id = $('#filter_sub_departments_id').val();
                        d.filter_law_deperment_id   = $('#filter_law_deperment_id').val();

                        d.filter_assign_month_start = $('#filter_assign_month_start').val();
                        d.filter_assign_year_start  = $('#filter_assign_year_start').val();
                        d.filter_assign_month_end   = $('#filter_assign_month_end').val();
                        d.filter_assign_year_end    = $('#filter_assign_year_end').val();

                        d.filter_month_start = $('#filter_month_start').val();
                        d.filter_year_start  = $('#filter_year_start').val();
                        d.filter_month_end   = $('#filter_month_end').val();
                        d.filter_year_end    = $('#filter_year_end').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date   = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'receive_no', name: 'receive_no' },
                    { data: 'law_job_types', name: 'law_job_types' },
                    { data: 'law_deparment', name: 'law_deparment' },
                    { data: 'receive_date', name: 'receive_date' },
                    { data: 'assign_date', name: 'assign_date' },
                    { data: 'last_date', name: 'last_date' },
                    { data: 'amount', name: 'amount' },
                    { data: 'status', name: 'status' },                    
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0, -1, -2, -3, -4, -5] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });


            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('.box_filter').find('input').val('');
                $('.box_filter').find('select').val('').trigger('change.select2');
                table.draw();
            });

             $(document).on('click', '#btn_export', function(){

                var url = 'law/report/summary-track-person/export_excel_person';
                    url += '?filter_users_id=' + '{!! $assign_users->user_id !!}';
                    url += '&filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_status=' + $('#filter_status').val();

                    url += '&filter_law_job_type_id=' + $('#filter_law_job_type_id').val();
                    url += '&filter_sub_departments_id=' + $('#filter_sub_departments_id').val();
                    url += '&filter_law_deperment_id=' + $('#filter_law_deperment_id').val();

                    url += '&filter_assign_month_start=' + $('#filter_assign_month_start').val();
                    url += '&filter_assign_year_start=' + $('#filter_assign_year_start').val();
                    url += '&filter_assign_month_end=' + $('#filter_assign_month_end').val();
                    url += '&filter_assign_year_end=' + $('#filter_assign_year_end').val();

                    url += '&filter_month_start=' + $('#filter_month_start').val();
                    url += '&filter_year_start=' + $('#filter_year_start').val();
                    url += '&filter_month_end=' + $('#filter_month_end').val();
                    url += '&filter_year_end=' + $('#filter_year_end').val();


                    url += '&filter_start_date=' + $('#filter_start_date').val();
                    url += '&filter_end_date=' + $('#filter_end_date').val();
                    url += '&filter_assign_start_date=' + $('#filter_assign_start_date').val();
                    url += '&filter_assign_end_date=' + $('#filter_assign_end_date').val();

                window.location = '{!! url("'+url +'") !!}';
            });

        });

        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('#show_time').text(object);
                }
            });
        }

    </script>

@endpush
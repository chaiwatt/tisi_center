@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">


                    <h3 class="box-title pull-left">ระบบผลเสนอพิจารณาและประกาศราชกิจจานุเบกษา IB/CB</h3>

                    <div class="pull-right">

                        <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="btn_approve">
                            <i class="fa fa-pencil" aria-hidden="true"></i> บันทึกผล
                        </button>

                        <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" id="btn_tisi_approve">
                            <i class="fa fa-edit" aria-hidden="true"></i> ผลเสนอ กมอ.
                        </button>

                        <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_gen_gazette">
                            <i class="fa fa-book" aria-hidden="true"></i> จัดทำประกาศ
                        </button>

                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light" id="btn_board_approve">
                            <i class="fa fa-clipboard" aria-hidden="true"></i> บันทึกประกาศ
                        </button>

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบผลเสนอพิจารณาและประกาศราชกิจจานุเบกษา IB/CB</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขผู้เสียภาษี/เลขที่คำขอ/เลขผู้เสียภาษี/สาขา/รายสาขา/มอก.']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                            </button>
                                        </div>
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                        </div>   
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                        </div>  
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::select('filter_status', HP::ApplicationStatusIBCB(), null, ['id' => 'filter_status', 'class' => 'form-control', 'placeholder' => '-เลือกสถานะ-']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch_group', 'สาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch', 'รายสาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_board_meeting_result', 'มติคณะอนุกรรมการ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_board_meeting_result', ['-1' => 'รอดำเนินการ', '1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], null, ['class' => 'form-control', 'id'=> 'filter_board_meeting_result', 'placeholder'=>'-เลือกมติคณะอนุกรรมการ-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_tisi_board_meeting_result', 'มติคณะ กมอ.', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_tisi_board_meeting_result', ['-1' => 'รอดำเนินการ', '1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], null, ['class' => 'form-control', 'id'=> 'filter_tisi_board_meeting_result', 'placeholder'=>'-เลือกมติคณะ กมอ.-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_date', 'วันที่ยื่นคำขอ', ['class' => 'col-md-12 label-filter']) !!}
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

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_gazette_start_date', 'วันที่ประกาศราชกิจจา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_gazette_start_date', null, ['class' => 'form-control','id'=>'filter_gazette_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_gazette_end_date', null, ['class' => 'form-control','id'=>'filter_gazette_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_board_meeting_start_date', 'วันที่ประชุมคณะอนุฯ:', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_board_meeting_start_date', null, ['class' => 'form-control','id'=>'filter_board_meeting_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_board_meeting_end_date', null, ['class' => 'form-control','id'=>'filter_board_meeting_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_tisi_board_meeting_start_date', 'วันที่ประชุมคณะ กมอ.:', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_tisi_board_meeting_start_date', null, ['class' => 'form-control','id'=>'filter_tisi_board_meeting_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_tisi_board_meeting_end_date', null, ['class' => 'form-control','id'=>'filter_tisi_board_meeting_end_date']) !!}
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
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th width="2%" class="text-center">ลำดับ</th>
                                        <th width="10%" class="text-center">เลขที่คำขอ/วันที่ยื่นคำขอ</th>
                                        <th width="20%" class="text-center">ผู้ยื่นคำขอ/เลขผู้เสียภาษี</th>
                                        <th width="20%" class="text-center">สาขา</th>
                                        <th width="8%" class="text-center">มติคณะอนุฯ</th>
                                        <th width="8%" class="text-center">มติคณะ กมอ.</th>
                                        <th width="8%" class="text-center">วันที่ประกาศราชกิจจา</th>
                                        <th width="9%" class="text-center">สถานะ</th>
                                        <th width="15%" class="text-center">การจัดการ</th>
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

    <!-- Modal บันทึกผลเสนอคณะอนุกรรมการ -->
    @include ('section5.application-ibcb-board-approve.modal.modal-approve')

    <!-- Modal บันทึกผลเสนอ กมอ. -->
    @include ('section5.application-ibcb-board-approve.modal.modal-tisi-appove')

    <!-- Modal จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม -->
    @include ('section5.application-ibcb-board-approve.modal.modal-gen-gazette')
    @include ('section5.application-ibcb-board-approve.modal.modal-edit-gazette')

    <!-- Modal บันทึกประกาศราชกิจจานุเบกษา -->
    @include ('section5.application-ibcb-board-approve.modal.modal-board-approve')

@endsection


@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
     <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script>

        var table = '';

        $(document).ready(function () {
            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        });

        $(function () {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/application-ibcb-board-approve/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch = $('#filter_branch').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_board_meeting_result = $('#filter_board_meeting_result').val();
                        d.filter_tisi_board_meeting_result = $('#filter_tisi_board_meeting_result').val();
                        
                        d.filter_gazette_start_date = $('#filter_gazette_start_date').val();
                        d.filter_gazette_end_date = $('#filter_gazette_end_date').val();

                        d.filter_board_meeting_start_date = $('#filter_board_meeting_start_date').val();
                        d.filter_board_meeting_end_date = $('#filter_board_meeting_end_date').val();

                        d.filter_tisi_board_meeting_start_date = $('#filter_tisi_board_meeting_start_date').val();
                        d.filter_tisi_board_meeting_end_date = $('#filter_tisi_board_meeting_start_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'application_no', name: 'application_no' },
                    { data: 'applicant_name', name: 'applicant_name' },
                    { data: 'scope', name: 'scope' },
                    { data: 'result', name: 'result' },
                    { data: 'tisi_result', name: 'tisi_result' },   
                    { data: 'government_gazette_date', name: 'government_gazette_date' },                 
                    { data: 'status_application', name: 'status_application' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2,-3,-4,-5] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search,#filter_standard').val('');
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                $('#filter_status').val('').select2();
                table.draw();
            });

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_status').change(function (e) { 
                table.draw();
            });

            $('#filter_branch_group').change(function (e) {

                $('#filter_branch').html('<option value=""> -เลือกรายสาขา- </option>');
                var value = ( $(this).val() != "" )?$(this).val():'ALL';
                if(value){
                    $.ajax({
                        url: "{!! url('/section5/get-branch-data') !!}" + "/" + value
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_branch').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }

            });

            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $('#btn_approve').click(function(event) {

                $('#myTable-Mapprove tbody').html('');

                $("#modal_form_approve").find('.fileinput').fileinput('clear');
                $("#modal_form_approve").find('input, textarea').val();
                $("#modal_form_approve").find('.m_board_meeting_result[value="1"]').prop('checked', true);
                $("#modal_form_approve").find('.m_board_meeting_result').iCheck('update');

                if($('.item_checkbox:checked').length > 0){
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {
                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('app_no'))+'<input type="hidden" name="id[]" class="item_m_ap_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('scope'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('type'))+'</td>';
                        tr_ += '</tr>';
                    });

                    $('#myTable-Mapprove tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_approve').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขอที่จะจัดทำประกาศอย่างน้อย 1 คำขอ');
                }
            });

            $('#btn_gen_gazette').click(function(event) {

                $('#myTable-Mannouncement tbody').html('');

                if($('.item_checkbox:checked').length > 0){

                    var status_fail = false;//true=วันที่ไม่เหมือนกัน

                    var date_first  = null;//วันที่ของรายการแรก

                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        //เช็คสถานะ
                        const announce_status = [13, 14, 15];//13=อยู่ระหว่างจัดทำประกาศ, 14=จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ, 15=ประกาศราชกิจจาฯ แล้ว
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะที่จัดทำประกาศได้
                            status_fail = true;
                        }

                        if( status_fail ==false ){
                            var gazette_issue =  $(el).data('gazette_issue');
                            var gazette_year  =  $(el).data('gazette_year');

                            tr_ += '<tr>';
                            tr_ += '<td class="text-top">'+(index+1)+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('app_no'))+'<input type="hidden" name="id[]" data-issue="'+(checkNone(gazette_issue)?gazette_issue:'')+'" data-year="'+(checkNone(gazette_year)?gazette_year:'')+'" class="item_m_id" value="'+($(el).val())+'"> </td>';
                            tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('scope'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('type'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('meeting_date_txt'))+'</td>';
                            tr_ += '</tr>';

                        }

                    });

                    if(status_fail){//สถานะไม่สามารถประกาศได้
                        alert('กรุณาเลือกรายการที่มี สถานะ "อยู่ระหว่างจัดทำประกาศ", "จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ" หรือ "ประกาศราชกิจจาฯ แล้ว"');
                        return false;
                    }

                    //ใส่ข้อมูลในตาราง
                    $('#myTable-Mannouncement tbody').html(tr_);

                    //ถ้าเลือกข้อมูลแค่รายการเดียว
                    if($('.item_checkbox:checked').length==1){
                        var item = $('.item_checkbox:checked');

                        if( checkNone($(item).data('gazette_issue'))  ){
                            $('#m_issue').val($(item).data('gazette_issue'));
                        }else{
                            $.ajax({
                                url: "{!! url('/section5/application-ibcb-board-approve/get_issue_gazette') !!}" + "?year=" + $('#m_year').val()  + '&type=get'
                            }).done(function( object ) {
                                $('#m_issue').val(object);
                            });
                        }

                        if( checkNone($(item).data('gazette_year'))  ){
                            $('#m_year').val($(item).data('gazette_year'));
                        }

                        //ใส่ข้อมูล
                    
                        $('#m_announcement_date').val($(item).data('gazette_announcement_date'));
                        $('#m_sign_id').val($(item).data('gazette_sign_id')).trigger('change');
               
                        if($(item).data('gazette_announcement_date')!=''){//จัดทำแล้ว
                            $('#link-doc-gazette').prop('href', '{!! url('section5/application-ibcb-board-approve/preview_document') !!}/' + $(item).val())
                            $('#link-doc-gazette').show();
                        }
                    }else{

                        $.ajax({
                            url: "{!! url('/section5/application-ibcb-board-approve/get_issue_gazette') !!}" + "?year=" + $('#m_year').val()  + '&type=get'
                        }).done(function( object ) {
                            $('#m_issue').val(object);
                        });

                        $('#link-doc-gazette').hide();
                    }

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_announcement').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขอที่จะจัดทำประกาศอย่างน้อย 1 คำขอ');
                }

            });

            $('#btn_tisi_approve').click(function(event) {

                $('#myTable-Mtisi_approve tbody').html('');

                $("#modal_form_tisi_approve").find('.fileinput').fileinput('clear');
                $("#modal_form_tisi_approve").find('input, textarea').val('');
                $("#modal_form_tisi_approve").find('.m_tisi_board_meeting_result[value="1"]').prop('checked', true);
                $("#modal_form_tisi_approve").find('.m_tisi_board_meeting_result').iCheck('update');

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=วันที่ไม่เหมือนกัน
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        //เช็คสถานะ
                        const announce_status = [11, 12, 13];//11=อยู่ระหว่างเสนอ. กมอ., 12=กมอ. ไม่อนุมัติ ตรวจสอบอีกครั้ง, 13=อยู่ระหว่างจัดทำประกาศ
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะที่จัดทำประกาศได้
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('app_no'))+'<input type="hidden" name="id[]" class="item_m_tisi_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('scope'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('type'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('meeting_date_txt'))+'</td>';
                        tr_ += '</tr>';
                    });

                    if(status_fail){//สถานะไม่สามารถประกาศได้
                        alert('กรุณาเลือกรายการที่มี สถานะ "อยู่ระหว่างเสนอ. กมอ.", "กมอ. ไม่อนุมัติ ตรวจสอบอีกครั้ง" หรือ "อยู่ระหว่างจัดทำประกาศ"');
                        return false;
                    }

                    $('#myTable-Mtisi_approve tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_tisi_approve').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขอที่จะจัดทำประกาศอย่างน้อย 1 คำขอ');
                }

            });

            //คลิกจัดทำประกาศจากแต่ละรายการ
            $('body').on('click', '.btn_edit_gazette', function () {

                //สั่งติ๊กเช็คบล็อค แถวเดียวกับรายการที่คลิก
                $('.item_checkbox').prop('checked', false);
                $(this).closest('tr').find('.item_checkbox').prop('checked', true);

                $('#btn_gen_gazette').click();

            });

            $('#btn_edit_gazette').click(function (e) {
                $('#me_edit').val('2');
                MeButton();
            });

            $('#btn_cancel_gazette').click(function (e) {
                $('#me_edit').val('1');
                MeButton();
            });


            $('#btn_board_approve').click(function(event) {

                $('#myTable-Mboard_approve tbody').html('');

                $("#modal_form_board_approve").find('.fileinput').fileinput('clear');
                $("#modal_form_board_approve").find('input, textarea').val();

                if($('.item_checkbox:checked').length > 0){

                    var id_board_approve_fail = false;//true=ยังไม่บันทึก วันที่ประชุมคณะอนุกรรมการ

                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        if( !checkNone($(el).data('board_approve_id'))  ){
                            id_board_approve_fail = true;
                        }

                        if( checkNone($(el).data('board_approve_id')) ){
                            tr_ += '<tr>';
                            tr_ += '<td class="text-top">'+(index+1)+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('app_no'))+'<input type="hidden" name="id[]" class="item_m_bap_id" value="'+($(el).val())+'"> </td>';
                            tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('scope'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('type'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('meeting_date_txt'))+'</td>';
                            tr_ += '<td class="text-top">'+($(el).data('email'))+'</td>';
                            tr_ += '</tr>';
                        }

                    });

                    if(id_board_approve_fail){//ยังไม่บันทึก วันที่ประชุมคณะอนุกรรมการ
                        alert('กรุณาเลือกรายการที่มี วันที่ประชุมคณะอนุกรรมการ');
                        return false;
                    }

                    $('#myTable-Mboard_approve tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_board_approve').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขอที่จะจัดทำประกาศอย่างน้อย 1 คำขอ');
                }
            });

            $('#btn_print_word').click(function (e) {
                var id = $('#me_id_edit').val();
                var url = '{!! url('section5/application-ibcb-board-approve/preview_document') !!}'+ '/' + id;
                window.open(url, '_blank');
            });

            $('body').on('change', '#m_year', function () {

                $('#m_issue').val('');

                if( $(this).val() != ''){
                    $.ajax({
                        url: "{!! url('/section5/application-ibcb-board-approve/get_issue_gazette') !!}" + "?year=" + $(this).val() + '&type=get'
                    }).done(function( object ) {
                        $('#m_issue').val(object);
                    });
                }

            });

        });

        function LoadDataGazette(id){

            $('#modal_gazette').find('input').val('');
            $('#modal_gazette').find('select').val('').trigger('change.select2');

            $('#modal_gazette').find('#me_edit').val('1');

            MeButton();

            $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{!! url('/section5/application-ibcb-board-approve/load_data_gazette') !!}" + "/" + id
            }).done(function( object ) {

                if( checkNone(object) ){

                    $('#me_issue').val(object.issue);
                    $('#me_year').val(object.year).trigger('change.select2');
                    $('#me_announcement_date').val(object.announcement_date);
                    $('#me_sign_id').val(object.sign_id).trigger('change.select2');
                    $('#me_sign_position').val(object.sign_position);

                    $('#me_id_edit').val(object.id);

                    $('#me_id_edit').val(object.id);
                    $('#me_app_name').val(object.applicant_name);
                    $('#me_app_taxid').val(object.applicant_taxid);
                    $('#me_app_no').val(object.application_no);
                    $('#me_app_board_meeting_date').val(object.board_meeting_date);
                    $.LoadingOverlay("hide");
                }

            });
        }

        function MeButton(){

            var edit = $('#me_edit').val();

            $('#me_issue').prop('disabled', true);
            $('#me_year').prop('disabled', true);
            $('#me_announcement_date').prop('disabled', true);
            $('#me_sign_id').prop('disabled', true);
            $('#me_sign_position').prop('disabled', true);

            if( edit == 1){

                $('#btn_save_gazette').hide();
                $('#btn_cancel_gazette').hide();

                $('#btn_print_word').show();
                $('#btn_edit_gazette').show();
                $('#btn_close_gazette').show();

            }else{

                $('#me_issue').prop('disabled', false);
                $('#me_year').prop('disabled', false);
                $('#me_announcement_date').prop('disabled', false);
                $('#me_sign_id').prop('disabled', false);
                $('#me_sign_position').prop('disabled', false);

                $('#me_sign_position').prop('readonly', true);

                $('#btn_print_word').hide();
                $('#btn_edit_gazette').hide();
                $('#btn_close_gazette').hide();

                $('#btn_save_gazette').show();
                $('#btn_cancel_gazette').show();
            }

        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function SaveApprove(){

            var id = [];
            $('.item_m_ap_id').each(function(index, element){
                id.push($(element).val());
            });

            var board_meeting_date = $('#m_board_meeting_date').val();
            var file_approve = $('#m_file_approve')[0].files[0];

            if( id.length > 0 && checkNone(board_meeting_date) && checkNone(file_approve) ){

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                var result = (( $("#m_board_meeting_result-1").is(':checked') )?'1':'2');

                var formData = new FormData($("#modal_form_approve")[0]);
                    formData.append('m_board_meeting_result', result);
                    formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-board-approve/update_approve') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();

                            Swal.fire({
                                // position: 'top-end',
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_approve').modal('hide');
                        }
                    }
                });

            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }
        }

        function SaveAnnouncement(){

            var issue = $('#m_issue').val();
            var year = $('#m_year').val();
            var announcement_date = $('#m_announcement_date').val();
            var sign_id = $('#m_sign_id').val();
            var sign_position = $('#m_sign_position').val();
            var sign_name = $('#m_sign_id').find('option:selected').text();

            var id = [];
            $('.item_m_id').each(function(index, element){
                id.push($(element).val());
            });

            if( id.length > 0 && checkNone(issue) && checkNone(year) && checkNone(announcement_date) && checkNone(sign_id) && checkNone(sign_position)  ){

                $.ajax({
                    url: "{!! url('/section5/application-ibcb-board-approve/get_issue_gazette') !!}" + "?year=" + year + '&issue='+ issue + '&type=check'
                }).done(function( object ) {
                    if( object == 'true'){

                        if( confirm("ประกาศราชกิจจา ฉบับที่ "+(issue)+" ปีที่ประกาศ "+(parseInt(year)+543)+" มีอยู่แล้ว ต้องการสร้างใหม่หรือใหม่") ){

                            $.LoadingOverlay("show", {
                                image: "",
                                text: "กำลังบันทึก กรุณารอสักครู่..."
                            });

                            $.ajax({
                                method: "POST",
                                url: "{{ url('/section5/application-ibcb-board-approve/save_announcement') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": id,
                                    "issue": issue,
                                    "year": year,
                                    "announcement_date": announcement_date,
                                    "sign_id": sign_id,
                                    "sign_name": sign_name,
                                    "sign_position": sign_position
                                },
                                success : function (obj){

                                    if (obj.msg == "success") {
                                        table.draw();

                                        Swal.fire({
                                            // position: 'top-end',
                                            icon: 'success',
                                            title: 'บันทึกสำเร็จ !',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $.LoadingOverlay("hide");
                                        $('#modal_announcement').modal('hide');
                                    }
                                }
                            });
                        }
          
                    }else{
                        $.LoadingOverlay("show", {
                            image: "",
                            text: "กำลังบันทึก กรุณารอสักครู่..."
                        });

                        $.ajax({
                            method: "POST",
                            url: "{{ url('/section5/application-ibcb-board-approve/save_announcement') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id,
                                "issue": issue,
                                "year": year,
                                "announcement_date": announcement_date,
                                "sign_id": sign_id,
                                "sign_name": sign_name,
                                "sign_position": sign_position
                            },
                            success : function (obj){

                                if (obj.msg == "success") {
                                    table.draw();

                                    Swal.fire({
                                        // position: 'top-end',
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ !',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $.LoadingOverlay("hide");
                                    $('#modal_announcement').modal('hide');
                                }
                            }
                        });
                    }
                });

            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }
        }

        function SaveGazette(){

            var id = $('#me_id_edit').val();
            var issue = $('#me_issue').val();
            var year = $('#me_year').val();
            var announcement_date = $('#me_announcement_date').val();
            var sign_id = $('#me_sign_id').val();
            var sign_position = $('#me_sign_position').val();
            var sign_name = $('#me_sign_id').find('option:selected').text();

            if( checkNone(id) && checkNone(issue) && checkNone(year) && checkNone(announcement_date) && checkNone(sign_id) && checkNone(sign_position)  ){

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-board-approve/save_data_gazette') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "issue": issue,
                        "year": year,
                        "announcement_date": announcement_date,
                        "sign_id": sign_id,
                        "sign_name": sign_name,
                        "sign_position": sign_position
                    },
                    success : function (obj){
                        if (obj.msg == "success") {
                            table.draw();

                            Swal.fire({
                                // position: 'top-end',
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#me_edit').val('1');
                            MeButton();

                            LoadDataGazette(id)

                        }
                    }
                });
            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }
        }

        function SaveBoradApprove(){

            var id = [];
            $('.item_m_bap_id').each(function(index, element){
                id.push($(element).val());
            });

            var government_gazette_date = $('#mb_government_gazette_date').val();
            var lab_start_date = $('#mb_ibcb_start_date').val();
            var lab_end_date = $('#mb_ibcb_end_date').val();
            var file_gazette = $('#mb_file_gazette')[0].files[0];

            if( id.length > 0 && checkNone(government_gazette_date) && checkNone(lab_start_date) && checkNone(lab_end_date) && checkNone(file_gazette) ){

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                var formData = new FormData($("#modal_form_board_approve")[0]);
                    formData.append('_token', "{{ csrf_token() }}")

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-board-approve/update_board_approve') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();

                            Swal.fire({
                                // position: 'top-end',
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_board_approve').modal('hide');
                        }
                    }
                });
            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }

        }

        function SaveBoradTisiApprove(){
            var id = [];
            $('.item_m_tisi_id').each(function(index, element){
                id.push($(element).val());
            });

            var m_tisi_board_meeting_date = $('#m_tisi_board_meeting_date').val();
            var file_tisi_approve = $('#m_file_tisi_approve')[0].files[0];
            
            if( id.length > 0 && checkNone(m_tisi_board_meeting_date) && checkNone(file_tisi_approve) ){

               var result = (( $("#m_tisi_board_meeting_result-1").is(':checked') )?'1':'2');

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                var formData = new FormData($("#modal_form_tisi_approve")[0]);
                    formData.append('m_tisi_board_meeting_result', result);
                    formData.append('_token', "{{ csrf_token() }}");
    
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-board-approve/update_tisi_approve') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();

                            Swal.fire({
                                // position: 'top-end',
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_tisi_approve').modal('hide');
                        }
                    }
                });

            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }
        }

    </script>
@endpush

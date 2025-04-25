@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style>

    </style>
@endpush

<div class="row">
    <div class="col-md-7">
        <div class="form-group {{ $errors->has('application_type') ? 'has-error' : ''}}">
            {!! Form::label('application_type', 'ประเภทหน่วยตรวจสอบ', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>{!! Form::radio('application_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'application_type_1']) !!} IB</label>
                <label>{!! Form::radio('application_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'application_type_2']) !!} CB</label>
                {!! $errors->first('application_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="form-group {{ $errors->has('audit_type') ? 'has-error' : ''}}">
            {!! Form::label('audit_type', 'การได้รับใบรับรองระบบงาน', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>{!! Form::radio('audit_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'audit_type_1']) !!} ได้รับ พร้อมแนบหลักฐาน</label>
                <label class="lable_audit_type2">{!! Form::radio('audit_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'audit_type_2']) !!} ไม่ได้รับ ทำการตรวจประเมิน ภาคผนวก ก.</label>
                {!! $errors->first('audit_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<!-- ข้อมูลผู้ยื่น -->
@include ('section5.manage-ibcb.add-new.infomation')

<!-- ใบรับรองระบบงานตามมาตรฐาน -->
@include ('section5.manage-ibcb.add-new.certificate')
@include ('section5.manage-ibcb.modals.modal-certificate')

<!-- ข้อมูลขอรับบริการ -->
@include ('section5.manage-ibcb.add-new.scope')
@include ('section5.manage-ibcb.modals.modal-scope-branches-tis-details')

<!-- รายชื่อผู้ตรวจที่ผ่านการแต่งตั้ง -->
@include ('section5.manage-ibcb.add-new.inspectors')
@include ('section5.manage-ibcb.modals.modal-inspector')


<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('start_date', 'ขอบข่ายที่ขอรับการแต่งตั้งมีผลตั้งแต่วันที่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('start_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('end_date', 'วันที่สิ้นสุด', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('end_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<center>
    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">

            @can('add-'.str_slug('manage-ibcb'))
                <button class="btn btn-primary show_tag_a" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
            @endcan
            <a class="btn btn-default show_tag_a" href="{{url('/section5/ibcb')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        </div>
    </div>
</center>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script>

        var list_isic_no =  $.parseJSON('{!! json_encode(App\Models\Basic\BranchGroup::whereNotNull('isic_no')->select('isic_no', 'id')->get()->pluck('isic_no', 'id')->toArray()) !!}');
        var inspector_owns = $.parseJSON('{!! json_encode([]) !!}');//รายชื่อผู้ตรวจที่สังกัดหน่วยงานที่ยื่นคำขอนี้
        var tr_inspector_own = $('#table-inspectors tbody').html();//รายชื่อผู้ตรวจที่สังกัดหน่วยงานที่ยื่นคำขอนี้ เป็น html

        var tableCer = '';
        jQuery(document).ready(function() {

            $('#btn_std_export').click(function (e) {
                $('#Mcertificate').modal('show');
            });

            tableCer = $('#myTableCertificate').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    "url": '{!! url('/section5/ibcb/getDataCertificate') !!}',
                    "dataType": "json",
                    "data": function (d) {
                        d.application_type = $("input[name=application_type]:checked").val();
                        d.table = (( $("input[name=application_type]:checked").val() == 1 )?'app_certi_ib_export':'app_certi_cb_export');
                        d.applicant_taxid = $('#applicant_taxid').val();
                        d.search = $('#modal_cer_search').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'cb_name', name: 'cb_name' },
                    { data: 'formula', name: 'formula' },
                    { data: 'certificate', name: 'certificate' },
                    { data: 'date_start', name: 'date_start' },
                    { data: 'date_end', name: 'date_end' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [

                ],
                fnDrawCallback: function() {

                }
            });

            $("body").on('keyup', '#modal_cer_search', function () {
                tableCer.draw();
            });

            $('input[name=application_type]').on('ifChecked', function(event){
                tableCer.draw();

                if( $(this).val() == 2 ){
                    $('#audit_type_1').iCheck('check');
                    BoxAuditType1();
                    $('.lable_audit_type2').hide();
                    LoadStdType()
                }else{
                    $('#audit_type_2').iCheck('check');
                    BoxAuditType1();
                    $('.lable_audit_type2').show();
                    LoadStdType()
                }

            });
            LoadStdType();

            $('input[name=audit_type]').on('ifChecked', function(event){
                BoxAuditType1();
            });
            BoxAuditType1();


            $('body').on('click','.btn_select_cer', function () {

                var cerno =  $('#certificate_cerno_export');
                var issue_date =  $('#certificate_issue_date');
                var expire_date =  $('#certificate_expire_date');

                var Mcer_no = $(this).data('certificate_no');
                var Mdate_start = $(this).data('date_start');
                var Mdate_end = $(this).data('date_end');
                var Mid = $(this).data('id');
                var Mtable = $(this).data('table');

                $(cerno).val(Mcer_no);
                $(issue_date).val(Mdate_start);
                $(expire_date).val(Mdate_end);

                $( cerno ).attr( "data-id", (checkNone(Mid)?Mid:'') );
                $( cerno ).attr( "data-table", (checkNone(Mtable)?Mtable:'') );

                $('#btn_std_export').val(2);

                ShowInputCertificate();

                $('#Mcertificate').modal('hide');

            });

            $('body').on('click','#btn_cer_add', function (e) {

                var std =  $('#certificate_std_export').val();
                var std_txt = $('#certificate_std_export').find('option:selected').text();
                var cerno =  $('#certificate_cerno_export').val();
                var issue_date =  $('#certificate_issue_date').val();
                var expire_date =  $('#certificate_expire_date').val();

                if( !checkNone(std) ){
                    alert("กรุณากรอก มอก. รับรองระบบงาน !");
                }else if( !checkNone(cerno) ){
                    alert("กรุณากรอก เลขที่ได้รับการรับรอง !");
                }else if( !checkNone(issue_date) ){
                    alert("กรุณากรอก วันที่ออกใบรับรอง !");
                }else if( !checkNone(expire_date) ){
                    alert("กรุณากรอก วันที่หมดอายุใบรับรอง !");
                }else{

                    var id = $('#certificate_cerno_export').data( "id" );
                    var table = $('#certificate_cerno_export').data( "table" );

                    var val_btn = $('#btn_std_export').val();

                    if( val_btn == 1){
                        id = '';
                        table = '';
                    }

                    var certificate_std_id  = '<input type="hidden" name="certificate_std_id" value="'+std+'">';
                    var certificate_id  = '<input type="hidden" class="certificate_id" name="certificate_id" value="'+(checkNone(id)?id:'')+'">';
                    var certificate_no  = '<input type="hidden" class="certificate_no" name="certificate_no" value="'+(checkNone(cerno)?cerno:'')+'">';
                    var certificate_start_date  = '<input type="hidden" name="certificate_start_date" value="'+issue_date+'">';
                    var certificate_end_date  = '<input type="hidden" name="certificate_end_date" value="'+expire_date+'">';
                    var certificate_table  = '<input type="hidden" name="certificate_table" value="'+(checkNone(table)?table:'')+'">';

                    var btn = '<button class="btn btn-sm btn-danger" type="button" data-repeater-delete> <i class="fa fa-minus"></i></button>';

                    var values =  $('#table-certificate').find(".certificate_id").map(function(){return $(this).val(); }).get();

                    if(checkNone(id)){
                        var url_center = '{!! isset( HP::getConfig()->url_center )?HP::getConfig()->url_center:'' !!}';
                        var tag_a = '<a href="'+(url_center)+'/api/v1/certificate?cer='+(cerno)+'"  target="_blank"><span class="text-info">'+(cerno)+'</span></a>';
                    }else{
                        var tag_a = cerno;
                    }

                    if( checkNone(id) ){
                        if( values.indexOf( String(id) ) == -1 ){
                            var tr_ = '<tr data-repeater-item>';
                                tr_ += '<td>'+tag_a+' '+ certificate_id + certificate_no +'</td>';
                                tr_ += '<td>'+issue_date+' '+ certificate_start_date +'</td>';
                                tr_ += '<td>'+expire_date+' '+ certificate_end_date +'</td>';
                                tr_ += '<td>'+std_txt+' '+certificate_std_id+'</td>';
                                tr_ += '<td>'+btn+' '+ certificate_table +'</td>';
                                tr_ += '</tr>';

                            $('#table-certificate tbody').append(tr_);
                            $('.certificate-repeater').repeater();
                        }
                    }else{
                        var tr_ = '<tr data-repeater-item>';
                            tr_ += '<td>'+tag_a+' '+ certificate_id + certificate_no +'</td>';
                            tr_ += '<td>'+issue_date+' '+ certificate_start_date +'</td>';
                            tr_ += '<td>'+expire_date+' '+ certificate_end_date +'</td>';
                            tr_ += '<td>'+std_txt+' '+certificate_std_id+'</td>';
                            tr_ += '<td>'+btn+' '+ certificate_table +'</td>';
                            tr_ += '</tr>';

                        $('#table-certificate tbody').append(tr_);
                        $('.certificate-repeater').repeater();

                    }

                    setTimeout(function(){

                        $('#certificate_std_export').val('').select2();
                        $('#certificate_cerno_export').val('');
                        $('#certificate_issue_date').val('');
                        $('#certificate_expire_date').val('');

                        $('#certificate_cerno_export').removeAttr( "data-id" );
                        $('#certificate_cerno_export').removeAttr( "data-table" );

                        $('#btn_std_export').val(1);
                        ShowInputCertificate();
                    }, 100);
                }

            });


            $('#scope_branches_group').change(function (e) {
                LoadBranche();
                $('#scope_isic_no').val('');
                if( checkNone($(this).val()) && checkNone(list_isic_no[ $(this).val() ])){
                    $('#scope_isic_no').val(list_isic_no[ $(this).val() ]);
                }
            });

            //เมื่อเลือกรายสาขา
            $('#scope_branches_multiple').change(function(event) {
                LoadBrancheTis();
            });

            $('#check_all_scope_branches_tis').on('ifChecked', function (event){
                $("#scope_branches_tis > option").prop("selected", "selected");
                $('#scope_branches_tis').trigger("change");
            });

            $('#check_all_scope_branches_tis').on('ifUnchecked', function (event){
                $('#scope_branches_tis').val('').trigger("change");
            });

            $('#scope_branches_tis').on('change', function (e) {
                var tis_length = $(this).find('option').length;
                var tis_length_selected = $(this).find('option:selected').length;
                if(tis_length != 0){
                    if(tis_length == tis_length_selected){
                        $('#check_all_scope_branches_tis').iCheck('check');
                    }else{
                        $('#check_all_scope_branches_tis').iCheck('uncheck');
                    }
                }
            });

            $('#btn_branche_add').click(function (e) {

                var branches_group =  $('#scope_branches_group').val();
                var branches =  $('#scope_branches_multiple').val();
                var branches_tis =  $('#scope_branches_tis').val();
                var isic_no =  $('#scope_isic_no').val();

                if( !checkNone(branches_group) ){
                    alert("กรุณาเลือก สาขาผลิตภัณฑ์ !");
                }else if( !checkNone(branches)  ){
                    alert("กรุณาเลือก รายสาขา !");
                }else if( !checkNone(branches_tis) ){
                    alert("กรุณาเลือก สาขาผลิตภัณฑ์ ที่มีเลขที่ มอก. !");
                }else{

                    var branches_txt = [];
                    var branch_input = '';
                    $( "#scope_branches_multiple option:selected" ).each(function( index, data ) {
                        branches_txt.push($(data).text());
                        branch_input += '<input type="hidden" name="branch_id" value="'+$(data).val()+'" class="input_array" data-name="branch_id">';
                    });

                    var tis_arr = [];
                    var tis_details = '';
                    var tis_no_input  = '';
                    var tis_detail_arr = [];
                    $( "#scope_branches_tis option:selected" ).each(function( index, data ) {
                        var tis_cut = $(data).text().split(':');
                        tis_detail_arr.push( $(data).text() );
                        tis_arr.push( checkNone(tis_cut[0])?tis_cut[0]:'' );
                        tis_details  += '<input type="hidden" data-tis_no="'+( checkNone(tis_cut[0])?tis_cut[0]:'' )+'" value="'+(checkNone(tis_cut[1])?tis_cut[1]:'')+'" class="tis_details">';
                        tis_no_input  += '<input type="hidden" name="tis_id" value="'+$(data).val()+'" class="input_array" data-name="tis_id">';
                    });

                    var branches_group_txt = $( "#scope_branches_group option:selected" ).text();

                    var branch_group_id  = '<input type="hidden" class="branch_group_id" name="branch_group_id" value="'+branches_group+'" data-name="branch_group_id">';
                    var isic_no_input = '<input type="hidden" name="isic_no" value="'+isic_no+'" data-name="isic_no">';

                    var tis_show = `<a class="open_scope_branches_tis_details" data-detail="${tis_detail_arr}" href="javascript:void(0)" title="คลิกดูรายละเอียด">${tis_arr.join(', ')}</a>`;

                    var btn = '<button class="btn btn-sm btn-danger btn_remove" type="button" data-repeater-delete> <i class="fa fa-minus"></i></button>';

                    var tr_ =   `
                                    <tr data-repeater-item>
                                        <td class="no text-center"></td>
                                        <td class="text-left">
                                            ${branches_group_txt}
                                            ${branch_group_id}
                                        </td>
                                        <td>
                                            ${branches_txt.join(', ')}
                                            ${branch_input}
                                        </td>
                                        <td>
                                            ${( checkNone(isic_no)?isic_no:'-' )}
                                            ${isic_no_input}
                                        </td>
                                        <td class="text-ellipsis">
                                            ${tis_show}
                                            ${tis_details}
                                            ${tis_no_input}
                                        </td>
                                        <td class="text-center">
                                            ${btn}
                                        </td>
                                    </tr>
                                `;

                    var values =  $('#table-scope').find(".branch_group_id").map(function(){return $(this).val(); }).get();

                    if( values.indexOf( String(branches_group) ) == -1 ){
                        $('#box_list_scpoe').append(tr_);
                    }else{

                        Swal.fire({
                            type: 'warning',
                            title: 'เลือกสาขาผลิตภัณฑ์ '+(branches_group_txt)+' ซ้ำ',
                            html: '<p class="h5">หากต้องการเพิ่มรายสาขาให้ลบสาขาออกแล้วเพิ่ม สาขาผลิตภัณฑ์ใหม่</p>',
                            width: 500
                        });
                    }

                    reset_name();
                    resetOrderNo();

                    $('#scope_branches_group').val('').trigger("change");
                    $('#scope_isic_no').val('');

                    update_table_inspectors();//อัพเดทข้อมูลตารางผู้ตรวจ

                    $('#check_all_scope_branches_tis').iCheck('uncheck');
                }

            });
            reset_name();

            $(document).on('click', '.btn_remove', function(){
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest('tr').remove();
                    update_table_inspectors();
                }
            });


            $(document).on('click', '.open_scope_branches_tis_details', function(){

                $("#table_scope_branches_tis_details").DataTable().clear().destroy();

                open_scope_branches_tis_details($(this));

                $('#table_scope_branches_tis_details').DataTable({
                    searching: true,
                    autoWidth: false,
                    columnDefs: [
                        { className: "text-center col-md-1", targets: 0 },
                        { className: "col-md-9", targets: 1 },
                        { width: "10%", targets: 0 }
                    ]
                });

                $('#maodal_scope_branches_tis_details').modal('show');

            });  


            //เมื่อคลิกปุ่ม ค้นหาผู้ตรวจ
            $('#btn_modal_inspectors').click(function (e) {
                $('#Minspectors').modal('show'); //แสดง modal ผู้ตรวจ
            });

            //เมื่อเลือกวันที่มีผลเป็นหน่วยตรวจสอบ บวก 3 ปีเป็นวันที่สิ้นสุด
            $('#start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#end_date').val(expire_date);
                }else{
                    $('#end_date').val('');
                }
            });

        });

        function BoxAuditType1(){
            var audit_type =  ($("input[name=audit_type]:checked").val() == 1 )?'1':'2';
            if( audit_type == '1' ){
                $('.box_audit_type_1').show();
                $('.box_audit_type_1').find('input').prop('disabled', false);
            }else{
                $('.box_audit_type_1').hide();
                $('.box_audit_type_1').find('input').prop('disabled', true);
            }
        }

        
        function ShowInputCertificate(){

            var value_btn = $('#btn_std_export').val();

            $('#certificate_issue_date').prop('disabled', true);
            $('#certificate_expire_date').prop('disabled', true);

            $('body').find('.certificate_cerno_export').prop('disabled', true);

            if( value_btn == '1'){
                $('body').find('.certificate_cerno_export').prop('disabled', false);
                $('#certificate_issue_date').prop('disabled', false);
                $('#certificate_expire_date').prop('disabled', false);
            }else if(  value_btn == '2' ){
                $('body').find('.certificate_cerno_export').prop('disabled', true);
            }
        }

        function LoadBranche(){

            var branches_group = $("#scope_branches_group").val();

            var select = $('#scope_branches_multiple');

            $(select).html('');
            $(select).val('').trigger('change');

            if( checkNone(branches_group) ){
                $.ajax({
                    url: "{!! url('/section5/ibcb/get-branche') !!}" + "/" + branches_group
                }).done(function( object ) {

                    if( checkNone(object) ){
                        $.each(object, function( index, data ) {
                            $(select).append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    }

                });
            }

            setTimeout(function(){
                $("#scope_branches_multiple > option").prop("selected", "selected");
                $('#scope_branches_multiple').trigger("change");
            }, 500);

        }

        function LoadBrancheTis(){

            //รายสาขา
            var branch_ids = $("#scope_branches_multiple").val();

            //เลขที่ มอก.
            var select = $('#scope_branches_tis');
            $(select).html('');
            $(select).val('').trigger('change');

            $('#check_all_scope_branches_tis').iCheck('uncheck');//เคลียร์เลือกทั้งหมด

            if(checkNone(branch_ids)){
                $.ajax({
                    url: "{!! url('/section5/ibcb/get-branche-tis') !!}" + "/" + branch_ids
                }).done(function( object ) {

                    if( checkNone(object) ){
                        $.each(object, function( index, data ) {
                            $(select).append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    }

                });
            }

        }
        function LoadStdType(){

            var application_type =  ($("input[name=application_type]:checked").val() == 1 )?'1':'2';
            $('#certificate_std_export').html('<option value=""> -เลือกมอก. รับรองระบบงาน- </option>');

            if( checkNone(application_type) ){
                $.ajax({
                    url: "{!! url('/section5/ibcb/get-standards') !!}" + "/" + application_type
                }).done(function( object ) {

                    if( checkNone(object) ){
                        $.each(object, function( index, data ) {
                            $('#certificate_std_export').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    }

                });
            }

        }

        function reset_name(){
            let rows = $('#box_list_scpoe').find('tr');
            let group_name = $('#box_list_scpoe').data('repeater-list');
            rows.each(function(index1, row){
                $(row).find('input, select').each(function(index2, el){
                    let old_name = $(el).data('name');
                    if(!!old_name){
                        if($(el).hasClass('input_array')){
                            $(el).attr('name', group_name+'['+index1+']['+old_name+'][]');
                        }else{
                            $(el).attr('name', group_name+'['+index1+']['+old_name+']');
                        }
                    }
                });
            });
        }

        
        //รีเซ็ตเลขลำดับ
        function resetOrderNo(){

            $('.no').each(function(index, el) {
                $(el).text(index+1);
            });

        }

        function resetInsNo2(){

            $('.ins_no').each(function(index, el) {
                $(el).text(index+1);
            });

        }


        function open_scope_branches_tis_details(link_click) {
            let scope_branches_tis = link_click.closest('td').find('input.tis_details');
            $('#scope_branches_tis_details').html('');
            let rows = '';
            scope_branches_tis.each(function(index, item){
                rows += `
                    <tr>
                        <td class="text-center">${index+1}</td>
                        <td class="">${$(item).data('tis_no')} : ${$(item).val()}</td>
                    </tr>
                `;
            });
            $('#scope_branches_tis_details').append(rows);
        }


        //โหลดรายชื่อผู้ตรวจที่สังกัดผู้ยื่นคำขอรายนี้ลงตาราง รายชื่อผู้ตรวจที่ผ่านการแต่งตั้ง ตามสาขาที่ผู้ยื่นเลือก
        function update_table_inspectors(){

            console.log(inspector_owns);

            $('#table-inspectors').find('tbody').html('');
            var branch_ids = $('#table-scope').find('[data-name="branch_id"]').map(function(){ return $(this).val(); }).get(); //ไอดีสาขาจากคำขอ

            if(branch_ids.length > 0){//มีการเพิ่มสาขาผลิตภัณฑ์ลงตารางแล้ว
                var order = 0;
                var btn = '<button class="btn btn-sm btn-danger" type="button" data-repeater-delete> <i class="fa fa-minus"></i></button>';

                $.each(inspector_owns, function(index, inspector) {//วนรอบรายชื่อผู้ตรวจที่อยู่ในสังกัดทั้งหมด
                    var inspector_scopes = Array(); //ข้อมูลสาขา
                    $.each(inspector.data_scopes, function(index, scopes) {//วนรอบสาขาผลิตภัณฑ์
                        $.each(scopes.branch, function(index, branch) {//วนรอบสาขา

                            if(branch_ids.includes(branch.branch_id)){//ถ้ามีในสาขาที่เลือกไว้
                                if(inspector_scopes[scopes.branch_group_id] === undefined){//ถ้ายังไม่มีกลุ่มสาขา
                                    inspector_scopes[scopes.branch_group_id] = {
                                                                                    group_id: scopes.branch_group_id,
                                                                                    group_title: scopes.branch_group_title,
                                                                                    branch: []
                                                                                };
                                }
                                inspector_scopes[scopes.branch_group_id].branch.push(branch);//เพิ่มสาขาเข้าไปในสาขาผลิตภัณฑ์
                            }
                        });
                    });

                    if(inspector_scopes.length > 0){//ถ้ามีสาขาผลิตภัณฑ์ตรงกับที่เลือกอย่างน้อย 1 สาขา

                        var html_branch  = '<ul class="list-unstyled">';
                        var group_id = [];
                        var inputB = '' ;
                        $.each(inspector_scopes, function(index, data) {

                            if(data===undefined){//เป็น array ที่ไม่มีข้อมูล
                                return true;
                            }
                            group_id.push(data.group_id);

                            html_branch += '<li>'+(data.group_title)+'</li>';
                            html_branch += '<li>';
                            var branch = data.branch;

                            var branch_title = [];
                            var branch_id = [];

                            $.each(branch, function(index2, ItemBranch) {
                                branch_title.push(ItemBranch.branch_title);
                                branch_id.push(ItemBranch.branch_id);
                            });

                            //id สาขา ทั้งหมดในสาขาผลิตภัณฑ์นี้ คั่นด้วย ,
                            inputB += '<input type="hidden" name="branch_id_'+(data.group_id)+'" value="'+branch_id+'">';

                            html_branch += '<ul>';
                            html_branch += '<li>'+(branch_title.join(', '))+'</li>';
                            html_branch += '</ul>';
                            html_branch += '</li>';
                        });
                        html_branch += '</ul>';

                        var input_  = '<input type="hidden" class="inspector_id" name="inspector_id" value="' + inspector.id + '">';
                            input_ += '<input type="hidden" name="inspector_taxid" value="' + inspector.inspectors_taxid + '">';
                            input_ += '<input type="hidden" name="inspector_prefix" value="' + inspector.inspectors_prefix + '">';
                            input_ += '<input type="hidden" name="inspector_first_name" value="' + inspector.inspectors_first_name + '">';
                            input_ += '<input type="hidden" name="inspector_last_name" value="' + inspector.inspectors_last_name + '">';
                            input_ += '<input type="hidden" name="inspector_type" value="1">';
                            input_ += '<input type="hidden" name="branch_group_id" value="' + group_id + '">';
                            input_ += inputB;

                        var full_name = inspector.inspectors_prefix + inspector.inspectors_first_name + ' ' + inspector.inspectors_last_name;

                        var inspector_tr  = '<tr data-repeater-item>';
                            inspector_tr += '  <td class="ins_no text-center">' + (++order) + '</td>';
                            inspector_tr += '  <td class="text-left">' + full_name + input_ + '</td>';
                            inspector_tr += '  <td class="text-left">' + inspector.inspectors_taxid + '</td>';
                            inspector_tr += '  <td class="text-left">' + html_branch + '</td>';
                            inspector_tr += '  <td class="text-left">ผู้ตรวจของหน่วยตรวจ</td>';
                            inspector_tr += '  <td class="text-center">' + btn + '</td>';
                            inspector_tr += '</tr>';

                        $('#table-inspectors').find('tbody').append(inspector_tr);
                        $('.inspectors-repeater').repeater();
                    }

                });

            }else{//ยังไม่มีการเลือกสาขาผลิตภัณฑ์

                $('#table-inspectors').find('tbody').html(tr_inspector_own);
                $('.inspectors-repeater').repeater();
            }

        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

                console.log(date_start.getDate());

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = ( '0' + date_start.getDate() ).slice( -2 );

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
        }
    </script>
@endpush

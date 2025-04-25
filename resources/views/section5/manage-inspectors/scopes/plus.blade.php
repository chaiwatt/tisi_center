
<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="PlusScopeModalLabel" aria-hidden="true" id="PlusScopeModal" >
    <div class="modal-dialog modal-dialog-centere modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title" id="PlusScopeModalLabel">เพิ่มขอบข่าย</h4>
            </div>
            <div class="modal-body">
                <div class="row form-horizontal">
                    <div class="col-md-12">

                        <form class="form-horizontal" id="form_plus_scope" onsubmit="return false">

                            {!! Form::hidden('inspectors_id', $inspector->id) !!}
                            {!! Form::hidden('_token', csrf_token()) !!}

                            @php
                                $branchgroups = App\Models\Basic\BranchGroup::where('state', 1)->pluck('title', 'id')->all()
                            @endphp

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group required">
                                        {!! Form::label('plus_agency_name', 'ชื่อหน่วยงาน', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('plus_agency_name', null,['class' => 'form-control']) !!}

                                            <div class="text-muted m-t-5">(กรอกชื่อหน่วยงาน 10 ตัวอักษรขึ้นไป หรือกรอกเลขประจำตัวผู้เสียภาษีอากรเพื่อค้นหา)</div>

                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        {!! Form::label('plus_agency_taxid', 'เลขประจำตัวผู้เสียภาษีอากร', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('plus_agency_taxid', null,['class' => 'form-control', 'required' => true, 'readonly' => true ]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        {!! Form::label('scope_start_date', 'วันที่มีผล', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                {!! Form::text('scope_start_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'scope_start_date', 'required' => true ]) !!}
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        {!! Form::label('scope_end_date', 'วันที่สิ้นสุด', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                {!! Form::text('scope_end_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'scope_end_date', 'required' => true ]) !!}
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        {!! Form::label('file_attach_scope', 'เอกสารแนบ', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_attach_scope" required>
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists delete_personfile"  data-dismiss="fileinput">ลบ</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('branch_group', 'หมวดอุตสากรรม', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('branch_group', $branchgroups , null,['class' => 'form-control branch_group', 'placeholder' => '- เลือกหมวดอุตสากรรม -', 'id' => 'branch_group']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('input_branch', 'รายสาขา', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('input_branch[]', [], null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'input_branch', 'data-placeholder'=>'- เลือกรายสาขา -']) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="padding-top: 10px;">
                                                {!! Form::checkbox('check_all_branch', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'check_all_branch','required' => false]) !!}
                                                <label for="check_all_branch">&nbsp;&nbsp; All</label>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="form-group">
                                        {!! Form::label('scope_branches_tis', 'เลขที่ มอก.', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('scope_branches_tis[]', [] , null, ['class' => 'select2-multiple scope_branches_tis', 'id' => 'scope_branches_tis',  'data-placeholder'=>'- เลือกมาตรฐาน -', 'multiple' => 'multiple', 'disabled' => false]); !!}
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="padding-top: 10px;">
                                                {!! Form::checkbox('check_all_scope_branches_tis', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'check_all_scope_branches_tis','required' => false]) !!}
                                                <label for="check_all_scope_branches_tis">&nbsp;&nbsp; All</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>                

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="text-center m-10">
                                        <button type="button" class="btn btn-success waves-effect waves-light text-center m-l-10" id="btn_add">เพิ่ม</button>
                                        <button type="button" class="btn btn-default waves-effect waves-light text-center m-l-10" id="btn_clear">ล้างข้อมูล</button>
                                    </div>

                                    <div class="table-responsive repeater-scope">
                                        <table class="table-bordered table table-hover primary-table" id="table-branch">
                                            <thead>
                                                <tr>
                                                    <th width="7%" class="text-center">รายการที่</th>
                                                    <th width="27%" class="text-center">สาขา</th>
                                                    <th class="text-center">รายสาขา</th>
                                                    <th class="text-center">มอก. เลขที่</th>
                                                    <th width="10%" class="text-center">ลบ</th>
                                                </tr>
                                            </thead>
                                            <tbody data-repeater-list="repeater-branch" id="box_list_branch">

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>


                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect" id="save_plus_scope" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>

    <script>
        var group_branchs = $.parseJSON('{!! json_encode(App\Models\Basic\Branch::select("id", "title", "branch_group_id")->where("state", 1)->get()->keyBy("id")->groupBy("branch_group_id")->toArray()) !!}');

        jQuery(document).ready(function() {

            $('#plus_agency_name').typeahead({
                minLength: 10,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-users") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    $('#plus_agency_name').val(jsondata.name_full);
                    $('#plus_agency_taxid').val(jsondata.taxid);

                }
            });

            $('#branch_group').click(function (e) {
                $('#input_branch').html('');
                $('#input_branch').val('').change();
                if(!!$(this).val()){
                    let branchs = group_branchs[$(this).val()];
                    if(!!branchs && branchs.length > 0){
                        $.each(branchs, function(index, branch){
                            $('#input_branch').append(`<option value="${branch.id}">${branch.title}</option>`);
                        });
                    }
                }
            });

            $('#check_all_branch').on('ifChecked', function (event){
                $("#input_branch > option").prop("selected", "selected");
                $('#input_branch').trigger("change");
            });

            $('#input_branch').on('change', function (e) {
                var branch_length = $(this).find('option').length;
                var branch_length_selected = $(this).find('option:selected').length;
                if(branch_length != 0){
                    if(branch_length == branch_length_selected){
                        $('#check_all_branch').iCheck('check');
                    }else{
                        $('#check_all_branch').iCheck('uncheck');
                    }
                }

                LoadBrancheTis();
            });

            //เมื่อเลือกเลขที่ มอก
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

            //Check All เลขที่ มอก
            $('#check_all_scope_branches_tis').on('ifChecked', function (event){
                $("#scope_branches_tis > option").prop("selected", "selected");
                $('#scope_branches_tis').trigger("change");
            });

            //UnCheck All เลขที่ มอก
            $('#check_all_scope_branches_tis').on('ifUnchecked', function (event){
                if($('#scope_branches_tis').find('option').length != $('#scope_branches_tis').find('option:selected').length){ //เป็น event จาก select

                }else{
                    $('#scope_branches_tis').val('').trigger("change");
                }
            });

            
            $('#btn_clear').click(function (e) {

                $('#branch_group').val('');
                $('#branch_group').trigger('change');
                $('#input_branch').val('');
                $('#input_branch').trigger('change');

            });
            
            
            $('#btn_add').click(function (e) {

                let branch_group_id = $('#branch_group').val();
                let branch = $('#input_branch').val();
                let branch_text = $('#input_branch option:selected').toArray().map(item => item.text);

                var branches_tis =  $('#scope_branches_tis').val();//มอก.

                let rows = $('#box_list_branch').children();
                let branchgroups = $.parseJSON('{!! json_encode($branchgroups,JSON_UNESCAPED_UNICODE) !!}');

                if(!!branch_group && !!branch && !!branches_tis){

                    let branch_show = '';
                    let branch_input = '';
                    let branch_old_id = '';
                    if(branch.length>0){
                        let branch_arr = [];
                        let branch_id_arr = [];
                        $.each(branch, function(index,item){

                            branch_id_arr.push(item);
                            branch_arr.push(branch_text[index]);
                            // branch_input += `<input class="branch_id" name="branch_id[]" type="hidden" value="${item}" data-name="branch_id">`;
                            // branch_old_id += `<input class="old_id" data-name="old_id" name="old_id" type="hidden" value="">`;
                        });

                        branch_input += `<input class="branch_id" name="branch_id" type="hidden" value="${branch_id_arr.join(', ')}" data-name="branch_id">`;

                        if(branch_arr.length > 0){
                            branch_show = branch_arr.join(', ');
                        }
                    }

                    //วนเก็บค่ามอก.
                    var tis_arr = [];
                    var tis_details = '';
                    var tis_no_input  = '';
                    var tis_detail_arr = [];
                    $( "#scope_branches_tis option:selected" ).each(function( index, data ) {

                        var tis_cut = $(data).text().split(':');
                        if(tis_arr.includes(tis_cut[0])===false){//ถ้าเลขมอก.ยังไม่มีใน array
                            tis_detail_arr.push( $(data).text() );
                            tis_arr.push( tis_cut[0]!='' ? tis_cut[0] : '' );
                            tis_details  += '<span data-tis_no="'+(tis_cut[0]!='' ? tis_cut[0] : '')+'" data-tis_title="'+(tis_cut[1]!='' ? tis_cut[1] : '')+'" data-branch_title="'+$(data).data('branch')+'" class="tis_details hide"></span>';
                        }
                    });
                    var tis_show = `<a class="open_scope_branches_tis_details" data-detail="${tis_detail_arr}" href="javascript:void(0)" title="คลิกดูรายละเอียด">${tis_arr.join(', ')}</a>`;
                    var tis_no_input = '<input type="hidden" name="tis_id" value="'+branches_tis+'" data-name="tis_id">';

                    var values = $('.repeater-scope').find('.branch_group_id').map(function(){return $(this).val(); }).get();

                    if(values.indexOf(String(branch_group_id)) == -1){

                        $('#box_list_branch').append(
                        `
                            <tr data-repeater-item>
                                <td class="text-center text-top branch_no">${rows.length+1}</td>
                                <td>
                                    ${branchgroups[branch_group_id]}
                                    <input class="branch_group_id" name="branch_group_id" type="hidden" value="${branch_group_id}" data-name="branch_group_id">

                                </td>
                                <td class="text-top">
                                    ${branch_show}
                                    ${branch_input}
                                    ${branch_old_id}
                                </td>
                                <td class="text-ellipsis text-top">
                                    ${tis_show}
                                    ${tis_details}
                                    ${tis_no_input}
                                </td>
                                <td class="text-center text-top">
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn_remove">X</button>
                                </td>
                            </tr>
                            `
                        );
                    }else{
                        alert('เลือกหมวดอุตสากรรมซ้ำไม่สามารถเพิ่มได้ กรุณาเลือกหมวดอุตสากรรมใหม่');
                    }

                    reset_name();
                    reset_value();
                    resetOrderNo();

                    $('.repeater-scope').repeater();
                }else{
                    alert('กรุณากรอกข้อมูลให้ครบ!!');
                }
            });

            $('#scope_start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#scope_end_date').val(expire_date);
                }else{
                    $('#scope_end_date').val('');
                }
            });

            $(document).on('click', '.btn_remove', function (e) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest('tr').remove();
                    reset_name();
                    resetOrderNo();
                }
            });

            $('#save_plus_scope').click(function (e) {

                var scope =  $('#table-branch').find(".branch_group_id").length
                if( scope >= 1 ){
                    $('#form_plus_scope').submit();
                }else{
                    alert('กรุณาเพิ่มข้อมูลสาขาผลิตภัณฑ์ ?');
                }
                  
            });

            
            $('#form_plus_scope').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#form_plus_scope")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังบันทึกข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/inspectors/plus_scope') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                       
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#PlusScopeModal').modal('hide');

                            setTimeout(function() { 
                                location.reload(); 
                            }, 1500);
                    
                        }
                    }
                });

            });

        });

        //โหลดข้อมูลมอก.ตามรายสาขา
        function LoadBrancheTis(){

            //รายสาขา
            var branch_ids = $("#input_branch").val();

            //เลขที่ มอก.
            var select = $('#scope_branches_tis');
            $(select).html('');
            $(select).val('').trigger('change');

            $('#check_all_scope_branches_tis').iCheck('uncheck');//เคลียร์เลือกทั้งหมด

            if(branch_ids!=''){
                $.ajax({
                    url: "{!! url('section5/inspectors/get-branche-tis') !!}" + "/" + branch_ids
                }).done(function( object ) {

                    if(object.length > 0){
                        $.each(object, function( index, data ) {
                            $(select).append('<option value="'+data.id+'" data-branch="'+data.branch_title+'">'+data.title+'</option>');
                        });
                    }

                });
            }

        }

        function reset_value(){
            $('#branch_group').val('').change();
            $('#input_branch').val('').change();
        }

        function resetOrderNo(){
            $('.branch_no').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function reset_name(){
            let rows = $('#box_list_branch').find('tr');
            let group_name = $('#box_list_branch').data('repeater-list');
            rows.each(function(index1, row){
                $(row).find('input, select').each(function(index2, el){
                    let old_name = $(el).data('name');
                    if($(el).hasClass('branch_id') || $(el).hasClass('old_id')){
                        $(el).attr('name', group_name+'['+index1+']['+old_name+'][]');
                    }else{
                        $(el).attr('name', group_name+'['+index1+']['+old_name+']');
                    }
                });
            });
        }

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = ( '0' + date_start.getDate() ).slice( -2 );

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2){
                return str;
            }else{
                return String(str).padStart(2, '0');
            }
        }
    </script>
@endpush
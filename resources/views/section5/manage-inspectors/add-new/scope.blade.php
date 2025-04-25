@push('css')
    <style>
        .table td.text-ellipsis {
            max-width: 177px;
        }
        .table td.text-ellipsis a {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 90%;
        }
    </style>
@endpush

@php
$branchgroups = App\Models\Basic\BranchGroup::where('state', 1)->pluck('title', 'id')->all()
@endphp
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลขอบข่ายที่ขอรับการตั้งแต่ง</h5></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required{{ $errors->has('branch_group') ? 'has-error' : ''}}">
                        {!! Form::label('branch_group', 'หมวดอุตสากรรม', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::select('branch_group', $branchgroups, null,['class' => 'form-control branch_group', 'placeholder' => '- เลือกหมวดอุตสากรรม -', 'id' => 'branch_group']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required{{ $errors->has('input_branch') ? 'has-error' : ''}}">
                        {!! Form::label('input_branch', 'รายสาขา', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::select('input_branch[]', [], null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'input_branch', 'data-placeholder'=>'- เลือกรายสาขา -']) !!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" style="padding-top: 10px;">
                                {!! Form::checkbox('check_all_branch', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'check_all_branch','required' => false]) !!}
                                <label for="check_all_branch">&nbsp;&nbsp; All</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('scope_branches_tis', 'เลขที่ มอก.'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
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

                    <div class="text-center">
                        <button type="button" class="btn btn-success waves-effect waves-light text-center" id="btn_add" style="margin-bottom: 10px; ">เพิ่ม</button>
                        <button type="button" class="btn btn-default waves-effect waves-light text-center" id="btn_clear" style="margin-bottom: 10px; ">ล้างข้อมูล</button>
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

        </fieldset>
    </div>
</div>

{{-- Modal แสดงมอก. --}}
@include ('section5/manage-inspectors/add-new/modal-scope-branches-tis-details')

@push('js')
    <script>
        var group_branchs = $.parseJSON('{!! json_encode(App\Models\Basic\Branch::select("id", "title", "branch_group_id")->where("state", 1)->get()->keyBy("id")->groupBy("branch_group_id")->toArray()) !!}');

        jQuery(document).ready(function() {

            $('.repeater-scope').repeater({
                show: function () {
                    $(this).slideDown();

                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){

                        }, 400);
                    }
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
                                <td class="text-center branch_no">${rows.length+1}</td>
                                <td>
                                    ${branchgroups[branch_group_id]}
                                    <input class="branch_group_id" name="branch_group_id" type="hidden" value="${branch_group_id}" data-name="branch_group_id">

                                </td>
                                <td>
                                    ${branch_show}
                                    ${branch_input}
                                    ${branch_old_id}
                                </td>
                                <td class="text-ellipsis">
                                    ${tis_show}
                                    ${tis_details}
                                    ${tis_no_input}
                                </td>
                                <td class="text-center">
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

            reset_name();
            resetOrderNo();

            $(document).on('click', '.btn_remove', function (e) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest('tr').remove();
                    reset_name();
                    resetOrderNo();
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
                LoadBrancheTis();
                //$('#input_branch').trigger("change");
            });

            $('#input_branch').on('change', function (e) {
                var branch_length = $(this).find('option').length;
                var branch_length_selected = $(this).find('option:selected').length;
                if(branch_length != 0){
                    if(branch_length == branch_length_selected){
                        $('#check_all_branch').iCheck('check');
                    }else{
                        $('#check_all_branch').iCheck('uncheck');
                        LoadBrancheTis();
                    }
                }else{
                    LoadBrancheTis();
                }

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
                $('#scope_branches_tis').val('').trigger("change");
            });

            //เปิด Modal แสดงรายชื่อมาตรฐาน มอก.
            $(document).on('click', '.open_scope_branches_tis_details', function(){

                $("#table_scope_branches_tis_details").DataTable().clear().destroy();

                open_scope_branches_tis_details($(this));

                $('#table_scope_branches_tis_details').DataTable({
                    searching: true,
                    autoWidth: false,
                    columnDefs: [
                        { className: "text-center col-md-1", targets: 0 },
                        { className: "col-md-8", targets: 1 },
                        { className: "col-md-3", targets: 2 }
                    ]
                });

                $('#maodal_scope_branches_tis_details').modal('show');

            });

        });

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
        function reset_value(){
            $('#branch_group').val('').change();
            $('#input_branch').val('').change();
        }

        function resetOrderNo(){
            $('.branch_no').each(function(index, el) {
                $(el).text(index+1);
            });
        }


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

        //แสดงรายชื่อมาตรฐานจาก span.tis_details
        function open_scope_branches_tis_details(link_click) {
            let scope_branches_tis = link_click.closest('td').find('span.tis_details');
            $('#scope_branches_tis_details').html('');
            let rows = '';
            scope_branches_tis.each(function(index, item){
                rows += `
                    <tr>
                        <td class="text-center">${index+1}</td>
                        <td class="">${$(item).data('tis_no')} : ${$(item).data('tis_title')}</td>
                        <td class="">${$(item).data('branch_title')}</td>
                    </tr>
                `;
            });
            $('#scope_branches_tis_details').append(rows);
        }

    </script>
@endpush

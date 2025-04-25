
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

                            {!! Form::hidden('ibcb_id', $ibcb->id) !!}
                            {!! Form::hidden('_token', csrf_token()) !!}

                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('scope_start_date', 'วันที่มีผล', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {!! Form::text('scope_start_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'scope_start_date', 'required' => true ]) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('scope_end_date', 'วันที่สิ้นสุด', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {!! Form::text('scope_end_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'scope_end_date', 'required' => true ]) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('scope_branches_group', 'สาขาผลิตภัณฑ์'.' :', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('scope_branches_group', App\Models\Basic\BranchGroup::pluck('title', 'id')->all(), null, ['class' => 'form-control', 'placeholder'=>'เลือกสาขาผลิตภัณฑ์']); !!}
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('scope_branches', 'รายสาขา'.' :', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('scope_branches[]', [] , null, ['class' => 'select2-multiple scope_branches_multiple', 'id' => 'scope_branches_multiple',  'data-placeholder'=>'เลือกรายสาขา', 'multiple' => 'multiple']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('scope_branches_tis', 'เลขที่ มอก.'.' :', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('scope_branches_tis[]', [] , null, ['class' => 'select2-multiple scope_branches_tis', 'id' => 'scope_branches_tis',  'data-placeholder'=>'เลือกมาตรฐาน', 'multiple' => 'multiple', 'disabled' => false]); !!}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group" style="padding-top: 10px;">
                                            {!! Form::checkbox('check_all_scope_branches_tis', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'check_all_scope_branches_tis','required' => false]) !!}
                                            <label for="check_all_scope_branches_tis">&nbsp;&nbsp; All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('scope_isic_no', 'ISIC NO'.' :', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-6">
                                        {!! Form::text('scope_isic_no', null, ['class' => 'form-control', 'id' => 'scope_isic_no',]); !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success show_tag_a" type="button" id="btn_branche_add"><i class="icon-plus"></i> เพิ่ม</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered scope-repeater" id="table-scope">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="1%">รายการที่</th>
                                                <th class="text-center" width="32%">สาขาผลิตภัณฑ์</th>
                                                <th class="text-center" width="32%">รายสาขา</th>
                                                <th class="text-center" width="15%">ISIC NO</th>
                                                <th class="text-center" width="15%">มาตรฐาน มอก. เลขที่</th>
                                                <th class="text-center" width="5%">ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody data-repeater-list="repeater-scope" class="text-left" id="box_list_scpoe">

                                        </tbody>
                                    </table>
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
    <script type="text/javascript">

        var list_isic_no =  $.parseJSON('{!! json_encode(App\Models\Basic\BranchGroup::whereNotNull('isic_no')->select('isic_no', 'id')->get()->pluck('isic_no', 'id')->toArray()) !!}');
        $(document).ready(function () {
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
                            tis_no = $.trim(tis_cut[0]);

                        if( tis_arr.indexOf( String(tis_no) ) == -1 ){
 
                            tis_detail_arr.push( $(data).text() );
                            tis_arr.push( checkNone(tis_no)?tis_no:'' );
                            tis_details  += '<input type="hidden" data-tis_no="'+( checkNone(tis_no)?tis_no:'' )+'" value="'+(checkNone(tis_cut[1])?tis_cut[1]:'')+'" data-branch_title="'+$(data).data('branch')+'" class="tis_details">';
                            tis_no_input  += '<input type="hidden" name="tis_id" value="'+$(data).val()+'" class="input_array" data-name="tis_id">';
                        }
                    });

                    var branches_group_txt = $( "#scope_branches_group option:selected" ).text();

                    var branch_group_id  = '<input type="hidden" class="branch_group_id" name="branch_group_id" value="'+branches_group+'" data-name="branch_group_id">';
                    var isic_no_input = '<input type="hidden" name="isic_no" value="'+isic_no+'" data-name="isic_no">';

                    var tis_show = `<a class="open_scope_branches_tis_details" data-detail="${tis_detail_arr}" href="javascript:void(0)" title="คลิกดูรายละเอียด">${tis_arr.join(', ')}</a>`;

                    var btn = '<button class="btn btn-sm btn-danger btn_scope_remove" type="button" data-repeater-delete> <i class="fa fa-minus"></i></button>';

                    var tr_ =   `
                                    <tr data-repeater-item>
                                        <td class="no_scope text-center text-top"></td>
                                        <td class="text-top text-left">
                                            ${branches_group_txt}
                                            ${branch_group_id}
                                        </td>
                                        <td class="text-top">
                                            ${branches_txt.join(', ')}
                                            ${branch_input}
                                        </td>
                                        <td class="text-top">
                                            ${( checkNone(isic_no)?isic_no:'-' )}
                                            ${isic_no_input}
                                        </td>
                                        <td class="text-top text-ellipsis">
                                            ${tis_show}
                                            ${tis_details}
                                            ${tis_no_input}
                                        </td>
                                        <td class="text-top text-center">
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

                    $('#check_all_scope_branches_tis').iCheck('uncheck');
                }

            });

            $(document).on('click', '.btn_scope_remove', function(){
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest('tr').remove();
                }
            });

            
            $('#save_plus_scope').click(function (e) {

                var scope =  $('#table-scope').find(".branch_group_id").length
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
                    url: "{{ url('/section5/ibcb/plus_scope') }}",
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

        function LoadBranche(){

            var branches_group = $("#scope_branches_group").val();

            var select = $('#scope_branches_multiple');

            $(select).html('');
            $(select).val('').trigger('change');

            if( checkNone(branches_group) ){
                $.ajax({
                    url: "{!! url('/section5/function/get-branche') !!}" + "/" + branches_group
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
                    url: "{!! url('/section5/function/get-branche-tis') !!}" + "/" + branch_ids
                }).done(function( object ) {

                    if( checkNone(object) ){
                        $.each(object, function( index, data ) {
                            $(select).append('<option value="'+data.id+'" data-branch="'+data.branch_title+'">'+data.title+'</option>');
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

            $('.no_scope').each(function(index, el) {
                $(el).text(index+1);
            });

        }


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush
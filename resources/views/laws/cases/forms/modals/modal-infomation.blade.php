@push('css')
    <style>
    </style>
@endpush

@php
    if(!empty($lawcasesform->LawRewardGroupArrayID)){
        $option_reward         = App\Models\Law\Basic\LawRewardGroup::whereIn('id', $lawcasesform->LawRewardGroupArrayID)->where('state', 1)->orWhere('title', 'ผู้แจ้งเบาะแส')->orderBy('ordering', 'ASC')->pluck('title', 'id');
    }else{
        $option_reward         = App\Models\Law\Basic\LawRewardGroup::where('state', 1)->orWhere('title', 'ผู้แจ้งเบาะแส')->orderBy('ordering', 'ASC')->pluck('title', 'id');
    }

    $sql = "(CASE 
                WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                ELSE  department.depart_nameShort
                END) AS sub_depart_shortname";

    $option_sub_department = App\Models\Basic\SubDepartment::leftjoin((new App\Models\Besurv\Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                                            ->select( DB::raw($sql), 'sub_id' )
                                                            ->orderbyRaw('CONVERT(sub_depart_shortname USING tis620)')
                                                            ->pluck('sub_depart_shortname', 'sub_id');
                                                     
    $option_law_department = App\Models\Law\Basic\LawDepartment::where('type', 2)->where('state',1)->orderbyRaw('CONVERT(title_short USING tis620)')->pluck('title_short', 'id');
    $option_ac_bank        = App\Models\Accounting\Bank::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');

@endphp

<div id="AddFormStaff" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > 
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ผู้มีส่วนร่วมในคดี</h4>
            </div>
            <div class="modal-body">

                <form  enctype="multipart/form-data" class="form-horizontal" id="from_staff" onsubmit="return false">

                    <input type="hidden"  id="m_id" value="">  
                    <input type="hidden"  id="m_keys" value="">  

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_bs_reward_group_id', 'ส่วนร่วมในคดี', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                    {!! Form::select('m_bs_reward_group_id', $option_reward  , null, ['class' => 'form-control ', 'placeholder'=>'- เลือกส่วนร่วมในคดี -','required' => true ])  !!}
                                </div>
                            </div> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_departmen_type', 'หน่วยงาน', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                    {!! Form::select('m_departmen_type', ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'], 1, ['class' => 'form-control ', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_departmen', 'ชื่อหน่วยงาน/กอง/กลุ่ม', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">

                                    <!-- กรณีภายใน -->
                                    <div class="m_sub_department_id">
                                        {!! Form::select('m_sub_department_id',  $option_sub_department,  null, ['class' => 'form-control ',  'placeholder'=>'- เลือกกอง/กลุ่ม (กรณีภายใน) -', 'required' => false, 'id' => 'm_sub_department_id']) !!}
                                    </div>

                                    <!-- กรณีภายนอก -->
                                    <div class="m_basic_department_id">
                                        {!! Form::select('m_basic_department_id',  $option_law_department ,  null, ['class' => 'form-control ',  'placeholder'=>'- เลือกหน่วยงาน (กรณีภายนอก) -', 'required' => false, 'id' => 'm_basic_department_id']) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_taxid', 'เลขประจำตัวประชาชน', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                    {{-- check_format_en_and_number --}}
                                    <div class=" input-group ">
                                        {!! Form::text('m_taxid',null, ['class' => 'form-control ',  'required' => true ,  'placeholder' => 'ค้นจาก เลขประจำตัวประชาชน/ชื่อ-สกุล']) !!}
                                        <span class="input-group-addon bg-info b-0 text-white" id="search_taxid"> ค้นหา </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_name', 'ชื่อ-สกุล', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-9">
                                    {!! Form::text('m_name',null, ['class' => 'form-control ', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_address', 'ที่อยู่', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('m_address', null , ['class' => 'form-control', 'rows'=>'3', 'required' => true ]); !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_mobile', 'เบอร์มือถือ', ['class' => 'col-md-6 control-label'])) !!}
                                <div class="col-md-6">
                                      {!! Form::text('m_mobile',null, ['class' => 'form-control','required' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_email', 'อีเมล', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-9">
                                    {!! Form::email('m_email',null, ['class' => 'form-control','required' => true]) !!}
                                    <p class="font-medium-6 text-muted">ระบบจะส่งอีเมลแจ้งเตือนข้อมูลใบสำคัญรับเงินกรุณาตรวจสอบข้อมูลให้ความถูกต้อง</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row div_basic_bank">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_basic_bank_id', 'ชื่อธนาคาร', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                    {!! Form::select('m_basic_bank_id', $option_ac_bank, null, ['class' => 'form-control ',  'placeholder'=>'- เลือกชื่อธนาคาร -','required' => true ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row div_basic_bank">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_bank_account_name', 'ชื่อบัญชี', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                      {!! Form::text('m_bank_account_name',null, ['class' => 'form-control ', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row div_basic_bank">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('m_bank_account_number', 'เลขที่บัญชี', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                      {!! Form::text('m_bank_account_number',null, ['class' => 'form-control check_format_en_and_number','required' => true]) !!}
                                </div>
                            </div>        
                        </div>
                    </div>

                    <div class="row div_basic_bank">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! HTML::decode(Form::label('m_attach', 'ไฟล์สมุดบัญชี'.'<br><small class="text-muted m-b-30 font-12"><i>(.jpg, .png ขนาดไม่เกิน 5 MB)</i></small>', ['class' => 'col-md-3 control-label'])) !!}
                   
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput" >
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="m_attach" id="m_attach" accept=".jpg,.png,.pdf" class="check_max_size_file">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists delete-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                    <p class="font-medium-6 text-muted"> หมายเหตุ : เป็นข้อมูลที่ใช้สำหรับคำนวณเงินสินบน/เงินรางวัล และออกใบสำคัญรับเงิน กรุณาตรวจสอบข้อมูลให้ครบถ้วนและถูกต้อง </p>
                                </div>
                                <div class="col-md-2 div_m_attach"></div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-primary ml-1" id="addRowStaff"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">บันทึก</span></button>
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ยกเลิก</span></button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('js')

    <script>
        jQuery(document).ready(function() {


            $('#m_taxid').typeahead({
                minLength: 2,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-user-law-registers") }}', { query: query,departmen_type: $('#m_departmen_type').val() }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {
                     if(checkNone(jsondata.basic_reward_group_id)){
                          $('#m_bs_reward_group_id').val(jsondata.basic_reward_group_id).trigger('change.select2');     
                    }
                    $('#m_taxid').val(jsondata.taxid); 

                    $('#m_name').val(jsondata.full_name);  
                    $('#m_email').val(jsondata.email);  
                    $('#m_mobile').val(jsondata.phone);  

                    if(checkNone(jsondata.sub_depart)){
                        $('#m_departmen_type').val('1').trigger('change.select2');
                        depart_type();
                        $('#m_sub_department_id').val( jsondata.sub_depart ).trigger('change.select2');
                    }else if(checkNone(jsondata.basic_sub_depart)){
                        $('#m_departmen_type').val('2').trigger('change.select2');
                        depart_type();
                        $('#m_basic_department_id').val( jsondata.basic_sub_depart ).trigger('change.select2');
                    }
                 

                    if(checkNone(jsondata.address)){
                        $('#m_address').val(jsondata.address);      
                    }else{
                        $('#m_address').val('');   
                    }

                    if(checkNone(jsondata.basic_bank_id)){
                        $('#m_basic_bank_id').val(jsondata.basic_bank_id).trigger('change.select2');     
                    }

                    if(checkNone(jsondata.bank_account_name)){
                        $('#m_bank_account_name').val(jsondata.bank_account_name);     
                    }

                    if(checkNone(jsondata.bank_account_number)){
                        $('#m_bank_account_number').val(jsondata.bank_account_number);     
                    }
                }
            });



            $(document).on('click', '.delte_staff_file', function(e) {

                if( confirm("ต้องการลบไฟล์หรือไม่ ?") ){

                    let url      = $(this).data( "url" );
                    $.ajax({
                        url: url
                    }).done(function( object ) {

                        if( object =='success'){
                            toastr.success('ลบสำเร็จ !');
                            $("#from_staff").find('div.div_m_attach').html('');
                        }else{
                            toastr.error('ผิดพลาด !');

                        }

                    }); 
                    

                }

            });

            $(document).on('click', '.staf_delete', function(e) {

                if( confirm("ต้องการลบแถวนี้หรือไม่ ?") ){

                    table_staff
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();

                    //$(this).closest( "tr" ).remove();
                    OrderStaffNo();
                    // table_staff = $('#myTable-staff').DataTable({
                    //     serverSide: false,
                    //     processing: false,
                    //     columnDefs: [
                    //         { className: "text-center text-top", targets:[0, -1] }
                    //     ],
                    //     order: [[0, 'asc']]
                    // });

                    $('.staff-repeater').repeater();
                }

            });

            $(document).on('click', '.staf_edit', function(e) {

                let row      = $(this).closest( "tr" );
                let key      = row.data('row');
                let name_arr = row.find('input').attr('name').split('][');
                let name_set = name_arr[0]+"]";

                var id                  = row.find('input[name*="'+name_set+'[id]"]').val();
                var bs_reward_group_id  = row.find('input[name*="'+name_set+'[basic_reward_group_id]"]').val();
                var depart_type         = row.find('input[name*="'+name_set+'[depart_type]"]').val();
                var sub_department_id   = row.find('input[name*="'+name_set+'[sub_department_id]"]').val();
                var basic_department_id = row.find('input[name*="'+name_set+'[basic_department_id]"]').val();
                var name                = row.find('input[name*="'+name_set+'[name]"]').val();
                var taxid               = row.find('input[name*="'+name_set+'[taxid]"]').val();
                var address             = row.find('input[name*="'+name_set+'[address]"]').val();
                var mobile              = row.find('input[name*="'+name_set+'[mobile]"]').val();
                var email               = row.find('input[name*="'+name_set+'[email]"]').val();

                var basic_bank_id       = row.find('input[name*="'+name_set+'[basic_bank_id]"]').val();
                var bank_account_name   = row.find('input[name*="'+name_set+'[bank_account_name]"]').val();
                var bank_account_number = row.find('input[name*="'+name_set+'[bank_account_number]"]').val();
                var file                = row.find('a.attach').attr('href');

                var formStaff = $("#from_staff");

                    formStaff.find('#m_keys').val(key);
                    formStaff.find('#m_id').val(id);

                    formStaff.find('#m_bs_reward_group_id').val(bs_reward_group_id).trigger('change.select2');
                    formStaff.find('#m_departmen_type').val(depart_type).trigger('change.select2');
             
                    setTimeout(function(){
                        formStaff.find('#m_departmen_type').change();
                        formStaff.find('#m_sub_department_id').val(sub_department_id).trigger('change.select2');
                        formStaff.find('#m_basic_department_id').val(basic_department_id).trigger('change.select2');
                    }, 500);

                    formStaff.find('#m_taxid').val(taxid);
                    formStaff.find('#m_name').val(name);
                    formStaff.find('#m_address').val(address);
                    formStaff.find('#m_mobile').val(mobile);
                    formStaff.find('#m_email').val(email);

                    formStaff.find('#m_basic_bank_id').val(basic_bank_id).trigger('change.select2');
                    formStaff.find('#m_bank_account_name').val(bank_account_name);
                    formStaff.find('#m_bank_account_number').val(bank_account_number);

                if( checkNone(file) ){

                    var id_file = row.find('a.attach').attr('data-id');
                    var url    = '{!! url("/law/attach/delete") !!}';
                    if( checkNone(id_file) ){
                        url += '?id='+id_file;
                    }else{
                        var path = row.find('input[name*="'+name_set+'[file]"]').val();
                        url += '?path='+path;
                    }

                    var link =  '<a href="'+( file )+'" class="attach_show" target="_blank">ไฟล์สมุดบัญชี</a>';
                        link += '<a href="javascript:void(0)" class="m-l-5 delte_staff_file" data-url="'+( url  )+'"><i class="pointer fa fa-remove text-danger icon-remove" style="font-size: 1.5em;"></i></a>';
                    formStaff.find('div.div_m_attach').html(link );
                }else{
                    formStaff.find('div.div_m_attach').html('');
                }

                $('#AddFormStaff').modal('show');
  
                
            });

            //เปิด modal
            $('#BtnAddFormStaff').click(function (e) { 
                $('#from_staff').find('ul.parsley-errors-list').remove();
                $('#from_staff').find('input,textarea').val('');
                $('#from_staff').find('select').val('').trigger('change.select2');
                $('#from_staff').find('.fileinput').fileinput('clear');
                $('#from_staff').find('#m_departmen_type').val('1').trigger('change.select2');
                $('#from_staff').find('div.div_m_attach').html('');
                $('#from_staff').find('input,textarea').removeClass('parsley-success');
                $('#from_staff').find('input,textarea').removeClass('parsley-error');
                depart_type();
                $('#AddFormStaff').modal('show'); 
            });

            $('#addRowStaff').click(function(event) {
                $('#from_staff').submit();
            });

            $('#m_departmen_type').change(function(event) {
                depart_type();
            });
            $('#m_departmen_type').change();

            $(".check_format_en_and_number").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }  
            });

            //ค้นหาจากเลขบัตร
            $('#search_taxid').click(function(event) {
                var  taxid =  $('#m_taxid').val();
                if(checkNone(taxid)){
                    // taxid = taxid.toString().replace(/\D/g,'');
                    // if(taxid.length >= 13){
                        $.LoadingOverlay("show", {
                            image       : "",
                            text  : "กำลังค้นหา กรุณารอสักครู่..."
                        });

                        $.ajax({
                            url: '{!! url("funtions/search-user-law-registers")  !!}',
                            method: "get",
                            data: {query: taxid,  departmen_type: $('#m_departmen_type').val() , _token: '{!! csrf_token() !!}'}
                        }).done(function (msg) {
                            $.LoadingOverlay("hide");
                            if( checkNone(msg[0]) ){
                                var data =  msg[0];
                         

                                if(checkNone(data.basic_reward_group_id)){
                                    $('#m_bs_reward_group_id').val(data.basic_reward_group_id).trigger('change.select2');     
                                }
                                $('#m_taxid').val(data.taxid); 
                                $('#m_name').val(data.full_name);  
                                $('#m_email').val(data.email);  
                                $('#m_mobile').val(data.phone);  

                                if(checkNone(data.sub_depart)){
                                    $('#m_departmen_type').val('1').trigger('change.select2');
                                    depart_type();
                                    $('#m_sub_department_id').val( data.sub_depart ).trigger('change.select2');
                                }else if(checkNone(data.basic_sub_depart)){
                                    $('#m_departmen_type').val('2').trigger('change.select2');
                                    depart_type();
                                    $('#m_basic_department_id').val( data.basic_sub_depart ).trigger('change.select2');
                                }
                 
                                
                                if(checkNone(data.address)){
                                    $('#m_address').val(data.address);      
                                }else{
                                    $('#m_address').val('');   
                                }

                                if(checkNone(data.basic_bank_id)){
                                    $('#m_basic_bank_id').val(data.basic_bank_id).trigger('change.select2');     
                                }

                                if(checkNone(data.bank_account_name)){
                                    $('#m_bank_account_name').val(data.bank_account_name);     
                                }

                                if(checkNone(data.bank_account_number)){
                                    $('#m_bank_account_number').val(data.bank_account_number);     
                                }
                                // $('#m_name').val(data.full_name);  
                                // $('#m_email').val(data.email);  
                                // $('#m_mobile').val(data.phone);  
                                // if(checkNone(data.sub_depart)){
                                //     $('#m_departmen_type').val('1').trigger('change.select2');
                                //     $('#m_sub_department_id').val( data.sub_depart ).trigger('change.select2');
                                // }

                                // if($('#m_departmen_type').val() == '1'){
                                //     $('#m_address').val('อยู่บ้านเลขที่ 75/42 ถนนพระราม 6 แขวงทุ่งพญาไท เขตราชเทวี จังหวัดกรุงเทพมหานคร');      
                                // }else{
                                //     $('#m_address').val('');   
                                // }
                            }
                        });

                    // }else{
                    //     Swal.fire({
                    //         position: 'center',
                    //         icon: 'warning',
                    //         title: 'กรุณากรอกเลขประจำตัวประชาชนให้ครบ 13 หลัก',
                    //         width: 600,
                    //         showConfirmButton: true
                    //     });
                    // }
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณากรอกเลขประจำตัวประชาชน',
                        width: 600,
                        showConfirmButton: true
                    });
                }

            });

            //บันทึกฟอร์ม
            $('#from_staff').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                table_staff = $('#myTable-staff').DataTable().destroy();
                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังบันทึก กรุณารอสักครู่..."
                });

                var formData = new FormData($("#from_staff")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                var attach = $('#m_attach').prop('files')[0];
                if(checkNone(attach)){
                    formData.append('attachment', $('#m_attach')[0].files[0]);
                } 
                
                var formStaff            = $("#from_staff");
                var input                = '';

                var keys                 = formStaff.find('#m_keys').val();
                var id                   = formStaff.find('#m_id').val();
                    input                += '<input type="hidden" name="id" value="'+(id)+'">';

                //ส่วนร่วมในคดี
                var reward_group         = formStaff.find('#m_bs_reward_group_id').val();
                var reward_group_txt     = formStaff.find('#m_bs_reward_group_id option:selected').text();
                    input                += '<input type="hidden" name="basic_reward_group_id" class="basic_reward_group_id" value="'+(reward_group)+'">';

                //หน่วยงาน
                var type_val             = formStaff.find('#m_departmen_type').val();
                var type                 = formStaff.find('#m_departmen_type option:selected').text();
                    input                += '<input type="hidden" name="depart_type" value="'+(type_val)+'">';

                //ชื่อหน่วยงาน/กอง/กลุ่ม
                var department           = '';
                var sub_department_id    = '';
                var basic_department_id  = '';

                if( checkNone(type_val) && type_val == 1 ){
                    department           = formStaff.find('#m_sub_department_id option:selected').text();
                    sub_department_id    = formStaff.find('#m_sub_department_id').val();
                }else if(checkNone(type_val) && type_val == 2){
                    department           = formStaff.find('#m_basic_department_id option:selected').text();
                    basic_department_id  = formStaff.find('#m_basic_department_id').val();
                }
                input                    += '<input type="hidden" name="sub_department_id" value="'+(sub_department_id)+'">';
                input                    += '<input type="hidden" name="basic_department_id" value="'+(basic_department_id)+'">';
                input                    += '<input type="hidden" name="department_name" value="'+(department)+'">';

                //ชื่อ-สกุล
                var m_name               = formStaff.find('#m_name').val();
                    input                += '<input type="hidden" name="name" value="'+(m_name)+'">';

                //เลขประจำตัวประชาชน
                var m_taxid              = formStaff.find('#m_taxid').val();
                    input                += '<input type="hidden" name="taxid" class="staff_taxid" value="'+(m_taxid)+'">';

                //ที่อยู่
                var m_address            = formStaff.find('#m_address').val();
                    input                += '<input type="hidden" name="address" value="'+(m_address)+'">';

                //เบอร์มือถือ
                var m_mobile             = formStaff.find('#m_mobile').val();
                    input                += '<input type="hidden" name="mobile" value="'+(m_mobile)+'">';

                //อีเมล
                var m_email              = formStaff.find('#m_email').val();
                    input                += '<input type="hidden" name="email" value="'+(m_email)+'">';

                //ชื่อธนาคาร
                var bank_id              = formStaff.find('#m_basic_bank_id').val();
                if( checkNone(bank_id) ){
                    var bank_txt         = formStaff.find('#m_basic_bank_id option:selected').text();
                }else{
                    var bank_txt         = '';
                }
                    input                += '<input type="hidden" name="basic_bank_id" value="'+(bank_id)+'">';

                //ชื่อบัญชี
                if( checkNone(formStaff.find('#m_bank_account_name').val()) ){
                    var bank_name            = formStaff.find('#m_bank_account_name').val();
                }else{
                    var bank_name         = '';
                }
                    input                += '<input type="hidden" name="bank_account_name" value="'+(bank_name)+'">';

                //เลขที่บัญชี
                if( checkNone(formStaff.find('#m_bank_account_number').val()) ){
                    var bank_number            = '('+formStaff.find('#m_bank_account_number').val()+')';
                }else{
                    var bank_number         = '';
                }
           
                    input                += '<input type="hidden" name="bank_account_number" value="'+(bank_number)+'">';

                    
                var btn                  =  '<a href="javascript: void(0)" class="staf_edit m-r-5"><i class="pointer fa fa-pencil text-primary icon-pencil" style="font-size: 1.5em;"></i></a>';
                    btn                  += '<a href="javascript: void(0)" class="staf_delete"><i class="pointer fa fa-remove text-danger icon-remove" style="font-size: 1.5em;"></i></a>';

                var label_mobile = '';
                if( checkNone(m_mobile) ){
                    label_mobile = '<div><i class="icon-phone"></i>'+(m_mobile)+'</div>'
                }

                var label_email = '';
                if( checkNone(m_email) ){
                    label_email = '<div><i class="icon-envelope-open"></i>'+(m_email)+'</div>';
                }


                // start ส่งเรื่องถึงผู้มีอำนาจพิจารณา (ขออนุมัติผ่านระบบ)
                var defaults =  [ '6', '5','2','1' ];
                if(  checkNone(type_val) && type_val == 1 && checkNone(reward_group) && defaults.includes(reward_group)   && checkNone(sub_department_id)){
                     var tbody = $('#table_tbody_approve').children();
                     var approve_tr = tbody.find('select.role_approve option[value="'+reward_group+'"]:selected').closest('tr');

                      //  ส่งเรื่องถึงกอง
    
                      var send_department  = $(approve_tr).find('select.send_department');
                      var authorize_userid = $(approve_tr).find('select.authorize_userid_approve');
                      var position         = $(approve_tr).find('input.position_approve');

                        var userids = [];
                        if(tbody.length > 0){
                            tbody.each(function(index, el) {
                                var userid = $(el).find('select.authorize_userid_approve').val();
                                if(checkNone(userid)){
                                    userids.push(userid);

                                }
                            });    
                        }
                    
                      $.ajax({
                        url:"{{ url('/law/cases/forms/get_user_departments') }}" ,
                        data: { sub_id:sub_department_id, role:reward_group },
                       type: 'GET',
                      }).done(function(  object ) {  
                            
                            // start ส่งเรื่องถึงกอง
                            if(checkNone(object.did)){
                                send_department.val(object.did).trigger('change.select2');
                             }else{
                                send_department.val('').trigger('change.select2');
                             }   
                             // end ส่งเรื่องถึงกอง
                             // start ผู้มีอำนาจพิจารณา
                            var users = object.users   ;
                             var runrecno = '';
                             authorize_userid.html('<option value=""> - เลือกผู้มีอำนาจพิจารณา - </option>');
                             if(users.length == 1){//ถ้ามึคนเดียวหรือ role ตรง
                                    $.each(users, function( index, data ) {
                                        if($.inArray( String(data.runrecno), userids) == -1) {//ห้ามเลือกซ้ำ

                                            authorize_userid.append('<option value="'+data.runrecno+'">'+data.name+'</option>');
                                            runrecno         = data.runrecno;
                                        }  
                                    });
                                    reBuiltSelect2(authorize_userid);

                                if(checkNone(runrecno)){
                                        authorize_userid.val(runrecno).trigger('change.select2');
                                    $.ajax({
                                        url: "{!! url('law/cases/forms/user_register/') !!}" + "/" + runrecno
                                    }).done(function( jsondata ) {
                                           position.val('');
                                        if(jsondata.position){
                                            position.val(jsondata.position);
                                        }
                                    });
                                }

                            }else{
                                    $.each(users, function( index, data ) {
                                        if(jQuery.inArray(String(data.runrecno), userids ) == -1) {
                                            authorize_userid.append('<option value="'+data.runrecno+'">'+data.name+'</option>');
                                        }
                                    });
                                    reBuiltSelect2(authorize_userid);
                            }
                          // end ผู้มีอำนาจพิจารณา
                       
                    });

  
                }

                // end  ส่งเรื่องถึงผู้มีอำนาจพิจารณา (ขออนุมัติผ่านระบบ) 
                    
                if( checkNone(attach) ){
                    //บันทึกไฟล์ Temp                 
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/law/funtion/upload-file-temp') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {

                            if(checkNone( msg ) ){

                                var link                 = '<a href="'+(msg.url)+'" class="attach" target="_blank">ไฟล์สมุดบัญชี</a>';
                                    input                += '<input type="hidden" name="file" value="'+(msg.path)+'">';

                                var tr                   = '';
                                    tr                   += '<tr data-repeater-item class="staff_tr">';
                                    tr                   += '<td class="text-top text-center staff_list_no"></td>';
                                    tr                   += '<td class="text-top text-center">'+m_name+'<div>('+m_taxid+')</div></td>';
                                    tr                   += '<td class="text-top">'+m_address+''+label_mobile+''+label_email+'</td>';
                                    tr                   += '<td class="text-top text-center">'+department+'<div>('+type+')</div></td>';
                                    tr                   += '<td class="text-top text-center"><span class="reward_group">'+reward_group_txt+'</span></td>';
                                    tr                   += '<td class="text-top text-center">'+bank_txt+'<div>'+bank_name+'</div><div>'+bank_number+'</div>'+link+'</td>';
                                    tr                   += '<td class="text-top text-center">'+btn+' '+input+'</td>';
                                    tr                   += '</tr>';

                                if( checkNone(keys) ){
                                
                                    $("tr[data-row='" + keys + "']").before(tr); 
                                    $("tr[data-row='" + keys + "']").remove();

                                }else{

                                    // var values =  $('.staff-repeater').find(".staff_taxid").map(function(){return $(this).val(); }).get();
                                    // if( values.indexOf( String(m_taxid) ) == -1 ){
                                        $('#tbd_staff').append(tr);
                                    // }
                                    
                                }

                                OrderStaffNo();

                                table_staff = $('#myTable-staff').DataTable({
                                    serverSide: false,
                                    processing: false,
                                    columnDefs: [
                                        { className: "text-center text-top", targets:[0, -1] }
                                    ],
                                    order: [[0, 'asc']]
                                });

                                $('.staff-repeater').repeater();

                                $.LoadingOverlay("hide");

                                $('#AddFormStaff').modal('hide'); 
    
                            }
                
                        }
                    });
                }else{

                    var link                 = '';
                    if( checkNone(formStaff.find('a.attach_show').attr('href')) ){
                        link                 = '<a href="'+( formStaff.find('a.attach_show').attr('href') )+'" class="attach" target="_blank">ไฟล์สมุดบัญชี</a>';
                    }

                    var tr                   = '';
                        tr                   += '<tr data-repeater-item class="staff_tr">';
                        tr                   += '<td class="text-top text-center staff_list_no"></td>';
                        tr                   += '<td class="text-top text-center">'+m_name+'<div>('+m_taxid+')</div></td>';
                        tr                   += '<td class="text-top">'+m_address+''+label_mobile+''+label_email+'</td>';
                        tr                   += '<td class="text-top text-center">'+department+'<div>('+type+')</div></td>';
                        tr                   += '<td class="text-top text-center">'+reward_group_txt+'</td>';
                        tr                   += '<td class="text-top text-center">'+bank_txt+'<div>'+bank_name+'</div><div>'+bank_number+'</div>'+link+'</td>';
                        tr                   += '<td class="text-top text-center">'+btn+' '+input+'</td>';
                        tr                   += '</tr>';

                    if( checkNone(keys) ){
                    
                        $("tr[data-row='" + keys + "']").before(tr); 
                        $("tr[data-row='" + keys + "']").remove();

                    }else{
                        // var values =  $('.staff-repeater').find(".staff_taxid").map(function(){return $(this).val(); }).get();
                        // if( values.indexOf( String(m_taxid) ) == -1 ){
                            $('#tbd_staff').append(tr);
                        // }
                    }

                    OrderStaffNo();

                    table_staff = $('#myTable-staff').DataTable({
                        serverSide: false,
                        processing: false,
                        columnDefs: [
                            { className: "text-center text-top", targets:[0, -1] }
                        ],
                        order: [[0, 'asc']]
                    });

                    $('.staff-repeater').repeater();

                    $.LoadingOverlay("hide");

                    $('#AddFormStaff').modal('hide'); 
                }



            });

            OrderStaffNo();
        });

        function depart_type() {

            let type = $('#m_departmen_type').val();

            //กอง/กลุ่ม (กรณีภายใน)
            $('.m_sub_department_id').hide();
            $('#m_sub_department_id').val('').trigger('change.select2');
            $('#m_sub_department_id').prop('required' ,false);     


            //หน่วยงาน (กรณีภายนอก)
            $('.m_basic_department_id').hide();
            $('#m_basic_department_id').val('').trigger('change.select2');
            $('#m_basic_department_id').prop('required' ,false );     

            if( type == '1'){
                //เลือกกอง/กลุ่ม (กรณีภายใน)
                $('.m_sub_department_id').show();
                $('#m_sub_department_id').prop('required' ,true);     
                $('#m_basic_bank_id, #m_bank_account_name, #m_bank_account_number').prop('required' ,false);    
                $('.div_basic_bank').hide();   
            }else if( type == '2'){
                //เลือกหน่วยงาน (กรณีภายนอก)
                $('.m_basic_department_id').show();
                $('#m_basic_department_id').prop('required' ,true);  
                $('#m_basic_bank_id, #m_bank_account_name, #m_bank_account_number').prop('required' ,true);    
                $('.div_basic_bank').show();   
                 
            }
        }

        function OrderStaffNo(){

            $('#myTable-staff tbody').find('.staff_list_no').each(function(index, el) {
                var uniqid = Math.floor(Math.random() * 1000000);
                $(el).closest( "tr" ).attr('data-row', uniqid);
                $(el).text(index+1);
            });

        }
    </script>

@endpush

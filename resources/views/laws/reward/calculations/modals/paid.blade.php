 
  <form id="form_paid" class="form-horizontal"  method="post" >

        @php
            if(!empty($cases->LawRewardGroupArrayID)){
                $option_reward         = App\Models\Law\Basic\LawRewardGroup::whereIn('id',$cases->LawRewardGroupArrayID)->where('state',1)->orderBy('ordering', 'ASC')->pluck('title', 'id');
            }else{
                $option_reward         = App\Models\Law\Basic\LawRewardGroup::where('state',1)->orderBy('ordering', 'ASC')->pluck('title', 'id');
            }
        @endphp
                {{ csrf_field() }}

<div class="modal fade" id="PaidModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="AssignModalLabel1">ชื่อผู้มีสิทธิ์ได้รับเงิน</h4>
            </div>
            <div class="modal-body"> 
                
                         <input type="hidden"  id="modal_id"  >  
                         <input type="hidden"  id="attach_ids"  >  
                         <input type="hidden"  id="modal_keys"  >  
                    <div class="form-group">
                        {!! HTML::decode(Form::label('law_reward_group_to', 'ส่วนร่วมในคดี'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::select('basic_reward_group_id',
                                           $option_reward,
                                            null,
                                            ['class' => 'form-control ', 
                                            'placeholder'=>'- เลือกส่วนร่วมในคดี -',
                                            'required' => true,
                                            'id' => 'basic_reward_group_id']) 
                            !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! HTML::decode(Form::label('end_date', 'หน่วยงาน'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                            {!! Form::select('depart_type', ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'], '1', ['class' => 'form-control ' , 'required' => true, 'id' => 'depart_type']) !!}
                            {!! $errors->first('depart_type', '<p class="help-block">:message</p>') !!}
                        </div>
                        {!! HTML::decode(Form::label('end_date', 'ชื่อหน่วยงาน/กอง/กลุ่ม'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label departmen font-medium-6 text-right'])) !!}
                        <div class="col-md-3 departmen">
                                    {{-- กรณีภายใน --}}
                                    <span id="span_sub_department_id">
                                        {!! Form::select('sub_department_id',
                                            App\Models\Basic\SubDepartment::orderbyRaw('CONVERT(sub_depart_shortname USING tis620)')->pluck('sub_depart_shortname', 'sub_id'),
                                            null,
                                            ['class' => 'form-control ', 
                                            'placeholder'=>'- เลือกกอง/กลุ่ม (กรณีภายใน) -',
                                            'required' => false,
                                        'id' => 'sub_department_id']) 
                                        !!}
                                    </span>
                                    {{-- กรณีภายนอก --}}
                                    <span id="span_basic_department_id">
                                        {!! Form::select('basic_department_id',
                                            App\Models\Law\Basic\LawDepartment::where('type', 2)->where('state',1)->orderbyRaw('CONVERT(title_short USING tis620)')->pluck('title_short', 'id'),
                                            null,
                                            ['class' => 'form-control ', 
                                            'placeholder'=>'- เลือกหน่วยงาน (กรณีภายนอก) -',
                                            'required' => false,
                                            'id' => 'basic_department_id']) 
                                        !!}
                                    </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! HTML::decode(Form::label('taxid', 'เลขประจำตัวประชาชน'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4">
                            {{-- check_format_en_and_number --}}
                               <div class=" input-group ">
                                    {!! Form::text('taxid',null, ['class' => 'form-control ', 'id'=>'taxid',  'required' => true ,   'placeholder' => 'ค้นจาก เลขประจำตัวประชาชน/ชื่อ-สกุล']) !!}
                                    <span class="input-group-addon bg-info b-0 text-white" id="search_taxid"> ค้นหา </span>
                               </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! HTML::decode(Form::label('name', 'ชื่อ-สกุล'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-9">
                            {!! Form::text('name',null, ['class' => 'form-control ',  'id'=>'name', 'required' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('address', 'ที่อยู่'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('address', null , ['class' => 'form-control', 'rows'=>'3' , "id"=>"address", 'required' => true ]); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('mobile', 'เบอร์มือถือ'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::text('mobile',null, ['class' => 'form-control ', "id"=>"mobile", 'required' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('email', 'อีเมล'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::email('email',null, ['class' => 'form-control ', "id"=>"email", 'required' => true]) !!}
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-5"> <p  class="font-medium-6 text-muted">ระบบจะส่งอีเมลแจ้งเตือนข้อมูลใบสำคัญรับเงินกรุณาตรวจสอบข้อมูลให้ความถูกต้อง</p>   </div>
                    </div>
 

                    <div class="form-group div_basic_bank">
                        {!! HTML::decode(Form::label('basic_bank_id', 'ชื่อธนาคาร'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::select('basic_bank_id',
                                         App\Models\Accounting\Bank::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                    null,
                                    ['class' => 'form-control ', 
                                    'placeholder'=>'- เลือกชื่อธนาคาร -',
                                    'required' => true,
                                    'id' => 'basic_bank_id']) 
                              !!}
                        </div>
                    </div>

                    <div class="form-group div_basic_bank">
                        {!! HTML::decode(Form::label('bank_account_name', 'ชื่อบัญชี'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::text('bank_account_name',null, ['class' => 'form-control ', "id"=>"bank_account_name", 'required' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group div_basic_bank">
                        {!! HTML::decode(Form::label('bank_account_number', 'เลขที่บัญชี'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-3">
                              {!! Form::text('bank_account_number',null, ['class' => 'form-control check_format_en_and_number', "id"=>"bank_account_number", 'required' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group div_basic_bank">
                        {!! HTML::decode(Form::label('end_date', 'ไฟล์สมุดบัญชี', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput" >
                                <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="attach_modal" id="attach_modal"   accept=".jpg,.png,.pdf" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists delete-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            <p class="font-medium-6 text-muted"> หมายเหตุ : เป็นข้อมูลสำหรับแสดงในใบสำคัญรับเงิน</p>
                        </div>
                        <div class="col-md-2" id="div_attach_modal"></div>
                    </div>
                  


                    <div class="text-center">
                        <button type="button"class="btn btn-primary"  id="form_paid_save"><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>

             
            </div>
        </div>
    </div>
</div>
</form>
@push('js')
    
    <script>
        $(document).ready(function () {

            $("body").on("click", "#ButtonModal", function() {
                $('#modal_keys').val('');
                $('#form_paid').find('ul.parsley-errors-list').remove();
                $('#form_paid').find('input, select, textarea').val('');
                $('#form_paid').find('select').select2();
                $('#form_paid').find('input,textarea').removeClass('parsley-success');
                $('#form_paid').find('input,textarea').removeClass('parsley-error');
                $('#form_paid').find('#div_attach_modal').html('');
                $('#form_paid').find('.delete-exists').click();
                $('#depart_type').val('1');
                $('#depart_type').select2();
                depart_type();
                $('#PaidModals').modal('show'); 
            });
            
            $('#taxid').typeahead({
                minLength: 2,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-user-law-registers") }}', { query: query, departmen_type: $('#depart_type').val()  }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {
             
                    if(checkNone(jsondata.basic_reward_group_id)){
                          $('#basic_reward_group_id').val(jsondata.basic_reward_group_id).trigger('change.select2');     
                    }
                    $('#taxid').val(jsondata.taxid); 

                    $('#name').val(jsondata.full_name);  
                    $('#email').val(jsondata.email);  
                    $('#mobile').val(jsondata.phone);  

                    if(checkNone(jsondata.sub_depart)){
                        $('#departmen_type').val('1').trigger('change.select2');
                        depart_type();
                        $('#sub_department_id').val( jsondata.sub_depart ).trigger('change.select2');
                    }else if(checkNone(jsondata.basic_sub_depart)){
                        $('#departmen_type').val('2').trigger('change.select2');
                        depart_type();
                        $('#basic_department_id').val( jsondata.basic_sub_depart ).trigger('change.select2');
                    }
                 

                    if(checkNone(jsondata.address)){
                        $('#address').val(jsondata.address);      
                    }else{
                        $('#address').val('');   
                    }

                    if(checkNone(jsondata.basic_bank_id)){
                        $('#basic_bank_id').val(jsondata.basic_bank_id).trigger('change.select2');     
                    }

                    if(checkNone(jsondata.bank_account_name)){
                        $('#bank_account_name').val(jsondata.bank_account_name);     
                    }

                    if(checkNone(jsondata.bank_account_number)){
                        $('#bank_account_number').val(jsondata.bank_account_number);     
                    }
                }
            });


         $(".check_format_en_and_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                        return false;
                    }  
          });
          $('#depart_type').change(function(event) {
                depart_type();
            });
            $('#depart_type').change();

            $('#search_taxid').click(function(event) {
                        var  taxid =  $('#taxid').val();
                     if(checkNone(taxid)){
                    //     taxid = taxid.toString().replace(/\D/g,'');
                    // if(taxid.length >= 13){
                                // Text
                            $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังค้นหา กรุณารอสักครู่..."
                                });
                            $.ajax({
                                url: '{!! url("funtions/search-user-law-registers")  !!}',
                                method: "get",
                                data: {query: taxid,  departmen_type: $('#depart_type').val() , _token: '{!! csrf_token() !!}'}
                            }).done(function (msg) {
                                $.LoadingOverlay("hide");
                                if( checkNone(msg[0]) ){
                                    var data =  msg[0];
                                      console.log(data);
                                    if(checkNone(data.basic_reward_group_id)){
                                        $('#basic_reward_group_id').val(data.basic_reward_group_id).trigger('change.select2');     
                                    }
                                    $('#taxid').val(data.taxid); 

                                    $('#name').val(data.full_name);  
                                    $('#email').val(data.email);  
                                    $('#mobile').val(data.phone);  

                                    if(checkNone(data.sub_depart)){
                                        $('#departmen_type').val('1').trigger('change.select2');
                                        depart_type();
                                        $('#sub_department_id').val( data.sub_depart ).trigger('change.select2');
                                    }else if(checkNone(data.basic_sub_depart)){
                                        $('#departmen_type').val('2').trigger('change.select2');
                                        depart_type();
                                        $('#basic_department_id').val( data.basic_sub_depart ).trigger('change.select2');
                                    }
                                
                                    if(checkNone(data.address)){
                                        $('#address').val(data.address);      
                                    }else{
                                        $('#address').val('');   
                                    }

                                    if(checkNone(data.basic_bank_id)){
                                        $('#basic_bank_id').val(data.basic_bank_id).trigger('change.select2');     
                                    }

                                    if(checkNone(data.bank_account_name)){
                                        $('#bank_account_name').val(data.bank_account_name);     
                                    }

                                    if(checkNone(data.bank_account_number)){
                                        $('#bank_account_number').val(data.bank_account_number);     
                                    }
                      
                                }else{
                                    
                                }
                            });

                        // }else{
                        //     Swal.fire({
                        //             position: 'center',
                        //             icon: 'warning',
                        //             title: 'กรุณากรอกเลขประจำตัวประชาชนให้ครบ 13 หลัก',
                        //             width: 600,
                        //             showConfirmButton: true
                        //             });
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
       
            $('#form_paid_save').click(function(event) {
                $('#form_paid').submit();
            });
       
              
            $('#form_paid').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {


                   // Text
                   $.LoadingOverlay("show", {
                                        image       : "",
                                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                                      });
       
                    var formData = new FormData($("#form_paid")[0]);
                        formData.append('_token', "{{ csrf_token() }}");

                    var attach = $('#attach_modal').prop('files')[0];
                           formData.append('case_number',"{{ !empty($cases->case_number)   ? $cases->case_number : 000000 }}");
                        if(checkNone(attach)){
                            formData.append('attach', $('#attach_modal')[0].files[0]);
                         }               
                     
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/law/reward/calculations/update_document') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                                  $.LoadingOverlay("hide");
                            if (msg != "") {
                      
                                var modal_id = $('#modal_id').val();
                                var modal_keys = $('#modal_keys').val();
                                var attach_ids = $('#attach_ids').val();
                                // ส่วนร่วมในคดี
                                var basic_reward_group_id = $('#basic_reward_group_id').val();
                                let basic_reward_group_text = $('#basic_reward_group_id').find('option:selected').text();

                                // หน่วยงาน
                                var depart_type_id = $('#depart_type').val();
                                let depart_type_text = $('#depart_type').find('option:selected').text();

                                // กอง/กลุ่ม (กรณีภายใน)
                                var sub_department_id = $('#sub_department_id').val();
                                var sub_department_text = $('#sub_department_id').find('option:selected').text();

                                // กอง/กลุ่ม (กรณีภายนอก)
                                var basic_department_id = $('#basic_department_id').val();
                                var basic_department_text = $('#basic_department_id').find('option:selected').text();

                                // เลขประจำตัวประชาชน
                                var taxid = $('#taxid').val();

                                //  ชื่อ-สกุล
                                var name = $('#name').val();
                                
                                // ที่อยู่
                                var address = $('#address').val();

                                // เบอร์มือถือ
                                var mobile = $('#mobile').val();

                                // อีเมล
                                var email = $('#email').val();

                                // ชื่อธนาคาร
                                var basic_bank_id = $('#basic_bank_id').val();
                                let basic_bank_text = $('#basic_bank_id').find('option:selected').text();

                                // ชื่อบัญชี
                                var bank_account_name = $('#bank_account_name').val();
                                // เลขที่บัญชี
                                var bank_account_number = $('#bank_account_number').val();


                                if(depart_type_id == '2'){
                                    var department_text = basic_department_text;
                                }else{
                                    var department_text = sub_department_text; 
                                }
                                var   bank = '';
                                if(bank_account_name != '' && bank_account_number != ''){
                                    bank =  bank_account_name +' : เลขที่'+ bank_account_number; 
                                }else if(bank_account_name != ''){
                                    bank =  bank_account_name; 
                                }else if(bank_account_number != ''){
                                    bank =   bank_account_number; 
                                }
                                
                            var  text = '';
                                    if(modal_keys == ''){
                                        text += '<tr class="krys">';
                                    }
                            
                                    text += '<td class="text-center text-top"></td>';
                                    text += '<td class="text-top">' +(name)+'<br/>'+(taxid)+'</td>';
                                    text += '<td class="text-top">' +(address)+'</td>';
                                    text += '<td class="text-top">' +(department_text)+'</td>';
                                    text += '<td class="text-top">' +(bank)+'</td>';

                                    text += '<td class="text-top">' +(basic_reward_group_text)+' ';
                                    if( checkNone(msg.file_attach) ){
                                       text += '<a  href="'+(msg.file_attach)+'" class="attach" target="_blank">'+msg.file_attach_icon+'</a>';
                                    }else{
                                        var file = $('#div_attach_modal').html();
                                        if(checkNone(file)){
                                            text += '<span class="a_file_attach" >'+file+'</span>';     
                                        }
                                    }
                                    text += '</td>';

                                    text += '<td class="text-center  text-top">';
                                    text += '<i class="pointer fa fa-pencil text-primary icon-pencil"  style="font-size: 1.5em;"></i>';
                                    text += ' <i class="pointer fa fa-remove text-danger icon-remove"  style="font-size: 1.5em;"></i>';
                                    text += '<input type="hidden"  class="id"    value="'+(modal_id)+'">';
                                    text += '<input type="hidden"  class="keys"  value="">';
                                    text += '<input type="hidden"  class="basic_reward_group_id"    value="'+(basic_reward_group_id)+'">';
                                    text += '<input type="hidden"  class="depart_type"    value="'+(depart_type_id)+'">';
                                    text += '<input type="hidden"  class="sub_department_id"   value="'+(sub_department_id)+'">';
                                    text += '<input type="hidden"  class="basic_department_id"   value="'+(basic_department_id)+'">';
                                    text += '<input type="hidden"  class="taxid"  value="'+(taxid)+'">';
                                    text += '<input type="hidden"  class="name" value="'+(name)+'">';
                                    text += '<input type="hidden"  class="address"  value="'+(address)+'">';
                                    text += '<input type="hidden"  class="mobile"  value="'+(mobile)+'">';
                                    text += '<input type="hidden"  class="email" value="'+(email)+'">';
                                    text += '<input type="hidden"  class="basic_bank_id"  value="'+(basic_bank_id)+'">';
                                    text += '<input type="hidden"  class="basic_bank_name"  value="'+(basic_bank_text)+'">' ;
                                    text += '<input type="hidden"  class="bank_account_name"  value="'+(bank_account_name)+'">';
                                    text += '<input type="hidden"  class="bank_account_number"  value="'+(bank_account_number)+'">';
                                      
                                    if( checkNone(attach_ids) ){
                                        text += '<input type="hidden" value="'+(attach_ids)+'" class="attach_ids"/>';
                                    }
                                    if( checkNone(msg.file_attach) ){
                                        text += '<input type="hidden" value="'+(msg.file_attach_odl)+'" class="input_file_attach_name"/>';
                                        text += '<input type="hidden" value="'+(msg.file_attach_path)+'" class="file_attach"/>';
                                    }

                                    text += '</td>';

                                if(modal_keys == ''){
                                        text += '</tr>';
                                        $('#table_tbody_paid').append(text);
                                }else{
                                    $('#table_tbody_paid').find('.keys[value="'+modal_keys+'"]').parent().parent().html(text);
                                } 
                                
                    
                                    ResetTablePaidNumber();
                                    save_paid();
                                        $('#modal_keys').val('');
                                        $('#form_paid').find('ul.parsley-errors-list').remove();
                                        $('#form_paid').find('input, select, textarea').val('');
                                        $('#form_paid').find('select').select2();
                                        $('#form_paid').find('input,textarea').removeClass('parsley-success');
                                        $('#form_paid').find('input,textarea').removeClass('parsley-error'); 
                                        $('#form_paid').find('#div_attach_modal').html('');
                                        $('#form_paid').find('.delete-exists').click();
                                        depart_type();


                                    $('#PaidModals').modal('hide');


                            }   
                        }
                    });


                  return false;
            });
 
        });

                
        function depart_type() {
                     $('#sub_department_id,#basic_department_id').val('').select2();
                    if($('#depart_type').val() == '2'){
                        $('#span_basic_department_id').show();
                        $('#span_sub_department_id').hide();
                        $('.departmen').show(); 
                        $('#sub_department_id').prop('required' ,false );     
                        $('#basic_department_id').prop('required' ,true);  
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,true);    
                        $('.div_basic_bank').show();    
                    }else  if($('#depart_type').val() == '1'){
                        $('#span_sub_department_id').show();
                        $('#span_basic_department_id').hide();
                        $('.departmen').show(); 
                        $('#sub_department_id').prop('required' ,true);     
                        $('#basic_department_id').prop('required' ,false);  
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,false);    
                        $('.div_basic_bank').hide();      
                     }else{ 
                        $('.departmen').hide(); 
                        $('#sub_department_id,#basic_department_id').prop('required' ,false);     
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,true);    
                       $('.div_basic_bank').show(); 
                    }
        }

        
    </script>
@endpush
 
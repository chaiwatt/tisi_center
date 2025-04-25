
@php
use App\Models\Basic\SubDepartment;
use App\Models\Besurv\Department;
use App\Models\Law\Basic\LawDepartment;

  $depart_type =  !empty($lawcasesform->owner_depart_type)?$lawcasesform->owner_depart_type:1;
  $owner_sub_department = [];
    if($depart_type == '1'){

        $sql = "(CASE 
                    WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                    ELSE  department.depart_nameShort
                END) AS title";

        $owner_sub_department = SubDepartment::leftjoin((new Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                ->select( DB::raw($sql), 'sub_id' )
                                ->pluck('title','sub_id')->toArray();

         if( !isset($lawcasesform->id) ){
            //หน่วยงาน (ใน)
            $lawcasesform->owner_sub_department_id = !empty( $user->subdepart->sub_id )?$user->subdepart->sub_id:null;
        }
        $lawcasesform->owner_department_name   = array_key_exists($lawcasesform->owner_sub_department_id, $owner_sub_department) ? $owner_sub_department[$lawcasesform->owner_sub_department_id]:'';
    }

  $owner_basic_department = [];
    if($depart_type == '2'){
        $owner_basic_department = LawDepartment::where('type', 2)->where('state',1)->pluck('title_short','id')->toArray();
        $lawcasesform->owner_department_name   = array_key_exists($lawcasesform->owner_basic_department_id, $owner_basic_department) ? $owner_basic_department[$lawcasesform->owner_basic_department_id]:'';

    }

@endphp

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <a class="btn btn-warning btn-xs pull-right" style="margin-left: 5px;" href="Javascript:void(0);" id="edit_first_form">
                 <i class="fa fa-pencil"></i>&nbsp;แก้ไข&nbsp;
            </a>
            <a class="btn btn-primary btn-xs pull-right for-show div-hide" href="Javascript:void(0);" id="clear_first_form">
                <i class="fa fa-trash-o"></i>&nbsp;ล้างข้อมูล&nbsp;
           </a>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label col-md-4"><small>หน่วยงาน :</small></label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted m-0" style="height:42px;" id="owner_depart_type_txt"> {!! !empty($lawcasesform->owner_depart_type)?$arr_depart_type[ $lawcasesform->owner_depart_type ]:'ภายใน (สมอ.)' !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::select('owner_depart_type', ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'], !empty($lawcasesform->owner_depart_type)?$lawcasesform->owner_depart_type:1 , ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงาน -', 'id' => 'owner_depart_type']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6 owner_department">
        <div class="form-group required">
            <label class="control-label col-md-5"><small>ชื่อหน่วยงาน/กอง/กลุ่ม</small> :</label>
            <div class="col-md-7 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_department_txt"> {!! !empty($lawcasesform->owner_department_name)?$lawcasesform->owner_department_name:null !!} </p>
            </div>
            <div class="col-md-7 for-show div-hide" id="owner_department_id">
                <!-- กรณีภายใน -->
                <div class="owner_sub_department_id">
                    {!! Form::select('owner_sub_department_id',$owner_sub_department, !empty($lawcasesform->owner_sub_department_id)?$lawcasesform->owner_sub_department_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกกอง/กลุ่ม (กรณีภายใน) -', 'id' => 'owner_sub_department_id']) !!}
                </div>
                <!-- กรณีภายนอก -->
                <div class="owner_basic_department_id">
                    {!! Form::select('owner_basic_department_id',$owner_basic_department, !empty($lawcasesform->owner_basic_department_id)?$lawcasesform->owner_basic_department_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงาน (กรณีภายนอก) -', 'id' => 'owner_basic_department_id']) !!}
                </div>
                <!-- เก็บชื่อ -->
                {!! Form::hidden('owner_department_name', !empty($lawcasesform->owner_department_name)?$lawcasesform->owner_department_name:null, ['id' => 'owner_department_name']) !!}
                {!! $errors->first('owner_department_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row box_law_bs_deperment_other">
    <div class="col-md-6 "></div>
        <div class="col-md-6">
            <div class="form-group  required {{ $errors->has('department_other') ? 'has-error' : ''}}" >
                {!! Form::label('owner_basic_department_other', 'อื่นๆระบุ', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7 for-show div-hide">
                    {!! Form::text('owner_basic_department_other', !empty($lawcasesform->owner_basic_department_other)?$lawcasesform->owner_basic_department_other:null, ['class' => 'form-control']) !!}
                    {!! $errors->first('owner_basic_department_other', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="col-md-7 for-show ">
                    <p class="form-control-static div_dotted" style="height:42px;" id="owner_basic_department_other_txt"> {!! !empty($lawcasesform->owner_basic_department_other)?$lawcasesform->owner_basic_department_other:null !!} </p>
                </div>
            </div>
        </div>
    </div>
    

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label col-md-4"><small>ข้าพเจ้า</small> :</label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_name_txt"> {!!  !empty($lawcasesform->owner_name)?$lawcasesform->owner_name:null  !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::text('owner_name', !empty($lawcasesform->owner_name)?$lawcasesform->owner_name:null , ['class' => 'form-control ', 'id'=>'owner_name']) !!}
                {!! $errors->first('$user->subdepart->sub_depart_shortname', '<p class="help-block">:message</p>') !!}
                {!! Form::hidden('owner_case_by', !empty($lawcasesform->owner_case_by)?$lawcasesform->owner_case_by:null, ['id' => 'owner_case_by']) !!}

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label col-md-5"><small>เลขประจำตัวผู้เสียภาษี</small> :</label>
            <div class="col-md-7 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_taxid_txt"> {!! !empty($lawcasesform->owner_taxid)?$lawcasesform->owner_taxid:null !!} </p>
            </div>
            <div class="col-md-7 for-show div-hide">
                {!! Form::text('owner_taxid', !empty($lawcasesform->owner_taxid)?$lawcasesform->owner_taxid:null , ['class' => 'form-control ', 'id'=>'owner_taxid']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label col-md-4"><small>อีเมล</small> :</label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_email_txt"> {!! !empty($lawcasesform->owner_email)?$lawcasesform->owner_email:null !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::text('owner_email', !empty($lawcasesform->owner_email)?$lawcasesform->owner_email:null , ['class' => 'form-control ', 'id'=>'owner_email']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label col-md-5"><small>เบอร์มือถือ</small> :</label>
            <div class="col-md-7 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_phone_txt"> {!! !empty($lawcasesform->owner_phone)?$lawcasesform->owner_phone:null !!} </p>
            </div>
            <div class="col-md-7 for-show div-hide">
                {!! Form::text('owner_phone', !empty($lawcasesform->owner_phone)?$lawcasesform->owner_phone:null , ['class' => 'form-control ', 'id'=>'owner_phone']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4"><small>เบอร์โทร</small> :</label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_tel_txt"> {!! !empty($lawcasesform->owner_tel)?$lawcasesform->owner_tel:null !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::text('owner_tel', !empty($lawcasesform->owner_tel)?$lawcasesform->owner_tel:null , ['class' => 'form-control ', 'id'=>'owner_tel']) !!}
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('send_mail_status', 'ติดต่อประสานงาน'.' :', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-8 m-t-5">
                <div class="col-md-10 for-show checkbox_contact">
               
                </div>
                <div class="col-md-10 for-show div-hide">
                    {!! Form::checkbox('owner_contact_options', '1', true, ['class'=>'check', 'id' => 'owner_contact_options', 'data-checkbox'=>'icheckbox_minimal-blue', 'readonly'=>'readonly']) !!}
                    <label for="owner_contact_options">ข้อมูลเดียวกับเจ้าของคดี</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">ชื่อ-สกุล :</label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_contact_name_txt"> {!! !empty($lawcasesform->owner_contact_name)?$lawcasesform->owner_contact_name:null !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::text('owner_contact_name', !empty($lawcasesform->owner_contact_name)?$lawcasesform->owner_contact_name:null , ['class' => 'form-control ', 'id'=>'owner_contact_name']) !!}
                {!! $errors->first('owner_contact_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-5">อีเมล :</label>
            <div class="col-md-7 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_contact_email_txt"> {!! !empty($lawcasesform->owner_contact_email)?$lawcasesform->owner_contact_email:null !!} </p>
            </div>
            <div class="col-md-7 for-show div-hide">
                {!! Form::text('owner_contact_email', !empty($lawcasesform->owner_contact_email)?$lawcasesform->owner_contact_email:null , ['class' => 'form-control ', 'id'=>'owner_contact_email']) !!}
                {!! $errors->first('owner_contact_email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">เบอร์มือถือ :</label>
            <div class="col-md-8 for-show">
                <p class="form-control-static div_dotted" style="height:42px;" id="owner_contact_phone_txt"> {!! !empty($lawcasesform->owner_contact_phone)?$lawcasesform->owner_contact_phone:null !!} </p>
            </div>
            <div class="col-md-8 for-show div-hide">
                {!! Form::text('owner_contact_phone', !empty($lawcasesform->owner_contact_phone)?$lawcasesform->owner_contact_phone:null , ['class' => 'form-control ', 'id'=>'owner_contact_phone']) !!}
                {!! $errors->first('owner_contact_phone', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {

            $('#edit_first_form').click(function(event) {
                let val = $(this).data('edit');
                $('.for-show').toggleClass('div-hide div-show');
                if (val == "0" ){
                    $('#edit_first_form').data('edit',1);
                }else{
                    $('.for-show').removeClass('div-show');
                    $('#edit_first_form').data('edit',0);
                }

                //โหลดข้อมูล
                LoadInfomation();
            });

            $('#clear_first_form').click(function(event) {
                $('.for-show').find('input').val('');
                $('.for-show').find('select').val('').change();
                $('#owner_contact_options').prop('checked',false);
                $('#owner_contact_options').iCheck('update');

            });
            
            $('#owner_depart_type').change(function(event) {
                owner_depart_type_null();
                let owner_depart_type = $(this).val();
                let url               = '{{ url('/law/cases/forms/get_owner_department') }}/' + owner_depart_type;
                
                //กอง/กลุ่ม (กรณีภายใน)
                $('.owner_sub_department_id').hide();
                $('#owner_sub_department_id').html('<option value="">- เลือกกอง/กลุ่ม -</option>');
                $('#owner_basic_department_id').val('').trigger('change.select2');

                //หน่วยงาน (กรณีภายนอก)
                $('.owner_basic_department_id').hide();
                $('#owner_basic_department_id').html('<option value="">- เลือกหน่วยงาน -</option>');
                $('#owner_sub_department_id').val('').trigger('change.select2');

                //วันที่
                $('.box_department_type1').hide();
                $('.box_department_type1').find('input').prop('required' ,false );    

                if ( owner_depart_type == 1 ) {
                    $('.owner_department').show();
                    //เลือกกอง/กลุ่ม (กรณีภายใน)
                    $('.owner_sub_department_id').show();
              
                    let selected = '{{ !empty($lawcasesform->owner_sub_department_id)?$lawcasesform->owner_sub_department_id:null }}';

                    $.ajax({
                        url: url,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            if (data.length > 1) {
                                $.each(data,function(index, value){

                                    var title = value.title;

                                    $('#owner_sub_department_id').append('<option value='+value.sub_id+' >'+title+'</option>');
                                });
                                // $('#owner_sub_department_id').val(selected).trigger('change.select2');
                                LoadInfomation();
                            }
                        }
                    });
                    $('#div_offend_ref_no').show();
                    $('#offend_ref_no').val('').select2();
                } else if( owner_depart_type == 2 ) {
                    $('.owner_department').show();
                    //เลือกหน่วยงาน (กรณีภายนอก)
                    $('.owner_basic_department_id').show();
                    //วันที่
                    $('.box_department_type1').show();
                    $('.box_department_type1').find('input').prop('required' ,true );  

                    let selected = '{{ !empty($lawcasesform->owner_basic_department_id)?$lawcasesform->owner_basic_department_id:null }}';

                    $.ajax({
                        url: url,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            if (data.length > 1) {
                                $.each(data,function(index, value){
                                    if(checkNone(value.title_short) && value.title_short != '-'){
                                        var title = value.title_short;
                                    }else{
                                        var title = value.title;
                                    }              
                                    $('#owner_basic_department_id').append('<option  value='+value.id+' >'+title+'</option>');
                                });
                                // $('#owner_basic_department_id').val(selected).trigger('change.select2');
                                LoadInfomation();
                            }
                        }    
                    });
                    $('#div_offend_ref_no').hide();
                    $('#offend_ref_no').val('').select2();
                }else{
                    $('.owner_department').hide();
                }
                ShowHideDepartmentsOther();
            });

            // $('#owner_depart_type').change();

            $('#owner_basic_department_id').change(function (e) {
                ShowHideDepartmentsOther();
                
            });
            ShowHideDepartmentsOther();

            $('#owner_contact_options').on('ifChanged', function (event) {

                if( $(this).prop('checked') == true ){
                    //ชื่อ-สกุล
                    var owner_name = $('#owner_name').val();
                    if( checkNone(owner_name) ){
                        $('#owner_contact_name').val(owner_name);
                        $('#owner_contact_name_txt').text(owner_name);
                    }

                    //อีเมล
                    var owner_email = $('#owner_email').val();
                    if( checkNone(owner_email) ){
                        $('#owner_contact_email').val(owner_email);
                        $('#owner_contact_email_txt').text(owner_email);
                    }

                    //เบอร์มือถือ
                    var owner_phone = $('#owner_phone').val();
                    if( checkNone(owner_phone) ){
                        $('#owner_contact_phone').val(owner_phone);
                        $('#owner_contact_phone_txt').text(owner_phone);
                    }
                }else{
                    $('#owner_contact_name').val('');
                    $('#owner_contact_name_txt').text('');

                    $('#owner_contact_email').val('');
                    $('#owner_contact_email_txt').text('');

                    $('#owner_contact_phone').val('');
                    $('#owner_contact_phone_txt').text('');
                }

            });

            $('#owner_name').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    let owner_sub_department_id = $('#owner_sub_department_id').val();
                    let owner_basic_department_id = $('#owner_basic_department_id').val();
                    let owner_depart_type = $('#owner_depart_type').val();
         
                    if(owner_depart_type == '1'){
                        return $.get('{{ url("funtions/search-user-registers") }}', { query: query, sub_department_id: owner_sub_department_id }, function (data) {
                            return process(data);
                        });
                    }else{
                        return $.get('{{ url("funtions/search-user-lawcase") }}', { query: query, owner_basic_department_id: owner_basic_department_id }, function (data) {
                            return process(data);
                        });
                    }

                },
                autoSelect: true,
                afterSelect: function (jsondata) {
                    if( checkNone(jsondata) ){
                        $('#owner_name').val(jsondata.full_name);
                        $('#owner_email').val(jsondata.email);
                        $('#owner_tel').val(jsondata.wphone);
                        $('#owner_phone').val(jsondata.phone);
                        $('#owner_taxid').val(jsondata.taxid);   
                        $('#owner_case_by').val(jsondata.id);   

                        $('#owner_contact_name').val(jsondata.full_name);   
                        $('#owner_contact_email').val(jsondata.email);   
                        $('#owner_contact_phone').val(jsondata.phone);   

                    }
                
                }
            });
            ShowHideDepartments();
        });
        

        function owner_depart_type_null(){
          if($('#edit_first_form').data('edit') == '1'){
            $('#owner_contact_options').prop('checked',false);
            $('#owner_contact_options').iCheck('update');
             $('#owner_sub_department_id, #owner_basic_department_id').val('').select2();
             $('#owner_name, #owner_case_by, #owner_taxid, #owner_email, #owner_phone, #owner_tel, #owner_contact_name, #owner_contact_email, #owner_contact_phone').val('');
          }
        } 
        function LoadInfomation(){

            //หน่วยงาน
            var type_val = $('#owner_depart_type').val();
            var type     = $('#owner_depart_type').find("option:selected").text();
            if( checkNone(type) && checkNone(type_val)  ){
                $('#owner_depart_type_txt').text(type);
            }else{
                $('#owner_depart_type_txt').text('');
            }

            //ชื่อหน่วยงาน/กอง/กลุ่ม
            var department = '-';
            if( checkNone(type_val) && type_val == 1 ){
                if( checkNone( $('#owner_sub_department_id').val()) ){
                    department = $('#owner_sub_department_id').find("option:selected").text();
                }
            }else if(checkNone(type_val) && type_val == 2){
                if( checkNone( $('#owner_basic_department_id').val()) ){
                    department = $('#owner_basic_department_id').find("option:selected").text();
                    ShowHideDepartmentsOther();
                }
            }

            //อื่นๆระบุ
            var owner_other = $('#owner_basic_department_other').val();
            if( checkNone(owner_other) ){
                $('#owner_basic_department_other_txt').text(owner_other);
            }else{
                $('#owner_basic_department_other_txt').text('');
            }

            if( checkNone(department) ){
                $('#owner_department_txt').text(department);
                $('#owner_department_name').val(department);
            }else{
                $('#owner_department_txt').text('');
            }

            //ข้าพเจ้า
            var owner_name = $('#owner_name').val();
            if( checkNone(owner_name) ){
                $('#owner_name_txt').text(owner_name);
            }else{
                $('#owner_name_txt').text('');
            }

            //เลขประจำตัวผู้เสียภาษี
            var owner_taxid = $('#owner_taxid').val();
            if( checkNone(owner_taxid) ){
                $('#owner_taxid_txt').text(owner_taxid);
            }else{
                $('#owner_taxid_txt').text('');
            }

            //อีเมล
            var owner_email = $('#owner_email').val();
            if( checkNone(owner_email) ){
                $('#owner_email_txt').text(owner_email);
            }else{
                $('#owner_email_txt').text('');
            }

            //เบอร์มือถือ
            var owner_phone = $('#owner_phone').val();
            if( checkNone(owner_phone) ){
                $('#owner_phone_txt').text(owner_phone);
            }else{
                $('#owner_phone_txt').text('')
            }

            //เบอร์โทร
            var owner_tel = $('#owner_tel').val();
            if( checkNone(owner_tel) ){
                $('#owner_tel_txt').text(owner_tel);
            }else{
                $('#owner_tel_txt').text('');
            }

            //-----------ติดต่อประสานงาน---------------

            //ชื่อ-สกุล
            var owner_contact_name = $('#owner_contact_name').val();
            if( checkNone(owner_contact_name) ){
                $('#owner_contact_name_txt').text(owner_contact_name);
            }else{
                $('#owner_contact_name_txt').text('');
            }

            //อีเมล
            var owner_contact_email = $('#owner_contact_email').val();
            if( checkNone(owner_contact_email) ){
                $('#owner_contact_email_txt').text(owner_contact_email);
            }else{
                $('#owner_contact_email_txt').text('');
            }

            //เบอร์มือถือ
            var owner_contact_phone = $('#owner_contact_phone').val();
            if( checkNone(owner_contact_phone) ){
                $('#owner_contact_phone_txt').text(owner_contact_phone);
            }else{
                $('#owner_contact_phone_txt').text('');
            }

            // ข้อมูลเดียวกับเจ้าของคดี
            var checked =  $('#owner_contact_options').prop('checked');

            if( checked == true ){
                $('.checkbox_contact').html('<label><div class="icheckbox_minimal-blue checked"></div> ข้อมูลเดียวกับเจ้าของคดี</label>');
            }else{
                $('.checkbox_contact').html('<label><div class="icheckbox_minimal-blue"></div> ข้อมูลเดียวกับเจ้าของคดี</label>');
            }

        }

        function ShowHideDepartmentsOther(){
            var law_bs_deperment_id = $('#owner_basic_department_id').val();
            if(law_bs_deperment_id !=""){
                $.ajax({
                    url: "{!! url('/law/funtion/get-other-departments') !!}" + "?id=" + law_bs_deperment_id
                }).done(function( other ) {
                    if(other){
                        $('.box_law_bs_deperment_other').show();
                        $('#owner_basic_department_other').prop('disabled', false);
                        $('#owner_basic_department_other').prop('required', true);
                    }else{
                        $('.box_law_bs_deperment_other').hide();
                        $('#owner_basic_department_other').prop('disabled', true);
                        $('#owner_basic_department_other').prop('required', false);
                    }             
        
                });
     
            }else{
                $('.box_law_bs_deperment_other').hide();
                $('#owner_basic_department_other').prop('disabled', true);
                $('#owner_basic_department_other').prop('required', false);
            }
        }

        function ShowHideDepartments(){
            var owner_depart_type_val = $('#owner_depart_type').val();
            if(owner_depart_type_val == '1'){
                $('.owner_sub_department_id').show();
                $('.owner_basic_department_id').hide();
            }else{
                $('.owner_basic_department_id').show();
                $('.owner_sub_department_id').hide();
            }
        }

    </script>
@endpush

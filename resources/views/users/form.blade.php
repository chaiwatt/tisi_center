<div class="col-md-6 col-sm-12">

    <div class="white-box">
        <h3 class="box-title m-b-0">ข้อมูลผู้ใช้งาน</h3>
        <hr>
        <fieldset class="white-box">
            <legend class="legend">ข้อมูลเจ้าหน้าที่ผู้ใช้งาน</legend>

            <div class="form-group">
                {!! Form::label('reg_13ID', 'เลขประจำตัวประชาชน:', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                    {!! Form::text('reg_13ID', !empty($user->reg_13ID)?$user->reg_13ID:null, ['class' => 'form-control', 'required' => 'required', 'readonly' => ( ( !isset($user->reg_13ID) || auth()->user()->isAdmin() ?false:true ) ) ]) !!}
                    {!! $errors->first('reg_13ID', '<p class="help-block">:message</p>') !!}
                    <input type="hidden" id="hidden_taxid" value="" name="hidden_taxid" @if( !isset($user->reg_13ID) ) required @endif>
                    <span class="invalid-feedback" role="alert">
                        <strong id="taxid_alert"></strong>
                    </span>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reg_fname', 'ชื่อ:', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                    {!! Form::text('reg_fname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('reg_fname', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reg_lname', 'สกุล:', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                    {!! Form::text('reg_lname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('reg_lname', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reg_phone', 'เบอร์มือถือ:', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                {!! Form::text('reg_phone', null, ['class' => 'form-control', 'maxlength' => 12, 'required'=>'required']) !!}
                {!! $errors->first('reg_phone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reg_wphone', 'เบอร์ที่ทำงาน:', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                {!! Form::text('reg_wphone', null, ['class' => 'form-control', 'maxlength' => 11, 'required'=>'required']) !!}
                {!! $errors->first('reg_wphone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reg_subdepart', 'หน่วยงาน:', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('reg_subdepart', App\Models\Besurv\Department::with('sub_department')->get()->pluck('sub_departments', 'depart_name'), null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('', 'ระดับ:', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('', @$user->RoleTitle, ['class' => 'form-control', 'disabled' => true]) !!}
                </div>
            </div>

        </fieldset>

        <fieldset class="white-box">
            <legend class="legend">รหัสผ่านสำหรับลงชื่อเข้าใช้งาน</legend>
            <div class="form-group">
                {!! Form::label('reg_email', 'อีเมล (ชื่อผู้ใช้งาน):', ['class' => 'col-sm-4 control-label required']) !!}
                <div class="col-sm-8">
                {!! Form::email('reg_email', null, ['class' => 'form-control', 'required' => 'required', 'readonly' => ( ( !isset($user->reg_13ID)?false:true ) )]) !!}
                {!! $errors->first('reg_email', '<p class="help-block">:message</p>') !!}
                <p class="text-danger m-b-0" id="reg_email_error"></p>
                    <span class="hide">
                        <input type="text" name="reg_email_error" />
                    </span>
                </div>
            </div>
            <div class="form-group @if( !isset($user->reg_13ID) ) required @endif">
                {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::password('password', ['class' => 'form-control', 'required' => ( ( !isset($user->reg_13ID)?true:false ) ), 'placeholder' => ( ( !isset($user->reg_13ID)?'ระบุรหัสผ่าน':'ถ้าไม่เปลี่ยนปล่อยว่างไว้' ) ) ]) !!}
                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                    <span id="span-warn-password" class="text-danger"></span>
                    <span class="text-warning">รูปแบบรหัสผ่าน ต้องเป็นตัวอักษรภาษาอังกฤษ ตัวพิมพ์ใหญ่หรือตัวพิมพ์เล็ก ตัวเลข สัญลักษณ์ และความยาวไม่น้อยกว่า 8 อักษร</span>
                </div>
            </div>
        </fieldset>
    </div>

</div>

<div class="col-md-6 col-sm-12">
    <div class="col-md-12">
        <div class="white-box">
            <h3 class="box-title m-b-0">กลุ่มผู้ใช้งาน</h3>
            <hr>
            <div class="form-group">
                <div class="col-sm-12">

                    @php

                        $modalRole             = new App\Role;
                        $modalRoleGroup        = new App\RoleGroup;
                        $modalRoleSettingGroup = new App\RoleSettingGroup;

                        $user_id               = Auth::user()->getKey();

                        $setting_system        = $modalRoleSettingGroup->count();
                        $roles_all             = $modalRole->Has('role_group', '>=',  $setting_system  )
                                                            ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                                                $check =  Auth::user()->roles()->select('level')->get();
                                                            
                                                                //ที่ไม่ใช่ level 1
                                                                if( $check->where('level', 1 )->count() == 0 ){
                                                                    //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                                    $checklevel = $check->whereIn('level', [2,3] )->count();

                                                                
                                                                    if( $checklevel >= 1 ){
                                                                        $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                                            $query->where('user_runrecno', $user_id );
                                                                                                        })
                                                                                                        ->select('id');

                                                                        $query->whereHas('role_setting_group',function($query) use($role_group){
                                                                                    $query->whereIn( 'id' , $role_group );
                                                                                });
                                                                    }
                                                                }
                                                            
                                                            })
                                                            ->when(!auth()->user()->isAdmin(), function ($query){
                                                                $query->where('name', '!=', 'admin');
                                                            })
                                                            ->where('label', 'staff' )->get();

                        $groups                =  $modalRoleGroup->with('setting_system')
                                                                ->whereHas('role', function($query) use ($setting_system , $modalRole ){
                                                                    $query->WhereNotIn('id', $modalRole->Has('role_group', '>=',  $setting_system  )->select('id') )->where('label', 'staff' );
                                                                })
                                                                ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                                                    $check =  Auth::user()->roles()->select('level')->get();
                                                                    //ที่ไม่ใช่ level 1
                                                                    if( $check->where('level', 1 )->count() == 0 ){
                                                                        //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                                        $checklevel = $check->whereIn('level', [2,3] )->count();
                                                                        if( $checklevel >= 1 ){
                                                                            $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                                                $query->where('user_runrecno', $user_id );
                                                                                                            })
                                                                                                            ->select('id');

                                                                            $query->whereIn( 'setting_systems_id' , $role_group );
                                                                        }
                                                                    }

                                                                    })
                                                                ->groupby('setting_systems_id')
                                                                ->get()
                                                                ->pluck('setting_system');

                        $roles                 =   $modalRole->Has('role_group','=',0)
                                                                ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                                                    $check =  Auth::user()->roles()->select('level')->get();
                                                                    //ที่ไม่ใช่ level 1
                                                                    if( $check->where('level', 1 )->count() == 0 ){
                                                                        //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                                        $checklevel = $check->whereIn('level', [2,3] )->count();
                                                                        if( $checklevel >= 1 ){
                                                                            $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                                                $query->where('user_runrecno', $user_id );
                                                                                                            })
                                                                                                            ->select('id');

                                                                            $query->whereHas('role_setting_group',function($query) use($role_group){
                                                                                        $query->whereIn( 'id' , $role_group );
                                                                                    });
                                                                        }
                                                                    }

                                                                })
                                                                ->when(!auth()->user()->isAdmin(), function ($query){
                                                                    $query->where('name', '!=', 'admin');
                                                                })
                                                                ->where('label', 'staff' )
                                                                ->get();

                        $staff = auth()->user();
                       
                    @endphp

                    @if( count( $roles_all ) >= 1 )
                        <div class="panel-group" id="accordion_all_group" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingAllGroup">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion_all_group" href="#collapseAllGroup" aria-expanded="true" aria-controls="collapseAllGroup">
                                            All Group <small><em>(อยู่ทุกระบบงาน)</em></small>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseAllGroup" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingAllGroup">
                                    <div class="panel-body">

                                        @foreach ( $roles_all as $role )
                                            
                                            @if($role->label!='staff')
                                                @continue
                                            @endif

                                            @if($role->name=='admin' && !$staff->isAdmin()) <!-- เป็นกลุ่ม admin แต่ผู้เข้ามาไม่ใช่ admin ไม่แสดงกลุ่มนี้ให้เลือก -->
                                                @continue
                                            @endif

                                            <div class="checkbox checkbox-success">
                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                <label for="roles">&nbsp;{{ $role->name }}</label>
                                            </div>

                                        @endforeach
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach ($groups as $group)

                        @php
                            $accordion_id = uniqid('accordion_');
                            $collapse_id = uniqid();
                        @endphp

                        <div class="panel-group" id="{!! $accordion_id !!}" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading{!! $collapse_id !!}">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#{!! $accordion_id !!}" href="#collapse_{!! $collapse_id !!}" aria-expanded="true" aria-controls="collapse_{!! $collapse_id !!}">
                                        {!! $group->title !!} <small><em>({!! $group->description !!})</em></small>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse_{!! $collapse_id !!}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{!! $collapse_id !!}">
                                    <div class="panel-body">

                                        @foreach ( $group->role()->WhereNotIn('id', App\Role::Has('role_group', '>=',  $setting_system  )->select('id') )->get() as $role )

                                            @if($role->name=='admin' && !$staff->isAdmin()) <!-- เป็นกลุ่ม admin แต่ผู้เข้ามาไม่ใช่ admin ไม่แสดงกลุ่มนี้ให้เลือก -->
                                                @continue
                                            @endif

                                            <div class="checkbox checkbox-success">
                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                <label for="roles">&nbsp;{{ $role->name }} </label>
                                            </div>

                                        @endforeach
                                
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach

                    @if( count( $roles ) >= 1 )
                        <div class="panel-group" id="accordion_other" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOther">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion_other" href="#collapseOther" aria-expanded="true" aria-controls="collapseOther">
                                            อื่นๆ <small><em>(ไม่มีระบบงาน)</em></small>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOther" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOther">
                                    <div class="panel-body">

                                        @foreach ( $roles as $role )
                                            
                                            @if($role->label!='staff')
                                                @continue
                                            @endif

                                            @if($role->name=='admin' && !$staff->isAdmin()) <!-- เป็นกลุ่ม admin แต่ผู้เข้ามาไม่ใช่ admin ไม่แสดงกลุ่มนี้ให้เลือก -->
                                                @continue
                                            @endif

                                            <div class="checkbox checkbox-success">
                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                <label for="roles">&nbsp;{{ $role->name }}</label>
                                            </div>

                                        @endforeach
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>


        </div>
    </div>

</div>

<div class="col-md-12">
    <div class="text-center">
        <button class="btn btn-primary waves-effect waves-light" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        <a class="btn btn-default waves-effect waves-light" href="{{ url('users') }}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    </div>
</div>

@push('js')

    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/inputmask/jquery.inputmask.bundle.js')}}"></script>

    <script type="text/javascript">

        $(document).ready(function() {

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

            @if ( (isset($user) && !$user->isAdmin())  && !isset($user)  )
                $('#reg_13ID').inputmask('9-9999-99999-99-9');   
            @endif

            @if(!isset($user))
                $('label[for="password"]').addClass('required');
                $('#password').prop('required', true);
                $('.description-password').text('');
            @endif

            $('#reg_email').keyup(function(event) {
                check_user();
            });

            $('.input_roles_checkbox').click(function (e) { 
 
                var role_id = $(this).val();

                if($(this).is(':checked',true)){
                    $(".input_role_id_"+role_id).prop('checked', true);
                } else {
                    $(".input_role_id_"+role_id).prop('checked',false);
                }
                
            });

            $("#reg_13ID").on("keyup" , function () {
                validateIDCard($(this).val());
                CheckTaxid($(this).val());
            });

            $("#reg_13ID").on("change" , function () {
                validateIDCard($(this).val());
                CheckTaxid($(this).val());
            });

            // เช็ค password
            $("#password").change(function(event) {
                var password = $(this).val();
                if(checkNone(password)){
                    var html = check_password_and_number(password);
                    if(html != ''){
                        Swal.fire({
                            title:'กรุณากรอกรูปแบบรหัสผ่านใหม่และความยาวไม่น้อยกว่า 8 อักษร',
                            html:html,
                            width: 700,
                            showDenyButton: true,
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        });
                        $('#password').val('');
                    }
                }
            });

        });

        function validateIDCard(value){
            var pid = value;
                pid = pid.toString().replace(/\D/g,'');
                $('#hidden_taxid').val('');
                if(pid.length == 13){
                    var sum = 0;
                    for(var i = 0; i < pid.length-1; i++){
                        sum += Number(pid.charAt(i))*(pid.length-i);
                    }
                    var last_digit = (11 - sum % 11) % 10;
                    if(pid.charAt(12) != last_digit){
                        alert("หมายเลขบัตรประชาชนระบุไม่ถูกต้อง");
                        $('#taxid').val('');
                        return false;
                    }else{
                        $('#taxid').removeClass('parsley-error');
                        $('#taxid').addClass('is-valid');
                        $('#hidden_taxid').val(1);
                        return pid.charAt(12) == last_digit;
                    }
                }else{
                    if(pid.length < 13){
                        $('#taxid').addClass('parsley-error');
                        $('#taxid').removeClass('is-valid');
                        $('#taxid').addClass('is-invalid');
                        $('#taxid_alert').text('หมายเลขบัตรประชาชนให้ครบถ้วน');
                        $('#hidden_taxid').val('');
                    }
                    return false;
                }
        }

        function CheckTaxid(value){
            var pid = value;
                pid = pid.toString().replace(/\D/g,'');
            if(value != ''){
                $.ajax({
                    url: "{!! url('/user/check-taxid/') !!}" + "/" + value
                }).done(function( object ) {
                    console.log(object == "error1");
                    if(object == "error1"){//มีแล้วทั้ง profile และ user
                        $('#reg_13ID').addClass('parsley-error');
                        $('#reg_13ID').removeClass('is-valid');
                        $('#reg_13ID').addClass('is-invalid');
                        $('#taxid_alert').text('หมายเลขบัตรประชาชนมีในระบบแล้ว');
                        $('#reg_13ID').val('');
                        $('#hidden_taxid').val('');
                    }else if( pid.length < 13){
                        $('#reg_13ID').addClass('parsley-error');
                        $('#reg_13ID').removeClass('is-valid');
                        $('#reg_13ID').addClass('is-invalid');
                        $('#taxid_alert').text('หมายเลขบัตรประชาชนให้ครบถ้วน');
                        $('#hidden_taxid').val('');
                    }else{
                        $('#reg_13ID').addClass('parsley-success');
                        $('#reg_13ID').removeClass('is-invalid');
                        $('#reg_13ID').addClass('is-valid');
                        $('#taxid_alert').text('');
                        $('#hidden_taxid').val(1);
                    }
                });
            }else{
                $('#hidden_taxid').val('');

                if(pid.length < 13){
                    $('#reg_13ID').addClass('parsley-error');
                    $('#reg_13ID').removeClass('is-valid');
                    $('#reg_13ID').addClass('is-invalid');
                    $('#taxid_alert').text('หมายเลขบัตรประชาชนให้ครบถ้วน');
                }else{
                    $('#reg_13ID').removeClass('parsley-success');
                    $('#reg_13ID').removeClass('parsley-error');
                    $('#reg_13ID').removeClass('is-invalid');
                    $('#reg_13ID').removeClass('is-valid');
                    $('#taxid_alert').text('');
                }
            }
        }

        function check_user(){
            var id = '{{ isset($user) ? $user->getKey() : '' }}';
            $.ajax('{!! url('user/check_email_repeat/') !!}/'+$('#reg_email').val() + '/' + id)
            .done(function(res) {
                if(res.hasOwnProperty('result')){
                    if(res.result==true){//ซ้ำ
                        $('#reg_email_error').text('อีเมลนี้มีในระบบแล้ว');
                        $('input[name="reg_email_error"]').prop('required', true);
                        $('input[name="reg_email"]').val('');
                    }else{//ไม่ซ้ำ
                        $('#reg_email_error').text('');
                        $('input[name="reg_email_error"]').prop('required', false);
                    }
                }
            });
        }

        function check_password_and_number(value) {

            var html = '';
            var password = value.toString();
            var passwords= password.split("");
            var format   = {upper: false, lower: false, number: false, symbol: false}
            var allows   = Array(' ', '!', '"', '#', '$', '%',
                                '&', "'", '(', ')', '*', '+',
                                ',', '-', '.', '/', ':', ';',
                                '<', '=', '>', '?', '@', '[',
                                '\\', ']', '^', '_', '`', '{',
                                '|', '}', '~'
                                );
            var not_prefix = ' ';
            var not_allows = Array();

            $.each(passwords, function(index, value) {

                if(value.match(/[A-Z]/g) !== null){
                    format.upper = true;
                }

                if(value.match(/[a-z]/g) !== null){
                    format.lower = true;
                }

                if(value.match(/[0-9]/g) !== null){
                    format.number = true;
                }

                if(value.match(/[A-Z]/g) == null && value.match(/[a-z]/g) == null && value.match(/[0-9]/g) == null){//นอกเหนือจากที่กำหนดไว้
                    if(!allows.includes(value)){//ไม่อยู่ในอักขระพิเศษที่อนุญาต
                        if(!not_allows.includes('<p>-ไม่อนุญาตให้ใช้ตัวอักษร '+value+'</p>')){//ยังไม่มี
                            not_allows.push('<p>-ไม่อนุญาตให้ใช้ตัวอักษร '+value+'</p>');
                        }
                    }else{
                        format.symbol = true;
                        if(value===not_prefix && index==0){
                            html += '<p>-ไม่อนุญาตให้ใช้ <code>ช่องว่าง</code> นำหน้า</p>';
                        }
                        if(value===not_prefix && index==(passwords.length-1)){
                            html += '<p>-ไม่อนุญาตให้ใช้ <code>ช่องว่าง</code> ตัวสุดท้าย</p>';
                        }
                    }
                }

            });

            if(format.lower===false && format.upper===false){
                html += '<p>-อักษรภาษาอังกฤษตัวพิมพ์ใหญ่ หรือ ตัวพิมพ์เล็ก อย่างน้อย 1 ตัว</p>';
            }

            if(format.number===false){
                html += '<p>-ตัวเลข อย่างน้อย 1 ตัว</p>';
            }

            if(format.symbol===false){
                html += '<p>-สัญลักษณ์พิเศษอย่างน้อย 1 ตัว</p>';
            }

            if(not_allows.length > 0){
                html += not_allows.join('');
            }

            if(password.length < 8){
                html += '<p>-คุณกรอกรหัสผ่านได้ '+password.length +' อักษร</p>';
            }

            return html ;

        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


    </script>

@endpush

<div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('offend_impound_type') ? 'has-error' : ''}}">
            {!! Form::label('approve_type', 'ส่งเรื่องถึง', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-10">
                <label>{!! Form::radio('approve_type', '1', !empty($lawcasesform->approve_type)?$lawcasesform->approve_type:true , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'approve_type_1']) !!} ส่งเรื่องถึงผู้มีอำนาจพิจารณา (ขออนุมัติผ่านระบบ)</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('offend_impound_type') ? 'has-error' : ''}}">
            {!! Form::label(' ', 'ส่งเรื่องถึง', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-10">
                <label>{!! Form::radio('approve_type', '2', !empty($lawcasesform->approve_type)?$lawcasesform->approve_type:true , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'approve_type_2']) !!} ส่งเรื่องถึง กม.(ผ่านอนุมัตินอกระบบแล้ว)</label>
            </div>
        </div>
    </div>
</div>

@php
$role  = [ 
          '7'=>'จนท',
          '6'=>'ผก',
          '5'=>'ผอ',
          '4'=>'ทป',
          '2'=>'รมอ',
          '1'=>'ลมอ'
        ];



$did             =   !empty(auth()->user()->didName)? auth()->user()->didName : null;
$department      =  App\Models\Besurv\Department::pluck('depart_name', 'did');
 $depart_type    =  !empty($lawcasesform->owner_depart_type)?$lawcasesform->owner_depart_type:1;

 $UserDepartments =  HP_Law::UserDepartments();
 
 use  App\Models\Basic\SubDepartment;
 use  App\User;

 $sub_departments  = SubDepartment::pluck('did', 'sub_id')->toArray();
  if($depart_type == '1'){     // ภายใน (สมอ.)  
      $defaults = [ '6', '5','2','1' ];   
  }else{   // ภายนอก
      $defaults = [ '2', '1'];   
  }
                            
   @endphp
                        
<div class="row" id="div_repeater_approve">
    <div class="col-md-12">
            <p class="font-medium-6 text-orange m-t10"> * ระบบจะส่งอีเมลแจ้งเตือนพิจารณาตามลำดับ </p>
            <table class="table color-bordered-table primary-bordered-table table-bordered table-sm repeater-form-approve" id="table_approve">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">ลำดับ</th>
                        <th class="text-center" width="15%">ส่งถึง</th>
                        <th class="text-center" width="20%">ส่งเรื่องถึงกอง</th>
                        <th class="text-center" width="20%">ผู้มีอำนาจพิจารณา</th>
                        <th class="text-center" width="20%">ตำแหน่ง</th>
                        <th class="text-center" width="10%">รักษาการแทน</th>
                        <th class="text-center" width="5%">จัดการ</th>
                    </tr>
                </thead>
                <tbody data-repeater-list="repeater-approve" id="table_tbody_approve">
                    @if( !empty($lawcasesform->cases_level_approve) && count($lawcasesform->cases_level_approve) >= 1 )
                                    {{-- @foreach(  $lawcasesform->cases_level_approve as $approve )
                                        @php
                                                $sub_id =  App\Models\Basic\SubDepartment::where('did',  $approve->send_department )->select('sub_id');
                                                $user   =  App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->pluck('runrecno');
                                                if(!empty($user) && count($user) == 1){
                                                    $user_list =   App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->pluck('name','runrecno');
                                                }else{
                                                    $user_list =   App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->pluck('name','runrecno');
                                                }
                                        @endphp
                                    <tr  data-repeater-item>
                                        <td class="text-top text-center">
                                            <span class="td_approve_no">1</span>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('role',$role,!empty($approve->role)?$approve->role:null, ['class' => 'form-control role_approve', 'placeholder'=>'- เลือกกอง -', 'required' => true ]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('send_department',$department,!empty($approve->send_department)?$approve->send_department:null, ['class' => 'form-control send_department', 'placeholder'=>'- เลือกกอง -', 'required' => true]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!!  Form::select('authorize_userid',$user_list,!empty($approve->authorize_userid)?$approve->authorize_userid:null, ['class' => 'form-control authorize_userid_approve', 'placeholder'=>'- เลือกผู้มีอำนาจพิจารณา -', 'required' => true ]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::text('position',!empty($approve->position)?$approve->position:null , ['class' => 'form-control position_approve', 'required' => 'required']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center">
                                            <div class="form-group col-md-12">
                                                {!! Form::checkbox('acting', '1', !empty($approve->acting)?$approve->acting:null, ['data-color'=>'#13dafe' ,'class'=>'acting' ,'id'=>'acting']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center ">
                                            <div class="td_approve_remove">
                                                <button type="button" class="btn btn-danger btn-sm btn_approve_remove" >
                                                    <i class="fa fa-times"></i>
                                                </button> 
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach --}}
                                  @foreach(  $lawcasesform->cases_level_approve as $approve )
                                        @php
                                               $authorize_userids  =  !empty($UserDepartments[$approve->send_department]['role'][$approve->role]) ? $UserDepartments[$approve->send_department]['role'][$approve->role] : [];
                                        @endphp
                                    <tr  data-repeater-item>
                                        <td class="text-top text-center">
                                            <span class="td_approve_no">1</span>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('role',$role,!empty($approve->role)?$approve->role:null, ['class' => 'form-control role_approve', 'placeholder'=>'- เลือกกอง -', 'required' => true ]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('send_department',$department,!empty($approve->send_department)?$approve->send_department:null, ['class' => 'form-control send_department', 'placeholder'=>'- เลือกกอง -', 'required' => true]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                <select name="authorize_userid" class="form-control authorize_userid_approve" required>
                                                    <option value="">- เลือกผู้มีอำนาจพิจารณา -</option>
                                                    @if (count($authorize_userids) > 0)
                                                        @foreach ($authorize_userids as $authorize_userid)
                                                           @php
                                                               $selected = '';
                                                               if( $authorize_userid->id  == $approve->authorize_userid){
                                                                   $selected       = 'selected';
                                                               }
                                                           @endphp
                                                           <option value="{{ $authorize_userid->id }}" {{   $selected  }}  data-position="{{  $authorize_userid->position }}">
                                                                 {{ $authorize_userid->name }}
                                                           </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::text('position',!empty($approve->position)?$approve->position:null , ['class' => 'form-control position_approve', 'required' => 'required']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center">
                                            <div class="form-group col-md-12">
                                                {!! Form::checkbox('acting', '1', !empty($approve->acting)?$approve->acting:null, ['data-color'=>'#13dafe' ,'class'=>'acting' ,'id'=>'acting']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center ">
                                            <div class="td_approve_remove">
                                                <button type="button" class="btn btn-danger btn-sm btn_approve_remove" >
                                                    <i class="fa fa-times"></i>
                                                </button> 
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                        @else 

                               @foreach ($defaults as $default)
                                        @php
                                            if(in_array($default,['1'])){
                                                $authorize_userids =  !empty($UserDepartments['01']['role'][$default]) ? $UserDepartments['01']['role'][$default] : [];
                                                 $dids = '01';
                                            }else if(in_array($default,['2'])){
                                                 $dids = '02';
                                                $authorize_userids =  !empty($UserDepartments['02']['role'][$default]) ? $UserDepartments['02']['role'][$default] : [];
                                            }else{
                                                $authorize_userids =  !empty($UserDepartments[$did]['role'][$default]) ? $UserDepartments[$did]['role'][$default] : [];
                                                  $dids =  $did;
                    
                                            }
                                            
                                             
                                             $userid          =  count($authorize_userids) > 0 ? max(array_keys($authorize_userids))  : null;  
                                             $position = '';
                                        @endphp
                                    <tr  data-repeater-item>
                                        <td class="text-top text-center">
                                            <span class="td_approve_no">1</span>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('role',$role, $default, ['class' => 'form-control role_approve', 'placeholder'=>'- เลือกกอง -', 'required' => true ]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {!! Form::select('send_department',$department,$dids ?? null, ['class' => 'form-control send_department', 'placeholder'=>'- เลือกกอง -', 'required' => true]) !!}
                                            </div>
                                        </td>
                                        <td class="text-top">
                                            <div class="form-group col-md-12">
                                                {{-- {!!  Form::select('authorize_userid',$authorize_userid,$userid, ['class' => 'form-control authorize_userid_approve', 'placeholder'=>'- เลือกผู้มีอำนาจพิจารณา -', 'required' => true ]) !!} --}}
                                                 <select name="authorize_userid" class="form-control authorize_userid_approve" required>
                                                     <option value="">- เลือกผู้มีอำนาจพิจารณา -</option>
                                                     @if (count($authorize_userids) > 0)
                                                         @foreach ($authorize_userids as $authorize_userid)
                                                            @php
                                                  
                                                                $selected = '';
                                                                if( $authorize_userid->id  == $userid){
                                                                    $selected       = 'selected';
                                                                    $position       = $authorize_userid->position;
                                                                }
                                                            @endphp
                                                            <option value="{{ $authorize_userid->id }}" {{   $selected  }}  data-position="{{  $authorize_userid->position }}">
                                                                  {{ $authorize_userid->name }}
                                                            </option>
                                                         @endforeach
                                                     @endif
                                                 </select>
                                            </div>
                                        </td>
                                        <td class="text-top"> 
                                            <div class="form-group col-md-12">
                                                {!! Form::text('position',$position , ['class' => 'form-control position_approve', 'required' => 'required']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center">
                                            <div class="form-group col-md-12">
                                                {!! Form::checkbox('acting', '1', null, ['data-color'=>'#13dafe' ,'class'=>'acting' ,'id'=>'acting']) !!}
                                            </div>
                                        </td>
                                        <td class="text-top text-center ">
                                            <div class="td_approve_remove">
                                                <button type="button" class="btn btn-danger btn-sm btn_approve_remove" >
                                                    <i class="fa fa-times"></i>
                                                </button> 
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach    
                

     
                        @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-top text-center">
                            <button type="button" class="btn btn-success btn-sm btn_approve_add"  >
                                <i class="fa fa-plus"></i>
                            </button>  
                        </td>
                    </tr>
                </tfoot>
            </table>
    </div>
</div>

@push('js')
    <script>
          var user_departments    = JSON.parse('{!! json_encode($UserDepartments) !!}'); 
          var sub_departments     = JSON.parse('{!! json_encode($sub_departments) !!}'); 

        $(document).ready(function() {
           
            // ส่งถึง
             $(document).on('change', '.role_approve', function(event) {
                 set_authorize_userid($(this));
            });
            // ส่งเรื่องถึงกอง	
            $(document).on('change', '.send_department', function(event) {
                  set_authorize_userid($(this));
            });

             // ตำแหน่ง	
             $(document).on('change', '.authorize_userid_approve', function(event) {
                var row                       = $(this).closest('tr');
             var position_approve             = $(row).find('.position_approve');
                $(position_approve).val('');  

                let selected = $(this).find('option:selected');
                var position    = selected.data('position');

                if( checkNone(position)){
                    $(position_approve).val(position);  
                }
 
            });

           // ส่งถึง หน่วยงาน
            $(document).on('change', '#owner_depart_type', function(event) {
                    $('.btn_approve_remove:eq(1)').click();
                    $('.btn_approve_remove:eq(2)').click();
                    $('.btn_approve_remove:eq(3)').click();
                    set_repeater_approve();
            });
            $(document).on('click', '#clear_first_form', function(event) {
                    $('.btn_approve_remove:eq(1)').click();
                    $('.btn_approve_remove:eq(2)').click();
                    $('.btn_approve_remove:eq(3)').click();
                    set_repeater_approve();
            });

            // ส่งถึง ชื่อหน่วยงาน/กอง/กลุ่ม  ภายใน (สมอ.)
            $(document).on('change', '#owner_sub_department_id', function(event) {
                    $('.btn_approve_remove:eq(1)').click();
                    $('.btn_approve_remove:eq(2)').click();
                    $('.btn_approve_remove:eq(3)').click();
                    set_repeater_approve();
            });
             // ส่งถึง ชื่อหน่วยงาน/กอง/กลุ่ม  ภายนอก
            $(document).on('change', '#owner_basic_department_id', function(event) {
                    $('.btn_approve_remove:eq(1)').click();
                    $('.btn_approve_remove:eq(2)').click();
                    $('.btn_approve_remove:eq(3)').click();
                    set_repeater_approve();
            });
            
            
            // $(document).on('change', '.authorize_userid_approve', function(event) {
            //     if($(this).val()){
            //         var position = $(this).parent().parent().parent().find('.position_approve');
            //         $.ajax({
            //                 url: "{!! url('law/cases/forms/user_register/') !!}" + "/" + $(this).val()
            //             }).done(function( jsondata ) {
            //                 position.val('');
            //                 if(jsondata.position){
            //                     position.val(jsondata.position);
            //                 }
            //             });
            //     }

            // });

    
            // $(document).on('change', '.send_department', function(event) {
            //     var send_department = $(this).val();
            //     var authorize_userid = $(this).parent().parent().parent().find('select.authorize_userid_approve');
            //     var role = $(this).parent().parent().parent().find('select.role_approve').val();
            //     var position = $(this).parent().parent().parent().find('input.position_approve');

            //     var rows = $('#table_tbody_approve').children(); //แถวทั้งหมด
            //     var userids = [];
            //     if(rows.length > 0){
            //         rows.each(function(index, el) {
            //             var userid = $(el).find('select.authorize_userid_approve').val();
            //             if(checkNone(userid)){
            //                 userids.push(userid);

            //             }
            //         });    
            //     }
            
            //     authorize_userid.html('<option value=""> - เลือกผู้มีอำนาจพิจารณา - </option>');
            //     if(send_department!=""){//ดึงuser ตามกอง
            
            //         $.ajax({
            //             url: "{!! url('/law/funtion/get-user-departments') !!}" + "?id=" + send_department +"&role=" + role
            //         }).done(function( object ) {
            //             var runrecno = '';
            //             if(object.length == 1){//ถ้ามึคนเดียวหรือ role ตรง
            //                 $.each(object, function( index, data ) {

            //                     if($.inArray( String(data.runrecno), userids) == -1) {//ห้ามเลือกซ้ำ

            //                         authorize_userid.append('<option value="'+data.runrecno+'">'+data.name+'</option>');
            //                         runrecno = data.runrecno;
            //                     }  
            //                 });
            //                 reBuiltSelect2(authorize_userid);

            //             if(checkNone(runrecno)){
            //                     authorize_userid.val(runrecno).trigger('change.select2');
            //                 $.ajax({
            //                     url: "{!! url('law/cases/forms/user_register/') !!}" + "/" + runrecno
            //                 }).done(function( jsondata ) {
            //                     position.val('');
            //                     if(jsondata.position){
            //                         position.val(jsondata.position);
            //                     }
            //                 });
            //             }

            //            }else{
            //                 $.each(object, function( index, data ) {
            //                     // if(jQuery.inArray(String(data.runrecno), userids ) == -1) {
            //                         authorize_userid.append('<option value="'+data.runrecno+'">'+data.name+'</option>');

            //                     // }
            //                 });
            //                 reBuiltSelect2(authorize_userid);

            //            }
              
            //         });
            //     }
 
            // });
            
            $('.btn_approve_add').click(function(event) {
                var tbody = $('#table_tbody_approve');
                    // if( tbody.find('tr').length >= '4'){
                    if( tbody.find('.btn_approve_remove').length >= '4'){
                        Swal.fire({
                                                    position: 'center',
                                                    icon: 'info',
                                                    title: 'ไม่สามารถมอบผู้มีอำนาจพิจารณา',
                                                    html: 'มากกว่า 4 ผู้มีสิทธิ์พิจารณา',
                                                    showConfirmButton: true 
                                        });  
                        return false;
                    }
                
                    tbody.children('tr:last()').clone().appendTo('#table_tbody_approve');

                var row = $('#table_tbody_approve').children('tr:last()');
                    row.find('.acting').val('');
                    row.find('.acting').next().remove();
                    row.find('.acting').each(function() {
                        new Switchery($(this)[0], $(this).data());
                    });
                    reBuiltSelect2(row.find('select'));
                    OrderTdApproveNo();
            
            });

            $('body').on('click', '.btn_approve_remove', function(event) {
                $(this).parent().parent().parent().remove();
                OrderTdApproveNo();
            });
          
            
            $(".acting").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

    
            $('input[name="approve_type"]').on('ifChecked', function (event) {
                var approve_type = $('input[name="approve_type"]:checked').val();
                if( approve_type == '2'){ 
                    Swal.fire({
                        title: 'อนุมัติผ่านระบบแล้ว?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#808080',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonText: 'ยืนยัน',
                        reverseButtons: true
                    }).then((result) => {
                        console.log(result);
                        if (result.isConfirmed) {
                            ApproveTypeShowHide();
                        }else{
                            $('#approve_type_1').prop('checked', true);
                            $('#approve_type_1').iCheck('update');
                            $('#approve_type_2').prop('checked', false);
                            $('#approve_type_2').iCheck('update');
 
                        }
                    })
                }else{
                    // Swal.fire({
                    //     title: 'ผ่านการอนุมัตินอกระบบแล้ว?',
                    //     icon: 'warning',
                    //     showCancelButton: true,
                    //     confirmButtonColor: '#d33',
                    //     cancelButtonColor: '#808080',
                    //     cancelButtonText: 'ยกเลิก',
                    //     confirmButtonText: 'ยืนยัน',
                    //     reverseButtons: true
                    // }).then((result) => {
                    //     console.log(result);
                    //     if (result.isConfirmed) {
                            ApproveTypeShowHide();
                    //     }else{
                    //         $('#approve_type_2').prop('checked', true);
                    //         $('#approve_type_2').iCheck('update');
                    //         $('#approve_type_1').prop('checked', false);
                    //         $('#approve_type_1').iCheck('update');
                    //     }
                    // })
                }
                // ApproveTypeShowHide();
            });
            
            ApproveTypeShowHide();
            OrderTdApproveNo();
        });

        function ApproveTypeShowHide(){
            var checked = $('input[name="approve_type"]:checked').val();
            var repeater_approve = $('#div_repeater_approve');
            if( checked == '1'){ 
                repeater_approve.show(400);
                repeater_approve.find('select, input.position_approve').prop('required',true);
            }else{
                repeater_approve.hide(400);
                repeater_approve.find('select, input.position_approve').prop('required',false);
            }
        }

       function reBuiltSelect2(select){
            $(select).val('');
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }  

        function OrderTdApproveNo(){
            $('.repeater-form-approve').repeater();
            var approve_no = $('#table_tbody_approve').children();
                approve_no.each(function(index, el) {
                    $(el).children().first().html(index+1);
                });

            ($('.btn_approve_remove').length==1)?$('.btn_approve_remove').hide():$('.btn_approve_remove').show();
        }

        function  set_authorize_userid($this){
            var row                          = $($this).closest('tr');
            var role_approve                 = $(row).find('select.role_approve').val();
            var send_department              = $(row).find('select.send_department').val();
            var authorize_userid_approve     = $(row).find('select.authorize_userid_approve');
            var position_approve             = $(row).find('.position_approve');

                $(authorize_userid_approve).children('option[value!=""]').remove();  
            //   $(authorize_userid_approve).html('<option value=""> - เลือกผู้มีอำนาจพิจารณา - </option>');
                $(position_approve).val('');  
                if( checkNone(role_approve) &&  checkNone(send_department)){
                    if(checkNone(user_departments[send_department]['role']) && checkNone(user_departments[send_department]['role'][role_approve])){
                 
                        var object1 =  user_departments[send_department]['role'][role_approve];
              
                           $.each(object1, function( index, data ) {
                                 $(authorize_userid_approve).append('<option value="'+data.id+'"  data-position="'+data.position+'" >'+data.name+'</option>');
                            });
                           var key =  Object.keys(object1);
                              if(key.length == '1'){
                                var id   = Math.max(...key);
                                $(authorize_userid_approve).val(id);  
                                
                              }else{
                                $(authorize_userid_approve).val('');  
                              }
                    }        
                }
   
                $(authorize_userid_approve).prev().remove();
                $(authorize_userid_approve).removeAttr('style');
                $(authorize_userid_approve).select2();
                $(authorize_userid_approve).change();
                // reBuiltSelect2(authorize_userid_approve);
        }

        function set_repeater_approve(){
            var  depart_type                =   $('#owner_depart_type').val();
        
                $('.btn_approve_remove:eq(1)').click();
                $('.btn_approve_remove:eq(2)').click();
                $('.btn_approve_remove:eq(3)').click();

                $('select[name="repeater-approve[0][role]"]').val('').select2();
                $('select[name="repeater-approve[0][send_department]"]').val('').select2();
                $('select[name="repeater-approve[0][authorize_userid]"]').children('option[value!=""]').remove();  
                $('select[name="repeater-approve[0][authorize_userid]"]').val('').select2();
                $('input[name="repeater-approve[0][position]"]').val('');
                var  did        =  '';
            if(checkNone(depart_type)){
                if(depart_type == '1'){  // ภายใน (สมอ.)  
                    var   defaults = [ '6', '5','2','1' ];   
                    var  sub_department_id        =   $('#owner_sub_department_id').val();
                    if(checkNone(sub_department_id)){
                          did  = sub_departments[sub_department_id];
                    }
                }else{   // ภายนอก
                      var   defaults = [ '2', '1'];   
                      var  did       = '{{$did}}';
                }
                if(checkNone(did)){
                      $.each(defaults, function( index, data ) {
                  
                                if(index >= 1){
                                    $('.btn_approve_add').click(); 
                                }
                                var authorize_userid_approve  =  $('select[name="repeater-approve['+index+'][authorize_userid]"]');
                                 $(authorize_userid_approve).children('option[value!=""]').remove();  
                                $('input[name="repeater-approve['+index+'][position]"]').val('');  
                                $('select[name="repeater-approve['+index+'][role]"]').val(data).select2();
                              
                                var role_approve        =  data;
                                var send_department     =  did;
                     
                                if( checkNone(role_approve) &&  checkNone(send_department)){
                                    if(checkNone(user_departments[send_department]['role']) && checkNone(user_departments[send_department]['role'][role_approve])){
                                
                                        
                                            if(role_approve == '1'){
                                               var object1 =  user_departments['01']['role'][role_approve];
                                               $('select[name="repeater-approve['+index+'][send_department]"]').val('01').select2();
                                            }else if(role_approve == '2'){
                                                var object1 =  user_departments['02']['role'][role_approve];
                                                $('select[name="repeater-approve['+index+'][send_department]"]').val('02').select2();
                                            }else{
                                                var object1 =  user_departments[send_department]['role'][role_approve];
                                                $('select[name="repeater-approve['+index+'][send_department]"]').val(send_department).select2();
                                            }
                            
                                        $.each(object1, function( index, data ) {
                                                $(authorize_userid_approve).append('<option value="'+data.id+'"  data-position="'+data.position+'" >'+data.name+'</option>');
                                            });
                                        var key =  Object.keys(object1);
                                            if(key.length == '1'){
                                                var id   = Math.max(...key);
                                                $(authorize_userid_approve).val(id);  
                                                
                                            }else{
                                                $(authorize_userid_approve).val('');  
                                            }
                
                                    } 
                                    $(authorize_userid_approve).prev().remove();
                                    $(authorize_userid_approve).removeAttr('style');
                                    $(authorize_userid_approve).select2();
                                    $(authorize_userid_approve).change();   
                                }
                                
                        }); 
                }
                     
            }


        }
        
        
    </script>
@endpush


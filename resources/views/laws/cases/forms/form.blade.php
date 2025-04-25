@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" src="{{asset('plugins/components/sweet-alert2/sweetalert2.min.css')}}">
<style>
    .div_dotted {
        border-bottom: 1px dotted #000;
        padding: 0 0 5px 0;
    }
    .div-show{
        display: block;
    }
    .div-hide{
        display: none;
    }
    .text-orange{
        color: #FFA500
    }
    .fa-exclamation-circle{
        cursor: pointer;
    }
    .label-height{
        line-height: 16px;
    }

    .font_size{
            font-size: 10px;
            color: #ccc;
    }
    .swal2-popup {
        font-size: 1.5rem !important;
        font-family: Georgia, serif;
    }

</style>
@endpush

@php

    $arr_depart_type = ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'];

    if( !isset($lawcasesform->id) ){

        $user = auth()->user();

        $lawcasesform                          = new App\Models\Law\Cases\LawCasesForm;
        //ประเภทหน่วยงาน
        $lawcasesform->owner_depart_type       = !empty( $user->subdepart->sub_id )?1:2;

        //ข้าพเจ้า
        $lawcasesform->owner_name              = !empty( $user->reg_fname )?($user->reg_fname.' '.$user->reg_lname):null;
        //เลขประจำตัวผู้เสียภาษี
        $lawcasesform->owner_taxid             = !empty( $user->reg_13ID )?$user->reg_13ID:null;
        //อีเมล
        $lawcasesform->owner_email             = !empty( $user->reg_email )?$user->reg_email:null;
        //เบอร์มือถือ
        $lawcasesform->owner_tel               = !empty( $user->reg_wphone )?$user->reg_wphone:null;
        //เบอร์โทร
        $lawcasesform->owner_phone             = !empty( $user->reg_phone )?$user->reg_phone:null;

        //---------ติดต่อประสานงาน---------

        //ชื่อ-สกุล
        $lawcasesform->owner_contact_name     = !empty( $user->reg_fname )?($user->reg_fname.' '.$user->reg_lname):null;
        //อีเมล
        $lawcasesform->owner_contact_email    = !empty( $user->reg_email )?$user->reg_email:null;
        //เบอร์มือถือ
        $lawcasesform->owner_contact_phone    = !empty( $user->reg_phone )?$user->reg_phone:null;

        $lawcasesform->owner_case_by          = !empty( $user->getKey() )?$user->getKey():null;


         //---------ส่วนที่ 3 : รายการผลิตภัณฑ์ตรวจยึด-อายัด (ของกลาง)---------

        // แสดงผลิตภัณฑ์เดียวกันส่วนที่ 2
          $lawcasesform->same_product            = '1';
        
    }

    $lawcase_status = !empty($lawcasesform->id)?$lawcasesform->StatusText:'';
     
@endphp

<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 1: ข้อมูลเจ้าของคดี(ผู้แจ้ง)</h3>
               
            </legend>
            <!-- ผู้แจ้ง -->
            @include('laws.cases.forms.form.infomation')

        </fieldset>
    </div>
    <div class="col-md-4">
        <div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!! $lawcase_status !!} </div>

        <fieldset class="white-box">
            @include('laws.cases.forms.form.status')
        </fieldset>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 2 : ข้อมูลผู้ต้องหา/ผู้กระทำความผิด</h3>
            </legend>
            <br>

            {{-- กรณีชื่อเรียกผ่าน api ยังไม่เปิดให้ใช้ --}}
            {{-- @include('laws.cases.forms.form.offend-api') --}}

            @include('laws.cases.forms.form.offend')
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 3 : รายการผลิตภัณฑ์ตรวจยึด-อายัด (ของกลาง)</h3>
            </legend>
            <br>

            @include('laws.cases.forms.form.product')
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี</h3>
            </legend>
            <br>

            @include('laws.cases.forms.form.staff')
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 5 : ไฟล์แนบ/หลักฐาน</h3>
            </legend>
            <br>

            @include('laws.cases.forms.form.evidence')
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ส่วนที่ 6 : การพิจารณา</h3>
            </legend>
            <br>

            @include('laws.cases.forms.form.level-approve')
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="form-group">
            <div class="col-sm-4 text-right">ท่านต้องการรับอีเมลแจ้งเตือนเกี่ยวกับงานคดีไปยัง<br><small class="text-muted m-b-30 font-12"><i>(กรณีที่นิติกรดำเนินการในส่วนที่เกี่ยวข้องระบบจะแจ้งเตือนผ่านอีเมล)</i></small></div>
            <div class="col-sm-6">
                <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                    <div class="col-md-7">
                        <input type="checkbox" class="check item_checkbox" id="access" value="1" name="notify_email_type[]" data-checkbox="icheckbox_square-green" @if(!empty($lawcasesform->notify_email_type) && in_array(1, $lawcasesform->notify_email_type)) checked @endif>
                        <label for="access">เจ้าของคดี</label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                    <div class="col-md-7">
                        <input type="checkbox" class="check item_checkbox" id="access-2" value="2" name="notify_email_type[]" data-checkbox="icheckbox_square-green" @if(!empty($lawcasesform->notify_email_type) && in_array(2, $lawcasesform->notify_email_type)) checked @endif>
                        <label for="access-2">ผู้ประสานงาน</label>
                    </div>
                </div>
            </div>
        </div>
 

        <div class="form-group">
            {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-5">
                {!! Form::text('created_by_show', !empty($lawcasesform->created_by)? $lawcasesform->CreatedName  : auth()->user()->Fullname  , ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-5">
                {!! Form::text('created_by_show', !empty($lawcasesform->updated_at)? HP::DateTimeThaiAndTime($lawcasesform->updated_at):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>


    <div class="form-group ">
        <div class="col-md-offset-3 col-md-6">
            <button class="btn btn-primary" type="button" name="submit_type" value="1" id="btn_submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            <button class="btn btn-success" type="text" name="submit_type" value="2">
                <i class="fa fa-clipboard" aria-hidden="true"></i> ฉบับร่าง
            </button>
            @can('view-'.str_slug('law-cases-forms'))
            <a class="btn btn-default show_tag_a" href="{{ url('/law/cases/forms') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
            @endcan
        </div>
    </div>

</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>

    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2@11.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

    <script>
        var table_staff = ''; 
        var confirm_submit = false;
        $(document).ready(function() {

               @if (isset($lawcasesform->id))
            //             $('#foreign').prop('disabled', true);
            //             $('#offend_taxid').prop('disabled', true);
            //             $('#offend_name').prop('disabled', true);
            //             $('#offend_ref_no').prop('disabled', true);
            //             $('.check-readonly').prop('disabled', true); 
            //             $('.check-readonly').parent().removeClass('disabled');
            //             $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
                        table_tbody_license_numbers();
               @endif 

               
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            $('#offend_license_notify').on('ifChecked', function (event) {
                LoadFileRequired();
            });

            $('#offend_license_notify').on('ifUnchecked', function (event) {
                LoadFileRequired();
            });

            LoadFileRequired();

            
            // $('#btn_submit').on('click', function (event) {
            //     confirm_submit();
            //    $('#Casesform').submit();  
            // });

      $('button[name="submit_type"]').on('click', function (event) {
            if($(this).val() == '2'){
                $('#Casesform').find('input, select, textarea').prop('required',false);
                confirm_submit = true;
                $('#Casesform').submit();
            }else{
                $('#Casesform').submit();  
            }

      
        });
   
        $('#Casesform').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
           
                    if($('#myTable-staff tbody').find('.reward_group_danger').length > 0){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'กรุณาตรวจสอบ/แก้ไข',
                                    html: 'ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี '+$('#myTable-staff tbody').find('.reward_group_danger').length+' รายการ',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                $('select[name="myTable-staff_length"]').val('10');
                                $('select[name="myTable-staff_length"]').change();
                    }else{
                         if(confirm_submit == false){
                            Swal.fire({
                                title: 'ยืนยันการส่งงานคดีใช่หรือไม่?',
                                html: "หากท่านยืนยันส่งงานคดีแล้วจะไม่สามารถแก้ไขได้ <br> จนกว่านิติกรตรวจสอบงานคดีแล้วขอข้อมูลเพิ่มเติม (ตีกลับ) !",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#1E90FF',
                                cancelButtonColor: '#808080',
                                cancelButtonText: 'ยกเลิก',
                                confirmButtonText: 'ยืนยัน',
                                reverseButtons: true,
                                width: '500px'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    confirm_submit = true;
                                     //show data table all
                                    table_staff.search('').columns().search('').draw(); //clear ตัวค้นหา รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
                                    $('select[name="myTable-staff_length"]').val('-1');
                                    $('select[name="myTable-staff_length"]').change();

                                    $('.staff-repeater').repeater();
                                 
                                    $('form').find('button, input[type=button], input[type=submit], input[type=reset]').prop('disabled', true);
                                    $('form').find('a').removeAttr('href');
                                    $('#Casesform').submit();  
                                }
                            })
                         }
                          
                    }
                    console.log(confirm_submit);
                    return confirm_submit;
                  
                // var table_section = $('#table_tbody_section tr').length;


                // var checked = $('input[name="offend_license_type"]:checked').val();
                // var license_number = $('.license_number:checked').length;
                // if( checked == '1' && license_number == '0'){ 
                //     Swal.fire({
                //         position: 'center',
                //         icon: 'error',
                //         title: 'กรุณาเพิ่ม ส่วนที่ 2 : เลขที่ใบอนุญาต  อย่างน้อย 1 รายการ',
                //         showConfirmButton: false,
                //         timer: 2000
                //     });
                //     return false;
                // } 

                // var tisnos = $('#table_tbody_tisno').children(); //แถวทั้งหมด
                // if(tisnos.length==0){
                //     Swal.fire({
                //         position: 'center',
                //         icon: 'error',
                //         title: 'กรุณาเพิ่ม ส่วนที่ 2 : มาตรฐานผลิตภัณฑ์อุตสาหกรรม  อย่างน้อย 1 รายการ',
                //         showConfirmButton: false,
                //         timer: 2000
                //     });
                //     return false;
                // }
                
                // if(!checkNone($('#law_basic_section_id').val())){
                //     Swal.fire({
                //         position: 'center',
                //         icon: 'error',
                //         title: 'กรุณาเพิ่ม ส่วนที่ 2 : ฝ่าฝืนตามมาตรา  อย่างน้อย 1 รายการ',
                //         showConfirmButton: false,
                //         timer: 2000
                //     });
                //     return false;
                // }
       
                // if(isEmpty){
                //     Swal.fire({
                //         position: 'center',
                //         icon: 'error',
                //         title: 'กรุณาเพิ่ม ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี  อย่างน้อย 1 รายการ',
                //         showConfirmButton: false,
                //         timer: 2000
                //     });
                //     return false;
                // }

                // var tbody_approve = $('#table_tbody_approve').children(); //แถวทั้งหมด            
                // var approve_type = $('input[name="approve_type"]:checked').val();

                // if(approve_type==1 && tbody_approve.length==0){
                //     Swal.fire({
                //         position: 'center',
                //         icon: 'error',
                //         title: 'กรุณาเพิ่ม ส่วนที่ 6 : การพิจารณา  อย่างน้อย 1 รายการ',
                //         showConfirmButton: false,
                //         timer: 2000
                //     });
                //     return false;
                // }
                // table_staff.search('').columns().search('').draw(); //clear ตัวค้นหา รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
                // $('select[name="myTable-staff_length"]').val('-1');
                // $('select[name="myTable-staff_length"]').change();

                // $('.staff-repeater').repeater();

                // $('form').find('button, input[type=button], input[type=submit], input[type=reset]').prop('disabled', true);
                // $('form').find('a').removeAttr('href');
            

            }); 

        });

        function LoadFileRequired(){

            let checked = $('#offend_license_notify').prop('checked');

            let label   =  $("label:contains('หนังสือแจ้งเตือนก่อนพักใช้ใบอนุญาต')");

            if( checkNone(label) ){

                var row = label.closest('.form-group');

                if (checked == true ){
                    label.addClass('required');
                    row.find('input[type="file"]').prop('required',true);

                }else{
                    label.removeClass('required');
                    row.find('input[type="file"]').prop('required',false);
                }
            }

        }

        function  addCommas(nStr, decimal){
            var tmp='';
            var zero = '0';

            nStr += '';
            x = nStr.split('.');

            if((x.length-1) >= 1){//ถ้ามีทศนิยม
                if(x[1].length > decimal){//ถ้าหากหลักของทศนิยมเกินที่กำหนดไว้ ตัดให้เหลือเท่าที่กำหนดไว้
                    x[1] = x[1].substring(0, decimal);
                }else if(x[1].length < decimal){//ถ้าหากหลักของทศนิยมน้อยกว่าที่กำหนดไว้ เพิ่ม 0
                    x[1] = x[1] + zero.repeat(decimal-x[1].length);
                }
                tmp = '.'+x[1];
            }else{//ถ้าไม่มีทศนิยม
                if(parseInt(decimal)>0){//ถ้ามีการกำหนดให้มี ทศนิยม
                    tmp = '.'+zero.repeat(decimal);
                }
            }
            x1 = x[0];
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1+tmp;
        }

        function IsNumber() {
            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".amount_date").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 
        }

        function RemoveCommas(str) {
            var res = str.replace(/[^\d\.\-\ ]/g, '');
            return   res;
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }

        
        function confirm_submit() {
            table_staff.search('').columns().search('').draw(); //clear ตัวค้นหา รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
            $('select[name="myTable-staff_length"]').val('-1');
            $('select[name="myTable-staff_length"]').change();
             if($('#myTable-staff tbody').find('.reward_group_danger').length > 0){
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'กรุณาตรวจสอบ/แก้ไข',
                            html: 'ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี '+$('#myTable-staff tbody').find('.reward_group_danger').length+' รายการ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        $('select[name="myTable-staff_length"]').val('10');
                        $('select[name="myTable-staff_length"]').change();
             }else{
                    Swal.fire({
                        title: 'ยืนยันการส่งงานคดีใช่หรือไม่?',
                        html: "หากท่านยืนยันส่งงานคดีแล้วจะไม่สามารถแก้ไขได้ <br> จนกว่านิติกรตรวจสอบงานคดีแล้วขอข้อมูลเพิ่มเติม (ตีกลับ) !",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1E90FF',
                        cancelButtonColor: '#808080',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonText: 'ยืนยัน',
                        reverseButtons: true,
                        width: '500px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#Casesform').submit();  
                        }
                    })
             }

        }

    </script>
@endpush

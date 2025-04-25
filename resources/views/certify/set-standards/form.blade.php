@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .design-process-section .text-align-center {
            line-height: 25px;
            margin-bottom: 12px;
        }
        .design-process-content {
            border: 1px solid #e9e9e9;
            position: relative;
            padding: 16px 34% 30px 30px;
        }
        .design-process-content img {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            max-height: 100%;
        }
        .design-process-content h3 {
            margin-bottom: 16px;
        }
        .design-process-content p {
            line-height: 26px;
            margin-bottom: 12px;
        } 
        .process-model {
            list-style: none;
            padding: 0;
            position: relative;
            /* max-width: 800px; */
            margin: 20px auto 26px;
            border: none;
            z-index: 0;
        }
        .process-model li::after {
            background: #e5e5e5 none repeat scroll 0 0;
            bottom: 0;
            content: "";
            display: block;
            height: 4px;
            margin: 0 auto;
            position: absolute;
            right: -150px;
            top: 33px;
            width: 100%;
            z-index: -1;
        }
        .process-model li.visited::after {
            background: #0283cc;
        }
        .process-model li:last-child::after {
            width: 0;
        }
        .process-model li {
            display: inline-block;
            width: 20%;
            text-align: center;
            float: none;
        }
        .nav-tabs.process-model > li.active > a, .nav-tabs.process-model > li.active > a:hover, .nav-tabs.process-model > li.active > a:focus, .process-model li a:hover, .process-model li a:focus {
            border: none;
            background: transparent;

        }
        .process-model li a {
            padding: 0;
            border: none;
            color: #606060;
        }
        .process-model li.active,
        .process-model li.visited {
            color: #0283cc;
        }
        .process-model li.active a,
        .process-model li.active a:hover,
        .process-model li.active a:focus,
        .process-model li.visited a,
        .process-model li.visited a:hover,
        .process-model li.visited a:focus {
            color: #0283cc;
        }
        .process-model li.active p,
        .process-model li.visited p {
            font-weight: 600;
        }
        .process-model li span {
            display: block;
            height: 68px;
            width: 68px;
            text-align: center;
            margin: 0 auto;
            background: #f5f6f7;
            border: 2px solid #e5e5e5;
            line-height: 65px;
            font-size: 30px;
            border-radius: 50%;
        }
        .process-model li.active span, .process-model li.visited span  {
            color: #fff;
            background-color: #0283cc;
            border-color: #0283cc;
            border: 2px solid #0283cc;
        }
        .process-model li p {
            font-size: 14px;
            margin-top: 11px;
        }
        .process-model.contact-us-tab li.visited a, .process-model.contact-us-tab li.visited p {
            color: #606060!important;
            font-weight: normal
        }
        .process-model.contact-us-tab li::after  {
            display: none;
        }
        .process-model.contact-us-tab li.visited i {
            border-color: #e5e5e5;
        }
 

        .input-show {
            height: 27px;
            padding: 3px 7px;
           /*font-size: 15px;*/
            line-height: 1.5;
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
        }
        .input-show[disabled]{
            background-color: #FFFFFF;
        }
        .text-top{
            vertical-align: top !important;
        }
 
 
    </style>
@endpush
 
      <div class="row">
        <div class="col-md-12 text-center">
 
          <ul class="nav nav-tabs process-model more-icon-preocess" role="tablist">
            <li role="presentation">
               <a id="1" href="#plan_standards" aria-controls="plan_standards" role="tab" data-toggle="tab" 
                @if(isset($setstandard) && $setstandard->status_id == 0) class="active"@endif
                >
                     <span>1</span>
                     <p>รายละเอียดการกำหนดมาตรฐาน</p>
                 </a>
            </li>
            <li role="presentation" >
                <a id="2" href="#plan_time"  aria-controls="plan_time"   role="tab" data-toggle="tab" 
                 @if(isset($setstandard) && $setstandard->status_id == 1) class="active"@endif
                >
                    <span>2</span>
                    <p>แผนการกำหนดมาตรฐาน</p>
              </a>
            </li>
            <li role="presentation">
                <a  id="3" href="#meet_topic" aria-controls="meet_topic" role="tab" data-toggle="tab" 
                @if(isset($setstandard) &&  in_array($setstandard->status_id,[2,3])) class="active"@endif
                >
                    <span>3</span>
                    <p>วาระการประชุม</p>
                </a>
            </li>   
            <li role="presentation">
                <a  id="4" href="#summarize" aria-controls="summarize" role="tab" data-toggle="tab" 
                @if(isset($setstandard) && in_array($setstandard->status_id,[4,5])) class="active"@endif
                >
                    <span>4</span>
                    <p>สรุปวาระการประชุม</p>
                </a>
            </li>
          </ul>

      </div>
    </div>
 
<div class="col-md-12" id="boxs_readonly"   >
    {!! Form::hidden('step_state',null,['id'=>'step_state']) !!}
    <!-- Tab panes -->
    <div class="tab-content">

    <div role="tabpanel" class="tab-pane " id="plan_standards">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                   @include ('certify.set-standards.form-plan-standards')
              </div>
         </div>
        </div>
    </div>
           
    <div role="tabpanel" class="tab-pane" id="plan_time">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    @include ('certify.set-standards.form-plan-time')
              </div>
         </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="meet_topic">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    @include ('certify.set-standards.form-meet-topic')
              </div>
         </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="summarize">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    @include ('certify.set-standards.form-report-meet')
              </div>
         </div>
        </div>
    </div>

    
    </div>
</div>

@if (!empty($setstandard->condition) && $setstandard->condition == 'form_edit')
        @if (in_array($setstandard->status_id,[2,3]))
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn btn-success " type="submit" @if(!empty($meetingstandards) &&  count($meetingstandards->where('status',1)) == 0) disabled @endif name="is_update" value="1">
                    <i class="fa fa-paper-plane"></i> อัพเดทข้อมูล
                </button>
                <button class="btn btn-primary " type="submit"     @if(!empty($meetingstandards) &&  count($meetingstandards->where('status',1)) == 0) disabled @endif name="is_save" value="1">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <a class="btn btn-default" href="{{ url('/certify/set-standards') }}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            </div>
        </div>
        @elseif (in_array($setstandard->status_id,[5]))
 
          <div class="form-group">
                <div class=" col-md-12">
                    <a class="btn btn-default btn-block" href="{{ url('/certify/set-standards') }}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>  
            </div>
          </div>
        @else   
            <div class="form-group">
                <div class="col-md-offset-4 col-md-4">
                        <button class="btn btn-success" type="submit" name="is_update" value="1">
                            <i class="fa fa-paper-plane"></i> อัพเดทข้อมูล
                        </button>
                        <button class="btn btn-primary" type="submit" name="is_save" value="1">
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                        <a class="btn btn-default" href="{{ url('/certify/set-standards') }}">
                            <i class="fa fa-rotate-left"></i> ยกเลิก
                        </a>  
                </div>
            </div>
        @endif
@endif


 
@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
 
  <script>
    $(document).ready(function () {

        @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
            });
       @endif


          $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 400);
                    }
                }
            });
            BtnDeleteFile();
            $('.repeater-form-file4').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add4').remove();
                    BtnDeleteFile4();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                            BtnDeleteFile4();
                        }, 500);
                    }
                }
            });
            BtnDeleteFile4();

            //ช่วงวันที่
            $('.date-range').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
            });

          // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
               $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                        return false;
                    }
                });
                IsInputNumber();

            $(".plan_date, .period").on("change",function(e){
                let plan_startdate = $('input[name="plan_startdate"]').val();
                let period = $('input[name="period"]').val();
                if(!!plan_startdate && !!period){
                    $('input[name="plan_enddate"]').val(addMonth(plan_startdate, period));
                }else{
                    $('input[name="plan_enddate"]').val(plan_startdate);
                }
            });

            $(".period").on("keyup",function(e){
                let plan_startdate = $('input[name="plan_startdate"]').val();
                let period = $('input[name="period"]').val();
                if(!!plan_startdate && !!period){
                    $('input[name="plan_enddate"]').val(addMonth(plan_startdate, period));
                }else{
                    $('input[name="plan_enddate"]').val(plan_startdate);
                }
            });
                
            $('.start_std_check').prop('disabled', true);
            $('.start_std_check').parent().removeClass('disabled');
            $('.start_std_check').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});

           var status =    "{{!empty($setstandard->status_id) ? $setstandard->status_id : 0 }}";
           var condition =    "{{!empty($setstandard->condition) ? $setstandard->condition : 0 }}";

        if(condition == 'form_edit'){
            if(status == 0){
                $('#plan_standards,#plan_time,#meet_topic,#summarize').find('input:not(#projectid), select, textarea').attr('disabled', false);
                $('#2').removeAttr('href');
                $('#2').prop('disabled', true);
                $('#tis_number,#tis_book,#tis_year').prop('required', false);
                $('#tis_book').prop('required', false);
                $('#plan_startdate,#plan_enddate,#period,#budget,#plan_time').prop('required', false);
                $('#detail').prop('required', false);
                $('#3').removeAttr('href');
                $('#3').prop('disabled', true);
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#step_state').val('1');
                $('#2,#3,#4').css({"cursor": "not-allowed"});
           }else if(status == 1){
                // $('#plan_standards').find('input, select, textarea').attr('disabled', true);
                $('#tis_number,#tis_book,#tis_year').prop('required', false);
                $('#plan_startdate,#plan_enddate,#period,#budget,#plan_time').prop('required', true);
                $('#detail').prop('required', false);
                $('#3').removeAttr('href');
                $('#3').prop('disabled', true);
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#step_state').val('2');
                $('#3,#4').css({"cursor": "not-allowed"});
                $('.repeater_form_file').remove();
           }else if(status == 2 || status == 3){
               $('#plan_time').find('input, select, textarea').attr('disabled', true);
                $('#tis_number,#tis_book,#tis_year').prop('required', false);
                $('#plan_startdate,#plan_enddate,#period,#budget,#plan_time').prop('required', false);
                $('#detail').prop('required', false);
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#step_state').val('3');
                $('#4').css({"cursor": "not-allowed"});
                $('.repeater_form_file').remove();
           }else if(status == 4 ){
                $('#plan_time,#meet_topic').find('input, select, textarea').attr('disabled', true);
                $('#tis_number,#tis_book,#tis_year').prop('required', false);
                $('#plan_startdate,#plan_enddate,#period,#budget,#plan_time').prop('required', false);
                $('#detail').prop('required', true);
                $('#step_state').val('4');
                // $('#1,#2,#3').css({"cursor": "not-allowed"});
                $('.repeater_form_file').remove();
           }else if( status == 5){
                $('#plan_standards,#plan_time,#meet_topic,#summarize').find('input, select, textarea').attr('disabled', true);
                // $('#1').removeAttr('href');
                // $('#1').prop('disabled', true);
                $('#tis_number,#tis_book,#tis_year').prop('required', false);
                $('#plan_startdate,#plan_enddate,#period,#budget,#plan_time').prop('required', false);
                $('#detail').prop('required', true);
                // $('#2').removeAttr('href');
                // $('#2').prop('disabled', true);
                // $('#3').removeAttr('href');
                // $('#3').prop('disabled', true);
                $('#step_state').val('4');
                // $('#1,#2,#3').css({"cursor": "not-allowed"});
                $('.repeater_form_file').remove();
                $('.repeater_form_file4').remove();
                $('#detail').prop('disabled', true);

                
           }
 
 
        }      
        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }
              $('.btn_file_remove:first').hide();   
              $('.btn_file_add:first').show();
              check_name_file();
        }
        function BtnDeleteFile4(){
            if( $('.btn_file_remove4').length >= 2 ){
                $('.btn_file_remove4').show();
            } 
              $('.btn_file_remove4:first').hide();   
              $('.btn_file_add4:first').show();   
              check_name_file();

        }
        
             check_name_file();



            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                format: 'dd/mm/yyyy',
            });

            //จัดการข้อมูลในฟอร์ม
            // $('.request-form').find('input, select, textarea').attr('disabled', true);
            // $('.request-form').find('button,.form-action, #cancel').remove();
            // $('.request-form').find('input, select, textarea').attr('required', false);

                ShowTabActive('a.active');
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                ShowTabActive(this);
             });

              function check_name_file(){
 
                $('input[type="file"]').change(function(){
                    var file_event = this;
                    setTimeout(function(){
                        var spantext = $(file_event).parent().parent().parent().find('span.fileinput-filename');
                        var result = spantext.text().slice(0,10);
                        result = result + "...";
                        spantext.text(result);
                    }, 500)
                });

            }
          



    });

    function ShowTabActive(element){
         
         var href = $(element).attr('href');
         var $curr = $(".process-model  a[href='" + href + "']").parent();

             $('.process-model li').removeClass();

             $curr.addClass("active");
             $curr.prevAll().addClass("visited");
            //  $('#plan_standards, #plan_time, #meet_topic').find('input, select ,hidden').prop('disabled',true);
            //  $(href).find('input, select ,hidden').prop('disabled',false);
             $(href).addClass('active');
     }

     function IsInputNumber() {
                   // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                   String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                   };

                   var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน
                    var s_inum=new String(inum);
                    var num2=s_inum.split(".");
                    var n_inum="";
                    if(num2[0]!=undefined){
                   var l_inum=num2[0].length;
                   for(i=0;i<l_inum;i++){
                    if(parseInt(l_inum-i)%3==0){
                   if(i==0){
                    n_inum+=s_inum.charAt(i);
                   }else{
                    n_inum+=","+s_inum.charAt(i);
                   }
                    }else{
                   n_inum+=s_inum.charAt(i);
                    }
                   }
                    }else{
                   n_inum=inum;
                    }
                    if(num2[1]!=undefined){
                   n_inum+="."+num2[1];
                    }
                    return n_inum;
                   }
                   // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                   $(".amount").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   });

                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ
                   $(".amount").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                               if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                           thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }else{ // ถ้าไม่มีคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                   });
         }

        function daysdifference(firstDate, secondDate){ // หาจำนวณวันระหว่างวันที่ (ตย. 21/02/2566 ถึง 23/02/2566 = 2 วัน)
            if(validateDate(firstDate) && validateDate(secondDate)){
                let startDay = new Date(convertDate(firstDate));
                let endDay = new Date(convertDate(secondDate));
            
                let millisBetween = startDay.getTime() - endDay.getTime();
                let days = millisBetween / (1000 * 3600 * 24);
            
                return Math.round(Math.abs(days));
            }
        }

        function addMonth(firstDate, month){ // เพิ่มเดือน (ตย. 21/02/2566 เพิ่ม 2 เดือน = 21/04/2566)
            if(validateDate(firstDate)){
                let date = new Date(convertDate(firstDate));
                return formatDateThString(new Date(date.setMonth(date.getMonth()+parseInt(month))));
            }
        }
        
        function validateDate(date) { // เช็คฟอร์แมทวันที่ (ตย. 21/02/2566)
            let date_regex = /^\d{2}\/\d{2}\/\d{4}$/;
            return date_regex.test(date);
        }
        
        function convertDate(date) { // แปลงวันที่ (ตย. 21/02/2566 เป็น 2023-02-21)
            let date_sp = date.split('/');
            let d = date_sp[0];
            let m = date_sp[1];
            let y = (parseInt(date_sp[2]) > 543)?(parseInt(date_sp[2])-543):(new Date()).getFullYear();
            return y+'-'+m+'-'+d
        }

        function formatDateThString(date){ // แปลงวันที่รูปแบบ 15/06/2565
            if(!!date){
                var options = {   
                    day: 'numeric',
                    month: '2-digit', 
                    year: 'numeric'
                };
                return date.toLocaleDateString('th-TH', options);
            }
        }




</script>
@endpush

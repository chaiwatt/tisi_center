@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />


<style type="text/css">
a:hover,a:focus{
    outline: none;
    text-decoration: none;
}
.tab .nav-tabs{
    background: #fff;
}
.tab .nav-tabs li{
    text-align: center;
    margin-right: 3px;
}
.tab .nav-tabs li a{
    font-size: 15px;
    font-weight: 600;
    color: #22272c;
    padding: 15px 25px;
    background: #eee;
    margin-right: 0;
    border-radius: 0;
    border: none;
    text-transform: uppercase;
    position: relative;
    transition: all 0.5s ease 0s;
}
.tab .nav-tabs li.active a,
.tab .nav-tabs li a:hover{
    background: #e16b47;
    color: #fff;
    border: none;
}
.tab .tab-content{
    font-size: 15px;
    color: #3d3537;
    line-height: 30px;
    padding: 30px 40px;
    border: 3px solid #e16b47;
}
.tab .tab-content h3{
    font-size: 20px;
    font-weight: bold;
    margin-top: 0;
}
@media only screen and (max-width: 480px){
    .tab .nav-tabs li{ width: 100%; }
}
.not-allowed {cursor: not-allowed;}

.disabled {
    pointer-events:none;
    opacity:0.6;    
}
</style>


@endpush

 
  <div class="row">
  
          <div class="tab" role="tabpanel">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation"    class="active"><a  id="1" href="#mor" aria-controls="mor" role="tab" data-toggle="tab" >มอก.</a></li>
                  @if (isset($set_standard))
                        <li role="presentation" ><a id="2"  href="#plan" aria-controls="plan" role="tab" data-toggle="tab">แผน</a></li>
                        <li role="presentation"><a  id="3"  href="#result" aria-controls="result" role="tab" data-toggle="tab">ผล</a></li>
                   @else
                        <li role="presentation" class="disabled not-allowed"><a id="2" class="not-allowed"   aria-controls="plan" role="tab" data-toggle="tab">แผน</a></li>
                        <li role="presentation"   class="disabled not-allowed"><a  id="3" class="not-allowed"    aria-controls="result" role="tab" data-toggle="tab">ผล</a></li>
                  @endif
              </ul>
              <!-- Tab panes -->
              <div class="tab-content tabs">
                  <div role="tabpanel" class="tab-pane fade in active" id="mor">
                         @if (isset($set_standard))           
                                {!! Form::model($set_standard, [
                                    'method' => 'PATCH',
                                    'url' => ['/tis/set_standard', $set_standard->id],
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'id' => 'cost_form'
                                ]) !!}
                                     @include('tis.set_standard.mor')
                               {!! Form::close() !!}
                         @else
                              {!! Form::open(['url' => '/tis/set_standard', 'class' => 'form-horizontal', 'files' => true]) !!}
                                 @include('tis.set_standard.mor')
                             {!! Form::close() !!}
                         @endif
                   
                  </div>
                  @if (isset($set_standard))
                    <div role="tabpanel" class="tab-pane fade " id="plan">
                        @include('tis.set_standard.plan')
                    </div>
                    <div role="tabpanel" class="tab-pane fade " id="result"> 
                        @include('tis.set_standard.result')
                    </div>
                  @endif
                
              </div>
          </div>
    
  </div>
 
 
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
<!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
 
<script src="{{ asset('js/function.js') }}"></script>

<script type="text/javascript">


      $(document).ready(function() {
    // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
    $(".input_number").on("keypress",function(e){
            var eKey = e.which || e.keyCode;
            if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                return false;
            }
        });
      IsInputNumber();             
  $("input[name=review_status]").on("ifChanged", function(event) {;
    review_status();
    });
    review_status();
  function review_status(){
        var row = $("input[name=review_status]:checked").val();
        if(row == "2"){               
          $('#show_revise').show(200);

          $('#div_tis_no1').hide();
          $('#div_tis_no2').show();
          $('#tis_no1').prop('required', false);
          $('#tis_no2').prop('required', true);

          $('#div_tis_book1').hide();
          $('#div_tis_book2').show();
          $('#tis_book1').prop('required', false);
          $('#tis_book2').prop('required', false);
        }else{
          $('#show_revise').hide(400);

          $('#div_tis_no1').show();
          $('#div_tis_no2').hide();
          $('#tis_no1').prop('required', true);
          $('#tis_no2').prop('required', false);

          $('#div_tis_book1').show();
          $('#div_tis_book2').hide();
          $('#tis_book1').prop('required', false);
          $('#tis_book2').prop('required', false);
        }
    }  

    $("input[name=revise_status]").on("ifChanged", function(event) {;
        revise_status();
    });
    revise_status();
  function revise_status(){

          $('#tis_no2').html('<option value="">- เลือกเลขที่ มอก.-</option>');
          $('#tis_book2').html('<option value="">- เลือกเลขที่ มอก.-</option>');

          var tis_no = '<?php  echo !empty($set_standard->tis_no) ? $set_standard->tis_no:null ?>';
          var tis_book = '<?php  echo !empty($set_standard->tis_book) ? $set_standard->tis_book:null ?>';

        var row = $("input[name=revise_status]:checked").val();
        if(row == "1"){               
                const url = "{{ url('api/tis/set_standard/standard') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (datas) {
                     if(datas.standards.length > 0) {
                        $.each(datas.standards,function (index,value) {
                            var selected1 = (value.id == tis_no)?'selected="selected"':'';
                            $('#tis_no2').append('<option value='+value.id+' '+selected1+' >' +value.tis_no + ' : '+ value.title+'</option>');
                         
                        });
                        $('#tis_no2').select2();
                      }
                      if(datas.tis_book.length > 0) {
                        $.each(datas.tis_book,function (index,value) {
                            var selected2 = (value.tis_book == tis_book)?'selected="selected"':'';
                            $('#tis_book2').append('<option value='+value.tis_book+' '+selected2+' >'+value.tis_book+'</option>');
                        });
                        $('#tis_book2').select2();
                      } 
                    }
                });
        }else  if(row == "2"){   
                const url = "{{ url('api/tis/set_standard/standards') }}";
                 $.ajax({
                    type: "GET",
                    url: url,
                    success: function (datas) {
                     if(datas.standards.length > 0) {
                        $.each(datas.standards,function (index,value) {
                            var selected1 = (value.id == tis_no)?'selected="selected"':'';
                            $('#tis_no2').append('<option value='+value.id+' '+selected1+' >' +value.tis_no + ' : '+ value.title+'</option>');
                        });
                        $('#tis_no2').select2();
                      }
                      if(datas.tis_book.length > 0) {
                        $.each(datas.tis_book,function (index,value) {
                            var selected2 = (value.tis_book == tis_book)?'selected="selected"':'';
                            $('#tis_book2').append('<option value='+value.tis_book+' '+selected2+'  >'+value.tis_book+'</option>');
                        });
                        $('#tis_book2').select2();
                      }       
                    }
                });
        }
     
      
    }  
    

    $('#standard_announcement').click(function(){
                    var set_standard_id = $(this).data("set_standard_id");
                    var r = confirm("กรุณาตรวจสอบข้อมูล มอก. และเอกสารแนบ ให้ถูกต้องก่อนกด OK เพื่อส่งข้อมูลไปยังระบบ มาตรฐาน มอก. และดำเนินการต่อไป");
                        if (r == true) {
                        $.ajax({
                            type: "POST",
                            url: "{{url('tis/set_standard/standard_announcement')}}",
                            datatype: "html",
                            data: {
                                set_standard_id: set_standard_id,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                if(data.status=='success'){
                                    $.toast({
                                        heading: 'Success!',
                                        position: 'top-center',
                                        text: 'ประกาศมาตรฐานเรียบร้อยแล้ว',
                                        loaderBg: '#70b7d6',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 6
                                    });
                                    window.location.assign("{{url('/tis/set_standard')}}");
                                }

                            }
                        });
                    }
            });

            $('#cancel_announcement').click(function(){
                var set_standard_id = $(this).data("set_standard_id");
                    var r = confirm("กรุณาแจ้ง กวป. เพื่อลบข้อมูลในระบบ มาตรฐาน มอก. ก่อนกด OK มิฉะนั้นจะเกิดข้อมูลซ้ำซ้อนกัน");
                        if (r == true) {
                            $.ajax({
                                type: "POST",
                                url: "{{url('tis/set_standard/cancel_announcement')}}",
                                datatype: "html",
                                data: {
                                    set_standard_id: set_standard_id,
                                    '_token': "{{ csrf_token() }}",
                                },
                                success: function (data) {
                                    if(data.status=='success'){
                                        $.toast({
                                            heading: 'Success!',
                                            position: 'top-center',
                                            text: 'ยกเลิกการประกาศมาตรฐานเรียบร้อยแล้ว',
                                            loaderBg: '#70b7d6',
                                            icon: 'success',
                                            hideAfter: 3000,
                                            stack: 6
                                        });
                                        window.location.reload();
                                    }

                                }
                            });
                        }
            });


    
         //ช่วงวันที่
         $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

    
      });

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

         function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }  
        function DateFormateTh(str){
            var arr_mount = {} ;
                arr_mount['01']  = 'ม.ค.';
                arr_mount['02']  = 'ก.พ.';
                arr_mount['03']  = 'มี.ค.';
                arr_mount['04']  = 'เม.ษ.';
                arr_mount['05']  = 'พ.ค.';
                arr_mount['06']  = 'มิ.ย.';
                arr_mount['07']  = 'ก.ค.';
                arr_mount['08']  = 'ส.ค.';
                arr_mount['09']  = 'ก.ย.';
                arr_mount['10']  = 'ต.ค.';
                arr_mount['11']  = 'พ.ย.';
                arr_mount['12']  = 'ธ.ค.';
              var appoint_date=str;
              var getdayBirth=appoint_date.split("/");
              var YB=getdayBirth[2];
              var MB=getdayBirth[1];
              var DB=getdayBirth[0];
              var date = DB+' '+arr_mount[MB]+' '+YB ;
              return date;
          }

</script>
@endpush

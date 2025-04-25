
@push('css')
   <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

   
   
   <!-- Modal -->
<div  class="modal  bs-example-modal-xl in"  id="exampleModal" tabindex="-1"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class=" modal-xl">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">แนบใบ Pay-in ครั้งที่ 1
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </h4>
        </div>
     <!-- เจ้าหน้าที่ส่งใบ Pay-in   --> 
     @if (!is_null($cc->applicant) && ($cc->applicant->status <= 15 || $find_cost_assessment->status_confirmed == 3) )     

          {!! Form::open(['url' => 'certify/check_certificate/update/status/pay_in1', 'class' => 'form-horizontal', 'files' => true,'id'=>'form_pay_in1' ]) !!}
            <div class="modal-body">
                    @if (!is_null($find_cost_assessment) && !is_null($find_cost_assessment->amount_invoice) &&  $find_cost_assessment->status_confirmed != 3)
                        @if ($find_cost_assessment->payin_cancel == 1)     <!-- ยกเว้นค่าธรรมเนียม   --> 
                                @if (!is_null($find_cost_assessment->amount_invoice))
                                    <div class="form-group {{ $errors->has('amount_invoice') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('amount_invoice', '<span class="text-danger">*</span>  หลักฐานการไม่เก็บค่าธรรมเนียม :', ['class' => 'col-md-5 control-label'])) !!}
                                        <div class="col-md-7">
                                            <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name : basename($find_cost_assessment->amount_invoice)  ))}}" target="_blank">
                                                {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                        @else
                                <div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
                                    {!! HTML::decode(Form::label('amount', '<span class="text-danger">*</span>  จำนวนเงิน :', ['class' => 'col-md-5 control-label'])) !!}
                                    <div class="col-md-4">
                                        <p class="text-left">{{!empty($find_cost_assessment->amount) ? number_format($find_cost_assessment->amount,2) : @$countItem }} บาท</p>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
                                    {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span>  วันที่แจ้งชำระ :', ['class' => 'col-md-5 control-label'])) !!}
                                    <div class="col-md-4">
                                        <p class="text-left">{{!empty($find_cost_assessment->report_date) ? HP::DateThai($find_cost_assessment->report_date) : ' ' }}</p>
                                    </div>
                                </div>
                                @if (!is_null($find_cost_assessment->amount_invoice))
                                    <div class="form-group {{ $errors->has('amount_invoice') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('amount_invoice', '<span class="text-danger">*</span>  ค่าบริการในการตรวจประเมิน :', ['class' => 'col-md-5 control-label'])) !!}
                                        <div class="col-md-7">
                                            <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name : basename($find_cost_assessment->amount_invoice)  ))}}" target="_blank">
                                                {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                        @endif

                    @else 

                        <div class="form-group  {{ $errors->has('amount') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('amount', '<span class="text-danger">*</span>  เงื่อนไขการชำระเงิน :', ['class' => 'col-md-3  control-label'])) !!}
                            <div class="col-md-9">
                                <label>{!! Form::radio('conditional_status', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บค่าธรรมเนียม &nbsp;&nbsp;</label>
                                <label>{!! Form::radio('conditional_status', '2', true  , ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ยกเว้นค่าธรรมเนียม &nbsp;&nbsp;</label>
                                <label>{!! Form::radio('conditional_status', '3', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ </label>
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('amount') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('amount', 'วันที่ตรวจประเมิน :', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-4">
                               <label class="control-label">{{ $find_cost_assessment->date_board_auditor ?? null}}</label>  
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('amount', '<span class="text-danger">*</span>  จำนวนเงิน :', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-4">
                                <input type="text" name="amount" id="amount"  class="form-control text-right  css_input" required
                                 value="{{!empty($find_cost_assessment->amount > 0.00) ? number_format($find_cost_assessment->amount,2) : @$countItem  }}">
                            </div>
                        </div>
                        <div class="form-group div-collect {{ $errors->has('report_date') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span>  วันที่แจ้งชำระ :', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('report_date', HP::revertDate(date('Y-m-d'),true)  ,  ['class' => 'form-control  text-right mydatepicker','required'=>true,'id'=>'report_date','placeholder'=>'dd/mm/yyyy'])!!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                                {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group div-except {{ $errors->has('report_date') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span>  หมายเหตุ :', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-4">
                                  {!! Form::textarea('remark', null, ['class' => 'form-control assessment_desc', 'rows'=>'3']); !!}
                            </div>
                        </div>
                         <div class="form-group div-except {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
                                {!! HTML::decode(Form::label('other_attach', '<span class="text-danger">*</span>  หลักฐานการไม่เก็บค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-7">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="other_attach" accept=".pdf " id="other_attach" class="check_max_size_file" >
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                </div>
                         </div>

                    @endif
            </div>
            <div class="modal-footer">
                @if(is_null($find_cost_assessment->amount_invoice))
                    <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                    <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button   type="button" class="btn btn-primary"  id="save_pay_in" >บันทึกชำระ</button>
                @endif
            </div>
           {!! Form::close() !!}

       @else 

       {!! Form::open(['url' => 'certify/check_certificate/update/status_confirmed/pay_in1', 'class' => 'form-horizontal','id'=>'form_pay_in1', 'files' => true]) !!}
       <div class="modal-body">
            <div class="row">
                <div class="col-sm-5 text-right"> <b>จำนวนเงิน :</b></div>
                <div class="col-sm-6">
                    <p>{{!empty($find_cost_assessment->amount) ? number_format($find_cost_assessment->amount,2) : null}} บาท</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 text-right"> <b>วันที่แจ้งชำระ :</b></div>
                <div class="col-sm-6">
                    <p>  {{!empty($find_cost_assessment->report_date) ? HP::DateThai($find_cost_assessment->report_date) : ' ' }} </p>
                </div>
            </div>
 
                    @if (!is_null($find_cost_assessment) && !is_null($find_cost_assessment->amount_invoice))
                    <div class="row">
                     @if ($find_cost_assessment->payin_cancel == 1)     <!-- ยกเว้นค่าธรรมเนียม   --> 
                        <div class="col-sm-5 text-right"> <b>หลักฐานการไม่เก็บค่าธรรมเนียม :</b></div>
                     @else 
                        <div class="col-sm-5 text-right"> <b>ค่าบริการในการตรวจประเมิน :</b></div>
                    @endif
                    <div class="col-sm-6">
                        <p>
                            <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ))}}" target="_blank">
                            {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                            </a>
                        <p>
                    </div>
                    </div>
                    @endif
               
            <h4 class="modal-title" >หลักฐานการชำระเงิน </h4>
            <hr>

            @if (!is_null($find_cost_assessment) && !is_null($find_cost_assessment->invoice))
                <div class="row">
                    <div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าตรวจประเมิน :</b></div>
                    <div class="col-sm-6">
                        <p> 
                           <a href="{{url('certify/check/file_client/'.$find_cost_assessment->invoice.'/'.( !empty($find_cost_assessment->invoice_client_name) ? $find_cost_assessment->invoice_client_name :   basename($find_cost_assessment->invoice) ))}}" target="_blank">
                                {!! HP::FileExtension($find_cost_assessment->invoice)  ?? '' !!}
                            </a>
                        </p>
                    </div>
                </div>
            @endif

            <input type="hidden" name="app_certi_lab_cost_assessments_id" value="{{ $find_cost_assessment->id ?? null}}">

            <div class="row">
                <div class="col-sm-5 text-right"> <b>ตรวจสอบการชำค่าตรวจประเมิน :</b></div>
                <div class="col-sm-7">
                    <label><input type="radio" name="assessmen_status" value="1" {{ (is_null($find_cost_assessment->status_confirmed)  || $find_cost_assessment->status_confirmed == 1) ? 'checked':'' }}   class="check check_readonly_1" data-radio="iradio_square-green">
                         &nbsp;ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว &nbsp;
                    </label>
                    <label><input type="radio" name="assessmen_status" value="0"   {{ (!is_null($find_cost_assessment->status_confirmed)  && $find_cost_assessment->status_confirmed == 0) ? 'checked':'' }}    class="check check_readonly_1" data-radio="iradio_square-red"  > 
                        &nbsp;ยังไม่ได้ชำระเงิน &nbsp;
                    </label>
                </div>
            </div>
             <div class="row show_status_confirmed">
                <div class="col-sm-5 text-right">หมายเหตุ : </div>
                  <div class="col-sm-7">
                        {!! Form::textarea('detail', null, ['class' => 'form-control assessment_desc', 'rows'=>'3']); !!}
                </div>
            </div>

        </div>
        <div class="modal-footer">
            @if (!is_null($find_cost_assessment) && ($find_cost_assessment->status_confirmed != 1))
                <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button   type="submit" class="btn btn-primary" onclick="return confirm('ยื่นยันการบันทึก ตรวจสอบการชำระค่าตรวจประเมิน')">บันทึก</button>
                @endif
        </div>
        {!! Form::close() !!}

       @endif

        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    
<script type="text/javascript">
   $(document).ready(function() {
        // change   สาขาที่ขอรับการรับรอง
         $("input[name=conditional_status]").on("ifChanged",function(){
             alert();
            conditional_status();
        });
        conditional_status();

         $('#save_pay_in').click(function () { 
            var row = $('#payin_cancel:checked').val();
            if(row == '1'){
                  $('#form_pay_in1').submit();
            }else{
                const amount  = $('#amount').val();
                const start_date  =   $('#report_date').val();
                if(start_date != '' && amount != ''){
                    $.ajax({
                         type:"POST",
                         url:  "{{ url('/certify/check_certificate/check_pay_in_lab') }}",
                         data:{
                             _token: "{{ csrf_token() }}",
                             lab_id:  "{{ $cc->app_certi_lab_id ?? null }}",
                             amount:  RemoveCommas(amount) ,
                             start_date:  DateFormate(start_date) ,
                             payin : '1'
                         },
                        success:function(data){
                            if(data.message === true){
                                $('#form_pay_in1').submit();
                            }else{
                                Swal.fire(data.status_error,'','warning');
                            }
                        }
                   });
                }else{
                    Swal.fire('กรุณาเลือกจำนวนเงินและวันที่แจ้งชำระ','','info');
                }

             }  
          });
          
          
        
    $('#form_pay_in1').parsley().on('field:validated', function() {
         var ok = $('.parsley-error').length === 0;
         $('.bs-callout-info').toggleClass('hidden', !ok);
         $('.bs-callout-warning').toggleClass('hidden', ok);
     }) .on('form:submit', function() {
             $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังบันทึก กรุณารอสักครู่..."
             });
            return true; // Don't submit form for this demo
     });


    });
    

     function conditional_status(){
           var status = $("input[name=conditional_status]:checked").val();

           if(status == '1'){ // เรียกเก็บค่าธรรมเนียม
                $('#amount').prop('required' ,true);  
                $('.mydatepicker').prop('required' ,true);  

                $('.div-collect').show();  
                $('.div-except').hide();   

           }else if(status == '2'){ // ยกเว้นค่าธรรมเนียม
                $('#amount').prop('required' ,false);  
                $('.mydatepicker').prop('required' ,false);  

                $('.div-collect').hide();  
                $('.div-except').show();   
           }else if(status == '3'){  //  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#amount').prop('required' ,false);  
                $('.mydatepicker').prop('required' ,false);  

           }
      }

            // ลบ คอมมา     
            function RemoveCommas(nstr){
                return nstr.replace(/[^\d\.\-\ ]/g, '');
            }
            function DateFormate(str){
            var appoint_date=str;  
            var getdayBirth=appoint_date.split("/");  
            var YB=getdayBirth[2]-543;  
            var MB=getdayBirth[1];  
            var DB=getdayBirth[0];  
            var date = YB+'-'+MB+'-'+DB;
            return date;
           }

    $(function(){
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
    $(".css_input").on("keypress",function(e){
    var eKey = e.which || e.keyCode;
    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
    return false;
    }
    }); 
    
    // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
    $(".css_input").on("change",function(){
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
    // $(".css_input:eq(0)").trigger("change");// กำหนดเมื่อโหลด ทำงานหาผลรวมทันที  
    
    });
    </script>


@endpush
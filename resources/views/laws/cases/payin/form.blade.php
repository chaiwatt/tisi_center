@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
<style>
    .div_dotted {
        border-bottom: 1px dotted #000;
        padding: 0 0 5px 0;
        cursor: not-allowed;
    }

    .input_dotted {
        border: none;
        border-bottom: 1px dotted #000;
        cursor: not-allowed;
    }

    legend {
        margin-bottom: 0px;
    }
 
 

    .div-show{
        display: block;
    }
    .div-hide{
        display: none;
    }
    .input_dotted[disabled] {
        background-color: #ffffff;
        opacity: 1;
    }
 
    .btn-sm {
    padding: 2px 5px;
    font-size: 12px;
    font-family: 'Kanit', Open Sans, sans-serif;
    line-height: 1.5;
    border-radius: 3px;
}



 
</style>
@endpush


<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ผลเปรียบเทียบปรับ</h5>
            </legend>


<div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'ผลเปรียบเทียบปรับ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
         <div class="table-responsive">
             <table class="table table-striped" id="myTable">
                <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    <th class="text-center" width="65%">รายละเอียดผลเปรียบเทียบปรับ</th>
                    <th class="text-center" width="30%">จำนวนเงิน</th>
                    <th class="text-center" width="3%"></th>
                </tr>
                </thead>
                <tbody id="table_tbody_adjusting">
                    @if (count($compare_amounts) > 0)
                            @foreach ($compare_amounts as $item)
                              <tr>
                                <td class="text-center text-top">
                                        1
                                </td> 
                                <td class="text-top">
                                        {!! Form::hidden('compare_amount[id][]', $item->id , ['class' => 'form-control' ] ) !!}
                                        {!! Form::text('compare_amount[detail_amounts][]', !empty($item->detail_amounts) ? $item->detail_amounts :  null , ['class' => 'form-control detail_amounts  ', 'required' => true  ] ) !!}
                                </td>
                                    <td class="text-top">
                                        {!! Form::text('compare_amount[amount][]',  !empty($item->amount) ?  number_format($item->amount,2) : @$cases->amount  , ['class' => 'form-control   amount text-right ','placeholder' => '0.00', 'required' => true  ] ) !!}
                                </td>
                                    <td class="text-right   text-top">
                                        <button type="button" class=" btn btn-danger btn-outline  manage  btn-sm  ">
                                            <i class="fa fa-close"></i>
                                        </button>
                                 </td>
                              </tr>  
                            @endforeach
                    @endif
                  
                </tbody>
                 <tfoot>
                  <tr>
                   <td colspan="2" class="text-top text-right ">
                    <span class="font-medium-7">รวมเงิน</span>         
                   </td>
                    <td class="text-top text-right">
                       <span id="amount_sum" class="font-medium-7"></span>  
                   </td>
                    <td class="text-top">
                        
                   </td>
                  </tr>
                </tfoot>
            </table>
        </div>     
    </div>
</div>

        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ข้อมูลใบแจ้งชำระ (Pay-in)</h5>
            </legend>


<div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'เงื่อนไขการชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        <label>{!! Form::radio('condition_type', '1',  empty($cases->law_cases_payments_to) || (!empty($cases->law_cases_payments_to->condition_type) && $cases->law_cases_payments_to->condition_type == '1') ?  true : false, ['class'=>'check ', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} เรียกเก็บเงินค่าปรับ</label>
        &nbsp;&nbsp;
        {{-- <label>{!! Form::radio('condition_type', '2', (!empty($cases->law_cases_payments_to->condition_type) && $cases->law_cases_payments_to->condition_type == '2') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ไม่เรียกเก็บค่าปรับ</label>
        &nbsp;&nbsp; --}}
        <label>{!! Form::radio('condition_type', '3', (!empty($cases->law_cases_payments_to->condition_type) && $cases->law_cases_payments_to->condition_type == '3') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บเงินค่าปรับนอกระบบ</label>
    </div>
</div>

<div class="form-group required div_case_payment{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('name', 'ชื่อผู้ชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('name', 
         (!empty($cases->law_cases_payments_to->name) &&   is_null($payment->cancel_status))   ?  $cases->law_cases_payments_to->name : ( !empty($cases->offend_name)  && !empty($cases->offend_taxid) ? HP::LawPayrName($cases->offend_name,$cases->offend_taxid) : @$cases->offend_name ), 
        ['class' => 'form-control ',   'required' => true   ] ) !!}
    </div>
</div>

<div class="form-group required div_case_payment {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'วันที่แจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
         <div class="table-responsive">
             <table class="table table-striped" id="myTable">
                <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    <th class="text-center" width="40%">รายการ</th>
                    <th class="text-center" width="20%">จำนวนเงิน</th>
                    <th class="text-center" width="38%">หมายเหตุ<i class="text-muted">(แสดงภายใต้ชื่อรายการ)</i></th>
                </tr>
                </thead>
                <tbody >
                <tr> 
                   <td class="text-center text-top">
                    <span class="font-medium-6">1</span>      
                   </td> 
                   <td class="text-top">
                    <span  class="font-medium-6">        
                        {!!    !empty($cases->law_cases_payments_to->law_cases_payments_detail_to->fee_name)  ? $cases->law_cases_payments_to->law_cases_payments_detail_to->fee_name  :  ''  !!} 
                     </span>   
                   </td>
                    <td class="text-top text-right">
                        <span id="amount" class="font-medium-7">
                            {!!    !empty($cases->law_cases_payments_to->law_cases_payments_detail_to->amount)  ? number_format($cases->law_cases_payments_to->law_cases_payments_detail_to->amount,2) :   null   !!}
                        </span>   
                   </td>
                    <td class="text-top">
                       {!! Form::text('remark_fee_name', 
                       !empty($cases->law_cases_payments_to->law_cases_payments_detail_to->remark_fee_name)  ? $cases->law_cases_payments_to->law_cases_payments_detail_to->remark_fee_name :  null , 
                       ['class' => 'form-control   ', 'id' =>'remark_fee_name' , 'required' => true  ] ) !!}
                    </td>
                </tr>  
                </tbody>
                <tfoot>
                    <tr>
                     <td colspan="2" class="text-top text-right ">
                        <span class="font-medium-6">รวมเงิน</span>          
                     </td>
                      <td class="text-top text-right ">
                        <span id="inform_sum" class="font-medium-6"> 
                             {!!  !empty($cases->law_cases_payments_to->law_cases_payments_detail_to->amount)  ? number_format($cases->law_cases_payments_to->law_cases_payments_detail_to->amount,2) :  ''  !!} 
                        </span>    
                     </td>
                      <td class="text-top">
                          
                     </td>
                    </tr>
            </table>
        </div>     
    </div>
</div>

<div class="form-group required div_case_payment {{ $errors->has('start_date') ? 'has-error' : ''}}">
    {!! Form::label('start_date', 'วันที่แจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
          <div class="inputWithIcon">
               {!! Form::text('start_date', 
               !empty($cases->law_cases_payments_to->start_date) ? HP::revertDate($cases->law_cases_payments_to->start_date, true) :  HP::revertDate(date("Y-m-d"), true),
                ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'id'=>'start_date'  , 'autocomplete' => 'off',  'required' => true    ] ) !!}
                <i class="icon-calender"></i>
          </div>
        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('amount_date', 'ชำระภายใน/วัน', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-2">
          {!! Form::text('amount_date',   !empty($cases->law_cases_payments_to->amount_date) ? $cases->law_cases_payments_to->amount_date :  '60', ['class' => 'form-control amount_date', 'id'=>'amount_date'  ,  'required' => true  ]) !!}
        {!! $errors->first('amount_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group div_case_payment  {{ $errors->has('end_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('end_date', 'วันที่ครบกำหนดชำระ'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
    <div class="col-md-3">
          <div class="inputWithIcon">
               {!! Form::text('end_date',  !empty($cases->law_cases_payments_to->end_date) ? HP::revertDate($cases->law_cases_payments_to->end_date, true) :  null , ['class' => 'form-control','placeholder' => 'dd/mm/yyyy', 'id'=>'end_date' , 'autocomplete' => 'off',  'disabled' => true   ] ) !!}
                <i class="icon-calender"></i>
          </div>
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('status', 'ใบแจ้งชำระ (Pay-in)', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        @if(!empty($payment) &&  ($payment->end_date < date("Y-m-d") || $payment->cancel_status == '1'))

           {!! Form::text('','แสดงไฟล์อัตโนมัติเมื่อบันทึก',  ['class' => 'form-control ', 'disabled'=>true ]) !!}

        @elseif (!empty($cases->law_cases_payments_to->file_law_cases_pay_in_to))
        @php
            $pay_in = $cases->law_cases_payments_to->file_law_cases_pay_in_to;
        @endphp
            <a href="{!! url('funtions/get-law-view/files/'.$pay_in->url.'/'.(!empty($pay_in->filename) ? $pay_in->filename :  basename($pay_in->url))) !!}" target="_blank">
                {{-- {!! !empty($pay_in->filename) ? $pay_in->filename : '' !!} --}}
                {!! HP::FileExtension($pay_in->url) ?? '' !!}
            </a>
        @else
                 {!! Form::text('','แสดงไฟล์อัตโนมัติเมื่อบันทึก',  ['class' => 'form-control ', 'disabled'=>true ]) !!}
        @endif
   
    </div>
</div>
{{-- 
<div class="form-group div_case_payment_remark  {{ $errors->has('end_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('end_date', 'หมายเหตุ'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
    <div class="col-md-8">
        {!! Form::textarea('remark', !empty($cases->law_cases_payments_to->remark) ? $cases->law_cases_payments_to->remark : '' , ['class' => 'form-control', 'rows'=>'3' , "id"=>"remark",  'required' => true ]); !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

@php
    $email_results = [];
    if(!is_null($law_notify)){
         $checked = 'checked'; 
       // อีเมล
        $emails =  $law_notify->email;
        if(!is_null($emails)){
            $emails = json_decode($emails,true);
            if(!empty($emails) && count($emails) > 0){ 
                $email_results = $emails; 
            }
        }
    }else{
        $checked = 'checked'; 
        $email_results[] =  $cases->offend_email ?? '';
    }
@endphp

<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        <div class="checkbox checkbox-primary">
            <input id="checkbox1" type="checkbox" value="1" name="funnel_system"  {{ $checked }}>
            <label for="checkbox1"> ส่งอีเมลแจ้งเตือนไปยังผู้กระทำความผิด </label>
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        <input type="text" value="{{ count($email_results) > 0 ?  implode(",",$email_results) : '' }}" data-role="tagsinput"  name="email_results"  id="email_results"  /> 
    </div>
</div>

<div class="form-group div_case_payment">
    <div class="col-md-offset-2 col-md-8">
       <p class="text-warning">คำอธิบาย : ระบบจะสร้างใบแจ้งชำระเงิน (Pay-in) ในกรณีที่เลือกเงื่อนไข "เรียกเก็บเงินค่าปรับ" เท่านั้น</p>  
    </div>

        </fieldset>
    </div>
</div>




@if (!empty($payment) &&  ($payment->end_date < date("Y-m-d") || $payment->cancel_status == '1'))
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="button" id="save_pay_in"  >
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-cases-payin'))
            <a class="btn btn-default show_tag_a"  href="{{url('/law/cases/payin')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
@else
<div class="clearfix"></div>
<a  href="{{ url('/law/cases/payin') }}"  class="btn btn-default btn-lg btn-block">
    <i class="fa fa-rotate-left"></i>
    <b>กลับ</b>
</a>

@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/function.js') }}"></script>
    
    <script>
        $(document).ready(function() {

            var offend_email =  '{{  (!empty($cases->offend_email)  && filter_var($cases->offend_email, FILTER_VALIDATE_EMAIL) ? $cases->offend_email : '') }}';
            $('#checkbox1').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && offend_email != ''){
                    $('#email_results').tagsinput('add', offend_email); 
                }else{
                    $('#email_results').tagsinput('remove', offend_email);
                }
            });

            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif
                $('.check-readonly').prop('disabled', true);
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});
            @if(!empty($payment) &&   $payment->cancel_status == '1' )

            @elseif(!empty($payment) &&  $payment->end_date >= date("Y-m-d")  )
                //Disable
                 $('#pay_in_form').find('input, select, textarea').prop('disabled', true);
                $('#pay_in_form').find('button').remove();
                $('#pay_in_form').find('.show_tag_a').hide();
                $('#pay_in_form').find('.box_remove').remove();
                $('.check-readonly').prop('disabled', true);
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});
            @endif

                $('#pay_in_form').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                }).on('form:submit', function() {
                        // Text
                        $.LoadingOverlay("show", {
                            image       : "",
                            text        :   "กำลังบันทึก กรุณารอสักครู่..." 
                        });
                        return true; // Don't submit form for this demo
                });
                // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
                $(".amount_date").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                }); 

                $('#save_pay_in').click(function () { 
                
                    var row =  $("input[name=condition_type]:checked").val();
                    if(row == '1'){ // เรียกเก็บเงินค่าปรับ
                            var start_date = $('#start_date').val();  
                             var amount_date = $('#amount_date').val();  
                                $.ajax({
                                    type:"get",
                                    url:  "{{ url('/law/cases/payin/check_pay_in') }}",
                                    data:{
                                        _token: "{{ csrf_token() }}",
                                        id:  "{{ $cases->id ?? null }}",
                                        amount:  $('#inform_sum').html(),
                                        name:  $('#name').val(),
                                        remark_fee_name:  $('#remark_fee_name').val(),
                                        start_date: start_date,
                                        amount_date: amount_date
                                    },
                                    success:function(data){
                                        if(data.message === true){
                                            $('#pay_in_form').submit();
                                        }else{
                                            Swal.fire(data.status_error,'','warning');
                                        }
                                    }
                            });

                    }else if(row == '2' ){ // ไม่เรียกเก็บค่าปรับ
                        $('#pay_in_form').submit();
                    }  
                });

                $('#amount, #start_date, #amount_date').keyup(function(event) {
                    amount_sum();
                });
                $('#amount, #start_date, #amount_date').change(function(event) {
                    amount_sum();
                });
                $('#amount, #start_date, #amount_date').blur(function(event) {
                    amount_sum();
                });


                    amount_sum();
                    ResetTableNumber();
                             //เพิ่มแถว
             $('body').on('click', '.add-row', function(){

                    $(this).removeClass('add-row').addClass('remove-row');
                    $(this).removeClass('btn-success').addClass('btn-danger');
                    $(this).parent().find('i').removeClass('fa-plus').addClass('fa-close');

                    //Clone b
                    $('#table_tbody_adjusting').children('tr:last()').clone().appendTo('#table_tbody_adjusting');
                    //Clear value
                    var row = $('#table_tbody_adjusting').children('tr:last()');
                        row.find('select.select2').val('');
                        row.find('select.select2').prev().remove();
                        row.find('select.select2').removeAttr('style');
                        row.find('select.select2').select2();
                        row.find('input[type="text"], input[type="hidden"]').val('');
                        row.find('textarea').val('');
                        row.find('ul.parsley-errors-list').remove();
            

                        ResetTableNumber();
                        IsInputNumber();

                        
                        $('.amount').keyup(function(event) {
                            amount_sum();
                        });
                        $('.amount').change(function(event) {
                            amount_sum();
                        });
                        $('.amount').blur(function(event) {
                            amount_sum();
                        });

               });
                 //ลบแถว
                $('body').on('click', '.remove-row', function(){
                    $(this).parent().parent().remove();
                    ResetTableNumber();
                    amount_sum();
                });
                $('.amount').keyup(function(event) {
                    amount_sum();
                });
                $('.amount').change(function(event) {
                    amount_sum();
                });
                $('.amount').blur(function(event) {
                    amount_sum();
                });

            $("input[name=condition_type]").on("ifChanged", function(event) {
                        condition_type();
                  });

                  condition_type();
                function condition_type(){
                      var row = $("input[name=condition_type]:checked").val();
                      if(row == "1"){ 
                          $('.div_case_payment').show(200);
                          $('.div_case_payment_remark').hide(400);
                          $('#remark_fee_name, #start_date, #amount_date, #name').prop('required' ,true);     
                          $('#remark').prop('required' ,false);  
                       
                      } else{
                         $('.div_case_payment').hide(400);
                         $('.div_case_payment_remark').show(200);
                         $('#remark_fee_name, #start_date, #amount_date, #name').prop('required' ,false);     
                         $('#remark').prop('required' ,true);   
                        
                      }
          
                  }
                  
                  $('#start_date').change(function(event) {
                        payments_date();
                   });
                   $('#amount_date').keyup(function(event) {
                        payments_date();
                    });

                //ปฎิทิน
                $('.mydatepicker').datepicker({
 
                    autoclose: true,
                    toggleActive: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy',
                });
                IsInputNumber();
      
        });



        
        function ResetTableNumber(){
                        var rows = $('#table_tbody_adjusting').children(); //แถวทั้งหมด
                            rows.each(function(index, el) {
                                 var key = (index+1);
                                //เลขรัน
                                $(el).children().first().html(key);
                            }); 
                        var row = $('#table_tbody_adjusting').children('tr:last()');
                            row.find('.manage').removeClass('remove-row').addClass('add-row');
                            row.find('.manage').removeClass('btn-danger').addClass('btn-success');
                            row.find('.manage > i').removeClass('fa-close').addClass('fa-plus');
                     
            }
            
        function amount_sum() {
                var rows = $('#table_tbody_adjusting').children(); //แถวทั้งหมด
                var sum = 0.00;
                    rows.each(function(index, el) {
                        var amount = $(el).find('.amount').val();  
                        if(checkNone(amount)){
                            sum  += parseFloat(RemoveCommas(amount));
                        } 
                    });  
                    $('#amount_sum, #amount, #inform_sum').html(addCommas(sum.toFixed(2), 2)); 

                   var condition_type = $("input[name=condition_type]:checked").val();
                   var start_date = $('#start_date').val();  
                   var amount_date = $('#amount_date').val();  
                    if(condition_type == "1" ){ 
                        if(sum != 0.00 && checkNone(start_date) && checkNone(amount_date)){
                            $('#save_pay_in').prop('disabled' ,false);      
                        }else{
                            $('#save_pay_in').prop('disabled' ,true);      
                        }
                    } else{ 
                        $('#save_pay_in').prop('disabled' ,false);   
                    }
            }  

        function payments_date(){
                var start_date = $('#start_date').val();  
                var amount_date = $('#amount_date').val();  
                if(checkNone(start_date) && checkNone(amount_date)){
                          $.ajax({
                                    type:"get",
                                    url:  "{{ url('/law/cases/compares/check_payments_date') }}",
                                    data:{
                                        _token: "{{ csrf_token() }}",
                                        start_date: start_date,
                                        amount_date: amount_date
                                    },
                                    success:function(data){
                                        if(data.message === true){
                                            $('#end_date').val(data.end_date );
                                        }
                                    }
                            });
                }else{

                }     
            }
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
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


    </script>
    @endpush

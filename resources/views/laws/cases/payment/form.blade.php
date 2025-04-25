@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
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


.tip {
    position: relative;
    display: inline-block;
    color: #ffc107;
     cursor: pointer
   }

.tip .tooltiptext {
  visibility: hidden;
  width: 350px;
  background-color: #fff;
  color: black;
  border: 1px solid  #e5ebec;
  border-radius: 6px;
  padding: 10px 10px;
  font-size:13px;
  position: absolute;
  z-index: 1;
}

.tip:hover .tooltiptext {
  visibility: visible;
}
 
</style>
@endpush

@php
       $paid_type = false;
     if($payment->end_date < date("Y-m-d") && $payment->paid_status == '1'){
        $paid_type = true;
      }
@endphp
 
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ข้อมูลผู้ชำระ</h5>
            </legend>


<div class="row for-show">
    <div class="col-md-6">
        <div class="form-group m-0">
            <label class="control-label col-md-5">ผู้ชำระเงิน :</label>
            <div class="col-md-7">
                {!! Form::text('',  !empty($cases->offend_name)    ? $cases->offend_name  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group m-0">
            <label class="control-label col-md-5">เลขประจำตัวผู้เสียภาษี :</label>
            <div class="col-md-7">
                {!! Form::text('',  !empty($cases->offend_taxid)   ? $cases->offend_taxid : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>


<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">จำนวนเงิน/บาท :</label>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable"  width="100%">
                       <thead>
                       <tr>
                           <th class="text-center" width="2%">ลำดับ</th>
                           <th class="text-center" width="40%">รายการ</th>
                           <th class="text-center" width="20%">จำนวนเงิน</th>
                           <th class="text-center" width="38%">หมายเหตุ </th>
                       </tr>
                       </thead>
                       <tbody >
                       <tr> 
                          <td class="text-center text-top">
                           <span class="font-medium-6">1</span>      
                          </td> 
                          <td class="text-top">
                            <span  class="font-medium-6">        
                               {!!    !empty($payment->law_cases_payments_detail_to->fee_name)  ? $payment->law_cases_payments_detail_to->fee_name  :  ''  !!} 
                            </span>      
                          </td>
                           <td class="text-top text-right">
                               <span id="inform_amount" class="font-medium-6"> {!! !empty($payment->law_cases_payments_detail_to->amount)  ? number_format($payment->law_cases_payments_detail_to->amount,2) :  ''  !!}</span>   
                          </td>
                           <td class="text-top">
                              <span   class="font-medium-6"> 
                                  {!!    !empty($payment->law_cases_payments_detail_to->remark_fee_name)  ? $payment->law_cases_payments_detail_to->remark_fee_name  :  ''  !!} 
                              </span>    
                           </td>
                       </tr>  
                       </tbody>
                       <tfoot>
                           <tr>
                            <td colspan="2" class="text-top text-right ">
                               <span class="font-medium-6">รวมเงิน</span>          
                            </td>
                             <td class="text-top text-right ">
                               <span id="inform_sum" class="font-medium-6">  {!!  !empty($payment->law_cases_payments_detail_to->amount)  ? number_format($payment->law_cases_payments_detail_to->amount,2) :  ''  !!} </span>    
                            </td>
                             <td class="text-top">
                                 
                            </td>
                           </tr>
                   </table>
               </div>     
            </div>
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
                <h5>หลักฐานการชำระ</h5>
            </legend>

<div class="form-group required{{ $errors->has('paid_status') ? 'has-error' : ''}}">
    {!! Form::label('paid_status', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
         {!! Form::select('paid_status', 
                [ '1'=> 'ยังไม่ชำระเงิน', '2'=> 'ชำระเงินแล้ว'], 
                   !empty($payment->paid_status) ? $payment->paid_status : null,
                  ['class' => 'form-control ',
                   'id' => 'paid_status',
                   'required' => true,
                   'placeholder'=>'-เลือกสถานะ-'])
         !!}
        {!! $errors->first('paid_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('paid_date') ? 'has-error' : ''}}">
    {!! Form::label('paid_date', 'วันที่ชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
          <div class="inputWithIcon">
               {!! Form::text('paid_date', !empty($payment->paid_date)? HP::revertDate($payment->paid_date, true) : HP::revertDate(date("Y-m-d"), true), ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off',  'required' => true   ] ) !!}
                <i class="icon-calender"></i>
          </div>
        {!! $errors->first('paid_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'ประเภท', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
          @if ($payment->condition_type == '3')
                 <label>{!! Form::radio('paid_type', '1',false, ['class'=>'check check-readonly  check_readonly paid_type1', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} Pay-in (กรมบัญชีกลาง)</label>
                     &nbsp;&nbsp;
                 <label>{!! Form::radio('paid_type', '2', true, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} นอกระบบ (เช่น ชำระ ณ สมอ. หรืออื่นๆ)</label>
            @else 
               <label>{!! Form::radio('paid_type', '1',  
                 ($paid_type  == false && empty($payment->paid_type) ) || (!empty($payment->paid_type) && $payment->paid_type == '1') ?  true : false, ['class'=>'check check-readonly paid_type1', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} Pay-in (กรมบัญชีกลาง)</label>
                 &nbsp;&nbsp;
               <label>{!! Form::radio('paid_type', '2', ($paid_type ==  true) || (!empty($payment->paid_type) && $payment->paid_type == '2') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} นอกระบบ (เช่น ชำระ ณ สมอ. หรืออื่นๆ)</label>
          @endif
          &nbsp;    <i class="fa fa-exclamation-circle bounce tip" ><span class="tooltiptext">กรณี เรียกเก็บเงินค่าปรับนอกระบบ จะไม่สามารถเลือกประเภท Pay-in (กรมบัญชีกลาง) ได้ เนืองจากระบบจะเชื่อมข้อมูลการชำระเงินอัตโมมัติ</span> </i> 
     
    </div>
</div>

<div class="form-group {{ $errors->has('[book_number]') ? 'has-error' : ''}}" id="div_ref_1">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('',  !empty($payment->app_certi_transaction_pay_in_to->Ref_1) ? $payment->app_certi_transaction_pay_in_to->Ref_1: '' , ['class' => 'form-control', 'disabled' =>  true ]) !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('paid_channel') ? 'has-error' : ''}}">
    {!! Form::label('paid_channel', 'ช่องทางชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
         {!! Form::select('paid_channel', 
                  ['1'=> 'โอนเงิน', '2'=> 'เงินสด'], 
                  !empty($payment->paid_channel) ? $payment->paid_channel : null,
                  ['class' => 'form-control ',
                   'id' => 'paid_channel',
                   'required' => true,
                   'placeholder'=>'-เลือกช่องทางชำระ-'])
         !!}
        {!! $errors->first('paid_status', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-3 div_paid_channel_remark">
        {!! Form::text('paid_channel_remark', !empty($payment->paid_channel_remark) ? $payment->paid_channel_remark : '', ['class' => 'form-control ', 'id' => 'paid_channel_remark', 'placeholder'=>'ระบุเลขบัญชี']); !!}
       {!! $errors->first('paid_channel_remark', '<p class="help-block">:message</p>') !!}
   </div>
</div>

<div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'หลักฐานการชำระ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-5">
        @if (!empty($payment->file_law_cases_attachs_bill_to))
        @php
            $attach = $payment->file_law_cases_attachs_bill_to;
            $url = url('funtions/get-law-view/files/'.(str_replace("//","/",$attach->url)).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)));
        @endphp
            <a href="{!! $url !!}" target="_blank">
                {!! !empty($attach->filename) ? $attach->filename : '' !!}
                {!! HP::FileExtension($attach->url) ?? '' !!}
            </a>
        @else
           <div class="fileinput fileinput-new input-group " data-provides="fileinput" >
                <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                <input type="file" name="attachs_bill" id="attachs_bill" required  accept=".jpg,.png,.pdf" class="check_max_size_file">
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
            </div>
           @endif
          
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group    {{ $errors->has('remark') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
    <div class="col-md-6">
        {!! Form::textarea('remark', !empty($payment->remark) ? $payment->remark : '' , ['class' => 'form-control', 'rows'=>'3' , "id"=>"remark" ]); !!}
        {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>



         </fieldset>
    </div>
</div>




@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>

    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

    
    <script>
        $(document).ready(function() {
            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
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

            @if($paid_type == true)
            $('.paid_type1').prop('disabled', true);
            $('.paid_type1').parent().removeClass('disabled');
            $('.paid_type1').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});
            @endif
            $('.check_readonly').prop('disabled', true);
            $('.check_readonly').parent().removeClass('disabled');
            $('.check_readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});
            

           $('#paid_channel').change(function(event) {
                    if($(this).val() == '1'){
                        $('.div_paid_channel_remark').show(200);
                        $('#paid_channel_remark').prop('required' ,true);   
                    }else{
                        $('.div_paid_channel_remark').hide(400);
                        $('#paid_channel_remark').prop('required' ,false);  
                    }
           });
           $('#paid_channel').change();

           $('.paid_type1').on('ifChanged', function(event){
                ifChangedBtnSubmit();
            });
            ifChangedBtnSubmit();

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });
        });

        function ifChangedBtnSubmit(){
            if($('.paid_type1').is(':checked')){
                $('#div_ref_1').show();
                $('#btn_submit').prop('disabled', true);
            } else {
                $('#div_ref_1').hide();
                $('#btn_submit').prop('disabled', false);

            }
        } 
        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>
    @endpush

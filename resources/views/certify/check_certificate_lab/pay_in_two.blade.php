@extends('layouts.master')
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<style>
    .border-dot-bottom {
        border-bottom: 1px dotted #000000;
    }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left"> Pay-In ครั้งที่ 2 </h3>
                    @can('view-'.str_slug('estimatedcostib'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
    @if (isset($payin2) && is_null($payin2->conditional_type))
        {!! Form::open(['url' => 'certify/check_certificate/update/status/cost_certificate/'.@$payin2->id, 'class' => 'form-horizontal pay_in2_form', 'files' => true,'id'=>'pay_in2_form']) !!}
    @else
        {!! Form::open(['url' => 'certify/check_certificate/update/status/receive_certificate/'.@$payin2->id, 'class' => 'form-horizontal pay_in2_form', 'files' => true,'id'=>'pay_in2_form']) !!}
    @endif

    <div class="form-group  {{ $errors->has('conditional_type') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('conditional_type', '<span class="text-danger">*</span>  เงื่อนไขการชำระเงิน :', ['class' => 'col-md-3  control-label'])) !!}
        <div class="col-md-9">
            <label>{!! Form::radio('conditional_type', '1',($payin2->conditional_type == 1 || $payin2->conditional == 1) ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บค่าธรรมเนียม &nbsp;&nbsp;</label>
            <label>{!! Form::radio('conditional_type', '2',($payin2->conditional_type == 2 || $payin2->conditional == 2) ? true : false  , ['class'=>'check check-readonly conditional_type', 'data-radio'=>'iradio_square-green']) !!} ยกเว้นค่าธรรมเนียม &nbsp;&nbsp;</label>
            <label>{!! Form::radio('conditional_type', '3', $payin2->conditional_type == 3 ? true :  false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ </label>
        </div>
    </div>

 <div class="form-group {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'คำขอเลขที่'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 m-t-10">
      <label for=""> {!! $payin2->applicant->app_no ?? null  !!} </label>
    </div>
</div>
   <!-- Start เรียกเก็บค่าธรรมเนียม  -->

<div class="form-group div-collect {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'วันที่แจ้งชำระ'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <label class="control-label"> {{!empty($payin2->notification_date) ? HP::DateThai($payin2->notification_date) : HP::DateThai(date('Y-m-d'))    }}</label>
    </div>
</div>

 <div class="form-group   div-collect {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'รายการ'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 m-t-10">
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table table-bordered" >
                <thead>
                        <tr>
                            <th class="text-center text-white" width="2%">ลำดับ</th>
                            <th class="text-center text-white" width="60%">รายการ</th>
                            <th class="text-center text-white" width="38%">จำนวนเงิน</th>
                        </tr>
                </thead>
                <tbody>
                    @if (!is_null($payin2->applicant))
                        @php
                            $purpose_costs = ['1' => 10000, '2' => 5000, '3' => 10000, '4' => 10000, '5' => 10000, '6' => 10000];
                            $purpose_texts = ['1' => 'ขอใบรับรอง', '2' => 'ต่ออายุ', '3' => 'ขยายขอบข่าย', '4' => 'การเปลี่ยนแปลงมาตรฐาน', '5' => 'ย้ายสถานที่', '6' => 'โอนใบรับรอง'];
                            $purpose_type  = $payin2->applicant->purpose_type;
                        @endphp

                        @if (array_key_exists($purpose_type, $purpose_costs))
                            @php
                                $fee          = 1000;
                                $purpose_text = $purpose_texts[$purpose_type];
                                $purpose_cost = $purpose_costs[$purpose_type];
                            @endphp

                            <tr>
                                <td class="text-center">1.</td>
                                <td class="text-left">ค่าธรรมเนียมคำขอการใบรับรอง สก.</td>
                                <td class="text-right">{{ number_format($fee, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2.</td>
                                <td class="text-left">ค่าธรรมเนียมใบรับรอง สก. ({{ $purpose_text }})</td>
                                <td class="text-right">{{ number_format($purpose_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">รวม</td>
                                <td class="text-right">{{ number_format($purpose_cost+$fee, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="3"><i class="text-danger">วัตถุประสงค์ของคำขอไม่ถูกต้องโปรดตรวจสอบ</i></td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td class="text-center" colspan="3"><i class="text-danger">ไม่พบใบสมัครโปรดตรวจสอบ</i></td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
@if(!is_null($payin2->attach) && HP::checkFileStorage($attach_path.$payin2->attach))
<div class="form-group div-collect {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
    {!! HTML::decode(Form::label('other_attach', ' ใบแจ้งหนี้ค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        <a href="{{url('certify/check/file_client/'.$payin2->attach.'/'.( !empty($payin2->attach_client_name) ? $payin2->attach_client_name : basename($payin2->attach) ))}}" target="_blank">
                {!! HP::FileExtension($payin2->attach)  ?? '' !!}
        </a>
    </div>
</div>
@endif

<!-- End เรียกเก็บค่าธรรมเนียม  -->

<!-- Start ยกเว้นค่าธรรมเนียม  -->
<div class="form-group div-except {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'ช่วงเวลาการยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <label class="control-label">    {{ !empty($payin2->DateFeewaiver)  ? $payin2->DateFeewaiver : (!empty($feewaiver->DatePayIn2)  ? $feewaiver->DatePayIn2 : null)   }}</label>
    </div>
</div>


@if(!is_null($payin2->attach) && HP::checkFileStorage($payin2->attach))
    <div class="form-group div-except {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
        {!! HTML::decode(Form::label('other_attach', ' เอกสารยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            <a href="{{url('funtions/get-view-file/'.base64_encode($payin2->attach).'/'.( !empty($payin2->attach_client_name) ? $payin2->attach_client_name : basename($payin2->attach) ))}}" target="_blank">
                    {!! HP::FileExtension($payin2->attach)  ?? '' !!}
            </a>
        </div>
    </div>
@else
    <div class="form-group div-except {{ $errors->has('report_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('report_date', 'เอกสารยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            @if(!empty($feewaiver->payin2_file) && HP::checkFileStorage($feewaiver->payin2_file))
                <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver->payin2_file).'/'.( !empty($feewaiver->payin2_file_client_name) ? $feewaiver->payin2_file_client_name :  basename($feewaiver->payin2_file)  ))}}" target="_blank">
                {!! HP::FileExtension($feewaiver->payin2_file)  ?? '' !!}
                </a>
            @endif
        </div>
    </div>
@endif
<!-- End ยกเว้นค่าธรรมเนียม  -->

<!-- Start ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
<div class="form-group div-other_cases {{ $errors->has('report_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> หมายเหตุ :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        @if (!is_null($payin2->remark))
        <p class="text-left">{{!empty($payin2->remark) ? $payin2->remark: null}} </p>
        @else
        {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows'=>'3','id'=>'remark']); !!}
        @endif

    </div>
</div>
@if (isset($payin2) && is_null($payin2->conditional_type))
<div class="form-group div-other_cases {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
    {!! HTML::decode(Form::label('other_attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        @if(!is_null($payin2->attach) && HP::checkFileStorage($attach_path.$payin2->attach))
        <a href="{{url('certify/check/file_client/'.$payin2->attach.'/'.( !empty($payin2->attach_client_name) ? $payin2->attach_client_name : basename($payin2->attach) ))}}" target="_blank">
                 {!! HP::FileExtension($payin2->attach)  ?? '' !!}
             </a>
         @else
             <div class="fileinput fileinput-new input-group div_amount_file" data-provides="fileinput">
                 <div class="form-control" data-trigger="fileinput">
                     <i class="glyphicon glyphicon-file fileinput-exists"></i>
                     <span class="fileinput-filename"></span>
                 </div>
                 <span class="input-group-addon btn btn-default btn-file">
                     <span class="fileinput-new">เลือกไฟล์</span>
                     <span class="fileinput-exists">เปลี่ยน</span>
                     <input type="file" name="attach"  id="attach" class="check_max_size_file">
                 </span>
                 <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
             </div>
         @endif
    </div>
</div>
@else
    @if(!is_null($payin2->attach) && HP::checkFileStorage($attach_path.$payin2->attach))
    <div class="form-group div-other_cases {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
        {!! HTML::decode(Form::label('other_attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            <a href="{{url('certify/check/file_client/'.$payin2->attach.'/'.( !empty($payin2->attach_client_name) ? $payin2->attach_client_name : basename($payin2->attach) ))}}" target="_blank">
                    {!! HP::FileExtension($payin2->attach)  ?? '' !!}
            </a>
        </div>
    </div>
    @endif
@endif

<!-- End ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->

<!-- Start หลักฐานค่าธรรมเนียม -->
@if(!is_null($payin2->invoice) && HP::checkFileStorage($attach_path.$payin2->invoice))
<div class="form-group {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'หลักฐานค่าธรรมเนียม'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3 m-t-9">
            <a href="{{url('certify/check/file_client/'.$payin2->invoice.'/'.( !empty($payin2->invoice_client_name) ? $payin2->invoice_client_name : basename($payin2->invoice) ))}}" target="_blank">
                {!! HP::FileExtension($payin2->invoice)  ?? '' !!}
        </a>
    </div>
</div>
@endif
<!-- End หลักฐานค่าธรรมเนียม  -->

@if(!is_null($payin2) && !is_null($payin2->invoice))
    <div class="row">
        <div class="col-sm-4 text-right"> <b>ตรวจสอบการชำระ :</b></div>
        <div class="col-sm-6">
                <label><input type="radio" name="status_confirmed" value="1" {{ ($payin2->status==1 || $payin2->status==null) ? 'checked':'' }}
                    class="check" data-radio="iradio_square-green"> &nbsp;รับชำระเงินเรียบร้อยแล้ว &nbsp;
                </label>
                <label>
                    <input type="radio" name="status_confirmed" value="2" {{ $payin2->status==2 ? 'checked':'' }}
                    class="check {{ (!empty($payin2->conditional_type)  && $payin2->conditional_type == 1) ? '':'' }} "
                     data-radio="iradio_square-red"  > &nbsp;ยังไม่ชำระเงิน &nbsp;
                </label>
        </div>
    </div>
    <div class="row show_status_payin2  form-group">
        <div class="col-sm-4 text-right">หมายเหตุ : </div>
        <div class="col-sm-7">
                {!! Form::textarea('detail', null, ['class' => 'form-control detail_payin2', 'rows'=>'3','id' => 'detail']); !!}
        </div>
    </div>

    @if (!empty($payin2->conditional_type)  && $payin2->conditional_type == 1  && $payin2->status_confirmed != 1)
        <div class="row form-group">
            <label class="col-sm-4 text-right"></label>
            <div class="col-sm-7">
                    <button type="button" class="btn btn-warning" id="transaction_payin">ตรวจสอบการชำระ</button>
            </div>
        </div>
    @endif

 @endif

 @if (!empty($payin2->transaction_payin_to))
 @php
    $payin =   $payin2->transaction_payin_to;
 @endphp
 
 @if (   $payin2->conditional_type  == 1 &&  
         $payin2->status_confirmed != 1 && 
         !is_null($payin)  && 
         !empty($payin->invoiceEndDate)  &&   
         date("Y-m-d") > date("Y-m-d", strtotime($payin->invoiceEndDate))  
     )
   <div class="row form-group">
    <label class="col-sm-4 text-right"><span class="text-danger">*</span> เงื่อนไขการชำระ :</label>
     <div class="col-sm-4">
                {!! Form::select('condition_pay',
                [  '1'=> 'pay-in เกินกำหนด (ชำระที่ สมอ.)',
                    '2'=> 'ได้รับการยกเว้นค่าธรรมเนียม',
                    '3'=> 'ชำระเงินนอกระบบ, กรณีอื่นๆ'
                ], 
                    null, 
                ['class' => 'form-control', 
                'placeholder'=>'- เลือกเงื่อนไขการชำระ -',
                'id'=>'condition_pay',
                'required' => true]); !!}
      </div>
    </div>

    <div class="row form-group">
        <label class="col-sm-4 text-right"><span class="text-danger">*</span> วันที่ชำระ :</label>
        <div class="col-sm-3">
            <div class="input-group">
                {!! Form::text('ReceiptCreateDate', 
                    !empty($payin->ReceiptCreateDate) ?  HP::revertDate(date("Y-m-d", strtotime($payin->ReceiptCreateDate)),true)  :  null ,  
                    ['class' => 'form-control mydatepicker','placeholder'=>'dd/mm/yyyy','required' => true])
                !!}
                <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
        </div>
    </div>

    <div class="row  form-group">
        <label class="col-sm-4 text-right">เลขที่ใบเสร็จรับเงิน / เลขอ้างอิงการชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('ReceiptCode', !empty($payin->ReceiptCode) ?   $payin->ReceiptCode : null, ['id'=>'ReceiptCode', 'class' => 'form-control' ]) !!}
        </div>
    </div>

@elseif (!is_null($payin))

    @if (!empty($payin2->ConditionPayName))
    <div class="row form-group">
        <label class="col-sm-4 text-right"><span class="text-danger">*</span> เงื่อนไขการชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('condition_pay', $payin2->ConditionPayName, ['id'=>'condition_pay', 'class' => 'form-control','disabled'=>true]) !!}
        </div>
    </div>   
    @endif


    <div class="row form-group">
        <label class="col-sm-4 text-right"><span class="text-danger">*</span> วันที่ชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('ReceiptCreateDate', !empty($payin->ReceiptCreateDate) ?  HP::DateTimeThai($payin->ReceiptCreateDate)    : null, ['id'=>'ReceiptCreateDate', 'class' => 'form-control','disabled'=>true]) !!}
        </div>
    </div>
    
    <div class="row  ">
        <label class="col-sm-4 text-right">เลขที่ใบเสร็จรับเงิน / เลขอ้างอิงการชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('ReceiptCode', !empty($payin->ReceiptCode) ?   $payin->ReceiptCode : null, ['id'=>'ReceiptCode', 'class' => 'form-control', 'disabled'=>true]) !!}
        </div>
    </div>
@endif

@endif

    @php
        if(!isset($payin)){
            $payin = null;
        }
    @endphp

  @if (isset($payin2) &&    $payin2->status_confirmed != 1  && (is_null($payin2->notification_date)  || !is_null($payin2->invoice))  )
            <div class="row form-group">
                <div class="col-md-offset-4 col-md-4 m-t-15  text-center">
                    @if($payin2->conditional_type  == 1 &&  
                            $payin2->status_confirmed != 1 && 
                            !is_null($payin)  && 
                            !empty($payin->invoiceEndDate)  &&   
                            date("Y-m-d") > date("Y-m-d", strtotime($payin->invoiceEndDate))  
                            ) <!-- กรณีชำระเกินกำหนด -->
                        <button class="btn btn-primary" type="submit"   >
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                        <a class="btn btn-default" href="{{  app('url')->previous() }}">
                            <i class="fa fa-rotate-left"></i> ยกเลิก 
                        </a>
                    @elseif (!is_null($payin2->conditional_type) &&  $payin2->conditional_type == 1)
                        <a class="btn btn-lg btn-default" href="{{ app('url')->previous() }}">
                            <i class="fa fa-rotate-left"></i> ยกเลิก 
                        </a>
                        <button class="btn btn-lg btn-primary show_save_btn" type="submit" style="display:none" >
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                    @elseif (!is_null($payin2->conditional_type))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                        <a class="btn btn-default" href="{{url("$previousUrl")}}">
                            <i class="fa fa-rotate-left"></i> ยกเลิก 
                        </a>
                    @else
                        <button type="button" class="btn btn-primary"  id="save_pay_in">
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                        <a class="btn btn-default" href="{{url("$previousUrl")}}">
                            <i class="fa fa-rotate-left"></i> ยกเลิก  
                        </a>
                    @endif


                </div>
            </div>
    @else

      <div class="text-center m-t-25">
          <a class="btn btn-lg btn-default" href="{{url("$previousUrl")}}">
              <i class="fa fa-rotate-left"></i> ยกเลิก
          </a>
            {{-- <button class="btn btn-lg btn-primary show_save_btn" type="submit" style="display:none" >
                <i class="fa fa-paper-plane"></i> บันทึก
            </button> --}}
      </div>

    @endif
{!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>



    <div class="modal fade text-left" id="ModalPayIn" tabindex="-1" role="dialog" aria-labelledby="addBrand">
        <div class="modal-dialog  modal-lg" role="document">
            <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">ตรวจสอบสถานะ การชำระเงิน</h4>
                </div>
                <div class="modal-body">

    <div class="row">
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">หมายเลขอ้างอิง : </label>
            <p class="col-md-8 border-dot-bottom"  id="ref1"> {!! !empty($payin->ref1)?  $payin->ref1:'-'  !!} </p>
        </div>
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">CGDRef1 : </label>
            <p class="col-md-8 border-dot-bottom"  id="CGDRef1"> {!! !empty($payin->CGDRef1)?  $payin->CGDRef1:'-'  !!} </p>
        </div>
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">วันที่ชำระ : </label>
            <p class="col-md-8 border-dot-bottom"  id="receipt_create_date"> {!! !empty($payin->ReceiptCreateDate)?   HP::DateTimeThai($payin->ReceiptCreateDate) :'-'  !!} </p>
        </div>
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">เลขที่ใบเสร็จรับเงิน : </label>
            <p class="col-md-8 border-dot-bottom"  id="receipt_code"> {!! !empty($payin->ReceiptCode)?  $payin->ReceiptCode:'-'  !!} </p>
        </div>
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">จำนวนเงินที่ชำระ : </label>
            <p class="col-md-8 border-dot-bottom"  id="PayAmountBill"> {!! !empty($payin->PayAmountBill)?  number_format($payin->PayAmountBill,2):'-'  !!} </p>
        </div>
        <div class="col-sm-12">
            <label class="col-md-3 text-right control-label">สถานะ : </label>
            <p class="col-md-8 border-dot-bottom"  id="StatusPayIn"> {!! !empty($payin->status_confirmed) && $payin->status_confirmed == 1 ?  'ชำระค่าธรรมเนียมเรียบร้อย' : '-'  !!} </p>
        </div>
    </div>
                </div>
                <div class="modal-footer ">
                    <div class="col-sm-12 text-center">
                        <button type="button" class="btn btn-success" id="SaveModalPayIn">ยืนยัน</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
              </div>
        </div>
     </div>



@endsection

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <!-- Data Table -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/function.js')}}"></script>
  <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 2 -->
 <script type="text/javascript">
      jQuery(document).ready(function() {
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                    toggleActive: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy',
             });
 

        var feewaiver = '{{  !empty($feewaiver)  ? 1 : 2  }}';
            if (feewaiver == '2') {
                $('.check-readonly[value="2"]').prop('disabled', true);
                $('.check-readonly[value="2"]').parent().removeClass('disabled');
                $('.check-readonly[value="2"]').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor": "not-allowed"});
            }
          var conditional_type = '{{  !empty($payin2->conditional_type)  ? 1 : 2  }}';
          if (conditional_type == '1') {
            $('.check-readonly').prop('disabled', true);
             $('.check-readonly').parent().removeClass('disabled');
             $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
          }

            conditional();
        $("input[name=conditional_type]").on("ifChanged",function(){
            conditional();
        });

                    var check = '{{  !empty($payin2) &&  ($payin2->status_confirmed == 1) ? 1 : null  }}';
                    if(check == 1){
                        $('.check_readonly_1').prop('disabled', true);
                        $('.check_readonly_1').parent().removeClass('disabled');
                        $('.check_readonly_1').parent().css('margin-top', '8px').css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor": "not-allowed"});
                        $('.show_save_btn').hide(200);
                    }

                 $("input[name=status_confirmed]").on("ifChanged", function(event) {;
                    show_status_payin2();
                  });
                  show_status_payin2();
                function show_status_payin2(){
                      var row = $("input[name=status_confirmed]:checked").val();
                      if(row != "1"){
                        $('.show_status_payin2').show(200);
                        $('#detail').prop('required' ,true);
                        $('.show_save_btn').show(200);
                      } else{
                        $('.show_status_payin2').hide(400);
                        $('#detail').prop('required' ,false);
                        $('.show_save_btn').hide(200);
                      }
                  }


         $('#save_pay_in').click(function () {
            var row =  $("input[name=conditional_type]:checked").val();
            if(row == '1'){ // เรียกเก็บค่าธรรมเนียม

                        $.ajax({
                            type:"POST",
                            url:  "{{ url('/certify/check_certificate/check_pay_in_lab') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                lab_id:  "{{ $payin2->app_certi_lab_id ?? null }}",
                                payin : '2'
                            },
                            success:function(data){
                                if(data.message === true){
                                  $('#pay_in2_form').submit();
                                }else{
                                    Swal.fire(data.status_error,'','warning');
                                }
                            }
                    });

            }else if(row == '2' || row == '3'){ // ยกเว้นค่าธรรมเนียม และ ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#pay_in2_form').submit();
             }
          });


          $('#transaction_payin').click(function () {
// alert('ooo')
                var ref1          =   "{{ @$payin2->applicant->app_no ?? null }}";

                if (checkNone(ref1)) {
                    $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังตรวจสอบ กรุณารอสักครู่..."
                             });
                    $.ajax({
                        method: "GET",
                        url: "{{ url('api/v1/checkbill') }}",
                        data: {
                            "ref1": ref1
                        }
                    }).success(function (msg) {
                        // console.log(msg);
                        if(msg.message == true){
                          var  response  =  msg.response;
                        //   console.log('response');
                            $('#ModalPayIn').modal('show');
                            $('#ReceiptCreateDate').val(response.receipt_create_date_th);
                            $('#ReceiptCode').val(response.ReceiptCode);
                            $('#ref1').html(response.ref1);
                            $('#CGDRef1').html(response.CGDRef1);
                            $('#receipt_create_date').html(response.receipt_create_date_th);
                            $('#receipt_code').html(response.ReceiptCode);
                            $('#PayAmountBill').html(addCommas(response.PayAmountBill, 2) );
                            if(response.status_confirmed == 1){
                                $('#StatusPayIn').html('ชำระค่าธรรมเนียมเรียบร้อย');
                            }else{
                                $('#StatusPayIn').html('ยังไม่ชำระค่าธรรมเนียม');
                            }

                            $.LoadingOverlay("hide");
                        }else{
                            $.LoadingOverlay("hide");
                            $('#ModalPayIn').modal('hide');
                            Swal.fire({
                                icon: 'warning',
                                width: 600,
                                position: 'center',
                                title: 'กรุณารอข้อมูล E-payment จาก สมอ.',
                                showConfirmButton: true,
                            });
                        }

                    });
                }
             });


        $('#SaveModalPayIn').click(function () {
             $('#ModalPayIn').modal('hide');
             $('#pay_in2_form').submit();
        });


    $('#pay_in2_form').parsley().on('field:validated', function() {
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



     function conditional(){
           var status = $("input[name=conditional_type]:checked").val();
           if(status == '1'){ // เรียกเก็บค่าธรรมเนียม
                $('#remark').prop('required' ,false);
                $('.div-collect').show();
                $('.div-except').hide();
                $('.div-other_cases').hide();
           }else if(status == '2'){ // ยกเว้นค่าธรรมเนียม
                $('#remark').prop('required' ,false);
                $('.div-collect').hide();
                $('.div-except').show();
                $('.div-other_cases').hide();
           }else if(status == '3'){  //  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#remark').prop('required' ,true);
                $('.div-collect').hide();
                $('.div-except').hide();
                $('.div-other_cases').show();
           }
      }

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
     function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


  </script>
  <!-- จบ แนบใบ Pay-in ครั้งที่ 2 -->

@endpush

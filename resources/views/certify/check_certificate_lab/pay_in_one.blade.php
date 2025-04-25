{{-- work on CheckCertificateLabController --}}
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
                    <h3 class="box-title pull-left"> Pay-In ครั้งที่ 1</h3>
                    @can('view-'.str_slug('estimatedcostcb'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>


   {!! Form::open(['url' => 'certify/check_certificate/update/status/pay_in1/'.$find_cost_assessment->id, 
                'class' => 'form-horizontal', 
                'method' => 'POST',
                    'files' => true,
                    'id'=>'form_pay_in1' ])
     !!}


<div class="form-group  {{ $errors->has('conditional_type') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('conditional_type', '<span class="text-danger">*</span>  เงื่อนไขการชำระเงิน :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-9">
        <label>{!! Form::radio('conditional_type', '1',($find_cost_assessment->conditional_type == 1 || $find_cost_assessment->conditional == 1) ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บค่าธรรมเนียม &nbsp;&nbsp;</label>
        <label>{!! Form::radio('conditional_type', '2',($find_cost_assessment->conditional_type == 2 || $find_cost_assessment->conditional == 2) ? true : false  , ['class'=>'check check-readonly conditional_type', 'data-radio'=>'iradio_square-green']) !!} ยกเว้นค่าธรรมเนียม &nbsp;&nbsp;</label>
        <label>{!! Form::radio('conditional_type', '3', $find_cost_assessment->conditional_type == 3 ? true :  false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ </label>
    </div>
</div>

<div class="row form-group">
    <div class="  {{ $errors->has('auditor') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('auditor', 'คณะผู้ตรวจประเมิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
        <div class="col-md-8 ">
            <p class="col-md-12 text-left ">   
                    {{ $find_cost_assessment->auditor ?? null }}
            </p>
        </div>
    </div>
    </div>
    <div class="form-group  {{ $errors->has('auditor') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('auditor', 'วันที่ตรวจประเมิน :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            <p class="col-md-12 text-left ">   
                {{  !empty($find_cost_assessment->date_board_auditor) ?  $find_cost_assessment->date_board_auditor : null}}
            </p>  
        </div>
    </div>

@if($find_cost_assessment->state == null)
<div class="row form-group  div-collect">
    <div class=" {{ $errors->has('amount') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('amount', '<span class="text-danger">*</span> จำนวนเงิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
        <div class="col-md-4">
            {!! Form::text('amount', 
                    !empty($find_cost_assessment->amount) ? $find_cost_assessment->amount : null,
                    ['class'=>'form-control input_number text-right','required' => true,'id'=>'amount']) 
            !!}
        </div>
        <div class="col-md-4">
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                    รายการค่าใช้จ่าย
                </button>
        </div>
    </div>
    </div>
         <!-- Start เรียกเก็บค่าธรรมเนียม  -->
  <div class="form-group div-collect  {{ $errors->has('report_date') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> วันที่แจ้งชำระ :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-4">
          <div class="input-group">
              {!! Form::text('report_date', 
                  !empty($find_cost_assessment->report_date) ?  HP::revertDate($find_cost_assessment->report_date,true)  :  HP::revertDate(date('Y-m-d'),true) ,  
                  ['class' => 'form-control mydatepicker text-right','placeholder'=>'dd/mm/yyyy','required' => true,'id'=>"report_date"])
              !!}
              <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
          {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="col-md-4">
               {{-- <button type="button" class="btn btn-primary" onclick="myPrints()" id="myPrint"  >พิมพ์</button> --}}
      </div>
  </div>
     <!-- End เรียกเก็บค่าธรรมเนียม  -->

  <!-- Start ยกเว้นค่าธรรมเนียม  -->
  <div class="form-group div-except {{ $errors->has('DatePayIn1') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('DatePayIn1', 'ช่วงเวลาการยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <label class="control-label">    {{ !empty($feewaiver->DatePayIn1)  ? $feewaiver->DatePayIn1 : null   }}</label>  
    </div>
</div>
@if(!empty($feewaiver->payin1_file) && HP::checkFileStorage($feewaiver->payin1_file)) 
<div class="form-group div-except {{ $errors->has('report_payin1_filedate') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('payin1_file', 'เอกสารยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
            <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver->payin1_file).'/'.( !empty($feewaiver->payin1_filename) ? $feewaiver->payin1_filename :  basename($feewaiver->payin1_file)  ))}}" target="_blank">
            {!! HP::FileExtension($feewaiver->payin1_file)  ?? '' !!}
            </a>
    </div>
</div>
@endif
  <!-- End ยกเว้นค่าธรรมเนียม  -->

<!-- Start ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
<div class="form-group div-other_cases {{ $errors->has('detail') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('detail', '<span class="text-danger">*</span> หมายเหตุ :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        @if (!is_null($find_cost_assessment->detail))
               <p class="text-left">{{!empty($find_cost_assessment->detail) ? $find_cost_assessment->detail: null}} </p>
        @else
                {!! Form::textarea('detail', null, ['class' => 'form-control', 'rows'=>'3','id'=>'detail']); !!}
        @endif
      
    </div>
</div>
<div class="form-group div-other_cases {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
    {!! HTML::decode(Form::label('other_attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        @if (!is_null($find_cost_assessment->amount_invoice))
                <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ))}}" target="_blank">
                    {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
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
                     <input type="file" name="other_attach" accept=".pdf " id="other_attach" class="check_max_size_file" >
                 </span>
                 <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
             </div>
         @endif
    </div>
</div> 
  <!-- End ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
@else  

@if (!empty($find_cost_assessment->amount) )
<div class="row">
    <label class="col-sm-3 text-right">จำนวนเงิน :</label>
    <div class="col-sm-8">
        <p>{{ number_format($find_cost_assessment->amount,2)}} บาท</p>
    </div>
</div>   
@endif

@if ($find_cost_assessment->conditional_type == 1)   <!--  เรียกเก็บค่าธรรมเนียม  -->
    <div class="row">
        <label class="col-md-3 text-right">วันที่แจ้งชำระ :</label>
        <div class="col-md-8">
            <p>  {{!empty($find_cost_assessment->report_date) ? HP::DateThai($find_cost_assessment->report_date) : ' ' }} </p>
        </div>
    </div>
    @if (!is_null($find_cost_assessment->amount_invoice))
    <div class="row">
        <label class="col-md-3 text-right">ค่าบริการในการตรวจประเมิน : </label> 
        <div class="col-md-8">
            <p>
                <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ))}}" target="_blank">
                    {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                </a>
            <p>
        </div> 
    </div>
    @endif

@elseif ($find_cost_assessment->conditional_type == 2)   <!--  เรียกเก็บค่าธรรมเนียม  -->
      <div class="row">
        <label class="col-sm-3 text-right">ช่วงเวลาการยกเว้นค่าธรรมเนียม :</label>
        <div class="col-sm-8">
            <p>    {{  !empty($find_cost_assessment->start_date_feewaiver) && !empty($find_cost_assessment->end_date_feewaiver) ? HP::DateFormatGroupTh($find_cost_assessment->start_date_feewaiver,$find_cost_assessment->end_date_feewaiver) :  '-' }}</p>
        </div>
      </div>
    @if (!is_null($find_cost_assessment->amount_invoice))
        <div class="form-group div-except {{ $errors->has('report_payin1_filedate') ? 'has-error' : ''}}">
            <label class="col-sm-3 text-right">เอกสารยกเว้นค่าธรรมเนียม :</label>
            <div class="col-md-4">
                <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ))}}" target="_blank">
                    {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                </a>
            </div>
        </div>
    @endif 
@elseif ($find_cost_assessment->conditional_type == 3)   <!--  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
    <div class="row">
        <label class="col-sm-3 text-right">หมายเหตุ :</label>
        <div class="col-sm-8">
            <p>  {{ !empty($find_cost_assessment->detail)  ? $find_cost_assessment->detail : null   }}</p>
        </div>
    </div>
    @if (!is_null($find_cost_assessment->amount_invoice))
    <div class="row">
        <label class="col-sm-3 text-right">ไฟล์แนบ : </label> 
        <div class="col-sm-8">
            <p>
                <a href="{{url('certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ))}}" target="_blank">
                    {!! HP::FileExtension($find_cost_assessment->amount_invoice)  ?? '' !!}
                </a>
            <p>
        </div> 
    </div>
    @endif
@endif


@endif


 <!-- ผปก  ส่งให้  จนท  แล้ว -->
 @if ($find_cost_assessment->state != 1 && !is_null($find_cost_assessment->invoice))

    <legend><h3>หลักฐานการชำระเงิน</h3></legend>   
    <div class="row">
    <label class="col-sm-4 text-right"> หลักฐานการชำระเงินค่าตรวจประเมิน :</label> 
    <div class="col-sm-6">
        <p>
            <a href="{{url('certify/check/file_client/'.$find_cost_assessment->invoice.'/'.( !empty($find_cost_assessment->invoice_client_name) ? $find_cost_assessment->invoice_client_name :   basename($find_cost_assessment->invoice) ))}}" target="_blank">
                {!! HP::FileExtension($find_cost_assessment->invoice)  ?? '' !!}
            </a>
        <p>
    </div>
    </div>
    @if($find_cost_assessment->remark != null)
        <div class="row">
            <label class="col-sm-4 text-right"> หมายเหตุ :</label>
            <div class="col-sm-7"> {{ $find_cost_assessment->remark ?? null}} </div>
        </div>
    @else 
        <div class="row form-group">
            <label class="col-sm-4 text-right">ตรวจสอบการชำค่าตรวจประเมิน :</label>
             <div class="col-sm-7">
                <label>
                    <input type="radio" name="status_confirmed" value="1" 
                    {{ (is_null($find_cost_assessment->status_confirmed)  || $find_cost_assessment->status_confirmed == 1) ? 'checked':'' }}  
                     class="check" 
                     data-radio="iradio_square-green">
                    &nbsp;ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว &nbsp;
                </label>
                <label>
                    <input type="radio" name="status_confirmed" value="0"  
                     {{ (!is_null($find_cost_assessment->status_confirmed)  && $find_cost_assessment->status_confirmed == 0) ? 'checked':'' }}   
                      class="check {{ (!empty($find_cost_assessment->conditional_type)  && $find_cost_assessment->conditional_type == 1) ? 'check-readonly':'' }} "
                       data-radio="iradio_square-red"  > 
                    &nbsp;ยังไม่ได้ชำระเงิน &nbsp;
                </label>
             </div>
        </div>
        <div class="row show_status_confirmed form-group">
            <label class="col-sm-4 text-right">หมายเหตุ : </label>
            <div class="col-sm-7">
                    {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows'=>'3','id'=>'remark']); !!}
            </div>
        </div>
        @if (!empty($find_cost_assessment->conditional_type)  && $find_cost_assessment->conditional_type == 1 && ($find_cost_assessment->state == null || $find_cost_assessment->state == 2))
            <div class="row form-group">
                <label class="col-sm-4 text-right"></label>
                <div class="col-sm-7">
                        <button type="button" class="btn btn-warning" id="transaction_payin">ตรวจสอบการชำระ</button>
                </div>
            </div>  
        @endif   

    @endif 
    
@endif 

@php
    $payin =   $find_cost_assessment->transaction_payin_to;
@endphp
@if (   $find_cost_assessment->conditional_type  == 1 &&  
        $find_cost_assessment->state == 2 && !is_null($payin)  && 
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

    @if (!empty($find_cost_assessment->ConditionPayName))
    <div class="row form-group">
        <label class="col-sm-4 text-right"><span class="text-danger">*</span> เงื่อนไขการชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('condition_pay', $find_cost_assessment->ConditionPayName, ['id'=>'condition_pay', 'class' => 'form-control','disabled'=>true]) !!}
        </div>
    </div>   
    @endif
    
    <div class="row form-group">
        <label class="col-sm-4 text-right"><span class="text-danger">*</span> วันที่ชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('ReceiptCreateDate', !empty($payin->ReceiptCreateDate) ?  HP::DateTimeThai($payin->ReceiptCreateDate)    : null, ['id'=>'ReceiptCreateDate', 'class' => 'form-control','disabled'=>true]) !!}
        </div>
    </div>

    <div class="row  form-group">
        <label class="col-sm-4 text-right">เลขที่ใบเสร็จรับเงิน / เลขอ้างอิงการชำระ :</label>
        <div class="col-sm-4">
                {!! Form::text('ReceiptCode', !empty($payin->ReceiptCode) ?   $payin->ReceiptCode : null, ['id'=>'ReceiptCode', 'class' => 'form-control', 'disabled'=>true]) !!}
        </div>
    </div>
@endif

{{-- {{$find_cost_assessment->state}} --}}
@if($find_cost_assessment->state == null || $find_cost_assessment->state == 2)
    <input type="hidden" name="previousUrl" id="previousUrl" value="{{   app('url')->previous() }}">
    <div class="row form-group">
        <div class="col-md-offset-4 col-md-4 m-t-15">
            @if ($find_cost_assessment->state == null)  <!--  เงื่อนไขการชำระเงิน -->
                <button class="btn btn-primary" type="button" id="save_pay_in"  >
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <a class="btn btn-default" href="{{  app('url')->previous() }}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก 
                </a>
            @elseif ($find_cost_assessment->conditional_type != 1)  <!-- กรณีไม่ใช่เรียกเก็บค่าธรรมเนียม  -->
                <button class="btn btn-primary" type="submit"   >
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <a class="btn btn-default" href="{{  app('url')->previous() }}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก 
                </a>
            @elseif($find_cost_assessment->conditional_type  == 1 &&  
                    $find_cost_assessment->state == 2 && 
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
            @else 
                <a class="btn btn-lg btn-block  btn-default" href="{{ app('url')->previous() }}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endif
        </div>
    </div>
 @else 

    <a class="btn btn-lg btn-block  btn-default" href="{{ app('url')->previous() }}">
        <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
@endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ค่าใช้จ่าย</h4>
        </div>
        <div class="modal-body">
            <table class="table color-bordered-table primary-bordered-table">
                <thead>
                    <tr>
                        <th class="text-center" width="2%">#</th>
                        <th class="text-center" width="38%">รายละเอียด</th>
                        <th class="text-center" width="20%">จำนวนเงิน</th>
                        <th class="text-center" width="10%">จำนวนวัน</th>
                        <th class="text-center" width="20%">รวม (บาท)</th>
 
                    </tr>
                </thead>
                <tbody id="table_body">
                    @if(!empty($find_cost_assessment->cost_item_confirm)  &&  count($find_cost_assessment->cost_item_confirm) > 0 )
                    @foreach($find_cost_assessment->cost_item_confirm as  $key => $item)
                    <tr>
                        <td  class="text-center">
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {!!   $item->StatusAuditorTo->title ?? null  !!}
                        </td>
                        <td>
                            {!! number_format($item->amount,2) ?? null !!}
                        </td>
                        <td>
                            {!!   $item->amount_date ?? null !!}
                        </td>
                        <td>
                            {!!   number_format(($item->amount_date *  $item->amount),2)  ?? null !!}
                        </td>
                    </tr>
                     @endforeach  
                @endif
                </tbody>
                <footer>
                    <tr>
                        <td colspan="4" class="text-right">รวม</td>
                        <td>
                            {!!  !empty($find_cost_assessment->sum_cost) ? $find_cost_assessment->sum_cost  : null  !!}
                        </td>
                    </tr>
                </footer>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    <script src="{{ asset('js/function.js') }}"></script>
    <!-- Data Table -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
         <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 1 -->
         <script type="text/javascript">
            jQuery(document).ready(function() {
                var check = '{{  !empty($find_cost_assessment) &&  ($find_cost_assessment->status_confirmed == 1) ? 1 : null  }}';
                    if(check == 1){
                        $('.check_readonly_1').prop('disabled', true); 
                        $('.check_readonly_1').parent().removeClass('disabled');
                        // $('.check_readonly_1').parent().css('margin-top', '8px');
                        $('.check_readonly_1').parent().css('margin-top', '8px').css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor": "not-allowed"});
                    }
        
                 $("input[name=status_confirmed]").on("ifChanged", function(event) {;
                    status_show_status_confirmed();
                  });
                  status_show_status_confirmed();
                function status_show_status_confirmed(){
                      var row = $("input[name=status_confirmed]:checked").val();
                      if(row != "1"){ 
                        $('.show_status_confirmed').show(200);
                        $('.assessment_desc').prop('required' ,true);
                      } else{
                        $('.show_status_confirmed').hide(400);
                        $('.assessment_desc').prop('required' ,false);
                      }
                  }
             });
         </script>
         <!-- จบ แนบใบ Pay-in ครั้งที่ 1 -->
    
  <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 1 -->
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
            var conditional_type = '{{  !empty($find_cost_assessment->conditional_type)  ? 1 : 2  }}';
            if (conditional_type == '1') {
                $('.check-readonly').prop('disabled', true); 
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor": "not-allowed"});
            }
                conditional();
        $("input[name=conditional_type]").on("ifChanged",function(){
                conditional();
            });



         $('#save_pay_in').click(function () { 
            var row =  $("input[name=conditional_type]:checked").val();
            
            if(row == '1'){ // เรียกเก็บค่าธรรมเนียม
                    const amount  = $('#amount').val();
                    const start_date  =   $('#report_date').val();
                    if(start_date != '' && amount != ''){
                        $.ajax({
                            type:"post",
                            url:  "{{ url('/certify/check_certificate/check_pay_in_lab') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                lab_id:  "{{ $certi_lab->id ?? null }}",
                                id:  "{{ $find_cost_assessment->id ?? null }}",
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
            }else if(row == '2' || row == '3'){ // ยกเว้นค่าธรรมเนียม และ ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#form_pay_in1').submit();
             }  
          });

          $('#transaction_payin').click(function () { 

                var app_no          =   "{{ $certi_lab->app_no ?? null }}";
                var assessment_id   =   "{{ $find_cost_assessment->app_certi_assessment_id ?? null }}";
                var ref1            = app_no+'-'+assessment_id;

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
                        if(msg.message == true){
                          var  response  =  msg.response;   
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
             $('#form_pay_in1').submit();
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

         function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

         function conditional(){
           var status = $("input[name=conditional_type]:checked").val();
           if(status == '1'){ // เรียกเก็บค่าธรรมเนียม
                $('#amount').prop('required' ,true);  
                $('.mydatepicker').prop('required' ,true);  
                $('#detail').prop('required' ,false);  
                $('.div-collect').show();  
                $('.div-except').hide();    
                $('.div-other_cases').hide();   

           }else if(status == '2'){ // ยกเว้นค่าธรรมเนียม

                $('#amount').prop('required' ,false);  
                $('.mydatepicker').prop('required' ,false);  
                $('#detail').prop('required' ,false);  
                $('.div-collect').hide();  
                $('.div-except').show();   
                $('.div-other_cases').hide();   
           }else if(status == '3'){  //  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#amount').prop('required' ,false);  
                $('.mydatepicker').prop('required' ,false);  
                $('#detail').prop('required' ,true);  
                $('.div-collect').hide();  
                $('.div-except').hide();   
                $('.div-other_cases').show(); 
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
  <!-- จบ แนบใบ Pay-in ครั้งที่ 1 -->

@endpush

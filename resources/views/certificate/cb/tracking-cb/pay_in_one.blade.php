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
                    <h3 class="box-title pull-left"> Pay-In ครั้งที่ 1 </h3>
                    @can('view-'.str_slug('trackingcb'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                      
 {!! Form::open(['url' => 'certificate/tracking-cb/pay-in/'.$pay_in->id,
                'class' => 'form-horizontal', 
                'files' => true,
                'method' => 'POST',
                'id'=>"form_pay_in1"]) 
!!}

@php 
$SumCost = !empty($pay_in->auditors_to->SumCostConFirm) ? $pay_in->auditors_to->SumCostConFirm :  '0.00';
@endphp
 

<div class="form-group  {{ $errors->has('conditional_type') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('conditional_type', '<span class="text-danger">*</span>  เงื่อนไขการชำระเงิน :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-9">
        <label>{!! Form::radio('conditional_type', '1',($pay_in->conditional_type == 1 || $pay_in->conditional == 1) ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บค่าธรรมเนียม &nbsp;&nbsp;</label>
        <label>{!! Form::radio('conditional_type', '2',($pay_in->conditional_type == 2 || $pay_in->conditional == 2) ? true : false  , ['class'=>'check check-readonly conditional_type', 'data-radio'=>'iradio_square-green']) !!} ยกเว้นค่าธรรมเนียม &nbsp;&nbsp;</label>
        <label>{!! Form::radio('conditional_type', '3', $pay_in->conditional_type == 3 ? true :  false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ </label>
    </div>
</div>
 
 <div class="row form-group">
  <div class="  {{ $errors->has('auditor') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('auditor', 'คณะผู้ตรวจประเมิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-8 ">
          <p class="col-md-12 text-left ">   
               {{ $pay_in->auditors_to->auditor ?? null }}
          </p>
      </div>
  </div>
</div>
<div class="form-group  {{ $errors->has('auditor') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('auditor', 'วันที่ตรวจประเมิน :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <p class="col-md-12 text-left ">   
            {{  !empty($pay_in->auditors_to->CertiAuditorsDateTitle) ?  $pay_in->auditors_to->CertiAuditorsDateTitle : null}}
        </p>  
    </div>
</div>

@if($pay_in->state == null)
<div class="row form-group div-tradition">
  <div class=" {{ $errors->has('amount') ? 'has-error' : ''}}">
          {!! HTML::decode(Form::label('amount', '<span class="text-danger">*</span> จำนวนเงิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-4">
          {!! Form::text('amount', 
               !empty($pay_in->amount) ? number_format($pay_in->amount,2) :  @$SumCost,
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
  <div class="form-group div-collect  {{ $errors->has('start_date') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('start_date', '<span class="text-danger">*</span> วันที่แจ้งชำระ :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-4">
          <div class="input-group">
              {!! Form::text('start_date', 
                  !empty($pay_in->start_date) ?  HP::revertDate($pay_in->start_date,true)  :  HP::revertDate(date('Y-m-d'),true) ,  
                  ['class' => 'form-control mydatepicker text-right','placeholder'=>'dd/mm/yyyy','required' => true,'id'=>"start_date"])
              !!}
              <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
          {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
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
            @if (!is_null($pay_in->detail))
            <p class="text-left">{{!empty($pay_in->detail) ? $pay_in->detail: null}} </p>
            @else
            {!! Form::textarea('detail', null, ['class' => 'form-control', 'rows'=>'3','id'=>'detail']); !!}
            @endif
          
        </div>
    </div>
    <div class="form-group div-other_cases {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
        {!! HTML::decode(Form::label('other_attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            @if (!is_null($pay_in->FileAttachPayInOne1To))
                <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInOne1To->url.'/'.( !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename :  basename($pay_in->FileAttachPayInOne1To->url)  ))}}" 
                    title="{{  !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename : basename($pay_in->FileAttachPayInOne1To->url) }}" target="_blank">
                    {!! HP::FileExtension($pay_in->FileAttachPayInOne1To->url)  ?? '' !!}
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
      <!-- End ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
 
@else

    @if (!empty($pay_in->amount) )
    <div class="row">
        <label class="col-sm-3 text-right">จำนวนเงิน :</label>
        <div class="col-sm-8">
            <p>{{ number_format($pay_in->amount,2)}} บาท</p>
        </div>
    </div>   
    @endif

   @if ($pay_in->conditional_type == 1)   <!--  เรียกเก็บค่าธรรมเนียม  -->
        <div class="row">
            <label class="col-md-3 text-right">วันที่แจ้งชำระ :</label>
            <div class="col-md-8">
                <p>  {{!empty($pay_in->start_date) ? HP::DateThai($pay_in->start_date) : ' ' }} </p>
            </div>
        </div>
        @if (!is_null($pay_in->FileAttachPayInOne1To))
        <div class="row">
            <label class="col-md-3 text-right">ค่าบริการในการตรวจประเมิน : </label> 
            <div class="col-md-8">
                <p>
                    <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInOne1To->url.'/'.( !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename :  basename($pay_in->FileAttachPayInOne1To->url)  ))}}" 
                        title="{{  !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename : basename($pay_in->FileAttachPayInOne1To->url) }}" target="_blank">
                        {!! HP::FileExtension($pay_in->FileAttachPayInOne1To->url)  ?? '' !!}
                    </a> 
                <p>
            </div> 
        </div>
        @endif

   @elseif ($pay_in->conditional_type == 2)   <!--  เรียกเก็บค่าธรรมเนียม  -->
          <div class="row">
            <label class="col-sm-3 text-right">ช่วงเวลาการยกเว้นค่าธรรมเนียม :</label>
            <div class="col-sm-8">
                <p>    {{  !empty($pay_in->start_date_feewaiver) && !empty($pay_in->end_date_feewaiver) ? HP::DateFormatGroupTh($pay_in->start_date_feewaiver,$pay_in->end_date_feewaiver) :  '-' }}</p>
            </div>
          </div>
        @if (!is_null($pay_in->FileAttachPayInOne1To))
            <div class="form-group div-except {{ $errors->has('report_payin1_filedate') ? 'has-error' : ''}}">
                <label class="col-sm-3 text-right">เอกสารยกเว้นค่าธรรมเนียม :</label>
                <div class="col-md-4">
                        <a href="{{url('funtions/get-view-file/'.base64_encode($pay_in->FileAttachPayInOne1To->url).'/'.( !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename :  basename($pay_in->FileAttachPayInOne1To->url)  ))}}" target="_blank">
                        {!! HP::FileExtension($pay_in->FileAttachPayInOne1To->url)  ?? '' !!}
                        </a>
                </div>
            </div>
        @endif 
    @elseif ($pay_in->conditional_type == 3)   <!--  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
        <div class="row">
            <label class="col-sm-3 text-right">หมายเหตุ :</label>
            <div class="col-sm-8">
                <p>  {{ !empty($pay_in->detail)  ? $pay_in->detail : null   }}</p>
            </div>
        </div>
        @if (!is_null($pay_in->FileAttachPayInOne1To))
        <div class="row">
            <label class="col-sm-3 text-right">ไฟล์แนบ : </label> 
            <div class="col-sm-8">
                <p>
                    <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInOne1To->url.'/'.( !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename :  basename($pay_in->FileAttachPayInOne1To->url)  ))}}" 
                        title="{{  !empty($pay_in->FileAttachPayInOne1To->filename) ? $pay_in->FileAttachPayInOne1To->filename : basename($pay_in->FileAttachPayInOne1To->url) }}" target="_blank">
                        {!! HP::FileExtension($pay_in->FileAttachPayInOne1To->url)  ?? '' !!}
                    </a> 
                <p>
            </div> 
        </div>
        @endif
   @endif

 


@endif

 <!-- ผปก  ส่งให้  จนท  แล้ว -->
 @if ($pay_in->state != 1 && !is_null($pay_in->FileAttachPayInOne2To))

    <legend><h3>หลักฐานการชำระเงิน</h3></legend>   
    <div class="row">
    <label class="col-sm-4 text-right"> หลักฐานการชำระเงินค่าตรวจประเมิน :</label> 
    <div class="col-sm-6">
        <p>
            <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInOne2To->url.'/'.( !empty($pay_in->FileAttachPayInOne2To->filename) ? $pay_in->FileAttachPayInOne2To->filename :  basename($pay_in->FileAttachPayInOne2To->url)  ))}}" 
                title="{{  !empty($pay_in->FileAttachPayInOne2To->filename) ? $pay_in->FileAttachPayInOne2To->filename : basename($pay_in->FileAttachPayInOne2To->url) }}" target="_blank">
                {!! HP::FileExtension($pay_in->FileAttachPayInOne2To->url)  ?? '' !!}
            </a> 
        <p>
    </div>
    </div>
    @if($pay_in->remark != null)
    <div class="row">
        <label class="col-sm-4 text-right"> หมายเหตุ :</label>
        <div class="col-sm-7"> {{ $pay_in->remark ?? null}} </div>
    </div>
    @else 
    <div class="row">
        <label class="col-sm-4 text-right">ตรวจสอบการชำค่าตรวจประเมิน :</label>
    <div class="col-sm-7">
        <label>
            <input type="radio" name="status" value="1" {{ (is_null($pay_in->status)  || $pay_in->status == 1) ? 'checked':'' }}   class="check check_readonly_1" data-radio="iradio_square-green">
            &nbsp;ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว &nbsp;
        </label>
        <label>
            <input type="radio" name="status" value="0"   {{ (!is_null($pay_in->status)  && $pay_in->status == 0) ? 'checked':'' }}    class="check check_readonly_1 {{ (!empty($pay_in->conditional_type)  && $pay_in->conditional_type == 1) ? 'check-readonly':'' }}" data-radio="iradio_square-red"  > 
            &nbsp;ยังไม่ได้ชำระเงิน &nbsp;
        </label>
    </div>
    </div>
    <div class="row show_status_confirmed">
    <label class="col-sm-4 text-right">หมายเหตุ : </label>
    <div class="col-sm-7">
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows'=>'3','id'=>'remark']); !!}
    </div>
    </div>
    @endif 

@endif


@if (!empty($pay_in->conditional_type)  && $pay_in->conditional_type == 1 &&  $pay_in->state == 2)
    <div class="row form-group">
        <label class="col-sm-4 text-right"></label>
        <div class="col-sm-7">
                <button type="button" class="btn btn-warning" id="transaction_payin">ตรวจสอบการชำระ</button>
        </div>
    </div>
 @endif  

@if (!empty($pay_in)  && !is_null($pay_in->transaction_payin_to))
@php
    $payin =   $pay_in->transaction_payin_to;
@endphp
<div class="row">
    <label class="col-sm-4 text-right"><span class="text-danger">*</span> วันที่ชำระ :</label>
    <div class="col-sm-8">
        <p>
            {!! !empty($payin->ReceiptCreateDate) ?  HP::DateTimeThai($payin->ReceiptCreateDate)    : '' !!}
        </p>
    </div>
    <label class="col-sm-4 text-right">เลขที่ใบเสร็จรับเงิน / เลขอ้างอิงการชำระ :</label>
    <div class="col-sm-8">
        <p>
            {!!  !empty($payin->ReceiptCode) ?   $payin->ReceiptCode :  '' !!}
        </p>
    </div>
</div>
@endif


@if($pay_in->state == null || $pay_in->state == 2)
<input type="hidden" name="previousUrl" id="previousUrl" value="{{   app('url')->previous() }}">
<div class="row form-group">
    <div class="col-md-offset-4 col-md-4 m-t-15">
      
        @if ($pay_in->state == null)
            <button class="btn btn-primary" type="button" id="save_pay_in"  >
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            <a class="btn btn-default" href="{{  app('url')->previous() }}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @elseif ($pay_in->conditional_type != 1)
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
 {!! Form::close() !!}
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
                @if(count($pay_in->auditors_to->auditors_status_many) > 0 )
                    @foreach($pay_in->auditors_to->auditors_status_many as  $key => $item)
                    <tr>
                        <td  class="text-center">
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {!!   $item->StatusAuditorTitle ?? null  !!}
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
                            {{ $SumCost ?? null }}  บาท
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
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <!-- Data Table -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/function.js')}}"></script>
         <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 1 -->
         <script type="text/javascript">
            jQuery(document).ready(function() {
                var check = '{{  !empty($pay_in) &&  ($pay_in->status == 1) ? 1 : null  }}';
                    if(check == 1){
                        $('.check_readonly_1').prop('disabled', true); 
                        $('.check_readonly_1').parent().removeClass('disabled');
                        $('.check_readonly_1').parent().css('margin-top', '8px').css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
                    }
        
                 $("input[name=status]").on("ifChanged", function(event) {;
                    status_show_status_confirmed();
                  });
                  status_show_status_confirmed();
                function status_show_status_confirmed(){
                      var row = $("input[name=status]:checked").val();
                      if(row != "1"){ 
                        $('.show_status_confirmed').show(200);
                        $('#remark').prop('required' ,true);
                      } else{
                        $('.show_status_confirmed').hide(400);
                        $('#remark').prop('required' ,false);
                      }
                  }

                //    เช็คการชำระ
              $('#transaction_payin').click(function () { 
                    var app_no          =   "{{ $pay_in->reference_refno ?? null }}";
                    var assessment_id   =   "{{ $pay_in->auditors_id ?? null }}";
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

             });
         </script>
         <!-- จบ แนบใบ Pay-in ครั้งที่ 1 -->



             <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 1 -->
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    var feewaiver = '{{  !empty($feewaiver)  ? 1 : 2  }}';
                        if (feewaiver == '2') {
                            $('.check-readonly[value="2"]').prop('disabled', true); 
                            $('.check-readonly[value="2"]').parent().removeClass('disabled');
                            $('.check-readonly[value="2"]').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
                        }
                    var conditional_type = '{{  !empty($pay_in->conditional_type)  ? 1 : 2  }}';
                    if (conditional_type == '1') {
                        $('.check-readonly').prop('disabled', true); 
                        $('.check-readonly').parent().removeClass('disabled');
                        $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
                    }
                         conditional();
                    $("input[name=conditional_type]").on("ifChanged",function(){
                         conditional();
                      });

                         IsInputNumber();
                         
                        $('#form_pay_in1').parsley().on('field:validated', function() {
                            var ok = $('.parsley-error').length === 0;
                            $('.bs-callout-info').toggleClass('hidden', !ok);
                            $('.bs-callout-warning').toggleClass('hidden', ok);
                        })  .on('form:submit', function() {
                                // Text
                                $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังบันทึก กรุณารอสักครู่..."
                                });
                            return true; // Don't submit form for this demo
                        });
                    $('#payin_cancel').change(function () { 
                        if ($(this).prop('checked')){    
                            $('#div-attach').show(500);
                            $('.div-tradition').hide(300);
                            $('#amount').prop('required' ,false);  
                            $('.mydatepicker').prop('required' ,false);  
                            $('#attach').prop('required' ,true);  
                        }else{
                            $('#div-attach').hide(300);
                            $('.div-tradition').show(500);
                            $('#amount').prop('required' ,true);  
                            $('.mydatepicker').prop('required' ,true);  
                            $('#attach').prop('required' ,false);  
                        }
                    });
                    $('#payin_cancel').change();

                    $('#save_pay_in').click(function () { 
                        var row =  $("input[name=conditional_type]:checked").val();
                        if(row == '1'){ // เรียกเก็บค่าธรรมเนียม
                                const amount  = $('#amount').val();
                                const start_date  =   $('#start_date').val();
                                if(start_date != '' && amount != ''){
                                    $.ajax({
                                        type:"GET",
                                        url:  "{{ url('/certificate/tracking-cb/check/pay_in') }}",
                                        data:{
                                            _token: "{{ csrf_token() }}",
                                            id:  "{{ $pay_in->id ?? null }}",
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


                    
                    //ปฎิทิน
                    $('.mydatepicker').datepicker({
                    toggleActive: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy',
                    });
                 });
</script>
<!-- จบ แนบใบ Pay-in ครั้งที่ 1 -->
<script>
  
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
                   $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   }); 
                   
                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
                   $(".input_number").on("change",function(){
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

</script>

@endpush

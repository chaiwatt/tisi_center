
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<!-- Modal -->
<div class="modal fade" id="PayIn1Modal" tabindex="-1" role="dialog" aria-labelledby="PayIn1Modal" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
 <div class="modal-content">
     <div class="modal-header">
     <h4 class="modal-title" id="PayIn1Modal">แนบใบ Pay-in ครั้งที่ 1
     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
     </button>
     </h4>
     </div>
     {{-- readonly --}}
 {!! Form::open(['url' => 'certify/check_certificate-ib/pay-in/'.$id, 'class' => 'form-horizontal', 'files' => true,'id'=>"pay_in1_form"]) !!}
 
 <div class="modal-body">
     <div class="container-fluid">
  @if(count($payin1) > 0)
  @foreach($payin1 as $key => $item)
  @if($item->status != 1)
    @php 
        $SumCost = !empty($item->CertiIBAuditorsTo->SumCostConFirm) ? $item->CertiIBAuditorsTo->SumCostConFirm :  '0.00';
    @endphp
 <div class="row">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
    <legend><h3>{{ $item->CertiIBAuditorsTo->auditor ?? null }}</h3></legend>   
    <input type="hidden" name="payin_id[]"  value="{{ $item->id ?? null}}">

 @if($item->state == null)
<div class="row form-group">
    <div class=" {{ $errors->has('amount') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('amount', 'จำนวนเงิน :', ['class' => 'col-md-5 control-label text-right'])) !!}
        <div class="col-md-7">
            {!! Form::text('amount['.$item->id.']', 
                 !empty($item->amount) ? number_format($item->amount,2) :  @$SumCost,
                 ['class'=>'form-control input_number text-right']) 
            !!}
        </div>
    </div>
</div>
<div class="row form-group">
    <div class="  {{ $errors->has('start_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('start_date', ' วันที่แจ้งชำระ :', ['class' => 'col-md-5 control-label text-right'])) !!}
        <div class="col-md-7">
            <div class="input-group">
                {!! Form::text('start_date['.$item->id.']', 
                    !empty($item->start_date) ?  HP::revertDate($item->start_date,true)  : null,  
                    ['class' => 'form-control mydatepicker text-right','placeholder'=>'dd/mm/yyyy'])
                !!}
                <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
            {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row form-group">
        <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span>  ค่าธรรมเนียมใบตรวจประเมิน :', ['class' => 'col-md-5 control-label text-right'])) !!}
            <div class="col-md-7">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="attach[{{$item->id}}]" class="check_max_size_file">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
        </div>
 </div>

 @elseif($item->state != null)  <!--จนท ส่งให้ ผปก แล้ว -->
<div class="row">
<div class="col-sm-5 text-right"> <b>จำนวนเงิน :</b></div>
<div class="col-sm-6">
    <p>{{!empty($item->amount) ? number_format($item->amount,2) : null}} บาท</p>
</div>
</div>
<div class="row">
<div class="col-sm-5 text-right"> <b>วันที่แจ้งชำระ :</b></div>
<div class="col-sm-6">
    <p>  {{!empty($item->start_date) ? HP::DateThai($item->start_date) : ' ' }} </p>
</div>
</div>

@if (!is_null($item) && !is_null($item->FileAttachPayInOne1To))
<div class="row">
<div class="col-sm-5 text-right"> <b>ใบแจ้งชำระเงินค่าตรวจประเมิน :</b></div> 
<div class="col-sm-6">
    <p>
        <a href="{{url('certify/check/files_ib/'.$item->FileAttachPayInOne1To->file)}}" target="_blank">
            {!! HP::FileExtension($item->FileAttachPayInOne1To->file)  ?? '' !!}
         </a> 
    <p>
</div>
</div>
@endif

@endif  

 <!-- ผปก  ส่งให้  จนท  แล้ว -->
 @if (!is_null($item) && !is_null($item->FileAttachPayInOne2To))

 <legend><h3>หลักฐานการชำระเงิน</h3></legend>   
 
<div class="row">
<div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าตรวจประเมิน :</b></div> 
<div class="col-sm-6">
    <p>
        <a href="{{url('certify/check/files_ib/'.$item->FileAttachPayInOne2To->file)}}" target="_blank">
            {!! HP::FileExtension($item->FileAttachPayInOne2To->file)  ?? '' !!}
         </a> 
    <p>
</div>
</div>


@if($item->remark != null)
<div class="row">
    <div class="col-sm-5 text-right"> <b>หมายเหตุ :</b></div>
    <div class="col-sm-7"> {{ $item->remark ?? null}} </div>
</div>
@else 
<div class="row">
    <div class="col-sm-5 text-right"> <b>ตรวจสอบการชำค่าตรวจประเมิน :</b></div>
<div class="col-sm-7">
    <label>
        <input type="radio" name="payin1_status[{{$item->id}}]" value="1" {{ (is_null($item->status)  || $item->status == 1) ? 'checked':'' }}   class="check payin1_status" data-radio="iradio_square-green">
         &nbsp;ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว &nbsp;
    </label>
    <label>
        <input type="radio" name="payin1_status[{{$item->id}}]" value="0"   {{ (!is_null($item->status)  && $item->status == 0) ? 'checked':'' }}    class="check payin1_status" data-radio="iradio_square-red"  > 
        &nbsp;ยังไม่ได้ชำระเงิน &nbsp;
    </label>
</div>
</div>
<div class="row show_hide_confirmed hide">
<div class="col-sm-5 text-right"><b>หมายเหตุ :</b>  </div>
  <div class="col-sm-7">
        {!! Form::textarea('remark['.$item->id.']', null, ['class' => 'form-control assessment_desc', 'rows'=>'3']); !!}
</div>
</div>
@endif  

 @endif  

        </div>
     </div>
</div>
@endif  
    @endforeach
@endif

<!-- START  Show  -->
@if($certi_ib->CertiIBPayInOneStatus == "StatusPayInOneNeat")
@if(count($payin1) > 0)
@foreach($payin1 as $key => $item)
 <div class="row">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
       <legend><h3>{{ $item->CertiIBAuditorsTo->auditor ?? null }}</h3></legend>   
<div class="row">
<div class="col-sm-5 text-right"> <b>จำนวนเงิน :</b></div>
<div class="col-sm-6">
    <p>{{!empty($item->amount) ? number_format($item->amount,2) : null}} บาท</p>
</div>
</div>
<div class="row">
<div class="col-sm-5 text-right"> <b>วันที่แจ้งชำระ :</b></div>
<div class="col-sm-6">
    <p>  {{!empty($item->start_date) ? HP::DateThai($item->start_date) : ' ' }} </p>
</div>
</div>

@if (!is_null($item) && !is_null($item->FileAttachPayInOne1To))
<div class="row">
<div class="col-sm-5 text-right"> <b>ใบแจ้งชำระเงินค่าตรวจประเมิน :</b></div> 
<div class="col-sm-6">
    <p>
        <a href="{{url('certify/check/files_ib/'.$item->FileAttachPayInOne1To->file)}}" target="_blank">
            {!! HP::FileExtension($item->FileAttachPayInOne1To->file)  ?? '' !!}
         </a> 
    <p>
</div>
</div>
@endif
<legend><h3>หลักฐานการชำระเงิน</h3></legend>   
 
<div class="row">
<div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าตรวจประเมิน :</b></div> 
<div class="col-sm-6">
    <p>
        <a href="{{url('certify/check/files_ib/'.$item->FileAttachPayInOne2To->file)}}" target="_blank">
            {!! HP::FileExtension($item->FileAttachPayInOne2To->file)  ?? '' !!}
         </a> 
    <p>
</div>
</div>

        </div>
     </div>
</div>
@endforeach
@endif
@endif
<!-- END Show  -->

    </div>
</div>

<div class="modal-footer">
    @if($certi_ib->CertiIBPayInOneStatus != "StatePayInOne" && $certi_ib->CertiIBPayInOneStatus != "StatusPayInOneNeat")   
    <button type="button" class="btn btn-secondary " data-dismiss="modal">ยกเลิก</button>
    <button   type="submit" class="btn btn-primary " onclick="submit_form_pay1();return false">บันทึก</button>
    @endif
</div>

 {!! Form::close() !!}


     </div>
   </div>
</div>

@push('js')

    <script>
       function submit_form_pay1() {
            Swal.fire({
                    title: 'ยืนยันทำรายการ !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                         // Text
                           $.LoadingOverlay("show", {
                            image       : "",
                            text  : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                            $('#pay_in1_form').submit();
                        }
                    })
            }
    </script>
@endpush
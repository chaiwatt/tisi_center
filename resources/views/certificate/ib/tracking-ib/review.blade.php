@extends('layouts.master')
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left"> ระบบสรุปผลการตรวจประเมิน </h3>
                    @can('view-'.str_slug('trackinglabs'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                      
 {!! Form::open(['url' => 'certificate/tracking-labs/update_inspection/'.$inspection->id,
                'class' => 'form-horizontal', 
                'files' => true,
                'method' => 'POST',
                'id'=>"form-inspection"]) 
!!}
 

 
 
<div class="row form-group">
  <div class="  {{ $errors->has('reference_refno') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('reference_refno', 'เลขที่อ้างอิง :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-8 ">
          {!! Form::text('reference_refno', (!empty($cer->reference_refno) ? $cer->reference_refno  : null) , ['id' => 'reference_refno', 'class' => 'form-control',  'disabled' => true]); !!}
      </div>
  </div>
</div>
 <div class="row form-group">
  <div class="  {{ $errors->has('org_name') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('org_name', 'ชื่อผู้ยื่นคำขอ :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-8 ">
          {!! Form::text('org_name', (!empty($cer->org_name) ? $cer->org_name  : null) , ['id' => 'org_name', 'class' => 'form-control',  'disabled' => true]); !!}
      </div>
  </div>
</div>
 <div class="row form-group">
  <div class="  {{ $errors->has('lab_name') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('lab_name', 'ชื่อห้องปฏิบัติการ :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-8 ">
          {!! Form::text('lab_name', (!empty($cer->lab_name) ? $cer->lab_name  : null) , ['id' => 'lab_name', 'class' => 'form-control',  'disabled' => true]); !!}
      </div>
  </div>
</div>
 <div class="row form-group"> 
  <div class="  {{ $errors->has('auditors_count') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('auditors_count', 'จำนวนครั้งการตรวจประเมิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-4">
          <div class="input-group" >
                    {!! Form::text('auditors_count',  (!empty($cer->tracking_assessment_many) ? count($cer->tracking_assessment_many)  : null) , ['class' => 'form-control text-center','id'=>'auditors_count',  'disabled' => true]) !!}
                    <span class="input-group-addon bg-secondary  b-0 text-dark"> ครั้ง </span>
          </div>
      </div>
  </div>
</div>
 <div class="row form-group"> 
  <div class="  {{ $errors->has('amount_bill_all') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('amount_bill_all', 'ค่าใช้จ่ายทั้งหมด :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-4">
           <div class="input-group" >
                    {!! Form::text('amount_bill_all',  (!empty($cer->AmountBillAll) ?  number_format($cer->AmountBillAll,2)   : null) , ['class' => 'form-control text-right','id'=>'amount_bill_all',  'disabled' => true]) !!}
                    <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
          </div>
      </div>
  </div>
</div>

<div class="row form-group" >
    <div class="col-md-12">
         <div class="white-box" style="border: 2px solid #e5ebec;">
              <legend><h3>ผลการตรวจประเมิน</h3></legend>  
              <hr> 



  @if ( count($cer->tracking_assessment_many)  > 0)
      @foreach ($cer->tracking_assessment_many as  $key => $item)
<div class="row">
   <div class="col-md-12">
         <div class="panel block4">
            <div class="panel-group" id="accordion{{($key+1)}}">
               <div class="panel panel-info">
                 <div class="panel-heading">
                    <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion{{($key+1)}}" href="#collapse{{($key+1)}}"> <dd>{!!   (!empty($item->auditors_to->auditor) ? $item->auditors_to->auditor.' ครั้งที่ '.($key+1)  : null)  !!}</dd>  </a>
                    </h4>
                </div>

<div id="collapse{{($key+1)}}" class="panel-collapse collapse in">
<br>

 <div class="row form-group"> 
  <div class="  {{ $errors->has('report_date') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> วันที่ทำรายงาน :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-5">
           {!! Form::text('report_date',  (!empty($item->report_date) ?  HP::DateThai($item->report_date)   : null) , ['class' => 'form-control' ,  'disabled' => true]) !!}
      </div>
  </div>
</div>
 <div class="row form-group"> 
  <div class="  {{ $errors->has('amount_bill_all') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('amount_bill_all', '<span class="text-danger">*</span> วันที่ตรวจประเมิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-5">
           {!! Form::text('amount_bill_all',  (!empty($item->auditors_to->CertiAuditorsDateTitle) ?  $item->auditors_to->CertiAuditorsDateTitle   : null), ['class' => 'form-control' ,  'disabled' => true]) !!}
      </div>
  </div>
</div>
 <div class="row form-group"> 
  <div class="  {{ $errors->has('status') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> รายงานข้อบกพร่อง :', ['class' => 'col-md-3 control-label text-right'])) !!}
      <div class="col-md-5">
          <div class="row">
          <label class="col-md-3">
                    {!! Form::radio('', '1', $item->status == 1 ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
          </label>
          <label class="col-md-3">
                    {!! Form::radio('', '2',  $item->status != 1 ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
          </label>
         </div>
      </div>
  </div>
</div>

@if(isset($item)  && !empty($item->FileAttachAssessment1To)) 
<div class="row form-group"> 
          <div class="  {{ $errors->has('amount_bill_all') ? 'has-error' : ''}}">
              {!! HTML::decode(Form::label('amount_bill_all', '<span class="text-danger">*</span> รายงานการตรวจประเมิน :', ['class' => 'col-md-3 control-label text-right'])) !!}
              <div class="col-md-5">
                    <a href="{{url('funtions/get-view/'.$item->FileAttachAssessment1To->url.'/'.( !empty($item->FileAttachAssessment1To->filename) ? $item->FileAttachAssessment1To->filename : 'null' ))}}" 
                              title="{{ !empty($item->FileAttachAssessment1To->filename) ? $item->FileAttachAssessment1To->filename :  basename($item->FileAttachAssessment1To->url) }}" target="_blank">
                              {!! HP::FileExtension($item->FileAttachAssessment1To->url)  ?? '' !!}
                     </a>
              </div>
          </div>
 </div>
@endif
@if(isset($item)  && !empty($item->FileAttachAssessment5To)) 
<div class="row form-group"> 
          <div class="  {{ $errors->has('amount_bill_all') ? 'has-error' : ''}}">
              {!! HTML::decode(Form::label('amount_bill_all', '<span class="text-danger">*</span> รายงานปิด Car :', ['class' => 'col-md-3 control-label text-right'])) !!}
              <div class="col-md-5">
                    <a href="{{url('funtions/get-view/'.$item->FileAttachAssessment5To->url.'/'.( !empty($item->FileAttachAssessment5To->filename) ? $item->FileAttachAssessment5To->filename : 'null' ))}}" 
                              title="{{ !empty($item->FileAttachAssessment5To->filename) ? $item->FileAttachAssessment5To->filename :  basename($item->FileAttachAssessment5To->url) }}" target="_blank">
                              {!! HP::FileExtension($item->FileAttachAssessment5To->url)  ?? '' !!}
                     </a>
              </div>
          </div>
 </div>
@endif
@if(isset($item)  && !empty($item->FileAttachAssessment4Many)) 
<div class="row form-group"> 
          <div class="  {{ $errors->has('amount_bill_all') ? 'has-error' : ''}}">
              {!! HTML::decode(Form::label('amount_bill_all', 'เอกสารแนบอื่นๆ :', ['class' => 'col-md-3 control-label text-right'])) !!}
              <div class="col-md-5">
                  @foreach($item->FileAttachAssessment4Many as   $item1)
                          <a href="{{url('funtions/get-view/'.$item1->url.'/'.( !empty($item1->filename) ? $item1->filename : 'null' ))}}" 
                                    title="{{ !empty($item1->filename) ? $item1->filename :  basename($item1->url) }}" target="_blank">
                                    {!! HP::FileExtension($item1->url)  ?? '' !!}
                          </a>
                  @endforeach
              </div>
          </div>
 </div>
@endif

@if(!empty($item->tracking_assessment_bug_many)  && count($item->tracking_assessment_bug_many) > 0) 
<div class="row form-group"> 
    <div class="col-sm-12  "   >
        <table class="table color-bordered-table primary-bordered-table">
            <thead>
            <tr>
                <th class="text-center" width="1%">ลำดับ</th>
                <th class="text-center" width="10%">รายงานที่</th>
                <th class="text-center" width="10%">ข้อบกพร่อง/ข้อสังเกต</th>
                <th class="text-center" width="10%">  มอก. 17025 : ข้อ   </th>
                <th class="text-center" width="10%">ประเภท</th>
                <th class="text-center" width="10%">แนวทางการแก้ไข</th>
                <th class="text-center" width="10%">หลักฐาน</th>
            </tr>
            </thead>    
            <tbody  >
              @foreach($item->tracking_assessment_bug_many as  $key2 =>  $item2)
           <tr> 
                <td  class="text-center"> {!! ($key2+1) !!}</td>
                <td > {!! $item2->report  !!}</td>
                <td > {!! $item2->remark  !!}</td>
                <td > {!! $item2->no  !!}</td>
                <td > {!! $item2->type  == 1 ? "ข้อบกพร่อง" : "ข้อสังเกต" !!}</td>
                <td >
                    <p> {!! $item2->details  !!}</p>
                        <label>
                              {!! Form::checkbox('status['.$item2->id.']', '1', !empty($item2->status == 1 ) ? true : false, 
                              ['class'=>"check checkbox_status check_readonly assessment_results",'data-checkbox'=>"icheckbox_flat-green", "data-key"=>($key2+1)]) !!}
                              &nbsp;ผ่าน &nbsp;
                        </label>
                    </td>
                <td >
                    @if(!is_null($item2->FileAttachAssessmentBugTo))
                     <p>
                          <a href="{{url('funtions/get-view/'.$item2->FileAttachAssessmentBugTo->url.'/'.( !empty($item2->FileAttachAssessmentBugTo->filename) ? $item2->FileAttachAssessmentBugTo->filename :   basename($item2->FileAttachAssessmentBugTo->url) ))}}" 
                              title="{{ !empty($item2->FileAttachAssessmentBugTo->filename) ? $item2->FileAttachAssessmentBugTo->filename :  basename($item2->FileAttachAssessmentBugTo->url) }}" target="_blank">
                              {!! HP::FileExtension($item2->FileAttachAssessmentBugTo->url)  ?? '' !!}
                            </a>
                    </p>
                    <label>
                              {!! Form::checkbox('file_status['.$item2->id.']', '1', !empty($item2->file_status == 1 ) ? true : false, 
                              ['class'=>"check check_readonly file_status",'data-checkbox'=>"icheckbox_flat-green", "data-key"=>($key+1)]) 
                              !!} &nbsp;ผ่าน &nbsp;
                    </label>
                    @endif
              </td>
           </tr>
              @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<br>    
</div>
                 </div>
            </div>
        </div>
   </div>
</div>
      @endforeach      
  @endif            


          </div>
     </div>
</div>



<div class="row form-group" id="div_file_scope">
      <div class="col-md-12">
          <div class="white-box" style="border: 2px solid #e5ebec;">
                    <legend><h3>สรุปผลการตรวจประเมิน</h3></legend>  
                    <hr> 
 
           <div class="row">
                <div class="col-md-12 ">

                    <div id="other_attach-box">
                        <div class="form-group other_attach_scope">
                            <div class="col-md-4 text-right">
                                <label class="attach_remove"><span class="text-danger">*</span> Scope  </label>
                            </div>
                            <div class="col-md-6">
                                @if (!is_null($inspection->FileAttachScopeTo))
                                <p>
                                        <a href="{{url('funtions/get-view/'.$inspection->FileAttachScopeTo->url.'/'.( !empty($inspection->FileAttachScopeTo->filename) ? $inspection->FileAttachScopeTo->filename :  basename($inspection->FileAttachScopeTo->url)  ))}}" 
                                            title="{{  !empty($inspection->FileAttachScopeTo->filename) ? $inspection->FileAttachScopeTo->filename : basename($inspection->FileAttachScopeTo->url) }}" target="_blank">
                                            {!! HP::FileExtension($inspection->FileAttachScopeTo->url)  ?? '' !!}
                                        </a> 
                                </p>
                                @endif
                                @if($cer->status_id == 11)
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file"  name="file_scope" class="check_max_size_file  "  {{ !is_null($inspection->FileAttachScopeTo) ? '' : 'required'}}>   
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                                @endif
                            </div>
                         </div>
                       </div>
                 </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <div id="other_attach_report">
                        <div class="form-group other_attach_report">
                            <div class="col-md-4 text-right">
                                <label class="attach_remove"><span class="text-danger">*</span>สรุปรายงานการตรวจทุกครั้ง </label>
                            </div>
                            <div class="col-md-6">
                                @if (!is_null($inspection->FileAttachReportTo))
                                <p>
                                        <a href="{{url('funtions/get-view/'.$inspection->FileAttachReportTo->url.'/'.( !empty($inspection->FileAttachReportTo->filename) ? $inspection->FileAttachReportTo->filename :  basename($inspection->FileAttachReportTo->url)  ))}}" 
                                            title="{{  !empty($inspection->FileAttachReportTo->filename) ? $inspection->FileAttachReportTo->filename : basename($inspection->FileAttachReportTo->url) }}" target="_blank">
                                            {!! HP::FileExtension($inspection->FileAttachReportTo->url)  ?? '' !!}
                                        </a> 
                                </p>
                                @endif
                                @if($cer->status_id == 11)
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file"  name="file_report" class="check_max_size_file" {{ !is_null($inspection->FileAttachReportTo) ? '' : 'required'}} >
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                                @endif
                            </div>
                         </div>
                       </div>
                 </div>
            </div>



          </div>
     </div>
</div>

@if($cer->status_id == 11)
<input type="hidden" name="previousUrl" id="previousUrl" value="{{   app('url')->previous() }}">
<div class="row form-group">
    <div class="col-md-offset-4 col-md-4 m-t-15">
 
            <button class="btn btn-primary" type="submit"   >
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
 
        <a class="btn btn-default" href="{{  app('url')->previous() }}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
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
 
    <script>
          $(document).ready(function () {

                    $( "#form-save" ).click(function() {
                          $('#form-inspection').submit();
                    });
                    $('#form-inspection').parsley().on('field:validated', function() {
                                var ok = $('.parsley-error').length === 0;
                                $('.bs-callout-info').toggleClass('hidden', !ok);
                                $('.bs-callout-warning').toggleClass('hidden', ok);
                        })  .on('form:submit', function() {
                                // Text
                          $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังบันทึก กรุณารอสักครู่..."
                           });
                          return true; 
                    });
 
                    $('.check-readonly').prop('disabled', true);
                    $('.check-readonly').parent().removeClass('disabled');
                    $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});    

                    $('.check_readonly').prop('disabled', true);
                    $('.check_readonly').parent().removeClass('disabled');
          });
   </script>
@endpush

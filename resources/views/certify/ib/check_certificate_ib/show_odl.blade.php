@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 <!-- Data Table CSS -->
 <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">คำขอรับใบรับรองหน่วยตรวจ {{ $certi_ib->app_no ?? null }} </h3>
                    @can('view-'.str_slug('checkcertificateib'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/check_certificate-ib') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <a class="btn {{ ($certi_ib->status >= 6) ? 'btn-info' : 'btn-warning'  }} "   href="{{ url('certify/check_certificate-ib/show/'.$certi_ib->app_no) }}" >
                      <i class="fa fa-search" aria-hidden="true"></i> คำขอ
                    </a>
<!-- START  admin , ผอ , ผก , เจ้าหน้าที่ IB -->
@if(auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("27",auth()->user()->RoleListId))  

                     @if($certi_ib->status >= 6 && !is_null($certi_ib->CertiIBCostTo))
                                @php 
                                     $Cost = $certi_ib->CertiIBCostTo;
                                     $cost_btn =  '';
                                     $cost_icon =  '';

                                if($Cost->check_status == 1   &&  $Cost->status_scope  == 1 ){//ผ่านการประมาณค่าใช้จ่ายแล้ว
                                    $cost_btn = 'btn-info';
                                    $cost_icon =  '<i class="fa fa-check-square-o"></i>';
                                }elseif($Cost->draft == 1  &&  $Cost->vehicle  == 1 ){  // ส่งให้ ผปก. แล้ว 
                                    $cost_btn = 'btn-success';
                                    $cost_icon =  '<i class="fa fa-file-text"></i>';
                                }elseif($Cost->check_status == 2   || $Cost->status_scope  == 2 ){    // ผปก. ส่งมา
                                    $cost_btn = 'btn-danger';
                                    $cost_icon =  '<i class="fa fa-arrow-circle-right"></i>';
                                }else{
                                    $cost_btn = 'btn-warning'; 
                                }
                                @endphp
                            <a  class="btn {{$cost_btn}}" href="{{  url('certify/estimated_cost-ib/'.$Cost->id.'/edit') }}" >
                                {!! $cost_icon  !!}     ค่าใช้จ่าย
                            </a>
                     @endif 
 
 <!-- START  status 9 -->  
 @if($certi_ib->status >= 9)
    @if(count($certi_ib->CertiIBAuditorsManyBy) > 0) 
  @php 
        $auditors_btn =  '';
        $auditors_icon =  '';
    if($certi_ib->CertiIBAuditorsStatus == "StatusSent"){
        $auditors_btn = 'btn-success';
        $auditors_icon =  '<i class="fa fa-file-text"></i>';
     }elseif($certi_ib->CertiIBAuditorsStatus == "StatusNotView"){
        $auditors_btn =  'btn-danger';
        $auditors_icon =  '<i class="fa fa-arrow-circle-right"></i>';
     }elseif($certi_ib->CertiIBAuditorsStatus == "StatusView"){
        $auditors_btn = 'btn-info';
        $auditors_icon =  '<i class="fa fa-check-square-o"></i>';
     }else{
        $auditors_btn =  'btn-warning';
        $auditors_icon =  '';
   }
 @endphp
   <div class="btn-group">
      <div class="btn-group">
             <a  class="btn  {{$auditors_btn}} " href="{{ url("certify/auditor-ib")}}" >
                {!! $auditors_icon  !!}    แต่งตั้งคณะฯ
             </a>

            <button type="button" class="btn {{$auditors_btn}} dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
             </button>

             <div class="dropdown-menu" role="menu" >
                @php $i_key = 0;   @endphp
               @foreach($certi_ib->CertiIBAuditorsManyBy as $key => $item)
                     @php 
                        $auditors_btn =  '';
                    if(is_null($item->status)){
                        $auditors_btn = 'btn-success';  
                    }elseif($item->status_cancel == 1){
                        $auditors_btn =  '#ffff80';
                    }elseif($item->status == 1){
                        $auditors_btn = 'btn-info';  
                    }elseif($item->status == 2){
                         $auditors_btn = 'btn-danger';  
                     }
                      @endphp
                   @if ($item->status_cancel != 1)
                    <a  class="btn {{$auditors_btn}} " href="{{ url("certify/auditor-ib/".$item->id."/edit")}}"  style="background-color:{{$auditors_btn}};width:750px;text-align: left">
                        ครั้งที่ {{ ($i_key + 1 )}} :  
                        {{ $item->auditor ?? '-'}}
                    </a> <br>
                   @endif
                @endforeach
             </div>

        </div>
    </div>

    @else 
        <a  class="btn btn-warning" href="{{ url("certify/auditor-ib")}}" >
            แต่งตั้งคณะฯ
        </a>
    @endif
@endif 
 <!-- END  status 9 --> 
                     
 @endif 
 <!-- END -->  

 
 <!-- START  admin , ผอ , ผก , เจ้าหน้าที่ ลท. -->
@if(auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("26",auth()->user()->RoleListId))    
    <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->
 @if(count($certi_ib->CertiIBPayInOneMany) > 0)
 
 @php 

      $payin1_btn =  '';
      $payin1_icon =  '';
   if($certi_ib->CertiIBPayInOneStatus == "StatePayInOne"){
      $payin1_btn = 'btn-success';
      $payin1_icon =  '<i class="fa fa-file-text"></i>';
   }elseif($certi_ib->CertiIBPayInOneStatus == "StatusPayInOneNotNeat"){
      $payin1_btn =  'btn-danger';
      $payin1_icon =  '<i class="fa fa-arrow-circle-right"></i>';
  }elseif($certi_ib->CertiIBPayInOneStatus == "StatusPayInOneNeat"){
      $payin1_btn = 'btn-info';
     $payin1_icon =  '<i class="fa fa-check-square-o"></i>';
  }else{
      $payin1_btn =  'btn-warning';
      $payin1_icon =  '';
  }
 @endphp
 <div class="btn-group">
     <div class="btn-group">
           <button type="button" class="btn {{$payin1_btn}} dropdown-toggle" data-toggle="dropdown">
               {!! $payin1_icon  !!}  Pay-in ครั้งที่ 1 <span class="caret"></span>
            </button>
            <div class="dropdown-menu" role="menu" >
                @php $key_payin_one = 0;   @endphp
              @foreach($certi_ib->CertiIBPayInOneMany as $key => $item)
                    @php 
                             $payin1_btn =  '';
                         if(is_null($item->state)){
                             $payin1_btn = 'btn-warning';  
                         }elseif($item->status == 1){ // ผ่าน
                             $payin1_btn = 'btn-info';  
                         }elseif($item->state == 1){  //จนท. ส่งให้ ผปก.
                             $payin1_btn = 'btn-success';  
                         }elseif($item->state == 2){   //ผปก. ส่งให้ จนท.
                             $payin1_btn = 'btn-danger';  
                         }
                     @endphp
                   @if ($item->status   != 3) 
                        <a  class="btn {{$payin1_btn}} " href="{{ url("certify/check_certificate-ib/Pay_In1/".$item->id."/".$certi_ib->token)}}" style="width:750px;text-align: left">
                            ครั้งที่ {{  ($key_payin_one +1) }} :  
                            {{ $item->CertiIBAuditorsTo->auditor ?? '-'}}
                        </a> 
                        <br>
                    @endif
               @endforeach
 
            </div>
       </div>
   </div>
 
  @endif 
 
  <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->         
 @endif 
 <!-- END -->  

 <!-- START  admin , ผอ , ผก ,เจ้าหน้าที่ IB  -->
 @if((auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("27",auth()->user()->RoleListId)) && count($certi_ib->CertiIBPayInOneStatusMany)  > 0) 

 @if(count($certi_ib->CertiIBSaveAssessmentMany) > 0 ) 
    @php 
        $assessment_btn =  '';
        $assessment_icon =  '';
    if($certi_ib->CertiIBSaveAssessmentStatus == "statusInfo"){
        $assessment_btn = 'btn-info';
        $assessment_icon =  '<i class="fa fa-check-square-o"></i>';
    }elseif($certi_ib->CertiIBSaveAssessmentStatus == "statusSuccess"){
        $assessment_btn = 'btn-success';
        $assessment_icon =  '<i class="fa fa-file-text"></i>';
    }elseif($certi_ib->CertiIBSaveAssessmentStatus == "statusDanger"){
        $assessment_btn =  'btn-danger';
        $assessment_icon =  '<i class="fa fa-arrow-circle-right"></i>';
    }elseif($certi_ib->CertiIBSaveAssessmentStatus == "statusPrimary"){
        $assessment_btn =  'btn-primary';
    
    }else{
        $assessment_btn =  'btn-warning';
        $assessment_icon =  '';
    }
    @endphp
 
    <div class="btn-group">
        <div class="btn-group">
                <a  class="btn {{$assessment_btn}}" href="{{ url("certify/save_assessment-ib")}}" >
                    {!! $assessment_icon  !!}    ผลการตรวจประเมิน
                </a>
                <button type="button" class="btn  {{$assessment_btn}} dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                </button>
                <div class="dropdown-menu" role="menu" >
@foreach($certi_ib->CertiIBSaveAssessmentMany as $key => $assessment)
@php
             $assessment_url =  '';
             $assessment_btn =  '';
         if ($assessment->degree == 7) { // ผ่านการการประเมิน
             $assessment_btn =  'btn-info';
             $assessment_url =  'certify/save_assessment-ib/assessment/'.$assessment->id.'/edit';
         }elseif ($assessment->degree == 0) {  //ฉบับร่าง
             $assessment_btn =  'btn-primary';
             $assessment_url =  'certify/save_assessment-ib/'.$assessment->id.'/edit'; 
         }elseif (in_array($assessment->degree,[1,3,4,6])) {  //จนท. ส่งให้ ผปก.
              $assessment_btn =  'btn-success';
              $assessment_url =  'certify/save_assessment-ib/assessment/'.$assessment->id.'/edit';
       }elseif ($assessment->degree == 8) {  //จนท. ส่งให้ ผปก.
              $assessment_btn =  '#ffff80';
              $assessment_url =  'certify/save_assessment-ib/assessment/'.$assessment->id.'/edit';
         }else {    //ผปก. ส่งให้ จนท.
              $assessment_btn =  'btn-danger';
              $assessment_url =  'certify/save_assessment-ib/assessment/'.$assessment->id.'/edit';
         }
 
@endphp
        <a  class="btn {{$assessment_btn}}"  href="{{ url("$assessment_url")}}"  style="background-color:{{$assessment_btn}};width:750px;text-align: left">
        ครั้งที่ {{ count($certi_ib->CertiIBSaveAssessmentMany) - ($key) }} :  
        {{ $assessment->CertiIBAuditorsTo->auditor ?? '-'}}
    </a> 
    <br>
@endforeach
                </div>
            </div>
        </div>
 @else 
         <a  class="btn btn-warning" href="{{ url("certify/save_assessment-ib")}}" >
            ผลการตรวจประเมิน
        </a>
@endif
 

 
 {{-- ทบทวน --}}
 @if( $certi_ib->status >= 11 && count($certi_ib->CertiIBSaveAssessmentMany) > 0   && $certi_ib->CertiIBSaveAssessmentStatus == "statusInfo")
@php 
  $review =  $certi_ib->CertiIBReviewTo;
  $review_btn =  '';
  $review_icon =  '';

if($certi_ib->review == 1){
  $review_btn =  'btn-warning';
  $review_icon =  '';
}else{
  $review_btn =  'btn-info';
  $review_icon =  '<i class="fa fa-check-square-o"></i>';
}
@endphp
<button type="button" class="btn {{$review_btn}}"  data-toggle="modal" data-target="#ReviewModal">
  {!! $review_icon !!} ทบทวนฯ
</button>
@include ('certify/ib/check_certificate_ib/modal.modalreview',['review' => $review,'certi_ib'=> $certi_ib])
 @endif 


@if($certi_ib->status >= 12)
@php 
      $report = $certi_ib->CertiIBReportTo;
          $report_btn =  '';
          $report_icon =  '';
        if(is_null($report->report_status)){
             $report_btn =  'btn-warning';
       }elseif ($report->report_status == 1 && is_null($report->updated_by)) {
            $report_btn =  'btn-success';
            $report_icon = '<i class="fa fa-file-text"></i> ';
       }elseif ($report->report_status == 1) {
            $report_btn =  'btn-info';
            $report_icon =  '<i class="fa fa-check-square-o"></i>';
       }else{
            $report_btn =  'btn-warning';
       }
@endphp
<!-- Button trigger modal     	สรุปรายงานและเสนออนุกรรมการฯ  -->
<button type="button" class="btn {{$report_btn}}" data-toggle="modal" data-target="#exampleModalReport">
{!! $report_icon !!} สรุปรายงาน
</button>
@include ('certify/ib/check_certificate_ib/modal.modalstatus17',['report' => $report
                                                           ])
<!-- Button trigger modal    	สรุปรายงานและเสนออนุกรรมการฯ  -->

@endif 

@endif 
 <!-- END -->  

  
 <!-- START  admin , ผอ , ผก , เจ้าหน้าที่ ลท. -->
@if(auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("26",auth()->user()->RoleListId))  

                   @if($certi_ib->status >= 14)
                         @php 
                               $payin2 = $certi_ib->CertiIBPayInTwoTo;
                                $payin2_btn =  '';
                                $payin2_icon =  '';
                            if(is_null($payin2->degree)){
                                 $payin2_btn =  'btn-warning';
                            }elseif ($payin2->degree == 3) {
                                 $payin2_btn =  'btn-info';
                                 $payin2_icon =  '<i class="fa fa-check-square-o"></i>';
                            }elseif ($payin2->degree == 1) {
                                 $payin2_btn =  'btn-success';
                                 $payin2_icon = '<i class="fa fa-file-text"></i> ';
                            }elseif ($payin2->degree == 2) {
                                 $payin2_btn =  'btn-danger';
                                 $payin2_icon =  '<i class="fa fa-arrow-circle-right"></i>';
                            }
                       @endphp
                  <!-- Button แนบใบ Pay-in ครั้งที่ 2  -->
                  <a  class="btn {{$payin2_btn}} " href="{{ url("certify/check_certificate-ib/Pay_In2/".$payin2->id."/".$certi_ib->token)}}">
                       {!! $payin2_icon !!}  Pay-in ครั้งที่ 2
                   </a> 
                    {{-- <button type="button" class="btn  {{$payin2_btn}}"   data-toggle="modal" data-target="#exampleModalPay">
                        {!! $payin2_icon !!}    Pay-in ครั้งที่ 2
                    </button> 
                    @include ('certify/ib/check_certificate_ib/modal.modalstatus19',['report' => $payin2 ?? null
                                                                                   ]) --}}
                   <!-- Button  แนบใบ Pay-in ครั้งที่  2  -->
                   @endif
                   
                   @if($certi_ib->status >= 17)
                         <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalExport">
                            แนบท้าย
                        </button>
                        @include ('certify/ib/check_certificate_ib/modal.modalstatus22',['file_all'=> $certi_ib->CertiIBFileAlls,
                                                                                         'certi_ib' => $certi_ib 
                                                                                        ])

                            @if(!is_null($certi_ib->CertiIBExportTo))
                                    <a   class="btn btn-info" href="{{ url('certify/certificate-export-ib/'.$certi_ib->CertiIBExportTo->id.'/edit') }}">
                                        <i class="fa fa-check-square-o"></i>  ออกใบรับรอง
                                    </a> 
                            @else 
                            <a  class="btn btn-warning" href="{{  url('certify/certificate-export-ib') }}" >
                               ออกใบรับรอง
                            </a>
                            @endif
                   @endif
 @endif 
 <!-- END -->  
                    <div class="clearfix"></div>
                    <br>
                    <div class="white-box">
                      <div class="row ">
                          <div class="col-sm-12">
                              <h3 class="box-title">ผลการตรวจสอบคำขอรับใบรับรองหน่วยตรวจ</h3>
                              <hr>
                              <div class="row text-center">

 {!! Form::model($certi_ib, [
                        'method' => 'PATCH',
                        'url' => ['/certify/check_certificate-ib', $certi_ib->id],
                        'class' => 'form-horizontal',
                        'id' => 'form_operating',
                        'files' => true
                    ]) !!}
   
                                      {{ csrf_field() }}
                                      {{ method_field('PUT') }}
                                      <div class="col-sm-8">
                                          <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                                              {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> ผลการตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                                              <div class="col-md-7">
                                                  @if($certi_ib->status < 6)
                                                  {!! Form::select('status',
                                                  App\Models\Certify\ApplicantIB\CertiIBStatus::whereNotIn('id',[0])->whereIN('id',[1,2,3,4,5,6])->pluck('title', 'id'), 
                                                  $certi_ib->status ?? null, 
                                                  ['class' => 'form-control', 
                                                   'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-',
                                                   'id'=>'status',
                                                   'required' => true]); !!}
                                                 @else 
                                                   {!! Form::text('status',  $certi_ib->TitleStatus->title ?? null ,['class' => 'form-control', 'placeholder'=>'', 'disabled']) !!}
                                                 @endif
                                              </div>
                                          </div>
                                      </div>
                                      @if(!in_array($certi_ib->status,['3','4','5']))
                                           <!-- 3.ขอเอกสารเพิ่มเติม 5.ไม่ผ่านการตรวจสอบ  -->
                                      <div class="col-sm-8 m-t-15 isShowDesc">
                                          <div class="form-group {{ $errors->has('desc') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('desc', '<span class="text-danger">*</span> ระบุรายละเอียด', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                                              <div class="col-md-7">
                                                  {!! Form::textarea('desc', null, ['class' => 'form-control requiredDesc', 'placeholder'=>'ระบุรายละเอียดที่นี่(ถ้ามี)', 'rows'=>'5']); !!}
                                              </div>
                                          </div>
                                      </div>
                                      <div  class="col-sm-8 m-t-15 isShowDesc">
                                          <div id="attach_files-box">
                                              <div class="form-group attach_files">
                                                  <div class="col-md-4  text-light">
                                                  {!! Form::label('attach_files', 'ไฟล์แนบ', ['class' => 'col-md-12 label_attach text-light  control-label ']) !!}
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                          <div class="form-control" data-trigger="fileinput">
                                                              <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                              <span class="fileinput-filename"></span>
                                                          </div>
                                                          <span class="input-group-addon btn btn-default btn-file">
                                                              <span class="fileinput-new">เลือกไฟล์</span>
                                                              <span class="fileinput-exists">เปลี่ยน</span>
                                                              <input type="file" name="file[]" class="check_max_size_file">
                                                          </span>
                                                          <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <button type="button" class="btn btn-sm btn-success attach-add" id="attach-add">
                                                          <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                      </button>
                                                      <div class="button_remove"></div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-8 m-t-15">
                                          <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                              {!! Form::label('employ_name', 'เจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                              <div class="col-md-7 text-left">
                                                  {!! Form::text('employ_name', $certi_ib->FullRegName ?? null   , ['class' => 'form-control', 'placeholder'=>'', 'disabled']); !!}
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-8 m-t-15">
                                          <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                              {!! HTML::decode(Form::label('save_date', '<span class="text-danger">*</span> วันที่บันทึก', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                                              <div class="col-md-7 text-left">
                                                  {!! Form::text('save_date', $certi_ib->save_date ? HP::revertDate($certi_ib->save_date,true): null, ['class' => 'form-control mydatepicker',
                                                   'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required', 'disabled' => ($certi_ib->status >= 6) ?   true :  false   ]) !!}
                                                  {!! $errors->first('save_date', '<p class="help-block">:message</p>') !!}
                                              </div>
                                          </div>
                                      </div>
                                     
                                      @if($certi_ib->status < 6)
                                      <div class="col-sm-8 m-t-15  {{ ($certi_ib->status == 4 || $certi_ib->status == 5) ? 'hide' : ''  }}">
                                          <div class="form-group">
                                              <div class="col-md-offset-4 col-md-6 m-t-15">
                                                  <button class="btn btn-primary" type="submit" id="form-save" >
                                                      <i class="fa fa-paper-plane"></i> บันทึก
                                                  </button>
          
                                                  <a class="btn btn-default" href="{{url('/certify/check_certificate-ib')}}">
                                                      <i class="fa fa-rotate-left"></i> ยกเลิก
                                                  </a>
                                              </div>
                                          </div>
                                      </div>
                                      @endif
                                      @endif
  {!! Form::close() !!}
                              </div>
                          </div>
                      </div>
                  </div>

                  @if($history->count() > 0 )
                  <div class="white-box">
                      <div class="row">
                          <div class="col-sm-12">
                               <legend><h3 class="box-title">ประวัติคำขอรับใบรับรองหน่วยตรวจ</h3></legend>

                            <div class="row">
                               
                                <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table zero-configuration  table-hover" id="myTable" width="100%">
                                      <thead>
                                              <tr>
                                                  <th class="text-center  bg-info  text-white" width="2%">ลำดับ</th>
                                                  <th class="text-center  bg-info  text-white" width="30%">วันที่/เวลาบันทึก</th>
                                                  <th class="text-center  bg-info  text-white" width="30%">เจ้าหน้าที่บันทึก</th>
                                                  <th class="text-center  bg-info  text-white" width="38%">รายละเอียด</th>
                                              </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($history as $key => $item)
                                              <tr>
                                                  <td class="text-center">{{ $key +1}}</td>
                                                  <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                                                  <td> {{ $item->user_created->FullName ?? '-'}}</td>
                                                  <td>
                                                            @if($item->DataSystem != '-')
                                                                 <button type="button" class="btn btn-link {{!is_null($item->details_auditors_cancel) ? 'text-danger' : ''}}" style="line-height: 16px;text-align: left;" 
                                                                  data-toggle="modal" data-target="#HistoryModal{{$item->id}}">
                                                                    {{ @$item->DataSystem }}
                                                                    <br>
                                                                     <!-- แต่งตั้งคณะผู้ตรวจประเมิน  -->
                                                                     @if(!is_null($item->auditors_id))
                                                                     <span class="text-danger" style="font-size: 10px">
                                                                         {{ isset($item->CertiIBAuditorsTo->auditor) ? '( '.$item->CertiIBAuditorsTo->auditor.' )' : null }}
                                                                     </span>
                                                                 @endif  
                                                                </button>

                                                                @include ('certify/ib/check_certificate_ib.history_detail',['history' => $item])
                                                             @else 
                                                                -
                                                             @endif  
                                                  </td>
                                              </tr>
                                          @endforeach
                                      </tbody>
                                  </table>
                              </div>
                                </div>
                              
                            </div>
                          </div>
                      </div>
                  </div>
                  @endif
                  @if(count($certi_ib->CertiIBAuditorsMany) > 0 )
                  <div class="white-box">
                      <div class="row">
                          <div class="col-sm-12">
                               <legend><h3 class="box-title">คณะกรรมการผู้ตรวจประเมิน</h3></legend>
                            <div class="row">
                               
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table color-bordered-table info-bordered-table table-bordered" >
                                            <thead>
                                                    <tr>
                                                        <th class="text-center text-white" width="2%">ลำดับ</th>
                                                        <th class="text-center text-white" width="20%">วันที่/เวลาบันทึก</th>
                                                        <th class="text-center text-white" width="40%">คณะผู้ตรวจประเมิน</th>
                                                        <th class="text-center text-white" width="38%">สถานะ</th>
                                                    </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($certi_ib->CertiIBAuditorsMany as $key => $item)
                                                <tr>
                                                    <td class="text-center"  >{{$key+1}}</td>
                                                    <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                                                    <td>{{ $item->auditor ?? null }}</td>
                                                    <td> 
                                                        <span style="color: {{($item->step_id == 9) ? 'red' : ''}} ">
                                                            {{ $item->CertiIBAuditorsStepTo->title ?? null }}
                                                        </span>
                                                        @if (!is_null($item->reason_cancel))
                                                            <br>
                                                            <span class="text-danger" style="font-size: 10px">
                                                                    ผู้ยกเลิก :   {{ isset($item->reason_cancel) ? @$item->UserCancelTo->FullName  : null }} <br>
                                                                    วันที่ยกเลิก :   {{ isset($item->date_cancel) ? HP::DateThai($item->date_cancel)   : null }} <br>
                                                                    เหตุผลที่ยกเลิก :   {{ isset($item->reason_cancel) ? $item->reason_cancel  : null }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                 @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              
                            </div>
 
                          </div>
                          
                      </div>
                  </div>
                  @endif  
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



              <!-- เริ่ม สรุปรายงาน -->
     <script type="text/javascript">
        jQuery(document).ready(function() {
             $("input[name=report_status]").on("ifChanged", function(event) {;
                status_show_report_status();
              });
              status_show_report_status();
            function status_show_report_status(){
                  var row = $("input[name=report_status]:checked").val();
                  if(row == "1"){ 
                     $('#div_file_loa').show();
                    $('#file_loa').prop('required' ,true);
                  } else{
                    $('#div_file_loa').hide();
                    $('#file_loa').prop('required' ,false);
                  }
              }
         });
     </script>
     <!-- จบ สรุปรายงาน -->

<script>

 
  $(document).ready(function(){

             @if(\Session::has('flash_message'))
                $.toast({
                    heading: 'Success!',
                    position: 'top-center',
                    text: '{{session()->get('flash_message')}}',
                    loaderBg: '#70b7d6',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 6
                });
            @endif

            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
                $('#myTable').DataTable( {
                    dom: 'Brtip',
                    pageLength:5,
                    processing: true,
                    lengthChange: false,
                    ordering: false,
                    order: [[ 0, "desc" ]]
                });
                $('#myTable1').DataTable( {
                    dom: 'Brtip',
                    pageLength:5,
                    processing: true,
                    lengthChange: false,
                    ordering: false,
                    order: [[ 0, "desc" ]]
                });
             IsInputNumber();
             AttachFileLoa();
         // <!-- 3.ขอเอกสารเพิ่มเติม 5.ไม่ผ่านการตรวจสอบ  -->
            $('#status').change(function(){ 
                    $('.isShowDesc').hide();
                    $('.requiredDesc').prop('required', false);

                  if($(this).val() == 3 || $(this).val() == 4 ||$(this).val() == 5){
                        $('.isShowDesc').show();
                        $('.requiredDesc').prop('required', true);
                    }

            });
            $('#status').change();
                check_max_size_file();
            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.attach_files:first').clone().appendTo('#attach_files-box');
                $('.attach_files:last').find('input').val('');
                $('.attach_files:last').find('a.fileinput-exists').click();
                $('.attach_files:last').find('a.view-attach').remove();
                $('.attach_files:last').find('.label_attach').remove();
                $('.attach_files:last').find('button.attach-add').remove();
                $('.attach_files:last').find('.button_remove').html('<button class="btn btn-danger btn-sm attach_remove" type="button"> <i class="icon-close"></i>  </button>');
                check_max_size_file();
            });
            //ลบไฟล์แนบ
            $('body').on('click', '.attach_remove', function(event) {
                $(this).parent().parent().parent().remove();
            });

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
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
         
            //  Attach File
            function  AttachFileLoa(){
            $('.file_loa').change( function () {
                    var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf ',
                        '',
                        'info'
                        )
                    this.value = '';
                    return false;
                    }
                });
        }


});

</script>
<script type="text/javascript">

    $(document).ready(function() {
      //Validate
      if($('form').length > 0){
          $('form:first:not(.not_validated)').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
          })
          .on('form:submit', function() {
                 // Text
                 $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                  });
            return true; // Don't submit form for this demo
          });
       }





    });
    
  </script>
@endpush

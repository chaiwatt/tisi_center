{{-- work on class CheckCertificateLabController extends Controller --}}
@extends('layouts.master')
@inject('Carbon', '\Carbon\Carbon')
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 <!-- Data Table CSS -->
 <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
 <style type="text/css">
    .form_group {
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid" id="app_check_deail">
        <div class="text-right m-b-15">
            @can('view-'.str_slug('auditor'))
                <a class="btn btn-danger btn-sm waves-effect waves-light" href="{{ route('check_certificate.index') }}">
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                </a>
            @endcan
        </div>

        <h3 class="box-title" style="display: inline-block;">คำขอรับใบรับรองห้องปฏิบัติการ {{ $cc->applicant->app_no ?? '-' }}</h3>

<div class="row">

    <input type="text" value="{{$cc->applicant->id}}">
    <div class="col-sm-12 ">

    
<a class="form_group btn {{ ($cc->applicant->status >= 6) ? 'btn-info' : 'btn-warning'  }} "   href="{{ route('show.certificate.applicant.detail', ['certilab'=>$cc->applicant]) }}" >
    <i class="fa fa-search" aria-hidden="true"></i> คำขอ
</a>

{{-- <a class="form_group btn btn-info"   href="{{ url('certify/check_certificate/export_word/'.$cc->applicant->id) }}" >
    <i class="fa fa-cloud-download"></i>  download
</a> --}}

@php 
    $User = App\User::where('runrecno',auth()->user()->runrecno)->first(); 
    // เช็คเจ้าหน้าที่ สก. 
    if(in_array("9",$User->RoleListId)){
        $staff  = "true";
    }
    $applicant =  $cc->applicant ;
@endphp

{{-- @if($User->IsGetIdLathRoles() == 'false'  || $User->IsGetRolesAdmin() == 'true') --}}

@if($applicant->status >= 6)

@if(!is_null($Cost))
        @php 
            $agree =  '';
            $cost_icon =  '';
        if($Cost->check_status == 1   &&  $Cost->status_scope  == 1 ){//ผ่านการประมาณค่าใช้จ่ายแล้ว
            $agree = 'btn-info';
            $cost_icon =  '<i class="fa fa-check-square-o"></i>';
        }elseif($Cost->draft == 1  &&  $Cost->vehicle  == 1 ){  // ส่งให้ ผปก. แล้ว 
            $agree = 'btn-success';
            $cost_icon =  '<i class="fa fa-file-text"></i>';
        }elseif($Cost->check_status == 2   || $Cost->status_scope  == 2 ){    // ผปก. ส่งมา
            $agree = 'btn-danger';
            $cost_icon =  '<i class="fa fa-arrow-circle-right"></i>';
        }else{
            $agree = 'btn-warning'; 
        }
        @endphp
            <a  class="form_group btn {{$agree}}" href="{{ route('estimated_cost.edit', ['ec' => $Cost]) }}">
                {!! $cost_icon  !!}     ค่าใช้จ่าย
            </a>
   @else 
        <a class="form_group btn btn-info" href="{{ route('estimated_cost.index') }}" >
            ค่าใช้จ่าย
        </a>
    @endif
{{-- @endif  --}}
            
@if(!is_null($Cost) &&  $Cost->check_status == 1   &&  $Cost->status_scope  == 1)
    @if(count($applicant->certi_auditors_many)  > 0  )
 
        @php 
            $auditors_btn =  '';
            $auditors_icon =  '';
        if($applicant->CertiAuditorsStatus == "StatusSent"){
            $auditors_btn = 'btn-success';
            $auditors_icon =  '<i class="fa fa-file-text"></i>';
        }elseif($applicant->CertiAuditorsStatus == "StatusNotView"){
            $auditors_btn =  'btn-danger';
            $auditors_icon =  '<i class="fa fa-arrow-circle-right"></i>';
        }elseif($applicant->CertiAuditorsStatus == "StatusView"){
            $auditors_btn = 'btn-info';
            $auditors_icon =  '<i class="fa fa-check-square-o"></i>';
        }else{
            $auditors_btn =  'btn-warning';
            $auditors_icon =  '';
        }
        @endphp

        <div class="btn-group form_group">
            <div class="btn-group">

                {{-- <a  class="btn  {{$auditors_btn}} " href="{{ url("certify/auditor-cb")}}" >
                    {!! $auditors_icon  !!}    แต่งตั้งคณะฯ
                </a> --}}

                <button type="button" class="btn {{$auditors_btn}} dropdown-toggle" data-toggle="dropdown">
                    {!! $auditors_icon  !!}    แต่งตั้งคณะฯ<span class="caret"></span>
                </button>


                <div class="dropdown-menu" role="menu" >
                                    
                    @if(in_array($applicant->status,[9,7]))  
                        <form action="{{ url('/certify/auditor/create')}}" method="POST" style="display:inline"> 
                            {{ csrf_field() }}
                            {!! Form::hidden('app_certi_lab_id', (!empty($applicant->id) ? $applicant->id  : null) , ['id' => 'app_certi_lab_id', 'class' => 'form-control', 'placeholder'=>'' ]); !!}
                            <button class="btn btn-warning" type="submit"   style="width:750px;text-align: left"> 
                                <i class="fa fa-plus"></i>    แต่งตั้งคณะฯ (เพิ่มเติม)

                            </button>
                            <input hidden type="text" name="current_url" value="{{ request()->fullUrl() }}">
                            <input hidden type="text" name="current_route" value="{{ request()->route()->getName() }}" readonly> 
                        </form>
                   @endif
                     
                    @foreach($applicant->certi_auditors_many as $key => $item)
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
                            <a  class="btn {{$auditors_btn}} "  href="{{ url('/certify/auditor/'.$item->id.'/edit', ['app' => $applicant ? $applicant->id : '']) }}" style="background-color:{{$auditors_btn}};width:750px;text-align: left">
                                ครั้งที่ {{ ($key + 1 )}} :  
                                  {{ $item->auditor ?? '-'}}
                            </a> 
                            <br>
                        @endif
                    
                    @endforeach
                </div>

            </div>
        </div>
 

    @else 
    <div class="btn-group form_group">
        {{-- work on BoardAuditorController --}}
        <form action="{{ url('/certify/auditor/create/')}}" method="POST" style="display:inline" > 
            {{ csrf_field() }}
            {!! Form::hidden('app_certi_lab_id',(!empty($applicant->id) ? $applicant->id  : null) , ['id' => 'app_certi_lab_id', 'class' => 'form-control']); !!}
                <button class="btn btn-warning" type="submit" >
                    <i class="fa fa-plus"></i>    แต่งตั้งคณะฯ {{$applicant->id}}
                </button>
                <input type="text" name="applicantId" value="{{$applicant->id}}">
                <input hidden type="text" name="current_url" value="{{ request()->fullUrl() }}">
                <input hidden type="text" name="current_route" value="{{ request()->route()->getName() }}" readonly> 
        </form>
    </div>

    @endif
@endif 

@endif   

{{-- @if($User->IsGetIdLathRoles() == 'true'   || $User->IsGetRolesAdmin() == 'true') --}}
    @if($applicant->status >= 7 &&count($applicant->many_cost_assessment) > 0)
        @php 
            $payin1_btn =  '';
            $payin1_icon =  '';
        if($applicant->CertiLabPayInOneStatus == "StatePayInOne"){
            $payin1_btn = 'btn-success';
            $payin1_icon =  '<i class="fa fa-file-text"></i>';
        }elseif($applicant->CertiLabPayInOneStatus == "StatusPayInOneNotNeat"){
            $payin1_btn =  'btn-danger';
            $payin1_icon =  '<i class="fa fa-arrow-circle-right"></i>';
        }elseif($applicant->CertiLabPayInOneStatus == "StatusPayInOneNeat"){
            $payin1_btn = 'btn-info';
            $payin1_icon =  '<i class="fa fa-check-square-o"></i>';
        }else{
            $payin1_btn =  'btn-warning';
            $payin1_icon =  '';
        }
        @endphp
        <div class="btn-group form_group">
            <div class="btn-group">
                <button type="button" class="btn {{$payin1_btn}} dropdown-toggle" data-toggle="dropdown">
                    {!! $payin1_icon  !!}  Pay-in ครั้งที่ 1 <span class="caret"></span>
                </button>
                <div class="dropdown-menu" role="menu" >
                    @foreach($applicant->many_cost_assessment as $key => $item)
                        @php 
                                    $payin1_btn =  '';
                                if(is_null($item->state)){
                                    $payin1_btn = 'btn-warning';  
                                }elseif($item->status_confirmed == 1){ // ผ่าน
                                    $payin1_btn = 'btn-info';  
                                }elseif($item->state == 1){  //จนท. ส่งให้ ผปก.
                                    $payin1_btn = 'btn-success';  
                                }elseif($item->state == 2){   //ผปก. ส่งให้ จนท.
                                    $payin1_btn = 'btn-danger';  
                                }
                                $key_temp = 0;
                            @endphp
        
                            @if (empty($item->assessment->board_auditor_to->status_cancel) && $item->assessment->board_auditor_to->status_cancel != 1)
                                
                                @php
                                    $key_temp++;
                                @endphp
                            <a  class="btn {{$payin1_btn}} " href="{{ url("certify/check_certificate/Pay_In1/".$item->id)}}" style="width:750px;text-align: left">
                                ครั้งที่ {{  ($key_temp) }} : 
                                {{ !empty($item->assessment->board_auditor_to->auditor) ? $item->assessment->board_auditor_to->auditor :'-'}}
                            </a> 
                            @else
                            @php
                                $key_temp=0;
                            continue;
                            @endphp 
                            
                            @endif
                        
                            <br>
                                @php
                                $key_temp=0;
                            @endphp 
                    @endforeach
        
                </div>
            </div>
        </div>
     @endif 
 {{-- @endif  --}}

{{-- @if($User->IsGetIdLathRoles() == 'false'  || $User->IsGetRolesAdmin() == 'true') --}}

    @if(count($applicant->many_cost_assessment_state3) > 0) 
        @if(count($applicant->notices) > 0)

            @php 
                $assessment_btn =  '';
                $assessment_icon =  '';
            if($applicant->CertiLabSaveAssessmentStatus == "statusInfo"){
                $assessment_btn = 'btn-info';
                $assessment_icon =  '<i class="fa fa-check-square-o"></i>';
            }elseif($applicant->CertiLabSaveAssessmentStatus == "statusSuccess"){
                $assessment_btn = 'btn-success';
                $assessment_icon =  '<i class="fa fa-file-text"></i>';
            }elseif($applicant->CertiLabSaveAssessmentStatus == "statusDanger"){
                $assessment_btn =  'btn-danger';
                $assessment_icon =  '<i class="fa fa-arrow-circle-right"></i>';
            }elseif($applicant->CertiLabSaveAssessmentStatus == "statusPrimary"){
                $assessment_btn =  'btn-primary';
            
            }else{
                $assessment_btn =  'btn-warning';
                $assessment_icon =  '';
            }
        @endphp


    <div class="btn-group form_group">
        <div class="btn-group">
                    <a  class="btn {{$assessment_btn}}" href="{{ url("certify/save_assessment")}}" >
                        {!! $assessment_icon  !!}    ผลการตรวจประเมิน
                    </a>
                <button type="button" class="btn  {{$assessment_btn}} dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                </button>
                <div class="dropdown-menu" role="menu" >
                        @foreach($applicant->notices as $key => $notice)
                            @php
                                        $assessment_url =  '';
                                        $assessment_btn =  '';
                                    if ($notice->degree == 7) { // ผ่านการการประเมิน
                                        $assessment_btn =  'btn-info';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }elseif ($notice->degree == 0) {  //ฉบับร่าง
                                        $assessment_btn =  'btn-primary';
                                        $assessment_url =  url('certify/save_assessment/'.$assessment->id.'/edit/'.$applicant->id);  
                                    }elseif (in_array($notice->degree,[1,3,4,6])) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  'btn-success';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                }elseif ($notice->degree == 8) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  '#ffff80';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }else {    //ผปก. ส่งให้ จนท.
                                        $assessment_btn =  'btn-danger';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }
                            
                            @endphp
                                    <a  class="btn {{$assessment_btn}}"  href="{{  $assessment_url }}"  style="background-color:{{$assessment_btn}};width:750px;text-align: left">
                                            ครั้งที่ {{$applicant->id}} {{ count($applicant->notices) - ($key) }} :  
                                            {{ !empty($notice->assessment->board_auditor_to->auditor) ? $notice->assessment->board_auditor_to->auditor :'-'}}
                                    </a> 
                                <br>
                        @endforeach
                </div>
            </div>
        </div>

        @else 
        <a  class="form_group btn btn-warning" href="{{ url('certify/save_assessment') }}" >
            ผลการตรวจประเมิน
        </a>


        @endif
        @php
            $assessment_btn =  '';
            $assessment_icon =  '';
        @endphp
        @if(count($applicant->many_cost_assessment_state3) > 0) 
            @if(count($applicant->notices) > 0)

                @php 
                        $assessment_btn =  '';
                        $assessment_icon =  '';
                    if($applicant->CertiLabSaveAssessmentStatus == "statusInfo"){
                        $assessment_btn = 'btn-info';
                        $assessment_icon =  '<i class="fa fa-check-square-o"></i>';
                    }elseif($applicant->CertiLabSaveAssessmentStatus == "statusSuccess"){
                        $assessment_btn = 'btn-success';
                        $assessment_icon =  '<i class="fa fa-file-text"></i>';
                    }elseif($applicant->CertiLabSaveAssessmentStatus == "statusDanger"){
                        $assessment_btn =  'btn-danger';
                        $assessment_icon =  '<i class="fa fa-arrow-circle-right"></i>';
                    }elseif($applicant->CertiLabSaveAssessmentStatus == "statusPrimary"){
                        $assessment_btn =  'btn-primary';
                    
                    }else{
                        $assessment_btn =  'btn-warning';
                        $assessment_icon =  '';
                    }
                @endphp
            @endif
        @endif

        <div class="btn-group form_group">
            <div class="btn-group">
                <button type="button" class="btn {{$assessment_btn}} dropdown-toggle" data-toggle="dropdown">
                    {!! $assessment_icon  !!} ผลการตรวจประเมิน (แยก) <span class="caret"></span>
                </button>
                <div class="dropdown-menu" role="menu" >
                    {{-- @foreach($applicant->many_cost_assessment as $key => $item) --}}
                    @foreach($applicant->certi_auditors_many->reverse() as $key => $item)
                            @php
                                $notice = $item->assessment_to->notice();
                            @endphp
                            @if ($notice !== null)
                                @php
                                    $assessment_url =  '';
                                    $assessment_btn =  '';
                                    if ($notice->degree == 7) { // ผ่านการการประเมิน
                                        $assessment_btn =  'btn-info';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }elseif ($notice->degree == 0) {  //ฉบับร่าง
                                        $assessment_btn =  'btn-primary';
                                        $assessment_url =  url('certify/save_assessment/'.$assessment->id.'/edit/'.$applicant->id);  
                                    }elseif (in_array($notice->degree,[1,3,4,6])) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  'btn-success';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }elseif ($notice->degree == 8) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  '#ffff80';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }else {    //ผปก. ส่งให้ จนท.
                                        $assessment_btn =  'btn-danger';
                                        $assessment_url =   route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $applicant ? $applicant->id : ''])  ;
                                    }
                                @endphp

                                {{-- {{$notice->id}} {{$applicant->id}} --}}
                                <a class="btn btn-info" href="{{ route('save_assessment.create', ['id' => $item->id]) }}" style="background-color:{{$assessment_btn}};width:750px;text-align: left"> {{$item->auditor}}  (ยังไม่ได้ตรวจ)</>  
                             @else
                                 <a class="btn {{$assessment_btn}}"  href="{{  $assessment_url }}" style="width:750px;text-align: left">   {{$item->auditor}} </a>
                            @endif
                           
                        </a>
                    <br>
                    @endforeach
                </div>
            </div>
        </div>
    @endif 
            

            @if ($applicant->status == 20)
                ต้องเพิ่มการทำรายงาน (ID20)
            @endif
            @if(!empty($applicant->report_to)  && $applicant->status >= 20)
            @php  
                    $report  = $applicant->report_to;
                    $btn_report = '';
                    $report_icon =  '';
                  if(!is_null($report->updated_by)){
                      $btn_report = 'btn-info';
                      $report_icon =  '<i class="fa fa-check-square-o"></i>';
                   }elseif(!is_null($report->created_by) && $report->status == 1){
                       $btn_report = 'btn-success';
                       $report_icon =  '<i class="fa fa-file-text"></i>';
                   }else{
                      $btn_report = 'btn-warning';
                   }
             @endphp
            <!-- Button trigger modal     	สรุปรายงานและเสนออนุกรรมการฯ  -->
            <button type="button" class="form_group btn {{ $btn_report }}" data-toggle="modal" data-target="#exampleModalReport">
                    {!! $report_icon !!} สรุปรายงาน
            </button>
            @include ('certify.check_certificate_lab.modal_report')
             <!-- Button trigger modal    	สรุปรายงานและเสนออนุกรรมการฯ  -->
            @endif

 {{-- @endif          --}}
 {{-- @if($User->IsGetIdLathRoles() == 'true'  || $User->IsGetRolesAdmin() == 'true') --}}

            @if(!empty($applicant->status) && $applicant->status >= 22)
            @php  
            $costcerti = App\Models\Certify\Applicant\CostCertificate::where('app_certi_lab_id',$cc->app_certi_lab_id)
                                                                    ->orderby('id','desc')
                                                                    ->first();
                           $btn_costcerti =  '';
                           $icon_costcerti =  '';
                        if(!is_null($costcerti) && ($costcerti->status_confirmed == 1 )){
                            $btn_costcerti = 'btn-info';
                            $icon_costcerti =  '<i class="fa fa-check-square-o"></i>';
                        }elseif(!is_null($costcerti) &&  $costcerti->invoice != ''){
                            $btn_costcerti = 'btn-danger';
                            $icon_costcerti =  '<i class="fa fa-arrow-circle-right"></i> ';
                        }elseif(!is_null($costcerti)  && ($costcerti->conditional_type != '' || $costcerti->attach_certification != '' )){
                            $btn_costcerti = 'btn-success';
                            $icon_costcerti =  '<i class="fa fa-file-text"></i>';
                        }else{
                            $btn_costcerti = 'btn-warning';
                            $icon_costcerti =  '';
                        }    
                                      
            @endphp
            <!-- Button แนบใบ Pay-in ครั้งที่ 2  -->
            <a  class="form_group btn {{$btn_costcerti}} " href="{{ url("certify/check_certificate/Pay_In2/".@$costcerti->id."/".$applicant->token)}}">
                {!! $icon_costcerti !!}  Pay-in ครั้งที่ 2
             </a> 
             <!-- Button  แนบใบ Pay-in ครั้งที่  2  -->
            @endif

{{-- {{$applicant->certificate_export}} --}}

 @if($applicant->status >= 25 )
    
    {{-- {{$applicant->certificate_export->status}} --}}
    @if( isset($applicant)  &&  !is_null($applicant->certificate_export)  && !in_array($applicant->certificate_export->status,[99]))
        @php 
            $export =  $applicant->certificate_export;
            $export_btn =  '';
            $export_icon =  '';
            if($export->status ==  4){
                $export_btn = 'btn-info';
                $export_icon =  '<i class="fa fa-check-square-o"></i>';
            }elseif($export->status == 3){
                $export_btn = 'btn-success';
                $export_icon =  '<i class="fa fa-file-text"></i>';
            }elseif($export->status == 5){
                $export_btn =  'btn-danger';
                $export_icon =  '<i class="fa fa-arrow-circle-right"></i>';
            }else{
                $export_btn =  'btn-warning';
                $export_icon =  '';
            }
        @endphp

        <a href="{{ url('certify/certificate-export-lab/'.$export->id.'/edit') }}" class="form_group btn  {{$export_btn}}"  >
                {!! $export_icon !!}    ออกใบรับรอง <span class="text-danger">(มีรายการแล้วใน CertificateExport)</span> 
        </a>

    @elseif( isset($applicant)  &&  (!is_null($applicant->certi_lab_export_mapreq_to) ) )
        <a  class="form_group btn  btn-info " href="{{ url("certify/certificate_detail/".$applicant->token)}}" >
            <i class="fa fa-paperclip"></i>  แนบท้าย
        </a> 
    @else 
        <div class="btn-group form_group">
        <form action="{{ url('/certify/certificate-export-lab/create')}}" method="POST" style="display:inline"  > 
            {{ csrf_field() }}
            {!! Form::hidden('app_token', (!empty($applicant->token) ? $applicant->token  : null) , ['id' => 'app_token', 'class' => 'form-control' ]); !!}
            <button class="btn btn-warning" type="submit" >
                ออกใบรับรอง<span class="text-danger">(ไม่มีรายการใน CertificateExport)</span> 
            </button>
        </form>
        </div>
    @endif
@else
   

@endif

 {{-- @endif --}}

    </div>
</div>

          
        <div class="white-box">
            <div class="row">


                <div class="col-sm-12">
                    <h3 class="box-title">ผลการตรวจสอบคำขอรับใบรับรองห้องปฏิบัติการ</h3>
                    <div class="row text-center">
                       

                        <form action="{{ route('check_certificate.update', ['cc' => $cc]) }}" class="form-horizontal" id="form_operating"   method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="col-sm-8">
                                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                                    {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> ผลการตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                                    <div class="col-md-7">
                                   @if($applicant->status < 6)
                                        {!! Form::select('status',
                                       [  '1'=> 'รอดำเนินการตรวจ',
                                          '2'=> 'อยู่ระหว่างการตรวจสอบ',
                                          '3'=> 'ขอเอกสารเพิ่มเติม',
                                          '4'=> 'ยกเลิกคำขอ',
                                          '5'=> 'ไม่ผ่านการตรวจสอบ',
                                          '9'=> 'รับคำขอ',], 
                                         $applicant->status ?? null, 
                                       ['class' => 'form-control', 
                                        'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-',
                                        'id'=>'cc_status',
                                        'required' => true]); !!}
                                    @else   
                                        {!! Form::text('status',   !empty($applicant->certi_lab_status_to->title)   ? $applicant->certi_lab_status_to->title : null,
                                        ['class' => 'form-control',  'disabled','id'=>'cc_status']) !!}
                                    @endif
                                      
                                      
                                    </div>
                                </div>
                            </div>
                            @if(!in_array($cc->status,['3','4','5']))
                                 <!-- 3.ขอเอกสารเพิ่มเติม 5.ไม่ผ่านการตรวจสอบ  -->
                            <div class="col-sm-8 m-t-15 isShowDesc">
                                <div class="form-group {{ $errors->has('desc') ? 'has-error' : ''}}">
                                    {!! Form::label('desc', 'ระบุรายละเอียด', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
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
                            @endif

                            <div class="col-sm-8 m-t-15">
                                <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                    {!! Form::label('employ_name', 'เจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                    <div class="col-md-7 text-left">
                                        {!! Form::text('employ_name',  $cc->FullRegName ?? null, ['class' => 'form-control', 'placeholder'=>'', 'disabled']); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                    {!! HTML::decode(Form::label('save_date', '<span class="text-danger">*</span> วันที่บันทึก', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                                    <div class="col-md-7 text-left">
                                        {!! Form::text('save_date',
                                         $cc->report_date ? HP::revertDate($cc->report_date->format('Y-m-d'),true): $Carbon->now()->addYear(543)->format('d/m/Y'), 
                                        ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 
                                        'required' => 'required','disabled' => ($cc->status >= 9) ?   true :  false ]) !!}
                                        {!! $errors->first('save_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            @if($cc->status < 9)
                            <div class="col-sm-8 m-t-15  {{ ($cc->status == 3 || $cc->status == 4 || $cc->status == 5) ? 'hide' : ''  }}">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-6 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save" onclick="submit_form('1');return false">
                                            <i class="fa fa-paper-plane"></i> บันทึก
                                        </button>

                                        <a class="btn btn-default" href="{{url('/certify/check_certificate')}}">
                                            <i class="fa fa-rotate-left"></i> ยกเลิก
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>


        @if($history->count() > 0 )
        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                     <legend><h3 class="box-title">ประวัติคำขอรับใบรับรองห้องปฏิบัติการ</h3></legend>
                     <div class="table-responsive">
                        <table class="table zero-configuration  table-hover" id="myTable" width="100%">
                            <thead>
                                    <tr>
                                        <th class="text-center bg-info  text-white" width="2%">ลำดับ</th>
                                        <th class="text-center bg-info  text-white" width="30%">วันที่/เวลาบันทึก</th>
                                        <th class="text-center bg-info  text-white" width="30%">เจ้าหน้าที่บันทึก</th>
                                        <th class="text-center bg-info  text-white" width="38%">รายละเอียด</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $key => $item)
                                    <tr>
                                        <td class="text-center">{{ $key +1 }}</td>
                                        <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                                        <td>  
                                             @if (in_array($item->system,[3,6])  && is_null($item->created_by))
                                                {{   'ระบบบันทึก' }}
                                            @else
                                                {{ $item->user_created->FullName ?? '-'}}
                                            @endif  
                                        </td>
                                        <td>
                                              @if($item->DataSystem != '-')
                                                    <button type="button" class="btn btn-link  {{!is_null($item->details_auditors_cancel) ? 'text-danger' : ''}}" style="line-height: 16px;text-align: left;" 
                                                        data-toggle="modal" data-target="#HistoryModal{{$item->id}}">
                                                            {{ @$item->DataSystem }}
                                                        <br>
                                                           
                                                     </button>

                                                                @include ('certify/check_certificate_lab.history_detail',['history' => $item])
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
        @endif

            @if(count($certi_lab->certi_auditors) > 0 )

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
                                            @foreach($certi_lab->certi_auditors as $key => $item)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                                                    <td>{{ $item->auditor ?? null }}</td>
                                                    <td>
                                                        <span style="color: {{($item->step_id == 9) ? 'red' : ''}} "> 
                                                            {{ $item->certi_lab_step_to->title ?? null }}
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
      <!-- Data Table -->
      <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
            $('#myTable').DataTable( {
                    dom: 'Brtip',
                    pageLength:5,
                    processing: true,
                    lengthChange: false,
                    ordering: false,
                    order: [[ 0, "desc" ]]
                });
         });

        @if($applicant->status == 1 && HP_API_PID::check_api('check_api_certify_check_certificate') && HP_API_PID::CheckDataApiPid($applicant, (new App\Models\Certify\Applicant\CertiLab)->getTable()) != '')

            var id    =   '{!! $applicant->id !!}';
            var table =   '{!! (new App\Models\Certify\Applicant\CertiLab)->getTable()  !!}';

            $.ajax({
                type: 'get',
                url: "{!! url('certify/function/check_api_pid') !!}" ,
                data: {
                    id:id,
                    table:table,
                    type:'false'
                },
            }).done(function( object ) {
                Swal.fire({
                    position: 'center',
                    html: object.message,
                    showConfirmButton: true,
                    width: 800
                }).then((result) => {
                    if (result.value) {
                            
                    }
                });
            });
            
        @endif

    </script>




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
            $(".delete_attach").on("click", function(){
           
                if( confirm('ต้องการลบไฟล์นี้ใช่หรือไม่ ?')){
                     $.ajax({
                           url: "{!! url('certify/check_certificate/delete_attach') !!}" + "/" + $(this).attr("data-id")
                       }).done(function( object ) {
                      });
                      $(this).parent().remove();
                }
            });
        });
        </script>
 
    <script>

        $(document).ready(function () {

            //ช่วงวันที่
            $('.date-range').datepicker({
                    toggleActive: true, 
                    language:'th-th',
                    format: 'dd/mm/yyyy',
            });




          // <!-- 3.ขอเอกสารเพิ่มเติม 5.ไม่ผ่านการตรวจสอบ  -->
             $('#cc_status').change(function(){ 
                    $('.isShowDesc').hide();
                    $('.requiredDesc').prop('required', false);

                  if($(this).val() == 3 || $(this).val() == 4 ||$(this).val() == 5){
                        $('.isShowDesc').show();
                        $('.requiredDesc').prop('required', true);
                    }

            });
            $('#cc_status').change();

            var data_hide = '{{  !empty($report) &&  ($report->status == 1) ? 1 : null  }}';
                if(data_hide == 1){
                    $('.data_hide').hide ();
                    $('.report_desc').prop('disabled', true);
                    $('.check_readonly').prop('disabled', true);
                    $('.check_readonly').parent().removeClass('disabled');
                    $('.check_readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
                }
            
        //    $('#cc_status').change(function(){ 
                      // <!-- 3.ขอเอกสารเพิ่มเติม 5.ไม่ผ่านการตรวจสอบ  -->
                    //  $('.isShowDesc').hide();
                    //  $('.requiredDesc').prop('required', false);

                    //  7.รอชำระค่าธรรมเนียม 
                    //  $('.isShowAmount').hide();
                    //  $('.requiredInvoice').prop('required', false);

                // if($(this).val() == 3 || $(this).val() == 4 ||$(this).val() == 5){
                    // $('.isShowDesc').show();
                    // $('.requiredDesc').prop('required', true);
                // }else{
                    // $('.isShowDesc').hide();
                    //  $('.requiredDesc').prop('required', false);
                // }
                // else if($(this).val() == 7 ){
                //     $('.isShowAmount').show();
                //     $('.requiredInvoice').prop('required', true);
                // }
            // });

            // $('#cc_status').change();
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

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
    
        });
        function submit_form(status) {
            var row =  '{{ !empty($cc->applicant->status) ? $cc->applicant->status : null }}';
            if((row != null && row >= 9) && $('#cc_status').val() <= 9){ 
                Swal.fire(
                        'ใบรับรองห้องปฏิบัติการ รับคำขอแล้ว',
                        '',
                        'info'
                     )
            }else{
                $('#form_operating').submit();
            }
        }
            jQuery(document).ready(function() {

               $('#form_operating').parsley().on('field:validated', function() {
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
            });
    </script>

<script type="text/javascript">
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
    });
    </script>

@endpush

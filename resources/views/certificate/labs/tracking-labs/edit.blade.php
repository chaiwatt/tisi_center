@extends('layouts.master')
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

<div class="container-fluid" >
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
                <h2 class=" pull-left" style="color:Black;">ระบบตรวจติดตามใบรับรองระบบงานห้องปฏิบัติการ (LAB) {!! !empty($tracking->certificate_no)? ' : '.$tracking->certificate_no:''  !!} </h2>
                @can('view-'.str_slug('trackinglabs'))
                    <a class="btn btn-danger  pull-right" href="{{ url('/certificate/tracking-labs') }}">
                        <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                    </a>
                @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 ">
               
<!-- start  ข้อมูลใบรับรอง -->
<a class="form_group btn btn-info "   href="{{ url('certificate/tracking-labs').'/'.$tracking->id }}" >
    ข้อมูลใบรับรอง
</a>
<!-- end  ข้อมูลใบรับรอง -->
@php 
    $user = App\User::where('runrecno',auth()->user()->runrecno)->first(); 
 
@endphp

{{-- @if($user->IsGetIdLathRoles() == 'false'  || $user->IsGetRolesAdmin() == 'true') --}}

    @if($tracking->status_id >= 2  && count($tracking->AuditorsManyBy) > 0) 
  @php 
        $auditors_btn =  '';
        $auditors_icon =  '';
    if($tracking->AuditorsStatus == "StatusSent"){
        $auditors_btn = 'btn-success';
        $auditors_icon =  '<i class="fa fa-file-text"></i>';
     }elseif($tracking->AuditorsStatus == "StatusNotView"){
        $auditors_btn =  'btn-danger';
        $auditors_icon =  '<i class="fa fa-arrow-circle-right"></i>';
     }elseif($tracking->AuditorsStatus == "StatusView"){
        $auditors_btn = 'btn-info';
        $auditors_icon =  '<i class="fa fa-check-square-o"></i>';
     }else{
        $auditors_btn =  'btn-warning';
        $auditors_icon =  '';
    }
 @endphp
   <div class="form_group btn-group ">
      <div class="btn-group">
 
            <button type="button" class="btn {{$auditors_btn}} dropdown-toggle" data-toggle="dropdown">
                {!! $auditors_icon  !!}      แต่งตั้งคณะฯ <span class="caret"></span>
             </button>

             <div class="dropdown-menu" role="menu" >
                @if($tracking->status_id >= 3 && $tracking->status_id <= 5)  
                    <form action="{{ url('/certificate/auditor-labs/create')}}" method="POST" style="display:inline"> 
                        {{ csrf_field() }}
                        {!! Form::hidden('refno', (!empty($tracking->reference_refno) ? $tracking->reference_refno  : null) , ['id' => 'ref_id', 'class' => 'form-control', 'placeholder'=>'' ]); !!}
                        <button class="btn btn-warning" type="submit"   style="width:750px;text-align: left"> 
                            <i class="fa fa-plus"></i>    แต่งตั้งคณะฯ
                        </button>
                    </form>
                @endif
                 
               @foreach($tracking->AuditorsManyBy as $key => $item)
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
                {{-- @if ($item->status_cancel != 1) --}}
                    <a  class="btn {{$auditors_btn}} " href="{{ url("certificate/auditor-labs/".$item->id."/edit")}}" style="background-color:{{$auditors_btn}};width:750px;text-align: left">
                        ครั้งที่ {{ ($key + 1 )}} :  
                        {{ $item->auditor ?? '-'}}
                    </a> 
                    <br>
                {{-- @endif --}}
                 
                @endforeach
             </div>

        </div>
    </div>

    @elseif($tracking->status_id >= 2)  
     <div class="form_group btn-group ">
        <form action="{{ url('/certificate/auditor-labs/create')}}" method="POST" style="display:inline"  > 
            {{ csrf_field() }}
            {!! Form::hidden('refno', (!empty($tracking->reference_refno) ? $tracking->reference_refno  : null) , ['id' => 'ref_id', 'class' => 'form-control', 'placeholder'=>'' ]); !!}
            <button class="btn btn-warning" type="submit" >
                  <i class="fa fa-plus"></i>    แต่งตั้งคณะฯ
            </button>
        </form>
     </div>
    @endif

{{-- @endif      --}}



   <!-- START  admin , ผอ , ผก , เจ้าหน้าที่ ลท. -->
   {{-- @if(auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("26",auth()->user()->RoleListId))                      --}}
   <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->
 
   @if($tracking->status_id >= 3  && count($tracking->tracking_payin_one_many) > 0)
  @php 
       $payin1_btn =  '';
       $payin1_icon =  '';
 
          if($tracking->CertiPayInOneStatus == "StatePayInOne"){
              $payin1_btn = 'btn-success';
              $payin1_icon =  '<i class="fa fa-file-text"></i>';
          }elseif($tracking->CertiPayInOneStatus == "StatusPayInOneNotNeat"){
              $payin1_btn =  'btn-danger';
              $payin1_icon =  '<i class="fa fa-arrow-circle-right"></i>';
          }elseif($tracking->CertiPayInOneStatus == "StatusPayInOneNeat"){
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

               @foreach($tracking->tracking_payin_one_many as $key => $item)
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
                          <a  class="btn {{$payin1_btn}} " href="{{ url("certificate/tracking-labs/Pay_In1/".$item->id)}}" style="width:750px;text-align: left">
                              ครั้งที่ {{  ($key +1) }} :      {{  (!empty($item->auditors_to->auditor) ?  $item->auditors_to->auditor : '') }}
                            
                          </a> 
                          <br>
                      @endif
                @endforeach
  
             </div>
        </div>
    </div>
  
   @endif 
  
   <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->
   {{-- @endif  --}}
   <!-- END -->  


      
 <!-- START  admin , ผอ , ผก ,เจ้าหน้าที่ CB  -->
 {{-- @if((auth()->user()->SetRolesLicenseCertify() == "true" ||  in_array("29",auth()->user()->RoleListId)) && count($tracking->tracking_payin_one_status1_many)  > 0 )   --}}

 @if($tracking->status_id >= 3  &&  count($tracking->tracking_assessment_many) > 0 ) 
  @php 
      $assessment_btn =  '';
      $assessment_icon =  '';
   if($tracking->CertiSaveAssessmentStatus == "statusInfo"){ 
     $assessment_btn = 'btn-info';
      $assessment_icon =  '<i class="fa fa-check-square-o"></i>';
   }elseif($tracking->CertiSaveAssessmentStatus == "statusSuccess"){
      $assessment_btn = 'btn-success';
      $assessment_icon =  '<i class="fa fa-file-text"></i>';
  }elseif($tracking->CertiSaveAssessmentStatus == "statusDanger"){
     $assessment_btn =  'btn-danger';
     $assessment_icon =  '<i class="fa fa-arrow-circle-right"></i>';
  }elseif($tracking->CertiSaveAssessmentStatus == "statusPrimary"){
    //  $assessment_btn =  'btn-primary';
        $assessment_btn =  'btn-warning';
  }else{
      $assessment_btn =  'btn-warning';
      $assessment_icon =  '';
  }
 @endphp
     <div class="btn-group form_group">
         <div class="btn-group">
                 <button type="button" class="btn  {{$assessment_btn}} dropdown-toggle" data-toggle="dropdown">
                    {!! $assessment_icon !!}    ผลการตรวจประเมิน  <span class="caret"></span>
                 </button>
                 <div class="dropdown-menu" role="menu" >
                    @foreach($tracking->tracking_assessment_many as $key => $assessment)
                            @php
                                    $assessment_url =  '';
                                    $assessment_btn =  '';

                                    if ($assessment->degree == 7 || $assessment->degree == 4) { // ผ่านการการประเมิน
                                        $assessment_btn =  'btn-info';
                                        $assessment_url =  'certificate/assessment-labs/'.$assessment->id.'/edit';
                                    }elseif ($assessment->degree == 0) {  //ฉบับร่าง
                                        //   $assessment_btn =  'btn-primary';
                                        $assessment_btn =  'btn-warning';
                                        $assessment_url =  'certificate/assessment-labs/'.$assessment->id.'/edit'; 
                                    }elseif (in_array($assessment->degree,[1,3,4,6])) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  'btn-success';
                                        $assessment_url =  'certificate/assessment-labs/'.$assessment->id.'/edit';
                                    }elseif ($assessment->degree == 8) {  //จนท. ส่งให้ ผปก.
                                        $assessment_btn =  '#ffff80';
                                        $assessment_url =  'certificate/assessment-labs/'.$assessment->id.'/edit';
                                    }else {    //ผปก. ส่งให้ จนท.
                                        $assessment_btn =  'btn-danger';
                                        $assessment_url =  'certificate/assessment-labs/'.$assessment->id.'/edit';
                                    }
                            @endphp
                                <a  class="btn {{$assessment_btn}}  " href="{{ url("$assessment_url")}}"  style="background-color:{{$assessment_btn}};width:750px;text-align: left">
                                    ครั้งที่ {{   ($key +1)  }} :  
                                    {{ $assessment->auditors_to->auditor ?? '-'}}
                                </a> 
                            <br>
                        @endforeach
                 </div>
             </div>
         </div>
 
    @endif
 

  @if( $tracking->status_id >= 4 && !is_null($tracking->tracking_inspection_to) )
        @php 
                $inspection = $tracking->tracking_inspection_to;

                $inspection_btn =  '';
                $inspection_icon =  '';  
            if($tracking->status_id == 4 && is_null($inspection->status)){
                $inspection_btn =  'btn-warning';
                $inspection_icon =  '';
            }else if($tracking->status_id == 4){
                $inspection_btn =  'btn-danger';
                $inspection_icon =  '';
            }else if($tracking->status_id == 5 && is_null($inspection->status)){
                $inspection_btn =  'btn-success';
                $inspection_icon =  '';
            }else{
                $inspection_btn =  'btn-info';
                $inspection_icon =  '<i class="fa fa-check-square-o"></i>';
            }
        @endphp

        @php
            $totalPendingTransactions = 0;
            $totalTransactions = 0;
            $pendingLabReportInfos = 0;
        
            foreach ($tracking->tracking_assessment_many as $assessment) {
                $labReportInfoStatus = $assessment->trackingLabReportInfo->status;
                if($labReportInfoStatus == 1)
                {
                    $pendingLabReportInfos ++;
                }
                // dd($labReportInfo);
                $totalPendingTransactions += $assessment->trackingLabReportInfo->signAssessmentTrackingReportTransactions->where('approval',0)->count();
                $totalTransactions += $assessment->trackingLabReportInfo->signAssessmentTrackingReportTransactions->count();
              
                
            }
        @endphp
        
        {{-- {{$pendingLabReportInfos}} --}}
        @if ($totalTransactions != 0 && $totalPendingTransactions == 0 && $pendingLabReportInfos == 0)
            <a class="form_group btn {{ $inspection_btn }}" href="{{ url("certificate/inspection-labs/$inspection->id") }}">
                {!! $inspection_icon !!} สรุปผลตรวจประเมิน
            </a>
        @else 
        @if ($totalTransactions == 0)
                <span class="text-warning">รอการสร้างรายงานตรวจประเมิน</span>
            @else
                @if ($pendingLabReportInfos != 0)
                        <span class="text-warning">รอการสร้างรายงานตรวจประเมิน</span>
                    @else
                        <span class="text-warning">รอการลงนามรายงานตรวจประเมิน</span>
                @endif
        @endif
      
            
        @endif
        


  @endif 
 

  {{--  สรุปรายงาน  --}} 
  {{-- @if( $tracking->status_id >= 6  && !is_null($tracking->tracking_report_to) )
  @php 
        $report = $tracking->tracking_report_to;

         $report_btn =  '';
         $report_icon =  '';  
       if($tracking->status_id == 6 && is_null($report->report_status)){
         $report_btn =  'btn-warning';
         $report_icon =  '';
       }else if($tracking->status_id == 7 && !is_null($report->report_status)){
         $report_btn =  'btn-success';
         $report_icon =  '';
       }else{
         $report_btn =  'btn-info';
         $report_icon =  '<i class="fa fa-check-square-o"></i>';
       }
  @endphp
            <button  class="form_group btn {{$report_btn}}"   data-toggle="modal"  data-target="#report"   > {!! $report_icon !!}  สรุปรายงาน     </button>
  @endif  --}}
 
  {{--  ทบทวนฯ  --}} 
  @if( $tracking->status_id >= 6  && !is_null($tracking->tracking_review_to) )
        @php 
            $review = $tracking->tracking_review_to;

            $review_btn =  '';
            $review_icon =  '';  

            if($tracking->status_id == 6 && is_null($review->review)){
                $review_btn =  'btn-warning';
                $review_icon =  '';
            }else   if($tracking->status_id == 7 && is_null($review->review)){
                $review_btn =  'btn-danger';
                $review_icon =  '';
            }else{
                $review_btn =  'btn-info';
                $review_icon =  '<i class="fa fa-check-square-o"></i>';
            }
        @endphp
        <button  class="form_group btn {{$review_btn}} "  data-toggle="modal"  data-target="#review"   > {!! $review_icon !!}  ทบทวนฯ </button>       
 
  @endif 

    {{--  Pay-in ครั้งที่ 2  --}} 
    {{-- @if( $tracking->status_id >= 9  && !is_null($tracking->tracking_payin_two_to) )
    @php 
          $pay_in2 = $tracking->tracking_payin_two_to;
  
           $pay_in2_btn =  '';
           $pay_in2_icon =  '';  
         if(is_null($pay_in2->state)){
           $pay_in2_btn =  'btn-warning';
           $pay_in2_icon =  '';
         }else if($pay_in2->state == 2){
           $pay_in2_btn =  'btn-danger';
           $pay_in2_icon =  '';
         }else if($pay_in2->state == 1){
           $pay_in2_btn =  'btn-success';
           $pay_in2_icon =  '';
         }else{
           $pay_in2_btn =  'btn-info';
           $pay_in2_icon =  '<i class="fa fa-check-square-o"></i>';
         }
    @endphp
  
        <a  class="form_group btn {{$pay_in2_btn}}" href="{{ url("certificate/inspection-labs/pay-in2/$pay_in2->id")}}" >
             {!! $pay_in2_icon  !!}     Pay-in ครั้งที่ 2 
       </a>
    
   
    @endif  --}}
    {{-- {{$tracking->status_id}} --}}
    {{-- @if( $tracking->status_id == 6  || $tracking->status_id == 7 )     --}}
    @if( $tracking->status_id >= 7 )   
        @if ($tracking->status_id == 8)
            <a  class="btn btn-info form_group" href="{{ url("certificate/tracking-labs/append/$tracking->id")}}" >
                <i class="fa fa-check-square-o"></i>   แนบท้าย
            </a>  
        @else
            <a  class="btn btn-warning form_group" href="{{ url("certificate/tracking-labs/append/$tracking->id")}}" >
                อัพเดทแนบท้าย
            </a> 
        @endif
  @endif 



  
 {{-- @endif  --}}
  <!-- END -->  

            

        </div>
    </div>

    {{-- @if( $tracking->status_id >= 6  && !is_null($tracking->tracking_report_to) )
        @include('certificate.labs.tracking-labs.modal.report',['certi'=> $tracking,'report' => $tracking->tracking_report_to ])   
    @endif 
     --}}
    @if( $tracking->status_id >= 6  && !is_null($tracking->tracking_review_to) )    
        @include('certificate.labs.tracking-labs.modal.review',['certi'=> $tracking,'review' => $tracking->tracking_review_to])
    @endif 
 
 
 
<div class="white-box">
    <div class="row">
        <div class="col-sm-12">
            <h3>สถานะการตรวจติดตามใบรับรองห้องปฏิบัติการ</h3>
            <hr>
<div class="row">
    <div class="col-sm-12">
        <p class="col-md-3 text-right">เลขที่อ้างอิง : </p>
        <p class="col-md-9"> {!! !empty($tracking->reference_refno)?  $tracking->reference_refno:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-3 text-right">ห้องปฏิบัติการ : </p>
        <p class="col-md-9"> {!! !empty($tracking->certificate_export_to->CertiLabTo->lab_name)?  $tracking->certificate_export_to->CertiLabTo->lab_name:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-3 text-right">สถานะการดำเนินการ : </p>
        <p class="col-md-9"> {!!   !empty($tracking->tracking_status->title)? $tracking->tracking_status->title:'N/A' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-3 text-right">เจ้าหน้าที่ที่ได้รับมอบหมาย  : </p>
        <p class="col-md-9"> {!! !empty($tracking->AssignName) && count($tracking->AssignName) > 0 ?   implode(',', $tracking->AssignName)  :''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-3 text-right">วันที่ได้รับมอบหมาย : </p>
        <p class="col-md-9"> {!! !empty($tracking->assigns_to->created_at)?  HP::DateTimeThai($tracking->assigns_to->created_at):''  !!} </p>
    </div>
</div>
        </div>
    </div>
</div>
 

@if (count($tracking->history_many) > 0)
<div class="white-box">
    <div class="row">
        <div class="col-sm-12">
            <h3>ประวัติคำขอรับใบรับรองห้องปฏิบัติการ</h3>
            <hr>
 
<div class="row">
    <div class="col-sm-12">
 
        <div class="table">
            <table class="table myTable"   width="100%">
                <thead>
                        <tr>
                            <th class="text-center " width="2%">ลำดับ</th>
                            <th class="text-center " width="30%">วันที่/เวลาบันทึก</th>
                            <th class="text-center " width="30%">เจ้าหน้าที่บันทึก</th>
                            <th class="text-center " width="38%">รายละเอียด</th>
                        </tr>
                </thead>
                <tbody>
                    @foreach ($tracking->history_many as  $key => $item) 
                        <tr>
                            <td class="text-center">{!! ($key+1) !!}</td>
                            <td> {{HP::DateTimeThai($item->created_at) ?? '-'}} </td>
                            <td>
                                @if ($item->system == 5  && is_null($item->created_by))
                                    {{   'ระบบบันทึก' }}
                                @else
                                    {{ $item->CreatedName ?? ''}}
                                @endif
                            </td>
                            <td>

                                @if($item->DataSystem != '-')
                                    <button type="button" class="btn btn-link {{!is_null($item->details_auditors_cancel) ? 'text-danger' : ''}}" style="line-height: 16px;text-align: left;" 
                                            data-toggle="modal" data-target="#HistoryModal{{$item->id}}">
                                           {{ @$item->DataSystem }}
                                           <br>
                                           <!-- แต่งตั้งคณะผู้ตรวจประเมิน  -->
                                           @if(!is_null($item->auditors_id))
                                               <span class="text-danger" style="font-size: 10px">
                                                   {{ isset($item->auditors_to->auditor) ? '( '.$item->auditors_to->auditor.' )' : null }}
                                               </span>
                                           @endif  
                                    </button>

                                    @include ('certificate/labs/tracking-labs.history_detail',['history' => $item])
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
 
    <script>
        $(document).ready(function(){
                $('.check-readonly').prop('disabled', true);
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
                $('.myTable').DataTable( {
                    dom: 'Brtip',
                    pageLength:5,
                    processing: true,
                    lengthChange: false,
                    ordering: false,
                    order: [[ 0, "desc" ]]
                });

                
                //ปฎิทิน
                $('.mydatepicker').datepicker({
                    toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
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

            @if(\Session::has('message'))
                $.toast({
                    heading: 'Success!',
                    position: 'top-center',
                    text: '{{session()->get('message')}}',
                    loaderBg: '#70b7d6',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 6
                });
            @endif
          });
    </script>

@endpush

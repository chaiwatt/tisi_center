@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 <!-- Data Table CSS -->
 <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
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
        <div class="m-b-15">
            <a class="btn {{ ($cc->applicant->status >= 9) ? 'btn-info' : 'btn-warning'  }} "   href="{{ route('show.certificate.applicant.detail', ['certilab'=>$cc->applicant]) }}" >
              <i class="fa fa-search" aria-hidden="true"></i> คำขอ
            </a>
 
            <a class="btn btn-info"   href="{{ url('certify/check_certificate/export_word/'.$cc->applicant->id) }}" >
                <i class="fa fa-cloud-download"></i>  download
           </a>

@php 
    $User = App\User::where('runrecno',auth()->user()->runrecno)->first(); 
    // เช็คเจ้าหน้าที่ สก. 
    if(in_array("9",$User->RoleListId)){
        $staff  = "true";
    }

@endphp
{{-- @if($User->IsGetIdLathRoles() == 'false'  || $User->IsGetRolesAdmin() == 'true') --}}
            @if($cc->applicant->status >= 9)
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
                        <a  class="btn {{$agree}}" href="{{ route('estimated_cost.edit', ['ec' => $Cost]) }}" >
                            {!! $cost_icon  !!}     ค่าใช้จ่าย
                        </a>
                    @else 
                    <a class="btn btn-info" href="{{ route('estimated_cost.index') }}" >
                        ค่าใช้จ่าย
                    </a>
                    @endif
            @endif 
            
            @if($cc->applicant->status >= 12 && (!is_null($Cost) &&  $Cost->check_status == 1   &&  $Cost->status_scope  == 1))
                @if(!is_null($cc->applicant->CertifyBoardAuditor)   )
                    @php 
                        $auditor =  '';
                    $status_cancel  =  $cc->applicant->CertifyBoardAuditor->status_cancel;
                     $status =   $cc->applicant->CertifyBoardAuditor->status;
                     $state =   $cc->applicant->CertifyBoardAuditor->state;
                     $vehicle =   $cc->applicant->CertifyBoardAuditor->vehicle;
                      $auditors_icon =  '';
                    if($status == 1){//ผ่านการประมาณค่าใช้จ่ายแลล้ว
                        $auditor = 'btn-info';
                        $auditors_icon = '<i class="fa fa-check-square-o"></i>';
                    }elseif($status == 2 ){    // ผปก. ส่งมา
                        $auditor = 'btn-danger';
                        $auditors_icon =  '<i class="fa fa-arrow-circle-right"></i>';
                    }elseif($state == 1  &&  $vehicle  == 1){  // ส่งให้ ผปก. แล้ว 
                        $auditor = 'btn-success';
                        $auditors_icon =  '<i class="fa fa-check-square-o"></i>';
                    }else{
                        $auditor = 'btn-warning'; 
                    }
                    @endphp
                    @if (!is_null($status_cancel))
                        <a  class="btn btn-warning"  href="{{ url('certify/auditor') }}" >
                                แต่งตั้งคณะฯ
                        </a>
                    @else
                        <a class="btn {{$auditor}}" href="{{ url('/certify/auditor/'.$cc->applicant->CertifyBoardAuditor->id.'/edit', ['']) }}"  >
                            {!! $auditors_icon  !!}        แต่งตั้งคณะฯ
                        </a>
                    @endif
         
                @else 
                <a  class="btn btn-warning"  href="{{ url('certify/auditor') }}" >
                    แต่งตั้งคณะฯ
               </a>
                @endif
            @endif 

{{-- @endif          --}}
{{-- @if($User->IsGetIdLathRoles() == 'true'   || $User->IsGetRolesAdmin() == 'true') --}}

            @if($cc->applicant->status >= 14 )
                    <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->
                    @php 
                           $assessment =  '';
                           $icon_assessment =  '';
                        if(!is_null($find_cost_assessment) && ($find_cost_assessment->status_confirmed == 1   && ($find_cost_assessment->status_confirmed != 3 ))){
                            $assessment = 'btn-info';
                            $icon_assessment =  '<i class="fa fa-check-square-o"></i>';
                        }elseif(!is_null($find_cost_assessment) &&  $find_cost_assessment->invoice != ''  && ($find_cost_assessment->status_confirmed != 3 )){
                            $assessment = 'btn-danger';
                            $icon_assessment =  '<i class="fa fa-arrow-circle-right"></i> ';
                        }elseif(!is_null($find_cost_assessment)  && $find_cost_assessment->conditional_type != ''  && ($find_cost_assessment->status_confirmed != 3 )){
                            $assessment = 'btn-success';
                            $icon_assessment =  '<i class="fa fa-file-text"></i>';
                        }else{
                            $assessment = 'btn-warning';
                            $icon_assessment =  '';
                        }
                    @endphp
                     <a   class="btn {{$assessment }}" href="{{ url('/certify/check_certificate/Pay_In1/'.base64_encode($find_cost_assessment->id)) }}"   >
                          {!! $icon_assessment !!}
                           Pay-in ครั้งที่ 1
                    </a>
                    {{-- <button type="button" class="btn {{$assessment }}"  data-toggle="modal" data-target="#exampleModal">
                        {!! $icon_assessment !!}
                        Pay-in ครั้งที่ 1
                   </button>
                   @include ('certify.check_certificate_lab.modal')  --}}
                   <!-- Button trigger modal     แนบใบ Pay-in ครั้งที่ 1 -->
             @endif 
 {{-- @endif  --}}

{{-- @if($User->IsGetIdLathRoles() == 'false'  || $User->IsGetRolesAdmin() == 'true') --}}

             @if($cc->applicant->status >= 16 && ( isset($find_cost_assessment) && $find_cost_assessment->status_confirmed == 1) ) 
               @if(count($cc->applicant->notices) > 0)
                    @php 
                        $notice =  $cc->applicant->notices->last();
                    @endphp

                     @if($notice->status == 3) 
                        <a href="{{ url('certify/save_assessment/show/'.$notice->id) }}" class="btn btn-info">
                        <i class="fa fa-check-square-o"></i>  ผลการตรวจประเมิน
                      </a>
                    @else 
                    
                        @if(!is_null($notice->step)) 
                                @php 
                                     $step = '';
                                     $assessment_icon =  '';
                                    if($notice->step == 4){
                                        $step = 'btn-info';
                                        $assessment_icon =  '<i class="fa fa-check-square-o"></i>';
                                    }elseif($notice->step == 3  || $notice->step == 1){
                                        $step = 'btn-success';
                                        $assessment_icon =  '<i class="fa fa-file-text"></i>';
                                    }else{
                                        $step = 'btn-danger';
                                        $assessment_icon =  '<i class="fa fa-arrow-circle-right"></i>';
                                    }
                                @endphp
                                <a  class="btn {{ $step }}"  href="{{  route('save_assessment.assess_edit', ['notice' => $notice, 'app' => $cc->applicant ? $cc->applicant->id : '']) }}"  >
                                    {!! $assessment_icon  !!}      ผลการตรวจประเมิน 
                                </a>
                         @else 
                            <a  class="btn btn-warning"  href="{{ url('/certify/save_assessment/'.$notice->id.'/edit', ['']) }}"  >
                               <i class="fa fa-check-square-o"></i>  ผลการตรวจประเมิน 
                           </a>
                          @endif
                    @endif

                @else 
                <a  class="btn btn-warning" href="{{ url('certify/save_assessment') }}" >
                    ผลการตรวจประเมิน 
                </a>
                @endif
            @endif 
            

            @php
                $report = App\Models\Certify\Applicant\Report::where('app_certi_lab_id',$cc->applicant->id)
                                                         ->orderby('id','desc')
                                                         ->first();

            @endphp
            @if($cc->applicant->status >=20 && !is_null($report))
            @php 
                    $btn_report = '';
                    $report_icon =  '';
                  if(!is_null($report->updated_by)){
                      $btn_report = 'btn-info';
                      $report_icon =  '<i class="fa fa-check-square-o"></i>';
                   }elseif(!is_null($report->created_by)){
                       $btn_report = 'btn-success';
                       $report_icon =  '<i class="fa fa-file-text"></i>';
                   }else{
                      $btn_report = 'btn-warning';
                   }
             @endphp
            <!-- Button trigger modal     	สรุปรายงานและเสนออนุกรรมการฯ  -->
            <button type="button" class="btn {{ $btn_report }}" data-toggle="modal" data-target="#exampleModalReport">
                    {!! $report_icon !!} สรุปรายงาน
            </button>
            @include ('certify.check_certificate_lab.modal_report')
             <!-- Button trigger modal    	สรุปรายงานและเสนออนุกรรมการฯ  -->
            @endif

 {{-- @endif          --}}
 {{-- @if($User->IsGetIdLathRoles() == 'true'  || $User->IsGetRolesAdmin() == 'true') --}}

            @if($cc->applicant->status >=22  && !is_null($cc->applicant->status))
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
            <a  class="btn {{$btn_costcerti}} " href="{{ url("certify/check_certificate/Pay_In2/".@$costcerti->id."/".$cc->applicant->token)}}">
                {!! $icon_costcerti !!}  Pay-in ครั้งที่ 2
             </a> 
             <!-- Button  แนบใบ Pay-in ครั้งที่  2  -->
            @endif

            @if($cc->applicant->status >= 25 )
            
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalExport">
                 แนบท้าย
              </button>
              @include ('certify.check_certificate_lab.modal_export')

                 @if( isset($cc->applicant)  &&  !is_null($cc->applicant->CertificateExportId))
                    <a href="{{ url('certify/certificate-export-lab/'.$cc->applicant->CertificateExportId.'/edit') }}" class="btn  btn-info ">
                        ออกใบรับรอง
                     </a>
                 @else 
                  <a  class="btn btn-warning" href="{{ url('certify/certificate-export-lab') }}" >
                  {{-- <a  class="btn btn-warning" href="{{ url('certify/certificate-export-lab/'.$cc->applicant->id.'/edit') }}" > --}}
                   ออกใบรับรอง
                 </a>
                @endif

            @endif

 {{-- @endif --}}

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
                                   @if($cc->status < 9)
                                        {!! Form::select('status',
                                       [  '1'=> 'รอดำเนินการตรวจ',
                                          '2'=> 'อยู่ระหว่างการตรวจสอบ',
                                          '3'=> 'ขอเอกสารเพิ่มเติม',
                                          '4'=> 'ยกเลิกคำขอ',
                                          '5'=> 'ไม่ผ่านการตรวจสอบ',
                                          '9'=> 'รับคำขอ',], 
                                       $cc->status ?? null, 
                                       ['class' => 'form-control', 
                                        'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-',
                                        'id'=>'cc_status',
                                        'required' => true]); !!}
                                    @else   
                                        {!! Form::text('status',  array_key_exists($cc->status,HP::DataStatusCertify()) ? HP::DataStatusCertify()[$cc->status] : null ,
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
                                         $cc->report_date ? HP::revertDate($cc->report_date->format('Y-m-d'),true): null, 
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
                                        <td> {{ $item->user_created->FullName ?? '-'}}</td>
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
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
            $('#myTable').DataTable( {
                    dom: 'Brtip',
                    pageLength:5,
                    processing: true,
                    lengthChange: false,
                    ordering: false,
                    order: [[ 0, "desc" ]]
                });
         });

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

            var staff = '{{ isset($staff) ? "true" : "false" }}';
            if(staff == 'true'){
                // $("#cc_status option[value='9']").prop('disabled', true);  
                // $("#cc_status option[value='13']").prop('disabled', true);  
                // $("#cc_status option[value='14']").prop('disabled', true);  
                // $("#cc_status option[value='15']").prop('disabled', true);  
                // $("#cc_status option[value='16']").prop('disabled', true);  
                // $("#cc_status option[value='17']").prop('disabled', true);  
                // $("#cc_status option[value='18']").prop('disabled', true);  
                // $("#cc_status option[value='22']").prop('disabled', true);  
                // $("#cc_status option[value='23']").prop('disabled', true);  
                // $("#cc_status option[value='19']").prop('disabled', true);  
                // $("#cc_status option[value='21']").prop('disabled', true);  
                // $("#cc_status option[value='25']").prop('disabled', true);  
            }
         




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

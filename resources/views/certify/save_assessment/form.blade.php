@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style>
        textarea.form-control {
            border-radius: 0 !important;
            border-top: none !important;
            border-bottom: none !important;
            resize: none;
            overflow: hidden; /* ซ่อน scrollbar */
        }
        .no-hover-animate tbody tr:hover {
            background-color: inherit !important; /* ปิดการเปลี่ยนสี background */
            transition: none !important; /* ปิดเอฟเฟกต์การเปลี่ยนแปลง */
        }
        
        /* กำหนดขนาดความกว้างของ SweetAlert2 */
        .custom-swal-popup {
            width: 500px !important;  /* ปรับความกว้างตามต้องการ */
        }

        textarea.non-editable {
            pointer-events: none; /* ทำให้ไม่สามารถคลิกหรือแก้ไขได้ */
            opacity: 0.9; /* กำหนดความทึบของ textarea */
        }
    </style>
@endpush

<div class="modal fade" id="modal-email-to-expert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">รายละเอียด</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            @php
                $hasEmail = false; // ตั้งค่าเริ่มต้นว่าไม่มีอีเมล
            @endphp

            @foreach ($statusAuditorMap as $statusId => $auditorIds)
                @foreach ($auditorIds as $auditorId)
                    @php
                        $info = HP::getExpertInfo($statusId, $auditorId);
                        if ($info->auditorInformation->email !== null) {
                            $hasEmail = true; // ถ้ามีอีเมลอย่างน้อย 1 รายการ เปลี่ยนเป็น true
                            // dd($info->auditorInformation->email);
                        }
                    @endphp
                @endforeach
            @endforeach

            <div class="modal-body text-left">
                
                <div class="row">
                    <div class="col-md-12 form-group" >
                       <table  class="table bordered-table">
                        <tr>
                            <th>#</th>
                            <th>ชื่อ-สกุล</th>
                            <th>อีเมล</th>
                            {{-- <th>สถานะ</th> --}}
                        </tr>
                        <tbody>
            
                            @php
                                $index = 0;
                            @endphp
                           @foreach ($statusAuditorMap as $statusId => $auditorIds)
                           @php $index++; @endphp
                          
                           @foreach ($auditorIds as $auditorId)
                           <tr>
                               @php
                                   $info = HP::getExpertInfo($statusId, $auditorId);
                               @endphp
                               @if ($info->auditorInformation->email != null)
                                   @php
                                       $user = HP::isTisiOfficer($info->auditorInformation->email);
                                   @endphp
                                        @if ($user == null)
                                            <td>
                                                <input type="checkbox" class="expert-email-checkbox" 
                                                    checked 
                                                    data-email="{{$info->auditorInformation->email}}">
                                            </td>
                                            <td>
                                                {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}
                                            </td>
                                            <td>
                                                {{$info->auditorInformation->email}}
                                            </td>
                                            <td></td>
                                        @else
                                            <td></td>
                                            <td>
                                                {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}
                                            </td>
                                            <td>
                                                <span class="text-danger">ไม่พบอีเมล</span>
                                            </td>
                                        @endif

                                   @else
                                        <td></td>
                                        <td>
                                            {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}
                                        </td>
                                        <td>
                                            <span class="text-danger">ไม่พบอีเมล</span>
                                        </td>

                               @endif   
                            </tr>
                           @endforeach
                                   
                       @endforeach
                       
                        </tbody>
                       </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        @if ($hasEmail)
                            <button type="button" class="btn btn-success pull-right" id="button_email_to_expert">
                                <span aria-hidden="true">ส่งอีเมล</span>
                            </button>
                            
                        @endif
                    </div>
                   @if ($find_notice !== null)
                   <div class="col-md-12 mt-2">
                        <hr>
                    <span style="font-weight:600">หมายเหตุ:</span> ท่านสามารถใช้ลิงก์เพื่อจัดส่งให้ผู้เชี่ยวชาญในช่องทางอื่น
                    <p>
                        @php
                            $config = HP::getConfig();
                            $url = $config->url_center.'create-by-expert/' . $find_notice->id .'?token='.$find_notice->expert_token;
                        @endphp
                        <a href="{{$url}}" target="_blank">ลิงก์บันทึกข้อมูล</a>
                    </p>
                    </div>
                   @endif
                  
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{-- <div class="col-md-6">
                <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
                <div class="col-md-8">
                    {!! Form::hidden('group_id',  $notice->assessment_group->id ??  null,  ['class' => 'form-control', 'id'=>'appGroupId','required'=>true])!!}
                    @if(!isset($notice->applicant->app_no) && isset($app_no))
                    {!! Form::select('auditor_id',
                          $app_no,
                          null,
                        ['class' => 'form-control',
                        'ref'=>'auditor_id',
                        'id'=>'auditor_id',
                        'placeholder'=>'-เลือกคำขอ-'])
                      !!}
                    @else 
                      <input type="text" class="form-control"  name="app_no"   value="{{ $notice->applicant->app_no ?? null }}"   readonly >  
                    @endif
                   
                </div>
            </div> --}}
            <div class="col-md-6" hidden>
                <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
                <div class="col-md-8">
                    <!-- Hidden Input -->
                    <input type="hidden" name="group_id" id="appGroupId" class="form-control" value="{{ $notice->assessment_group->id ?? null }}" required>    
                    @if (!isset($notice->applicant->app_no) && isset($app_no))
                        <select name="auditor_id"  class="form-control" id="auditor_id" ref="auditor_id" required>
                            <option value="" disabled selected>-เลือกคำขอ-</option>
                            @foreach ($app_no as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" name="app_no" value="{{ $notice->applicant->app_no ?? null }}" readonly>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                <label class="col-md-4 text-right">ชื่อผู้ยื่นคำขอ : </label>
                <div class="col-md-8">
                    {!! Form::text('department',   $notice->applicant->name ??  null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                </div>
            </div>
            <div class="col-md-6">
                {{-- @if ($notice !== null)
                
                @endif --}}
{{-- {{$notice}} --}}
                <input type="hidden" id="app_id" value="">
                <label class="col-md-4 text-right">ชื่อห้องปฏิบัติการ : </label>
                <div class="col-md-8">
                    {!! Form::text('lab_name',   $notice->applicant->lab_name ??  null,  ['class' => 'form-control', 'id'=>'appLabName','disabled'=>true])!!}
                </div>
            </div>
            {{-- {{$find_notice}} --}}

           
        </div>
        <div class="form-group">
           
            {{-- @if(isset($notice))  --}}
            <div class="col-md-6">
                <label class="col-md-4 text-right"><span class="text-danger">*</span> วันที่บันทึก : </label>
                <div class="col-md-8">
                     <div class="input-group">
                        {!! Form::text('savedate', !empty($notice->assessment_date) ? HP::revertDate($notice->assessment_date->format("Y-m-d"),true) :  null,  ['class' => 'form-control mydatepicker', 'id'=>'SaveDate','required'=>true,'placeholder'=>'dd/mm/yyyy'])!!}
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                </div>
            </div>

            @if ($find_notice != null)
                @if ($find_notice->submit_type == 'save')
                <input type="hidden" id="notice_id" value="{{$find_notice->id}}">
                    <div class="col-md-6">
                        <label class="col-md-4 text-right">แจ้งผู้เชี่ยวชาญ : </label>
                        
                        <div class="col-md-8">
                            <button type="button" class="btn btn-info" id="show-modal-email-to-expert"><i class="fa fa-envelope"></i> อีเมล</button>
                        </div>
                    </div>
                @endif

                {{-- {{$find_notice->submit_type}} --}}
                {{-- @if ($find_notice->submit_type == 'confirm' || $find_notice->submit_type == null)
                        <a href="{{ route('save_assessment.assess_edit', ['notice' => $find_notice, 'app' => $app ? $app->id : '']) }}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>
                @elseif($find_notice->submit_type == 'save')
                    <a href="{{ route('save_assessment.create',['board_auditor_id' => $find_notice->assessment->board_auditor_to->id]) }}" class="btn btn-info">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                @endif --}}
                    <div class="col-md-6">
                        <label class="col-md-4 text-right">รายงานการตรวจประเมิน : </label>
                        
                        <div class="col-md-8 mt-5" style="margin-top:5px">
                            {{-- <a type="button" href="{{ route('save_assessment.create',['board_auditor_id' => $find_notice->assessment->board_auditor_to->id]) }}" class="btn btn-info mt-5" >รายงานที่1</a>
                            <a type="button" class="btn btn-info mt-5" >รายงานที่2 (ปิดcar)</a> --}}

                            @if ($find_notice->labReportInfo !== null)
                                {{-- รายงานที่1 --}}
                                @if ($find_notice->labReportInfo->status === "1")
                                        <a href="{{route('save_assessment.view_lab_info',['id' => $find_notice->id])}}"
                                            title="จัดทำรายงาน" class="btn btn-warning">
                                            รายงานที่1
                                        </a>
                                    @else
                                        <a href="{{route('save_assessment.view_lab_info',['id' => $find_notice->id])}}"
                                            title="จัดทำรายงาน" class="btn btn-info">
                                            รายงานที่1
                                        </a>
                                @endif 

                                @if(!is_null($find_notice) && !is_null($find_notice->file) )
                                   
                                        <a href="{{url('certify/check/file_client/'.$find_notice->file.'/'.( !empty($find_notice->file_client_name) ? $find_notice->file_client_name : 'null' ))}}" 
                                            title=" {{ !empty($find_notice->file_client_name) ? $find_notice->file_client_name : basename($find_notice->file)}}"   target="_blank">
                                            {!! HP::FileExtension($find_notice->file)  ?? '' !!} 
                                        </a>
                                        {{-- <button id="button_audit_report" type="button" class="btn btn-danger btn-xs {{ ($find_notice->report_status == 2) ? 'hide' : '' }}"><i class="fa fa-trash"></i></button> --}}
                                    
                                @endif
                                {{-- รายงาน2 --}}
                                {{-- @if ($find_notice->labReportInfo->status === "1")
                                        <a href="{{route('save_assessment.view_lab_info',['id' => $find_notice->id])}}"
                                            title="จัดทำรายงาน" class="btn btn-warning">
                                            รายงานที่2(ปิดcar) ซ่อนรอจนกว่าลูกค้าส่งแนวทางมา
                                        </a>
                                    @else
                                        <a href="{{route('save_assessment.view_lab_info',['id' => $find_notice->id])}}"
                                            title="จัดทำรายงาน" class="btn btn-info">
                                            รายงานที่2(ปิดcar) ซ่อนรอจนกว่าลูกค้าส่งแนวทางมา
                                        </a>
                                @endif  --}}
                            @endif
                        </div>
                    </div>

        
              
            @endif
            {{-- @endif --}}

        </div>
        <div class="form-group">
            <div class="col-md-6" >
                <label class="col-md-5 text-right"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                <div class="col-md-7">
                    <div class="row">
                        <label class="col-md-6">
                            {!! Form::radio('report_status', '1', isset($notice)  && !empty($notice->report_status ==1) ? false:true , ['class'=>'check', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
                        </label>
                        <label class="col-md-6">
                            {!! Form::radio('report_status', '2', isset($notice) && !empty($notice->report_status ==1) ? true: false, ['class'=>'check', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
                        </label>
                    </div>
                </div>
            </div>

            @if(isset($notice)) 
            <div class="col-md-6">
                <label class="col-md-4 text-right"><span class="text-danger">*</span>รายงานการตรวจประเมิน : </label>
                <div class="col-md-8">
                    @if(isset($notice)  && !is_null($notice->file)) 
                        <a href="{{url('certify/check/file_client/'.$notice->file.'/'.( !empty($notice->file_client_name) ? $notice->file_client_name : 'null' ))}}" 
                            title=" {{ !empty($notice->file_client_name) ? $notice->file_client_name : basename($notice->file)}}"   target="_blank">
                            {!! HP::FileExtension($notice->file)  ?? '' !!}
                        </a>

                        
                    {{-- @else 
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput" >
                        <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="file" required class="check_max_size_file">
                            </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div> --}}
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        {{-- <div class="form-group" id="div_file_scope">
            <div class="col-md-12">
                <div class="white-box" style="border: 2px solid #e5ebec;">
                <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>   
                      
                   <div class="row">
                       <div class="col-md-12 ">
                           <div id="other_attach-box">
                               <div class="form-group other_attach_scope">
                                   <div class="col-md-4 text-right">
                                       <label class="attach_remove"><span class="text-danger">*</span> Scope  </label>
                                   </div>
                                   <div class="col-md-6">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput" >
                                            <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="file_scope[]"  class=" check_max_size_file">
                                                </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                            </div>
                                   </div>
                                   <div class="col-md-2 text-left">
                                       <button type="button" class="btn btn-sm btn-success attach_remove" id="attach_add_scope">
                                           <i class="icon-plus"></i>&nbsp;เพิ่ม
                                       </button>
                                       <div class="button_remove_scope"></div>
                                   </div> 
                                </div>
                              </div>
                        </div>
                   </div>

               </div>
           </div>
        </div> --}}

    </div>
</div>

<div class="clearfix"></div>
<div class="row status_report_status">
    <div class="col-md-12 text-right" v-if="isTable">
        <button type="button" class="btn btn-success btn-sm" id="plus-row"><i class="icon-plus"></i> เพิ่ม</button>
    </div>
    <div class="col-sm-12 m-t-15 " v-if="isTable" id="box-required">
        <table class="table color-bordered-table primary-bordered-table no-hover-animate" >
            <thead>
            <tr>
                <th class="text-center" width="1%">ลำดับ</th>
                <th class="text-center" width="10%">รายงานที่</th>
                <th class="text-center" width="25%">ข้อบกพร่อง/ข้อสังเกต</th>
                <th class="text-center" width="10%">มอก. 17025 : ข้อ</th>
                <th class="text-center" width="8%">ประเภท</th>
                {{-- <th class="text-center" width="10%">ผู้พบ</th> --}}
                <th class="text-center" width="3%"> <i class="fa fa-pencil-square-o"></i></th>
            </tr>
            </thead>
            <tbody id="table-body">
                @foreach($NoticeItem as $key => $item)
                    <tr>
                        <td class="text-center" style="padding: 0px;">1</td>
                        <td style="padding: 0px;">
                            <input type="hidden" name="id[]" value="{{ $item->id ?? '' }}" class="form-control">
                            <textarea name="report[]" class="form-control input_required auto-expand" rows="5" style="border-right: 1px solid #ccc;" required>{{ $item->report ?? '' }}</textarea>
                        </td>
                        <td style="padding: 0px;">
                            <textarea name="notice[]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->remark ?? '' }}</textarea>
                        </td>
                        <td style="padding: 0px;">
                            <textarea name="nok[]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->no ?? '' }}</textarea>
                        </td>
                        <td style="padding-left: 15px;vertical-align:top">
                            <select name="type[]" class="form-control type input_required select2" required>
                                <option value="" disabled selected>-เลือกประเภท-</option>
                                <option value="1" {{ isset($item->type) && $item->type == 1 ? 'selected' : '' }}>ข้อบกพร่อง</option>
                                <option value="2" {{ isset($item->type) && $item->type == 2 ? 'selected' : '' }}>ข้อสังเกต</option>
                            </select>
                        </td>
                        <td class="text-center" style="vertical-align:top">
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-10 ">
            <div id="other_attach">
                <div class="form-group other_attach_item">
                    <div class="col-md-2 text-light">
                        <label for="#" class="label_other_attach text-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ไฟล์แนบ : </label>

                    </div>
                    <div class="col-md-6">
                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span> 
                                {{-- {!! Form::file('attachs[]', null) !!} --}}
                                <input type="file" name="attachs[]" class="check_max_size_file">
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                        {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-md-2 text-left">
                        <button type="button" class="btn btn-sm btn-success attach-add" id="attach-add">
                            <i class="icon-plus"></i>&nbsp;เพิ่ม
                        </button>
                        <div class="button_remove"></div>
                    </div>
                </div>
            </div>
            @if(isset($notice)  && !is_null($notice->attachs)) 
                @php 
                $attachs = json_decode($notice->attachs);
                @endphp
                    @foreach($attachs as  $key => $item)
                        <div class="row">
                            <div class="col-md-2 text-light"></div>
                            <div class="col-md-10">
                               <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name : 'null' ))}}" 
                                      title=" {{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)}}"  target="_blank">
                                    {!! HP::FileExtension($item->attachs)  ?? '' !!}
                                    {!!  !empty($item->attachs_client_name) ? $item->attachs_client_name :  @basename($item->attachs) ?? '' !!}
                                </a>
                            </div>
                            
                        </div>
                    @endforeach
                </p>  
           @endif
        </div>
     </div>
</div>

@if(isset($notice)  && !in_array($notice->degree,[1,4,8]))
<div class="clearfix"></div>
   <a  href="{{ url("certify/save_assessment") }}"  class="btn btn-default btn-lg btn-block">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
@else
<div class="form-group">
    <div class="col-md-offset-4 col-md-6 m-t-15">
         <input type="hidden" id="submit_type" name="submit_type">
        <div id="degree_btn"></div>
{{-- 
        <button class="btn btn-success " type="button"  id="confirm" onclick="submit_form('1','confirm');return false;" style="visibility: hidden">
            <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
        </button>  --}}

        <button class="btn btn-success" type="button" id="confirm" onclick="submit_form('1','confirm');return false;" style="display: none">
            <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
        </button>

        <button class="btn btn-primary " type="button" id="save"  onclick="submit_form('1','save');return false;">
            <i class="fa fa-paper-plane"></i><span id="save_text" style="padding-left:5px">บันทึก</span> 
        </button>
      
        <a class="btn btn-default" href="{{url('/certify/save_assessment')}}"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
    </div>
</div> 
@endif
@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
       <!-- input calendar thai -->
       <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
       <!-- thai extension -->
       <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
       <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
       <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
       <script>
          var id = @json($board_auditor_id);
          var notice_id = $('#notice_id').val();


            if (notice_id) {
                document.getElementById('confirm').style.display = '';
            }

           
            // $('#confirm').css('display', 'block');
         function  submit_form(degree,submit_type){ 
           

            // console.log("id ",id );
            // วิธีที่ 1: ตรวจสอบ undefined หรือ empty string
            // if (typeof notice_id === 'undefined' || notice_id === '') {
            //     console.log('no report1');
            // }

            // หรือ วิธีที่ 2: ใช้การตรวจสอบที่สั้นกว่า

            // return;
                $('#submit_type').val(submit_type);


                var report_status = $("input[name=report_status]:checked").val(); 
       
                var main_state =  $("input[name=main_state]:checked").val();

                // console.log(submit_type,report_status);
                // return;

                    if(report_status == 2){
                        //ไม่มีข้อบกพร่อง
                        let i = 4;
                        Swal.fire({
                                title:"ยืนยันทำรายการ !",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'บันทึก',
                                cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.value) {
                                        $('#degree_btn').html('<input type="text" name="degree" value="'+i+'" hidden>');
                                       $('#form_assessment').submit();
                                    }
                            })
              
                    }else{
                
                        if(degree == 0){  
                            //มีข้อบกพร่อง ฉบับร่าง
                            Swal.fire({
                                title:'ยืนยันทำฉบับร่างรายงานข้อบกพร่อง !',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'บันทึก',
                                cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.value) {
                                        $('#degree_btn').html('<input type="text" name="degree" value="' + degree + '" hidden>');
                                       $('#form_assessment').submit();
                                    }
                            })
                        }else{
                            let title = '';
                            let l = '';
                            if(main_state == 2){
                                title =  'ยืนยันปิดผลการตรวจประเมิน !';
                                l = 8;
                            }else{
                                //มีข้อบกพร่อง บันทึก
                                title = 'ยืนยันทำรายงานข้อบกพร่อง<span style="color: #f39c12;">ฉบับร่าง</span> และ<br><span style="color: #f39c12;">อนุญาตให้ผู้ประกอบการยืนยันรายการข้อบกพร่อง</span>'
                                if(submit_type == 'confirm'){
                                    title = 'ยืนยันทำรายงานข้อบกพร่องและ<br><span style="color: #f39c12;">อนุญาตผู้ประกอบการส่งรายงานแนวทางแก้ไข</span>'
                                }
                                l = 1;
                            }
                            const _token = $('input[name="_token"]').val();
                            
                            
                            Swal.fire({
                                title: title,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'บันทึก',
                                cancelButtonText: 'ยกเลิก',
                                customClass: {
                                    popup: 'custom-swal-popup',  // ใส่คลาส CSS เพื่อจัดการความกว้าง
                                }
                            }).then((result) => {
                                if (result.value) {
                                    if(submit_type == 'confirm'){
                                        $.ajax({
                                            url: "{{route('save_assessment.check_complete_report_one_sign')}}",
                                            method: "POST",
                                            data: {
                                                _token: _token,
                                                notice_id:notice_id
                                            },
                                            success: function(result) {
                                                console.log(result);
                                                if (result.message == true) {
                                                    $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                                    $('#form_assessment').submit();
                                                }else{
                                                    
                                                    if (result.record_count == 0) {
                                                        alert('ยังไม่ได้สร้างรายงานการตรวจประเมิน(รายงานที่1)');
                                                       
                                                        if (!notice_id) {
                                                            window.location.href = window.location.origin + '/certify/save_assessment/create/' + id;
                                                        }else{
                                                            // $notice->applicant->
                                                            window.location.href = window.location.origin + '/certify/check_certificate/'+$('#app_id').val()+'/show';
                                                        }
                                                    }else{
                                                        alert('อยู่ระหว่างการลงนามรายงานการตรวจประเมิน(รายงานที่1)');
                                                    }
                                                }
                                                // $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                                // $('#form_assessment').submit();
                                            }
                                        });

                                    }else if(submit_type == 'save'){
                                            $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                            $('#form_assessment').submit();
                                    }

                                }
                            });
                        }   
                    } 
       }

        $(document).ready(function () {

            if (id !== null) {
           
                console.log("ID:", id);
                // console.log($.fn.select2);
                // $('#auditor_id').val(id).trigger('change.select2');

                if ($('#auditor_id option[value="' + id + '"]').length === 0) {
                    $('#auditor_id').append(new Option('Temporary Label', id, true, true));
                }
                $('#auditor_id').val(id).trigger('change');
                loadBoardAuditor(id);
                // console.log($('#auditor_id').val());



            }

            function loadBoardAuditor(id)
            {
                if(id!=""){
                    $.ajax({
                        url:'{{ route('save_assessment.api.get.app') }}/' + id
                    }).done(function( object ) {
                        if (object.message === true) {
                            console.log(object.app.check)
                            const app = object.app;
                            var appDepart = app ? app.name : '';
                            var appLabName = app ? app.lab_name : '';
                            var appid = app.check.id ;
                            $('#appDepart').val(appDepart);
                            $('#appLabName').val(appLabName);
                            $('#appLabName').val(appLabName);
                            $('#SaveDate').val(object.created_at);
                            $('#appGroupId').val(object.group_id);
                            $('#app_id').val(appid);

                        }else{
                  
                             $('#appDepart').val('');
                             $('#appLabName').val('');
                             $('#auditor_id').val('').select2();
                             $('#SaveDate').val('');
                             $('#appGroupId').val('');
                             $('#app_id').val('');

                        }


                    });
                }else{
                            $('#appDepart').val('');
                             $('#appLabName').val('');
                             $('#auditor_id').val('').select2();
                             $('#SaveDate').val('');
                             $('#appGroupId').val('');
                }
            }


            function autoExpand(textarea) {
                textarea.style.height = 'auto'; // รีเซ็ตความสูง
                textarea.style.height = textarea.scrollHeight + 'px'; // กำหนดความสูงตามเนื้อหา
            }

            // ฟังก์ชันปรับขนาด textarea ทุกตัวในแถวเดียวกัน
            function syncRowHeight(textarea) {
                let $row = $(textarea).closest('tr'); // หา tr ที่ textarea อยู่
                let maxHeight = 0;

                // วนลูปหา maxHeight ใน textarea ทุกตัวในแถว
                $row.find('.auto-expand').each(function () {
                    this.style.height = 'auto'; // รีเซ็ตความสูงก่อนคำนวณ
                    let currentHeight = this.scrollHeight;
                    if (currentHeight > maxHeight) {
                        maxHeight = currentHeight;
                    }
                });

                // กำหนดความสูงให้ textarea ทุกตัวในแถวเท่ากัน
                $row.find('.auto-expand').each(function () {
                    this.style.height = maxHeight + 'px';
                });
            }

            // ดักจับ event input
            $(document).on('input', '.auto-expand', function () {
                // console.log('aha');
                autoExpand(this); // ปรับ textarea ที่มีการเปลี่ยนแปลง
                syncRowHeight(this); // ปรับ textarea ทั้งแถว
            });

            // ปรับขนาดทุก textarea เมื่อโหลดหน้าเว็บ
            $('.auto-expand').each(function () {
                autoExpand(this);
                syncRowHeight(this);
            });

            
            $('#form_assessment').parsley().on('field:validated', function() {
             var ok = $('.parsley-error').length === 0;
                       $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
             }) .on('form:submit', function() {
            // Text
               $.LoadingOverlay("show", {
                            image       : "",
                            text  : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                        return true; // Don't submit form for this demo
              });
            //เพิ่มไฟล์แนบ
                check_max_size_file();
            $('#attach_add_scope').click(function(event) {
                $('.other_attach_scope:first').clone().appendTo('#other_attach-box');
                $('.other_attach_scope:last').find('input').val('');
                $('.other_attach_scope:last').find('a.fileinput-exists').click();
                $('.other_attach_scope:last').find('a.view-attach').remove();
                $('.other_attach_scope:last').find('.attach_remove').remove();
                $('.other_attach_scope:last').find('.button_remove_scope').html('<button class="btn btn-danger btn-sm attach_remove_scope" type="button"> <i class="icon-close"></i>  </button>');
                check_max_size_file();
            });
    
            //ลบไฟล์แนบ
            $('body').on('click', '.attach_remove_scope', function(event) {
                $(this).parent().parent().parent().remove();
            });
    
 
            $('#show-modal-email-to-expert').on('click', function() {
                $('#modal-email-to-expert').modal('show');
            });

        });



    
    </script>
       <script>
        $(document).ready(function () {

        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
            $('.other_attach_item:first').clone().appendTo('#other_attach');
            $('.other_attach_item:last').find('input').val('');
            $('.other_attach_item:last').find('a.fileinput-exists').click();
            $('.other_attach_item:last').find('a.view-attach').remove();
            $('.other_attach_item:last').find('.label_other_attach').remove();
            $('.other_attach_item:last').find('button.attach-add').remove();
            $('.other_attach_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i>  </button>');
            check_max_size_file();
        });

        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
            $(this).parent().parent().parent().remove();
        });



            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });
            $('#auditor_id').change(function(){

                if($(this).val()!=""){
                    $.ajax({
                        url:'{{ route('save_assessment.api.get.app') }}/' + $(this).val()
                    }).done(function( object ) {
                        if (object.message === true) {
                            const app = object.app;
                            var appDepart = app ? app.name : '';
                            var appLabName = app ? app.lab_name : '';
                            $('#appDepart').val(appDepart);
                            $('#appLabName').val(appLabName);

                            $('#SaveDate').val(object.created_at);
                            $('#appGroupId').val(object.group_id);

                        }else{
                  
                             $('#appDepart').val('');
                             $('#appLabName').val('');
                             $('#auditor_id').val('').select2();
                             $('#SaveDate').val('');
                             $('#appGroupId').val('');

                        }


                    });
                }else{
                            $('#appDepart').val('');
                             $('#appLabName').val('');
                             $('#auditor_id').val('').select2();
                             $('#SaveDate').val('');
                             $('#appGroupId').val('');
                }

            });

        status_report_status();
        //  รายงานข้อบกพร่อง
        $("input[name=report_status]").on("ifChanged",function(){
            // console.log('dd');
            status_report_status();
        });
                
        function status_report_status(){
            var row = $("input[name=report_status]:checked").val(); 
            console.log(row);
                if(row == "1"){
                    $('.status_report_status').show(200); 
                    $('#box-required').find('.input_required').prop('required', true);
                    $('#div_file_scope').hide(400); 
                    $('.file_scope_required').prop('required', false);
                    $('#confirm').css('visibility', 'visible');
                    
                    if (notice_id) {
                        $('#save_text').html('ฉบับร่าง');
                    }else{
                        $('#save_text').html('บันทึก');
                    }
                    
                } else{
                    $('.status_report_status').hide(400);
                    $('#box-required').find('.input_required').prop('required', false);
                    $('#div_file_scope').show(200); 
                    $('.file_scope_required').prop('required', true);
                    $('#confirm').css('visibility', 'hidden');
                    $('#save_text').html('บันทึก');
                }
            }


        $('#button_email_to_expert').on('click', function () 
        {
                let selectedEmails = [];
                
                $('.expert-email-checkbox:checked').each(function () {
                    let email = $(this).data('email');
                    if (email) {
                        selectedEmails.push(email);
                    }
                });
                
                if (selectedEmails.length == 0) {
                    alert('กรุณาเลือกอีเมลอย่างน้อยหนึ่งรายการ');
                    return;
                }

                // รับค่าจากฟอร์ม
                const _token = $('input[name="_token"]').val();

                var notice_id = $('#notice_id').val();

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });
                // เรียก AJAX
                $.ajax({
                    url: "{{route('save_assessment.email_to_expert')}}",
                    method: "POST",
                    data: {
                        _token: _token,
                        notice_id:notice_id,
                        selectedEmails: selectedEmails
                    },
                    success: function(result) {
                        console.log(result);
                        // location.reload(); // รีโหลดหน้าเว็บหลังจากสำเร็จ
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
                    },
                    complete: function() {
                        // ลบ overlay เมื่อคำขอเสร็จสิ้น
                        $('#modal-email-to-expert').modal('hide');
                        $.LoadingOverlay("hide");
                    }
                });

            });

         });

    </script>
    <script>
        jQuery(document).ready(function() {
            check_max_size_file();
            //เพิ่มแถว
            $('#plus-row').click(function(event) {
              //Clone
            //   $('#table-body').children('tr:first()').clone().appendTo('#table-body');
            //   //Clear value
            //     var row = $('#table-body').children('tr:last()');

            //     row.find('select.select2').val('');
            //     row.find('select.select2').prev().remove();
            //     row.find('select.select2').removeAttr('style');
            //     row.find('select.select2').select2();
            //     row.find('input[type="text"],textarea').val('');
            //     row.find('.file_attachs').html('');
            //     row.find('input[type="hidden"]').val('');
            //     var html =[];
            //         html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
            //         html += '<div class="form-control" data-trigger="fileinput">';
            //         html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
            //         html += '<span class="fileinput-filename"></span>';
            //         html += '</div>';
            //         html += '<span class="input-group-addon btn btn-default btn-file">';
            //         html += '<span class="fileinput-new">เลือกไฟล์</span>';
            //         html += '<span class="fileinput-exists">เปลี่ยน</span>';
            //         html += '<input type="file" name="attachs[]" class="check_max_size_file">';
            //         html += '</span>';
            //         html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
            //         html += '</div>';
            //     row.find(".file_attachs").append(html);

            // Clone tr แถวแรก
            $('#table-body').children('tr:first()').clone().appendTo('#table-body');

            // เลือกแถวที่เพิ่ง clone
            var row = $('#table-body').children('tr:last()');

            // รีเซ็ตค่า input, select, textarea
            row.find('select.select2').val('');
            row.find('select.select2').prev().remove();
            row.find('select.select2').removeAttr('style');
            row.find('select.select2').select2();
            row.find('input[type="text"], textarea').val('');
            row.find('textarea').css('height', 'auto').attr('rows', 5); // กำหนด textarea เป็น 5 rows
            row.find('.file_attachs').html('');
            row.find('input[type="hidden"]').val('');

            // เพิ่ม file attach ใหม่
            var html = [];
            html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput">';
            html += '<div class="form-control" data-trigger="fileinput">';
            html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
            html += '<span class="fileinput-filename"></span>';
            html += '</div>';
            html += '<span class="input-group-addon btn btn-default btn-file">';
            html += '<span class="fileinput-new">เลือกไฟล์</span>';
            html += '<span class="fileinput-exists">เปลี่ยน</span>';
            html += '<input type="file" name="attachs[]" class="check_max_size_file">';
            html += '</span>';
            html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
            html += '</div>';
            row.find(".file_attachs").append(html);
              //เลขรัน
              ResetTableNumber();
              check_max_size_file();
            //   ChangeSelectTypeFile();
            });
            //ลบแถว
            $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
            });
            ResetTableNumber();
            // ChangeSelectTypeFile();
        });
        //รีเซตเลขลำดับ
        function ResetTableNumber(){
          var rows = $('#table-body').children(); //แถวทั้งหมด
          (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
          rows.each(function(index, el) {
            //เลขรัน
            $(el).children().first().html(index+1);
            //เลข index
            $(el).children().find('input[type="file"]').prop('name', 'attachs['+index+']');
          });
        }

        function ChangeSelectTypeFile(){
             $('select.type').change(function(){
                var file =   $(this).parent().parent().find(".file_attachs");
                    file.html('');
                if($(this).val() != ''){
                    var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="attachs[]" class="check_max_size_file">';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
                    file.append(html);
                    check_max_size_file();
                }else{
                    file.html('');
                }
                ResetTableNumber();
            });
        }

</script>
@endpush

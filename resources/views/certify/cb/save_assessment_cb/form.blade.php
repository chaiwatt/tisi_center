@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <style>
        textarea.auto-expand {
            border-radius: 0 !important;
            border-top: none !important;
            border-bottom: none !important;
            resize: none; 
            overflow: hidden; 
            min-height: 50px; 
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

            @foreach ($certiCBAuditorsLists as $certiCBAuditorsList)
                    @if ($certiCBAuditorsList->auditorInformation->email != null)
                        @php
                            $hasEmail = true; // ตั้งค่าเริ่มต้นว่าไม่มีอีเมล
                        @endphp
                    @endif

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

                            @foreach ($certiCBAuditorsLists as $certiCBAuditorsList)
                                <tr>
                                    @if ($certiCBAuditorsList->auditorInformation->email != null)
                                        <td>
                                            <input type="checkbox" class="expert-email-checkbox" 
                                                checked 
                                                data-email="{{$certiCBAuditorsList->auditorInformation->email}}">
                                        </td>
                                        <td>
                                            {{$certiCBAuditorsList->auditorInformation->title_th}}{{$certiCBAuditorsList->auditorInformation->fname_th}} {{$certiCBAuditorsList->auditorInformation->lname_th}}
                                        </td>
                                        <td>
                                            {{$certiCBAuditorsList->auditorInformation->email}}
                                        </td>
                                        <td></td>
                                        @else
                                        <td></td>
                                        <td>
                                            {{$certiCBAuditorsList->auditorInformation->title_th}}{{$certiCBAuditorsList->auditorInformation->fname_th}} {{$certiCBAuditorsList->auditorInformation->lname_th}}
                                        </td>
                                        <td>
                                            <span class="text-danger">ไม่พบอีเมล</span>
                                        </td>
                                    @endif
                              
                                </tr>
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
                   @if ($assessment !== null)
                   <input type="hidden" id="assessmentId" value="{{$assessment->id}}">
                   <div class="col-md-12 mt-2">
                        <hr>
                    <span style="font-weight:600">หมายเหตุ:</span> ท่านสามารถใช้ลิงก์เพื่อจัดส่งให้ผู้เชี่ยวชาญในช่องทางอื่น
                    <p>
                        @php
                            $config = HP::getConfig();
                            $url = $config->url_center.'create-by-cb-expert/' . $assessment->id .'?token='.$assessment->expert_token;
                        @endphp
                        {{-- {{$assessment}} --}}
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
            {{-- <div class="col-md-6" >
                <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
                <div class="col-md-8">
                    @if(isset($app_no))
                    {!! Form::select('auditors_id', 
                        $app_no, 
                        null,
                        ['class' => 'form-control',
                        'id' => 'auditors_id',
                        'placeholder'=>'- เลขคำขอ -', 
                        'required' => true]); !!}
                       {!! $errors->first('auditors_id', '<p class="help-block">:message</p>') !!}
                       <input type="hidden" class="form-control" value="{{ $assessment->app_certi_cb_id ?? null  }}" name="app_certi_cb_id"  id="app_certi_cb_id">   
                    @else 
                        <input type="text" class="form-control"    value="{{ $assessment->AuditorsTitle ?? null }}"   disabled >  
                    @endif
                
                </div>
            </div> --}}
            <input type="hidden" class="form-control" value="{{ $assessment->app_certi_cb_id ?? null  }}" name="app_certi_cb_id"  id="app_certi_cb_id">   
            <input type="hidden" name="auditors_id" value="{{$auditorId}}">
            <div class="col-md-6">
                <label class="col-md-4 text-right">เลขคำขอ : </label>
                <div class="col-md-8">
                    <input type="text" name="app_no" class="form-control" id="app_no" readonly>
                </div>
            </div>
            
            <div class="col-md-6">
                <label class="col-md-4 text-right">ชื่อผู้ยื่นคำขอ : </label>
                <div class="col-md-8">
                    {!! Form::text('name', null,  ['class' => 'form-control', 'id'=>'applicant_name','readonly'=>true])!!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label class="col-md-4 text-right">ชื่อหน่วยรับรอง/หน่วยตรวจสอบ : </label>
                <div class="col-md-8">
                    {!! Form::text('laboratory_name', null,  ['class' => 'form-control', 'id'=>'laboratory_name','readonly'=>true])!!}
                </div>
            </div>
            {{-- <div class="col-md-6">
                <label class="col-md-4 text-right"><span class="text-danger">*</span> วันที่ทำรายงาน : </label>
                <div class="col-md-8">
                     <div class="input-group">     
                        {!! Form::text('report_date', 
                        !empty($assessment->report_date) ? HP::revertDate($assessment->report_date,true) :  null,  
                        ['class' => 'form-control mydatepicker', 'id'=>'SaveDate',
                        'required'=>true,'placeholder'=>'dd/mm/yyyy'])!!}
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                </div>
            </div> --}}
            {{-- {{$assessment}} --}}
            {{-- @php
                dd($assessment)
            @endphp --}}
            @if ($assessment != null)
                <input type="hidden" id="assessment_id" value="{{$assessment->id}}">
                <div class="col-md-6">
                    <label class="col-md-4 text-right">แจ้งผู้เชี่ยวชาญ :</label>
                    
                    <div class="col-md-8">
                        <button type="button" class="btn btn-info" id="show-modal-email-to-expert"><i class="fa fa-envelope"></i> อีเมล</button>


                    </div>

                    
                </div>
            @endif

        </div>
        <div class="form-group">
            {{-- <div class="col-md-6">
                <label class="col-md-5 text-right"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                <div class="col-md-7">
                    <div class="row">
                        <label class="col-md-6">
                            {!! Form::radio('bug_report', '1', true , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
                        </label>
                        <label class="col-md-6">
                            {!! Form::radio('bug_report', '2', false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
                        </label>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-6">
                <label class="col-md-5 text-right"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                <div class="col-md-7">
                    <div class="row">
                        <label class="col-md-6">
                            <input type="radio" name="bug_report" value="1" class="check check-readonly" data-radio="iradio_square-green" required="required" checked> มี
                        </label>
                        <label class="col-md-6">
                            <input type="radio" name="bug_report" value="2" class="check check-readonly" data-radio="iradio_square-red" required="required"> ไม่มี
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="col-md-4 text-right"><span class="text-danger">*</span>รายงานการตรวจประเมิน : </label>
                <div class="col-md-8">

                    @if ($assessment !== null)
                        @if ($assessment->cbReportInfo->status === "1")
                                <a href="{{route('save_assessment.cb_report_create',['id' => $assessment->id])}}"
                                    title="จัดทำรายงาน" class="btn btn-warning">
                                    รายงานที่1
                                </a>
                            @else
                                <a href="{{route('save_assessment.cb_report_create',['id' => $assessment->id])}}"
                                    title="จัดทำรายงาน" class="btn btn-info">
                                    รายงานที่1
                                </a>
                        @endif 
                    {{-- @else      --}}
                        {{-- <a href="{{route('save_assessment.view_cb_info',['id' => $assessment->id])}}"
                            title="จัดทำรายงาน" class="btn btn-warning">
                            รายงานที่1
                        </a> --}}
                        {{-- <a href="{{route('save_assessment.cb_report_create',['id' => $assessment->id ])}}" class="btn btn-warning">
                            รายงานที่1
                        </a> --}}
                    @endif

                    @if(isset($assessment)  && !is_null($assessment->FileAttachAssessment1To)) 
                          {{-- <p id="RemoveFlie"> --}}
                            {{-- @if($assessment->FileAttachAssessment1To->file !='' && HP::checkFileStorage($attach_path. $assessment->FileAttachAssessment1To->file)) --}}
                                  <a href="{{url('certify/check/file_cb_client/'.$assessment->FileAttachAssessment1To->file.'/'.( !empty($assessment->FileAttachAssessment1To->file_client_name) ? $assessment->FileAttachAssessment1To->file_client_name : 'null' ))}}" 
                                    title="{{ !empty($assessment->FileAttachAssessment1To->file_client_name) ? $assessment->FileAttachAssessment1To->file_client_name :  basename($assessment->FileAttachAssessment1To->file) }}" target="_blank">
                                    {!! HP::FileExtension($assessment->FileAttachAssessment1To->file)  ?? '' !!}
                                </a>
                            {{-- @endif --}}
                            {{-- <button class="btn btn-danger btn-xs div_hide" type="button"
                             onclick="RemoveFlie({{$assessment->FileAttachAssessment1To->id}})">
                               <i class="icon-close"></i>
                           </button>    --}}
                        {{-- </p> --}}
                        <div id="AddFile"></div>    
                        
                        


                    @else 
                        {{-- <div class="fileinput fileinput-new input-group" data-provides="fileinput" >
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
        </div>
    </div>
</div>      
 
 <div class="row form-group" id="div_file_scope">
     <div class="col-md-12">
         <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>   
            <div class="row">
                {{-- <div class="col-md-12 ">
                    <div id="other_attach-box">
                        <div class="form-group other_attach_scope">
                            <div class="col-md-4 text-right">
                                <label class="attach_remove"><!--<span class="text-danger">*</span> -->Scope  </label>
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
                                        <input type="file"  name="file_scope[]" class="check_max_size_file  ">  <!-- file_scope_required -->   
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-2 text-left">
                                <button type="button" class="btn btn-sm btn-success attach_remove" id="attach_add_scope">
                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                </button>
                                <div class="button_remove_scope"></div>
                            </div> 
                         </div>
                       </div>
                 </div> --}}
                 
                 @php
                     $certiCb = $auditor->CertiCbCostTo;

                 @endphp

                 @if (isset($certiCb) && $certiCb->FileAttach3->count() > 0)
                 <div class="row">
                     @foreach($certiCb->FileAttach3 as $data)
                       @if ($data->file)
                         <div class="col-md-12">
                             <div class="form-group">
                                 <div class="col-md-4 text-light"> </div>
                                 <div class="col-md-6 text-light">
                                     <a href="{{url('certify/check/file_cb_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file) ))}}" target="_blank">
                                         {!! HP::FileExtension($data->file)  ?? '' !!}
                                         {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                     </a> 
                                 </div>
                                 {{-- <div class="col-md-2 text-left">
                                     <a href="{{url('certify/certi_cb/delete').'/'.basename($data->id).'/'.$data->token}}" class="hide_attach btn btn-danger btn-xs" 
                                          onclick="return confirm('ต้องการลบไฟล์นี้ใช่หรือไม่ ?')" >
                                         <i class="fa fa-remove"></i>
                                     </a>
                                 </div>  --}}
                             </div>
                         </div>
                         @endif
                      @endforeach
                   </div>
                 @endif
            </div>
            {{-- <div class="row">
                <div class="col-md-12 ">
                    <div id="other_attach_report">
                        <div class="form-group other_attach_report">
                            <div class="col-md-4 text-right">
                                <label class="attach_remove">สรุปรายงานการตรวจทุกครั้ง </label>
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
                                        <input type="file"  name="file_report[]" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-2 text-left">
                                <button type="button" class="btn btn-sm btn-success attach_remove" id="attach_add_report">
                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                </button>
                                <div class="button_remove_report"></div>
                            </div> 
                         </div>
                       </div>
                 </div>
            </div> --}}
        </div>
    </div>
</div>         
       
<div class="clearfix"></div>
<div class="row status_bug_report">

    {{-- <div class="row"> --}}
        <div class="col-md-12 text-right">
                <button type="button" class="   btn btn-success btn-sm div_hide" id="plus-row"><i class="icon-plus"></i> เพิ่ม</button>
        </div>
    {{-- </div> --}}
   
    <div class="col-sm-12 m-t-15 "  id="box-required">
        <table class="table color-bordered-table primary-bordered-table">
            <thead>
            <tr>
                <th class="text-center" width="1%">ลำดับ</th>
                <th class="text-center" width="13%">รายงานที่</th>
                <th class="text-center" width="20%">ข้อบกพร่อง/ข้อสังเกต</th>
                <th class="text-center" width="12%">
                    มอก. <span id="Tis">
                        {{  !empty($assessment->CertiCBCostTo->FormulaTo->title) ?   str_replace("มอก.","",$assessment->CertiCBCostTo->FormulaTo->title) :''  }}
                    </span>
                </th>
                <th class="text-center" width="6%">ประเภท</th>
                {{-- <th class="text-center" width="10%">ผู้พบ</th> --}}
                <th class="text-center  div_hide " width="3%"> <i class="fa fa-pencil-square-o"></i></th>
            </tr>
            </thead>
            <tbody id="table-body">
             @foreach($bug as $key => $item)

                {{-- <tr>
                    <td class="text-center">
                        1
                    </td>
                    <td>
                        {!! Form::hidden('detail[id][]',!empty($item->id)?$item->id:null, ['class' => 'form-control '])  !!}
                        {!! Form::text('detail[report][]', $item->report ?? null,  ['class' => 'form-control input_required','required'=>true])!!}
                    </td>
                    <td>
                        {!! Form::text('detail[notice][]', $item->remark ?? null,  ['class' => 'form-control input_required','required'=>true])!!}
                    </td>
                    <td>
                        {!! Form::text('detail[no][]',  $item->no ?? null,  ['class' => 'form-control input_required','required'=>true])!!}
                    </td>
                    <td>
                        {!! Form::select('detail[type][]',
                          ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต'],
                            $item->type ?? null,
                            ['class' => 'form-control type input_required  select2',
                            'required'=>true,
                            'placeholder'=>'-เลือกประเภท-'])
                        !!}
                    </td>
                    <td class="text-center   div_hide">
                        <button type="button" class="btn btn-danger btn-sm remove-row" ><i class="fa fa-trash"></i></button>
                    </td>
                </tr> --}}
                <tr>
                    <td class="text-center">
                        1
                    </td>
                    <td style="padding: 0px;">
                        <input type="hidden" name="detail[id][]" class="form-control" value="{{ !empty($item->id) ? $item->id : '' }}">
                        <textarea name="detail[report][]" class="form-control input_required auto-expand" rows="5" style="border-right: 1px solid #ccc;"  required>{{ $item->report ?? '' }}</textarea>
                    </td>
                    <td style="padding: 0px;">
                        <textarea name="detail[notice][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->remark ?? '' }}</textarea>
                    </td>
                    <td style="padding: 0px;">
                        <textarea name="detail[no][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->no ?? '' }}</textarea>
                    </td>
                    <td style="padding-left: 15px;vertical-align:top">
                        <select name="detail[type][]" class="form-control type input_required select2" required>
                            <option value="" disabled selected>-เลือกประเภท-</option>
                            <option value="1" {{ ($item->type ?? '') == '1' ? 'selected' : '' }}>ข้อบกพร่อง</option>
                            <option value="2" {{ ($item->type ?? '') == '2' ? 'selected' : '' }}>ข้อสังเกต</option>
                        </select>
                    </td>
                    <td class="text-center div_hide">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                
                
                @endforeach 
            </tbody>
        </table>
    </div>
</div>
<div class="row status_bug_report">
    <div class="col-md-12   ">
        <div id="other_attach">
            <div class="form-group other_attach_item">
                <div class="col-md-2 text-right">
                    <label for="#" class="label_other_attach text-right ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ไฟล์แนบ : </label>

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
                            {!! Form::file('attachs[]', null) !!}
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="col-md-2 text-left">
                    <button type="button" class="btn btn-sm btn-success attach-add div_hide" id="attach-add">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                    </button>
                    <div class="button_remove"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 ">
        <div class="col-md-2 text-right"></div>
        <div class="col-md-6">
        @if(!is_null($assessment) && (count($assessment->FileAttachAssessment4Many) > 0 ) )
            @foreach($assessment->FileAttachAssessment4Many as  $key => $item)
              <p id="remove_attach_all{{$item->id}}">
                @if( $item->file  !='' && HP::checkFileStorage($attach_path. $item->file ))
                    <a href="{{url('certify/check/file_cb_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                            title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                        {!! HP::FileExtension($item->file)  ?? '' !!}
                    </a>
                @endif
                <button class="btn btn-danger btn-xs deleteFlie div_hide"
                     type="button" onclick="deleteFlieAttachAll({{$item->id}})">
                     <i class="icon-close"></i>
                </button>   
            </p>
            @endforeach
        @endif
     </div>
   </div>
 </div>
 <br>
 <div class="clearfix"></div>


 {{-- @if($assessment->degree != 1 && $assessment->degree !=4  && $assessment->degree !=8) --}}
 {{-- @if(isset($assessment)  && !in_array($assessment->degree,[1,4,8])) --}}
    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
            <input type="hidden" name="previousUrl" id="previousUrl" value="{{ $previousUrl ?? null}}">
            {{-- <div  class="status_bug_report"> 
                <label>{!! Form::checkbox('main_state', '2', false, ['class'=>'check','data-checkbox'=>"icheckbox_flat-red"]) !!} 
                    &nbsp;ปิดผลการตรวจประเมิน&nbsp;
                </label>
            </div>  --}}
    
            <div id="degree_btn"></div>
                <input type="hidden" id="submit_type" name="submit_type">
                <div id="degree_btn"></div>
                {{-- <button class="btn btn-success " type="button"  id="confirm" onclick="submit_form('1','confirm');return false;" style="visibility: hidden">
                    <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
                </button> --}}
                <button class="btn btn-success " type="button"  id="confirm" onclick="submit_form('1','confirm');return false;" style="display: none">
                    <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
                </button>
                <button class="btn btn-primary " type="button" id="save"  onclick="submit_form('1','save');return false;">
                    <i class="fa fa-paper-plane"></i><span id="save_text" style="padding-left:5px">บันทึก</span> 
                </button>
            
                <a class="btn btn-default" href="{{url('/certify/save_assessment')}}"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
        </div>
    </div>
 {{-- @else 
    <div class="clearfix"></div>
        <a  href="{{  url("$previousUrl")  }}"  class="btn btn-default btn-lg btn-block">
        <i class="fa fa-rotate-left"></i>
        <b>กลับ</b>
    </a>
 
 @endif --}}
 

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script>
        var auditorId = @json($auditorId);
        var assessment_id = $('#assessment_id').val();

        if (assessment_id) {
                document.getElementById('confirm').style.display = '';
            }

        $(document).ready(function () {
             check_max_size_file();
            //เพิ่มไฟล์แนบ
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

            //เพิ่มไฟล์แนบ
            $('#attach_add_report').click(function(event) {
                $('.other_attach_report:first').clone().appendTo('#other_attach_report');
                $('.other_attach_report:last').find('input').val('');
                $('.other_attach_report:last').find('a.fileinput-exists').click();
                $('.other_attach_report:last').find('a.view-attach').remove();
                $('.other_attach_report:last').find('.attach_remove').remove();
                $('.other_attach_report:last').find('.button_remove_report').html('<button class="btn btn-danger btn-sm attach_remove_report" type="button"> <i class="icon-close"></i>  </button>');
                check_max_size_file();
            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach_remove_report', function(event) {
                $(this).parent().parent().parent().remove();
            });
        });
 
// console.log(auditorId);
    function  submit_form(degree,submit_type){ 
        $('#submit_type').val(submit_type);
        var bug_report = $("input[name=bug_report]:checked").val(); 
        var vehicle =  $("input[name=vehicle]:checked").val();
        var main_state =  $("input[name=main_state]:checked").val();

        
        if(bug_report == 2)
        {
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
        
        }
        else
        {
    
            if(degree == 0){  // ฉบับร่าง
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
                // console.log(submit_type)
                let title = '';
                let l = '';
                if(main_state == 2){
                    title =  'ยืนยันปิดผลการตรวจประเมิน !';
                    l = 8;
                }else{
            
                    title = 'ยืนยันทำรายงานข้อบกพร่อง<span style="color: #f39c12;">ฉบับร่าง</span> และ<br><span style="color: #f39c12;">อนุญาตให้ผู้ประกอบการยืนยันรายการข้อบกพร่อง</span>'
                    if(submit_type == 'confirm'){
                        title = 'ยืนยันทำรายงานข้อบกพร่องและ<br><span style="color: #f39c12;">อนุญาตผู้ประกอบการส่งรายงานแนวทางแก้ไข</span>'
                    }
                    l = 1;
                }
            
                // Swal.fire({
                //     title:title,
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'บันทึก',
                //     cancelButtonText: 'ยกเลิก',
                //     customClass: {
                //         popup: 'custom-swal-popup',  // ใส่คลาส CSS เพื่อจัดการความกว้าง
                //     }
                //     }).then((result) => {
                //         if (result.value) {
                //             $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                //             $('#form_assessment').submit();
                //         }
                // })
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
                                            url: "{{route('save_assessment.check_complete_cb_report_one_sign')}}",
                                            method: "POST",
                                            data: {
                                                _token: _token,
                                                assessment_id:assessment_id
                                            },
                                            success: function(result) {
                                                console.log(result);
                                                if (result.message == true) {
                                                    $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                                    $('#form_assessment').submit();
                                                }else{
                                                    
                                                    if (result.record_count == 0) {
                                                        alert('ยังไม่ได้สร้างรายงานการตรวจประเมิน(รายงานที่1)');
                                                       
                                                        if (!assessment_id) {
                                                            window.location.href = window.location.origin + '/certify/save_assessment-cb/create/' + id;
                                                        }else{
                                                            window.location.href = window.location.origin + '/certify/save_assessment-cb/view-cb-info/' + assessment_id;
                                                        }
                                                    }else{
                                                        alert('อยู่ระหว่างการลงนามรายงานการตรวจประเมิน(รายงานที่1)');
                                                    }
                                                }
                                            }
                                        });

                                    }else if(submit_type == 'save'){
                                        // console.log(submit_type)
                                            $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                            $('#form_assessment').submit();
                                    }

                                }
                            });
            }   
    
        } 
    }


    function loadCertiCb()
        {
            $.ajax({
                    url:'{{ url('certify/save_assessment-cb/certi_cb') }}/' + auditorId
                }).done(function( object ) {
                    
                        if(object.certi_cb != '-'){ 
                            let certi_cb = object.certi_cb;
                            $('#app_no').val(certi_cb.app_no); 
                            $('#applicant_name').val(certi_cb.name); 
                            $('#laboratory_name').val(certi_cb.name_standard);
                            $('#Tis').html(certi_cb.tis); 
                            $('#app_certi_cb_id').val(certi_cb.id); 
                        }else{
                            $('#app_no').val(''); 
                            $('#applicant_name').val(''); 
                            $('#laboratory_name').val(''); 
                            $('#Tis').html(''); 
                            $('#app_certi_cb_id').val(''); 
                        }

                        // if(object.auditors_list != '-'){ 
                        //     let auditors = object.auditors_list;
                        //     $.each(auditors, function( index, data ) {
                        //          $('select.found').append('<option value="'+data.user_id+'">'+ data.temp_users+'</option>');
                        //     });
                        // }
                });
        }

    jQuery(document).ready(function() {
               $('#form_assessment').parsley().on('field:validated', function() {
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
 

     
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
            orientation: 'bottom'
        });
        

        loadCertiCb();


         
        $('#show-modal-email-to-expert').on('click', function() {
            $('#modal-email-to-expert').modal('show');
        });


        
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


        $("#auditors_id").change(function(){
            
            // $('select.found').html('<option value="">-เลือกผู้พบ-</option>').select2();
            if($(this).val()!=""){
                $.ajax({
                    url:'{{ url('certify/save_assessment-cb/certi_cb') }}/' + $(this).val()
                }).done(function( object ) {
                    
                        if(object.certi_cb != '-'){ 
                            let certi_cb = object.certi_cb;
                            $('#applicant_name').val(certi_cb.name); 
                            $('#laboratory_name').val(certi_cb.name_standard);
                            $('#Tis').html(certi_cb.tis); 
                            $('#app_certi_cb_id').val(certi_cb.app_certi_cb_id); 
                        }else{
                            $('#applicant_name').val(''); 
                            $('#laboratory_name').val(''); 
                            $('#Tis').html(''); 
                            $('#app_certi_cb_id').val(''); 
                        }

                        // if(object.auditors_list != '-'){ 
                        //     let auditors = object.auditors_list;
                        //     $.each(auditors, function( index, data ) {
                        //          $('select.found').append('<option value="'+data.user_id+'">'+ data.temp_users+'</option>');
                        //     });
                        // }
                });
            }else{
                $('#applicant_name').val(''); 
                $('#laboratory_name').val(''); 
                $('#app_certi_cb_id').val(''); 
            }

        });
          //  รายงานข้อบกพร่อง
         $("input[name=bug_report]").on("ifChanged",function(){
            bug_report();
         });
            bug_report();
            function bug_report(){
            var row = $("input[name=bug_report]:checked").val(); 
                if(row == "1"){ 
                    $('.status_bug_report').show(200); 
                    $('#submit_draft').show(200); 
                    $('#box-required').find('.input_required').prop('required', true);
                    $('#div_file_scope').hide(400); 
                    $('#checkbox_document').hide(400); 
                    $('.file_scope_required').prop('required', false);
                    $('#confirm').css('visibility', 'visible');
                    if (assessment_id) {
                        $('#save_text').html('ฉบับร่าง');
                    }else{
                        $('#save_text').html('บันทึก');
                    }
                } else{
                    $('.status_bug_report').hide(400);
                    $('#submit_draft').hide(400); 
                    $('#box-required').find('.input_required').prop('required', false);
                    $('#div_file_scope').show(200);
               
                    $('#checkbox_document').show(200);  
                    $('.file_scope_required').prop('required', true);
                    $('#confirm').css('visibility', 'hidden');
                    $('#save_text').html('บันทึก');
                }
            }

        //เพิ่มแถว
        $('#plus-row').click(function(event) {
          //Clone
          $('#table-body').children('tr:first()').clone().appendTo('#table-body');
          //Clear value
            var row = $('#table-body').children('tr:last()');
            row.find('select.select2').val('');
            row.find('select.select2').prev().remove();
            row.find('select.select2').removeAttr('style');
            row.find('select.select2').select2();
            row.find('input[type="text"],textarea').val('');
            row.find('.file_attachs').html('');
            row.find('.parsley-required').html('');
            row.find('input[type="hidden"]').val('');
          //เลขรัน 
          ResetTableNumber();
   
        });
        //ลบแถว
        $('body').on('click', '.remove-row', function(){
          $(this).parent().parent().remove();
          ResetTableNumber();
        });
        ResetTableNumber();



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

  });
          //รีเซตเลขลำดับ
     function ResetTableNumber(){
      var rows = $('#table-body').children(); //แถวทั้งหมด
      (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
        rows.each(function(index, el) {
        //เลขรัน
        $(el).children().first().html(index+1);
      });
    }

function  RemoveFlie(id){
        var html =[];
                html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                html += '<div class="form-control" data-trigger="fileinput">';
                html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                html += '<span class="fileinput-filename"></span>';
                html += '</div>';
                html += '<span class="input-group-addon btn btn-default btn-file">';
                html += '<span class="fileinput-new">เลือกไฟล์</span>';
                html += '<span class="fileinput-exists">เปลี่ยน</span>';
                html += '  <input type="file" name="file" required >';
                html += '</span>';
                html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                html += '</div>';
    Swal.fire({
            icon: 'error',
            title: 'ยื่นยันการลบไฟล์แนบ !',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                   $.ajax({
                        url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
                    }).done(function( object ) {
                        if(object == 'true'){
                            $('#RemoveFlie').remove();
                            $("#AddFile").append(html);
                            check_max_size_file();
                        }else{
                            Swal.fire('ข้อมูลผิดพลาด');
                        }
                    });

                }
            })
     }

     function  RemoveFlieScope(id){
        var html =[];
                html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                html += '<div class="form-control" data-trigger="fileinput">';
                html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                html += '<span class="fileinput-filename"></span>';
                html += '</div>';
                html += '<span class="input-group-addon btn btn-default btn-file">';
                html += '<span class="fileinput-new">เลือกไฟล์</span>';
                html += '<span class="fileinput-exists">เปลี่ยน</span>';
                html += '<input type="file" name="file_scope"  class="file_scope_required">';
                html += '</span>';
                html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                html += '</div>';
    Swal.fire({
            icon: 'error',
            title: 'ยื่นยันการลบไฟล์แนบ !',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                   $.ajax({
                        url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
                    }).done(function( object ) {
                        if(object == 'true'){
                            $('#RemoveFlieScope').remove();
                            $("#AddFileScope").append(html);
                            check_max_size_file();
                        }else{
                            Swal.fire('ข้อมูลผิดพลาด');
                        }
                    });

                }
            })
     }

     
    function  deleteFlieAttachAll(id){
      Swal.fire({
            icon: 'error',
            title: 'ยื่นยันการลบไฟล์แนบ !',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                   $.ajax({
                        url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
                    }).done(function( object ) {
                        if(object == 'true'){
                            $('#remove_attach_all'+id).remove();
                        }else{
                            Swal.fire('ข้อมูลผิดพลาด');
                        }
                    });

                }
            })
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

                var assessmentId = $('#assessmentId').val();

                // console.log(selectedEmails)
                // return;

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });
                // เรียก AJAX
               
                $.ajax({
                    url: "{{route('save_assessment.email_to_cb_expert')}}",
                    method: "POST",
                    data: {
                        _token: _token,
                        assessmentId:assessmentId,
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
</script>
@endpush


@extends('layouts.master')
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
@section('content')
<div class="modal fade" id="modal-request-edit-scope">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">รายละเอียด</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-left">
                
                <div class="row">
                    <div class="col-md-12 form-group" >
                        <label for="edit_detail">โปรดระบุเหตุผล:</label>
                        <textarea name="edit_detail" id="edit_detail" class="form-control" row="5"></textarea>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <button type="button" class="btn btn-success pull-right " id="button_request_edit_scope">
                            <span aria-hidden="true">บันทึก</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    
                    <input type="hidden" name="" id="app_id" value="{{$app->id}}">
                    <input type="hidden" name="" id="notice_id" value="{{$find_notice->id}}">

                    <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน #{{$find_notice->id}}</h3>
                   
                    @can('view-'.str_slug('auditor'))
                    <a class="btn btn-success pull-right" href="{{ route('save_assessment.index', ['app' => $app ? $app->id : '']) }}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                   @endcan
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
      
                      {!! Form::open(['url' => route('save_assessment.assess_update', ['notice'=> $notice, 'app' => @$notice->applicant->id ? : '']),
                                      'class' => 'form-horizontal',
                                      'method' => 'put',
                                      'id' => 'form_assessment',
                                      'files' => true]) 
                    !!}
                    
   <div id="box-readonly">
                    <div class="row">
                        <input type="hidden" id="assessment_passed" name="assessment_passed" value="0">
                        <div class="col-md-12">
                            <div class="white-box" style="border: 2px solid #e5ebec;">
                          <legend><h4>บันทึกการแก้ไขข้อบกพร่อง / ข้อสังเกต</h4></legend>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right"> เลขคำขอ : </label>
                                    <div class="col-md-8">
                                         {!! Form::text('app_no', $app->app_no ??  null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right">หน่วยงาน : </label>
                                    <div class="col-md-8">
                                        {!! Form::text('name',$app->name ??   null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right"> ชื่อห้องปฏิบัติการ : </label>
                                    <div class="col-md-8">
                                         {!! Form::text('lab_name', $app->lab_name ??  null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right">วันที่ทำรายงาน : </label>
                                    <div class="col-md-8">
                                        {!! Form::text('assessment_date', $app->assessment_date ??   null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                    </div>
                                </div>

                             
                            </div>


                            <div class="form-group" hidden>
                                <div class="col-md-12">
                                    <label class="col-md-3 text-right"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <label class="col-md-2">
                                                {!! Form::radio('report_status', '1', ($find_notice->report_status == 1) ? true :false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
                                            </label>
                                            <label class="col-md-2">
                                                {!! Form::radio('report_status', '2',  ($find_notice->report_status == 2) ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-3 text-right"><span class="text-danger">*</span> รายงานการตรวจประเมิน: </label>
                                    <div class="col-md-6">
                                        @if(!is_null($find_notice) && !is_null($find_notice->file) )
                                            <p>
                                                <a href="{{url('certify/check/file_client/'.$find_notice->file.'/'.( !empty($find_notice->file_client_name) ? $find_notice->file_client_name : 'null' ))}}" 
                                                    title=" {{ !empty($find_notice->file_client_name) ? $find_notice->file_client_name : basename($find_notice->file)}}"   target="_blank">
                                                    {!! HP::FileExtension($find_notice->file)  ?? '' !!}  {{basename($find_notice->file)}}
                                                </a>
                                                {{-- <button id="button_audit_report" type="button" class="btn btn-danger btn-xs {{ ($find_notice->report_status == 2) ? 'hide' : '' }}"><i class="fa fa-trash"></i></button> --}}
                                            </p>
                                        @else 
                                        <span class="text-warning">(ยังไม่ได้สร้าง)</span>
                                        @endif
                                        <div id="audit_report"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {{-- {{$report}} --}}
                                <div class="col-md-12">
                                    <label class="col-md-3 text-right"><span class="text-danger">*</span> ขอบข่ายที่ขอรับการรับรอง: </label>
                                    <div class="col-md-6">
                                        @if(!is_null($find_notice->file_scope))
                                            @php
                                                $file_scope = json_decode($find_notice->file_scope);
                                            @endphp
            
                                            @if(!empty($file_scope) && is_array($file_scope))
                                                @foreach($file_scope  as $key => $item)
                                                    <p id="remove_attach_all{{$key}}">
                                                        <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) ))}}"
                                                            title="{{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) }}" target="_blank">
                                                            {!! HP::FileExtension($item->attachs)  ?? '' !!} {{basename($item->attachs)}}
                                                        </a>
                                                        @if ($report != null)
                                                        
                                                            @if ($report->status == null)
                                                                <a type="button" class="btn btn-sm btn-info attach-add" id="button_show_request_edit_scope_modal">
                                                                    <i class="fa fa-pencil-square-o"></i>&nbsp;ขอให้แก้ไข
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </p>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-3 text-right"><span class="text-danger">*</span> รายงานการตรวจประเมิน (ปิดcar): </label>
                                    <div class="col-md-6">
                                        @if(isset($find_notice)) 

                                            @if (is_null($find_notice->file_car))
                                                    @if ($find_notice->labReportTwoInfo->status === "1")
                                                            <a href="{{route('save_assessment.view_lab_report2_info',['id' => $find_notice->id])}}"
                                                                title="จัดทำรายงาน2" class="btn btn-warning">
                                                                รายงานที่2
                                                            </a>
                                                        @else
                                                            <a href="{{route('save_assessment.view_lab_report2_info',['id' => $find_notice->id])}}"
                                                                title="จัดทำรายงาน2" class="btn btn-info">
                                                                รายงานที่2
                                                            </a>
                                                    @endif 
                                                @else
                                                  @if($find_notice->date_car != null)
                                                        <a href="{{route('save_assessment.view_lab_report2_info',['id' => $find_notice->id])}}"
                                                            title="จัดทำรายงาน2" class="btn btn-info">
                                                            รายงานที่2
                                                        </a>
                                                        <a href="{{url('certify/check/file_client/'.$find_notice->file_car.'/'.( !empty($find_notice->file_car_client_name) ? $find_notice->file_car_client_name : 'null' ))}}"
                                                            title="{{ !empty($find_notice->file_car_client_name) ? $find_notice->file_car_client_name :  basename($find_notice->file_car) }}" target="_blank">
                                                        {!! HP::FileExtension($find_notice->file_car)  ?? '' !!} {{basename($find_notice->file_car)}}
                                                  @else
                                                    @if ($find_notice->labReportTwoInfo->status === "1")
                                                            <a href="{{route('save_assessment.view_lab_report2_info',['id' => $find_notice->id])}}"
                                                                title="จัดทำรายงาน2" class="btn btn-warning">
                                                                รายงานที่2
                                                            </a>
                                                        @else
                                                            <a href="{{route('save_assessment.view_lab_report2_info',['id' => $find_notice->id])}}"
                                                                title="จัดทำรายงาน2" class="btn btn-info">
                                                                รายงานที่2
                                                            </a>
                                                    @endif 
                                                  @endif
                                                 
                                                </a>
                                            @endif


                                        @endif

                                    </div>

                                </div>
                            </div>
                  

                            <div class="form-group {{ ($find_notice->report_status == 2) ? 'hide' : '' }}">
                                <div class="col-md-12">
                                    <label class="col-md-3 text-right"> ไฟล์แนบ: </label>
                                    <div class="col-md-7" id="remove_attachs">
                                        <div id="other_attach">
                                            <div class="form-group other_attach_item ">
                                                <div class="col-md-10">
                                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            
                                                            <input type="file" name="attachs[]"   class="check_max_size_file">
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
                                        @if(!is_null($find_notice) && !is_null($find_notice->attachs) )
                                          @php 
                                              $attachs = json_decode($find_notice->attachs);
                                          @endphp  
                                           @foreach($attachs as  $key => $item)
                                              <p class="attachs{{$key}}">
                                                <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name : 'null' ))}}" 
                                                    title=" {{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)}}"  target="_blank">
                                                    {!! HP::FileExtension($item->attachs)  ?? '' !!}
                                                </a>
                                            <button  onclick="remove_attachs({{$key}})"  type="button" class="btn btn-danger btn-xs remove-row {{ ($find_notice->report_status == 2) ? 'hide' : '' }}"><i class="fa fa-trash"></i></button>
                                              </p>
                                           @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>

{{-- log --}}
 <div class="clearfix"></div>
 <hr>
 
        @include ('certify.save_assessment.log_assessment')
   @if($find_notice->degree == 2 || $find_notice->degree == 8)
   
        @include ('certify.save_assessment.bug')
        
   @endif
   
   @if($find_notice->degree >= 5 || $find_notice->degree == 8)
   
        @include ('certify.save_assessment.log_scope')
   @endif

</div>



@if($find_notice->degree == 5)

<div class="row form-group" id="div_details">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>   
              
           <div class="row">
               <div class="col-md-12 ">
                   <div id="other_attach-box">
                       <div class="form-group other_attach_scope">
                           <div class="col-md-4 text-right">
                               <label class="attach_remove"><span class="text-danger">*</span>Scope </label>
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
                                       <input type="file"  name="file_scope[]" required class="check_max_size_file">
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
                </div>
           </div>
 

       </div>
   </div>
</div> 

<div class="clearfix"></div>

@endif
 



@if(in_array($find_notice->degree,[2,5,8]))

<input type="hidden" name="previousUrl" id="previousUrl" value="{{ $previousUrl ?? null}}">
<div id="degree_btn"></div>
       <div class="form-group">
        <div class="col-md-offset-5 col-md-6">
           <div  class="status_bug_report {{($find_notice->report_status == 2)?'hide':''}}"> 
                <label>{!! Form::checkbox('main_state', '2',($find_notice->main_state == 2)?true:false, ['class'=>'check','data-checkbox'=>"icheckbox_flat-red"]) !!} 
                    &nbsp;ปิดผลการตรวจประเมิน&nbsp;
                 </label>
             </div> 
            <button type="submit" class="btn btn-primary " id="form-save" onclick="submit_form();return false;"> 
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            <a class="btn btn-default " href="{{url("$previousUrl") }}"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
        </div>
      </div>
@else 
      <div class="clearfix"></div>
        <a  href="{{ url("$previousUrl") }}"  class="btn btn-default btn-lg btn-block ">
            <i class="fa fa-rotate-left"></i>
            <b>กลับ</b>
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
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

<script>
    let find_notice = @json($find_notice ?? []);
    let approveNoticeItems = @json($approveNoticeItems ?? []);
    // document.getElementById('mergeBtn').addEventListener('click', async function() {
    //   // สร้าง overlay และข้อความใน JavaScript
    //   const overlay = document.createElement('div');
    //   overlay.id = 'overlay';
    //   overlay.style.position = 'fixed';
    //   overlay.style.top = '0';
    //   overlay.style.left = '0';
    //   overlay.style.width = '100%';
    //   overlay.style.height = '100%';
    //   overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    //   overlay.style.display = 'flex';
    //   overlay.style.justifyContent = 'center';
    //   overlay.style.alignItems = 'center';
    //   overlay.style.zIndex = '9999';
  
    //   const overlayContent = document.createElement('div');
    //   overlayContent.style.color = 'white';
    //   overlayContent.style.fontSize = '24px';
    //   overlayContent.style.fontWeight = 'bold';
    //   overlayContent.innerText = 'กำลังรวมไฟล์...';
  
    //   overlay.appendChild(overlayContent);
  
    //   // เพิ่ม overlay ลงใน body
    //   document.body.appendChild(overlay);
  
    // //   const proxyUrl = 'http://127.0.0.1:8000/proxy?url=';
    // const proxyUrl = window.location.origin + '/proxy?url=';
    //   const pdfUrls = [
    //     proxyUrl + encodeURIComponent('http://127.0.0.1:8081/certify/check/file_client/LAB_67_236/676f691891eaf_20241228_095728.pdf/LAB_67_236_scope_20241228_095729.pdf'),
    //     proxyUrl + encodeURIComponent('http://127.0.0.1:8081/certify/check/file_client/LAB_67_236/hzsSWk4ZCR-date_time20241228_091225.pdf/temp.pdf')
    
    //   ];

    //   const files = [
    //         { app_no: 'LAB_67_236', filename: '676f691891eaf_20241228_095728.pdf', client_name: 'LAB_67_236_scope_20241228_095729.pdf' },
    //         { app_no: 'LAB_67_236', filename: 'hzsSWk4ZCR-date_time20241228_091225.pdf', client_name: 'temp.pdf' }
    //     ];

  
    //   const pdfArrayBufferPromises = pdfUrls.map(url => fetch(url).then(res => res.arrayBuffer()));
    //   const pdfArrayBuffers = await Promise.all(pdfArrayBufferPromises);
  
    //   const mergedPdf = await PDFLib.PDFDocument.create();
  
    //   for (const pdfBuffer of pdfArrayBuffers) {
    //     const pdfDoc = await PDFLib.PDFDocument.load(pdfBuffer);
    //     const copiedPages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
    //     copiedPages.forEach(page => mergedPdf.addPage(page));
    //   }
  
    //   const mergedPdfBytes = await mergedPdf.save();
    //   const mergedPdfBlob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
  
    //   // ซ่อน overlay หลังจากรวมไฟล์เสร็จ
    //   overlay.style.display = 'none';
  
    //   const downloadLink = document.createElement('a');
    //   downloadLink.href = URL.createObjectURL(mergedPdfBlob);
    //   downloadLink.download = 'merged_file.pdf';
    //   downloadLink.click();


    // });



  </script>

<script>



function  submit_form(){

          var main_state =  $("input[name=main_state]:checked").val();
          let report_status = '{{ !empty($find_notice->report_status)  ? $find_notice->report_status : 1 }}';
          let title = '';
          let l = '';
          if(report_status == 2){
            
                title =  'ยืนยันทำรายการ !';
                l = 4;
          }else{
              if(main_state == 2){
                      title =  'ยืนยันปิดผลการตรวจประเมิน !';
                      l = 8;
              }else{
                      title =  'ยืนยันทำรายการ !';
                      l = 3;
               }
          }
          const _token = $('input[name="_token"]').val();
          var notice_id = $('#notice_id').val();

          Swal.fire({
              title:title,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'บันทึก',
              cancelButtonText: 'ยกเลิก'
              }).then((result) => {
                if($('#assessment_passed').val() == "1"){
                    $.ajax({
                        url: "{{route('save_assessment.check_complete_report_two_sign')}}",
                        method: "POST",
                        data: {
                            _token: _token,
                            notice_id: notice_id,
                        },
                        success: function(result) {
                            console.log(result);
                            if (result.message == true) {
                                $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                                $('#form_assessment').submit();
                            }else{
                                
                                if (result.record_count == 0) {
                                    alert('ยังไม่ได้สร้างรายงานปิด Car(รายงานที่2)');
                                    window.location.href = window.location.origin + '/certify/save_assessment/view-lab-report2-info/' + notice_id;
                                }else{
                                    alert('อยู่ระหว่างการลงนามรายงานปิด Car(รายงานที่2)');
                                }
                            }

                        }
                    });
                }else{
                    if (result.value) {
                        $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                        $('#form_assessment').submit();
                    }
                }

          })
      }

  $(document).ready(function(){
    

    const mergeBtn = $('#mergeBtn');
    if (mergeBtn.length) {
        mergeBtn.on('click', async function () {
            // โค้ดของคุณที่เกี่ยวกับการดาวน์โหลดและรวมไฟล์ PDF
            if(find_notice != null){
                if(find_notice.file != null){
                    const files = []; // สร้าง array สำหรับเก็บข้อมูลไฟล์
                    // ตรวจสอบว่า find_notice มีค่าและมีฟิลด์ file และ file_client_name
                    if (find_notice && find_notice.file && find_notice.file_client_name) {
                        // แยก app_no และ filename จาก find_notice.file
                        const [app_no, filename] = find_notice.file.split('/');

                        // ตรวจสอบว่า app_no และ filename ถูกต้อง
                        if (app_no && filename) {
                            files.push({
                                app_no, // ใช้ app_no จากส่วนแรกของ find_notice.file
                                filename, // ใช้ filename จากส่วนหลังของ find_notice.file
                                client_name: find_notice.file_client_name // ใช้ client_name จาก find_notice
                            });
                        }
                    }

                    // วนลูป approveNoticeItems และเพิ่มข้อมูลเข้าไปใน files
                    approveNoticeItems.forEach(item => {
                        if (item.attachs && item.attachs_client_name) {
                            // แยก app_no และ filename จาก attachs
                            const [app_no, filename] = item.attachs.split('/');

                            // ตรวจสอบว่า app_no และ filename ถูกต้อง
                            if (app_no && filename) {
                                files.push({
                                    app_no, // ใช้ app_no จากส่วนแรกของ attachs
                                    filename, // ใช้ filename จากส่วนหลังของ attachs
                                    client_name: item.attachs_client_name // ใช้ client_name จาก approveNoticeItem
                                });
                            }
                        }
                    });

                    // ตรวจสอบผลลัพธ์
                    // console.log(files);

                    const overlay = document.createElement('div');
                    overlay.id = 'overlay';
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                    overlay.style.display = 'flex';
                    overlay.style.justifyContent = 'center';
                    overlay.style.alignItems = 'center';
                    overlay.style.zIndex = '9999';
                
                    const overlayContent = document.createElement('div');
                    overlayContent.style.color = 'white';
                    overlayContent.style.fontSize = '24px';
                    overlayContent.style.fontWeight = 'bold';
                    overlayContent.innerText = 'กำลังดาวน์โหลด...';
                
                    overlay.appendChild(overlayContent);
                
                    // เพิ่ม overlay ลงใน body
                    document.body.appendChild(overlay);

                    const baseUrl = 'http://127.0.0.1:8081/certify/check/file_client/';

                    // สร้าง URLs สำหรับการดาวน์โหลด
                    const pdfUrls = files.map(file =>
                        `${baseUrl}${file.app_no}/${encodeURIComponent(file.filename)}/${encodeURIComponent(file.client_name)}`
                    );

                    try {
                        // ดาวน์โหลดไฟล์ PDF
                        const pdfArrayBufferPromises = pdfUrls.map(url => fetch(url).then(res => {
                            if (!res.ok) {
                                throw new Error(`Failed to fetch ${url}`);
                            }
                            return res.arrayBuffer();
                        }));
                        const pdfArrayBuffers = await Promise.all(pdfArrayBufferPromises);

                        // ใช้ pdf-lib รวมไฟล์ PDF
                        const mergedPdf = await PDFLib.PDFDocument.create();
                        for (const pdfBuffer of pdfArrayBuffers) {
                            const pdfDoc = await PDFLib.PDFDocument.load(pdfBuffer);
                            const copiedPages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                            copiedPages.forEach(page => mergedPdf.addPage(page));
                        }

                        // บันทึกไฟล์ PDF ที่รวมแล้ว
                        const mergedPdfBytes = await mergedPdf.save();
                        const mergedPdfBlob = new Blob([mergedPdfBytes], { type: 'application/pdf' });

                        // ซ่อน overlay หลังจากรวมไฟล์เสร็จ
                        overlay.style.display = 'none';
                        // สร้างลิงก์สำหรับดาวน์โหลดไฟล์ PDF
                        const downloadLink = document.createElement('a');
                        downloadLink.href = URL.createObjectURL(mergedPdfBlob);
                        downloadLink.download = 'merged_file.pdf';
                        downloadLink.click();

                    } catch (error) {
                        console.error('Error merging PDFs:', error);
                    }

                }else{
                    alert("ยังไม่ได้สร้างรายงานตรวจประเมิน")
                }

            }
        });
    }

    // document.getElementById('mergeBtn').addEventListener('click', async function () 
    // {

    

    // });

    let degree = '{{ !empty($find_notice->degree)  ? $find_notice->degree : null  }}';
       console.log(degree);
       if(degree == 1 || degree == 4 || degree == 3  || degree == 7 ){
          $('#box-readonly').find('.icon-close').parent().remove();
          $('#box-readonly').find('.fa-copy').parent().remove();
          $('#box-readonly').find('.div_hide').hide();
          $('#box-readonly').find('input').prop('disabled', true);
          $('#box-readonly').find('textarea').prop('disabled', true); 
          $('#box-readonly').find('select').prop('disabled', true);
          $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
          $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
          $('#box-readonly').find('button').prop('disabled', true);

      }

      $('.check_readonly').prop('disabled', true);
      $('.check_readonly').parent().removeClass('disabled');
      $('.check_readonly').parent().css('margin-top', '8px');
   
       $('.check-readonly').prop('disabled', true);
       $('.check-readonly').parent().removeClass('disabled');
       $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});

      let check_readonly = '{{ ($find_notice->report_status == 1)  ? 1 : 2 }}';
      if(check_readonly == 1){
          $('.check-readonly').prop('disabled', true);
          $('.check-readonly').parent().removeClass('disabled');
          $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
      }

      
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

      $('#button_show_request_edit_scope_modal').on('click', function(event) {
        console.log('ok');
        event.preventDefault(); // ป้องกัน default behavior

        $('#modal-request-edit-scope').modal('show');
            // แสดง modal ด้วย id ของมัน
            $('#edit_detail').css({
                'width': '100%',
                'height': '150px',
                'padding': '5px',
                'box-sizing': 'border-box !important',
                'border': '1px solid #ccc !important',
                'border-top': '1px solid #ccc !important',
                'border-bottom': '1px solid #ccc !important',
                'border-radius': '4px !important',
                'background-color': '#e6f7ff', // เปลี่ยนสีพื้นหลังที่นี่
                'font-size': '16px',
                'resize': 'none'
            });

            $('#edit_detail').val(''); // โฟกัสไปที่ textarea


       
        });
 });
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
                          url: "{!! url('/certify/estimated_cost-ib/delete_file') !!}"  + "/" + id
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
                          url: "{!! url('/certify/estimated_cost-ib/delete_file') !!}"  + "/" + id
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
  </script>
  <script>
      $(document).ready(function () {
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


              
      $(document).on('click', '#button_request_edit_scope', function(e) {
            e.preventDefault();

            // รับค่าจากฟอร์ม
            const _token = $('input[name="_token"]').val();
            var app_id = $('#app_id').val();
            var notice_id = $('#notice_id').val();
            var message = $('#edit_detail').val();

            if (message == "") {
                alert("กรุณากรอกเหตผล");
                return;
            }

            // สร้าง overlay
            showOverlay();

            // เรียก AJAX
            $.ajax({
                url: "{{route('save_assessment.api.request_edit_scope')}}",
                method: "POST",
                data: {
                    _token: _token,
                    app_id: app_id,
                    notice_id:notice_id,
                    message: message,
                },
                success: function(result) {
                    console.log(result);
                    $('#modal-request-edit-scope').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
                },
                complete: function() {
                    // ลบ overlay เมื่อคำขอเสร็จสิ้น
                    hideOverlay();
                }
            });
        });


    function showOverlay() {
        // ตรวจสอบว่ามี overlay อยู่หรือยัง
        if ($('#loading-overlay').length === 0) {
            $('body').append(`
                <div id="loading-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.4);
                    z-index: 1050;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: black;
                    font-size: 65px;
                    font-family: 'Kanit', sans-serif;
                ">
                    กำลังบันทึก กรุณารอสักครู่...
                </div>
            `);
        }
    }


    // ฟังก์ชันสำหรับลบ overlay
    function hideOverlay() {
        $('#loading-overlay').remove();
    }

  
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
                   text        : "กำลังบันทึก กรุณารอสักครู่..."
             });
              return true; // Don't submit form for this demo
            });
        }
    
      });
    
    </script>
@endpush


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
                    <h3 class="box-title pull-left">บันทึกผลการตรวจประเมิน (CB)</h3>
                    @can('view-'.str_slug('assessmentcb'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous()  }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
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

                    {!! Form::model($assessment, [
                        'method' => 'POST',
                        'url' => ['/certificate/assessment-cb/update',$assessment->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form_assessment'
                    ]) !!}
 <div id="box-readonly">
<div class="row">
    <div class="col-md-12">

    <div class="form-group {{ $errors->has('reference_refno') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('reference_refno', '<span class="text-danger">*</span> '.'เลขคำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-7">
                {!! Form::text('reference_refno', (!empty($assessment->reference_refno) ? $assessment->reference_refno  : null) , ['id' => 'reference_refno', 'class' => 'form-control no-drop', 'placeholder'=>'', 'disabled' => true]); !!}
            </div>
    </div>
    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('name','ชื่อผู้ยื่นคำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            {!! Form::text('name', null, ['id' => 'applicant_name', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('laboratory_name') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('laboratory_name','ชื่อหน่วยรับรอง '.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            {!! Form::text('laboratory_name',   null , ['id' => 'laboratory_name', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span> '.'ชื่อคณะผู้ตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            {!! Form::text('auditor',  null, ['id' => 'auditor', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('auditor_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('auditor_date', '<span class="text-danger">*</span> '.'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            {!! Form::text('auditor_date',  null, ['id' => 'auditor_date', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
        </div>
    </div>

    @if (!empty($assessment->auditor_file))
        <div class="form-group {{ $errors->has('auditor_date') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('auditor_date', '<span class="text-danger">*</span> '.'กำหนดการตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-7">
                <a href="{{url('funtions/get-view/'.$assessment->auditor_file->url.'/'.( !empty($assessment->auditor_file->filename) ? $assessment->auditor_file->filename : 'null' ))}}" 
                    title="{{ !empty($assessment->auditor_file->filename) ? $assessment->auditor_file->filename :  basename($assessment->auditor_file->url) }}" target="_blank">
                    {!! HP::FileExtension($assessment->auditor_file->url)  ?? '' !!}
                </a>
            </div>
        </div>
    @endif
  <hr>

    <div class="form-group {{ $errors->has('laboratory_name') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('laboratory_name', '<span class="text-danger">*</span> '.'รายงานข้อบกพร่อง'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            <div class="row">
                <label class="col-md-3">
                    {!! Form::radio('bug_report', '1', false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
                </label>
                <label class="col-md-3">
                    {!! Form::radio('bug_report', '2', true, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
                </label>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> '.'วันที่ทำรายงาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            <div class="input-group">     
                {!! Form::text('report_date', 
                !empty($assessment->report_date) ? HP::revertDate($assessment->report_date,true) :  null,  
                ['class' => 'form-control mydatepicker', 'id'=>'SaveDate',
                  'placeholder'=>'dd/mm/yyyy','disabled'=>true])!!}
                <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> '.'รายงานการตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
              @if(isset($assessment)  && !is_null($assessment->FileAttachAssessment1To)) 
                        <p id="RemoveFlie">
                            <a href="{{url('funtions/get-view/'.$assessment->FileAttachAssessment1To->url.'/'.( !empty($assessment->FileAttachAssessment1To->filename) ? $assessment->FileAttachAssessment1To->filename : 'null' ))}}" 
                                title="{{ !empty($assessment->FileAttachAssessment1To->filename) ? $assessment->FileAttachAssessment1To->filename :  basename($assessment->FileAttachAssessment1To->url) }}" target="_blank">
                                {!! HP::FileExtension($assessment->FileAttachAssessment1To->url)  ?? '' !!}
                            </a>
                

                        <button class="btn btn-danger btn-xs div_hide" type="button"
                            onclick="RemoveFlie({{$assessment->FileAttachAssessment1To->id}})">
                            <i class="icon-close"></i>
                        </button>     
                    </p>
                    <div id="AddFile"></div>      
              @endif
        </div>
    </div>
    @if(isset($assessment)  && !is_null($assessment->FileAttachAssessment5To) &&  in_array($assessment->degree,[7])) 
    <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('', '<span class="text-danger">*</span> '.'รายงานปิด Car'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            <a href="{{url('funtions/get-view/'.$assessment->FileAttachAssessment5To->url.'/'.( !empty($assessment->FileAttachAssessment5To->filename) ? $assessment->FileAttachAssessment5To->filename : 'null' ))}}" 
                title="{{ !empty($assessment->FileAttachAssessment5To->filename) ? $assessment->FileAttachAssessment5To->filename :  basename($assessment->FileAttachAssessment5To->url) }}" target="_blank">
                {!! HP::FileExtension($assessment->FileAttachAssessment5To->url)  ?? '' !!}
            </a>   
        </div>
    </div>
    @endif

    <div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('report_date', 'ไฟล์แนบ'.' :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
                <div id="other_attach">
                    <div class="form-group other_attach_item">
                        <div class="col-md-10">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>  
                                    <input type="file"  name="attachs[]"  class="check_max_size_file">
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
                @if(!is_null($assessment) && (count($assessment->FileAttachAssessment4Many) > 0 ) )
                    @foreach($assessment->FileAttachAssessment4Many as  $key => $item)
                      <p id="remove_attach_all{{$item->id}}">
                             <a href="{{url('funtions/get-view/'.$item->url.'/'.( !empty($item->filename) ? $item->filename : 'null' ))}}" 
                                 title="{{ !empty($item->filename) ? $item->filename :  basename($item->url) }}" target="_blank">
                                  {!! HP::FileExtension($item->filename)  ?? '' !!}
                            </a>

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
</div>

                  
 
          
                        <div class="clearfix"></div>
                         {{-- @include ('certificate/cb/assessment-cb/form.log_bug') --}}

     
                         <div class="clearfix"></div>
                           @if(in_array($assessment->degree,[2,3,4,7,8]))
                          @include ('certificate/cb/assessment-cb/form.bug')
                          @endif
                          {{-- @if($assessment->degree >= 5 || $assessment->degree == 8)
                          @include ('certificate/cb/assessment-cb/form.log_inspection')
                          @endif   --}}
</div>

@if($assessment->degree == 5)

<div class="row form-group" id="div_details">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend><h3>ผลการตรวจประเมิน</h3></legend>   
              

           <div class="row">
               <div class="col-md-12 ">
                   <div id="other_attach_report">
                       <div class="form-group other_attach_report">
                           <div class="col-md-4 text-right">
                               <label class="attach_remove"><span class="text-danger">*</span> สรุปรายงานการตรวจทุกครั้ง </label>
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
                                       <input type="file"  name="file_report[]" class="check_max_size_file" required>
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
           </div>

       </div>
   </div>
</div> 

<div class="clearfix"></div>

@endif



 

@if(in_array($assessment->degree,[2,5,8]))
            <input type="hidden" name="previousUrl" id="previousUrl" value="{{ app('url')->previous()  }}">
                <div id="degree_btn"></div>
            <div class="form-group">
            <div class="col-md-offset-5 col-md-6">
                
                <div  class="status_bug_report {{($assessment->bug_report == 2)?'hide':''}}"> 
                    <label>{!! Form::checkbox('main_state', '2',($assessment->main_state == 2)?true:false, ['class'=>'check','data-checkbox'=>"icheckbox_flat-red"]) !!} 
                        &nbsp;ปิดผลการตรวจประเมิน&nbsp;
                        </label>
                    </div> 
                <button type="submit" class="btn btn-primary" id="form-save" onclick="submit_form();return false;"> 
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <a class="btn btn-default" href="{{app('url')->previous()}}"><i class="fa fa-rotate-left"></i> ยกเลิก </a>
            </div>
            </div>
@else 
            <div class="clearfix"></div>
            <a  href="{{app('url')->previous()}}"  class="btn btn-default btn-lg btn-block">
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
 
  <script>
    jQuery(document).ready(function() {
        let degree = '{{ !empty($assessment->degree)  ? $assessment->degree : null  }}';
         
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

    });
</script>
  <script>


         function  submit_form(){
            var main_state =  $("input[name=main_state]:checked").val();
            let bug_report = '{{ !empty($assessment->bug_report)  ?  $assessment->bug_report : 1 }}';
            let title = '';
            let l = '';
            if(bug_report == 2){
              
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
     
         
            Swal.fire({
                title:title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                        $('#form_assessment').submit();
                    }
            })
        }
 
    $(document).ready(function(){
 
        $('#form_assessment').parsley().on('field:validated', function() {
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

        $('.check_readonly').prop('disabled', true);
        $('.check_readonly').parent().removeClass('disabled');
        $('.check_readonly').parent().css('margin-top', '8px');
     
         $('.check-readonly').prop('disabled', true);
         $('.check-readonly').parent().removeClass('disabled');
         $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});

        let check_readonly = '{{ ($assessment->bug_report == 1)  ? 1 : 2 }}';
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
                            url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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
                            url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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
    
    </script>
@endpush


@push('css')
 
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <style>
        .vertical {
            float: left;
            border-right: 2px solid #eee;
     
        }
        input[type="checkbox"]:disabled {
            cursor: not-allowed;
        }
        .alert-secondary {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }
    </style>
@endpush

<div class="panel panel-info">
    <div class="panel-heading">
        งานคดีผลิตภัณฑ์อุตสาหกรรม
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    @php
                        $lawcase = $case;
                    @endphp
                    
                    @include ('laws.cases.request-form.cases')

                </div>
            </div>

        </div>
    </div>
</div>

@php
    $option_status = App\Models\Law\Cases\LawCasesForm::status_list();

    $log = App\Models\Law\Log\LawLogWorking::where(function($query) use($case){
                                                $query->where('ref_table', (new App\Models\Law\Cases\LawCasesForm)->getTable() )
                                                        ->where('ref_id', $case->id )
                                                        ->where('ref_system', "ผลพิจารณางานคดี" )
                                                        ->where('title', "บันทึกผลตรวจสอบเอกสาร" );
                                            })
                                            ->orderByDesc('created_at')
                                            ->first();      
    $log_document = '';
    if(!empty($case->law_log_working_title_many) && count($case->law_log_working_title_many) >= '1'){
        $log_document .= '<a  href="javascript:void(0)" class="font-medium-7 text-primary log_document col-md-8 control-label" data-id="'.$case->id.'" >(ประวัติ '.count($case->law_log_working_title_many).' ครั้ง)</a>';
    }
@endphp
<div class="panel panel-info">
    <div class="panel-heading">
        พิจารณาความผิด
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true">
        <div class="panel-body">

            <div class="row">

                <div class="col-md-6 col-12 mb-md-0 mb-4 m-t-5 vertical">
                    <div class="form-group">
                        {!! $log_document !!}
                    </div>

                    <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status', 'สถานะ', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::select('status',  [  '2'=> 'อยู่ระหว่างตรวจสอบข้อมูล','3'=> 'ขอข้อมูลเพิ่มเติม (ตีกลับ)','4'=> 'ข้อมูลครบถ้วนอยู่ระหว่างพิจารณา', ],  (!empty($log->status) && !empty($option_status)) ?array_search ($log->status, $option_status):null, ['class' => 'form-control ',  'id' => 'status', 'required' => true,'placeholder'=>'-เลือกสถานะ-']) !!}
                            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <p class="text-muted text-right"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>
                    
                    <div class="form-group {{ $errors->has('case_number') ? 'has-error' : ''}}">
                        {!! Form::label('case_number', 'หลักฐานผลพิจารณา(ถ้ามี)', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                           @if (!empty($case->AttachFileDocument))
                                @php
                                    $attachs_document = $case->AttachFileDocument;
                                @endphp
                                <a href="{!! url('funtions/get-law-view/files/'.$attachs_document->url.'/'.(!empty($attachs_document->filename) ? $attachs_document->filename :  basename($attachs_document->url))) !!}" target="_blank">
                                    {!! !empty($attachs_document->filename) ? $attachs_document->filename : '' !!}
                                    {!! HP::FileExtension($attachs_document->url) ?? '' !!}
                                </a>
                                <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_document->id).'/'.base64_encode('law/cases/results/'.$case->id.'/document') ) !!}" title="ลบไฟล์">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            @else
                               <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="file_document" id="file_document"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                               </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('accept_remark') ? 'has-error' : ''}}" >
                        {!! Form::label('accept_remark', 'หมายเหตุ', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::textarea('accept_remark', null, ['class' => 'form-control accept_remark','id' =>'accept_remark', 'rows'=>'3']); !!}
                            {!! $errors->first('accept_remark', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    
                    <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                        {!! Form::label('', 'ผู้บันทึก', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                             {!! Form::text('', !empty($case->user_accept_to->FullName) ? $case->user_accept_to->FullName :  auth()->user()->FullName, ['class' => 'form-control ', 'disabled' => true ]) !!}
                            {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    
                    <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                        {!! Form::label('', 'วันที่บันทึก', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                             {!! Form::text('', !empty($case->accept_at) ? HP::DateTimeThai($case->accept_at) : HP::DateTimeThai(date('Y-m-d H:i:s')), ['class' => 'form-control ', 'disabled' => true ]) !!}
                            {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>

                <div class="col-md-6 col-12 mb-md-0 mb-4 border-left m-t-5">

                    @php
                        $email_results = [];

                        if(!is_null($law_notify)){

                            // อีเมล
                            $emails =  $law_notify->email;
                            if(!is_null($emails)){
                                $emails = json_decode($emails,true);
                                if(!empty($emails) && count($emails) > 0){ 
                                    $email_results = $emails; 
                                }
                            }
                        }else{ // ครั้งแรกแจ้งเตือน
                             // เจ้าของคดี
                            $owner_email =  (!empty($case->owner_email)  && filter_var($case->owner_email, FILTER_VALIDATE_EMAIL) ? $case->owner_email : null) ;
                            if(!is_null($owner_email)){
                                $email_results[] =  $owner_email;
                            }
                            // อีเมลผู้ประสานงาน (เจ้าของคดี)
                            // $owner_contact_email =  (!empty($case->owner_contact_email)  && filter_var($case->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $case->owner_contact_email : null) ;
                            // if(!is_null($owner_contact_email)){
                            //     $email_results[] =  $owner_contact_email;
                            // }
                            // อีเมลผู้ประสานงาน (กระทำความผิด)
                            // $offend_contact_email =  (!empty($case->offend_contact_email)  && filter_var($case->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $case->offend_contact_email : null) ;
                            // if(!is_null($offend_contact_email)){
                            //     $email_results[] =  $offend_contact_email;
                            // }
                        }

                    @endphp

                    <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                        {!! Form::label('', 'ช่องทางแจ้งเตือน', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-3">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox1" type="checkbox" value="1" name="funnel_system" {!! !empty($law_notify->channel) && in_array( 1 ,  json_decode($law_notify->channel,true))?'checked':( empty($law_notify)?'checked':null ) !!} >
                                <label for="checkbox1"> ผ่านระบบ </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox2" type="checkbox" value="2" name="funnel_email"  {!! !empty($law_notify->channel) && in_array( 2 ,json_decode($law_notify->channel,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                <label for="checkbox2"> ผ่านอีเมล </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                        {!! Form::label('', 'แจ้งเตือนไปยัง', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            <div class="checkbox checkbox-info">
                                <input id="checkbox3" type="checkbox" value="1" name="owner_email" {!! !empty( $law_notify->notify_type ) && in_array( 1, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                <label for="checkbox3"> เจ้าของคดี </label>
                            </div>
                            {{-- <div class="checkbox checkbox-info">
                                <input id="checkbox4" type="checkbox" value="2" name="owner_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 2, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                <label for="checkbox4"> ผู้ประสานงาน (เจ้าของคดี) </label>
                            </div>
                            <div class="checkbox checkbox-info">
                                <input id="checkbox5" type="checkbox" value="3" name="offend_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 3, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                <label for="checkbox5">  ผู้ประสานงาน (กระทำความผิด) </label>
                            </div> --}}
                            <div class="checkbox checkbox-info">
                                <input id="checkbox6" type="checkbox" value="4" name="reg_email"  {!! !empty( $law_notify->notify_type ) && in_array( 4,json_decode($law_notify->notify_type,true) )?'checked':null !!} >
                                <label for="checkbox6"> ผู้มอบหมายงาน </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="col-md-offset-4 col-md-8">
                            <input type="text" value="{{ count($email_results) > 0 ?  implode(",",$email_results) : '' }}" data-role="tagsinput"  name="email_results"  id="email_results"  /> 
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="col-md-offset-4 col-md-8">     
                            <div class="alert alert-bg-secondary font-15">
                                <b>หมายเหตุ : กรณีที่ผู้รับแจ้งเตือนไม่ใช่สมาชิกในระบบจะไม่สามารถรับแจ้งเตือนผ่านระบบได้</b>
                            </div>   
                        </div>
                    </div>
                    
                </div>

            </div>
            <div class="modal fade" id="LogDocumentModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="PaymentModalLabel1" aria-hidden="true">
                <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                             <h4 class="modal-title" id="LogDocumentModalLabel1">ข้อมูลบันทึกผลตรวจสอบเอกสาร</h4>
                         </div>
                         <div class="modal-body">
                                 <div class="white-box">
                                      <div class="row form-group">
                                        <div class="col-md-12 ">
                                            <div class="table">
                                                <table class="table table-striped"  >
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" width="2%">#</th>
                                                            <th class="text-center" width="20%">สถานะ</th>
                                                            <th class="text-center" width="20%">ผู้บันทึก</th>
                                                            <th class="text-center" width="20%">วันที่บันทึก</th>
                                                            <th class="text-center" width="20%">หมายเหตุ</th>
                                    
                                                        </tr>
                                                        </thead>
                                                        <tbody id="table_tbody_log_document">
        
                                                        </tbody>
                                                    </table>
                                                </div>
                                        </div>
                                     </div>
                                 </div>
                                 <div class="text-right ">
                                     <a type="button" href="javascript:void(0)"  class="btn btn-default text-dark" data-dismiss="modal" aria-label="Close">
                                         {!! __('ปิด') !!}
                                     </a>
                                 </div>
             
                         </div>
                     </div>
                 </div>
             </div>
        </div>
    </div>
</div>
@if( in_array( $case->status , [1,2,3] ) )

    <div class="form-group">
        <div class="col-md-offset-5 col-md-4">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
    
            @can('view-'.str_slug('law-cases-result'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/results') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>


@endif

@push('js')
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {

            $("body").on("click", ".log_document", function() {
                          $('#table_tbody_log_document').html('');
                          $.LoadingOverlay("show", {
                            image       : "",
                            text        :   "กำลังตรวจสอบ กรุณารอสักครู่..." 
                          });
                       $.ajax({
                                method: "get",
                                url: "{{ url('law/cases/results/log_document') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id":  $(this).data('id')
                                }
                            }).success(function (msg) {

                                if (msg.message == true) {
                                    $.each(msg.datas,function (index,value) {
                                       var  $tr = '';
                                            $tr += '<tr>';
                                                $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                                $tr += '<td class="text-top">' +value.status+ '</td>';
                                                $tr += '<td class="text-top">' +value.created_by+ '</td>';
                                                $tr += '<td class="text-top">' +value.created_at+ '</td>';
                                                $tr += '<td class="text-top">' +value.remark+ '</td>';
                                            $tr += '</tr>';
                                        $('#table_tbody_log_document').append($tr);
                                    });
                                   
                                    $('#LogDocumentModals').modal('show');
                                    $.LoadingOverlay("hide");

                                }else{
                                    $('#LogDocumentModals').modal('show');
                                    $.LoadingOverlay("hide");

                                }
                            });
                  
                 });

            @if ( !in_array( $case->status , [1,2,3] ))
                $('.form-document').find('button[type="submit"]').remove();
                $('.form-document').find('.icon-close').parent().remove();
                $('.form-document').find('.fa-copy').parent().remove();
                $('.form-document').find('input').prop('disabled', true);
                $('.form-document').find('textarea').prop('disabled', true);
                $('.form-document').find('select').prop('disabled', true);
                $('.form-document').find('.bootstrap-tagsinput').prop('disabled', true);
                $('.form-document').find('span.tag').children('span[data-role="remove"]').remove();
                $('.form-document').find('button').prop('disabled', true);
                $('.form-document').find('button').remove();
                $('.form-document').find('.btn-remove-file').parent().remove();
                $('.form-document').find('.show_tag_a').hide();
                $('.form-document').find('.input_show_file').hide();
            @endif

            // อีเมลเจ้าของคดี 
            var owner_email =  '{{  (!empty($case->owner_email)  && filter_var($case->owner_email, FILTER_VALIDATE_EMAIL) ? $case->owner_email : '') }}';
            $('#checkbox3').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_email != ''){
                    $('#email_results').tagsinput('add', owner_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_email);
                }
            });

            // อีเมลผู้ประสานงาน (เจ้าของคดี)
            var owner_contact_email =  '{{  (!empty($case->owner_contact_email)  && filter_var($case->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $case->owner_contact_email : '') }}';
            $('#checkbox4').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_contact_email != ''){
                    $('#email_results').tagsinput('add', owner_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_contact_email);
                }
            });

            // อีเมลผู้ประสานงาน (กระทำความผิด)
            var offend_contact_email =  '{{  (!empty($case->offend_contact_email)  && filter_var($case->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $case->offend_contact_email : '') }}';
            $('#checkbox5').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && offend_contact_email != ''){
                    $('#email_results').tagsinput('add', offend_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', offend_contact_email);
                }
            });

            // อีเมลผู้มอบหมายงาน
            var reg_email =  '{{  (!empty($case->reg_email)  && filter_var($case->reg_email, FILTER_VALIDATE_EMAIL) ? $case->reg_email : '') }}';
            $('#checkbox6').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && reg_email != ''){
                    $('#email_results').tagsinput('add', reg_email); 
                }else{
                    $('#email_results').tagsinput('remove', reg_email);
                }
            });
                

            $('#status').change(function(){
                if($(this).val() !== '' && ($(this).val() == 5 || $(this).val() == 7)){
                    $('#div_case_number').show();
                }else{
                    $('#div_case_number').hide();
                }
            }); 
            $('#status').change();  
        
            $('#file_document').change( function () {
                var fileExtension = ['jpg','png' ,'pdf'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .jpg .png หรือ .pdf',
                        '',
                        'info'
                    );
                    this.value = '';
                    return false;
                }
            });
        });
    </script>
        
@endpush 
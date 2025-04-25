@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />    
    <style>
 
        .not-allowed {
           cursor: not-allowed
        }
    
       .btn-light-info {
           background-color: #ccf5f8;
           color: #00CFDD !important;
       }
       .btn-light-info:hover, .btn-light-info.hover {
           background-color: #00CFDD;
           color: #fff !important;
       }
    
       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 10px 10px;
            vertical-align: middle;
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

@php

    $modelNotify        = new App\Models\Law\Log\LawNotify;
    $modelLicenseResult = new App\Models\Law\Cases\LawCasesLicenseResult;

    //เหตุผลเพิกถอน
    $option_revoke_type = App\Models\Tb4\TisiCancelReason::where('status' ,1)->select( DB::raw('reason AS title'), 'id' )->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    //แจ้งคดี
    $lawcases           = $result->law_case_to;
    //ข้อมูลดำเนินการกับใบอนุญาต
    $license_result     = $result->law_case_license_result_to;

    //การแจ้งเตือน
    $law_notify         = null;
    if(!is_null($license_result)){
        $law_notify     = $modelNotify->where('ref_table',($modelLicenseResult)->getTable())->where('ref_id',$license_result->id)->where('name_system','ดำเนินการกับใบอนุญาต(ทางปกครอง)')->orderby('id','desc')->first();
    }

    $email_results      = [];
    if(!is_null($law_notify)){

        // อีเมล
        $emails                = $law_notify->email;
        if(!is_null($emails)){
            $emails            = json_decode($emails,true);
            if(!empty($emails) && count($emails) > 0){ 
                $email_results = $emails; 
            }
        }

    }else{ // ครั้งแรกแจ้งเตือน

        // อีเมลผู้ประสานงาน (เจ้าของคดี)
        $owner_contact_email =  (!empty($lawcases->owner_contact_email)  && filter_var($lawcases->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $lawcases->owner_contact_email : null) ;
        if(!is_null($owner_contact_email)){
            $email_results[] =  $owner_contact_email;
        }

        // อีเมลผู้ประสานงาน (กระทำความผิด)
        $offend_contact_email =  (!empty($lawcases->offend_contact_email)  && filter_var($lawcases->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $lawcases->offend_contact_email : null) ;
        if(!is_null($offend_contact_email)){
            $email_results[]  =  $offend_contact_email;
        }
    }

@endphp

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend><b>ข้อมูลดำเนินการกับใบอนุญาต</b></legend>

            <div class="form-group required{{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'เลขคดี', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('',   !empty($lawcases->case_number) ? $lawcases->case_number :  null , ['class' => 'form-control ', 'disabled' => true ]) !!}
                    {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'ผู้ประกอบการ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('',  !empty($lawcases->offend_name) ? $lawcases->offend_name :  null, ['class' => 'form-control ', 'disabled' => true ]) !!}
                    {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'เลขที่ใบอนุญาต', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('',  !empty($lawcases->offend_license_number) ? $lawcases->offend_license_number :  null , ['class' => 'form-control ', 'disabled' => true ]) !!}
                    {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('status_result') ? 'has-error' : ''}}" >
                {!! Form::label('status_result', 'สถานะใบอนุญาต', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-7">
                    <label>{!! Form::radio('status_result', '1',(is_null($license_result) || ( !empty($license_result->status_result)  && $license_result->status_result == '1' ) ) ?  true : false , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' =>  true]) !!}&nbsp; ใช้งาน &nbsp;</label>
                    <label>{!! Form::radio('status_result', '2',  (!empty($license_result->status_result)  && $license_result->status_result == '2' ) ?  true : false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}&nbsp; พักใช้ &nbsp;</label>
                    <label>{!! Form::radio('status_result', '3',  (!empty($license_result->status_result)  && $license_result->status_result == '3' ) ?  true : false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}&nbsp; เพิกถอน &nbsp;</label>
                    {!! $errors->first('status_result', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="div_pause">
                <div class="form-group required {{ $errors->has('') ? 'has-error' : ''}}" >
                    {!! Form::label('date_pause_start', 'วันที่เริ่มพักใช้', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-3">
                        <div class="inputWithIcon">
                            {!! Form::text('date_pause_start', !empty($license_result->date_pause_start)?HP::revertDate($license_result->date_pause_start,true):null , ['class' => 'form-control  mydatepicker ', 'id' => 'date_pause_start','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off' , 'required' => true ] ) !!}
                            <i class="icon-calender"></i>
                        </div>
                    </div>
                    {!! Form::label('date_pause_amount', 'จำนวนวันที่พักใช้', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-2">
                        {!! Form::text('date_pause_amount', !empty($license_result->date_pause_amount)?$license_result->date_pause_amount:null , ['class' => 'form-control amount_date', 'id' => 'date_pause_amount', 'autocomplete' => 'off' , 'required' => true ] ) !!}
                    </div>
                </div>
                <div class="form-group required {{ $errors->has('') ? 'has-error' : ''}}" >
                    {!! Form::label('date_pause_end', 'สิ้นสุดพักใช้', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-3">
                        <div class="inputWithIcon">
                            {!! Form::text('date_pause_end', !empty($license_result->date_pause_end)?HP::revertDate($license_result->date_pause_end,true):null , ['class' => 'form-control ', 'id' => 'date_pause_end','placeholder' => 'วว/ดด/ปปปป', 'readonly' => true , 'required' => true ]  ) !!}
                            <i class="icon-calender"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="div_revoke">
                <div class="form-group required   {{ $errors->has('') ? 'has-error' : ''}}"  >
                    {!! Form::label('', 'วันที่เพิกถอน', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-2">
                        <div class="inputWithIcon">
                            {!! Form::text('date_revoke', !empty($license_result->date_revoke)?HP::revertDate($license_result->date_revoke,true):null , ['class' => 'form-control mydatepicker  ', 'id' => 'date_revoke','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off' , 'required' => true ] ) !!}
                            <i class="icon-calender"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group required {{ $errors->has('basic_revoke_type_id') ? 'has-error' : ''}}" >
                    {!! Form::label('basic_revoke_type_id', 'เหตุผลเพิกถอน', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-4">
                        {!! Form::select('basic_revoke_type_id',$option_revoke_type ,  !empty($license_result->basic_revoke_type_id)?$license_result->basic_revoke_type_id:null,  ['class' => 'form-control ', 'placeholder'=>'- เลือกเหตุผลเพิกถอน -' , 'required' => true ]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-4">
                    <p class="text-muted"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>
                </div>
            </div>

            <div class="form-group {{ $errors->has('case_number') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('case_number', 'หลักฐานผลการพิจารณา:<span class="text-danger label_attachs">*</span>', ['class' => 'col-md-4 control-label label-filter'])) !!}
                {{-- {!! Form::label('case_number', 'หลักฐานผลการพิจารณา', ['class' => 'col-md-4 control-label']) !!} --}}
                <div class="col-md-6">
                    @if (!empty($license_result->FileAttachTo))
                        @php
                            $attachs_document = $license_result->FileAttachTo;
                        @endphp
                        <a href="{!! HP::getFileStorage($attachs_document->url) !!}" target="_blank">
                            {!! !empty($attachs_document->filename) ? $attachs_document->filename : '' !!}
                            {!! HP::FileExtension($attachs_document->url) ?? '' !!}
                        </a>
                        <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_document->id).'/'.base64_encode('law/cases/manage_license/'.$result->id.'/edit') ) !!}" title="ลบไฟล์">
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
                                <input type="file" name="attachs" id="attachs"  accept=".jpg,.png,.pdf" class="check_max_size_file" required>
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}" >
                {!! Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::textarea('remark',!empty($license_result->remark)?$license_result->remark:'', ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3']); !!}
                    {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend><b>การแจ้งเตือน</b></legend>

            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'ช่องทางแจ้งเตือน', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-2">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox1" type="checkbox" value="1" name="funnel_system" {!! !empty($law_notify->channel) && in_array( 1 ,  json_decode($law_notify->channel,true))?'checked':( empty($law_notify)?'checked':null ) !!} >
                        <label for="checkbox1"> ผ่านระบบ </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox2" type="checkbox" value="2" name="funnel_email"  {!! !empty($law_notify->channel) && in_array( 2 ,json_decode($law_notify->channel,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                        <label for="checkbox2"> ผ่านอีเมล </label>
                    </div>
                </div>
            </div>

            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'แจ้งเตือนไปยัง', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-7">
                    <div class="checkbox checkbox-info">
                        <input id="checkbox3" type="checkbox" value="1" name="owner_email" {!! !empty( $law_notify->notify_type ) && in_array( 1, json_decode($law_notify->notify_type,true) )?'checked':null !!} >
                        <label for="checkbox3"> เจ้าของคดี </label>
                    </div>
                    <div class="checkbox checkbox-info">
                        <input id="checkbox4" type="checkbox" value="2" name="owner_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 2, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                        <label for="checkbox4"> ผู้ประสานงาน (เจ้าของคดี) </label>
                    </div>
                    <div class="checkbox checkbox-info">
                        <input id="checkbox5" type="checkbox" value="3" name="offend_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 3, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                        <label for="checkbox5">  ผู้ประสานงาน (กระทำความผิด) </label>
                    </div>
                    <div class="checkbox checkbox-info">
                        <input id="checkbox6" type="checkbox" value="4" name="reg_email"  {!! !empty( $law_notify->notify_type ) && in_array( 4,json_decode($law_notify->notify_type,true) )?'checked':null !!} >
                        <label for="checkbox6"> ผู้มอบหมายงาน </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                <div class="col-md-offset-4 col-md-7">
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
        
            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-4">
                     {!! Form::text('', !empty($license_result->user_created->FullName) ? $license_result->user_created->FullName :  auth()->user()->FullName, ['class' => 'form-control ', 'disabled' => true ]) !!}
                    {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            
            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-4">
                     {!! Form::text('', !empty($license_result->accept_at) ? HP::DateTimeThai($license_result->accept_at) : HP::DateTimeThai(date('Y-m-d H:i:s')), ['class' => 'form-control ', 'disabled' => true ]) !!}
                    {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </fieldset>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-cases-manage-licenses'))
            <a class="btn btn-default" href="{{url('/law/cases/manage_license')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>

        $(document).ready(function() {

            $("input[name=status_result]").on("ifChanged",function(){
                status_result();
            });
            status_result();

            // อีเมลเจ้าของคดี 
            var   owner_email =  '{{  (!empty($lawcases->owner_email)  && filter_var($lawcases->owner_email, FILTER_VALIDATE_EMAIL) ? $lawcases->owner_email : '') }}';
                $('#checkbox3').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_email != ''){
                    $('#email_results').tagsinput('add', owner_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_email);
                }
            });
            // อีเมลผู้ประสานงาน (เจ้าของคดี)
            var   owner_contact_email =  '{{  (!empty($lawcases->owner_contact_email)  && filter_var($lawcases->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $lawcases->owner_contact_email : '') }}';
                $('#checkbox4').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_contact_email != ''){
                    $('#email_results').tagsinput('add', owner_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_contact_email);
                }
            });
            // อีเมลผู้ประสานงาน (กระทำความผิด)
                var   offend_contact_email =  '{{  (!empty($lawcases->offend_contact_email)  && filter_var($lawcases->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $lawcases->offend_contact_email : '') }}';
                $('#checkbox5').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && offend_contact_email != ''){
                    $('#email_results').tagsinput('add', offend_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', offend_contact_email);
                }
            });

            // อีเมลผู้มอบหมายงาน
            var   reg_email =  '{{  (!empty($lawcases->reg_email)  && filter_var($lawcases->reg_email, FILTER_VALIDATE_EMAIL) ? $lawcases->reg_email : '') }}';
                $('#checkbox6').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && reg_email != ''){
                    $('#email_results').tagsinput('add', reg_email); 
                }else{
                    $('#email_results').tagsinput('remove', reg_email);
                }
            });
       
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

                
            $("#date_pause_start").change(function() {
                CalculatorDate();
            });

            $('#attachs').change( function () {
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

            $(".amount_date").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 

            $('#date_pause_amount').keyup(function (e) { 
                CalculatorDate();
            });

        });

        function CalculatorDate(){

            var new_date = '';
            if( checkNone($('#date_pause_start').val()) ){
                var dates = $('#date_pause_start').val();

                var add_day    = checkNone($('#date_pause_amount').val())?$('#date_pause_amount').val():0;
                if (/\D/g.test(add_day))
                {
                    add_day = add_day.replace(/\D/g, '');
                }

                dates = dates.split('/'); 
                if(dates.length==3){

                    var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                        date_start.setDate( date_start.getDate() + parseInt(add_day) ); // + 1 วัน
                      
                    var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
                    var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
                    var DB = str_pad(date_start.getDate());

                    var date = DB+'/'+MB+'/'+YB;
                    new_date = date;
                }

            }else{
                alert('กรอกวันที่เริ่มพักใช้');
            }

            $('#date_pause_end').val(new_date);

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

        function status_result(){
            var checked = $("input[name=status_result]:checked").val(); 
            var pause   = $('div.div_pause');
            var revoke  = $('div.div_revoke');

            pause.find('input[type=text], select, textarea').prop('required', false);
            revoke.find('input[type=text], select, textarea').prop('required', false);

            if(checked == "1"){ //ใช้งาน 
                pause.hide();
                revoke.hide();

                pause.find('input').val('');
  
                revoke.find('input').val('');
                revoke.find('select').val('').trigger('change.select2');

                $(".label_attachs").hide();
                $("#attachs").prop('required', false);
                
            } else if(checked == "2"){ //พักใช้
                pause.show(400);
                pause.find('input, select, textarea').prop('required', true);

                revoke.hide();
                revoke.find('input').val('');
                revoke.find('select').val('').trigger('change.select2');

                $(".label_attachs").show();
                $("#attachs").prop('required', true);
            } else if(checked == "3"){ //เพิกถอน
                revoke.show(400);
                revoke.find('input.form-control, select, textarea').prop('required', true);

                pause.hide();
                pause.find('input').val('');

                $(".label_attachs").show();
                $("#attachs").prop('required', true);
            }
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }
    </script>
@endpush

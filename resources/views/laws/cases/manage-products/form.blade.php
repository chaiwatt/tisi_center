@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    <style>
        .border-left {
            border-left: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@php
    $impound        = $lawcases->law_cases_impound_to;
    $product_result = $lawcases->product_result;

    $lawcases->assign_email = !empty($lawcases) && !is_null($lawcases->user_assign_to) && !empty($lawcases->user_assign_to->reg_email)?$lawcases->user_assign_to->reg_email:null;
    $lawcases->lawyer_email = !empty($lawcases) && !is_null($lawcases->user_lawyer_to) && !empty($lawcases->user_lawyer_to->reg_email)?$lawcases->user_lawyer_to->reg_email:null;
    $lawcases->create_email = !empty($lawcases) && !is_null($lawcases->user_created) && !empty($lawcases->user_created->reg_email)?$lawcases->user_created->reg_email:null;

@endphp

{!! Form::hidden('law_cases_id', !empty($lawcases->id)?$lawcases->id:null , ['class' => 'form-control' ]) !!}
{!! Form::hidden('law_case_impound_id', !empty($impound->id)?$impound->id:null , ['class' => 'form-control' ]) !!}

<fieldset class="white-box">
    <legend class="legend"><h4>ข้อมูลรายละเอียด</h4></legend>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_ref_no', 'เลขคดี'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_ref_no', !empty($lawcases->case_number)?$lawcases->case_number:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_name', 'ผู้ประกอบการ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_name', !empty($lawcases->offend_name)?$lawcases->offend_name:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_name', 'เลขประตัวผู้เสียภาษี'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_taxid', !empty($lawcases->offend_taxid)?$lawcases->offend_taxid:null , ['class' => 'form-control', 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_tb3_tisno', 'มอก.'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_tb3_tisno', !empty($lawcases->tb3_tisno)?$lawcases->tb3_tisno:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_license_number', 'เลขที่ใบอนุญาต'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_license_number', !empty($lawcases->offend_license_number)?$lawcases->offend_license_number:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('detail', 'ผลิตภัณฑ์'.' : ', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::textarea('detail', !empty($lawcases->TisName)?$lawcases->TisName:null , ['class' => 'form-control ', 'rows' => 4, 'disabled' => true]) !!}
                    {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    
</fieldset>


<fieldset class="white-box">
    <legend class="legend"><h4>บันทึกดำเนินการกับผลิตภัณฑ์</h4></legend>
    <div class="row">
        <div class="col-md-7 col-12 mb-md-0 mb-4 m-t-0" style="border-right: 1px solid #dee2e6 !important;">
            <p><h4>ข้อมูลการดำเนินการ</h4></p>
            
            <div class="form-group required">
                {!! Form::label('result_process_product_id', 'ดําเนินการกับผลิตภัณฑ์'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::select('result_process_product_id', App\Models\Law\Basic\LawProcessProduct::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') , !empty($product_result->result_process_product_id)?$product_result->result_process_product_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกดําเนินการกับผลิตภัณฑ์ -', 'required' => true ]) !!}
                    {!! $errors->first('result_process_product_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('result_description') ? 'has-error' : ''}}">
                {!! Form::label('result_description', 'โดยวิธีการ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::textarea('result_description', !empty($product_result->result_description)?$product_result->result_description:null , ['class' => 'form-control ', 'required' => 'required', 'rows' => 4]) !!}
                    {!! $errors->first('result_description', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('result_start_date') ? 'has-error' : ''}} box_condition_response">
                {!! Form::label('result_start_date', 'วันที่มีคําสั่ง'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    <div class="inputWithIcon">
                        {!! Form::text('result_start_date', !empty($product_result->result_start_date)? HP::revertDate($product_result->result_start_date, true) : null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required'] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                    {!! $errors->first('result_start_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('result_amount') ? 'has-error' : ''}}">
                {!! Form::label('result_amount', 'ดําเนินการภายใน/วัน'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('result_amount',  !empty($product_result->result_amount)?$product_result->result_amount:null , ['class' => 'form-control input_number vertical-spin-amount text-right', 'data-bts-button-down-class' => 'btn btn-default btn-outline', 'data-bts-button-up-class' => 'btn btn-default btn-outline', 'required' => 'required'  ]) !!}
                    {!! $errors->first('result_amount', '<p class="help-block">:message</p>') !!} 
                </div>
            </div>

            <div class="form-group required{{ $errors->has('result_end_date') ? 'has-error' : ''}} box_condition_response">
                {!! Form::label('result_end_date', 'วันที่เสร็จสิ้น'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    <div class="inputWithIcon">
                        {!! Form::text('result_end_date', !empty($product_result->result_end_date)? HP::revertDate($product_result->result_end_date, true) : null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required'] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                    {!! $errors->first('result_end_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('file_result_tisi') ? 'has-error' : ''}}">
                {!! Form::label('file_result_tisi', 'หลักฐานคำสั่ง กมอ.'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    @if( !empty($product_result->file_law_result) )
                        @php
                            $file_law_result = $product_result->file_law_result;
                        @endphp
                        <a href="{!! HP::getFileStorage($file_law_result->url) !!}" target="_blank" class="m-l-5">
                            {!! !empty($file_law_result->filename) ? $file_law_result->filename : '' !!}
                            {!! HP::FileExtension($file_law_result->filename)  ?? '' !!}
                        </a>
                        <a class="btn btn-danger btn-xs show_tag_a m-l-5" href="{!! url('law/delete-files/'.($file_law_result->id).'/'.base64_encode('law/cases/manage-products/'.$lawcases->id.'/edit') ) !!}" title="ลบไฟล์">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    @else
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                <span class="input-group-text btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="file_result_tisi" required>
                                </span>
                            </span>
                        </div>
                    @endif

                </div>
            </div>

            <div class="form-group{{ $errors->has('result_remark') ? 'has-error' : ''}}">
                {!! Form::label('result_remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::textarea('result_remark', !empty($product_result->result_remark)?$product_result->result_remark:null , ['class' => 'form-control ', 'rows' => 4]) !!}
                    {!! $errors->first('result_remark', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


        </div>
        <div class="col-md-5 col-12 mb-md-0 mb-4 border-left m-t-0">
            <p><h4>การแจ้งเตือน</h4></p>

            @php
                $law_notify =  App\Models\Law\Log\LawNotify::where('ref_table',(new App\Models\Law\Cases\LawCasesForm)->getTable())->where('ref_id',$lawcases->id)->where('name_system','งานคดี : ดำเนินการกับผลิตภัณฑ์')->orderby('id','desc')->first();
            @endphp

            <div class="form-group">
                {!! Form::label('send_mail_status', 'ช่องทางการแจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8 m-t-5">
                    <div class="col-md-6">
                        {!! Form::checkbox('channel[]', '1',( !empty($law_notify) && is_array( $law_notify->ChannelList ) && in_array( '1', $law_notify->ChannelList )?true:false ), ['class'=>'check', 'id' => 'noti_sytem_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                        <label for="noti_sytem_status">ผ่านระบบ</label>
                    </div>
                    <div class="col-md-6">
                        {!! Form::checkbox('channel[]', '2',( !empty($law_notify) && is_array( $law_notify->ChannelList ) && in_array( '2', $law_notify->ChannelList )?true:false ), ['class'=>'check box_mail_status', 'id' => 'noti_email_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                        <label for="noti_email_status">ผ่านอีเมล</label>
                    </div>
                </div>
            </div>
    
            <div class="form-group box_noti_email">
                {!! Form::label('notify_types', 'แจ้งเตือนไปยัง'.' :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8 m-t-5">
                    <div class="col-md-12">
                        {!! Form::checkbox('notify_types[]', '6', !empty($law_notify) && is_array( $law_notify->NotifyTypeList ) && in_array( '6', $law_notify->NotifyTypeList )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-1', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => ( !empty($lawcases->offend_email)?$lawcases->offend_email:'' ) ]) !!}
                        <label for="send_mail_status-1">ผู้กระทำผิด</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::checkbox('notify_types[]', '4', !empty($law_notify) && is_array( $law_notify->NotifyTypeList ) && in_array( '4', $law_notify->NotifyTypeList )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-2', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => ( !empty($lawcases->assign_email)?$lawcases->assign_email:'' )  ]) !!}
                        <label for="send_mail_status-2">ผู้มอบหมาย</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::checkbox('notify_types[]', '2', !empty($law_notify) && is_array( $law_notify->NotifyTypeList ) && in_array( '2', $law_notify->NotifyTypeList )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-3', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => ( !empty($lawcases->create_email)?$lawcases->create_email:'' )  ]) !!}
                        <label for="send_mail_status-3">เจ้าของงานคดี (ผู้แจ้ง)</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::checkbox('notify_types[]', '3', !empty($law_notify) && is_array( $law_notify->NotifyTypeList ) && in_array( '3', $law_notify->NotifyTypeList )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-4', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => ( !empty($lawcases->owner_contact_email)?$lawcases->owner_contact_email:'' )  ]) !!}
                        <label for="send_mail_status-4">ผู้ประสานงานคดี</label>
                    </div>
                    <div class="col-md-12">
                        {!! Form::checkbox('notify_types[]', '99', !empty($law_notify) && is_array( $law_notify->NotifyTypeList ) && in_array( '99', $law_notify->NotifyTypeList )?true:false, ['class'=>'check input_get_mail send_mail_status', 'id' => 'send_mail_status-5', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                        <label for="send_mail_status-5">อื่นๆ(ระบุ)</label>
                    </div>
                </div>
            </div>

            <div class="form-group box_noti_email input_noti_email">
                {!! Form::label('noti_email', 'แจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('noti_email', !empty($law_notify) &&  is_array( $law_notify->Emaillist ) ? implode(',',$law_notify->Emaillist ) :null,  ['class' => 'form-control', 'id'=> 'noti_email' ]) !!}
                </div>
            </div>
            
        </div>

    </div>

</fieldset>

 
<center>
    <div class="form-group">
        <div class="col-md-12">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-cases-manage-products'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/manage-products') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
</center>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
    
    <script type="text/javascript">
        var tableFile = '';
        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            $(".input_number").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            });

            $('.vertical-spin-amount').TouchSpin({
                verticalbuttons: true,
                verticalupclass: 'ti-plus',
                verticaldownclass: 'ti-minus',
                max: 100000000,
                step: 1,
                boostat: 5,
                maxboostedstep: 10
            });

            $('#result_amount').change(function (e) { 
                CalExpireDate($("#result_start_date").val()); 
            });

            $('#result_amount').keyup(function (e) { 
                CalExpireDate($("#result_start_date").val());
            });

            $('#result_start_date').change(function (e) { 
                CalExpireDate($(this).val());
            });

            $('#noti_email').tagsinput({
                // itemText: 'label'
            });

            $(document).on('ifChanged', '.input_get_mail', function(event){
                GetEmailInputChecked();
            });
            GetEmailInputChecked();


        });

        
        function GetEmailInputChecked(){

            var arr = [];
            var remove = [];

            var email_1 = $('#send_mail_status-1').attr("data-email" );
            if( $('#send_mail_status-1:checked').val() && checkNone(email_1) ){
                arr.push(email_1);
            }else{
                if( checkNone(email_1) && arr.indexOf( email_1 ) == -1  ){
                    remove.push(email_1);
                }
            }

            var email_2 = $('#send_mail_status-2').attr("data-email" );
            if( $('#send_mail_status-2:checked').val() && checkNone(email_2)  ){
                arr.push(email_2);
            }else{
                if( checkNone(email_2) && arr.indexOf( email_2 ) == -1  ){
                    remove.push(email_2);
                }
            }

            var email_3 = $('#send_mail_status-3').attr("data-email" );
            if( $('#send_mail_status-3:checked').val() && checkNone(email_3) ){
                arr.push(email_3);
            }else{
                if( checkNone(email_3) && arr.indexOf( email_3 ) == -1  ){
                    remove.push(email_3);
                }
            }

            var email_4 = $('#send_mail_status-4').attr("data-email" );
            if( $('#send_mail_status-4:checked').val() && checkNone(email_4)  ){
                arr.push(email_4);
            }else{
                if( checkNone(email_4) && arr.indexOf( email_4 ) == -1  ){
                    remove.push(email_4);
                }
            } 

            if( arr.length >= 1 ){
                $.each(arr, function( index, data ) {
                    $('#noti_email').tagsinput('add', data );
                });
            }

            if(remove.length >= 1){
                $.each(remove, function( index, data ) {
                    $('#noti_email').tagsinput('remove', data );
                });
            }
            
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }

        function CalExpireDate(date){

            var result = '';
            if( checkNone(date) ){

                var amount = parseInt( $("#result_amount").val() );
                    amount = checkNone(amount) && amount != 0 ?amount:1;
                var dates = date.split("/");
                var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);

                if( checkNone(amount) && !isNaN(amount) ){
                    date_start.setDate(date_start.getDate() + (amount)); // + 1 วัน
                }else{
                    date_start.setDate(date_start.getDate() + 1);
                }
                
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

                var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
                var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
                var DB = str_pad(date_start.getDate());

                result = DB+'/'+MB+'/'+YB;

            }
            return $('#result_end_date').val(result);

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

    </script>
@endpush
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>

        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }
        .alert-primary {
            background-color: #7ab2fa;
            border-color: #ceddfa;
            color: #ffffff;
        }

    </style>
@endpush

<!-- รายละเอียด -->
@include('laws.track.form.detail')

<div class="row">
    <div class="col-md-12">

        @php
            $category_operate =  App\Models\Law\Basic\LawStatusOperation::Where('state',1)->where('law_bs_category_operate_id', 8)->orderbyRaw('CONVERT(id USING tis620)')->pluck('title', 'id');
        @endphp

        <fieldset class="white-box repeater-form">
            <legend class="legend"><h5>ข้อมูลการดำเนินการ</h5></legend>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" width="93%">การดำเนินการ</th>
                        <th class="text-center" width="5%">จัดการ</th>
                    </tr>
                </thead>
                <tbody data-repeater-list="repeater-operation">

                    @if( count($lawtrackreceive->law_track_operation) >= 1 )
                        @foreach(  $lawtrackreceive->law_track_operation as $operation )
                            <tr data-repeater-item>
                                <td class="text-top text-center">
                                    <span class="td_no">1</span>
                                    {!! Form::hidden('operation_id' , !empty($operation->id)?$operation->id:null, ['class' => '' , 'required' => false])   !!}
                                </td>
                                <td>
        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('operation_date', 'วันที่ดำเนินการ', ['class' => 'col-md-12 label-filter']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('operation_date', !empty($operation->operation_date)?HP::revertDate($operation->operation_date,true):null , ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                                    <i class="icon-calender"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {!! Form::label('due_date', 'วันที่ครบกำหนด', ['class' => 'col-md-12 label-filter']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('due_date', !empty($operation->due_date)?HP::revertDate($operation->due_date,true):null , ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                                    <i class="icon-calender"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('status_job_track_id', 'การดำเนินการ', ['class' => 'col-md-12 label-filter']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('status_job_track_id', $category_operate,  !empty($operation->status_job_track_id)?$operation->status_job_track_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true ]) !!}
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('detail', 'รายละเอียด', ['class' => 'col-md-12 label-filter']) !!}
                                            <div class="col-md-12">
                                                {!! Form::textarea('detail', !empty($operation->detail)?$operation->detail:null , ['class' => 'form-control', 'rows' => 3 ]) !!}
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="col-md-2">

                                        @if( !empty($operation->attach_file) )
                                            @php
                                                $attach = $operation->attach_file;
                                            @endphp
                                            <div class="form-group operation_attach">
                                                <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                                    {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                                    {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                                </a>

                                                <a class="btn btn-danger btn-xs m-l-15 show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/track/operation/'.$operation->id.'/edit') ) !!}" title="ลบไฟล์">
                                                     <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        @endif

                                        <div class="form-group operation_file">
                                            {!! Form::label('attach_file', 'ไฟล์แนบ', ['class' => 'col-md-12 label-filter']) !!}
                                            <div class="col-md-12">
                                                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                                    <div class="form-control " data-trigger="fileinput" >
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                        <span class="input-group-text btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="attach_file" class="check_max_size_file">
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
        
                                </td>
                                <td class="text-top text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                                        <i class="fa fa-times"></i>
                                    </button> 
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr data-repeater-item>
                            <td class="text-top text-center">
                                <span class="td_no">1</span>
                                {!! Form::hidden('operation_id' , null, ['class' => '' , 'required' => false])   !!}
                            </td>
                            <td>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('operation_date', 'วันที่ดำเนินการ', ['class' => 'col-md-12 label-filter']) !!}
                                        <div class="col-md-12">
                                            <div class="inputWithIcon">
                                                {!! Form::text('operation_date', null , ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                                <i class="icon-calender"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('due_date', 'วันที่ครบกำหนด', ['class' => 'col-md-12 label-filter']) !!}
                                        <div class="col-md-12">
                                            <div class="inputWithIcon">
                                                {!! Form::text('due_date', null , ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                                <i class="icon-calender"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('status_job_track_id', 'การดำเนินการ', ['class' => 'col-md-12 label-filter']) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('status_job_track_id', $category_operate, null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('detail', 'รายละเอียด', ['class' => 'col-md-12 label-filter']) !!}
                                        <div class="col-md-12">
                                            {!! Form::textarea('detail', null , ['class' => 'form-control', 'rows' => 3 ]) !!}
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="form-group operation_file">
                                        {!! Form::label('attach_file', 'ไฟล์แนบ', ['class' => 'col-md-12 label-filter']) !!}
                                        <div class="col-md-12">
                                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                                <div class="form-control " data-trigger="fileinput" >
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                    <span class="input-group-text btn-file">
                                                        <span class="fileinput-new">เลือกไฟล์</span>
                                                        <span class="fileinput-exists">เปลี่ยน</span>
                                                        <input type="file" name="attach_file" class="check_max_size_file">
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                            </td>
                            <td class="text-top text-center">
                                <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                                    <i class="fa fa-times"></i>
                                </button> 
                            </td>
                        </tr>
                    @endif

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-top text-center">
                            <button type="button" class="btn btn-success btn-sm" data-repeater-create>
                                <i class="fa fa-plus"></i>
                            </button>  
                        </td>
                    </tr>
                </tfoot>
            </table>

        </fieldset>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>สถานะงาน</h5></legend>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('status_job_track_id') ? 'has-error' : ''}}">
                        {!! Form::label('status_job_track_id', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-9">
                            {!! Form::select('status_job_track_id', App\Models\Law\Basic\LawStatusOperation::where('law_bs_category_operate_id', 1)->whereIn('id',[2,3,4])->orderbyRaw('CONVERT(id USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกสถานะ -', 'required' => true ]) !!}
                            {!! $errors->first('status_job_track_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('remarks') ? 'has-error' : ''}}">
                        {!! Form::label('remarks', 'หมายเหตุ'.':', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('remarks', null , ['class' => 'form-control', 'rows' => 4 ]) !!}
                            {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        {!! Form::label('send_mail_status', 'ช่องทางการแจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8 m-t-5">
                            <div class="col-md-6">
                                {!! Form::checkbox('noti_sytem_status', '1',( !is_null($lawtrackreceive) && !empty($lawtrackreceive->noti_sytem_status) ? true:( is_null($lawtrackreceive)?false:null ) ), ['class'=>'check', 'id' => 'noti_sytem_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                                <label for="noti_sytem_status">ผ่านระบบ</label>
                            </div>
                            <div class="col-md-6">
                                {!! Form::checkbox('noti_email_status', '1',( !is_null($lawtrackreceive) && !empty($lawtrackreceive->noti_sytem_status) ? true:( is_null($lawtrackreceive)?false:null )), ['class'=>'check box_mail_status', 'id' => 'noti_email_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                                <label for="noti_email_status">ผ่านอีเมล</label>
                            </div>
                        </div>
                    </div>

                    @php
                        
                        $email_assign = null;
                        if( isset($lawtrackreceive->users_assign) && ($lawtrackreceive->users_assign->count() >= 1)  ){

                            $email_assign = $lawtrackreceive->users_assign->pluck('reg_email','reg_email')->implode(','); 
                        }

                    @endphp
                    <div class="form-group box_noti_email">
                        {!! Form::label('send_mail_status', 'แจ้งเตือนไปยัง'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8 m-t-5">

                            <div class="col-md-6">
                                {!! Form::checkbox('send_mail_status[]', 'assign',is_array( $lawtrackreceive->send_mail_status ) && in_array( 'assign', $lawtrackreceive->send_mail_status )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-1', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => (!empty($email_assign)?$email_assign:'') ]) !!}
                                <label for="send_mail_status-1">ผู้รับมอบหมาย</label>
                            </div>
                            <div class="col-md-6">
                                {!! Form::checkbox('send_mail_status[]', 'other',is_array( $lawtrackreceive->send_mail_status ) && in_array( 'other', $lawtrackreceive->send_mail_status )?true:false, ['class'=>'check input_get_mail send_mail_status', 'id' => 'send_mail_status-2', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                                <label for="send_mail_status-2">อื่นๆ(ระบุ)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group box_noti_email input_noti_email">
                        {!! Form::label('noti_email', 'แจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('noti_email', is_array( $lawtrackreceive->noti_email ) ? implode(',',$lawtrackreceive->noti_email ) :null,  ['class' => 'form-control', 'id'=> 'noti_email', 'disabled' => true ]) !!}
                        </div>
                    </div>


                </div>
            </div>


        </fieldset>

    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-track-operation'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/track/operation') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>


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

    <script type="text/javascript">
        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            $('.clockpicker').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                donetext: 'Done',
                default: 'now'
            });

            //เพิ่มลบไฟล์แนบ
            $('.repeater-form').repeater({
                show: function () {
                    $(this).slideDown();

                    $('.mydatepicker').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                    });

                    reBuiltSelect2($(this).find('select'));
                    $(this).find('.operation_attach').remove();

                    OrderTdNo();
                    BtnDeleteFile();

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    BtnDeleteFile();

                    OrderTdNo();
                    ShowInputFile();
                }
            });

            BtnDeleteFile();
            $('.box_mail_status').on('ifChanged', function(event){
                box_mail_status();
            });
            box_mail_status();

            $('#noti_email').tagsinput({
                // itemText: 'label'
            });

            $('.send_mail_status').on('ifChanged', function(event){
                send_mail_status();
            });
            send_mail_status();

            $('#send_mail_status-1').on('ifChanged', function(event){
                GetAssignEmail();
            });


            OrderTdNo();
            ShowInputFile();
        });

        function box_mail_status(){
            var noti_email = $('.box_noti_email');

            if($('.box_mail_status:checked').val()){
                noti_email.show();
                noti_email.find('input').prop('disabled', false);
            }else{
                noti_email.hide();
                noti_email.find('input').prop('disabled', true);
                $('.input_get_mail').iCheck('uncheck');
            }
        }

        function send_mail_status(){
            var noti_email = $('.input_noti_email');

            if($('.send_mail_status:checked').val()){
                noti_email.show();
                noti_email.find('input').prop('readonly', false);
            }else{
                noti_email.hide();
                noti_email.find('input').prop('readonly', true);
            }
        }

        function GetAssignEmail(){

            var input = $('#send_mail_status-1');

            if($('#send_mail_status-1:checked').val()){
                $('#noti_email').tagsinput('add', input.data('email') );
            }else{

                var email = input.data('email');
                var object = email.split(',');
                $.each(object, function( index, data ) {
                    $('#noti_email').tagsinput('remove', data );
                });
              
            }

        }

        function reBuiltSelect2(select){

            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }
        function BtnDeleteFile(){

            if( $('.btn_file_remove').length <= 1 ){
                $('.btn_file_remove:first').hide();   
                $('.btn_file_add:first').show();  
            }else{
                $('.btn_file_remove').show();
            }
 
            check_max_size_file();
        }

        function OrderTdNo(){
            $('.td_no').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function ShowInputFile(){
            $('.operation_attach').each(function(index, el) {
                var row = $(el).parent();
                row.find('.operation_file').hide();
            });
        }
    </script>
@endpush
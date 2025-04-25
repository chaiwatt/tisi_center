@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

    <style>
        .border-left {
            border-left: 1px solid #dee2e6 !important;
        }

    </style>
@endpush


<div class="row">
    <div class="col-md-7">

        <p><h4>ข้อมูลรายละเอียด</h4></p>

        @php
            $option_case = App\Models\Law\Cases\LawCasesForm::whereNotNull('case_number')->select(DB::raw('CONCAT(case_number," | ", owner_name ) AS title'), 'id')->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
        @endphp
        
        <div class="form-group required">
            {!! Form::label('law_case_id', 'เลขคดี'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::select('law_case_id',$option_case  , null, ['class' => 'form-control', 'placeholder'=>'- เลือกเลขคดี -', 'required' => true ]) !!}
                {!! $errors->first('law_case_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required">
            {!! Form::label('send_type', 'ประเภท'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-6">
                {!! Form::select('send_type', App\Models\Law\Basic\LawDelivery::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') , null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภท -', 'required' => true ]) !!}
                {!! $errors->first('send_type', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon">ครั้งที่</span>
                    {!! Form::text('send_no', null , ['class' => 'form-control ', 'readonly' => 'readonly','id'=>'send_no']) !!}
                </div>
            </div>
        </div>

        <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
            {!! Form::label('title', 'เรื่อง'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::text('title', null , ['class' => 'form-control ', 'required' => 'required']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('send_to') ? 'has-error' : ''}}">
            {!! Form::label('send_to', 'เรียน'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::text('send_to', null , ['class' => 'form-control ']) !!}
                {!! $errors->first('send_to', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required{{ $errors->has('attach_show') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('attach_show', 'ไฟล์เเนบ'.' : ', ['class' => 'col-md-3 control-label font-medium-6'])) !!}
            <div class="col-md-9 m-t-5" >
                <span class="text-muted">อัพโหลดได้เฉพาะไฟล์ .pdf, .docx หรือ .xlsx ไฟล์ละไม่เกิน 8 MB </span>
            </div>
        </div>

        @if( isset($lawcasesdelivery->file_law_cases_delivery) && ($lawcasesdelivery->file_law_cases_delivery->count() >= 1) )
            <div class="form-group">
                <div class="col-md-9 col-md-offset-3">

                    @foreach ($lawcasesdelivery->file_law_cases_delivery as $Ifile )
                        <div class="row form-group" >
                            <div class="col-md-4">
                                {!! Form::text('attach_description_show',  !empty($Ifile->caption)?$Ifile->caption:null, ['class' => 'form-control', 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                            </div>
                            <div class="col-md-8">
                                <a href="{!! HP::getFileStorage($Ifile->url) !!}" target="_blank" class="m-l-5">
                                    {!! !empty($Ifile->filename) ? $Ifile->filename : '' !!}
                                    {!! HP::FileExtension($Ifile->filename)  ?? '' !!}
                                </a>
                                <a class="btn btn-danger btn-xs show_tag_a m-l-5" href="{!! url('law/delete-files/'.($Ifile->id).'/'.base64_encode('law/cases/delivery/'.$lawcasesdelivery->id.'/edit') ) !!}" title="ลบไฟล์">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>  
                    @endforeach

                </div>
            </div>
        @endif

        <div class="form-group repeater-form-file">
            <div class="col-md-9 col-md-offset-3" data-repeater-list="repeater-attach">

                <div class="row form-group"  data-repeater-item>
                    <div class="col-md-4">
                        {!! Form::text('attach_description', null, ['class' => 'form-control', 'placeholder' => 'คำอธิบาย']) !!}
                    </div>
                    <div class="col-md-4">
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
                        <input type="hidden" name="attachfilein_id" value="">
                        <div class="file_in_case_db"></div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                            <i class="fa fa-times"></i>
                        </button> 
                        <button type="button" class="btn btn-success btn-sm btn_file_add" data-repeater-create>
                            <i class="fa fa-plus"></i>
                        </button>  
                        <button type="button" class="btn btn-info btn-sm btn_file_law" value="">
                            เลือกไฟล์จากฐานข้อมูล
                        </button> 
                    </div>
                </div>
  
            </div>
        </div>

        <div class="form-group required{{ $errors->has('condition') ? 'has-error' : ''}}">
            {!! Form::label('condition', 'เงื่อนไข'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                <label class="m-r-10">{!! Form::radio('condition', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'condition_1']) !!} ตอบกลับ</label>
                <label>{!! Form::radio('condition', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'condition_2']) !!} ไม่ต้องตอบกลับ</label>
                {!! $errors->first('condition', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}} box_condition_response">
            {!! Form::label('date_due', 'วันที่ครบกำหนด'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-6">
                <div class="inputWithIcon">
                    {!! Form::text('date_due', !empty($lawcasesdelivery->date_due)? HP::revertDate($lawcasesdelivery->date_due, true) : null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required'] ) !!}
                    <i class="icon-calender"></i>
                </div>
                {!! $errors->first('date_due', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required{{ $errors->has('attach_response') ? 'has-error' : ''}} box_condition_response">
            {!! Form::label('attach_response', 'สิ่งที่ต้องตอบกลับ'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::textarea('attach_response', null , ['class' => 'form-control ', 'required' => 'required', 'rows' => 4]) !!}
                {!! $errors->first('attach_response', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group{{ $errors->has('remark') ? 'has-error' : ''}}">
            {!! Form::label('remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::textarea('remark', null , ['class' => 'form-control ', 'rows' => 4]) !!}
                {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::text('created_by_show', !empty($lawcasesdelivery->created_by)? $lawcasesdelivery->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-3 control-label font-medium-6']) !!}
            <div class="col-md-9">
                {!! Form::text('created_by_show',  !empty($lawcasesdelivery->created_at)? HP::revertDate($lawcasesdelivery->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>

    </div>
    <div class="col-md-5 mb-md-0 mb-4 border-left">
        <p><h4>ข้อมูลการจัดส่ง</h4></p>

        <div class="form-group">
            {!! Form::label('send_mail_status', 'ช่องทางการแจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8 m-t-5">
                <div class="col-md-6">
                    {!! Form::checkbox('noti_sytem_status', '1',( !empty($lawcasesdelivery) && !empty($lawcasesdelivery->noti_sytem_status) ? true:( empty($lawcasesdelivery)?false:null ) ), ['class'=>'check', 'id' => 'noti_sytem_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                    <label for="noti_sytem_status">ผ่านระบบ</label>
                </div>
                <div class="col-md-6">
                    {!! Form::checkbox('noti_email_status', '1',( !empty($lawcasesdelivery) && !empty($lawcasesdelivery->noti_sytem_status) ? true:( empty($lawcasesdelivery)?false:null )), ['class'=>'check box_mail_status', 'id' => 'noti_email_status', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                    <label for="noti_email_status">ผ่านอีเมล</label>
                </div>
            </div>
        </div>

        <div class="form-group box_noti_email">
            {!! Form::label('send_mail_status', 'แจ้งเตือนไปยัง'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8 m-t-5">
                <div class="col-md-12">
                    {!! Form::checkbox('send_mail_status[]', 'offender', !empty($lawcasesdelivery) && is_array( $lawcasesdelivery->send_mail_status ) && in_array( 'offender', $lawcasesdelivery->send_mail_status )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-1', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => '' ]) !!}
                    <label for="send_mail_status-1">ผู้กระทำผิด</label>
                </div>
                <div class="col-md-12">
                    {!! Form::checkbox('send_mail_status[]', 'assign', !empty($lawcasesdelivery) && is_array( $lawcasesdelivery->send_mail_status ) && in_array( 'assign', $lawcasesdelivery->send_mail_status )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-2', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => '' ]) !!}
                    <label for="send_mail_status-2">ผู้มอบหมาย</label>
                </div>
                <div class="col-md-12">
                    {!! Form::checkbox('send_mail_status[]', 'owner', !empty($lawcasesdelivery) && is_array( $lawcasesdelivery->send_mail_status ) && in_array( 'owner', $lawcasesdelivery->send_mail_status )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-3', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => '' ]) !!}
                    <label for="send_mail_status-3">เจ้าของงานคดี (ผู้แจ้ง)</label>
                </div>
                <div class="col-md-12">
                    {!! Form::checkbox('send_mail_status[]', 'coordinator', !empty($lawcasesdelivery) && is_array( $lawcasesdelivery->send_mail_status ) && in_array( 'coordinator', $lawcasesdelivery->send_mail_status )?true:false, ['class'=>'check input_get_mail', 'id' => 'send_mail_status-4', 'data-checkbox'=>'icheckbox_minimal-blue','data-email' => '' ]) !!}
                    <label for="send_mail_status-4">ผู้ประสานงานคดี</label>
                </div>
                <div class="col-md-12">
                    {!! Form::checkbox('send_mail_status[]', 'other', !empty($lawcasesdelivery) && is_array( $lawcasesdelivery->send_mail_status ) && in_array( 'other', $lawcasesdelivery->send_mail_status )?true:false, ['class'=>'check input_get_mail send_mail_status', 'id' => 'send_mail_status-5', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                    <label for="send_mail_status-5">อื่นๆ(ระบุ)</label>
                </div>
            </div>
        </div>

        <div class="form-group box_noti_email input_noti_email">
            {!! Form::label('noti_email', 'แจ้งเตือน'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('noti_email', !empty($lawcasesdelivery) &&  is_array( $lawcasesdelivery->noti_email ) ? implode(',',$lawcasesdelivery->noti_email ) :null,  ['class' => 'form-control', 'id'=> 'noti_email', 'disabled' => true ]) !!}
            </div>
        </div>

        @if( !empty($lawcasesdelivery->response_date) )
            <hr>
            <p><h4>ข้อมูลการตอบกลับ</h4></p>

            <div class="form-group m-0">
                <label class="control-label text-right col-md-3">ไฟล์แนบ :</label>
                <div class="col-md-9">

                    @if( isset($lawcasesdelivery->file_law_cases_response) && ($lawcasesdelivery->file_law_cases_response->count() >= 1) )
                        @foreach ($lawcasesdelivery->file_law_cases_response as $kf => $Rfile )
                            <p class="form-control-static">  
                                {!! '('.($kf+1).') '.!empty($Rfile->caption)?$Rfile->caption:'-' !!}

                                <a href="{!! HP::getFileStorage($Rfile->url) !!}" target="_blank" class="m-l-5">
                                    {!! !empty($Rfile->filename) ? $Rfile->filename : '' !!}
                                </a>
                            </p>
                        @endforeach
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('response_remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::textarea('response_remark', null , ['class' => 'form-control', 'rows' => 4 , 'disabled' => true ]) !!}
                    {!! $errors->first('response_remark', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('response_name', 'ชื่อ-สกุล'.' : ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('response_name', null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('response_email', 'อีเมล'.' : ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('response_email', null , ['class' => 'form-control', 'disabled' => true ]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('response_tel', 'เบอร์โทร'.' : ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('response_tel', null , ['class' => 'form-control', 'disabled' => true ]) !!}
                </div>
            </div>

            
            <div class="form-group">
                {!! Form::label('response_date', 'วันที่ตอบกลับ'.' : ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('response_date', !empty($lawcasesdelivery->response_date)? HP::revertDate($lawcasesdelivery->response_date, true):null , ['class' => 'form-control', 'disabled' => true ]) !!}
                </div>
            </div>

        @endif
    </div>

</div>

<center>
    <div class="form-group">
        <div class="col-md-12">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-cases-delivery'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/delivery') }}">
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

            //เพิ่มลบไฟล์แนบ
            $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {

                    if( confirm("ยืนยันการลบข้อมูลแถวนี้?") ){
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 400);
                    }
               
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

            $('input[name=condition]').on('ifChecked', function(event){
                BoxCondition();
            });
            BoxCondition();

            tableFile = $('#MyTable-File').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/cases/delivery/data_file_list') !!}',
                    data: function (d) {
                        d.law_case_id   = $('#law_case_id').val();
                        d.filter_search = $('#filter_search').val();
                        d.input_other_row = $('#input_other_row').val();
                        
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'file_name', name: 'file_name' },
                    { data: 'file_cation', name: 'file_cation' },
                    { data: 'file_create_at', name: 'file_create_at' },
                    { data: 'action', name: 'action' }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                }
            });

            $('#law_case_id').change(function (e) { 

                $('#send_mail_status-1').attr("data-email", "");
                $('#send_mail_status-2').attr("data-email", "");
                $('#send_mail_status-3').attr("data-email", "");
                $('#send_mail_status-4').attr("data-email", "");

                $('#send_to').val('');
                 
                if( $(this).val() != '' ){
                    $.ajax({
                        url: "{!! url('/law/cases/delivery/get-law-cases') !!}" + "/" + $(this).val()
                    }).done(function( object ) {

                        if( checkNone(object) ){

                            $('#send_mail_status-1').attr("data-email",  object.owner_email );
                            $('#send_mail_status-2').attr("data-email",  object.assign_email );
                            $('#send_mail_status-3').attr("data-email",  object.create_email );
                            $('#send_mail_status-4').attr("data-email",  object.owner_contact_email );

                        }

                    });


                    var lean = $(this).find('option:selected').text();
                    var explode = lean.split('|');
                    $('#send_to').val( $.trim(explode[1]) );

                    tableFile.draw();

                }
                
            });

            @if (isset($lawcasesdelivery->id))
                $('#law_case_id').change();    
            @endif


            $(document).on('ifChanged', '.input_get_mail', function(event){
                GetEmailInputChecked();
            });
            GetEmailInputChecked();

            $('#send_type').change(function (e) { 
                loadSendNo();     
            });

            $('body').on('click', '.btn_file_law', function(){
            
                $('#input_other_row').val($(this).val());
                tableFile.draw();
                $('#ModalFile').modal('show');
                
            });
        });

        function loadSendNo(){

            var id   = $('#law_case_id').val();
            var type = $('#send_type').val();

            $('#send_no').val("");

            if( id != '' ){
                $.ajax({
                        url: "{!! url('/law/cases/delivery/get-send-no') !!}" + "?law_case_id=" + id  + "&send_type=" +type
                }).done(function( object ) {

                    if( checkNone(object) ){
                        $('#send_no').val(object);
                    }

                });
            }
        }

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
            return value !== '' && value !== null && value !== undefined;
        }

        function BoxCondition(){
            var condition =  ($("input[name=condition]:checked").val() == 1 )?'1':'2';
            if( condition == 1){
                $('.box_condition_response').show();
                $('.box_condition_response').find('input, textarea').prop('disabled', false);

                $('.box_condition_response').find('input, textarea').prop('required', true);
            }else{
                $('.box_condition_response').hide();
                $('.box_condition_response').find('input, textarea').prop('disabled', true);
                $('.box_condition_response').find('input, textarea').prop('required', false);  
            }
        }

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
                noti_email.show();
                noti_email.find('input').prop('readonly', true);
            }

            if( $('.box_mail_status:checked').val()){
                $('.input_noti_email').show();
            }else{
                $('.input_noti_email').hide();
            }

        }

        function BtnDeleteFile(){

            $('.btn_file_remove:first').hide();   
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }

            console.log($('.btn_file_add').length);
            if(  $('button.btn_file_add').length == 0 ){
                $('button.btn_file_remove:first').before('<button type="button" class="btn btn-success btn-sm btn_file_add" data-repeater-create><i class="fa fa-plus"></i></button>');
            }
            $('.btn_file_add:first').show();   
            check_max_size_file();

            var i = 0;
            $('.btn_file_law').each(function (index, rowId) {
                $(rowId).val(++i);
            });
            
        }
    </script>
@endpush
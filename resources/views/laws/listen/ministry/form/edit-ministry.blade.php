<div class="col-md-10 offset-md-1">

    <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'ชื่อเรื่องประกาศ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('title', null, ['class' => 'form-control ', 'required' => 'required']) !!}
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group {{ $errors->has('dear') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('dear', 'เรียน <span class=" text-danger" id="label_dear">*</span>', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
            {!! Form::text('dear', null, ['class' => 'form-control']) !!}
            {!! $errors->first('dear', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required{{ $errors->has('date_start') ? 'has-error' : ''}}">
        {!! Form::label('date_start', 'วันที่ประกาศ'.':', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_start', null, ('required' == 'required') ? ['class' => 'form-control mydatepicker',
                'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                {!! $errors->first('date_start', '<p class="help-block">:message</p>') !!}
                 <i class="icon-calender"></i>
            </div>
        </div>
        {!! Form::label('date_end', 'วันที่ปิดประกาศ', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_end', null, ('required' == 'required') ? ['class' => 'form-control mydatepicker',
                'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                {!! $errors->first('date_end', '<p class="help-block">:message</p>') !!}
                 <i class="icon-calender"></i>
            </div>
        </div>
    </div>
    
    <div class="form-group required{{ $errors->has('date_due') ? 'has-error' : ''}}">
        {!! Form::label('amount', 'แสดงความเห็นได้ภายใน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-2">
            <div class="input-group">
                {!! Form::text('amount', null, ['class' => 'form-control text-center amount', 'required' => 'required']) !!}
                <span class="input-group-addon bg-info b-0 text-white"> วัน </span>
                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        {!! Form::label('date_due', 'วันครบกำหนดให้ความเห็น'.':', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_due', null, ('required' == 'required') ? ['class' => 'form-control mydatepicker',
                'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                {!! $errors->first('date_due', '<p class="help-block">:message</p>') !!}
                 <i class="icon-calender"></i>
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('url_type') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('url_type', 'แบบรับฟังความเห็น', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-2">
            {!! Form::radio('url_type', '1', true, ['class' => 'form-control check url_type', 'data-radio' => 'iradio_flat-orange', 'id'=>'url_type-1', 'required' => false]) !!}
            {!! Html::decode(Form::label('url_type-1', 'ดึงจากระบบ', ['class' => 'control-label text-capitalize'])) !!}
        </div>
        <div class="col-md-2">
            {!! Form::radio('url_type', '2', null, ['class' => 'form-control check url_type', 'data-radio' => 'iradio_flat-orange', 'id'=>'url_type-2', 'required' => false]) !!}
            {!! Form::label('url_type-2', 'ระบุเอง', ['class' => 'control-label text-capitalize']) !!}
        </div>
    </div>
    
    <div class="form-group  {{ $errors->has('ref_no') ? 'has-error' : ''}}">
        {!! Form::label('url', 'url', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            <div class="input-group">
                {!! Form::text('url',!empty($lawlistministry->url)?$lawlistministry->url:$url, ['class' => 'form-control', 'readonly'=>true , 'placeholder'=>'ระบุ url', 'id'=>"url"]) !!}
                {!! Form::hidden('url_text',$url, ['class' => 'form-control', 'disabled'=>false ,'id'=>"url_text"]) !!}
                <span class="input-group-btn">
                    <a type="button" class="btn waves-effect waves-light btn-inverse copy-clipboard" data-toggle="tooltip" data-placement="right" title="" data-original-title="คัดลอกไปคลิปบอร์ด"><i class="fa fa-clipboard"></i></a>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-4 col-md-8">
            <small class="text-warning">อัพโหลดได้เฉพาะไฟล์ .jpg .docx .png .xlsx และ.pdf ขนาดไฟล์ละไม่เกิน 8 MB </small>
        </div>
    </div>

    @php
        //ประกาศรับฟังความเห็น
        $attachs_listen_ministry   = $lawlistministry->AttachFileListenMinistry;
        //ร่างกฏกระทรวง
        $attachs_draft_ministerial = $lawlistministry->AttachFileDraftMinisterial;
        //ร่างมาตรฐาน
        $attachs_draft_standard    = $lawlistministry->AttachFileDraftStandard;
        //ไฟล์เเนบ
        $attachs_other             = $lawlistministry->AttachFileOther;
    @endphp

    <div class="form-group required{{ $errors->has('file_listen_ministry') ? 'has-error' : ''}}">
        {!! Form::label('file_listen_ministry', 'ประกาศรับฟังความเห็น'.':', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            @if( !empty( $attachs_listen_ministry ) )
                <a href="{!! HP::getFileStorage($attachs_listen_ministry->url) !!}" target="_blank" class="m-t-5">
                    {!! !empty($attachs_listen_ministry->filename) ? $attachs_listen_ministry->filename : '' !!}
                    {!! HP::FileExtension($attachs_listen_ministry->url) ?? '' !!}
                </a>
                <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($attachs_listen_ministry->id).'/'.base64_encode('law/listen/ministry/'.$lawlistministry->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            @else
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="file_listen_ministry" class="check_max_size_file" required>
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group required{{ $errors->has('file_draft_ministerial') ? 'has-error' : ''}}">
        {!! Form::label('file_draft_ministerial', 'ร่างกฏกระทรวง'.':', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            @if( !empty( $attachs_draft_ministerial ) )
                <a href="{!! HP::getFileStorage($attachs_draft_ministerial->url) !!}" target="_blank" class="m-t-5">
                    {!! !empty($attachs_draft_ministerial->filename) ? $attachs_draft_ministerial->filename : '' !!}
                    {!! HP::FileExtension($attachs_draft_ministerial->url) ?? '' !!}
                </a>
                <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($attachs_draft_ministerial->id).'/'.base64_encode('law/listen/ministry/'.$lawlistministry->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

            @else   
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="file_draft_ministerial" class="check_max_size_file" required>
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('file_draft_standard') ? 'has-error' : ''}}">
        {!! Form::label('file_draft_standard', 'ร่างมาตรฐาน'.':', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            @if( !empty( $attachs_draft_standard ) )
                <a href="{!! HP::getFileStorage($attachs_draft_standard->url) !!}" target="_blank" class="m-t-5">
                    {!! !empty($attachs_draft_standard->filename) ? $attachs_draft_standard->filename : '' !!}
                    {!! HP::FileExtension($attachs_draft_standard->url) ?? '' !!}
                </a>
                <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($attachs_draft_standard->id).'/'.base64_encode('law/listen/ministry/'.$lawlistministry->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            @else  
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="file_draft_standard" class="check_max_size_file" >
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('file_other') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('file_other', 'ไฟล์เเนบ'.'<div><span class="text-muted m-b-30 font-14"><i>(เพิ่มได้ไม่เกิน 5 ไฟล์)</i></span></div>', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8 repeater-form-file">

            @if ( !empty($attachs_other) && count($attachs_other) >= 1 )
                @foreach ($attachs_other as $attach)
                    <p>     
                        <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank" class="file_max">
                            {!! !empty($attach->caption) ? $attach->caption : '' !!}
                            {!! HP::FileExtension($attach->filename)  ?? '' !!}
                        </a>
                        <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/listen/ministry/'.$lawlistministry->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </p>
                @endforeach
            @endif

            <div class="row" data-repeater-list="repeater-attach">
                <div class="repeater_form_file4" data-repeater-item>
                    <div class="col-md-6">
                        {!! Form::text('file_desc', null,['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-5">
                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                            <div class="form-control " data-trigger="fileinput" >
                                <span class="fileinput-filename"></span>
                            </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                <span class="input-group-text btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="file_other" class="check_max_size_file file_max">
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success btn-sm btn-outline btn_file_add" data-repeater-create>
                            <i class="fa fa-plus"></i>
                        </button>  
                        <button class="btn btn-danger btn-sm btn_file_remove btn-outline" data-repeater-delete type="button">
                            <i class="fa fa-times"></i>
                        </button>              
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group{{ $errors->has('state') ? 'has-error' : ''}}">
        {!! Form::label('state', 'เปิดรับฟังความเห็น'.':', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::checkbox('state', '1', (!empty($lawlistministry->state)?$lawlistministry->state:true), ['data-color'=>'#13dafe' , 'id'=>'state']) !!}
            {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    @php
        $comment_point_list = App\Models\Law\Listen\LawListenMinistryResponse::list_comment_point();
        $responses_types = !empty($lawlistministry->responses_type) ? json_decode($lawlistministry->responses_type):[];

    @endphp

    <div class="form-group image_qr{{ $errors->has('type') ? 'has-error' : ''}}">
        {!! Form::label('type', 'ประเภทการตอบความเห็น:', ['class' => 'col-md-4 control-label ']) !!}
        <div class="col-md-8" id="comment">
            @if (count($comment_point_list) > 0 )
                @foreach ($comment_point_list as $responses_type=> $comment_point)
                @php
                if(count($responses_types)){
                    if(in_array($responses_type,$responses_types)){
                        $checked = ' checked ';
                    }else{
                        $checked = '';
                    }
                }else{
                    if(($responses_type == 1 || $responses_type == 2 )){
                        $checked = ' checked ';
                    }else{
                        $checked = '';
                    }
                }
                @endphp

                    <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                        <input type="checkbox" class="check comment_point" value="{{$responses_type}}" id="comment_point-{{$responses_type}}" name="responses_type[]"  data-radio="icheckbox_square-green" {!! $checked !!}>
                        <label for="comment_point-{{$responses_type}}"> &nbsp; {!! array_key_exists($responses_type,$comment_point_list)?$comment_point_list[$responses_type]:null !!} </label>   
                    </div>  
                @endforeach
            @endif
        </div>
    </div>

    <div class="form-group required{{ $errors->has('mail_status') ? 'has-error' : ''}}">
        {!! Form::label('mail_status', 'แจ้งเตือน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            <label>{!! Form::radio('mail_status', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} แจ้งอีเมล&nbsp;&nbsp;&nbsp;</label>
            <label>{!! Form::radio('mail_status', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ไม่แจ้ง</label>
            {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
        </div>
    </div>    
    <div class="form-group {{ $errors->has('mail_list') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('', '', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-6">
            {!! Form::text('mail_list', null,  ['class' => 'form-control tag', 'id'=>'mail_list', 'data-role' => "tagsinput"]) !!}
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal"> เลือกจากฐานข้อมูล</button>
        </div>
    </div>
</div>

    <div class="col-md-2">
        <div class="form-group {{ $errors->has('status_dear') ? 'has-error' : ''}}" style="margin-top: 60px;">
            <div class="col-md-12">
                <input type="checkbox" class="check status_dear" id="status_dear"  value="1" name="status_dear" data-checkbox="icheckbox_square-green"  @if(!empty($lawlistministry->status_dear) && $lawlistministry->status_dear == 1) checked @endif>
                            <label for="access_tisi-1">แสดงในอีเมลทุกฉบับ</label>
                            <div><span class="text-muted m-b-30 font-12">(กรณีไม่เลือกจะเเสดงชื่อหน่วยงาน)</span></div>
            </div>
        </div>

        <div class="form-group  image_qr{{ $errors->has('ref_no') ? 'has-error' : ''}}" style="margin-top: 120px;">
            <a href="{{ $url }}"  target="_blank"><img src="data:image/png;base64, {!! base64_encode($image_qr) !!} " width="115cm" ></a>
        </div>
        <div class="form-group font-medium-4 image_qr"  style="margin-top: -25px;">
            <a href="{{ $url }}"  target="_blank"> Dowload QR Code</a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-4 col-md-3">
    
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-listen-ministry'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/listen/ministry') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>



@include('laws.listen.ministry.modals.modal-mail')

@push('js')
    <script>
        $(document).ready(function() {

            // Switchery
            $("#state").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $('.url_type').on('ifChanged', function(event){
                if($('#url_type-2').is(':checked')){
                    $('#url').val('');
                } else {
                    $('#url').val($('#url_text').val());
                }
                ifChangedCheckedAccess();
                    
            });
            ifChangedCheckedAccess();
     
            $('.status_dear').on('ifChanged', function(event){
                ifChangedCheckedDear();
            });
            ifChangedCheckedDear();

            $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 500);
                    }
                }
            });

            BtnDeleteFile();
            check_max_size_file();
            ResetTableNumber();

            //ลบแถว
            $('body').on('click', '.remove-row', function(){
                $(this).parent().parent().remove();
                ResetTableNumber();
                data_list_disabled();
            });
            
            $('#btn-modal').click(function(){ //เลือกรายชื่อส่งเมล
                $('.item_checkbox:checked').each(function(index, element){
                    $("#mail_list").tagsinput('add', $(element).data('email'));
                });
                $('#exampleModal').modal('hide');
            });

            $('body').on("keyup change", "#amount",function (event) {//คำนวณวันที่
                var amountDate = $(this).val();
                if(amountDate && $('#date_start').val()!=""){
                    var newDate = CalExpireDate(  $('#date_start').val() );
                    $('#date_end').val(newDate);
                    $('#date_due').val(newDate);
                }else{
                    $('#date_end').val('');
                    $('#date_due').val('');
                }
            });

            $('#date_start').change(function (e) { 
                CalExpireDate($(this).val());
            });

            //คัดลอกไปคลิปบอร์ด
            $('.copy-clipboard').click(function(event) {

                var selector = $(this).closest('.input-group').find('input');

                selector.select();
                selector[0].setSelectionRange(0, 99999);
                document.execCommand("copy");

                $.toast({
                    heading: 'สำเร็จ',
                    text: 'คัดลอกไปคลิปบอร์ดสำเร็จ',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'info',
                    hideAfter: 3000,
                    stack: 6
                });
            });
        });
        
        function ifChangedCheckedAccess(){
            if($('#url_type-2').is(':checked')){
                $('#url').prop('readonly', false);
                $('#url').prop('required', true);
                $('.image_qr').hide(500);
            } else {
                $('#url').prop('readonly', true);
                $('#url').prop('required', false);
                $('#url').val($('#url_text').val());
                $('.image_qr').show(500);
            }
        }

        function ifChangedCheckedDear(){
            if( $("input[name='status_dear']:checked").val() == 1){
                $('#dear').prop('required', true);
                $('#label_dear').show();
            } else {
                $('#dear').prop('required', false);
                $('#label_dear').hide();
            }
        }
        
        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            } 
              $('.btn_file_remove:first').hide();   
              $('.btn_file_add:first').show();   
              check_max_size_file();

            if( $('.file_max').length >= 5 ){//เพิ่มได้ไม่เกิน 5 ไฟล์
                $('.btn_file_add:first').prop('disabled', true); 
            }else{
                $('.btn_file_add:first').prop('disabled', false); 
            }
         }

        function ResetTableNumber(){
            var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
        }    

        function CalExpireDate(date){

            var result = '';
            if( checkNone(date) ){

                var amount = parseInt( $("#amount").val() );
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
            return result;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

    </script>
@endpush
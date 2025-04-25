@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <style>
        .hr-sect {
            display: flex;
            flex-basis: 100%;
            align-items: center;
            font-size: 24px;
            margin: 15px 0px;
        }
        .hr-sect:before {
            content: "";
            flex-grow: 1;
            background: rgba(122, 122, 122, 0.35);
            height: 1px;
            font-size: 0px;
            line-height: 0px;
            margin: 0px 15px;
        }
        .hr-sect:after {
            content: "";
            flex-grow: 15;
            background: rgba(122, 122, 122, 0.35);
            height: 1px;
            font-size: 0px;
            line-height: 0px;
            margin: 0px 15px;
        }
        .round-button {
            display:block;
            width:40px;
            height:40px;
            line-height:35px;
            border: 1.5px solid #00CFDD;
            border-radius: 50%;
            color:#f5f5f5;
            text-align:center;
            text-decoration:none;
            background: #555777;
            box-shadow: 0 0 3px gray;
            font-size:35px;
            /* font-weight:bold; */
        }

    </style>
@endpush

<div class="form-group  required{{ $errors->has('listen_id') ? 'has-error' : ''}}">
    {!! Form::label('listen_id', 'ชื่อเรื่องประกาศ:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('listen_id', App\Models\Law\Listen\LawListenMinistry::orderbyRaw('CONVERT(title USING tis620)')->where('state',1)->pluck('title', 'id'), !empty($lawlistministry->id)?$lawlistministry->id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อเรื่องที่ประกาศ -', 'required' => true, 'id' => 'listen_id', 'disabled'=> !empty($lawlistministry->id)? true : false ]) !!}
        @if (!empty($lawlistministry->id))
        {!! Form::hidden('listen_id',  !empty($lawlistministry->id)?$lawlistministry->id:null, ['class' => 'form-control']) !!}
        @endif
        {!! $errors->first('listen_id', '<p class="help-block">:message</p>') !!}
    </div>
    @if (empty($lawlistministry->id))
    <div class="col-md-1">
        <button type="button" class=" round-button btn-light-info" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="สามารถเลือกได้ประกาศที่ยังไม่ปิดรับฟังความเห็นเท่านั่น">!</button>
    </div>
    @endif
</div>

<div class="form-group  required{{ $errors->has('tis_no') ? 'has-error' : ''}}">
    {!! Form::label('tis_no', 'เลข มอก. :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-4">
       <span class="font-medium-6" id="tis_no"> {!! !empty($lawlistministry->tis_no)?$lawlistministry->tis_no:null !!}</span>
    </div>
</div>

<div class="form-group  required{{ $errors->has('tis_name') ? 'has-error' : ''}}">
    {!! Form::label('tis_name', 'ชื่อ มอก. (TH) :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-4">
        <span class="font-medium-6" id="tis_name"> {!! !empty($lawlistministry->tis_name)?$lawlistministry->tis_name:null !!}</span>
    </div>
</div>

<div class="form-group  required{{ $errors->has('remark') ? 'has-error' : ''}}">
    {!! Form::label('remark', 'รายละเอียดประกาศ :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-3">
        <span class="font-medium-6" id="remark"> {!! !empty($lawlistministry->remark)?$lawlistministry->remark:null !!}</span>
    </div>
    {!! Form::label('date_due', 'วันครบกำหนดแบบตอบรับฟัง :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-3">
        <span class="font-medium-6" id="date_due"> {!!!empty( $lawlistministry->date_due)?HP::formatDateThaiFull($lawlistministry->date_due,true):null !!}</span>
    </div>
</div>

<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('file_listen_ministry', 'หนังสือแจ้งรับฟังความเห็น :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-4" id="file_listen_ministry">
        @if (!empty($lawlistministry->AttachFileListenMinistry))
        @php
            $attachs_listen_ministry = $lawlistministry->AttachFileListenMinistry;
        @endphp
            <a href="{!! HP::getFileStorage($attachs_listen_ministry->url) !!}" target="_blank">
                {!! !empty($attachs_listen_ministry->filename) ? $attachs_listen_ministry->filename : '' !!}
                {!! HP::FileExtension($attachs_listen_ministry->url) ?? '' !!}
            </a>
        @endif
    </div>
</div>

<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('file_draft_ministerial', 'ร่างกฏกระทรวง :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-4" id="file_draft_ministerial">
        @if (!empty($lawlistministry->AttachFileDraftMinisterial))
        @php
            $attachs_draft_ministerial = $lawlistministry->AttachFileDraftMinisterial;
        @endphp
            <a href="{!! HP::getFileStorage($attachs_draft_ministerial->url) !!}" target="_blank">
                {!! !empty($attachs_draft_ministerial->filename) ? $attachs_draft_ministerial->filename : '' !!}
                {!! HP::FileExtension($attachs_draft_ministerial->url) ?? '' !!}
            </a>
        @endif
    </div>
</div>

<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('file_draft_standard', 'ร่างมาตรฐาน :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-4" id="file_draft_standard">
        @if (!empty($lawlistministry->AttachFileDraftStandard))
        @php
            $attachs_draft_standard = $lawlistministry->AttachFileDraftStandard;
        @endphp
            <a href="{!! HP::getFileStorage($attachs_draft_standard->url) !!}" target="_blank">
                {!! !empty($attachs_draft_standard->filename) ? $attachs_draft_standard->filename : '' !!}
                {!! HP::FileExtension($attachs_draft_standard->url) ?? '' !!}
            </a>
        @endif
    </div>
</div>
<div id="file_other">
    @if (!empty($lawlistministry->AttachFileOther) && count($lawlistministry->AttachFileOther) > 0)
        <div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
            {!! Form::label('file_other', 'ไฟล์เเนบอื่นๆ :', ['class' => 'col-md-3 text-right']) !!}
            <div class="col-md-4"> 
                @php
                    $attachs = $lawlistministry->AttachFileOther;
                @endphp
                @if (!empty($attachs) && count($attachs) > 0)
                    @foreach ($attachs as $attach)
                                <p>     
                                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                        {!! !empty($attach->caption) ? $attach->caption : '' !!}
                                        {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                    </a>
                                </p>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>

<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'ประกาศอื่นๆ ที่เกี่ยวข้อง :', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-3">
        <span class="font-medium-6">  <a href="https://www.tisi.go.th/standard" target="_blank"><u>https://www.tisi.go.th/standard</u> </a></span>
    </div>
</div>

<div class="hr-sect">ความคิดเห็น</div>

@if(!empty($accept))

<div class="container-fluid">
    <p class="h1 text-bold-500 text-center">ขออภัย ขณะนี้ปิดรับฟังความเห็นแล้ว</p>
    <p class="h2 text-bold-300 text-center">หากท่านประสงค์ต้องการแสดงความคิดเห็นขอให้ติดต่อเจ้าหน้าที่ สมอ.</p>
</div>

@elseif (!empty($lawlistministry->url_type) && $lawlistministry->url_type == 2)
<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'แบบรับฟังความคิดเห็น', ['class' => 'col-md-3 text-right']) !!}
    <div class="col-md-3">
        <span class="font-medium-6">  <a href="{!! $lawlistministry->url !!}" target="_blank"><u>{!! $lawlistministry->url !!}</u> </a></span>
    </div>
</div>

@else
<div class="url_type_2"></div>
<div class="url_type_1">

@php
    $comment_point_list = App\Models\Law\Listen\LawListenMinistryResponse::list_comment_point();
    $responses_types = !empty($lawlistministry->responses_type) ? json_decode($lawlistministry->responses_type):[];
@endphp

<div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'ความคิดเห็น:', ['class' => 'col-md-3 control-label ']) !!}
    <div class="col-md-9" id="comment">
    @if (count($responses_types) > 0 )
        @foreach ($responses_types as $responses_type)
        <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
            <input type="radio" class="check comment_point" value="{{$responses_type}}" id="comment_point-{{$responses_type}}" name="comment_point"  data-radio="iradio_square-green"@if((!empty($lawlistministryrsponse->comment_point) && in_array($lawlistministryrsponse->comment_point,[$responses_type])) || $responses_type == 1)  checked  required @endif >
            <label for="comment_point-{{$responses_type}}"> &nbsp; {!! array_key_exists($responses_type,$comment_point_list)?$comment_point_list[$responses_type]:null !!} </label>   
        </div>  
        @endforeach
    @endif
    </div>
</div>

<div class="form-group  {{ $errors->has('comment_more') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('comment_more', 'ข้อคิดเห็นเพิ่มเติม:<span class=" text-danger" id="label_comment_more">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6">
        {!! Form::textarea('comment_more', null, ['class' => 'form-control', 'rows'=> '3','id'=>'comment_more']) !!}
        {!! $errors->first('comment_more', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@php
//ไฟล์แนบความเห็น
$attachs_listen_response   = !empty($lawlistministryrsponse->AttachFileResponse)?$lawlistministryrsponse->AttachFileResponse:[];
@endphp

<div class="form-group {{ $errors->has('file_response') ? 'has-error' : ''}}">
    {!! Form::label('file_response', 'ไฟล์แนบ'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        @if( count( $attachs_listen_response ) > 0 )
        @foreach ($attachs_listen_response as $listen_response)
        <p>  
             <a href="{!! HP::getFileStorage($listen_response->url) !!}" target="_blank" class="m-t-5">
            {!! !empty($listen_response->filename) ? $listen_response->filename : '' !!}
            {!! HP::FileExtension($listen_response->url) ?? '' !!}
            </a>
            <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($listen_response->id).'/'.base64_encode('law/listen/ministry-response/'.$lawlistministryrsponse->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        </p>
         @endforeach
        @else
            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">เลือกไฟล์</span>
                    <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="file_response" class="check_max_size_file">
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
            </div>
            <div class="text-warning">รองรับไฟล์ png, jpg, pdf, docx ขนาดไม่เกิน 10 MB</div>
        @endif
    </div>
</div>

<div class="hr-sect">ข้อมูลที่ติดต่อได้</div>

<div class="form-group required">    
    {!! Form::label('type', 'ประเภทสถานประกอบการ:', ['class' => 'col-md-3 control-label ']) !!}
    <div class="col-md-5" >
        <input type="radio" class="check trader_type" id="trader_type-1" value="1" name="trader_type" data-radio="iradio_square-green" @if(!empty($lawlistministryrsponse->trader_type) && in_array($lawlistministryrsponse->trader_type,[1])) checked @endif required>
        <label for="trader_type-1"> &nbsp; นิติบุคคล &nbsp;</label>
        <input type="radio" class="check trader_type" id="trader_type-2" value="2" name="trader_type" data-radio="iradio_square-green" @if(!empty($lawlistministryrsponse->trader_type) && in_array($lawlistministryrsponse->trader_type,[2])) checked @endif>
        <label for="trader_type-2"> &nbsp; บุคคลธรรมดา &nbsp;</label>
        <input type="radio" class="check trader_type" id="trader_type-3" value="3" name="trader_type" data-radio="iradio_square-green" @if(!empty($lawlistministryrsponse->trader_type) && in_array($lawlistministryrsponse->trader_type,[3])) checked @endif>
        <label for="trader_type-3"> &nbsp; อื่นๆ(ระบุ) &nbsp;</label>
    </div>
    <div class="col-md-3" id="box_trader_other">
        {!! Form::text('trader_other', null, ['class' => 'form-control', 'id'=>'trader_other']) !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tax_number') ? 'has-error' : ''}}">
    {!! Form::label('tax_number', 'เลขที่บัตรประชาชน/Passport', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('tax_number', null, ['class' => 'form-control']) !!}
        {!! $errors->first('tax_number', '<p class="help-block">:message</p>') !!}
    </div>
    {!! HTML::decode(Form::label('comment_more', 'ชื่อ-สกุล/ชื่อบริษัท:<span class=" text-danger">*</span>', ['class' => 'col-md-2 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('agency') ? 'has-error' : ''}}">
    {!! Form::label('agency', 'สังกัด/หน่วยงาน', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('agency', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('agency', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('position', 'ตำแหน่ง', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('position', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('position', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('address', 'ที่อยู่', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('tel') ? 'has-error' : ''}}">
    {!! Form::label('tel', 'เบอร์โทรศัพท์', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('tel', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('email', 'e-Mail', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-8 text-right">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-listen-ministry-response'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/listen/ministry-response') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

</div>
@endif

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script type="text/javascript">
        @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
        @endif

        $(document).ready(function() {
            $('#listen_id').change(function(){ 
                
                if($(this).val() != ''){
                    $.ajax({
                        url: "{!! url('law/listen/ministry-response/data_ministry') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#tis_no').text(object.tis_no);
                        $('#tis_name').text(object.tis_name);
                        $('#remark').text(object.remark);
                        $('#date_due').text(object.date_due);
                        $('#file_listen_ministry').html(object.file_listen_ministry);
                        $('#file_draft_ministerial').html(object.file_draft_ministerial);
                        $('#file_draft_standard').html(object.file_draft_standard);
                        $('#file_other').html(object.file_other);
                        $('.url_type_2').html(object.url);
                        if(object.url_type == 2){
                            $('.url_type_1').hide();
                            $('.url_type_2').show();
                        }else{
                            $('.url_type_2').hide();
                            $('.url_type_1').show();
     
                            $('#comment').html(object.comment);
                            $('#comment').find('.check').each(function() {
                                var ck = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-red';
                                var rd = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-red';

                                if (ck.indexOf('_line') > -1 || rd.indexOf('_line') > -1) {
                                    $(this).iCheck({
                                        checkboxClass: ck,
                                        radioClass: rd,
                                        insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                                    });
                                } else {
                                    $(this).iCheck({
                                        checkboxClass: ck,
                                        radioClass: rd
                                    });
                                }
                            });
                        }
              
                    });
                }
            });

        $(document).on('ifChanged','.comment_point', function(event){   
            ifChangedCommentPoint();
        });
        ifChangedCommentPoint();
        
        $('.trader_type').on('ifChanged', function(event){
            ifChangedTraderType();
        });
        ifChangedTraderType();
        });

        function ifChangedTraderType(){
            if( $("input[name='trader_type']:checked").val() == 3){
                $('#box_trader_other').show(200);
                $('#trader_other').prop('required', true);
            } else {        
                $('#box_trader_other').hide(200);
                $('#trader_other').prop('required', false);
                $('#trader_other').val('');
            }

        }

        function ifChangedCommentPoint(){
            var comment_point =  $("input[name='comment_point']:checked").val();
            if(comment_point  == 1 || comment_point == 3){
                $('#label_comment_more').hide();
                $('#comment_more').prop('required', false);
                $('#comment_more').val('');
            } else {
                $('#label_comment_more').show();
                $('#comment_more').prop('required', true);
            }

        }

</script>
@endpush
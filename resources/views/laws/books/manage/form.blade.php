@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/components/summernote/summernote.css') }}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 70px;
            border-radius: 0;
            width: 100% !important;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
        .note-editor.note-frame {
            border-radius: 4px !important;
        }

    </style>
@endpush

<div class="clearfix"></div>

<div class="col-md-12">
    <div class="white-box">
        <p class="text-muted m-b-30 font-20">ข้อมูลห้องสมุด</p>

            <div class="form-group  required{{ $errors->has('basic_book_group_id') ? 'has-error' : ''}}">
                {!! Form::label('basic_book_group_id', 'หมวดหมู่', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::select('basic_book_group_id', App\Models\Law\Basic\LawBookGroup::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกหมวดหมู่ -', 'required' => true, 'id' => 'basic_book_group_id']) !!}
                    {!! $errors->first('basic_book_group_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group  required{{ $errors->has('basic_book_type_id') ? 'has-error' : ''}}">
                {!! Form::label('basic_book_type_id', 'ประเภท', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::select('basic_book_type_id', App\Models\Law\Basic\LawBookType::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกประเภท -', 'required' => true, 'id' => 'basic_book_type_id']) !!}
                    {!! $errors->first('basic_book_type_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title', 'ชื่อเรื่อง', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9 ">
                    {!! Form::text('title', null , ['class' => 'form-control ', 'required' => 'required']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('important') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('important', 'ใจความสำคัญ<br><small class="text-muted m-b-30 font-12"><i>(คีย์เวิร์ดสำคัญสำหรับค้นหา)</i></small>', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-9 ">
                    {!! Form::text('important', null , ['class' => 'form-control']) !!}
                    {!! $errors->first('important', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('description', 'คำอธิบาย (ถ้ามี)', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7 ">
                    {!! Form::textarea('description', null , ['class' => 'form-control  summernote','id'=>'description' ]) !!}
                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('tag') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('tag', 'Tag <span class="text-danger">*</span> '.'<br><small class="text-muted m-b-30 font-12"><i>(คีย์เวิร์ดสำคัญสำหรับค้นหา)</i></small>', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('tag', null,  ['class' => 'form-control tag', 'required' => 'required', 'data-role' => "tagsinput"]) !!}
                </div>
            </div>
            
            <div class="form-group{{ $errors->has('owner') ? 'has-error' : ''}}">
                {!! Form::label('owner', 'เจ้าของเรื่อง', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9 ">
                    {!! Form::text('owner', null , ['class' => 'form-control ']) !!}
                    {!! $errors->first('owner', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('lawyer') ? 'has-error' : ''}}">
                {!! Form::label('lawyer', 'นิติกร', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9 ">
                    {!! Form::text('lawyer', null , ['class' => 'form-control ']) !!}
                    {!! $errors->first('lawyer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            @php
                $file_image_cover_max = HP::get_upload_max_filesize('5MB');
            @endphp
            <div class="form-group {{ $errors->has('file_image_cover') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('file_image_cover', 'ไฟล์ภาพหน้าปก '.'<br><small class="text-muted m-b-30 font-12"><i>(.jpg, .png ขนาดไม่เกิน '.$file_image_cover_max.')</i></small>', ['class' => 'col-md-3 control-label p-l-0'])) !!}
                <div class="col-md-8">
                    @if (!empty($book_manage->FileImageCoverBookManage))
                        @php
                            $file_image_cover = $book_manage->FileImageCoverBookManage;
                        @endphp
                        @if (!empty($file_image_cover))
                            <p>
                                <a href="{!! HP::getFileStorage($file_image_cover->url) !!}" target="_blank">
                                    {!! !empty($file_image_cover->filename) ? $file_image_cover->filename : '' !!}
                                    {!! HP::FileExtension($file_image_cover->filename) ?? '' !!}
                                </a>
                                &nbsp; <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('law/delete-files/'.($file_image_cover->id).'/'.base64_encode('law/book/manage/'.$book_manage->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </p>
                            @php
                                goto no_show_file_image_cover; //ไม่แสดง input file
                            @endphp
                        @endif
                    @endif

                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                        <div class="form-control " data-trigger="fileinput" >
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                            <span class="input-group-text btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="file_image_cover" class="check_max_size_file" max-size="{{ $file_image_cover_max }}" accept=".png, .jpg">
                            </span>
                        </span>
                    </div>

                    @php
                        no_show_file_image_cover:
                    @endphp
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <small class="text-warning">อัพโหลดได้เฉพาะไฟล์ .jpg .docx .png .xlsx และ.pdf ขนาดไฟล์ละไม่เกิน 8 MB </small>
                  
                </div>
            </div>

            <div class=" required{{ $errors->has('attach') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('attach', 'ไฟล์เเนบ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-9  repeater-form-file" >
                    @php
                        $attachs_check = 'required';
                    @endphp
                    @if (!empty($book_manage->AttachFileBookManage))
                        @php
                            $attachs = $book_manage->AttachFileBookManage;
                        @endphp
                        @if (!empty($attachs) && count($attachs) > 0)
                            @php
                                $attachs_check = '';
                            @endphp
                            @foreach ($attachs as $attach)
                                        <p>
                                            <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                                {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                                {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                            </a>
                                            &nbsp; <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/book/manage/'.$book_manage->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </p>
                            @endforeach
                        @endif
                    @endif
                    <div class="row" data-repeater-list="repeater-attach">
                        <div class="form-group repeater_form_file4" data-repeater-item>
                            <div class="col-md-11">
                                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                    <div class="form-control " data-trigger="fileinput" >
                                        <span class="fileinput-filename"></span>
                                    </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                        <span class="input-group-text btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="file_book_manage" class="check_max_size_file" {!! $attachs_check !!}>
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

            <div id="box_url">
                @if (!empty($book_manage->url) && is_array($book_manage->url) )
                    @foreach ($book_manage->url as  $key => $url )
                        <div class=" required box_url{{ $errors->has('attach') ? 'has-error' : ''}}">
                            <div class="col-md-3 text-right">
                                @if($key == 0)
                                    <label class="url_remove">URL ที่เกี่ยวข้อง (ถ้ามี) </label>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            {!! Form::text('url_description[]',!empty( $url['url_description'])?$url['url_description']:'' , ['class' => 'form-control ', 'placeholder' => 'คำอธิบาย']) !!}
                                        </div>
                                        <div class="col-md-7">
                                            {!! Form::text('url[]', !empty( $url['url'])?$url['url']:'' , ['class' => 'form-control ', 'placeholder' => 'วาง url']) !!}
                                        </div>
                                        <div class="col-md-1">
                                        @if($key == 0)
                                            <button type="button" class="btn btn-success btn-sm btn-outline url_remove" id="url_add">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <div class="button_remove"></div>
                                        @else
                                            <div class="button_remove">
                                                <button class="btn btn-danger btn-outline btn-sm btn_url_remove" type="button"> <i class="fa fa-times"></i>  </button>
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class=" required box_url{{ $errors->has('attach') ? 'has-error' : ''}}">
                        <div class="col-md-3 text-right">
                            <label class="url_remove">URL ที่เกี่ยวข้อง (ถ้ามี) </label>
                        </div>
                        <div class="col-md-9" >
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::text('url_description[]',null , ['class' => 'form-control ', 'placeholder' => 'คำอธิบาย']) !!}
                                    </div>
                                        <div class="col-md-7">
                                            {!! Form::text('url[]', null , ['class' => 'form-control ','placeholder' => 'วาง url']) !!}
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success btn-sm btn-outline url_remove" id="url_add">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <div class="button_remove"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group required">
                {!! Form::label('date_publish', 'วันที่เผยแพร่'.':', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-4">
                    <div class="inputWithIcon">
                        {!! Form::text('date_publish', !empty($book_manage->date_publish)?HP::revertDate($book_manage->date_publish,true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control mydatepicker  text-center', 'id' => 'date_publish','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required'] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('date_publish', 'วันที่ดำเนินการ'.':', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-4">
                    <div class="inputWithIcon">
                        {!! Form::text('operation_date', null, ['class' => 'form-control mydatepicker  text-center', 'id' => 'operation_date','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                </div>
            </div>

            <div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
                {!! Form::label('state', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">
                    <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เผยแพร่&nbsp;&nbsp;&nbsp;</label>
                    <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่เผยแพร่</label>
                    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="white-box">
        <p class="text-muted m-b-30 font-20">สิทธิ์การเข้าถึงข้อมูล</p>

            <div class="form-group{{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-7">
                    <input type="checkbox" class="check" id="checkall_access" data-checkbox="icheckbox_square-green"@if(!empty($book_manage->access) && empty($book_manage->access_tisi) && in_array(1, $book_manage->access) && in_array(2, $book_manage->access)) checked @endif>
                    <label for="checkall_access">ทั้งหมด</label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-7">
                    <input type="checkbox" class="check item_checkbox" id="access" value="1"  name="access[]" data-checkbox="icheckbox_square-green" @if(!empty($book_manage->access) && in_array(1, $book_manage->access)) checked @endif>
                    <label for="access">บุคคลทั่วไป</label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-7">
                    <input type="checkbox" class="check item_checkbox" id="access-2"  value="2" name="access[]" data-checkbox="icheckbox_square-green" checked>
                <label for="access-2">เจ้าหน้าที่ สมอ.</label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">
                    <input type="radio" class="check item_checkbox access_tisi" id="access_tisi-1" name="access_tisi_check"  data-radio="iradio_square-green" checked>
                                <label for="access_tisi-1">ทั้งหมด</label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    <input type="radio" class="check access_tisi" id="access_tisi-2" name="access_tisi_check" data-radio="iradio_square-green" @if(!empty($book_manage->access_tisi) )checked @endif>
                                <label for="access_tisi-2">เฉพาะกลุ่ม/กอง (ระบุ)</label>
                </div>
            </div>
            <div class="form-group div-access_tisi{{ $errors->has('checkbox') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::select('access_tisi[]',
                     App\Models\Basic\SubDepartment::orderbyRaw('CONVERT(sub_departname USING tis620)')->pluck('sub_departname','sub_id'),
                    null,
                    ['class' => 'select2-multiple ',
                    'multiple'=>'multiple',
                    'id'=>'access_tisi',
                    'data-placeholder' => '-เลือกมาตราที่เกี่ยวข้อง-']); !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6 ">
                    {!! Form::text('created_by_show', !empty($book_manage->created_by)? $book_manage->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('created_by_show',  !empty($book_manage->created_at)? HP::revertDate($book_manage->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-book-manage'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/book/manage')}}">
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
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {

        
        @if ( \Session::has('success_message'))
            Swal.fire({
                title: 'บันทึกสำเร็จ',
                text: "คุณต้องทำรายการต่อหรือไม่ ?",
                icon: 'success',
                width: 500,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'กลับหน้าแรก',
                cancelButtonText: 'ทำรายการต่อ',
                confirmButtonClass: 'btn btn-primary btn-sm',
                cancelButtonClass: 'btn btn-danger  btn-sm m-l-5',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    window.location = '{!! url('law/book/manage') !!}';
                }
            });
        @endif

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

        $('.summernote').summernote({
            placeholder: 'เขียนคำอธิบายที่นี่...',
            fontNames: ['Lato', 'Arial', 'Courier New'],
            height: 200,
        });

        //เมื่อเลือกหมวดหมู่
        var basic_book_type_id = $('#basic_book_type_id').html();
        $('#basic_book_group_id').change(function (e) {
            $('#basic_book_type_id').html('<option value=""> - ประเภท - </option>');
                if($(this).val()!=""){//ดึงประเภทตามหมวดหมู่
                    $.ajax({
                        url: "{!! url('/law/funtion/get-book-type') !!}" + "?id=" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#basic_book_type_id').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                        $('#basic_book_type_id').val('').trigger('change.select2');
                    });
                }else{
                    $('#basic_book_type_id').html(basic_book_type_id);//แสดงประเภททั้งหมด
                    $('#basic_book_type_id').val('').trigger('change.select2');
                }
        });

         //เพิ่มurl
         $('#url_add').click(function(event) {
            $('.box_url:first').clone().appendTo('#box_url').slideDown();
                var btn ='<button class="btn btn-danger btn-outline btn-sm btn_url_remove" type="button"> <i class="fa fa-times"></i>  </button>';
                var box_url = $('.box_url:last');
                    box_url.find('input').val('');
                    box_url.find('.url_remove').remove();
                    box_url.find('.button_remove').html(btn);
        });

        //ลบurl
        $('body').on('click', '.btn_url_remove', function(event) {
            $(this).parent().parent().parent().remove();
        });

        //เพิ่มลบไฟล์แนบ
        $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 200);
                }
            });
            BtnDeleteFile();

        //สิทธื์การเข้าถึงข้อมูล
        $('#access_tisi-2').on('ifChanged', function(event){
            ifChangedCheckedAccess();

        });
        ifChangedCheckedAccess();

        //เลือกทั้งหมด
        $('#checkall_access').on('ifChanged', function(event){
            ifChangedChecked();
        });

       //เจ้าหน้าที่ สมอ.
       $('#access-2').on('ifChanged', function(event){
            if($(this).is(':checked',true)){
                $('#access_tisi-1').prop('checked', true);
                $('#access_tisi-1').iCheck('update');
                $('#access_tisi-2').prop('checked', false);
                $('#access_tisi-2').iCheck('update');
            }else{
                $('#access_tisi-1').prop('checked', false);
                $('#access_tisi-1').iCheck('update');
                $('#access_tisi-2').prop('checked', false);
                $('#access_tisi-2').iCheck('update');
            }
        });
  

    });

    function ifChangedChecked(){
        if($('#checkall_access').is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                $('.item_checkbox').iCheck('update');
                $('#access_tisi-2').prop('checked', false);
                $('#access_tisi-2').iCheck('update');
                $(".div-access_tisi").hide(200);
                $('#access_tisi').val('').change();
                $('#access_tisi').prop('required', false);
            } else {
                $(".item_checkbox,.access_tisi").prop('checked',false);
                $('.item_checkbox,.access_tisi').iCheck('update');
            }

    }
    function ifChangedCheckedAccess(){
        if($('#access_tisi-2').is(':checked')){
            $('#access_tisi').prop('required', true);
            $('#access_tisi-1').prop('checked', false);
            $('#access_tisi-1').iCheck('update');
            $(".div-access_tisi").show(200);
        } else {
            $('#access_tisi').val('').change();
            $('#access_tisi').prop('required', false);
            $(".div-access_tisi").hide(200);

        }
    }
    

    function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }
              $('.btn_file_remove:first').hide();
              $('.btn_file_add:first').show();
              check_max_size_file();
     }

</script>
@endpush

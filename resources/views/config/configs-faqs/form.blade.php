@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อปัญหาการใช้งาน', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'รายละเอียด', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
        {!! Form::textarea('description', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'rows' => 3]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@if( isset($result->attach_file_faqs) && count($result->attach_file_faqs) >= 1 )
    <div class="form-group">
        {!! Form::label('attach', 'รายการแนบไฟล์'.':', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-9">
            <ul class="list-unstyled">
                @foreach ( $result->attach_file_faqs as $File )
                    <li class="form-group">
            
                        <a href=" {!! HP::getFileStorage($File->url) !!}" target="_blank" title="{!! !empty($File->filename) ? $File->filename : 'ไฟล์แนบ' !!}">
                            {!! !empty($File->filename) ? $File->filename : 'ไฟล์แนบ' !!}
                        </a>
                        <a class="btn btn-danger btn-sm show_tag_a" href="{!! url('config/delete-files/'.($File->id).'/'.base64_encode('config/faqs/'.$result->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="form-group{{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'ไฟล์เเนบ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9 repeater-form-file" >

        <div class="row" data-repeater-list="repeater-attach">
            <div class="form-group repeater_form_file4" data-repeater-item>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                        <div class="form-control inputradius" data-trigger="fileinput" >
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
                <div class="col-md-1">
                    <button class="btn btn-danger btn-sm btn_file_remove btn-outline" data-repeater-delete type="button">
                        <i class="fa fa-times"></i>
                    </button> 
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-sm btn-outline btn_file_add" data-repeater-create>
                        <i class="fa fa-plus"></i>
                    </button>  
                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>

        @can('view-'.str_slug('configs-faq'))
            <a class="btn btn-default show_tag_a" href="{{url('/config/faqs')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
        
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/ckeditor-14/ckeditor.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script>

        $(document).ready(function () {

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
                    confirmButtonClass: 'btn btn-primary btn-sm m-l-5',
                    cancelButtonClass: 'btn btn-danger btn-sm m-l-5',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '{!! url('config/faqs') !!}'
                    }
                });

            @endif

            var config = {
                language: 'th',
                // skin: 'office2013',
                contentsCss: [ 'https://cdn.ckeditor.com/4.8.0/full-all/contents.css', 'mystyles.css' ],
                bodyClass: 'document-editor',
                // width: '210mm',
                extraPlugins : 'ckeditorfa'
            }

            CKEDITOR.replace( 'description', config );

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

        });

        
        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            } 
            $('.btn_file_remove:first').hide();   
            $('.btn_file_add:first').show();   
        }

    </script>
@endpush
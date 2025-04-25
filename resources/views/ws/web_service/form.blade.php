@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .url{
            padding-left: 25px !important;
        }
        #api-add{
            margin-bottom: 5px;
        }
    </style>
@endpush

@php
    $apis = collect(HP_API::APILists());
    $api_names = $apis->pluck('detail', 'name');

    //คั้งค่า
    $config  = HP::getConfig(false);
    $configs = json_encode(['url_sso' => $config->url_sso]);
@endphp

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อผู้ใช้งาน Web Service:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'อีเมลติดต่อ:&nbsp;&nbsp;', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('phone') ? 'has-error' : ''}}">
    {!! Form::label('phone', 'เบอร์โทรผู้ติดต่อ:&nbsp;&nbsp;', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
        {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group required {{ $errors->has('app_name') ? 'has-error' : ''}}">
    {!! Form::label('app_name', 'app-name:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="input-group">
            {!! Form::text('app_name', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'app_name ใช้ส่งค่าใน API']) !!}

            <span class="input-group-btn">
                <button type="button" class="btn waves-effect waves-light btn-inverse copy-clipboard" data-toggle="tooltip" data-placement="right" title="" data-original-title="คัดลอกไปคลิปบอร์ด"><i class="fa fa-clipboard"></i></button>
            </span>

        </div>
        {!! $errors->first('app_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('app_secret') ? 'has-error' : ''}}">

    {!! Form::label('app_secret', 'app-secret:&nbsp;&nbsp;', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="input-group">
            {!! Form::text('app_secret', null, ['class' => 'form-control', 'readonly'=>true, 'placeholder'=>'จะแสดงอัตโนมัติเมื่อบันทึกข้อมูลแล้ว']) !!}

            <span class="input-group-btn">
                <button type="button" class="btn waves-effect waves-light btn-info" id="app_secret-view"><i class="fa fa-eye"></i></button>
                <button type="button" class="btn waves-effect waves-light btn-inverse copy-clipboard" data-toggle="tooltip" data-placement="right" title="" data-original-title="คัดลอกไปคลิปบอร์ด"><i class="fa fa-clipboard"></i></button>
            </span>

        </div>
        {!! $errors->first('app_secret', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="col-md-2">

        @if(isset($web_service))
            <div class="checkbox checkbox-warning">
                <input id="gen_new_secret" type="checkbox" name="gen_new_secret">
                <label for="gen_new_secret">
                    <span class="font-16">Gen ใหม่</span>
                </label>
            </div>
        @endif

    </div>

</div>

<div class="form-group {{ $errors->has('file') ? 'has-error' : ''}}">
    {!! Form::label('file', 'ไฟล์:&nbsp;&nbsp;', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">

        @if( !empty($web_service) && !empty($web_service->file) )

            @php
                $attach = json_decode($web_service->file);
            @endphp

            <div class="col-md-6" >
                <a href="{!! HP::getFileStorage('tis_attach/web_service/'.$attach->file_name) !!}" target="_blank">
                    {!! HP::FileExtension($attach->file_client_name)  ?? '' !!}
                </a>
            </div>
            <div class="col-md-6" >
                <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('ws/web_service/delete-files/'.($web_service->id).'/'.base64_encode('ws/web_service/'.$web_service->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </div>
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
                        <input type="file" name="file">
                    </span>
                </span>
            </div>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ:&nbsp;&nbsp;', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิดใช้งาน</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ระงับใช้งาน</label>

        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="col-md-12">
    <h5>API ที่เปิดให้ใช้งาน
        <button class="btn btn-success btn-sm pull-right" type="button" id="api-add">
            <i class="icon-plus"></i> เพิ่ม API
        </button>
    </h5>
    <div>
        <table class="table color-bordered-table info-bordered-table">
            <thead>
                <tr>
                    <th class="col-md-1 text-center">#</th>
                    <th class="col-md-4 text-center">ชื่อ API</th>
                    <th class="col-md-5 text-center">URL</th>
                    <th class="col-md-1 text-center">คู่มือ</th>
                    <th class="col-md-1 text-center">ลบ</th>
                </tr>
            </thead>
            <tbody id="api-box">
                @foreach ($ListAPI as $key => $api)

                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            {!! Form::select('api[]', $api_names, $api, ['class' => 'form-control api', 'placeholder'=>'- เลือก API -']) !!}
                        </td>
                        <td class="url"></td>
                        <td class="text-center manual"></td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm api-remove" type="button">
                                <i class="icon-close"></i>
                            </button>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('web_service'))
            <a class="btn btn-default" href="{{url('/ws/web_service')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function() {

        var Configs = JSON.parse('{!! $configs !!}');
        var Apis    = JSON.parse('{!! json_encode($apis) !!}');

        //เมื่อเลือก api แสดง URL
        $('body').on('change', '.api', function() {
            var url    = $(this).parent().parent().find('.url');
            var manual = $(this).parent().parent().find('.manual');
            if($(this).val()==''){
                url.text('');
                manual.text('');
            }else{
                var api = Apis[$(this).val()];

                var site = Configs.hasOwnProperty(api.domain) ? Configs[api.domain] : '{!! url('') !!}/'; //ถ้าโดเมนมีใน config แสดงว่าไม่ได้อยู่ไซต์นี้
                url.text(site+api.url);//แสดง URL

                var link = '{!! url('downloads/api_manual') !!}/'+api.manual;
                manual.html('<a href="'+link+'" target="_blank" class="font-22 text-danger"><i class="mdi mdi-file-pdf"></i></a>');//แสดงคู่มือ
            }

        });

        //เมื่อเพิ่ม api
        $('#api-add').click(function(event) {

            $('#api-box').children(':first').clone().appendTo('#api-box'); //Clone Element

            var last_new = $('#api-box').children(':last');

            //Clear value select
            $(last_new).find('select').val('');
            $(last_new).find('select').prev().remove();
            $(last_new).find('select').removeAttr('style');
            $(last_new).find('select').select2();

            //Clear URL
            $(last_new).find('.url, .manual').text('');

            resetOrder();

        });

        //ลบ api
        $('body').on('click', '.api-remove', function(){

            $(this).parent().parent().remove();

            resetOrder();

        });

        //ดู app_secret
        $('#app_secret-view').click(function(event) {

            var type = $('#app_secret').prop('type');
            if(type=='password'){
                $('#app_secret').prop('type', 'text');
                $(this).html('<i class="fa fa-eye"></i>');
            }else{
                $('#app_secret').prop('type', 'password');
                $(this).html('<i class="fa fa-eye-slash"></i>');
            }

        });

        //คัคัดลอกไปคลิปบอร์ด
        $('.copy-clipboard').click(function(event) {

            var selector = $(this).closest('.input-group').find('input');

            var change_to_text = false;
            if($(selector).prop('type')=='password'){//ถ้าเป็น password เปลี่ยนเป็น text ก่อน เนื่องจาก browser ไม่ให้คัดลอก
                $(selector).prop('type', 'text');
                change_to_text = true;
            }

            selector.select();
            selector[0].setSelectionRange(0, 99999);
            document.execCommand("copy");

            //เปลี่ยนกลับเป็น password
            if(change_to_text){
                $(selector).prop('type', 'password');
            }

            $.toast({
                heading: 'สำเร็จ',
                text: 'คัดลอกไปคลิปบอร์ดสำเร็จ',
                position: 'top-right',
                loaderBg: '#70b7d6',
                icon: 'info',
                hideAfter: 3000,
                stack: 6
            });
        });

        resetOrder();
        $('.api').change();
        $('#app_secret-view').click();

    });

    function resetOrder(){//รีเซตลำดับของตำแหน่ง

        $('#api-box').children().each(function(index, el) {
            $(el).find('td:first').text((index+1));
        });

        if($('#api-box').children().length>1){
            $('.api-remove').show();
        }else{
            $('.api-remove').hide();
        }

    }

</script>

@endpush

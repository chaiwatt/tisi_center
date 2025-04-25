@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css" id="css-after-load">

    </style>
    <div id="tmp-after-load" class="hide">

    </div>
@endpush

@php
    $app_names = App\Models\WS\Client::pluck('app_name', 'app_name');
    $groups = App\Models\Config\SettingSystemGroup::pluck('title', 'id');

    $menu_json = [];
    //ระบบกำหนดมาตรฐาน
    if (File::exists(base_path('resources/laravel-admin/new-menu-standards.json'))) {
        $laravelMenuStandards = json_decode(File::get(base_path('resources/laravel-admin/new-menu-standards.json')));
        if(isset($laravelMenuStandards->menus[0]->_comment)){
            $menu_json['new-menu-standards.json'] = $laravelMenuStandards->menus[0]->_comment;
        }
    }

    //ระบบกำหนดมาตรฐานรับรอง
    if (File::exists(base_path('resources/laravel-admin/new-menu-set-standards.json'))) {
        $laravelMenueSetStd = json_decode(File::get(base_path('resources/laravel-admin/new-menu-set-standards.json')));
        if(isset($laravelMenueSetStd->menus[0]->_comment)){
            $menu_json['new-menu-set-standards.json'] = $laravelMenueSetStd->menus[0]->_comment;
        }
    }

    //ระบบรับรองระบบงาน
    if (File::exists(base_path('resources/laravel-admin/new-menu-certify.json'))) {
        $laravelMenuCertify = json_decode(File::get(base_path('resources/laravel-admin/new-menu-certify.json')));
        if(isset($laravelMenuCertify->menus[0]->_comment)){
            $menu_json['new-menu-certify.json'] = $laravelMenuCertify->menus[0]->_comment;
        }
    }

    //ระบบตรวจการอิเล็กทรอนิกส์(e-Surv)
    if (File::exists(base_path('resources/laravel-admin/new-menu-e-surv.json'))) {
        $laravelMenueSurv = json_decode(File::get(base_path('resources/laravel-admin/new-menu-e-surv.json')));
        if(isset($laravelMenueSurv->menus[0]->_comment)){
            $menu_json['new-menu-e-surv.json'] = $laravelMenueSurv->menus[0]->_comment;
        }
    }

    //ระบบขึ้นทะเบียนตาม(ม.5)
    if (File::exists(base_path('resources/laravel-admin/new-menu-section5.json'))) {
        $laravelMenuSection5 = json_decode(File::get(base_path('resources/laravel-admin/new-menu-section5.json')));
        if(isset($laravelMenuSection5->menus[0]->_comment)){
            $menu_json['new-menu-section5.json'] = $laravelMenuSection5->menus[0]->_comment;
        }
    }

    //ระบบบันทึกคดีผลิตภัณฑ์อุตสาหกรรม
    if (File::exists(base_path('resources/laravel-admin/new-menu-law.json'))) {
        $laravelMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/new-menu-law.json')));
        if(isset($laravelMenuLaw->menus[0]->_comment)){
            $menu_json['new-menu-law.json'] = $laravelMenuLaw->menus[0]->_comment;
        }
    }
@endphp

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อระบบ <span class="text-danger">*</span><p class="text-muted font-15">ชื่อที่ใช้แสดงให้ผู้ใช้งานเห็น</p>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}} required">
    {!! Form::label('description', 'รายละเอียด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required', 'rows' => 2]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('urls') ? 'has-error' : ''}} required">
    {!! Form::label('urls', 'URL:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('urls', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('urls', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('icons') ? 'has-error' : ''}} required">
    {!! Form::label('icons', 'ไอคอน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @php
            $icons = File::get(public_path('less/icons/material-design-iconic-font/json/list.json'));
            $icons = json_decode($icons, true);
            $icons = collect($icons)->pluck('name');
            $icon_values = explode(',', 'mdi-'.$icons->implode(',mdi-'));
            $icons = array_combine($icon_values, $icons->toArray());
        @endphp
        {!! Form::select('icons', $icons, null, ['class' => 'form-control not_select2', 'required' => 'required', 'placeholder'=>'-เลือกไอคอน-']) !!}
        {!! $errors->first('icons', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('colors') ? 'has-error' : ''}} required">
    {!! Form::label('colors', 'สี', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @php
            $colors = File::get(public_path('less/colors/json/list.json'));
            $colors = json_decode($colors, true);
            $colors = collect($colors)->pluck('name', 'name');
        @endphp
        {!! Form::select('colors', $colors, null, ['class' => 'form-control not_select2', 'required' => 'required', 'placeholder'=>'-เลือกสี-']) !!}
        {!! $errors->first('colors', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('menu_jsons') ? 'has-error' : ''}}">
    {!! Form::label('menu_jsons', 'อ้างอิงไฟล์เมนู', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('menu_jsons', $menu_json, null, ['class' => 'form-control', 'placeholder'=>'-เลือกไฟล์เมนู-']) !!}
        {!! $errors->first('menu_jsons', '<p class="help-block">:message</p>') !!}
        <p><small class="text-muted">หมายเหตุ: หากเลือกอ้างอิงไฟล์เมนูระบบจะเช็คสิทธิ์การเข้าถึงระบบงานจากไฟล์ที่เลือก</small></p>
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('displays') ? 'has-error' : ''}}">
    {!! Form::label('displays', 'แสดงที่กลุ่มผู้ใช้งาน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('displays', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('displays', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('displays', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('role-setting-group'))
            <a class="btn btn-default" href="{{url('/role-setting-group')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>

        $(document).ready(function() {

            /* ไอคอน */
            function format(option) {
                if (!option.id) return option.text; // optgroup
                return "<i class=\"mdi " + option.id + " pre-icon\"></i> " + option.text;
            }
            $("#icons").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });

            /* สี */
            function format_color(option) {
                if (!option.id) return option.text; // optgroup
                return "<i class=\"mdi mdi-solid pre-icon " + option.id + "\"></i> " + option.text;
            }
            $("#colors").select2({
                formatResult: format_color,
                formatSelection: format_color,
                escapeMarkup: function(m) { return m; }
            });

            //สร้าง box div เพื่อดึงค่าสีตาม class css และเอาไปสร้างเป็น css ชุดใหม่ใน css-after-load
            var css_colors = Array();
            $('#colors').children('option').each(function(index, el) {
                $('#tmp-after-load').append('<div class="'+$(el).text()+'"></div>');
                var color = '.' + $(el).text() + '{';
                    color += ' color: ' + $('#tmp-after-load').find('.'+$(el).text()).css('background-color') + ' !important;';
                    color += ' background-color: transparent !important;';
                    color += '}';
                css_colors.push(color);
            });
            $('#css-after-load').html(css_colors.join(' '));

        });

    </script>
@endpush

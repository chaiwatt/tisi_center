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
@endphp

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อระบบ <span class="text-danger">*</span><p class="text-muted font-15">ชื่อที่ใช้แสดงให้ผู้ใช้งานเห็น</p>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('app_name') ? 'has-error' : ''}}">
    {!! Form::label('app_name', 'app_name <span class="text-danger">*</span><p class="text-muted font-15">ชื่อที่ใช้สื่อสารกันระหว่างระบบ</p>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::select('app_name', $app_names, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือก app_name-']) !!}
        {!! $errors->first('app_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}} required">
    {!! Form::label('details', 'รายละเอียด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('details', null, ['class' => 'form-control', 'required' => 'required', 'rows' => 2]) !!}
        {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('urls') ? 'has-error' : ''}} required">
    {!! Form::label('urls', 'URL:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('urls', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('urls', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('group_id') ? 'has-error' : ''}}">
    {!! Form::label('group_id', 'กลุ่ม URL <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::select('group_id', $groups, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือก กลุ่มเมนู-']) !!}
        {!! $errors->first('group_id', '<p class="help-block">:message</p>') !!}
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
        {!! Form::select('icons', $icons, null, ['class' => 'form-control not_select2', 'required' => 'required']) !!}
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
        {!! Form::select('colors', $colors, null, ['class' => 'form-control not_select2', 'required' => 'required']) !!}
        {!! $errors->first('colors', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('transfer_method') ? 'has-error' : ''}} required">
    {!! Form::label('transfer_method', 'วิธีไป URL ปลายทาง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('transfer_method', App\Models\Config\SettingSystem::transfer_methods(), null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('transfer_method', '<p class="help-block">:message</p>') !!}
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

<div class="form-group {{ $errors->has('branch_block') ? 'has-error' : ''}}">
    {!! Form::label('branch_block', 'ไม่ให้สาขาใช้งาน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('branch_block', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ใช่ (ไม่ให้ใช้งาน)</label>
        <label>{!! Form::radio('branch_block', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ไม่ (ให้ใช้งาน)</label>
        {!! $errors->first('branch_block', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('SsoUrl'))
            <a class="btn btn-default" href="{{url('/config/sso-url')}}">
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

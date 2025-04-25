@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css" id="css-after-load">

    </style>
    <div id="tmp-after-load" class="hide">

    </div>
@endpush


<div class="form-group required {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'ชื่อเรื่อง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'ชื่อเรื่อง', 'required' => true]) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('color') ? 'has-error' : ''}} required">
    {!! Form::label('color', 'สีที่แสดง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @php
            $colors = File::get(public_path('less/colors/json/list.json'));
            $colors = json_decode($colors, true);
            $colors = collect($colors)->pluck('name', 'name');
        @endphp
        {!! Form::select('color', $colors, null, ['class' => 'form-control not_select2', 'required' => 'required', 'placeholder'=>'-เลือกสี-']) !!}
        {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('state_notify') ? 'has-error' : ''}}">
    {!! Form::label('state_notify', 'ระบบแจ้งเตือน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state_notify', '1', true,  ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state_notify', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state_notify', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($config_section->created_by)? $config_section->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($config_section->created_at)? HP::revertDate($config_section->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-config-system-category'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/config/system-category')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            /* สี */
            function format_color(option) {
                if (!option.id) return option.text; // optgroup
                return "<i class=\"mdi mdi-solid pre-icon " + option.id + "\"></i> " + option.text;
            }
            $("#color").select2({
                formatResult: format_color,
                formatSelection: format_color,
                escapeMarkup: function(m) { return m; }
            });
            //สร้าง box div เพื่อดึงค่าสีตาม class css และเอาไปสร้างเป็น css ชุดใหม่ใน css-after-load
            var css_colors = Array();
            $('#color').children('option').each(function(index, el) {
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

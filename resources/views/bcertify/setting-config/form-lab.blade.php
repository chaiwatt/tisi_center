
@php
           $setting_lab = $setting_config->where('grop_type','lab')->first();
@endphp
<div class="clearfix"></div>
{!! Form::open(['url' => '/bcertify/setting-config/store',    'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}

    <div class="col-md-12">

        <div class="form-group  required{{ $errors->has('from_filed') ? 'has-error' : ''}}">
            {!! Form::label('from_filed', 'ติดตามครั้งแรกจากวันที่', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-4">
                {!! Form::select('from_filed',App\Models\Bcertify\SettingConfig::list_from_filed(),!empty($setting_lab->from_filed)?$setting_lab->from_filed:null, ['class' => 'form-control', 'placeholder'=>'- เลือกติดตามครั้งแรกจากวันที่ -', 'required' => true, 'id' => 'from_filed']) !!}
                {!! $errors->first('from_filed', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        
        <div class="form-group required{{ $errors->has('warning_day') ? 'has-error' : ''}}">
            {!! Form::label('warning_day', 'แจ้งเตือนล้วงหน้า', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-4">
                <div class="input-group">
                    {!! Form::text('warning_day', !empty($setting_lab->warning_day)?$setting_lab->warning_day:null, ['class' => 'form-control text-center numberonly', 'required' => 'required']) !!}
                    <span class="input-group-addon bg-info b-0 text-white"> วัน </span>
                    {!! $errors->first('warning_day', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group required{{ $errors->has('condition_check') ? 'has-error' : ''}}">
            {!! Form::label('condition_check', 'เงื่อนไขการกดติดตาม', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-4">
                <div class="input-group">
                    {!! Form::text('condition_check', !empty($setting_lab->condition_check)?$setting_lab->condition_check:null, ['class' => 'form-control text-center numberonly', 'required' => 'required']) !!}
                    <span class="input-group-addon bg-info b-0 text-white"> เดือน </span>
                    {!! $errors->first('condition_check', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group {{ $errors->has('check_first') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-7">
                <input type="checkbox" class="check item_checkbox" id="check_first" value="1"  name="check_first" data-checkbox="icheckbox_square-green" @if(!empty($setting_lab->check_first) && $setting_lab->check_first==1) checked @endif>
                <label for="check_first">ตรวจติดตามครั้งแรก 6 เดือน</label>
            </div>
        </div>

        </div>
        <input name="grop_type" type="hidden" value="lab">

        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn btn-primary" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('bcertify-setting-config'))
                    <a class="btn btn-default" href="{{url('/home')}}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>
{!! Form::close() !!}
        
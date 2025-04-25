@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อกลุ่มที่กำหนด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'ชื่อกลุ่มที่กำหนด', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('reward_group_id') ? 'has-error' : ''}}">
    {!! Form::label('reward_group_id', 'กลุ่มผู้มีสิทธิ์ได้รับเงิน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('reward_group_id', App\Models\Law\Basic\LawRewardGroup::Where('state', 1)->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกกลุ่มผู้มีสิทธิ์ได้รับเงิน -', 'required' => true]) !!}
        {!! $errors->first('reward_group_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('arrest_id') ? 'has-error' : ''}}">
    {!! Form::label('arrest_id', 'มีการจับกุม', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('arrest_id', App\Models\Law\Basic\LawArrest::Where('state', 1)->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกกรณีมี/ไม่มีการจับกุม -', 'required' => true]) !!}
        {!! $errors->first('arrest_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('operation_id') ? 'has-error' : ''}}">
    {!! Form::label('operation_id', 'การดำเนินการ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('operation_id', '1', null, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปรียบเทียบปรับ</label>
        <label>{!! Form::radio('operation_id', '2', null, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ส่งดำเนินคดี</label>
        {!! $errors->first('operation_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('amount') ? 'has-error' : ''}}">
    {!! Form::label('amount', 'จำนวนเปอร์เซ็นเงินที่จะได้รับ', ['class' => 'col-md-4 control-label']) !!}

    <div class="col-md-6">
        <div class="input-group">
            {!! Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'กรอกเป็นตัวเลข', 'required' => true, 'max' => 100]) !!}
            <span class="input-group-addon"><i class="mdi mdi-percent"></i></span>
        </div>
    </div>
    {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}

</div>

<div class="form-group required {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
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
        @can('view-'.str_slug('law-config-reward'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/config/reward')}}">
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

        });
    </script>

@endpush

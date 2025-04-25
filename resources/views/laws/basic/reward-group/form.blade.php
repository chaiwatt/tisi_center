@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'กลุ่มผู้มีสิทธิ์ได้รับเงินรางวัล', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('title', null , ['class' => 'form-control ', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group{{ $errors->has('condition_formscase') ? 'has-error' : ''}}">
    {!! Form::label('condition_formscase', ' ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('condition_formscase', '1', (!empty($rewardgroup->condition_formscase)?$rewardgroup->condition_formscase:false), ['class'=>'check','data-checkbox'=>'icheckbox_square-green', 'id'=>'condition_formscase']) !!}
        {!! Form::label('condition_formscase', 'สำหรับดึงไปแสดงที่ระบบแจ้งงานคดี', ['class' => 'control-label']) !!}
        {!! $errors->first('condition_formscase', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($rewardgroup->created_by)? $rewardgroup->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($rewardgroup->created_at)? HP::revertDate($rewardgroup->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-reward-group'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/basic/reward-group') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

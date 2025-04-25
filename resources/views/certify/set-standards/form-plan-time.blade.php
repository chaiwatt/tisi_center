<div class="form-group {{ $errors->has('period') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('period', 'ระยะเวลา'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-4">
          <div class=" input-group ">
            {!! Form::text('period',  !empty($standardplan->period) ? $standardplan->period : null, ['class' => 'form-control input_number text-right period', 'required' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> เดือน </span>
          </div>
          {!! $errors->first('period', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('plan_startdate') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('plan_startdate', 'กำหนด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            {!! Form::text('plan_startdate',  !empty($standardplan->plan_startdate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_startdate)) ,true) : null, ['class' => 'form-control date plan_date', 'required' => true]) !!}
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('plan_enddate',  !empty($standardplan->plan_enddate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_enddate)) ,true)  : null, ['class' => 'form-control date', 'required' => true]) !!}
          </div>
        {!! $errors->first('plan_enddate', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('budget') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('budget', 'งบประมาณ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-4">
        <div class=" input-group ">
            {!! Form::text('budget',  !empty($standardplan->budget) ? $standardplan->budget : null, ['class' => 'form-control amount text-right ', 'required' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> บาท </span>
        </div>
        {!! $errors->first('budget', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('plan_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('plan_time', 'ประมาณการจำนวนครั้งการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-4">
        <div class=" input-group ">
            {!! Form::text('plan_time', null, ['class' => 'form-control input_number text-right ', 'required' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> ครั้ง </span>
        </div>
        {!! $errors->first('plan_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>



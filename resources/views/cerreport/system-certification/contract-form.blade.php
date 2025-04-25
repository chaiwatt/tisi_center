
<div class="form-group {{ $errors->has('contact_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('contact_name', 'ชื่อผู้ติดต่อ'.' :', ['class' => 'col-md-3 control-label required'])) !!}
    <div class="col-md-6">
        {!! Form::text('contact_name', null, ['class' => 'form-control contact_name', 'disabled' => true]) !!}
        {!! $errors->first('contact_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_tel') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('contact_tel', 'โทรศัพท์'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6">
        {!! Form::text('contact_tel', null, ['class' => 'form-control contact_tel not_valid', 'disabled' => true]) !!}
        {!! $errors->first('contact_tel', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_mobile') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('contact_mobile', 'โทรศัพท์มือถือ'.' :', ['class' => 'col-md-3 control-label required'])) !!}
    <div class="col-md-6">
        {!! Form::text('contact_mobile', null, ['class' => 'form-control contact_mobile', 'disabled' => true]) !!}
        {!! $errors->first('contact_mobile', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('contact_email') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('contact_email', 'E-Mail'.' :', ['class' => 'col-md-3 control-label required'])) !!}
    <div class="col-md-6">
        {!! Form::text('contact_email', null, ['class' => 'form-control contact_email', 'disabled' => true]) !!}
        {!! $errors->first('contact_email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
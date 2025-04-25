
<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>1. ข้อมูลทั่วไป (General information)</h4></legend>
<div class="m-l-10 form-group {{ $errors->has('petitioner') ? 'has-error' : ''}}">
    {{-- <label for="man_applicant" class="col-md-12" style="padding-top: 7px;margin-bottom: 5px;font-size: 16px"><span class="text-danger">*</span> ผู้ยื่นคำขอ (Qualifications of Applicant)</label> --}}
    <div class="col-md-6 ">
        {!! Form::text('petitioner' ,'ใบรับรองหน่วยตรวจ', ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('petitioner', '<p class="help-block">:message</p>') !!}
    </div>
</div>
        </div>
    </div>
</div>

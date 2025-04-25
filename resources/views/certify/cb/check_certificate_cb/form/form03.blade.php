

<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>1. ข้อมูลทั่วไป (General information)</h4></legend>
<div class="m-l-10 form-group {{ $errors->has('petitioner') ? 'has-error' : ''}}">
    <label for="man_applicant" class="col-md-12" style="padding-top: 7px;margin-bottom: 5px;font-size: 16px">ผู้ยื่นคำขอ (Qualifications of Applicant)</label>
    <div class="col-md-6 ">
        {!! Form::select('petitioner',
        App\Models\Certify\ApplicantCB\CertiCBFormulas::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'), 
           null, 
         ['class' => 'form-control', 
         'id'=>'petitioner',
         'required' => true,
         'placeholder' =>'- ผู้ยื่นคำขอ -']) !!}
        {!! $errors->first('petitioner', '<p class="help-block">:message</p>') !!}
    </div>
</div>

        </div>
    </div>
</div>


@push('js')
     <script src="{{asset('js/jasny-bootstrap.js')}}"></script> 
@endpush
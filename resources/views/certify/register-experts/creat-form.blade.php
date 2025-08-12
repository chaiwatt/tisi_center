@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">

    .free-dot {
        border-bottom: thin dotted #000000;
        padding-bottom: 0px !important;
    }

    .detail-result {
        display: block;
        padding: 6px 12px;
    }

    .detail-result-underline {
        display: block;
        padding: 6px 12px;
        /* border-top: #000000 solid 1px; */
        border-bottom: #000000 solid 1px;
    }
    
    .label-height{
        line-height: 25px;
        font-size: 16px;
        font-weight: 600 !important;
        color: black !important;
    }

    .font_size{
        font-size: 10px;
    }

 .autofill {
    border-right-width: 0px !important;
    border-left-width: 0px !important;
    border-top-width: 0px !important;
    border-bottom: 1px !important;
    border-style: dotted !important;
    border-color: #585858 !important;
    background-color: #fff !important;
    /* cursor: no-drop; */
}
.label-height{
        line-height: 25px;
        font-size: 20px;
        font-weight: 600 !important;
        color: black !important;
    }

.label-height-font10{
      line-height: 25px;
      font-size: 16px;
      font-weight: 600 !important;
      color: black !important;
  }
  .label_height{
        line-height: 25px;
        font-size: 16px;
        font-weight: 600 !important;
        color: black !important;
        text-align:left;
  }
</style>
@endpush



<div id="box-readonly">


    <div class="row">

        <div class="form-group required{{ $errors->has('standard_code') ? 'has-error' : ''}}">
            {!! Form::label('standard_code', 'รหัสประเภทมาตรฐาน'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('standard_code', null,  ['class' => 'form-control','required'=>true]) !!}
                {!! $errors->first('standard_code', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group required{{ $errors->has('standard_code') ? 'has-error' : ''}}">
            {!! Form::label('standard_code', 'รหัสประเภทมาตรฐาน'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('standard_code', null,  ['class' => 'form-control','required'=>true]) !!}
                {!! $errors->first('standard_code', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group  required{{ $errors->has('department_id') ? 'has-error' : ''}}">
            {!! Form::label('department_id', 'หน่วยงาน/สังกัด:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('department_id', 
                   App\Models\Basic\AppointDepartment::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                     null,
                    ['class' => 'form-control input_required',
                    'id' => 'department_id',
                    'required' => true , 
                    'placeholder'=>'- เลือกหน่วยงาน/สังกัด -' ]); !!}
            </div>
        </div>
    </div>
     


</div>






@push('js')

@endpush
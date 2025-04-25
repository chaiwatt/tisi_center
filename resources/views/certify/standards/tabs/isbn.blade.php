@php
    $Step2StatusIDArr  = [ '5'=> 'แจ้งระบุเลข ISBN', '6'=> 'ดำเนินการ และเสนอผู้มีอำนาจลงนาม' ];
    $StepStatus2       =  isset($standard) && in_array( $standard->status_id, [ 5,6]  )?$standard->status_id:( isset($standard) && $standard->status_id > 6?6:null );
@endphp

<div class="form-group required {{ $errors->has('status_id') ? 'has-error' : ''}}">
    {!! Form::label('status_id', 'ขั้นตอนการดำเนินงาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('step_status_2', $Step2StatusIDArr,  $StepStatus2 , ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_2'] : ['class' => 'form-control', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_2']) !!}
        {!! $errors->first('step_status_2', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('isbn_no') ? 'has-error' : ''}}">
    {!! Form::label('isbn_no', 'ไฟล์หน้าปก:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        <button class="btn" style="background-color:white" name="submit" type="submit" value="print" id="print" onclick="submit_form('print')">
            <i class="fa fa-download fa-xl"></i>
        </button>
    </div>
</div>

<div class="form-group {{ $errors->has('isbn_no') ? 'has-error' : ''}}">
    {!! Form::label('isbn_no', 'เลข ISBN:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('isbn_no', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'readonly'=>  (!empty($standard->isbn_no)?true:false) ] : ['class' => 'form-control', 'readonly'=> (!empty($standard->isbn_no)?true:false)  ]) !!}
        {!! $errors->first('isbn_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group isbn_by {{ $errors->has('isbn_by') ? 'has-error' : ''}}">
    {!! Form::label('isbn_by', 'ผู้ดำเนินการระบุเลข:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('isbn_by',null, ['class' => 'form-control', 'readonly'=>  (!empty($standard->isbn_by)?true:false) ]) !!}
        {!! $errors->first('isbn_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
    <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
        {!! Form::label('created_by', 'ผู้บันทึก:', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            <span>{{ !empty($standard->CreatedName)?$standard->CreatedName:(auth()->user()->FullName) }}</span>
        </div>
    </div>

    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
        {!! Form::label('created_at', 'วันที่บันทึก:', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            <span>{{ HP::DateTimeFullThai(date('Y-m-d H:m:s')) }}</span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        @if( $step_tap_disabled >= 5 )
            <input type='button' class='btn btn-previous btn-fill btn-warning' name='back' value='Back' />
        @endif
        <button class="btn btn-primary step_save" type="button">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('certifystandard'))
            <a class="btn btn-default" href="{{url('/certify/standards')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
        @if( $step_tap_disabled >= 6 )
            <input type='button' class='btn btn-next btn-fill btn-success' name='next' value='Next' />
        @endif
    </div>
</div>
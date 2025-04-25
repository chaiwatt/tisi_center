
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $application_inspection_unit->refno_application )?$application_inspection_unit->refno_application:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($application_inspection_unit->date_application)?HP::DateThaiFull($application_inspection_unit->date_application):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $application_inspection_unit->checking_by ) && ($application_inspection_unit->CheckingName != '') ?$application_inspection_unit->CheckingName:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับรับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($application_inspection_unit->checking_date) && ($application_inspection_unit->checking_date != '') ?HP::DateThaiFull($application_inspection_unit->checking_date):'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>


<div class="row" id="box-readonly">
    <div class="col-md-12">
        @include('section5.accept-inspection-unit.form-request')
    </div>
</div>

@if( isset( $application_inspection_unit->edited ) && $application_inspection_unit->edited == true  || isset( $application_inspection_unit->show ) && $application_inspection_unit->show == true )
    <fieldset class="white-box">
        <legend class="legend"><h5>ตรวจสอบคำขอ</h5></legend>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('checking_status') ? 'has-error' : ''}}">
                    {!! Form::label('checking_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::select('checking_status', [ 2 => 'เอกสารไม่ครบถ้วน', 3 => 'เอกสารครบถ้วน ส่งต่อให้ผู้อนุมัติ', 7 => 'ไม่รับคำขอ/Reject' ],!empty( $application_inspection_unit->checking_status )?$application_inspection_unit->checking_status:null,['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                        {!! $errors->first('checking_status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('checking_comment') ? 'has-error' : ''}}">
                    {!! Form::label('checking_comment', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('checking_comment', !empty($application_inspection_unit->checking_comment)?$application_inspection_unit->checking_comment:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                        {!! $errors->first('checking_comment', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('checking_by', 'ผู้ตรวจสอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('checking_by', !empty($application_inspection_unit->CheckingName)?$application_inspection_unit->CheckingName:null,  ['class' => 'form-control', 'disabled' => true]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('checking_date', 'วันที่ตรวจสอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('checking_date', !empty($application_inspection_unit->checking_date)?HP::DateThaiFull($application_inspection_unit->checking_date):null ,  ['class' => 'form-control', 'disabled' => true ]) !!}
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
@endif

@if( isset( $application_inspection_unit->approve ) && $application_inspection_unit->approve == true  || isset( $application_inspection_unit->show ) && $application_inspection_unit->show == true )
    <fieldset class="white-box">
        <legend class="legend"><h5>พิจารณาอนุมัติ </h5></legend>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('approve_status') ? 'has-error' : ''}}">
                    {!! Form::label('approve_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::select('approve_status', [ 5 => 'อนุมัติ', 6 => 'ไม่อนุมัติ ตรวจสอบอีกครั้ง', 7 => 'ไม่รับคำขอ/Reject' ],!empty( $application_inspection_unit->approve_status )?$application_inspection_unit->approve_status:null,['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                        {!! $errors->first('approve_status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('approve_comment') ? 'has-error' : ''}}">
                    {!! Form::label('approve_comment', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('approve_comment', !empty($application_inspection_unit->approve_comment)?$application_inspection_unit->approve_comment:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                        {!! $errors->first('approve_comment', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('approve_by', 'ผู้ตรวจสอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('approve_by', !empty($application_inspection_unit->ApproveName)?$application_inspection_unit->ApproveName:null,  ['class' => 'form-control', 'disabled' => true]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('approve_date', 'วันที่ตรวจสอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('approve_date', !empty($application_inspection_unit->approve_date)?HP::DateThaiFull($application_inspection_unit->approve_date):null ,  ['class' => 'form-control', 'disabled' => true ]) !!}
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
@endif


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        @can('view-'.str_slug('accept-inspection-unit'))
            <button class="btn btn-primary show_tag_a" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
        @endcan
        <a class="btn btn-default show_tag_a" href="{{url('/section5/accept-inspection-unit')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    
    </div>
</div>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>
        jQuery(document).ready(function() {

            $('#box-readonly').find('button[type="submit"]').remove();
            $('#box-readonly').find('.icon-close').parent().remove();
            $('#box-readonly').find('.fa-copy').parent().remove();
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true);
            $('#box-readonly').find('select').prop('disabled', true);
            $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
            $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
            $('#box-readonly').find('button').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.btn-remove-file').parent().remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.input_show_file').hide();
            
        });
    </script>
@endpush
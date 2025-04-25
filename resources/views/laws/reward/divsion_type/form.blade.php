@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
@endpush

<div class="form-group  required {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Form::label('division_category_id', 'หมวดหมู่', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('division_category_id',
        App\Models\Law\Basic\LawBasicDivisionCategory::Where('state', 1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
        null, 
        ['class' => 'form-control ',
        'placeholder'=>'- เลือกหมวดหมู่ -', 
         'required' => true]) !!}
        {!! $errors->first('division_category_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อประเภท', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('title', null , ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{{-- <div class="form-group  {{ $errors->has('reward_group_id') ? 'has-error' : ''}}">
    {!! Form::label('reward_group_id', 'กลุ่มผู้มีสิทธิ์ได้รับเงิน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('reward_group_id', 
        App\Models\Law\Basic\LawRewardGroup::Where('state', 1)->pluck('title', 'id'), 
        null, 
        ['class' => 'form-control ', 'placeholder'=>'- เลือกกลุ่มผู้มีสิทธิ์ได้รับเงิน -', 'required' => false]) !!}
        {!! $errors->first('reward_group_id', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

 
<div class="form-group">
  <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
    @can('view-'.str_slug('law-reward-divsion-type'))
        <a class="btn btn-default" href="{{url('/law/reward/divsion-type')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    @endcan
  </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    
    <script>

        $(document).ready(function() {

 

        });

        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


    </script>
@endpush

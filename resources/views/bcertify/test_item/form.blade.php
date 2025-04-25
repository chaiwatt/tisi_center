@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'รายการทดสอบ (TH):', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
    {!! Form::label('title_en', 'รายการทดสอบ (EN):', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('formula_id') ? 'has-error' : ''}}">
    {!! Form::label('formula_id', 'มาตรฐาน:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('formula_id', App\Models\Bcertify\Formula::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกมาตรฐาน-']) !!}
        {!! $errors->first('formula_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('test_branch_id') ? 'has-error' : ''}}">
    {!! Form::label('test_branch_id', 'สาขาการทดสอบ:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('test_branch_id', $test_branchs, null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกสาขาการทดสอบ-']) !!}
        {!! $errors->first('test_branch_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
<label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('test_item'))
            <a class="btn btn-default" href="{{url('/bcertify/test_item')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script type="text/javascript">

    jQuery(document).ready(function($) {

      //เมื่อเลือกมาตรฐาน
      $('#formula_id').change(function () {

        $('#test_branch_id').children(":not([value=''])").remove();

        var url = '{{ url('bcertify/test_branch/list') }}/'+$(this).val();
        $.ajax({
          'type': 'GET',
          'url': url,
          'success': function (datas) {

              $.each(datas, function(index, data) {
                $('#test_branch_id').append('<option value="'+index+'">'+data+'</option>');
              });

          }
        });

      });

    });

  </script>
@endpush

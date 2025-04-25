@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'รายการสอบเทียบ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('formula_id') ? 'has-error' : ''}}">
  {!! Form::label('formula_id', 'มาตรฐาน:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('formula_id', App\Models\Bcertify\Formula::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกมาตรฐาน-']) !!}
    {!! $errors->first('formula_id', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('calibration_branch_id') ? 'has-error' : ''}}">
  {!! Form::label('calibration_branch_id', 'สาขาการสอบเทียบ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('calibration_branch_id', $calibration_branchs, null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกสาขาการสอบเทียบ-']) !!}
    {!! $errors->first('calibration_branch_id', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('calibration_group_id') ? 'has-error' : ''}}">
  {!! Form::label('calibration_group_id', 'หมวดหมู่รายการสอบเทียบ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('calibration_group_id', $calibration_groups, null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกหมวดหมู่รายการสอบเทียบ-']) !!}
    {!! $errors->first('calibration_group_id', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
  {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
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
    @can('view-'.str_slug('calibration_item'))
    <a class="btn btn-default" href="{{url('/bcertify/calibration_item')}}">
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

      $('#calibration_branch_id').children(":not([value=''])").remove();

      var url = '{{ url('bcertify/calibration_branch/list') }}/'+$(this).val();
      $.ajax({
        'type': 'GET',
        'url': url,
        'success': function (datas) {

            $.each(datas, function(index, data) {
              $('#calibration_branch_id').append('<option value="'+index+'">'+data+'</option>');
            });

        }
      });

    });

    //เมื่อเลือกสาขาการสอบเทียบ
    $('#calibration_branch_id').change(function () {

      var formula_id = $('#formula_id').val();

      // $('#calibration_group_id').children(":not([value=''])").remove().change();
      $('#calibration_group_id').empty().append('<option value=""> - เลือกหมวดหมู่รายการสอบเทียบ -</option>').change();

      var url = '{{ url('bcertify/calibration_group/list') }}/'+formula_id+'/'+$(this).val();
      $.ajax({
        'type': 'GET',
        'url': url,
        'success': function (datas) {

            $.each(datas, function(index, data) {
              $('#calibration_group_id').append('<option value="'+index+'">'+data+'</option>');
            });

        }
      });

    });

  });

</script>
@endpush

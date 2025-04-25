@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<!-- Tag Input -->
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
<style>
  .bootstrap-tagsinput {
  width: 100% !important;
}
</style>
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'วิธีจัดทำ'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
  {!! Form::label('details', 'รายละเอียดย่อย'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('details', null, ['class' => 'form-control','id'=>'details', 'data-role' => 'tagsinput' , 'placeholder'=>'พิมพ์ Enter หรือ , เพื่อแบ่งรายการ']) !!}
    {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
  </div>
</div>
 
<div class="form-group required{{ $errors->has('period') ? 'has-error' : ''}}">
  {!! Html::decode(Form::label('period', 'ระยะเวลา'.' :', ['class' => 'col-md-4 control-label'])) !!}
  <div class="col-md-4">
        <div class=" input-group ">
          {!! Form::text('period', null, ['class' => 'form-control input_number', 'required' => true,'id'=>'period']) !!}
          <span class="input-group-addon bg-secondary b-0  "> เดือน </span>
        </div>
        {!! $errors->first('period', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
  {!! Form::label('state', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
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
    @can('view-'.str_slug('method'))
    <a class="btn btn-default" href="{{url('/basic/method')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')

<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- Tag input -->
<script src="{!! asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') !!}"></script>

<script type="text/javascript">

  $(document).ready(function() {
         $('.bootstrap-tagsinput').addClass('col-md-12');
         $('.bootstrap-tagsinput').find('input').prop('style','width:500px;');

          // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
          $(".input_number").on("keypress",function(e){
          var eKey = e.which || e.keyCode;
          if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
              return false;
          }
      });
  });

</script>
@endpush

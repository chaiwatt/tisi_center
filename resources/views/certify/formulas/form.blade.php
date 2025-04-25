@push('css')
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">


@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_standard', '<span class="text-danger">*</span> สาขา'.':', ['class' => 'col-md-4 control-label label-height'])) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('formulas_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('formulas_id', '<span class="text-danger">*</span> มาตรฐาน'.':', ['class' => 'col-md-4 control-label label-height'])) !!}
    <div class="col-md-6">
        {!! Form::select('formulas_id', 
        App\Models\Bcertify\Formula::where('applicant_type',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
         null,
        ['class' => 'form-control',
        'id'=>'formulas_id', 
        'required' => true,
        'placeholder'=>'-เลือกมาตรฐาน-']) !!}
         {!! $errors->first('formulas_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('image', '<span class="text-danger">*</span> รูปภาพ <span class="text-danger">(png,jpg)</span>'.':', ['class' => 'col-md-4 control-label label-height'])) !!}
    <div class="col-sm-6">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            @if(isset($formula) && !is_null($formula->image))
             <div class="fileinput-new thumbnail" >
                <img src="{!! asset('plugins/formulas/'.$formula->image) !!}" alt="profile pic" style="max-width: 200px; max-height: 200px;">
            </div>
            @endif  
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
            <div>
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new ">Select image</span>
                    <span class="fileinput-exists">Change</span>
                    <input id="image" name="image" {{ isset($formula) && is_null($formula->image)  ? 'required' : ''  }}   type="file" class="form-control file_image"/>
                </span>
                <a href="#" class="btn btn-danger fileinput-exists"data-dismiss="fileinput">Remove</a>
            </div>
        </div>
        <span class="help-block">{{ $errors->first('image', ':message') }}</span>
    </div>
</div>

<div class="form-group {{ $errors->has('imagery') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('imagery', '<span class="text-danger">*</span> รูปภาพ <span class="text-danger">(png,jpg)</span>'.':', ['class' => 'col-md-4 control-label label-height'])) !!}
    <div class="col-sm-6">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            @if(isset($formula) && !is_null($formula->imagery))
             <div class="fileinput-new thumbnail" >
                <img src="{!! asset('plugins/formulas/'.$formula->imagery) !!}" alt="profile pic" style="max-width: 200px; max-height: 200px;">
            </div>
            @endif  
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
            <div>
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new ">Select image</span>
                    <span class="fileinput-exists">Change</span>
                    <input id="imagery" name="imagery" {{ isset($formula) && is_null($formula->imagery)  ? 'required' : ''  }}   type="file" class="form-control file_image"/>
                </span>
                <a href="#" class="btn btn-danger fileinput-exists"data-dismiss="fileinput">Remove</a>
            </div>
        </div>
        <span class="help-block">{{ $errors->first('imagery', ':message') }}</span>
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        {{-- @can('view-'.str_slug('standardformulas')) --}}
            <a class="btn btn-default" href="{{url('/certify/formulas')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        {{-- @endcan --}}
    </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
<script>
    $(document).ready(function () {
        file_image();
     });
  //  Attach File
  function  file_image(){
      $('.file_image').change( function () {
              var fileExtension = ['png','jpg'];
              if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                  Swal.fire(
                  'ไม่ใช่ไฟล์รูปภาพที่อนุญาต',
                  '',
                  'info'
                  )
              this.value = '';
              return false;
              }
          }); 
  }
  </script>
@endpush

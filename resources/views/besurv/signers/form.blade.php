@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<div class="form-group required {{ $errors->has('tax_number') ? 'has-error' : ''}}">
    {!! Form::label('tax_number', 'เลขประจำตัวประชาชน:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('tax_number', null, ('required' == 'required') ? ['class' => 'form-control tax_number', 'required' => 'required'] : ['class' => 'form-control tax_number']) !!}
        {!! $errors->first('tax_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'ชื่อผู้ลงนาม:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('name_eng') ? 'has-error' : ''}}">
    {!! Form::label('name_eng', 'ชื่อผู้ลงนาม (ENG):', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name_eng', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('name_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('position') ? 'has-error' : ''}}">
    {!! Form::label('position', 'ตำแหน่ง:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('position', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=>'4'] : ['class' => 'form-control', 'rows'=>'4']) !!}
        {!! $errors->first('position', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('main_group') ? 'has-error' : ''}}">
    {!! Form::label('main_group', 'สังกัดกลุ่มงานหลัก:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('main_group[]', App\Models\Besurv\Department::selectRaw('CONCAT(depart_nameShort," ",depart_name) As title, did')->pluck('title', 'did'), null, ('required' == 'required') ? ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'main_group', 'data-allow-clear'=>'true', 'data-placeholder'=>'- เลือก สังกัดกลุ่มงานหลัก -', 'required' => 'required'] : ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'main_group', 'data-allow-clear'=>'true', 'data-placeholder'=>'- เลือก สังกัดกลุ่มงานหลัก -'] ) !!}
        {!! $errors->first('main_group', '<p class="help-block">:message</p>') !!}
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

<div class="form-group  {{ $errors->has('line_token') ? 'has-error' : ''}}">
    {!! Form::label('line_token', 'Line token:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('line_token', null, ['class' => 'form-control']) !!}
        {!! $errors->first('line_token', '<p class="help-block">:message</p>') !!}
    </div>
</div>

  <div class="form-group  {{ $errors->has('signed') ? 'has-error' : ''}}">
    {!! Form::label('signed', 'ลายเซ็นอิเล็กทรอนิกส์:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="checkbox">
            {!! Form::checkbox('signed', '1', !empty($signer->signed) && $signer->signed == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('signed', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('attach', 'ไฟล์แนบลายเซ็น'.' : ', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-6">
            @php
                $attach = !empty($signer->AttachFileAttachTo)?$signer->AttachFileAttachTo:[];             
            @endphp
            @if (!empty($attach))
            <div class="col-md-6" >
                <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                    title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                     {!! !empty($attach->filename) ? $attach->filename : '' !!}
                </a>
            </div>
                <div class="col-md-6" >
                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('funtions/get-delete/files/'.($attach->id).'/'.base64_encode('besurv/signers/'.$signer->id.'/edit') ) !!}" 
                        title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            @else
            <div class=" other_attach_item">
                <div class="col-md-12">
                       <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                              <div class="form-control" data-trigger="fileinput">
                              <i class="glyphicon glyphicon-file fileinput-exists"></i>
                              <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file">
                              <span class="fileinput-new">เลือกไฟล์</span>
                              <span class="fileinput-exists">เปลี่ยน</span>
                              <input type="file" name="attach" class="attach check_max_size_file" >
                              </span> 
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                       </div>
                       {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            @endif
        </div>
    </div>




<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('signers'))
            <a class="btn btn-default" href="{{url('/besurv/signers')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/inputmask/jquery.inputmask.bundle.js')}}"></script>
  <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

  <script type="text/javascript">
    $(document).ready(function () {
        $('.tax_number').inputmask('9-9999-99999-99-9');
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
          });
    });
  </script>
@endpush

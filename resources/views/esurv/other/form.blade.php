@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush
<div id="box-readonly">
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'เรื่อง:', ['class' => 'col-md-4 control-label required']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('inform_type') ? 'has-error' : ''}}">
  {!! Form::label('inform_type', 'ประเภทการแจ้ง:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('inform_type', HP::OtherTypes(), null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกประเภทการแจ้ง-']) !!}
    {!! $errors->first('inform_type', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
  {!! Form::label('detail', 'รายละเอียด:', ['class' => 'col-md-4 control-label required']) !!}
  <div class="col-md-6">
    {!! Form::textarea('detail', null, ['class' => 'form-control', 'required' => 'required', 'rows'=>'3']) !!}
    {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('tb3_Tisno') ? 'has-error' : ''}}">
  {!! Form::label('tb3_Tisno', 'มาตรฐาน (ถ้ามี):', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('tb3_Tisno[]', HP::TisList(), null, ['class' => 'select2 select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือกมาตรฐาน -']) !!}
    {!! $errors->first('tb3_Tisno', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  {!! Html::decode(Form::label('attach', 'ไฟล์แนบ (ถ้ามี):<br><span class="text-muted">รองรับไฟล์ .pdf และ .jpg ขนาดไม่เกิน 10 MB</span>', ['class' => 'col-md-4 control-label'])) !!}
  <div class="col-md-8">
    <button type="button" class="btn btn-sm btn-success" id="attach-add">
      <i class="icon-plus"></i>&nbsp;เพิ่ม
    </button>
  </div>
</div>

<div id="other_attach-box">

  @foreach ($attachs as $key => $attach)

  <div class="form-group other_attach_item">
    <div class="col-md-4">
      {!! Form::hidden('attach_filenames[]', $attach->file_name); !!}
    </div>
    <div class="col-md-3">
      {!! Form::text('attach_notes[]', $attach->file_note, ['class' => 'form-control', 'placeholder' => 'คำอธิบายไฟล์แนบ(ถ้ามี)']) !!}
    </div>
    <div class="col-md-3">

      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
        <div class="form-control" data-trigger="fileinput">
          <i class="glyphicon glyphicon-file fileinput-exists"></i>
          <span class="fileinput-filename"></span>
        </div>
        <span class="input-group-addon btn btn-default btn-file">
          <span class="fileinput-new">เลือกไฟล์</span>
          <span class="fileinput-exists">เปลี่ยน</span>
          {!! Form::file('attachs[]', null) !!}
        </span>
        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
      </div>
      {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="col-md-2">

      @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
        <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
      @endif

      <button class="btn btn-danger btn-sm attach-remove" type="button">
        <i class="icon-close"></i>
      </button>

    </div>

  </div>

  @endforeach

</div>

<div class="form-group {{ $errors->has('applicant_name') ? 'has-error' : ''}}">
  {!! Form::label('applicant_name', 'ชื่อผู้บันทึก:', ['class' => 'col-md-4 control-label required']) !!}
  <div class="col-md-6">
    {!! Form::text('applicant_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('applicant_name', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
  {!! Form::label('tel', 'เบอร์โทร:', ['class' => 'col-md-4 control-label required']) !!}
  <div class="col-md-6">
    {!! Form::text('tel', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
  {!! Form::label('email', 'Email:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
  </div>
</div>
</div>
<hr>
<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
  {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('state',
     [ '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'], 
     !empty($other->state) ? $other->state : '1',
     ['class' => 'form-control list_disabled','required'=>'required',
      'placeholder'=>'-เลือกสถานะ-']); !!}
    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group {{ $errors->has('remake') ? 'has-error' : ''}}">
    {!! Form::label('remake', 'ความคิดเห็นเพิ่มเติม:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('remake', null, ['class' => 'form-control list_disabled', 'rows'=>'3']) !!}
    </div>
  </div>
  <div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('', 'พิจารณา:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('',   !empty($other->user_updated->FullName) ?  $other->user_updated->FullName : auth()->user()->FullName, ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
<div class="form-group">
  <div class="col-md-offset-4 col-md-4">
    <input type="hidden" name="previousUrl" value="{{$previousUrl}}">
    <button class="btn btn-primary list_attach" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
    @can('view-'.str_slug('other'))
    <a class="btn btn-default list_attach"href="{{url("$previousUrl")}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')

<!-- icheck -->
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

<script type="text/javascript">

  $(document).ready(function() {

    //เพิ่มไฟล์แนบ
    $('#attach-add').click(function(event) {
      $('.other_attach_item:first').clone().appendTo('#other_attach-box');

      $('.other_attach_item:last').find('input').val('');
      $('.other_attach_item:last').find('a.fileinput-exists').click();
      $('.other_attach_item:last').find('a.view-attach').remove();

      ShowHideRemoveBtn();
    });

    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn();
    });

    ShowHideRemoveBtn();

  });

  function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

    if ($('.other_attach_item').length > 1) {
      $('.attach-remove').show();
    } else {
      $('.attach-remove').hide();
    }

  }

</script>
    <script>
        jQuery(document).ready(function() {
           // จัดการข้อมูลในกล่องคำขอ false
            $('#box-readonly').find('button[type="submit"]').remove();
            $('#box-readonly').find('.icon-close').parent().remove();
            $('#box-readonly').find('.fa-copy').parent().remove();
            $('#box-readonly').find('.list_attach').hide();
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true); 
             $('#box-readonly').find('select').prop('disabled', true);
             $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
             $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
             $('#box-readonly').find('button').prop('disabled', true);
             $('#box-readonly').find('button').remove();
             $('#box-readonly').find('button').remove();
            $('body').on('click', '.attach-remove', function() {
                $(this).parent().parent().parent().find('input[type=hidden]').val('');
                $(this).parent().remove();
            });
        });
    </script>
     

@endpush
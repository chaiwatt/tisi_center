@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('product') ? 'has-error' : ''}}">
    {!! Form::label('product', 'ผลิตภัณฑ์', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('product', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('product', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('product_groups_id') ? 'has-error' : ''}}">
    {!! Form::label('product_groups_id', 'กลุ่มผลิตภัณฑ์/สาขา', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('product_groups_id', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
        {!! $errors->first('product_groups_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'รายละเอียด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('description', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=>2] : ['class' => 'form-control', 'rows'=>2]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('standards_ref') ? 'has-error' : ''}}">
    {!! Form::label('standards_ref', 'มาตรฐานอ้างอิง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('standards_ref', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('standards_ref', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('attach', 'ข้อมูลเพิ่มเติม (ถ้ามี):', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
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

            <div class="col-md-6">

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
            </div>

            <div class="col-md-4"></div>
            <div class="col-md-6">
                {!! Form::text('attach_notes[]', $attach->file_note, ['class' => 'form-control', 'placeholder' => 'คำอธิบายไฟล์แนบ(ถ้ามี)']) !!}
            </div>

            <div class="col-md-2">

                <button class="btn btn-danger btn-sm attach-remove" type="button">
                    <i class="icon-close"></i>
                </button>

            </div>

        </div>

    @endforeach

</div>


<div class="form-group {{ $errors->has('commentator') ? 'has-error' : ''}}">
    {!! Form::label('commentator', 'ชื่อ - สกุล ผู้เสนอ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('commentator', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('commentator', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
    {!! Form::label('tel', 'เบอร์โทร', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('tel', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'E-mail', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('departments_id') ? 'has-error' : ''}}">
    {!! Form::label('departments_id', 'หน่วยงาน', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('departments_id', $departments, null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงาน -']) !!}
        {!! $errors->first('departments_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('other_departments') ? 'has-error' : ''}}">
    {!! Form::label('other_departments', 'ชื่อหน่วยงานอื่น', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('other_departments', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('other_departments', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
            <a class="btn btn-default" href="{{url('/idea-public')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <!-- input file -->
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script type="text/javascript">
      $(document).ready(function() {

          $('#other_departments').parent().parent().hide();

          $('#departments_id').change(function () {
              if($(this).val()=='9999'){
                 $('#other_departments').parent().parent().show(400);
              } else{
                  $('#other_departments').parent().parent().hide(400);
              }
          });

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
          ShowHideForce();

      });

      function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

          if ($('.other_attach_item').length > 1) {
              $('.attach-remove').show();
          } else {
              $('.attach-remove').hide();
          }

      }

      function ShowHideForce(){

          if($('#tis_force1').prop('checked')){//ทั่วไป
              $('label[for="issue_date"]').text('วันที่ประกาศใช้');
              $('.tis_force').hide();
          }else{//บังคับ
              $('label[for="issue_date"]').text('วันที่มีผลบังคับใช้');
              $('.tis_force').show();
          }

      }

      </script>
@endpush

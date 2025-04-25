@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="col-md-12">
    <div class="form-group">
         {!! Form::label('standard_name', 'ชื่อ มอก. :', ['class' => 'col-md-2 control-label']) !!}
         <div class="col-md-10">
             {!! Form::text('standard_name', null, ['class' => 'form-control', 'disabled']) !!}
         </div>
     </div>
</div>

 <div class="col-md-6">
     <div class="form-group">
         {!! Form::label('standard_no', 'เลข มอก. :', ['class' => 'col-md-4 control-label']) !!}
         <div class="col-md-6">
             {!! Form::text('standard_no', null, ['class' => 'form-control', 'disabled']) !!}
         </div>
     </div>
     <div class="form-group">
         {!! Form::label('product_group', 'กลุ่มผลิตภัณฑ์/สาขา :', ['class' => 'col-md-4 control-label']) !!}
         <div class="col-md-6">
             {!! Form::text('product_group', null, ['class' => 'form-control', 'disabled']) !!}
         </div>
     </div>
 </div>
 <div class="col-md-6">
     <div class="table-responsive">
         <table class="table table-bordered" id="myTable">
             <thead>
                <tr bgcolor="#5B9BD5">
                  <th class="text-center" style="color: white">เอกสารประกอบข้อคิดเห็น</th>
                </tr>
             </thead>
             <tbody>
                @if ($public_draft)
                    @foreach ($public_draft->getFiles() as $file)
                    <tr>
                        <td style="padding:7px;">
                            <span style="margin-top:5px;" class="pull-left">{{$loop->iteration}}. {{$file->file_name}}</span>
                            <a href="{{url('tis/public_draft/files/'.basename($file->file_path))}}" target="_blank" class="pull-right">
                            <i class="mdi mdi-file-pdf text-danger pull-right" style="font-size:25px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @endif
             </tbody>
         </table>

         <div class="pagination-wrapper"></div>

     </div>
 </div>

<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('comment', 'ความคิดเห็น:', ['class' => 'col-md-4 control-label required']) !!}
        <div class="col-md-8">
          <div class="row">
              <label>
                      <input type="radio" class="check" id="comment_all_agree" name="comment" data-radio="iradio_flat-red" value="all_agree" checked>
                      <label for="comment_all_agree">เห็นชอบตามร่างทุกประการ</label>
              </label>
              <label>
                      <input type="radio" class="check" id="comment_most_agree" name="comment" data-radio="iradio_flat-red" value="most_agree">
                      <label for="comment_most_agree">เห็นชอบตามร่างเป็นส่วนใหญ่ แต่มีข้อคิดเห็นเพิ่มเติมดังนี้</label>
              </label>
              <label>
                      <input type="radio" class="check" id="comment_not_agree" name="comment" data-radio="iradio_flat-red" value="not_agree">
                      <label for="comment_not_agree">ไม่ให้ความเห็นชอบ เพราะเหตุผลดังนี้</label>
              </label>
              <label>
                      <input type="radio" class="check" id="comment_not_comment" name="comment" data-radio="iradio_flat-red" value="not_comment">
                      <label for="comment_not_comment">ไม่ออกความเห็น เพราะเหตุผลดังนี้</label>
              </label>
          </div>
        </div>
    </div>
</div>

<div class="col-md-12 repeater" id="tb-comment">
    <button type="button" class="btn btn-success btn-sm pull-right clearfix" id="plus-row">
        <i class="icon-plus" aria-hidden="true"></i> เพิ่ม
    </button>
    <div class="clearfix"></div>
    <br/>

    <table class="table color-bordered-table primary-bordered-table">
        <thead>
        <tr>
            <th width="5%" class="text-center">No.</th>
            <th width="7%" class="text-center">เลขหน้า <span class="text-danger">*</span></th>
            <th width="7%" class="text-center">ข้อที่</th>
            <th class="text-center">ข้อคิดเห็น</th>
            <th class="text-center">เหตุผลที่เสนอ</th>
            <th class="text-center">เอกสารที่เกี่ยวข้อง <span class="text-danger">*</span></th>
            <th class="text-center">ลบ</th>
        </tr>
        </thead>
        <tbody id="table-body">
        <tr class="repeater-item">
            <td class="text-center">
                1
            </td>
            <td class="text-center text-top">
                {!! Form::text('detail_comment[page][]', null, ['class' => 'form-control']) !!}
                {!! Form::hidden('detail_comment[id][]', null, ['class' => 'form-control']) !!}
            </td>
            <td class="align-right text-top">
                {!! Form::text('detail_comment[no][]', null, ['class' => 'form-control']) !!}
            </td>
            <td class="text-top">
                {!! Form::text('detail_comment[comment_detail][]', null, ['class' => 'form-control']) !!}
            </td>
            <td class="text-top">
                {!! Form::text('detail_comment[reason][]', null, ['class' => 'form-control']) !!}
            </td>
            <td class="align-top text-top">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                      <span class="fileinput-new">เลือกไฟล์</span>
                      <span class="fileinput-exists">เปลี่ยน</span>
                      {!! Form::file('detail_comment[attachs][]', null) !!}
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                       data-dismiss="fileinput">ลบ</a>
                </div>
                {!! $errors->first('detail_comment[attachs]', '<p class="help-block">:message</p>') !!}
            </td>
            <td align="center" class="text-top">
                <button type="button" class="btn btn-danger btn-xs repeater-remove">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="clearfix"></div>
<div class="form-group">
    {!! Form::label('attachs', 'เอกสารแนบเพิ่มเติม :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-8">
        <p class="form-control-static text-danger">รองรับไฟล์ .pdf jpg. ขนาดไม่เกิน 10Mb</p>
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
<div class="form-group">
    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <button type="button" class="btn btn-sm btn-success pull-right" id="attach-add">
            <i class="icon-plus"></i>&nbsp;เพิ่ม
        </button>
    </div>
</div>

<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'ชื่อ - สกุล ผู้ให้ข้อมูล :', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
    {!! Form::label('tel', 'เบอร์โทร :', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-6">
        {!! Form::text('tel', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'E-mail :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('department_id') ? 'has-error' : ''}}">
    {!! Form::label('department_id', 'หน่วยงาน :', ['class' => 'col-md-4 control-label required']) !!}
    <div class="col-md-3">
        {!! Form::select('department_id', App\Models\Basic\Department::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'- เลือกประเภทหน่วยงาน -']) !!}
        {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-1">
        <input type="checkbox" class="check" id="department_other" data-checkbox="icheckbox_flat-red">
        <label for="department_other">อื่น ๆ</label>
    </div>
    <div class="col-md-2">
        {!! Form::text('department_name', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('department_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('comment-standard-drafts'))
            {{-- <a class="btn btn-default" href="{{url('/tis/comment-standard-drafts')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a> --}}
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

  <script type="text/javascript">
        $(document).ready(function () {

            $('#tb-comment').hide();

            $('#comment_all_agree').on('ifChecked', function () {
                if ($(this).is(':checked')){
                    $('#tb-comment').hide(200);
                } else {
                    $('#tb-comment').show(200);
                }
            });
            $('#comment_most_agree, #comment_most_agree, #comment_not_agree, #comment_not_comment').on('ifChecked', function () {
                if ($(this).is(':checked')){
                    $('#tb-comment').show(200);
                } else {
                    $('#tb-comment').hide(200);
                }
            });

        let mock = $('.repeater-item').clone();
        setRepeaterIndex();

        //เพิ่มตำแหน่งงาน
        $('#plus-row').click(function () {

            let item = mock.clone();

            //Clear value select
            item.find('select').val('');
            item.find('select').prev().remove();
            item.find('select').removeAttr('style');
            item.find('select').select2();

            item.find('.repeater-remove').on('click', function () {
                removeIndex(this)
            });

            item.find('.btn-user-select').on('click', function () {
                modalHiding($(this).closest('.modal'));
            });
            item.find('.modal').on('show.bs.modal', function () {
                modalOpening($(this));
            });
            item.find('.modal').on('hidden.bs.modal', function () {
                modalClosing($(this));
            });

            item.find('.status').on('change', function () {
                statusChange($(this));
            });

            item.find('.select-all').on('change', function () {
                checkedAll($(this));
            });

            item.appendTo('#table-body');

            setRepeaterIndex();

        });

        $('.status').change(function () {
            statusChange($(this));
        });

        $('.repeater-remove').click(function () {
            removeIndex(this)
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

            //เมื่อเลือกหน่วยงานอื่นๆ
            $('#department_other').on('ifChecked', function(event){
              ControlOtherDepartment();
            });

            //เมื่อไม่เลือกหน่วยงานอื่นๆ
            $('#department_other').on('ifUnchecked', function(event){
              ControlOtherDepartment();
            });

            //ควบคุมหน่วยงานอื่นๆ
            ControlOtherDepartment();

        });

        function setRepeaterIndex() {
            let n = 1;
            $('#table-body').find('tr.repeater-item').each(function () {
                $(this).find('td:first').html(n);
                n++;
            });
        }

        function removeIndex(that) {
            that.closest('tr').remove();

            setRepeaterIndex();
        }

        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 1) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }

        }

        function ControlOtherDepartment(){

            if($('#department_other').prop('checked')){
              $('#department_id').attr('disabled', true);
              $('#department_name').attr('disabled', false);
            }else{
              $('#department_id').attr('disabled', false);
              $('#department_name').attr('disabled', true);
            }

        }

    </script>
@endpush

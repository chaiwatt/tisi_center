@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<div class="m-t-20">

   <div class="form-group required {{ $errors->has('tis_no') ? 'has-error' : ''}}">
      {!! Form::label('tis_no', 'เลข มอก. :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('tis_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;', 'readonly'=>'readonly'] : ['class' => 'form-control', 'style' => 'font-size:small', 'readonly'=>'readonly']) !!}
        {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

   <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
      {!! Form::label('title', 'ชื่อ มอก. (TH) :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;', 'readonly'=>'readonly'] : ['class' => 'form-control', 'style' => 'font-size:small', 'readonly'=>'readonly']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group required {{ $errors->has('title_en') ? 'has-error' : ''}}">
      {!! Form::label('title_en', 'ชื่อ มอก. (EN) :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;', 'readonly'=>'readonly'] : ['class' => 'form-control', 'style' => 'font-size:small', 'readonly'=>'readonly']) !!}
        {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group {{ $errors->has('document') ? 'has-error' : ''}}">
        {!! Form::label('document', 'เอกสารที่เกี่ยวข้อง :', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-9">
            @foreach ($attachs as $key => $attach)
                <div  style="float: left;">

                    <div class="checkbox checkbox-success">
                        {!! Form::checkbox('file_checkbox_selected['.$key.']', 'y', !empty($attach) && $attach->file_checkbox=='y'?true:false , ['class' => 'form-control']) !!}
                        <label style="padding-left:10px">
                            @if(!empty($attach->file_name) && $attach->file_from=='standard')
                                    {{ $attach->file_client_name }} {{ HP::getSetAttachName($attach->file_note) }}
                            @elseif(!empty($attach->file_name) && $attach->file_from=='set_standard')
                                    {{ $attach->file_client_name }} {{ HP::getSetAttachName($attach->file_note) }}
                            @else
                                    {{ "ไม่มีไฟล์แนบ" }}
                            @endif
                        </label>
                        <input type="hidden" name="file_name_selected[]" value="{{ $attach->file_name }}">
                        <input type="hidden" name="file_client_name_selected[]" value="{{ $attach->file_client_name }}">
                        <input type="hidden" name="file_note_selected[]" value="{{ $attach->file_note }}">
                        <input type="hidden" name="file_from_selected[]" value="{{ $attach->file_from ?? null }}">
                    </div>

                </div>

                <div style="float: left; padding-left:10px">
                    @if(!empty($attach->file_name) && $attach->file_from=='standard' && HP::checkFileStorage($attach_std_path.$attach->file_name))
                        <a href="{{ HP::getFileStorage($attach_std_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-danger btn-sm"><i class="fa fa-search"></i></a>
                    @elseif(!empty($attach->file_name) && $attach->file_from=='set_standard' && HP::checkFileStorage($attach_set_std_path.$attach->file_name))
                        <a href="{{ HP::getFileStorage($attach_set_std_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                    @else
                        {{ "" }}
                    @endif
                </div>

                <div style="clear: both; margin: 0pt; padding: 3pt;"></div>
            @endforeach
        </div>
    </div>
    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
      <div class="col-md-2 text-right ">
           <button type="button" class="btn btn-sm btn-success" id="attach-add">
             <i class="icon-plus"></i>&nbsp;เพิ่ม
          </button>
      </div>
      <div class="col-md-9" id="other_attach-box">
            {{-- <div class="other_attach_item"> --}}
              @if ( isset($note_std_draft->attach_note) && count($note_std_draft->attach_note) > 0)
                  @foreach ($note_std_draft->attach_note as $key => $attach)
                    <div class="row  other_attach_item">
                          <div class="col-md-4">
                            {!! Form::hidden('attach_filenames['.$key.']', (!empty($attach['file_name'])? $attach['file_name']:null)   ) !!}
                            {!! Form::select('attach_text['.$key.']',
                             App\Models\Basic\SetAttach::Where('state',1)->pluck('title', 'id'), 
                               $attach['file_note']??null,
                             ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อรายการไฟล์แนบ -']) !!}
                        </div>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i><span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    {!! Form::file('attach_notes['.$key.']', null) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('attach_notes', '<p class="help-block">:message</p>') !!}
                        </div>
                        @if($attach['file_name']!='' && HP::checkFileStorage($attach_path.$attach['file_name']))
                          <div class="col-md-1">
                                <a href="{{ HP::getFileStorage($attach_path.$attach['file_name']) }}" target="_blank" class="view-attach btn btn-info btn-sm"
                                    title="{{(!empty($attach['file_client_name'])? $attach['file_client_name']:'') }}">
                                  <i class="fa fa-search"></i>
                                </a>
                          </div>
                        @endif
                        <div class="col-md-1">
                            <button class="btn btn-danger btn-sm attach-remove" type="button">
                            <i class="icon-close"></i>
                            </button>
                        </div>
                    </div>
                  @endforeach
              @endif

            {{-- </div> --}}
 
      </div>
    </div>
 
    <div class="form-group {{ $errors->has('status_publish') ? 'has-error' : ''}}">
      {!! Form::label('status_publish', 'เผยแพร่ผ่านหน้าเว็บ', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
         <div class="checkbox">
          {!! Form::checkbox('status_publish', '1', !empty($note_std_draft->status_publish) &&  $note_std_draft->status_publish == 1 ? true  : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
         </div>
       {!! $errors->first('status_publish', '<p class="help-block">:message</p>') !!}
      </div>
    </div>
 
    <div class="form-group required{{ $errors->has('start_date') ? 'has-error' : ''}}">
      {!! Form::label('start_date', 'ตั้งแต่วันที่', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-4">
          <div class="input-daterange input-group date-range" id="date-range">
            {!! Form::text('start_date', null, ['class' => 'form-control','required'=> true]) !!}
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('end_date', null, ['class' => 'form-control','required'=> true]) !!}
          </div>
      </div>
    </div>
 
    <div class="form-group required {{ $errors->has('title_draft') ? 'has-error' : ''}}">
        {!! Form::label('title_draft', 'ชื่อเรื่องประกาศรับฟังความคิดเห็นร่างกฎกระทรวง :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
        {!! Form::text('title_draft', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('title_draft', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

     
    <div class="form-group  {{ $errors->has('title_draft') ? 'has-error' : ''}}">
      {!! Form::label('title_draft', 'สรุปผลการแสดงความคิดเห็น: ', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-7">
        <div class="table-responsive">
          <table class="table color-bordered-table info-bordered-table">
              <thead>
                <tr>
                    <th class="text-center" width="1%">#</th>
                    <th class="text-center" width="90%">ความคิดเห็น</th>
                    <th class="text-center" width="9%">จำนวน</th>
                </tr>
                </thead>
                <tbody>
                  @if (!empty($note_std_draft->comments) && count($note_std_draft->comments) > 0)
                    @foreach ($note_std_draft->comments as  $key => $item)
                          <tr>
                            <td  class="text-center" >{{  $key +1 }}</td>
                            <td>{{ $item->title ?? null  }}</td>
                            <td  class="text-center" >{{ $item->number ?? null  }}</td>
                        </tr>
                    @endforeach
                  @endif
                
              </tbody>
          </table>
      </div>
      </div>
  </div>


     <div class="form-group {{ $errors->has('result_draft') ? 'has-error' : ''}}"  style="margin-top: 3rem;">
      {!! Form::label('result_draft', 'ผลการเวียนร่าง :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-5">
        {!! Form::select('result_draft', ['1'=>'แก้ไขมาตรฐาน', '2'=>'ประกาศเป็นมาตรฐานบังคับ'], null, ['class' => 'form-control', 'id'=>'result_draft', 'placeholder'=>'- เลือก ผลการเวียนร่าง -']) !!}
        {!! $errors->first('result_draft', '<p class="help-block">:message</p>') !!}
      </div>
    </div>


  <div class="row" id="show_decree" @if($note_std_draft->result_draft!='2') style="display:none" @endif>
    <div class="col-md-6">
      <div class="form-group {{ $errors->has('decree') ? 'has-error' : ''}}">
        {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
        <label class="control-label"><b>ประกาศกฤษฎีกา/ประกาศกฎกระทรวง</b></label>
        </div>
      </div>

      <div class="form-group {{ $errors->has('minis_dated_compulsory') ? 'has-error' : ''}}">
        {!! Form::label('minis_dated_compulsory', 'ลงวันที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
          {!! Form::text('minis_dated_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off']) !!}
          {!! $errors->first('minis_dated_compulsory', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group {{ $errors->has('issue_date_compulsory') ? 'has-error' : ''}}">
        {!! Form::label('issue_date_compulsory', 'วันที่มีผลบังคับใช้ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
          {!! Form::text('issue_date_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off']) !!}
          {!! $errors->first('issue_date_compulsory', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group {{ $errors->has('amount_date_compulsory') ? 'has-error' : ''}}">
        {!! Form::label('amount_date_compulsory', 'จำนวนวัน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
          {!! Form::number('amount_date_compulsory', null, ['class' => 'form-control', 'autocomplete'=>'off']) !!}
          {!! $errors->first('amount_date_compulsory', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
        {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
        <label class="control-label"><b>ราชกิจจานุเบกษา</b></label>
        </div>
      </div>

      <div class="form-group {{ $errors->has('gaz_date_compulsory') ? 'has-error' : ''}}">
        {!! Form::label('gaz_date_compulsory', 'วันที่ประกาศ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
          {!! Form::text('gaz_date_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
          {!! $errors->first('gaz_date_compulsory', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group {{ $errors->has('gaz_no_compulsory') ? 'has-error' : ''}}">
        {!! Form::label('gaz_no_compulsory', 'เล่ม :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
          {!! Form::text('gaz_no_compulsory', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
          {!! $errors->first('gaz_no_compulsory', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group {{ $errors->has('gaz_space_compulsory') ? 'has-error' : ''}}">
            {!! Form::label('gaz_space_compulsory', 'ตอนที่ :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-7">
              {!! Form::text('gaz_space_compulsory', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
              {!! $errors->first('gaz_space_compulsory', '<p class="help-block">:message</p>') !!}
            </div>
      </div>
    </div>

  </div>



    <div class="form-group" style="margin-top: 3rem;">
      {!! Form::label('created_by', 'ผู้บันทึก :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-4">
        {!! Form::text('created_by', $note_std_draft->createdName??auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'readonly'=>'readonly'] : ['class' => 'form-control', 'readonly'=>'readonly']) !!}
        {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group">
      {!! Form::label('created_at', 'วันที่บันทึก :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-4">
        {!! Form::text('created_at', HP::DateThai($note_std_draft->created_at)  ?? HP::DateThai(date('Y-m-d hh:mm:ss')), ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'readonly'=>'readonly'] : ['class' => 'form-control', 'readonly'=>'readonly']) !!}
        {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
      </div>
    </div>
 
    <div class="form-group" style="margin-top: 3rem;">
      <div class="col-md-offset-4 col-md-8">

          {{-- <button class="btn btn-success" type="submit">
              <i class="fa fa-book"></i> ฉบับร่าง
          </button> --}}
          <button class="btn btn-primary" type="submit">
              <i class="fa fa-paper-plane"></i> บันทึก
          </button>
          @can('view-'.str_slug('set_standard'))
              <a class="btn btn-default" href="{{url('/tis/note_std_draft')}}">
                  <i class="fa fa-rotate-left"></i> ยกเลิก
              </a>
          @endcan
      </div>
    </div>
</div>


@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script type="text/javascript">

            //ช่วงวันที่
            jQuery('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
      $(document).ready(function() {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
          $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
          });
          ShowHideRemoveBtn();
            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#other_attach-box');

                var last_new = $('.other_attach_item:last');

                $(last_new).find('.view-filename').text('');
                $(last_new).find('.view-attach').remove();
                $(last_new).find('input[type="hidden"]').val('');
                $(last_new).find('span.fileinput-filename').text('');
                // $(last_new).find('i.fileinput-exists').remove();

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                ShowHideRemoveBtn();
                orderKeyAttach();

            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().remove();
                ShowHideRemoveBtn();
                orderKeyAttach();
            });



        $('#result_draft').change(function(){
            var draft_val = $(this).val();
              if(draft_val=='2'){
                $('#show_decree').show(300);
              }else{
                $('#show_decree').find('input').val('');
                $('#show_decree').hide(500);
              }
        });

        $('#amount_date_compulsory').change(function(){
            var amountDate = $(this).val();
            if(amountDate){
              if($('#gaz_date_compulsory').val()!=""){
                var array = $('#gaz_date_compulsory').val().split("/");
                var gazDate = array[1]+"/"+array[0]+"/"+parseInt(array[2]-543);
                var newDate = newDayAdd(gazDate,parseInt(amountDate));
                $('#issue_date_compulsory').val(newDate);
              }else{
                alert("วันที่ประกาศ ในราชกิจจานุเบกษา ไม่มีค่า");
              }
            } else {
              $('#issue_date_compulsory').val('');
            }
        });

        $('.notOver30').on('change',function () {
            try {
                if(this.files[0].size > 30000000){//
                    alert("ขนาดไฟล์ใหญ่เกิน 30 MB");
                    this.value = "";
                }
            }catch (e) {}
        });

      });

      function checkNone(value) {
          return value !== '' && value !== null && value !== undefined;
      }

      function newDayAdd(inputDate,addDay){
        var d = new Date(inputDate);
        d.setDate(d.getDate()+addDay);
        mkMonth=d.getMonth()+1;
        mkMonth=new String(mkMonth);
        if(mkMonth.length==1){
            mkMonth="0"+mkMonth;
        }
        mkDay=d.getDate();
        mkDay=new String(mkDay);
        if(mkDay.length==1){
            mkDay="0"+mkDay;
        }
        mkYear=d.getFullYear();
        return mkDay+"/"+mkMonth+"/"+parseInt(mkYear+543); // แสดงผลลัพธ์ในรูปแบบ วัน/เดือน/ปี ไทย
      }
    function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ
      if ($('.other_attach_item').length > 1) {
          $('.attach-remove').show();
      } else {
          $('.attach-remove').hide();
      }
    }
      function orderKeyAttach() {
            var rows = $('#other_attach-box').children(); //แถวทั้งหมด
            rows.each(function(index, el) {
                var attach_filenames = $(el).find('div > input[type="hidden"]');
                    attach_filenames.attr('name', 'attach_filenames[' + index + ']');
                var attach_notes = $(el).find('div > div.fileinput > span > input');
                    attach_notes.attr('name', 'attach_notes[' + index + ']');
                var attach_text = $(el).find('div > select');
                   attach_text.attr('name', 'attach_text[' + index + ']');

            });
        }

    </script>
@endpush

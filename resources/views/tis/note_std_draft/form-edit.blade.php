@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="m-t-20">

   <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
      {!! Form::label('title', 'เลข มอก. :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

   <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
      {!! Form::label('title', 'ชื่อ มอก. (TH) :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group required {{ $errors->has('title_en') ? 'has-error' : ''}}">
      {!! Form::label('title_en', 'ชื่อ มอก. (EN) :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
        {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
        {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
      </div>
    </div>



    <div class="form-group required {{ $errors->has('title_en') ? 'has-error' : ''}}">
      {!! Form::label('title_en', 'เอกสารที่เกี่ยวข้อง :', ['class' => 'col-md-2 control-label']) !!}
      <div class="col-md-9">
           <div class="checkbox checkbox-success">
                  {!! Form::checkbox('announce_compulsory', 'y', !empty($standard) && $standard->announce_compulsory=='y'?true:false , ['class' => 'form-control', 'id'=>'tis_force1']) !!}
                  <label for="announce_compulsory" style="padding-left:10px"> filename.pdf</label>
                            <a href="#" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>

                </div>
           <div class="checkbox checkbox-success">
                  {!! Form::checkbox('announce_compulsory', 'y', !empty($standard) && $standard->announce_compulsory=='y'?true:false , ['class' => 'form-control', 'id'=>'tis_force1']) !!}
                  <label for="announce_compulsory" style="padding-left:10px"> ไฟล์ต้นฉบับ</label>
                            <a href="#" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>

                </div>

      </div>
    </div>

    <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'ชื่อเรื่องประกาศรับฟังความคิดเห็นร่างกฎกระทรวง :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <input type="text" id="title" name="title" class="form-control" placeholder="เรื่อง" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>



    {{-- <div class="form-group required">
        {!! Form::label('attach', 'ไฟล์แนบ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <button type="button" class="btn btn-sm btn-success" id="attach-add">
                <i class="icon-plus"></i>&nbsp;เพิ่ม
            </button>
        </div>
    </div> --}}

    {{-- <div id="other_attach-box">
        <div class="form-group other_attach_item">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-2 m-t-5">
                <input type="text" id="attach_name[]" name="attach_name[]" class="form-control" placeholder="ชื่อไฟล์" required>
            </div>
            <div class="col-md-4 m-t-5">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                    <input type="file" name="attach_files[]" class="notOver30" required>
                </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
            <div class="col-md-1 m-t-5">
                <button class="btn btn-danger btn-sm attach-remove" type="button" style="margin-top: 3px">
                    <i class="icon-close"></i>
                </button>
            </div>
        </div>
    </div> --}}

    {{-- <div class="form-group required {{ $errors->has('staff_group') ? 'has-error' : ''}}">
        {!! Form::label('staff_group', 'กลุ่มงานเจ้าหน้าที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="staff_group" id="staff_group" class="form-control" required>
                <option value="" selected>- กลุ่มงานเจ้าหน้าที่ -</option>
            </select>
            {!! $errors->first('staff_group', '<p class="help-block">:message</p>') !!}
        </div>
    </div> --}}


    <div class="form-group" style="margin-top: 3rem;">
        <div class="col-md-offset-4 col-md-8">

            <button class="btn btn-success" type="submit">
                <i class="fa fa-book"></i> ฉบับร่าง
            </button>
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('set_standard'))
                <a class="btn btn-default" href="{{url('/tis/public_draft')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
</div>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

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

        $('.notOver30').on('change',function () {
            try {
                if(this.files[0].size > 30000000){//
                    alert("ขนาดไฟล์ใหญ่เกิน 30 MB");
                    this.value = "";
                }
            }catch (e) {}
        });

        $(document).on('change', '.draft_type', function () {
            let val_type = $(this).val();
            if (checkNone(val_type)){
                get_format(val_type);
            }
            $('#set_format_id').val('').change();
            $('#set_standard_id').val('').change();
            $('#tis_no_select').val('').change();
            $('#product_group_id').val('').change();
            $('#staff_group').val('').change();
        });

        $(document).on('change', '#set_format_id', function () {
            let val_type = $('input[name=public_draft_type]:checked').val();
            let standard_type = $(this).find('option:selected').val();
            if (checkNone(val_type) &&  checkNone(standard_type)){
                get_Number_Standard(val_type);
            }
            $('#set_standard_id').val('').change();
            $('#tis_no_select').val('').change();
            $('#product_group_id').val('').change();
            $('#staff_group').val('').change();
        });

        $(document).on('change', '#tis_no_select', function () {
            let val_type = $('input[name=public_draft_type]:checked').val();
            let tis = $(this).find('option:selected').val();
            let tis_no = $(this).find('option:selected').attr('data-tis_no');

            if (checkNone(tis)){
                $('#tis_no').val(tis_no);
                standardName_branch(val_type,tis);
            }
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

      function get_format(val) {
          let selected = $('#set_format_id');
          $.ajax({
              url: '{!! url('tis/public_draft/api/getFormat.api') !!}',
              method: "POST",
              data: {val_type: val, _token: '{!! csrf_token() !!}'}
          }).done(function (msg) {
              let data = JSON.parse(JSON.stringify(msg));
              if (data.status === true) {
                  selected.empty();
                  selected.append('<option value="">- เลือกรูปแบบการกำหนดมาตรฐาน -</option>');
                  $.each(data.format, function (k, v) {
                      selected.append('<option value="' + v.id + '">' + v.title + '</option>');
                  });
                  selected.val('').change();
              } else {
                  alert('ไม่พบข้อมูลรูปแบบกำหนดมาตรฐาน');
                  selected.val('').change();
              }
          });
      }

      function get_Number_Standard(val) {
          let selected = $('#tis_no_select');
          $.ajax({
              url: '{!! url('tis/public_draft/api/getNumberFormula.api') !!}',
              method: "POST",
              data: {val_type: val, _token: '{!! csrf_token() !!}'}
          }).done(function (msg) {
              let data = JSON.parse(JSON.stringify(msg));
              if (data.status === true) {
                  selected.empty();
                  selected.append('<option value="">- เลือกเลขมาตรฐาน -</option>');
                  $.each(data.number_formula, function (k, v) {
                      let year = v.tis_year !== undefined ? v.tis_year:v.start_year !== undefined ? v.start_year:'-';
                      let tis_book = v.tis_book != null && v.tis_book != '-' ? ' เล่ม '+v.tis_book:'';

                      selected.append('<option value="' + v.id + '" data-tis_no="'+v.tis_no+'">' + v.tis_no+tis_book+'-'+year + ' : '+v.title+'</option>');
                  });
                  selected.val('').change();

              } else {
                  alert('ไม่พบข้อมูลเลขมาตรฐาน');
                  selected.val('').change();
              }
          });
      }

      function standardName_branch(val,tis) {
          let standard_select = $('#set_standard_id');
          let product_group_select = $('#product_group_id');
          let staff_select = $('#staff_group');
          $.ajax({
              url: '{!! url('tis/public_draft/api/getStandardName_branch.api') !!}',
              method: "POST",
              data: {val_type: val,tis_no:tis, _token: '{!! csrf_token() !!}'}
          }).done(function (msg) {
              let data = JSON.parse(JSON.stringify(msg));
              console.log(data);
              if (data.status === true) {
                  standard_select.empty();
                  standard_select.append('<option value="">- ชื่อมาตรฐาน -</option>');
                  product_group_select.empty();
                  product_group_select.append('<option value="">- สาขา -</option>');
                  staff_select.empty();
                  staff_select.append('<option value="">- กลุ่มงานเจ้าหน้าที่ -</option>');
                  //////

                  if (checkNone(data.name_branch)){
                      let stan_id = data.name_branch.id;
                      standard_select.append('<option value="'+stan_id+'">'+data.name_branch.title+'</option>');
                      standard_select.val(stan_id).change();
                  }else{
                      standard_select.val('').change();
                  }

                  if (checkNone(data.product_group)){
                      let pro_group_id = data.product_group.id !== null ? data.product_group.id:0;
                      product_group_select.append('<option value="'+pro_group_id+'">'+data.product_group.title+'</option>');
                      product_group_select.val(pro_group_id).change();
                  }else{
                      product_group_select.val('').change();
                  }

                  if (checkNone(data.staff_group)){
                      let staff_group = data.staff_group.id;
                      staff_select.append('<option value="'+staff_group+'">'+data.staff_group.order+' - '+data.staff_group.title+'</option>');
                      staff_select.val(staff_group).change();
                  }else{
                      staff_select.val('').change();
                  }

              } else {
                  alert('ไม่พบข้อมูล');
                  standard_select.val('').change();
                  product_group_select.val('').change();
              }
          });
      }

      function checkNone(value) {
          return value !== '' && value !== null && value !== undefined;
      }

    </script>
@endpush

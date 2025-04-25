@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="m-t-40">
    <div class="form-group {{ $errors->has('public_draft_type') ? 'has-error' : ''}}">
        {!! Form::label('public_draft_type', ' ', ['class' => 'col-md-4 control-label']) !!}
        <div class="radio-list">
            <label class="radio-inline">
                <div class="radio radio-info">
                    <input type="radio" name="public_draft_type" id="radio1" value="0" {{$public_draft->public_draft_type == 0 ? 'checked':null}} class="draft_type">
                    <label for="radio1">เวียนร่าง</label>
                </div>
            </label>
            <label class="radio-inline">
                <div class="radio radio-info">
                    <input type="radio" name="public_draft_type" id="radio2" value="1" {{$public_draft->public_draft_type == 1 ? 'checked':null}} class="draft_type">
                    <label for="radio2">เวียนทบทวน</label>
                </div>
            </label>
        </div>
        {!! $errors->first('public_draft_type', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group required {{ $errors->has('set_format_id') ? 'has-error' : ''}}">
        {!! Form::label('set_format_id', 'รูปแบบการกำหนดมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            @php
                use App\Models\Basic\SetFormat;
                $formatArr = null;
                if ($public_draft->public_draft_type == 0){
                    $formatArr = SetFormat::whereIn('id',[1,2])->get();
                }elseif ($public_draft->public_draft_type == 1){
                    $formatArr = SetFormat::where('id',2)->get();}
            @endphp
            <select name="set_format_id" id="set_format_id" class="form-control" required>
                <option value="" selected>- เลือกรูปแบบการกำหนดมาตรฐาน -</option>
                @foreach ($formatArr as $format)
                    @if ($format->id == $public_draft->set_format_id)
                        <option value="{{$format->id}}" selected>{{$format->title ?? '-'}}</option>
                        @else
                        <option value="{{$format->id}}">{{$format->title ?? '-'}}</option>
                    @endif
                @endforeach
            </select>
            {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('tis_no') ? 'has-error' : ''}}">
        {!! Form::label('tis_no', 'เลขมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            @php

                use App\Models\Tis\SetStandard;
                use App\Models\Tis\Standard;
                $number_formula = null;
                if ($public_draft->public_draft_type == 1 && $public_draft->set_format_id == 2){ //ดึงจาก D112 // ทบทวน // เวียนทบทวน
                    $number_formula = Standard::where('state', 1)->get();
                }else{ // ดึงจาก D115 เวียนร่าง
                    $number_formula = SetStandard::where('state', 1)->get();}
            @endphp
            <select name="tis_no_select" id="tis_no_select" class="form-control" required>
                <option value="" selected>- เลือกเลขมาตรฐาน -</option>
                @foreach ($number_formula as $formula)
                    <?php
                        $year = $formula->tis_year ?? $formula->start_year ?? '-';
                        $tis_book = (!empty($formula->tis_book) && $formula->tis_book != "-") ? ' เล่ม '.$formula->tis_book:'';
                    ?>
                    @if ($formula->id == $public_draft->set_standard_id)
                        <option value="{{$formula->id}}" selected>{{$formula->tis_no.$tis_book.'-'.$year.' : '.$formula->title}}</option>
                        @else
                        <option value="{{$formula->id}}">{{$formula->tis_no.$tis_book.'-'.$year.' : '.$formula->title}}</option>
                    @endif
                @endforeach
            </select>
            <input type="hidden" name="tis_no" id="tis_no" value="{{$public_draft->tis_no}}">
            {!! $errors->first('tis_no_select', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('set_standard_id') ? 'has-error' : ''}}">
        {!! Form::label('set_standard_id', 'ชื่อมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="set_standard_id" id="set_standard_id" class="form-control" required>
                <option value="">- ชื่อมาตรฐาน -</option>
                <option value="{{$public_draft->set_standard_id}}" selected>{{$public_draft->StandardName}}</option>
            </select>
            {!! $errors->first('set_standard_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
        {!! Form::label('product_group_id', 'สาขา :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="product_group_id" id="product_group_id" class="form-control" required>
                <option value="">- สาขา -</option>
                <option value="{{$public_draft->product_group_id}}" selected>{{$public_draft->getStand_Branch()->title ?? '-'}}</option>
            </select>
            {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'เรื่อง :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="title" name="title" class="form-control" placeholder="เรื่อง" required value="{{$public_draft->title}}">
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('number_book') ? 'has-error' : ''}}">
        {!! Form::label('number_book', 'เลขหนังสือ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="number_book" name="number_book" class="form-control" placeholder="เลขหนังสือ" required value="{{$public_draft->number_book}}">
            {!! $errors->first('number_book', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('mask_date') ? 'has-error' : ''}}">
        {!! Form::label('mask_date', 'ลงวันที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="mask_date" name="mask_date" class="form-control mydatepicker" required value="{{$public_draft->mask_date}}">
            {!! $errors->first('mask_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('anniversary_date') ? 'has-error' : ''}}">
        {!! Form::label('anniversary_date', 'วันครบกำหนด :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="anniversary_date" name="anniversary_date" class="form-control mydatepicker" required value="{{$public_draft->anniversary_date}}">
            {!! $errors->first('anniversary_date', '<p class="help-block">:message</p>') !!}
            <label class="m-t-10"><input type="checkbox" class="check" name="lock_qr" value="locked" {{$public_draft->lock_qr == 'locked' ? 'checked':null}}> &nbsp;ล็อกวัน </label>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('attach', 'ไฟล์แนบ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <button type="button" class="btn btn-sm btn-success" id="attach-add">
                <i class="icon-plus"></i>&nbsp;เพิ่ม
            </button>
        </div>
    </div>

    <div id="other_attach-box">
        <div class="form-group other_attach_item">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-2 m-t-5">
                <input type="text" id="attach_name[]" name="attach_name[]" class="form-control" placeholder="ชื่อไฟล์">
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
                    <input type="file" name="attach_files[]" class="notOver30">
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
    </div>

    <div class="form-group required {{ $errors->has('staff_group') ? 'has-error' : ''}}">
        {!! Form::label('staff_group', 'กลุ่มงานเจ้าหน้าที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="staff_group" id="staff_group" class="form-control" required>

                @if ($public_draft->basic_staff_groups_id && $public_draft->getStaff() && $public_draft->getStaff()->title)
                    <option value="{{$public_draft->basic_staff_groups_id}}" selected>{{$public_draft->getStaff()->order.' - '.$public_draft->getStaff()->title ?? '-'}}</option>
                    @else
                    <option value="" selected>- กลุ่มงานเจ้าหน้าที่ -</option>
                @endif

            </select>
            {!! $errors->first('staff_group', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('result_draft') ? 'has-error' : ''}} {{$public_draft->public_draft_type == 1 ? 'show':'hide'}}" id="result_draft_div">
        {!! Form::label('result_draft', 'ผลการเวียนทบทวน:', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="result_draft" id="result_draft" class="form-control">
                <option value="" selected>- เลือก -</option>
                @if (!is_null($public_draft->result_draft))
                    <option value="1" {{$public_draft->result_draft == 1 ? 'selected':null}}>ใช้มาตรฐานเดิม</option>
                    <option value="2" {{$public_draft->result_draft == 2 ? 'selected':null}}>ทบทวนมาตรฐาน</option>
                    @else
                    <option value="1">ใช้มาตรฐานเดิม</option>
                    <option value="2">ทบทวนมาตรฐาน</option>
                @endif
            </select>
            {!! $errors->first('result_draft', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group" style="margin-top: 3rem;">
        <div class="col-md-offset-6 col-md-8">

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
@if ($public_draft->getFiles()->count() > 0)
    <hr>
    <div id="appoint_files_table">
        <h3 class="m-b-10">ไฟล์แนบ</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th class="text-white text-center">#</th>
                    <th class="text-white text-center">ชื่อไฟล์</th>
                    <th class="text-white text-center">บันทึกวันที่</th>
                    <th class="text-white text-center">ลบ</th>
                </tr>
                </thead>
                <tbody id="appoint_files_body">
                @foreach ($public_draft->getFiles() as $file)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">
                            <a href="{{url('tis/public_draft/files/'.basename($file->file_path))}}" target="_blank">
                                {{$file->file_name ?? '-'}}
                            </a>
                        </td>
                        <td class="text-center">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y') ?? '-'}}</td>
                        <td class="text-center">
                            <a href="{{url('tis/delete/file').'/'.basename($file->file_path).'/'.$file->token}}" class="btn btn-danger btn-xs" onclick="return confirm('ต้องการลบไฟล์นี้ใช่หรือไม่ ?')">
                                <i class="fa fa-remove"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
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
                if (val_type == 1){
                    $('#result_draft_div').removeClass('hide').addClass('show');
                    $('#result_draft').val('').change();
                }else{
                    $('#result_draft_div').removeClass('show').addClass('hide');
                    $('#result_draft').val('').change();
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
            let selected = $('#tis_no');
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

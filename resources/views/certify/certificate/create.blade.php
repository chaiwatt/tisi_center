@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img{
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มข้อมูลใบรับรองระบบงาน</h3>
{{--                    @can('view-'.str_slug('board'))--}}
                        <a class="btn btn-success pull-right" href="{{url('certificate')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    {{--@endcan--}}
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => 'certificate','method'=>'POST','id'=>'addForm', 'class' => 'form-horizontal', 'files' => true]) !!}

                        <div class="form-group">
                            <label class="control-label col-md-4"></label>
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <div class="radio radio-info">
                                        <input type="radio" name="radio" id="radio1" value="option1" checked class="clickRadio">
                                        <label for="radio1">ออกใบรับรองในระบบ</label>
                                    </div>
                                </label>
                                <label class="radio-inline">
                                    <div class="radio radio-info">
                                        <input type="radio" name="radio" id="radio2" value="option2" class="clickRadio">
                                        <label for="radio2">ออกใบรับรองนอกระบบ</label>
                                    </div>
                                </label>
                            </div>
                        </div>


                    <div id="request_numberDiv" class="form-group {{ $errors->has('requestNumber') ? 'has-error' : ''}}">
                        {!! Form::label('requestNumber', 'เลขที่ใบคำขอ :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('requestNumber', null,['id'=>'request_number','class' => 'form-control', 'placeholder'=>'เลขที่ใบคำขอ']) !!}
                            {!! $errors->first('requestNumber', '<p class="help-block">* กรุณาใส่เลขที่ใบคำขอ</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('department_type') ? 'has-error' : ''}}">
                        {!! Form::label('assessment_type', 'ประเภทการตรวจประเมิน :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <select name="assessment_type" id="assessment_type" class="form-control" required>
                                <option value="" selected>-เลือกประเภทการตรวจประเมิน-</option>
                                <option value="1">CB</option>
                                <option value="2">IB</option>
                                <option value="3">LAB ทดสอบ</option>
                                <option value="4">LAB สอบเทียบ</option>
                            </select>
                            {!! $errors->first('assessment_type', '<p class="help-block">* กรุณาใส่ประเภทการตรวจประเมิน</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('unit_name') ? 'has-error' : ''}}">
                        {!! Form::label('unit_name', 'ชื่อหน่วยตรวจ/หน่วยรับรอง/ห้องปฏิบัติการ :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('unit_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('unit_name', '<p class="help-block">* กรุณาใส่ชื่อหน่วยตรวจ/หน่วยรับรอง/ห้องปฏิบัติการ</p>') !!}
                        </div>
                    </div>

                    <div id="lab_statusDiv" class="form-group {{ $errors->has('lab_status') ? 'has-error' : ''}} m-b-20" style="display: none;">
                        {!! Form::label('lab_status', 'สถานภาพห้องปฏิบัติการ :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6 col-md-3" style="margin-top: 7px;">
                                    <input class="checkbox-info"  type="checkbox" name="lab_status[]" value="0"> &nbsp;ถาวร
                                </div>
                                <div class="col-6 col-md-3" style="margin-top: 7px;">
                                    <input class="checkbox-info"  type="checkbox" name="lab_status[]" value="1"> &nbsp;ชั่วคราว
                                </div>
                                <div class="col-6 col-md-3" style="margin-top: 7px;">
                                    <input class="checkbox-info"  type="checkbox" name="lab_status[]" value="2"> &nbsp;เคลื่อนที่
                                </div>
                                <div class="col-6 col-md-3" style="margin-top: 7px;">
                                    <input class="checkbox-info"  type="checkbox" name="lab_status[]" value="3"> &nbsp;นอกสถานที่
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('cerFileNumber') ? 'has-error' : ''}}">
                        {!! Form::label('cerFileNumber', 'เลขที่ใบรับรอง :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('cerFileNumber', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('cerFileNumber', '<p class="help-block">* กรุณาใส่เลขที่ใบรับรอง</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('cerNumber') ? 'has-error' : ''}}">
                        {!! Form::label('cerNumber', 'หมายเลขการรับรอง :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('cerNumber', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('cerNumber', '<p class="help-block">* กรุณาใส่เลขหมายเลขการรับรอง</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('standardNumber') ? 'has-error' : ''}}">
                        {!! Form::label('standardNumber', 'เลขมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <select name="standardNumber" id="standardNumber" class="form-control pull-left" required disabled>
                                <option value="" selected>- เลือกประเภทการตรวจประเมินก่อน -</option>
                            </select>
                            {!! $errors->first('standardNumber', '<p class="help-block">* กรุณาใส่เลขมาตรฐาน</p>') !!}
                        </div>
                    </div>

{{--                    <div class="form-group {{ $errors->has('department_type') ? 'has-error' : ''}}">--}}
{{--                        {!! Form::label('branch', 'สาขา :', ['class' => 'col-md-4 control-label']) !!}--}}
{{--                        <div class="col-md-6">--}}
{{--                                <select name="branch" id="branch" class="form-control pull-left" required disabled>--}}
{{--                                    <option value="" selected>-เลือกประเภทการตรวจประเมินก่อน-</option>--}}
{{--                                </select>--}}
{{--                            {!! $errors->first('branch', '<p class="help-block">:message</p>') !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="form-group {{ $errors->has('branch') ? 'has-error' : ''}}">
                        {!! Form::label('branch', 'สาขา :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <select name="branch[]" id="branch" class=" pull-left select2-multiple" required disabled multiple data-placeholder='  - เลือกประเภทการตรวจประเมินก่อน -'>
                            </select>
                            {!! $errors->first('branch', '<p class="help-block">* กรุณาใส่สาขา</p>') !!}
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('certified_date') ? 'has-error' : ''}}">
                        {!! Form::label('certified_date', 'วันที่ออกใบรับรอง:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('certified_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                            {!! $errors->first('certified_date', '<p class="help-block">* กรุณาใส่วันที่ออกใบรับรอง</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('certified_exp') ? 'has-error' : ''}}">
                        {!! Form::label('certified_exp', 'วันที่ออกใบรับรองหมดอายุ:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('certified_exp', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                            {!! $errors->first('certified_exp', '<p class="help-block">* กรุณาใส่วันที่ออกใบรับรองหมดอายุ</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('CertificateAttach') ? 'has-error' : ''}}">
                        {!! Form::label('attachCer', 'ไฟล์แนบใบรับรอง:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('CertificateAttach', null) !!}
                                    </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('CertificateAttach', '<p class="help-block">* กรุณาเพิ่มไฟล์แนบใบรับรอง</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('attach', 'ไฟล์แนบใบอื่นๆ:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <button type="button" class="btn btn-sm btn-success" id="attach-add">
                                <i class="icon-plus"></i>&nbsp;เพิ่ม
                            </button>
                        </div>
                    </div>

                    <div id="other_attach-box">
                        <div class="form-group other_attach_item">
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                {!! Form::text('attach_filenames[]', null, ['class' => 'form-control', 'placeholder' => 'ชื่อไฟล์']) !!}
                            </div>
                            <div class="col-md-4">
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

                            <div class="col-md-2 text-left" style="margin-top: 3px">
                                <button class="btn btn-danger btn-sm attach-remove" type="button">
                                    <i class="icon-close"></i>
                                </button>
                            </div>

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

                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-4">

                            <button class="btn btn-primary" type="button" onclick="checkOption()">
                                <i class="fa fa-paper-plane"></i> บันทึก
                            </button>
{{--                            @can('view-'.str_slug('committee'))--}}
                                <a class="btn btn-default" href="{{url('certificate')}}">
                                    <i class="fa fa-rotate-left"></i> ยกเลิก
                                </a>
                            {{--@endcan--}}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>

    <script type="text/javascript">

        var $uploadCrop;
        var submitted = false;
        var assignment = null;

        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            $("form").submit(function() {
                submitted = true;
            });

            window.onbeforeunload = function () {
                if (!submitted) {
                    return 'คุณต้องการออกจากหน้านี้ใช่หรือไม่?';
                }
            };

            //เพิ่มตำแหน่งงาน
            $('#work-add').click(function() {

                $('#work-box').children(':first').clone().appendTo('#work-box'); //Clone Element

                var last_new = $('#work-box').children(':last');

                //Clear value text
                $(last_new).find('input[type="text"]').val('');

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                //Clear Radio
                $(last_new).find('.check').each(function(index, el) {
                    $(el).prependTo($(el).parent().parent());
                    $(el).removeAttr('style');
                    $(el).parent().find('div').remove();
                    $(el).iCheck();
                    $(el).parent().addClass($(el).attr('data-radio'));
                });

                //Change Button
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger work-remove');
                $(last_new).find('button').html('<i class="icon-close"></i> ลบ');

                resetOrder();

            });

            //ลบตำแหน่ง
            $('body').on('click', '.work-remove', function() {

                $(this).parent().parent().parent().parent().remove();

                resetOrder();

            });

            //Crop image
            $uploadCrop = $('#upload-demo').croppie({

                enableExif: true,

                viewport: {

                    width: 140,

                    height: 140,

                },

                boundary: {

                    width: 200,

                    height: 200

                }

            });

            $('#upload').on('change', function () {

                $('#upload-demo').removeClass('hide');
                $('#image-show').addClass('hide');

                var reader = new FileReader();

                reader.onload = function (e) {

                    $uploadCrop.croppie('bind', {

                        url: e.target.result

                    }).then(function(){

                        console.log('jQuery bind complete');

                    });

                }

                reader.readAsDataURL(this.files[0]);

            });

            $('#form-save').click(function(event) {

                //เลื่อนมาแถบแรก
                $('.tab-pane').removeClass('active in');
                $('#home1').addClass('active in');

                //คัดลอกข้อมูลภาพที่ Crop
                CropFile();

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


            ///////////////////////////////

            $('.clickRadio').on('change',function () {
                if ($(this).val() === 'option1'){
                    $('#request_numberDiv').show(300);
                }

                if ($(this).val() === 'option2'){
                    $('#request_number').val('');
                    $('#request_numberDiv').hide(300);
                }
            });

            $('#assessment_type').on('change',function () {
                let assessment_type = $(this).find('option:selected').val();
                assignment = assessment_type;
                if (assessment_type !== ''){
                    if (assessment_type == 1){
                        $('#cerNumber').removeAttr('required');
                    }else{
                        $('#cerNumber').attr('required','required');
                    }
                    getApplicantTypeAjax(assessment_type);
                    getBranchAjax(assessment_type);
                }else{
                    clearNumberStandard();
                    clearBranch();
                }
            })

        });

        function getApplicantTypeAjax(assessment_type) {
            if (assessment_type === '3' || assessment_type === '4'){
                assessment_type = '3';
                $('#lab_statusDiv').show(300);
            }else{
                $('#lab_statusDiv').hide(300);
                $("input[name='lab_status[]']").prop('checked',false);
            }
            $.ajax({
                url: '{!! url('certificate/api/getApplicantType.api') !!}',
                method: "POST",
                data: {assessment_type: assessment_type,_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.parse(JSON.stringify(msg)));
                let number_stan = $('#standardNumber');
                if (data.status === true) {
                    number_stan.empty();
                    number_stan.append('<option value="">- เลือกเลขมาตรฐาน -</option>');
                    number_stan.val('').change();
                    $.each(data.formula, function (key,val) {
                        number_stan.append('<option value="'+val.id+'">'+val.title+" ("+val.title_en+")"+'</option>');
                    });
                    number_stan.prop('disabled',false);
                }else{
                    alert('ไม่พบข้อมูลเลขมาตรฐาน');
                    clearNumberStandard();
                }
            });
        }

        function clearNumberStandard() {
            let number_stan = $('#standardNumber');
            number_stan.empty();
            number_stan.append('<option value="">- เลือกประเภทการตรวจประเมินอีกครั้ง -</option>');
            number_stan.val('').change();
            number_stan.prop('disabled',true);
        }

        function clearBranch() {
            let branch = $('#branch');
            branch.empty();
            branch.append('<option value="">- เลือกประเภทการตรวจประเมินอีกครั้ง -</option>');
            branch.val('').change();
            branch.prop('disabled',true);
        }

        function getBranchAjax(assessment_type) {
            $.ajax({
                url: '{!! url('certificate/api/getBranch.api') !!}',
                method: "POST",
                data: {assessment_type: assessment_type,_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.parse(JSON.stringify(msg)));
                let branch = $('#branch');
                if (data.status === true) {
                    branch.empty();
                    // branch.append('<option value="">-เลือกสาขา-</option>');
                    branch.attr('data-placeholder','  - เลือกสาขา -');
                    branch.val('').change();
                    $.each(data.branch, function (key,val) {
                        branch.append('<option value="'+val.id+'">'+val.title+" ("+val.title_en+")"+'</option>')
                    });
                    branch.prop('disabled',false);
                }else{
                    alert('ไม่พบข้อมูลสาขา');
                    clearBranch();
                }
            });
        }


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

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#work-box').children().each(function(index, el) {
                $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
                $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
            });

        }

        function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

            var croppied = $uploadCrop.croppie('get');

            $('#top').val(croppied.points[1]);
            $('#left').val(croppied.points[0]);
            $('#bottom').val(croppied.points[3]);
            $('#right').val(croppied.points[2]);
            $('#zoom').val(croppied.zoom);

            $uploadCrop.croppie('result', {

                type: 'canvas',

                size: 'viewport'

            }).then(function (resp) {

                $('#croppied').val(resp);

            });
        }

        function checkOption() {
            let radio = $('input[name=radio]:checked').val();
            let request_number = $('#request_number').val();
            if (radio === 'option1'){
                if (request_number !== '' && request_number !== undefined && request_number !== null){
                    if (assignment != "1") {
                        let check = $('#cerNumber').val();
                        if (check != null && check !== ''){
                            $('#addForm').submit();
                        }else{
                            alert('กรุณาใส่หมายเลขการรับรอง');
                        }
                    }else{
                        //$('#request_number').val('');
                        $('#addForm').submit();
                    }
                }else{
                    alert('กรุณาใส่เลขที่ใบคำขอ');
                }
            }
            else{
                if (assignment != "1") {
                    let check = $('#cerNumber').val();
                    if (check != null && check !== ''){
                        $('#request_number').val('');
                        $('#addForm').submit();
                    }else{
                        alert('กรุณาใส่หมายเลขการรับรอง');
                    }
                }else{
                    $('#request_number').val('');
                    $('#addForm').submit();
                }
            }
        }
    </script>

@endpush


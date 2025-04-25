@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="col-md-8">
            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                {!! Form::label('taxid', 'เลขคำขอ', ['class' => 'col-md-5 control-label label-filter']) !!}
                <div class="col-md-7">
                    {!! Form::text('taxid', null, ['class' => 'form-control', 'placeholder'=>'', 'required' => true]); !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('judgement_date') ? 'has-error' : ''}}">
                {!! Form::label('judgement_date', 'วันที่ทบทวนผลการตรวจประเมิน'.':', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    <div class="input-group">
                        {!! Form::text('judgement_date', null, ('' == 'required') ? ['class' => 'form-control mydatepicker',
                          'required' => 'required', 'placeholder' => 'yyyy/mm/dd', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'yyyy/mm/dd', 'autocomplete' => 'off', 'required' => 'required']) !!}
                        {!! $errors->first('judgement_date', '<p class="help-block">:message</p>') !!}
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('other_attach') ? 'has-error' : ''}}">
                {!! Form::label('other_attach', 'หนังสือแต่งตั้งคณะทบทวนผลการตรวจประเมิน'.':', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! $errors->first('other_attach', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        {!! Form::file('other_attach', null, ['required']) !!}
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 repeater">
    <button type="button" class="btn btn-success btn-sm pull-right clearfix" id="plus-row">
        <i class="icon-plus" aria-hidden="true"></i>
        เพิ่ม
    </button>
    <div class="clearfix"></div>
    <br/>

    <table class="table color-bordered-table primary-bordered-table">
        <thead>
        <tr>
            {{-- <th class="text-center">ลำดับ</th>  --}}
            <th class="text-center">สถานะผู้ทบทวนผลการตรวจประเมิน</th>
            <th class="text-center">ชื่อผู้ทบทวนผลการตรวจประเมิน</th>
            <th class="text-center"></th>
            <th class="text-center">จากหน่วยงาน</th>
            <th class="text-center">เครื่องมือ</th>
        </tr>
        </thead>
        <tbody id="table-body">
        <tr class="repeater-item">
            <td class="text-center text-top">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    <div class="col-md-9">
                        {!! Form::select('status', $status_auditor,
                          null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกสถานะผู้ทบทวนผลการตรวจประเมิน-', 'data-name'=>'status', 'required'=>true]); !!}
                    </div>
                </div>
            </td>
            {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
            <td class="align-right text-top td-users">
                {!! Form::text('filter_search', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'filter_search','required' => true]); !!}
            </td>
            {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
            <td class="text-top">
                <button type="button" class="btn btn-primary repeater-modal-open" data-toggle="modal" data-target="#exampleModal"
                        data-whatever="@mdo"> select
                </button>
                <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                <div class="modal fade repeater-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="exampleModalLabel1">ข้อมูลผู้ทบทวนผลกำรตรวจประเมิน</h4>
                            </div>
                            <div class="modal-body">
                                {{-- ------------------------------------------------------------------------------------------------- --}}
                                <div class="white-box">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-bordered color-table primary-table" id="myTable" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><input type="checkbox" class="select-all"></th>
                                                    <th>ชื่อผู้ทบทวนผลกำรตรวจประเมิน</th>
                                                    <th>หน่วยงาน</th>
                                                    <th>ตำแหน่ง</th>
                                                    <th>เพิ่มเติม</th>
                                                </tr>
                                                </thead>
                                                <tbody class="tbody-auditor">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <div class="pull-right">
                                        {!! Form::button('<i class="icon-check"></i> เลือก', ['type' => 'button', 'class' => 'btn btn-primary btn-user-select']) !!}

                                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                            {!! __('ยกเลิก') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="align-top text-top td-departments">
                {!! Form::text('department', null, ('' == 'required') ? ['class' => 'form-control item', 'required' => 'required'] : ['class' => 'form-control item','readonly'=>'readonly','data-name'=>'department']) !!}
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

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit" id="form-save">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>

        <a class="btn btn-default" href="{{url('/certify/board_review')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>

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
        $(document).ready(function () {
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy'
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

            $('.btn-user-select').on('click', function () {
                modalHiding($(this).closest('.modal'));
            });

            $('.modal').on('show.bs.modal', function () {
                modalOpening($(this));
            });

            $('.modal').on('hidden.bs.modal', function () {
                modalClosing($(this));
            });

            $('.select-all').change(function () {
                checkedAll($(this));
            });

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
        });

        function checkedAll(that) {
            let checkboxes = $(that).closest('.modal').find('.tbody-auditor').find('input[type=checkbox]');
            checkboxes.each(function() {
                $(this).prop('checked', $(that).is(':checked'));
            });
        }

        function statusChange(that) {
            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            tdUsers.children().remove();
            tdDepartments.children().remove();

            let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
            input.appendTo(tdUsers);
            let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
            inputDepart.appendTo(tdDepartments);

            let tbody = $(that).closest('tr').find('.modal').find('tbody');
            let id = $(that).val();
            if (id !== "" && id !== undefined) {
                let url = '/certify/auditor/status/' + id;
                $.ajax({
                    type: 'get',
                    url: url,
                    success: function (resp) {
                        tbody.children().remove();
                        let auditors = resp.auditors;
                        let n = 1;
                        auditors.forEach(auditor => {
                            let tr = $('<tr rolw="row" class="odd">');
                            let td = $('<td class="sorting_1">');
                            td.text(n + '.');
                            td.appendTo(tr);

                            let td2 = $('<td>');
                            let input = $('<input type="checkbox" id="master" value="'+auditor.id+'">');
                            input.attr('data-value', auditor.name_th).attr('data-department', auditor.department.title);
                            input.on('change', function () {
                                changeSelectAll($(this));
                            });
                            input.appendTo(td2);
                            td2.appendTo(tr);

                            let td3 = $('<td>');
                            td3.text(auditor.name_th);
                            td3.appendTo(tr);

                            let td4 = $('<td>');
                            td4.text(auditor.department.title);
                            td4.appendTo(tr);

                            let td5 = $('<td>');
                            td5.text(auditor.position);
                            td5.appendTo(tr);

                            let td6 = $('<td>');
                            let button = $('<button class="btn btn-primary">');
                            let icon = $('<i class="glyphicon glyphicon-info-sign" aria-hidden="true">');
                            icon.appendTo(button);
                            button.appendTo(td6);
                            td6.appendTo(tr);

                            tr.appendTo(tbody);
                        });
                    },
                    error: function (resp) {
                        console.log(resp);
                    },
                })
            } else if (id === "") {
                tbody.children().remove();
            }
        }

        var tempCheckboxes = [];
        function modalHiding(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            let empty = true;
            let groupVal = "";
            tdUsers.children().remove();
            tdDepartments.children().remove();
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    let val = $(this).data('value');
                    let depart = $(this).data('department');
                    let input = $('<input type="text" class="form-control item" data-name="temp_users[]" value="'+val+'" readonly>');
                    input.appendTo(tdUsers);
                    let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" value="'+depart+'" readonly>');
                    inputDepart.appendTo(tdDepartments);
                    empty = false;

                    groupVal += groupVal !== "" ? ";" + $(this).val() : $(this).val();

                    tempCheckboxes.push($(this));
                }
            });

            let input = $('<input type="hidden" class="form-control item" data-name="users" value="'+groupVal+'">');
            input.appendTo(tdUsers);

            if (empty) {
                let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
                input.appendTo(tdUsers);
                let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
                inputDepart.appendTo(tdDepartments);
            }

            $(that).modal('hide');

            setRepeaterIndex();
        }

        function modalOpening(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    tempCheckboxes.push($(this));
                    checkedCount++;
                }
            });

            changeSelectAll(that);

        }

        function modalClosing(that) {
            let checkboxes = $(that).find('input[type=checkbox]');
            checkboxes.prop('checked', false);
            tempCheckboxes.forEach(function (checkbox) {
                checkboxes.each(function () {
                    if (checkbox.val() === $(this).val()) {
                        $(this).prop('checked', true);
                    }
                });
            });
            tempCheckboxes = [];
        }

        function changeSelectAll(that) {
            let modal = $(that).closest('.modal');
            let checkboxes = modal.find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    checkedCount++;
                }
            });

            if (checkedCount === checkboxes.length && checkboxes.length > 0) {
                modal.find('.select-all').prop('checked', true);
            } else {
                modal.find('.select-all').prop('checked', false);
            }
        }

        function setRepeaterIndex() {
            let group_name = "group";
            let n = 0;
            $('#table-body').find('tr.repeater-item').each(function () {
                $(this).find('.item').each(function () {
                    let dataName = $(this).data('name');
                    if (dataName !== undefined) {
                        let strArray = '';
                        if (dataName.includes('[]')) {
                            strArray = "[]";
                            dataName = dataName.substring(0, dataName.length - 2);
                        }

                        $(this).attr('name', group_name + "[" + n + "]" + "[" + dataName + "]" + strArray);
                    }
                });

                let newId = 'modal-' + n;
                $(this).find('.repeater-modal').attr('id', newId);
                $(this).find('.repeater-modal-open').attr('data-target', '#'+newId);
                n++;
            });
        }

        function removeIndex(that) {
            that.closest('tr').remove();

            setRepeaterIndex();
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
    </script>

@endpush

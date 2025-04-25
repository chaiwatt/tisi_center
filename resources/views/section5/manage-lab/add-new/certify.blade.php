<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลใบรับรอง</h5></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_cerno_export', 'เลขที่ได้รับการรับรอง'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('certificate_cerno_export', null, ['class' => 'form-control certificate_cerno_export', 'id' => 'certificate_cerno_export', 'placeholder'=>'กรอกเลขที่ได้รับการรับรอง']); !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="button" id="btn_std_export" value="1"><i class="fa fa-database"></i> ดึงจากฐานของ สมอ.</button>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('certificate_accereditatio_no', 'หมายเลขการรับรอง'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('certificate_accereditatio_no', null, ['class' => 'form-control certificate_accereditatio_no', 'id' => 'certificate_accereditatio_no']); !!}
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_issue_date', 'วันที่ได้รับ'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('certificate_issue_date', null, ['class' => 'form-control mydatepicker', 'placeholder'=>'dd/mm/yyyy']); !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_expire_date', 'วันที่หมดอายุ'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('certificate_expire_date', null, ['class' => 'form-control mydatepicker', 'placeholder'=>'dd/mm/yyyy']); !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success show_tag_a" type="button" id="btn_cer_add"><i class="icon-plus"></i> เพิ่ม</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered repeater_audit_type_1" id="table-certificate">
                            <thead>
                                <tr>
                                    <th class="text-center" width="25%">ใบรับรองเลขที่</th>
                                    <th class="text-center" width="20%">หมายเลขการรับรอง</th>
                                    <th class="text-center" width="15%">วันที่ได้รับ</th>
                                    <th class="text-center" width="15%">วันที่หมดอายุ</th>
                                    <th class="text-center" width="20%">ไฟล์ใบรับรอง</th>
                                    <th class="text-center" width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-audit-1" class="text-center">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include ('section5.manage-lab.modals.modal-certify')
        </fieldset>

    </div>
</div>

@push('js')
    <script>
        jQuery(document).ready(function() {

            $("body").on('click', '#btn_std_export', function () {
                $('#CerModal').modal('show');
            });

            $('body').on('click','#btn_cer_add', function (e) {

                var cerno =  $('#certificate_cerno_export').val();
                var issue_date =  $('#certificate_issue_date').val();
                var expire_date =  $('#certificate_expire_date').val();
                var accereditatio_no = $('#certificate_accereditatio_no').val()

                var values = $('.repeater_audit_type_1').find('.certificate_ids').map(function(){return $(this).val(); }).get();

                if( !checkNone(cerno) ){
                    alert("กรุณากรอก เลขที่ได้รับการรับรอง !");
                }else if( !checkNone(issue_date) ){
                    alert("กรุณากรอก วันที่ออกใบรับรอง !");
                }else if( !checkNone(expire_date) ){
                    alert("กรุณากรอก วันที่หมดอายุใบรับรอง !");
                }else{

                    var id = $('#certificate_cerno_export').data( "id" );
                    var table = $('#certificate_cerno_export').data( "table" );

                    var val_btn = $('#btn_std_export').val();

                    if( val_btn == 1){
                        id = '';
                        table = '';
                    }

                    var certificate_id  = '<input type="hidden" class="certificate_ids" name="certificate_id" value="'+(checkNone(id)?id:'')+'">';
                    var certificate_no  = '<input type="hidden" class="certificate_no" name="certificate_no" value="'+(checkNone(cerno)?cerno:'')+'">';
                    var certificate_start_date  = '<input type="hidden" name="certificate_start_date" value="'+issue_date+'">';
                    var certificate_end_date  = '<input type="hidden" name="certificate_end_date" value="'+expire_date+'">';
                    var certificate_table  = '<input type="hidden" name="certificate_table" value="'+(checkNone(table)?table:'')+'">';
                    var certificate_accereditatio_no  = '<input type="hidden" name="accereditatio_no" value="'+(checkNone(accereditatio_no)?accereditatio_no:'')+'">';

                    var btn = '<button class="btn btn-sm btn-danger" type="button" data-repeater-delete> <i class="fa fa-minus"></i></button>';

                    var url_center = '{!! isset( HP::getConfig()->url_center )?HP::getConfig()->url_center:'' !!}';

                    var inputFile = '';

                    if(!checkNone(id)){
                        inputFile += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" id="modal_form_file">';
                        inputFile +=  '<div class="form-control" data-trigger="fileinput"><span class="fileinput-filename"></span></div>';
                        inputFile +=  '<span class="input-group-addon btn btn-default btn-file">';
                        inputFile +=  '<span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>';
                        inputFile +=  '<span class="input-group-text btn-file">';
                        inputFile +=  '<span class="fileinput-new">เลือกไฟล์</span>';
                        inputFile +=  '<span class="fileinput-exists">เปลี่ยน</span>';
                        inputFile +=  '<input type="file" name="certificate_file" class="certificate_file"  accept=".pdf,.jpg,.png" >';
                        inputFile +=  '</span>';
                        inputFile +=  '</span>';
                        inputFile +=  '</div>';
                    }else{
                        inputFile += '<a href="'+(url_center)+'/api/v1/certificate?cer='+(cerno)+'"  target="_blank"><span class="text-info"><i class="fa fa-file"></i></span></a>';
                    }

                    var tr_ = '<tr data-repeater-item>';
                        tr_ += '<td>'+cerno+' '+ certificate_id + certificate_no +'</td>';
                        tr_ += '<td>'+accereditatio_no+' '+ certificate_accereditatio_no +'</td>';
                        tr_ += '<td>'+issue_date+' '+ certificate_start_date +'</td>';
                        tr_ += '<td>'+expire_date+' '+ certificate_end_date +'</td>';
                        tr_ += '<td>'+ inputFile +'</td>';
                        tr_ += '<td>'+btn+' '+ certificate_table +'</td>';
                        tr_ += '</tr>';

                    if(checkNone(id)){
                        if(values.indexOf(String(id)) == -1){
                            $('#table-certificate tbody').append(tr_);
                        }
                    }else{
                        $('#table-certificate tbody').append(tr_);
                    }


                    $('.repeater_audit_type_1').repeater();

                    setTimeout(function(){

                        $('#certificate_std_export').val('').select2();
                        $('#certificate_cerno_export').val('');
                        $('#certificate_issue_date').val('');
                        $('#certificate_expire_date').val('');
                        $('#certificate_accereditatio_no').val('');

                        $('#certificate_cerno_export').removeAttr( "data-id" );
                        $('#certificate_cerno_export').removeAttr( "data-table" );
                        $('#certificate_cerno_export').removeAttr( "data-accereditatio_no" );

                        $('#btn_std_export').val(1);
                        ShowInputCertificate();
                    }, 100);
                }

            });

        });

        function ShowInputCertificate(){

            var value_btn = $('#btn_std_export').val();

            $('#certificate_issue_date').prop('disabled', true);
            $('#certificate_expire_date').prop('disabled', true);
            $('#certificate_accereditatio_no').prop('disabled', true);
            $('body').find('.certificate_cerno_export').prop('disabled', true);

            if( value_btn == '1'){
                $('body').find('.certificate_cerno_export').prop('disabled', false);
                $('#certificate_issue_date').prop('disabled', false);
                $('#certificate_expire_date').prop('disabled', false);
                $('#certificate_accereditatio_no').prop('disabled', false);
            }else if(  value_btn == '2' ){
                $('body').find('.certificate_cerno_export').prop('disabled', true);
            }
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush

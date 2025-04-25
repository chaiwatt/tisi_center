<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="ScopeModalLabel" aria-hidden="true" id="ScopeModal" >
    <div class="modal-dialog modal-dialog-centere modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title" id="ScopeModalLabel">เลือกรายการทดสอบที่รับการแต่งตั้ง</h4>
            </div>
            <div class="modal-body">
                <div class="row form-horizontal">
                    <div class="col-md-12">
                        @php
                            $list_standard = App\Models\Basic\Tis::select('tb3_Tisno', 'tb3_TisThainame', 'tb3_TisAutono AS id')->orderBy('tb3_Tisno')->get();

                            $option_standard = [];
                            foreach ($list_standard as $key => $item) {
                                $option_standard[$item->id] = $item->tb3_Tisno.' : '.(strip_tags($item->tb3_TisThainame));
                            }
                        @endphp
                        <div class="row">
                            <div class="form-group required">
                                {!! Form::label('modal_tis_id', 'มอก.', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('modal_tis_id', $option_standard, null, ['class' => 'form-control', 'placeholder'=>'- เลือกมอก. -', 'id' => 'modal_tis_id']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group required">
                                {!! Form::label('modal_tis_name', 'ชื่อ มอก.', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_tis_name', null, ['class' => 'form-control', 'id' => 'modal_tis_name', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_test_item', 'รายการทดสอบ', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('modal_test_item', [], null, ['class' => 'form-control', 'placeholder'=>'- เลือกรายการทดสอบ -', 'id' => 'modal_test_item']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row box_input_detail">
                            <div class="form-group required box_input_tools_select">
                                {!! Form::label('modal_test_tools', 'เครื่องมือที่ใช้', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::select('modal_test_tools', [], null, ['class' => 'form-control', 'placeholder'=>'- เลือกเครื่องมือที่ใช้ -', 'id' => 'modal_test_tools']) !!}
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="button" id="modal_btn_test_tools_specify">ระบุเอง</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="row box_input_detail">
                            <div class="form-group required box_input_tools_txt">
                                {!! Form::label('modal_test_tools_txt', 'เครื่องมือที่ใช้', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('modal_test_tools_txt', null, ['class' => 'form-control', 'id' => 'modal_test_tools_txt']) !!}
                                        <span class="modal_test_tools_select">{!! Form::select('modal_test_tools_select', [], null, ['class' => 'form-control', 'id' => 'modal_test_tools_select']) !!}</span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="button" id="modal_btn_test_tools_input" value="1">เลือก</button>
                                            <button class="btn btn-success" type="button" id="modal_btn_test_tools_add">เพิ่ม</button>
                                            <button class="btn btn-danger" type="button" id="modal_btn_test_tools_cancel">ยกเลิก</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row box_input_detail">
                            <div class="form-group">
                                {!! Form::label('modal_test_tools_no', 'รหัส/หมายเลข', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_test_tools_no', null, ['class' => 'form-control', 'id' => 'modal_test_tools_no']) !!}
                                </div>
                            </div>
                        </div>
                  

                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_capacity', 'ขีดความสามารถ', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_capacity', null, ['class' => 'form-control', 'id' => 'modal_capacity']) !!}
                                </div>
                            </div>
                        </div>  

                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_range', 'ช่วงการใช้งาน', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_range', null, ['class' => 'form-control', 'id' => 'modal_range']) !!}
                                </div>
                            </div>
                        </div>
                      
                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_true_value', 'ความละเอียดที่อ่านได้', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_true_value', null, ['class' => 'form-control', 'id' => 'modal_true_value']) !!}
                                </div>
                            </div>
                        </div>
                   
                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_fault_value', 'ความคลาดเคลื่อนที่ยอมรับ', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_fault_value', null, ['class' => 'form-control', 'id' => 'modal_fault_value']) !!}
                                </div>
                            </div>
                        </div>
                       

                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_test_duration', 'ระยะการทดสอบ(วัน)', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_test_duration', null, ['class' => 'form-control input_number', 'id' => 'modal_test_duration']) !!}
                                </div>
                            </div>
                        </div>
                      
                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_test_price', 'ค่าใช้จ่ายในการทดสอบ/ชุดละ', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('modal_test_price', null, ['class' => 'form-control Mscope_number_only', 'id' => 'modal_test_price']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_start_date', 'วันที่มีผล', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('modal_start_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'modal_start_date' ]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_end_date', 'วันที่สิ้นสุด', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('modal_end_date', null,  ['class' => 'form-control mydatepicker', 'id' => 'modal_end_date' ]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="row box_input_detail">
                            <div class="form-group required">
                                {!! Form::label('modal_remarks', 'หมายเหตุ', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('modal_remarks', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'modal_remarks']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-11">
                            <div class="pull-right">
                                <button type="button" class="btn btn-info waves-effect text-left" value="1" id="btn_box_detail">ซ่อน</button>
                                <button type="button" class="btn btn-success waves-effect text-left" id="btn_get_tr">เพิ่ม</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-11">
                            <p class="text-danger">หมายเหตุ : เพิ่มข้อมูลในตารางรายการทดสอบภายใต้ มอก. เดียวกันเท่านั้น</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <form enctype="multipart/form-data" class="form-horizontal" id="from_test_item" onsubmit="return false">

                            <div class="table-responsive repeater-table-scope">
                                <table class="table table-bordered table-sm" id="myTableScope" data-toggle="table" >
                                    <thead>
                                        <tr>
                                            <th align="top" width="2%" class="text-center">#</th>
                                            <th align="top" width="7%" class="text-center align-top">รายการทดสอบ</th>
                                            <th align="top" width="7%" class="text-center align-top">เครื่องมือที่ใช้</th>
                                            <th align="top" width="7%" class="text-center">รหัส/หมายเลข</th>
                                            <th align="top" width="7%" class="text-center">ขีดความสามารถ</th>
                                            <th align="top" width="7%" class="text-center">ช่วงการ<br>ใช้งาน</th>
                                            <th align="top" width="7%" class="text-center">ความละเอียดที่อ่านได้</th>
                                            <th align="top" width="10%" class="text-center">ความคลาดเคลื่อนที่ยอมรับ</th>
                                            <th align="top" width="10%" class="text-center">ระยะการทดสอบ(วัน)</th>
                                            <th align="top" width="10%" class="text-center">ค่าใช้จ่ายในการทดสอบ/ชุดละ</th>
                                            <th align="top" width="10%" class="text-center align-top">วันที่มีผล/วันที่สิ้นสุด</th>
                                            <th align="top" width="9%" class="text-center align-top">หมายเหตุ</th>
                                            <th align="top" width="9%" class="text-center align-top">ไฟล์แนบ</th>
                                            <th align="top" width="5%" class="text-center">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-top" data-repeater-list="repeater-scope">

                                    </tbody>
                                </table>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect text-left" id="btn_gen_box">สร้าง</button>
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>

@push('js')
    <script>
        $(document).ready(function () {

            $(".input_number").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            });

            $('#btn_box_detail').click(function (e) { 

                var value = $(this).val();

                if( value == 1){
                    $('#btn_box_detail').text('แสดง');
                    $('#btn_box_detail').val(0);
                    $('.box_input_detail').hide();
                }else{
                    $('#btn_box_detail').text('ซ่อน');
                    $('#btn_box_detail').val(1);
                    $('.box_input_detail').show();
                }
            
            });

            $('#modal_start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#modal_end_date').val(expire_date);
                }else{
                    $('#modal_end_date').val('');
                }
            });

            $(document).on('click', '.btn_remove_modalscope', function () {
                if(confirm('ยืนยันการลบข้อมูล แถวนี้')){
                    $(this).parent().parent().remove();
                    resetOrderNo();
                    data_test_item_list_disabled();
                    $('.repeater-table-scope').repeater();
                }
            });
            

            $(".Mscope_number_only").on("keypress keyup blur",function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $('.box_input_tools_txt').hide();

            showInput();

            $('#modal_btn_test_tools_specify').click(function (e) { 
                var modal_test_item = $('#modal_test_item').val();
                if( modal_test_item != ''){
                    $('.box_input_tools_txt').show();
                    $('.box_input_tools_select').hide();
                }else{
                    alert('กรุณาเลือกรายการทดสอบ');
                }
            });

            $('#modal_btn_test_tools_cancel').click(function (e) { 
                $('.box_input_tools_txt').hide();
                $('.box_input_tools_select').show();
            });

            $('#modal_btn_test_tools_add').click(function (e) { 

                var btn = $('#modal_btn_test_tools_input').val();
            
                if( btn == 1 ){
                    var txtr = $('#modal_test_tools_txt').val();
                }else{
                    var txtr = $('#modal_test_tools_select').val();
                }

                if( !empty(txtr) ){
                    SaveTestTools();
                }else{  
                    alert('กรุณากรอกเครื่องมือที่ใช้ ?');
                }
            });

            $('#modal_btn_test_tools_input').click(function (e) { 
                
                var val = $(this).val();

                if( val == 1 ){
                    $('#modal_btn_test_tools_input').val(2);
                    $('#modal_btn_test_tools_input').text('ระบุ');
                }else{
                    $('#modal_btn_test_tools_input').val(1);
                    $('#modal_btn_test_tools_input').text('เลือก');
                }

                showInput();
            });

            $("#modal_tis_id").on('change', function () {
                var val = $(this).val();

                $('#modal_test_item').html('<option value=""> -เลือกรายการทดสอบ- </option>');

                $('#modal_test_tools').html('<option value=""> -เลือกเครื่องมือที่ใช้- </option>');

                $('#modal_tis_name').val('');

                
                $('#modal_test_item').val('').trigger('change').select2();
                $('#modal_test_tools').val('').trigger('change').select2();

                $('#modal_test_tools_no').val('');
                $('#modal_capacity').val('');
                $('#modal_range').val('');
                $('#modal_true_value').val('');
                $('#modal_fault_value').val('');

                $('#modal_test_duration').val('');
                $('#modal_test_price').val('');


                if(  val != '' && $.isNumeric(val) ){
                    var tis_num = $(this).find('option:selected').text();
                    var explode_tis_num = tis_num.split(':');

                    $('#modal_tis_name').val( $.trim(explode_tis_num[1]) );

                    $.ajax({
                        url: "{!! url('/section5/get-test-item') !!}" + "/" + val
                    }).done(function( object ) {

                        if( object.length > 0){
                            $.each(object, function( index, data ) {
                                $('#modal_test_item').append('<option value="'+data.id+'">'+data.title+'</option>');
                            });
                        }

                    });

                }
            });

            $("#modal_tis_id").change();

            $("#modal_test_item").on('change', function () {
                var val = $(this).val();

                $('#modal_test_tools').html('<option value=""> -เลือกเครื่องมือที่ใช้- </option>');

                if( val != ''){
                    
                    $.ajax({
                        url: "{!! url('/section5/get-test-tools') !!}" + "/" + val
                    }).done(function( object ) {

                        if( object.length > 0){
                            $.each(object, function( index, data ) {
                                $('#modal_test_tools').append('<option value="'+data.id+'">'+data.title+'</option>');
                            });
                        }

                    });

                }

            });

            $('#btn_get_tr').click(function (e) { 
    
                var tis_id = $('#modal_tis_id').val();
                var tis_name =  $('#modal_tis_name').val();
                var tis_num = $('#modal_tis_id').find('option:selected').text();

                var test_item = $('#modal_test_item').val();
                var test_item_txt = $('#modal_test_item').find('option:selected').text();

                var test_tools = $('#modal_test_tools').val();
                var test_tools_txt = $('#modal_test_tools').find('option:selected').text();

                var test_tools_no = $('#modal_test_tools_no').val();
                var capacity = $('#modal_capacity').val();
                var range = $('#modal_range').val();
                var true_value = $('#modal_true_value').val();
                var fault_value = $('#modal_fault_value').val();

                var test_duration = $('#modal_test_duration').val();
                var test_price = $('#modal_test_price').val();

                var start_date = $('#modal_start_date').val();
                var end_date = $('#modal_end_date').val();

                var remarks = $('#modal_remarks').val();

                var explode_tis_num = tis_num.split(':');

                if( tis_id == '' ){
                    alert('กรุณากรอก มอก.');
                }else if( test_item == '' ){
                    alert('กรุณากรอก รายการทดสอบ');
                }else if( test_tools == '' ){
                    alert('กรุณากรอก เครื่องมือที่ใช้');
                // }else if( test_tools_no == '' ){
                //     alert('กรุณากรอก รหัส/หมายเลข');
                }else if( capacity == '' ){
                    alert('กรุณากรอก ขีดความสามารถ');
                }else if( range == '' ){
                    alert('กรุณากรอก ช่วงการใช้งาน');
                }else if( true_value == '' ){
                    alert('กรุณากรอก ความละเอียดที่อ่านได้');
                }else if( fault_value == '' ){
                    alert('กรุณากรอก ความคลาดเคลื่อนที่ยอมรับ');
                }else if( test_duration == '' ){
                    alert('กรุณากรอก ระยะการทดสอบ');
                }else if( test_price == '' ){
                    alert('กรุณากรอก ค่าใช้จ่ายในการทดสอบ');
                }else if( start_date == '' ){
                    alert('กรุณากรอก วันที่มีผล');
                }else if( end_date == '' ){
                    alert('กรุณากรอก วันที่สิ้นสุด');
                }else if( remarks == '' ){
                    alert('กรุณากรอก หมายเหตุ');
                }else{
 
                    var LastRow = $('#myTableScope tbody').length;
                    var inputSTD = '<input type="hidden" class="myTableScope_tis_id" name="tis_id" value="'+(tis_id)+'"><input type="hidden" class="Mscope_tis_tisno" name="tis_tisno" value="'+($.trim(explode_tis_num[0]))+'">';
                    var idRows = '<input type="hidden" class="Mscope_id" name="scope_id" value="">';
                    var inputStartDate = '<input type="hidden" class="Mscope_start_date" name="start_date" value="'+(start_date)+'">';
                    var inputEndDate = '<input type="hidden" class="Mscope_end_date" name="end_date" value="'+(end_date)+'">';
                    var inputRemarks = '<input type="hidden" class="Mscope_remarks" name="remarks" value="'+(remarks)+'">';
                    var inputFile  = '<div class="fileinput fileinput-new input-group" data-provides="fileinput" id="modal_form_file">';
                        inputFile +=  '<div class="form-control" data-trigger="fileinput"><span class="fileinput-filename"></span></div>';
                        inputFile +=  '<span class="input-group-addon btn btn-default btn-file">';
                        inputFile +=  '<span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>';
                        inputFile +=  '<span class="input-group-text btn-file">';
                        inputFile +=  '<span class="fileinput-new">เลือกไฟล์</span>';
                        inputFile +=  '<span class="fileinput-exists">เปลี่ยน</span>';
                        inputFile +=  '<input type="file" name="attach_file" class="Mscope_attach_file"  accept=".pdf,.jpg,.png" >';
                        inputFile +=  '</span>';
                        inputFile +=  '</span>';
                        inputFile +=  '</div>';

                    var _tr = '';
                        _tr += '<tr data-repeater-item>';
                        _tr += '<td class="text-top"><span class="Modalno">'+(LastRow)+'</span>'+(idRows)+'</td>';
                        _tr += '<td class="text-top">'+(inputSTD)+'<input type="hidden" class="Mscope_test_item_id" name="test_item_id" value="'+(test_item)+'">'+(test_item_txt)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_test_tools_id" name="test_tools_id" value="'+(test_tools)+'">'+(test_tools_txt)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_test_tools_no" name="test_tools_no" value="'+(test_tools_no)+'">'+(test_tools_no)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_capacity" name="capacity" value="'+(capacity)+'">'+(capacity)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_range" name="range" value="'+(range)+'">'+(range)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_true_value" name="true_value" value="'+(true_value)+'">'+(true_value)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_fault_value" name="fault_value" value="'+(fault_value)+'">'+(fault_value)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_test_duration" name="test_duration" value="'+(test_duration)+'">'+(test_duration)+'</td>';
                        _tr += '<td class="text-top"><input type="hidden" class="Mscope_test_price" name="test_price" value="'+(test_price)+'">'+(test_price)+'</td>';
                        _tr += '<td class="text-top">'+(start_date)+' - '+(end_date)+' '+(inputStartDate)+''+(inputEndDate)+'</td>';
                        _tr += '<td class="text-top">'+(remarks)+''+(inputRemarks)+'</td>';
                        _tr += '<td class="text-top">'+(inputFile)+'</td>';
                        _tr += '<td class="text-top"><button type="button" class="btn btn-danger btn-sm btn_remove_modalscope">ลบ</button></td>';
                        _tr += '</tr>';

                    var table = $('#myTableScope tbody');
                    var addRows = true;
                    if( table.length > 0 ){
                        $('.myTableScope_tis_id').each(function(index, element){
                            if( $(element).val() != tis_id ){
                                addRows = false;
                            }
                        });
                    }

                    if( addRows == true ){
                        $('#myTableScope tbody').append(_tr);

                        resetOrderNo();

                        $('.repeater-table-scope').repeater();

                        $('#modal_test_item').val('').select2();
                        $('#modal_test_tools').val('').select2();

                        $('#modal_test_tools_no').val('');
                        $('#modal_capacity').val('');
                        $('#modal_range').val('');
                        $('#modal_true_value').val('');
                        $('#modal_fault_value').val('');

                        $('#modal_test_duration').val('');
                        $('#modal_test_price').val('');

                        $('#modal_start_date').val('');
                        $('#modal_end_date').val('');

                        $('#modal_remarks').val('');


                    }else{
                        alert('กรุณาเลือกมอก. ให้ตรงกัน');
                    }

                    // data_test_item_list_disabled();
                }

            });

            $('#btn_gen_box').click(function (e) { 
                $('#from_test_item').submit();   
            });

            
            $('#from_test_item').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
                var formData = new FormData($("#from_test_item")[0]);
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', "{{ $labs->id }}");

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/labs/save_std_test_item') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                        
                            $('#ScopeModal').find('select').val('').select2();
                            $('#ScopeModal').find('input, textarea').val('');

                            $('#myTableScope tbody').html('');
                            
                            $('#ScopeModal').modal('hide');
                            LoadGroupScope();
                        }
                    }
                });

            });


        }); 

        function showInput(){

            var btn =   $('#modal_btn_test_tools_input').val();

            $('#modal_test_tools_select').html('<option value=""> -เลือกเครื่องมือที่ใช้- </option>');

            $('#modal_test_tools_txt').val('');
            $('#modal_test_tools_select').val('').trigger('change.select2');

            if( btn ==  2 ){
                $('#modal_test_tools_txt').hide();
                $('.modal_test_tools_select').show();
                LoadToolsBasic();
            }else{
                $('#modal_test_tools_txt').show();
                $('.modal_test_tools_select').hide();
            }

        }

    
        function SaveTestTools(){

            var test_item = $('#modal_test_item').val();
            var test_tool = $('#modal_test_tools_txt').val();
            var test_tool_id = $('#modal_test_tools_select').val();

            var btn = $('#modal_btn_test_tools_input').val();

            $.ajax({
                method: "POST",
                url: "{{ url('/section5/save_test_tools') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "test_item": test_item,
                    "test_tool": test_tool,
                    "test_tool_id": test_tool_id,
                    "type": btn
                },
                success : function (data){
                    if (data.mgs == "success") {

                        $.toast({
                            heading: 'Compleate!',
                            text: 'บันทึกสำเร็จ',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 1000,
                            stack: 6,
                        });

                        LoadItemTools( data );

                        $('#modal_test_tools_txt').val('');

                        $('.box_input_tools_txt').hide();
                        $('.box_input_tools_select').show();
                
                    }else{

                        $.toast({
                            heading: 'Compleate!',
                            text: 'บันทึกไม่สำเร็จ',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 1000,
                            stack: 6,

                        });

                    }
                }
            });
        }

        function LoadItemTools( data ){
            
            $('#modal_test_tools').html('<option value=""> -เลือกเครื่องมือที่ใช้- </option>');
            var val  = $('#modal_test_item').val();
            if(  val != '' && $.isNumeric(val) ){

                $.LoadingOverlay("show", {
                    image       : "",
                    text        : "Loading..."
                });

                $.ajax({
                    url: "{!! url('/section5/get-test-tools') !!}" + "/" + val
                }).done(function( object ) {

                    if( object.length > 0){
                        $.each(object, function( index, data ) {
                            $('#modal_test_tools').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });

                        $('#modal_test_tools').val( data.tools_id).trigger('change.select2');
                        
                        $.LoadingOverlay("hide", true);  
                    }else{
                        $.LoadingOverlay("hide", true);    
                    }

                });

            } 
        }

        function LoadToolsBasic(){
            
            var val  = $('#modal_test_item').val();

            $.LoadingOverlay("show", {
                image       : "",
                text        : "Loading..."
            });

            $.ajax({
                url: "{!! url('/section5/get-basic-tools') !!}" + "/" + val
            }).done(function( object ) {

                if( object.length > 0){
                    $.each(object, function( index, data ) {
                        $('#modal_test_tools_select').append('<option value="'+data.id+'">'+data.title+'</option>');
                    });
                    $.LoadingOverlay("hide", true);  
                }else{
                    $.LoadingOverlay("hide", true);    
                }

            });

        }

        function data_test_item_list_disabled(){
            $('#modal_test_item').children('option').prop('disabled',false);
            $('.Mscope_test_item_id').each(function(index , item){
                var data_list = $(item).val();
                $('#modal_test_item').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
            });
        }

        function resetOrderNo(){
            $('.Modalno').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = str_pad(date_start.getDate());

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }
    </script>
@endpush

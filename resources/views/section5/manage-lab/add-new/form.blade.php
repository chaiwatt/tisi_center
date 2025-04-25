@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style>

    </style>
@endpush

<!-- ข้อมูลผู้ยื่น -->
@include ('section5.manage-lab.add-new.infomation')

<!-- ข้อมูลขอรับบริการ -->
@include ('section5.manage-lab.add-new.scope')

<!-- ข้อมูลใบรับรอง -->
@include ('section5.manage-lab.add-new.certify')

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('start_date', 'ขอบข่ายที่ขอรับการแต่งตั้งมีผลตั้งแต่วันที่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('start_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('end_date', 'สิ้นสุดวันที่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('end_date', null, ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row p-t-15">
    <center>
        <div class="form-group">
            @can('add-'.str_slug('manage-lab'))
                <button class="btn btn-primary show_tag_a" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
            @endcan
            <a class="btn btn-default show_tag_a" href="{{url('/section5/labs')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        </div>
    </center>
</div>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script>
        jQuery(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });

            $('#start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#end_date').val(expire_date);
                }else{
                    $('#end_date').val('');
                }
            });

            //เมื่อ Submit form
            $('#create_froms').submit(function (e) { 
                var values = $('.repeater-table-scope').find('.section_box_tis').map(function(){return $(this).val(); }).get();
                if(values.length ==0 ){//ถ้ามีเอกสารอบรม แต่ไม่บันทึกประวัติการอบรม
                    alert('กรุณาบันทึก ข้อมูลขอรับบริการ');
                    event.preventDefault(); //this will prevent the default submit
                }
                
            });

            $('.modal_create_modal').click(function (e) {

                $('#myTableScope tbody').html('');
                $('#modal_tis_id').val('').trigger('change').select2();

                $('#modal_test_item').val('').trigger('change').select2();
                $('#modal_test_tools').val('').trigger('change').select2();

                $('#modal_test_tools_no').val('');
                $('#modal_capacity').val('');
                $('#modal_range').val('');
                $('#modal_true_value').val('');
                $('#modal_fault_value').val('');
                $('#modal_tis_id').prop('disabled', false);

                data_tis_disabled();

            });

            $("body").on('click', '.btn_section_edit', function () {

                var tb = $(this).data('table');
                var tis_id = $(this).data('tis_id');

                if( tb != '' && tis_id != '' ){
                    var i = 0;
                    $('#modal_tis_id').val(tis_id);
                    $('#modal_tis_id').trigger('change');

                    $('#modal_tis_id').prop('disabled', true);

                    $('#myTableScope tbody').html('');

                    $('#'+tb ).find('.scope_tis_id').each(function (index, rowId) {
                        i++;
                        var row = $(rowId).parent().parent();

                        var tis_ids =  $(rowId).val();;

                        var tis_num = row.find('.scope_tis_tisno').val();
                        var test_item = row.find('.scope_test_item_id').val();
                        var test_tools = row.find('.scope_test_tools_id').val();
                        var test_tools_no = row.find('.scope_test_tools_no').val();
                        var capacity = row.find('.scope_capacity').val();
                        var range = row.find('.scope_range').val();
                        var true_value = row.find('.scope_true_value').val();
                        var fault_value = row.find('.scope_fault_value').val();

                        var test_duration = row.find('.scope_test_duration').val();
                        var test_price = row.find('.scope_test_price').val();

                        var test_item_txt = row.children("td:nth-child(2)").text();

                        var test_tools_txt = row.children("td:nth-child(3)").text();

                        var scope_id = row.find('.scope_id').val();

                        var inputSTD = '<input type="hidden" class="myTableScope_tis_id" name="tis_id" value="'+(tis_id)+'"><input type="hidden" class="Mscope_tis_tisno" name="tis_tisno" value="'+(tis_num)+'">';
                        var idRows = '<input type="hidden" class="Mscope_id" name="scope_id" value="'+(scope_id)+'">';

                        var id_row_tr = Math.floor(Math.random() * 26) + Date.now();

                        var test_item_name = arr_tst_item[test_item];
                        var test_tools_name = arr_tools[test_tools];

                        var inputHidden = inputSTD;
                            inputHidden += idRows;
                            inputHidden += '<input type="hidden" class="Mscope_test_item_id" name="test_item_id" value="'+(test_item)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_test_tools_id" name="test_tools_id" value="'+(test_tools)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_test_tools_no" name="test_tools_no" value="'+(test_tools_no)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_capacity" name="capacity" value="'+(capacity)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_range" name="range" value="'+(range)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_true_value" name="true_value" value="'+(true_value)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_fault_value" name="fault_value" value="'+(fault_value)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_test_duration" name="test_duration" value="'+(test_duration)+'">';
                            inputHidden += '<input type="hidden" class="Mscope_test_price" name="test_price" value="'+(test_price)+'">';

                        var _tr = '';
                            _tr += '<tr class="row_tr_'+(id_row_tr)+'">';
                            _tr += '<td class="text-center text-top"><span class="Modalno_'+(test_item)+'"></span></td>';
                            _tr += '<td class="text-center text-top">'+(test_item_name)+'</td>';
                            _tr += '<td class="text-center text-top">'+(test_tools_name)+'</td>';
                            _tr += '<td class="text-center text-top">'+(test_tools_no)+'</td>';
                            _tr += '<td class="text-center text-top">'+(capacity)+'</td>';
                            _tr += '<td class="text-center text-top">'+(range)+'</td>';
                            _tr += '<td class="text-center text-top">'+(true_value)+'</td>';
                            _tr += '<td class="text-center text-top">'+(fault_value)+'</td>';
                            _tr += '<td class="text-center text-top">'+(test_duration)+'</td>';
                            _tr += '<td class="text-center text-top">'+(test_price)+'</td>';
                            _tr += '<td class="text-center text-top"><button type="button" class="btn btn-danger btn-sm btn_remove_modalscope" data-tr="'+(id_row_tr)+'">ลบ</button>'+inputHidden+'</td>';
                            _tr += '</tr>';

                        console.log(index);
                        console.log(test_tools_name);

                        $('#myTableScope tbody').append(_tr);
                    });

                    setTimeout(function(){

                        CloneTableScope();

                        $('#ScopeModal').modal('show');

                        $('#modal_tis_id').trigger('change');
                    }, 1000);

                }

            });


            $("body").on('click', '.btn_remove_scope', function () {
                if(  $('.Tscope_number-'+tis_id).length > 1 ){
                    if(confirm('ยืนยันการลบข้อมูล แถวนี้')){
                        var tis_id = $(this).data('tis_id');
                        $(this).parent().parent().remove();
                        NumberTableScope(tis_id);
                    }
                }else{
                    alert('ไม่สามารถลบได้ !!');
                }
            });

            $('body').on( 'click', '.btn_section_remove',function (e) {
                if (confirm('คุณต้องการลบชุดรายการทดสอบ?')) {
                    $(this).parent().parent().parent().parent().remove();
                    setTimeout(function(){
                        $('.repeater-table-scope').repeater();
                        BtnRemoveSection();
                    }, 400);
                }
            });

            BtnRemoveSection();
            merge_table_box_scope();
        });

        
        function NumberTableScope(tis_id){
            $('.Tscope_number-'+tis_id).each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function BtnRemoveSection(){
            $('.table_multiples').length>1?$('.btn_section_remove').show():$('.btn_section_remove').hide();
        }

        function data_tis_disabled(){
            $('#modal_tis_id').children('option').prop('disabled',false);
            $('.section_box_tis').each(function(index , item){
                var data_list = $(item).val();
                $('#modal_tis_id').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
            });
        }
        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function merge_table_box_scope(){

            $('.section_box_tis').each(function(index , item){
                var tis_id = $(item).val();

                const table = document.querySelector('#table-group-'+tis_id );

                if( checkNone(table)  ){

                    NumberTableMScope(tis_id);

                    //Col 1
                    let headerCell = null;
                        for (let row of table.rows) {
                            const Cell1 = row.cells[0];
                            const Cell2 = row.cells[1];

                            if (headerCell === null || Cell1.innerText !== headerCell.innerText) {
                                headerCell = Cell1;
                                header2Cell = Cell2;

                            } else {
                                headerCell.rowSpan++;
                                header2Cell.rowSpan++;
                                Cell1.remove();//ลบคอลัมภ์แรก
                                Cell2.remove();//ลบคอลัมภ์สอง
                            }
                        }
                }

            })
        }

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
            console.log(date_start.getMonth());
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

                console.log(date_start.getDate());

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = ( '0' + date_start.getDate() ).slice( -2 );

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(input) {
            let string = String(input);
            return string.padStart(2, '0');
        }
            
    </script>
@endpush
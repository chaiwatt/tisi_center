

@extends('layouts.master')
@push('css')
    <link href="{{ asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/components/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .list-group-item{
            position: unset;
        }
    </style>
@endpush

@php
    $list_standard = App\Models\Basic\Tis::select('tb3_Tisno', 'tb3_TisThainame', 'tb3_TisAutono')->orderBy('tb3_Tisno')->get();

    $option_standard = [];
    foreach ($list_standard as $key => $item ) {
        $number = $item->tb3_Tisno;
        $option_standard[$item->getKey()] = $number.' : '.(strip_tags($item->tb3_TisThainame));
    }


@endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายการทดสอบ</h3>
                    @can('view-'.str_slug('bsection5-testitem'))
                        <a class="btn btn-success pull-right" href="{{ url('/bsection5/test_item') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('std_tis_id', 'มอก.', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('std_tis_id', $option_standard , !empty($tis_id)?$tis_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกมอก. -']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <button class="btn btn-success  pull-right" type="button" id="add_main_test"><span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มหัวข้อหลัก</b></button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">

                            <div id="from_modal" class="no-border-treeview"></div>

                        </div>
                    </div>

                    @include ('bsection5.test-item.modal.create')

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-treeview/js/bootstrap-treeview.min.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $("#unit_id").select2({
                formatResult: formatState,
                formatSelection: formatState,
                escapeMarkup: function(m) { return m; }
            });

            $('#std_tis_id').change(function (e) {
                $('#from_modal').html('');

                $("#parent_id").html('<option value=""> -เลือกภายใต้หัวข้อทดสอบ- </option>');
                $('#tis_id').val('');
                $('#add_main_test').hide();

                if($(this).val()!=''){

                    LoadDataAjax();

                    $.ajax({
                        url: "{!! url('/bsection5/test_item/main/get-data-item') !!}" + "?tis_id=" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $("#parent_id").append('<option value="'+index+'">'+data+'</option>');
                        });
                    });

                    $('#tis_id').val($(this).val());
                    $('#add_main_test').show();
                }
            });

            $('#std_tis_id').change();

            $(document).on("click", "#add_main_test", function() {
                $('.modal_input').val('');
                $('.modal_select').val('').trigger('change.select2');

                $('.input_condition[value="1"]').iCheck('check');
                $('.input_condition').iCheck('update');

                $('.input_state[value="1"]').iCheck('check');
                $('.input_state').iCheck('update');

                $('.input_result[value="1"]').iCheck('check');
                $('.input_result').iCheck('update');

                //สรุปผลทดสอบ
                $('.test_summary[value="1"]').iCheck('check');
                $('.test_summary').iCheck('update');

                $('#label_condition_1').show();
                $('#label_condition_2').show();
                $('#label_condition_3').show();

                $('#label_condition_2').hide();
                $('#label_condition_3').hide();

                $('#tis_id').val($('#std_tis_id').val());

                $('#btn_copy_save').hide();
                BoxCondition();
                $('#AddForm').modal('show');
            });

            $(document).on("click", ".btn_add_test_item,.btn_edit_test_item", function() {

                var id = $(this).data('id');
                var title = $(this).data('title');
                var type = $(this).data('type');
                var edit = $(this).data('edit');

                var parent_id = $(this).data('parent_id');
                var main_topic_id = $(this).data('main_topic_id');

                $('.modal_input').val('');
                $('.modal_select').val('').trigger('change.select2');

                $('#tis_id').val($('#std_tis_id').val());

                $('#label_condition_1').show();
                $('#label_condition_2').show();
                $('#label_condition_3').show();

                var tis_id =  $('#tis_id').val();

                $('#btn_copy_save').hide();

                $("#parent_id").html('<option value=""> -เลือกภายใต้หัวข้อทดสอบ- </option>');

                var filter_main_topic_id = '';
                var filter_parent_id = '';
                var filter_check_main = '';
                if( type == 1 && checkNone(main_topic_id)){
                    filter_main_topic_id = main_topic_id;
                    filter_check_main = 'true';
                }else if(  type == 2 && checkNone(parent_id)  ){
                    filter_main_topic_id = main_topic_id;
                    filter_parent_id = parent_id;
                }else if(  type == 3 && checkNone(main_topic_id) && checkNone(parent_id)  ){
                    filter_main_topic_id = main_topic_id;
                    filter_parent_id = parent_id;
                }

                if(tis_id!=''){
                    $.ajax({
                        url: "{!! url('/bsection5/test_item/main/get-data-item') !!}" + "?tis_id=" + tis_id + '&filter_main_topic_id=' + filter_main_topic_id + '&filter_parent_id=' + filter_parent_id + '&filter_check_main=' + filter_check_main + '&type='+type + '&id=' + id + '&edit=' + edit
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $("#parent_id").append('<option value="'+index+'">'+data+'</option>');
                        });

                        if( edit != 1){
                            $("#parent_id").val(id).trigger('change.select2');
                        }
                    });
                }

                if( edit != 1){
                    if( confirm("ต้องการเพิ่มข้อมูล ภายใต้ "+title+" ใช่หรือไม่ ?") ){

                        $('.input_state[value="1"]').iCheck('check');
                        $('.input_state').iCheck('update');

                        $('.input_result[value="1"]').iCheck('check');
                        $('.input_result').iCheck('update');

                        //สรุปผลทดสอบ
                        $('.test_summary[value="1"]').iCheck('check');
                        $('.test_summary').iCheck('update');

                        if( type == 1){
                            $('#label_condition_1').hide();

                            $('.input_condition[value="2"]').iCheck('check');
                            $('.input_condition').iCheck('update');

                        }else if(  type == 2 ){
                            $('#label_condition_1').hide();
                            // $('#label_condition_2').hide();

                            $('.input_condition[value="3"]').iCheck('check');
                            $('.input_condition').iCheck('update');

                            $('#btn_copy_save').show();
                        }

                        $("#parent_id").val(id).trigger('change.select2');

                        BoxCondition();
                        $('#AddForm').modal('show');

                    }
                }else{
                    if( confirm("ต้องการแก้ไขข้อมูล "+title+" ใช่หรือไม่ ?") ){

                        $('#condition_1').prop('checked', false);
                        $('#condition_2').prop('checked', false);
                        $('#condition_3').prop('checked', false);

                        $('#condition_1').iCheck('update');
                        $('#condition_2').iCheck('update');
                        $('#condition_3').iCheck('update');

                        $('#input_state_1').prop('checked', false);
                        $('#input_state_2').prop('checked', false);
                        $('#input_state_1').iCheck('update');
                        $('#input_state_2').iCheck('update');

                        LoadDataTestItem(id);

                    }
                }

            });

            $(document).on("click", ".btn_delete_test_item", function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var type = $(this).data('type');

                var  alert = "<h4 class='text-dark'>ต้องการลบ <b>"+title+"</b> ใช่หรือไม่ ?</h4>";
                if(type == 1){
                    var amount_test_list =   $(".btn_delete_test_item[data-main_topic_id='"+id+"']").length;
                    if( amount_test_list >= 2){
                        alert = '<h4 class="text-dark">มีรายการทดสอบภายใต้ <b>'+title+'</b> หากลบ รายการทดสอบที่อยู่ภายใต้จะถูกลบทั้งหมด</h4>';
                    }

                }else if(type == 2){
                    var amount_test_list =   $(".btn_delete_test_item[data-parent_id='"+id+"']").length;
                    if( amount_test_list >= 1){
                        alert = '<h4 class="text-dark">มีรายการทดสอบภายใต้ <b>'+title+'</b> หากลบ รายการทดสอบที่อยู่ภายใต้จะถูกลบทั้งหมด</h4>';
                    }
                }else{
                    alert = "<h4 class='text-dark'>ต้องการลบ <b>"+title+"</b> ใช่หรือไม่ ?</h4>";
                }

                Swal.fire({
                    html: alert,
                    icon: 'warning',
                    width: 500,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ลบข้อมูล',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonClass: 'btn btn-primary btn-sm m-l-5',
                    cancelButtonClass: 'btn btn-danger btn-sm m-l-5',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "{!! url('/bsection5/test_item/delete_std_test_item') !!}" + "/" + id
                        }).done(function( object ) {
                            LoadDataAjax();
                        });
                    }
                });

            });

            $('#condition_1').on('ifChecked', function(event){
                BoxCondition();
            });

            $('#condition_2').on('ifChecked', function(event){
                BoxCondition();
            });

            $('#condition_3').on('ifChecked', function(event){
                BoxCondition();
            });

            BoxCondition();

            //เมื่อเลือกกรอกผลทดสอบ ได้/ไม่ได้
            $('.input_result[type="radio"]').on('ifChecked', function(event){
                let input_result = $('.input_result[type="radio"]:checked').val();
                if(input_result==1){//ได้
                    $('#amount_test_list').closest('div.row').show();

                    $('#box-format_result, #box-format_result_detail, #box-format_result_preview').show();//รูปแบบข้อมูลผลการทดสอบ
                }else if(input_result==2){//ไม่ได้
                    $('#amount_test_list').closest('div.row').hide();
                    $('#amount_test_list').val('');//clear ค่า
                    $('#amount_test_list').trigger('change.select2');

                    $('#box-format_result, #box-format_result_detail, #box-format_result_preview').hide();//รูปแบบข้อมูลผลการทดสอบ
                }
            });


            $('#btn_save').click(function (e) {
                $('#copy_and_save').val('');
                $('#from_test_item').submit();
            });

            $('#btn_copy_save').click(function (e) {
                $('#copy_and_save').val(1);
                $('#from_test_item').submit();
            });


            $('#from_test_item').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
                var formData = new FormData($("#from_test_item")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                var copy_and_save =  $('#copy_and_save').val();

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/bsection5/test_item/save_std_test_item') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");

                            if( !checkNone(copy_and_save) ){
                                $('#AddForm').modal('hide');
                            }
                            LoadDataAjax();


                        }
                    }
                });

            });

        });

        function BoxCondition(){

            if( $('#condition_1').is(':checked',true) ){

                $('.box_parent').hide();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', false);
                $('.box_parent').find('#parent_id').prop('required', false);

            }else if( $('#condition_2').is(':checked',true) || $('#condition_3').is(':checked',true)  ){

                $('.box_parent').show();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', false);
                $('.box_parent').find('#parent_id').prop('required', true);
            }

        }

        function LoadDataAjax(){

            $.LoadingOverlay("show", {
                image: "",
                text: "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{!! url('/bsection5/test_item/get-data-item') !!}" + "?tis_id=" + $('#std_tis_id').val()
            }).done(function( object ) {
                if(object != ''){
                    $('#from_modal').treeview({
                        data: object,
                        collapseIcon:'fa fa-minus',
                        expandIcon:'fa fa-plus',
                        showBorder: false,
                        showTags: false,
                        highlightSelected: false,

                    });

                    $('#from_modal').treeview('expandAll', { levels: 3, silent: true });
                }else{
                    $('#from_modal').html('<center><p class="text-danger">ไม่พบข้อมูลรายการทดสอบ</p></center>');
                }

                $.LoadingOverlay("hide");
            });
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function LoadDataTestItem(id){

            $('#btn_copy_save').hide();

            $.LoadingOverlay("show", {
                image: "",
                text: "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{!! url('/bsection5/test_item/get-testitem-data') !!}" + "/" + id
            }).done(function( object ) {

                if( checkNone(object.type) ){
                     if( object.type == 1 ){
                        $('#label_condition_2').hide();
                        $('#label_condition_3').hide();
                    }else if( object.type == 2){
                        $('#label_condition_1').hide();
                    }else if( object.type == 3){
                        $('#label_condition_1').hide();
                        // $('#label_condition_2').hide();
                    }

                    $('.input_condition[value="'+(object.type)+'"]').iCheck('check');
                    $('.input_condition').iCheck('update');
                }

                if( checkNone(object.id) ){
                    $('#id').val(object.id);
                }

                if( checkNone(object.no) ){
                    $('#no').val(object.no);
                }

                if( checkNone(object.title) ){
                    $('#title').val(object.title);
                }

                if( checkNone(object.unit_id) ){
                    $("#unit_id").val(object.unit_id).trigger('change.select2');
                }

                if( checkNone(object.criteria) ){
                    $('#criteria').val(object.criteria);
                }

                if( checkNone(object.parent_id) ){
                    $("#parent_id").val(object.parent_id).trigger('change.select2');
                }

                if( checkNone(object.test_method_id) ){
                    $("#test_method_id").val(object.test_method_id).trigger('change.select2');
                }

                if( checkNone(object.criteria) ){
                    $('#criteria').val(object.criteria);
                }

                if( checkNone(object.input_result) ){
                    $('.input_result[value="'+(object.input_result)+'"]').iCheck('check');
                    $('.input_result').iCheck('update');
                }

                if( checkNone(object.amount_test_list) ){
                    $("#amount_test_list").val(object.amount_test_list).trigger('change.select2');
                }

                //สรุปผลทดสอบ
                if( checkNone(object.test_summary) ){
                    $('.test_summary[value="'+(object.test_summary)+'"]').iCheck('check');
                    $('.test_summary').iCheck('update');
                }

                if( checkNone(object.state) ){
                    $('.input_state[value="'+(object.state)+'"]').iCheck('check');
                    $('.input_state').iCheck('update');
                }

                //รูปแบบข้อมูลผลทดสอบ
                if( checkNone(object.format_result) ){
                    $("#format_result").val(object.format_result).trigger('change.select2');
                    $("#format_result").change();

                    if(checkNone(object.format_result_detail)){
                        let format_result_detail = JSON.parse(object.format_result_detail);
                        let box = $('#box-format_result_detail');

                        if(object.format_result!='mix'){//ไม่ใช่แบบรวม
                            $.each(format_result_detail, function(index, item) {

                                $(box).find('#'+index).val(item);

                                //tagsinput
                                if($(box).find('#'+index).attr('data-role')=='tagsinput'){
                                    $(box).find('#'+index).tagsinput('add', item);
                                }

                                //checkbox
                                if($(box).find('#'+index).prop('type')=='checkbox' && item==1){
                                    $(box).find('#'+index).prop('checked', true);
                                }

                            });

                            //สั่ง change เพื่อให้คำสั่งทำงาน
                            $(box).find('input[type="checkbox"], input[id="digit"]').change();
                        }else{//แบบรวม
                            $.each(format_result_detail, function(index, items) {

                                if(index>0){
                                    $('#format_result_mix-plus').click(); //สั่งคลิกเพื่อเพิ่มกรอบที่ได้เลือกไว้ ยกเว้นอันแรกจะมีอยู่แล้ว
                                }

                                if(typeof items === 'object' && items.hasOwnProperty('format_result_mix')){

                                    //ใส่ค่าใน รูปแบบข้อมูลผลทดสอบ และสั่งคลิก ให้ input ออกมาเพิ่ม
                                    let current = $('.box-format_result-mix').find('.item-format_result-mix:last');//แถวที่กำลังวนอยู่
                                    $(current).find('.format_result_mix').val(items.format_result_mix).trigger('change.select2');
                                    $(current).find('.format_result_mix').change();

                                    $.each(items, function(index2, item) {//วนรอบใส่ค่า input ของแต่ละรูปแบบข้อมูลผลทดสอบ

                                        if(index2!='format_result_mix'){//ไม่ใช่ input บอกประเภท
                                            $(current).find('#'+index2+'-'+index).val(item);

                                            //tagsinput
                                            if($(current).find('#'+index2+'-'+index).attr('data-role')=='tagsinput'){
                                                $(current).find('#'+index2+'-'+index).tagsinput('add', item);
                                            }

                                            //checkbox
                                            if($(current).find('#'+index2+'-'+index).prop('type')=='checkbox' && item==1){
                                                $(current).find('#'+index2+'-'+index).prop('checked', true);
                                            }
                                        }
                                    });

                                    //สั่ง change เพื่อให้คำสั่งทำงาน
                                    $(current).find('input[type="checkbox"], input[id*="digit"]').change();
                                }

                            });
                        }

                    }

                }else{
                    $("#format_result").val('').trigger('change.select2');
                    $("#format_result").change();
                }

                if( checkNone(object.tools) ){

                    var tools = [];
                    $.each(object.tools, function( index, data ) {
                        tools.push(data);
                    });
                    $('.test_tools_ids').select2('val', tools);
                    $(".test_tools_ids").trigger('change.select2');
                }

                BoxCondition();

                $.LoadingOverlay("hide");
                $('#AddForm').modal('show');

            });

        }
    </script>

    <script>

        // ควบคุมรูปแบบผลการทดสอบ
        $(document).ready(function () {

            //เมื่อเลือกรูปแบบข้อมูลผลทดสอบ
            $('#format_result').change(function(event) {
                let format_result = $(this).val();
                let box = $('#box-format_result_detail');
                if(format_result!=''){

                    //Copy กล่อง input ต้นแบบมาใส่ กล่องที่จะใช้
                    $(box).html($('div[data-format_result="'+$(this).val()+'"]').html());

                    //เปลี่ยนชื่อ name input ครอบ format_result_detail[ชื่อ input ต้นแบบ]
                    $(box).find('input, select').each(function(index, el) {
                        $(el).prop('name', 'format_result_detail[' + $(el).prop('name') + ']');
                    });

                    //ลบ tagsinput และสร้างใหม่
                    $(box).find('#option_list').prev('.bootstrap-tagsinput').remove();
                    $(box).find('#option_list').tagsinput('refresh');

                    //ลบ select2 และสร้างใหม่
                    $(box).find('select').prev('div').remove();
                    $(box).find('select').css('display', 'block').select2();

                }else{
                    $(box).html('');
                }
            });

            //เมื่อเลือก/ไม่เลือก checkbox (กำหนด) ให้ enable/disable ช่องกรอกด้านหลัง
            $(document).on('change', 'input[type="checkbox"].config_text', function(event) {
                let row = $(this).closest('div.row');
                $(row).find('input[type="text"], input[type="number"]').prop('disabled', !$(this).prop('checked'));
            });

            //เมื่อกรอกจำนวนหลักทศนิยมสูงสุด ใส่ step ให้ input number ให้ถูกต้อง
            $(document).on('change', 'input[id*="digit"]', function(event) {
                let digit = parseInt($(this).val());
                if(!isNaN(digit)){
                    let step = '1';
                        step = '0.'+step.padStart(digit, '0');
                    $('#box-format_result_detail').find('input[type="number"][id!="digit"]').prop('step', step);
                }

            });

            //mix เมื่อกดปุ่มเพิ่ม
            $(document).on('click', '#format_result_mix-plus', function(event) {

                //clone
                let detail = $('#box-format_result_detail');
                $(detail).find('.item-format_result-mix:first').clone().appendTo($(detail).find('.box-format_result-mix'));

                let last_row = $(detail).find('.item-format_result-mix:last');

                //ลบ select2 และสร้างใหม่
                $(last_row).find('select').prev('div').remove();
                $(last_row).find('select').val('');
                $(last_row).find('select').change();
                $(last_row).find('select').css('display', 'block').select2();

                reset_name_order_mix();
            });

            //mix กดปุ่มลบ
            $(document).on('click', '.format_result_mix-remove', function(event) {
                $(this).closest('.item-format_result-mix').remove();
                reset_name_order_mix();
            });

            //mix เมื่อเลือก รูปแบบข้อมูลผลทดสอบ
            $(document).on('change', '.format_result_mix', function(event) {

                let box = $(this).closest('.item-format_result-mix').find('.box-format_result-mix_detail');

                if($(this).val() !=''){

                    //Copy กล่อง input ต้นแบบมาใส่ กล่องที่จะใช้
                    $(box).html($('div[data-format_result="'+$(this).val()+'"]').html());

                    //เปลี่ยนชื่อ name input ครอบ format_result_detail[ชื่อ input ต้นแบบ]
                    $(box).find('input, select').each(function(index, el) {
                        $(el).prop('name', 'format_result_detail[' + $(el).prop('name') + ']');
                    });

                    //ลบ tagsinput และสร้างใหม่
                    $(box).find('#option_list').prev('.bootstrap-tagsinput').remove();
                    $(box).find('#option_list').tagsinput('refresh');

                    reset_name_order_mix();
                }else{
                    $(box).html('');
                }

            });

            //เมื่อคลิก ดูตัวอย่างช่องกรอกรูปแบบข้อมูลผลทดสอบ
            $('#btn-format_result_preview').click(function(event) {
                if($('#format_result').val()!=''){
                    let inputs = {};
                    $('#box-format_result_detail').find('input, select').each(function(index, el) {
                        inputs[$(el).prop('name')] = $(el).val();
                    });
                    inputs['format_result'] = $('#format_result').val();

                    let query_param = decodeURIComponent($.param(inputs));
                    window.open("{{ url('bsection5/test_item/example_input') }}/?"+query_param);
                }else{
                    alert('กรุณาเลือกรูปแบบข้อมูลผลทดสอบ');
                }
            });
        });

        //รีเซต name ของ input เปลี่ยน เลขใน [] หลังสุด
        function reset_name_order_mix(){
            $('#box-format_result_detail').find('.item-format_result-mix').each(function(index, row) {
                $(row).find('input, select').each(function(index2, input) {
                    //เปลี่ยน name
                    let input_name    = $(input).prop('name');
                    let input_names   = [];
                    let bracket_open  = input_name.indexOf("[");
                    let bracket_close = input_name.indexOf("]");
                    if(bracket_open!==-1 && bracket_close!==-1){//มี []
                        if(!isNaN(parseInt(input_name.substring(bracket_open+1, bracket_close)))){//ถ้าเป็นตัวเลขตัดออก
                            let number_split = input_name.substring(bracket_open, bracket_close+1);//ตัดเอาชุด [ตัวเลข] ข้างหลังออก
                                input_names  = input_name.split(number_split);
                        }else{
                            input_names    = input_name.split('[');
                            input_names[1] = '['+input_names[1];
                        }
                        $(input).prop('name', input_names[0]+'['+index+']'+input_names[1]); //ตัวอย่าง format_result_detail+[ตัวเลข]+[min_start]
                    }

                    // //เปลี่ยน id
                    let input_id  = $(input).prop('id');
                    let input_ids = input_id.split('-');
                    if(input_ids.length==2){
                        input_id = input_ids[0];//ตัดตัวเลขและ - ออก
                    }
                    $(input).prop('id', input_id+'-'+index); //ตัวอย่าง min_start-0

                });

                $(row).find('label').each(function(index2, label) {
                    //เปลี่ยน for
                    let label_for  = $(label).prop('for');
                    let label_fors = label_for.split('-');
                    if(label_fors.length==2){
                        label_for = label_fors[0];//ตัดตัวเลขและ - ออก
                    }
                    $(label).prop('for', label_for+'-'+index); //ตัวอย่าง min_start-0

                });
            });
        }

        function formatState (option) {
            if (!option.id) return option.text; // optgroup
            return option.text;
        }

    </script>

@endpush

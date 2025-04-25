<div class="row  ">
    <div class="col-md-12">
        <fieldset class="white-box" >
            <legend class="legend"><h3 class="m-t-0">ส่วนที่ 2 : ข้อมูลการกระทำความผิด</h3></legend>

            @php
                $option_section    = App\Models\Law\Basic\LawSection::select(DB::Raw('CONCAT(number," : ",title) AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
                $option_status     = [ 1 => 'รอดำเนินการ', 2 => 'อยู่ระหว่างดำเนินการ', 3 => 'ปิดงานคดี' ];
                $subdepart_ids     = ['0600','0601','0602','0603','0604'];
                $option_users_law  = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')->whereIn('reg_subdepart',$subdepart_ids)->get()->pluck('title', 'id'); 
                $option_tis        = App\Models\Basic\Tis::select( DB::raw('tb3_TisAutono AS id'), DB::raw('CONCAT(tb3_Tisno, " : ", tb3_TisThainame) AS title') )->where('status',1)->orderbyRaw('CONVERT(CONCAT(tb3_Tisno, " : ", tb3_TisThainame) USING tis620)')->pluck('title', 'id');
            @endphp

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('condition', ' ข้อมูลการกระทำความผิด', ['class' => 'col-md-4 control-label text-right']) !!}
                            <div class="col-md-4">
                                {!! Form::radio('condition', '1', true , ['class' => 'form-v check', 'data-radio' => 'iradio_flat-blue', 'id'=>'condition_1']) !!}
                                {!! Form::label('condition_1', 'พบการกระทำผิด', ['class' => 'control-label text-capitalize']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::radio('condition', '2',null, ['class' => 'form-control check', 'data-radio' => 'iradio_flat-blue', 'id'=>'condition_2']) !!}
                                {!! Form::label('condition_2', 'ไม่พบการกระทำผิด', ['class' => 'control-label text-capitalize']) !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="repeater-cases">

                <div class="col-md-12 box_parent" data-repeater-list="repeater-cases">

                    <div class="row row_list_item" data-repeater-item>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-danger btn-outline btn-sm pull-right btn_caeses_remove" data-repeater-delete type="button">
                                        ลบ
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        {!! Form::hidden('license_number', null , ['class' => 'form-control offend_license_no']) !!}
                        {!! Form::hidden('tb3_tisno', null , ['class' => 'form-control offend_tb3_tisno']) !!}

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('tb4_tisilicense_id') ? 'has-error' : ''}}">
                                    {!! Form::label('tb4_tisilicense_id', 'เลขที่ใบอนุญาต', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('tb4_tisilicense_id', [] , null, ['class' => 'form-control offend_license_number','placeholder' => '- เลขที่ใบอนุญาต -']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('case_number') ? 'has-error' : ''}}">
                                    {!! Form::label('case_number', 'เลขคดี', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('case_number', null , ['class' => 'form-control', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('tis_id') ? 'has-error' : ''}}">
                                    {!! Form::label('tis_id', 'มอก.'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('tis_id', $option_tis, null,  ['class' => 'form-control offend_tis_id', 'required' => true, 'placeholder' => '- มอก. -']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('date_offender') ? 'has-error' : ''}}">
                                    {!! Form::label('date_offender', 'วันที่พบการกระทำผิด'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {!! Form::text('date_offender', null,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('section') ? 'has-error' : ''}}">
                                    {!! Form::label('section', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('section', $option_section , null, ['class' => '', 'required' => 'required', 'multiple'=>'multiple']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('law_basic_section_id') ? 'has-error' : ''}}">
                                    {!! Form::label('punish', 'ลงโทษตามมาตรา', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('punish', $option_section , null, ['class' => '', 'required' => 'required', 'multiple'=>'multiple']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('case_product') ? 'has-error' : ''}}">
                                    {!! Form::label('case_product', 'ดำเนินการของกลาง', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-6">
                                        <label>{!! Form::radio('case_product', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ดำเนินการ</label>
                                        <label>{!! Form::radio('case_product', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่ดำเนินการ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('case_person') ? 'has-error' : ''}}">
                                    {!! Form::label('case_person', 'ดำเนินการทางอาญา', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-6">
                                        <label>{!! Form::radio('case_person', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ดำเนินการ</label>
                                        <label>{!! Form::radio('case_person', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่ดำเนินการ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('case_license') ? 'has-error' : ''}}">
                                    {!! Form::label('case_license', 'ดำเนินการปกครอง', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-6">
                                        <label>{!! Form::radio('case_license', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ดำเนินการ</label>
                                        <label>{!! Form::radio('case_license', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่ดำเนินการ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('lawyer_by') ? 'has-error' : ''}}">
                                    {!! Form::label('lawyer_by', 'นิติกร', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('lawyer_by', $option_users_law , null, ['class' => 'form-control', 'required' => 'required','placeholder' => '- นิติกร -']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                                    {!! Form::label('status', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('status', $option_status , null, ['class' => 'form-control', 'required' => 'required','placeholder' => '- สถานะ -']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('date_close') ? 'has-error' : ''}}">
                                    {!! Form::label('date_close', 'วันที่ปิดคดี'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            {!! Form::text('date_close', null,  ['class' => 'form-control mydatepicker']) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                </div>


                <div class="col-md-12" >
                    <button type="button" class="btn btn-success btn-outline btn-sm pull-right" data-repeater-create>
                        <i class="fa fa-plus"></i> เพิ่มชุดข้อมูลการกระทำความผิด
                    </button> 
                </div>
            </div>

        </fieldset>
    </div>
</div>

@push('js')

    <script type="text/javascript">

        $(document).ready(function() {
            $('.repeater-cases').repeater({
                show: function () {

                    $(this).slideDown();
                    reBuiltSelect2($(this).find('select'));

                    //ปฎิทิน
                    $('.mydatepicker').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                    });

                    reBuiltIcheck();

                    LoadSelectTisilicense( $(this).find('select.offend_license_number') );
                    BtnDeleteCases();
                    data_list_disabled();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeleteCases();
                        }, 500);

                    }
                }
            });

            $(document).on('change', '.offend_license_number', function(e) {

                var row =  $(this).closest('div.row_list_item');

                if( $(this).val() != '' ){

                    let selected = $(this).find('option:selected');

                    var tisi_id    = selected.data('tisi_id');
                    var tis_no     = selected.data('tis_no');
                    var tis_name   = selected.data('tis_name');
                    var license_no = selected.data('license_no');

                    row.find('.offend_tis_id').val(tisi_id).trigger('change.select2');
                    row.find('.offend_tb3_tisno').val(tis_no);
                    row.find('.offend_license_no').val(license_no);


                }else{

                    row.find('.offend_tis_id').val('').trigger('change.select2');
                    row.find('.offend_tb3_tisno').val('');
                    row.find('.offend_license_no').val('');
  
                }
                
            });

            
            $(document).on('change', '.offend_tis_id', function(e) {

                var row =  $(this).closest('div.row_list_item');

                if( $(this).val() != '' ){
                    let selected = $(this).find('option:selected');
                    row.find('.offend_tb3_tisno').val( selected.text() );
                }else{
                    row.find('.offend_tb3_tisno').val('');
                }
                
            });

            BtnDeleteCases();
            data_list_disabled();
            reBuiltIcheck();

            BoxCondition();
        });



        function BtnDeleteCases(){

            if( $('.btn_caeses_remove').length <= 1 ){
                $('.btn_caeses_remove:first').hide();   
            }else{
                $('.btn_caeses_remove').show();
            }
        }

        
        function data_list_disabled(){
            $('select.offend_license_number').children('option').prop('disabled',false);
            $('select.offend_license_number').each(function(index , item){
                var data_list = $(item).val();
                $('select.offend_license_number').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
            });
        }

        function reBuiltIcheck(){
            $('.check').each(function() {
                var ck = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-red';
                var rd = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-red';

                if (ck.indexOf('_line') > -1 || rd.indexOf('_line') > -1) {
                    $(this).iCheck({
                        checkboxClass: ck,
                        radioClass: rd,
                        insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                    });
                } else {
                    $(this).iCheck({
                        checkboxClass: ck,
                        radioClass: rd
                    });
                }
            });
        }

        function reBuiltSelect2(select){
            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

             
        function BoxCondition(){

            var state = $('input[name="condition"]:checked').val();
    
            if( state == 1 ){

                $('.box_parent').show();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', false);
                $('.box_parent').find('input:required, select:required').prop('required', true);

            }else{

                $('.box_parent').hide();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', true);
                $('.box_parent').find('input:required, select:required').prop('required', false);


            }


        }

    </script>
@endpush
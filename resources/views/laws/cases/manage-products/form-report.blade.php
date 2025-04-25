@push('css')
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">


    <style>
        .border-left {
            border-left: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@php
    $impound        = $lawcases->law_cases_impound_to;
    $product_result = $lawcases->product_result;

    $lawcases->assign_email = !empty($lawcases) && !is_null($lawcases->user_assign_to) && !empty($lawcases->user_assign_to->reg_email)?$lawcases->user_assign_to->reg_email:null;
    $lawcases->lawyer_email = !empty($lawcases) && !is_null($lawcases->user_lawyer_to) && !empty($lawcases->user_lawyer_to->reg_email)?$lawcases->user_lawyer_to->reg_email:null;
    $lawcases->create_email = !empty($lawcases) && !is_null($lawcases->user_created) && !empty($lawcases->user_created->reg_email)?$lawcases->user_created->reg_email:null;

@endphp

{!! Form::hidden('law_cases_id', !empty($lawcases->id)?$lawcases->id:null , ['class' => 'form-control' ]) !!}
{!! Form::hidden('law_case_impound_id', !empty($impound->id)?$impound->id:null , ['class' => 'form-control' ]) !!}

<fieldset class="white-box">
    <legend class="legend"><h4>ข้อมูลผู้กระทำความผิด</h4></legend>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_ref_no', 'เลขคดี'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_ref_no', !empty($lawcases->case_number)?$lawcases->case_number:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_name', 'ผู้ประกอบการ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('law_cases_name', !empty($lawcases->offend_name)?$lawcases->offend_name:null , ['class' => 'form-control', 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('detail', 'มอก'.' : ', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::textarea('detail',!empty($lawcases->tb3_tisno)? $lawcases->tb3_tisno.': '.$lawcases->TisName:null , ['class' => 'form-control ', 'rows' => 2, 'disabled' => true]) !!}
                    {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_tb3_tisno', 'คำสั่งให้ดำเนินการ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::select('result_process_product_id', App\Models\Law\Basic\LawProcessProduct::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') , !empty($product_result->result_process_product_id)?$product_result->result_process_product_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกดําเนินการกับผลิตภัณฑ์ -', 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_license_number', 'วันที่มีคำสั่ง-สิ้นสุด'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                        <div class="input-daterange input-group date-range">
                         {!! Form::text('start_date', !empty($product_result->result_start_date)?HP::formatDateThaiFull($product_result->result_start_date):null, ['class' => 'form-control date', 'disabled' => true]) !!}
                         <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                         {!! Form::text('end_date',!empty($product_result->result_end_date)?HP::formatDateThaiFull($product_result->result_end_date):null,['class' => 'form-control date', 'disabled' => true]) !!}
                       </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_tb3_tisno', 'หลักฐานคำสั่ง กมอ.'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    @if( !empty($product_result->file_law_result) )
                    @php
                        $file_law_result = $product_result->file_law_result;
                    @endphp
                    <a href="{!! HP::getFileStorage($file_law_result->url) !!}" target="_blank" class="m-l-5">
                        {!! !empty($file_law_result->filename) ? $file_law_result->filename : '' !!}
                        {!! HP::FileExtension($file_law_result->filename)  ?? '' !!}
                    </a>
                @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('law_cases_license_number', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::textarea('result_remark',  !empty($product_result->result_remark)?$product_result->result_remark:null , ['class' => 'form-control ', 'rows' => 2, 'disabled' =>  true ]) !!}
                </div>
            </div>
        </div>
    </div>

</fieldset>


<fieldset class="white-box">
    <legend class="legend"><h4>รายงานผลการติดตาม</h4></legend>
    <table class="table table-bordered repeater-form">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center" width="15%">วันที่ดำเนินการ</th>
                <th class="text-center" width="20%">การดำเนินการ</th>
                <th class="text-center" width="15%">วันที่ครบกำหนด</th>
                <th class="text-center" width="25%">รายละเอียด</th>
                <th class="text-center" width="15%">ไฟล์แนบ</th>
                <th class="text-center" width="5%">จัดการ</th>
            </tr>
        </thead>
        <tbody data-repeater-list="repeater-operation">
            @if( count($product_result->law_case_product_operations) >= 1 )
            @foreach(  $product_result->law_case_product_operations as $operations )
                <tr  data-repeater-item>
                    <td class="text-top text-center">
                        <span class="td_no">1</span>
                        {!! Form::hidden('product_operations_id' , $operations->id , ['class' => '' , 'required' => false])   !!}
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            <div class="inputWithIcon">
                                {!! Form::text('operation_date',!empty($operations->operation_date)?HP::revertDate($operations->operation_date,true):null, ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            {!! Form::select('status_job_track_id',App\Models\Law\Cases\LawCaseProductOperations::list_status(),!empty($operations->status_job_track_id)?$operations->status_job_track_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true ]) !!}
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            <div class="inputWithIcon">
                                {!! Form::text('due_date',!empty($operations->due_date)?HP::revertDate($operations->due_date,true):null, ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            {!! Form::textarea('detail',!empty($operations->detail)?$operations->detail:null, ['class' => 'form-control', 'rows' => 3 ]) !!}
                        </div>
                    </td>
                    <td class="text-top">
    
                        @if( !empty($operations->AttachFileOperations) )
                            @php
                                $attach = $operations->AttachFileOperations;
                            @endphp
                            <div class="form-group col-md-12 operation_attach">
                                <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                    {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                    {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                </a>

                                <a class="btn btn-danger btn-xs m-l-15 show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/manage-products/'.$lawcases->id.'/report') ) !!}" title="ลบไฟล์">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>

                            </div>
                        @endif
                        <div class="form-group col-md-12 operation_file">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control " data-trigger="fileinput" >
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                    <span class="input-group-text btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="file_law_product_operations" class="check_max_size_file">
                                    </span>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-top text-center">
                        <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                            <i class="fa fa-times"></i>
                        </button> 
                    </td>
                </tr>
                @endforeach
                @else
                <tr  data-repeater-item>
                    <td class="text-top text-center">
                        <span class="td_no">1</span>
                        {!! Form::hidden('product_operations_id' , null , ['class' => '' , 'required' => false])   !!}
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            <div class="inputWithIcon">
                                {!! Form::text('operation_date', '', ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            {!! Form::select('status_job_track_id',App\Models\Law\Cases\LawCaseProductOperations::list_status(), '', ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true ]) !!}
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            <div class="inputWithIcon">
                                {!! Form::text('due_date', '', ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </td>
                    <td class="text-top">
                        <div class="form-group col-md-12">
                            {!! Form::textarea('detail', '' , ['class' => 'form-control', 'rows' => 3 ]) !!}
                        </div>
                    </td>

                    <td class="text-top">
                        <div class="form-group col-md-12 operation_file">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control " data-trigger="fileinput" >
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                    <span class="input-group-text btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="file_law_product_operations" class="check_max_size_file">
                                    </span>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-top text-center">
                        <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                            <i class="fa fa-times"></i>
                        </button> 
                    </td>
                </tr>
                @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"></td>
                <td class="text-top text-center">
                    <button type="button" class="btn btn-success btn-sm" data-repeater-create>
                        <i class="fa fa-plus"></i>
                    </button>  
                </td>
            </tr>
        </tfoot>
    </table>
</fieldset>

 
<center>
    <div class="form-group">
        <div class="col-md-12">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-cases-manage-products'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/manage-products') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
</center>


@push('js')
<script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{ asset('js/function.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    
<script type="text/javascript">
    $(document).ready(function() {


        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

        //แก้ไขแถวในตาราง
        $('body').on('click', '.staf_edit', function(){
            var row = $(this).parent().parent().parent();
                row.find('input, select, textarea').prop('readonly', false);
                row.find('input, select, textarea').prop('disabled', false);
                row.find('.show_tag_a').show();
                row.find('.status_job_track_id').remove();//ลบ hidden select
                
                row.find('.mydatepicker_edit').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                });
        });

        //เพิ่มลบไฟล์แนบ
        $('.repeater-form').repeater({
            show: function () {
                $(this).slideDown();

                $('.mydatepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy'
                });

                reBuiltSelect2($(this).find('select'));
                $(this).find('.operation_attach','.staf_edit').remove();

                $(this).find('input, select, textarea').prop('readonly', false);
                $(this).find('input, select, textarea').prop('disabled', false);

                $(this).find('.mydatepicker_edit').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy'
                });

                OrderTdNo();
                BtnDeleteFile();

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    BtnDeleteFile();

                    OrderTdNo();
                    ShowInputFile();
                }
            });

            OrderTdNo();
            ShowInputFile();
         BtnDeleteFile();
    });

       function reBuiltSelect2(select){

            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

        function BtnDeleteFile(){

        if( $('.btn_file_remove').length <= 1 ){
            $('.btn_file_remove:first').hide();   
            $('.btn_file_add:first').show();  
        }else{
            $('.btn_file_remove').show();
        }

        check_max_size_file();
        }

    function ResetTableNumber(){
        var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
            
        }    

    function OrderTdNo(){
            $('.td_no').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function ShowInputFile(){
            $('.operation_attach').each(function(index, el) {
                var row = $(el).parent();
                row.find('.operation_file').hide();
            });
        }
          
</script>


@endpush
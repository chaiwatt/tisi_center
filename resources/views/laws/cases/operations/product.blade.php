@if (!empty($result->product) && $result->product == 1)

        <table class="table table-bordered repeater-form-product">
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
            <tbody data-repeater-list="repeater-product">

                @if( count($result->operations_detail_product))
                    @foreach(  $result->operations_detail_product as $operation_product )
                          @if( !empty($operation_product->operation_date))
                        <tr  data-repeater-item>
                            <td class="text-top text-center">
                                <span class="td_no_product">1</span>
                                {!! Form::hidden('operation_detail_id' , $operation_product->id , ['class' => '' , 'required' => false])   !!}
                                {!! Form::hidden('operation_type' ,"3", ['class' => 'operation_type' , 'required' => false])   !!}
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('operation_date', !empty($operation_product->operation_date)?HP::revertDate($operation_product->operation_date,true):null , ['class' => 'form-control mydatepicker_edit required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ,'readonly' => true ] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::select('status_job_track_id',App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',6)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),  !empty($operation_product->status_job_track_id)?$operation_product->status_job_track_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => false  ,'disabled' => true]) !!}
                                    {!! Form::hidden('status_job_track_id' ,!empty($operation_product->status_job_track_id)?$operation_product->status_job_track_id:null, ['class' => 'status_job_track_id' , 'required' => false])   !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('due_date', !empty($operation_product->due_date)?HP::revertDate($operation_product->due_date,true):null, ['class' => 'form-control mydatepicker_edit required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ,'readonly' => true ] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::textarea('remark', !empty($operation_product->remark)?$operation_product->remark:null , ['class' => 'form-control', 'rows' => 3 ,'readonly' => true]) !!}
                                </div>
                            </td>
                            <td class="text-top">

                                @if( !empty($operation_product->attach_file) )
                                    @php
                                        $attach = $operation_product->attach_file;
                                    @endphp
                                    <div class="form-group col-md-12 operation_attach_product">
                                        <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                            {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                            {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                        </a>

                                        <a class="btn btn-danger btn-xs m-l-15 show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/operations/'.$result->id.'/edit') ) !!}" title="ลบไฟล์" style="display: none">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>

                                    </div>
                                @endif
                                <div class="form-group col-md-12 operation_file_product">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control " data-trigger="fileinput" >
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                            <span class="input-group-text btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="attachs" class="check_max_size_file" disabled>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-top text-center">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-sm staf_edit" >
                                        <i class="fa fa-pencil"></i>
                                    </button> 
                                    <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                                        <i class="fa fa-times"></i>
                                    </button>                 
                                </div>
                            </td>
                        </tr>
                        @else
                        <tr  data-repeater-item>
                            <td class="text-top text-center">
                                <span class="td_no_product">1</span>
                                {!! Form::hidden('operation_detail_id' ,null, ['class' => '' , 'required' => false])   !!}
                                {!! Form::hidden('operation_type' ,"3", ['class' => 'operation_type' , 'required' => false])   !!}
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('operation_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::select('status_job_track_id',
                                    App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',6)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                    null  , 
                                    ['class' => 'form-control  select2 reward_status_job_track',
                                    'placeholder'=>'- เลือกการดำเนินงาน -', 'required' => false 
                                    ]) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                        {!! Form::text('due_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::textarea('remark', '' , ['class' => 'form-control', 'rows' => 3 ]) !!}
                                </div>
                            </td>
    
                            <td class="text-top">
                                <div class="form-group col-md-12 operation_file_product">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control " data-trigger="fileinput" >
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                            <span class="input-group-text btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="attachs" class="check_max_size_file">
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-top text-center">
                                <button type="button" class="btn btn-danger btn-sm btn_file_remove_product" data-repeater-delete>
                                    <i class="fa fa-times"></i>
                                </button> 
                            </td>
                        </tr>
                    @endif
                    @endforeach
                @else
                    <tr  data-repeater-item>
                        <td class="text-top text-center">
                            <span class="td_no_product">1</span>
                            {!! Form::hidden('operation_detail_id' ,null, ['class' => '' , 'required' => false])   !!}
                            {!! Form::hidden('operation_type' ,"3", ['class' => 'operation_type' , 'required' => false])   !!}
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                {!! Form::text('operation_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                            </div>
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                {!! Form::select('status_job_track_id',
                                App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',6)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                null  , 
                                ['class' => 'form-control  select2 reward_status_job_track',
                                'placeholder'=>'- เลือกการดำเนินงาน -', 'required' => false 
                                ]) !!}
                            </div>
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                    {!! Form::text('due_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                            </div>
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                {!! Form::textarea('remark', '' , ['class' => 'form-control', 'rows' => 3 ]) !!}
                            </div>
                        </td>

                        <td class="text-top">
                            <div class="form-group col-md-12 operation_file_product">
                                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                    <div class="form-control " data-trigger="fileinput" >
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                        <span class="input-group-text btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="attachs" class="check_max_size_file">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-top text-center">
                            <button type="button" class="btn btn-danger btn-sm btn_file_remove_product" data-repeater-delete>
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
    @else
        <p class="h1 text-bold-300 text-center">"ไม่ต้องดำเนินการใดๆ"</p>
    @endif
  
@push('js')   
<script type="text/javascript">
    $(document).ready(function() {


        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });
        //เพิ่มลบไฟล์แนบ
        $('.repeater-form-product').repeater({
            show: function () {
                $(this).slideDown();

                $('.mydatepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy'
                });

                reBuiltSelect2product($(this).find('select'));
                $(this).find('.operation_attach_product, .staf_edit').remove();
                $(this).find('.operation_type').val('3');

                $(this).find('input, select, textarea').prop('readonly', false);
                $(this).find('input, select, textarea').prop('disabled', false);

                $(this).find('.mydatepicker_edit').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                });

                OrderTdNoproduct();
                BtnDeleteFileproduct();

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    BtnDeleteFileproduct();

                    OrderTdNoproduct();
                    ShowInputFileproduct();
                }
            });

            OrderTdNoproduct();
            ShowInputFileproduct();
         BtnDeleteFileproduct();
    });

       function reBuiltSelect2product(select){

            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

        function BtnDeleteFileproduct(){

        if( $('.btn_file_remove_product').length <= 1 ){
            $('.btn_file_remove_product:first').hide();   
            $('.btn_file_add_product:first').show();  
        }else{
            $('.btn_file_remove_product').show();
        }

        check_max_size_file();
        }

    function ResetTableNumberproduct(){
        var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
            
        }    

    function OrderTdNoproduct(){
            $('.td_no_product').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function ShowInputFileproduct(){
            $('.operation_attach_product').each(function(index, el) {
                var row = $(el).parent();
                row.find('.operation_file_product').hide();
            });
        }
          
</script>

@endpush

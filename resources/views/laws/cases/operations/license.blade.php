@if (!empty($result->license) && $result->license == 1)

        <table class="table table-bordered repeater-form-license">
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
            <tbody data-repeater-list="repeater-license">

                @if( count($result->operations_detail_license))
                    @foreach(  $result->operations_detail_license as $operation_license )
                    @if( !empty($operation_license->operation_date))
                        <tr  data-repeater-item>
                            <td class="text-top text-center">
                                <span class="td_no_license">1</span>
                                {!! Form::hidden('operation_detail_id' , $operation_license->id , ['class' => '' , 'required' => false])   !!}
                                {!! Form::hidden('operation_type' ,"2", ['class' => 'operation_type' , 'required' => false])   !!}
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('operation_date', !empty($operation_license->operation_date)?HP::revertDate($operation_license->operation_date,true):null , ['class' => 'form-control mydatepicker_edit required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ,'readonly' => true] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::select('status_job_track_id',App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',3)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),  !empty($operation_license->status_job_track_id)?$operation_license->status_job_track_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => false, 'disabled' => true ]) !!}
                                    {!! Form::hidden('status_job_track_id' ,!empty($operation_license->status_job_track_id)?$operation_license->status_job_track_id:null, ['class' => 'status_job_track_id' , 'required' => false])   !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('due_date', !empty($operation_license->due_date)?HP::revertDate($operation_license->due_date,true):null, ['class' => 'form-control mydatepicker_edit required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ,'readonly' => true] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::textarea('remark', !empty($operation_license->remark)?$operation_license->remark:null , ['class' => 'form-control', 'rows' => 3 ,'readonly' => true]) !!}
                                </div>
                            </td>
                            <td class="text-top">

                                @if( !empty($operation_license->attach_file) )
                                    @php
                                        $attach = $operation_license->attach_file;
                                    @endphp
                                    <div class="form-group col-md-12 operation_attach_license">
                                        <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                            {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                            {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                        </a>

                                        <a class="btn btn-danger btn-xs m-l-15 show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/operations/'.$result->id.'/edit') ) !!}" title="ลบไฟล์" style="display: none">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>

                                    </div>
                                @endif
                                <div class="form-group col-md-12 operation_file_license">
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
                                <span class="td_no_license">1</span>
                                {!! Form::hidden('operation_detail_id' ,null, ['class' => '' , 'required' => false])   !!}
                                {!! Form::hidden('operation_type' ,"2", ['class' => 'operation_type' , 'required' => false])   !!}
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::text('operation_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                                </div>
                            </td>
                            <td class="text-top">
                                <div class="form-group col-md-12">
                                    {!! Form::select('status_job_track_id',
                                    App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',3)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                    null  , 
                                    ['class' => 'form-control  select2 reward_status_job_track',
                                    'placeholder'=>'- เลือกการดำเนินงาน -',
                                    'required' => false
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
                                <div class="form-group col-md-12 operation_file_license">
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
                                <button type="button" class="btn btn-danger btn-sm btn_file_remove_license" data-repeater-delete>
                                    <i class="fa fa-times"></i>
                                </button> 
                            </td>
                        </tr>
                    @endif
                    @endforeach
                @else
                    <tr  data-repeater-item>
                        <td class="text-top text-center">
                            <span class="td_no_license">1</span>
                            {!! Form::hidden('operation_detail_id' ,null, ['class' => '' , 'required' => false])   !!}
                            {!! Form::hidden('operation_type' ,"2", ['class' => 'operation_type' , 'required' => false])   !!}
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                {!! Form::text('operation_date', '', ['class' => 'form-control mydatepicker required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => false ] ) !!}
                            </div>
                        </td>
                        <td class="text-top">
                            <div class="form-group col-md-12">
                                {!! Form::select('status_job_track_id',
                                App\Models\Law\Basic\LawStatusOperation::Where('state', 1)->where('law_bs_category_operate_id',3)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                null  , 
                                ['class' => 'form-control  select2 reward_status_job_track',
                                'placeholder'=>'- เลือกการดำเนินงาน -'
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
                            <div class="form-group col-md-12 operation_file_license">
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
                            <button type="button" class="btn btn-danger btn-sm btn_file_remove_license" data-repeater-delete>
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
        $('.repeater-form-license').repeater({
            show: function () {
                $(this).slideDown();

                $('.mydatepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy'
                });

                reBuiltSelect2license($(this).find('select'));
                $(this).find('.operation_attach_license, .staf_edit').remove();
                $(this).find('.operation_type').val('2');

                $(this).find('input, select, textarea').prop('readonly', false);
                $(this).find('input, select, textarea').prop('disabled', false);

                $(this).find('.mydatepicker_edit').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                });

                OrderTdNolicense();
                BtnDeleteFilelicense();

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    BtnDeleteFilelicense();

                    OrderTdNolicense();
                    ShowInputFilelicense();
                }
            });

            OrderTdNolicense();
            ShowInputFilelicense();
         BtnDeleteFilelicense();
    });

       function reBuiltSelect2license(select){

            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

        function BtnDeleteFilelicense(){

        if( $('.btn_file_remove_license').length <= 1 ){
            $('.btn_file_remove_license:first').hide();   
            $('.btn_file_add_license:first').show();  
        }else{
            $('.btn_file_remove_license').show();
        }

        check_max_size_file();
        }

    function ResetTableNumberlicense(){
        var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
            
        }    

    function OrderTdNolicense(){
            $('.td_no_license').each(function(index, el) {
                $(el).text(index+1);
            });
        }

        function ShowInputFilelicense(){
            $('.operation_attach_license').each(function(index, el) {
                var row = $(el).parent();
                row.find('.operation_file_license').hide();
            });
        }
          
</script>

@endpush

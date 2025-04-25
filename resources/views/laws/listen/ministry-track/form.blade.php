@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

    <style>
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 70px;
            border-radius: 0;
            width: 100% !important;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
        .note-editor.note-frame {
            border-radius: 4px !important;
        }

    </style>
@endpush

<div class="clearfix"></div>

    <div class="form-group  required{{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'เรื่อง :', ['class' => 'col-md-3 text-right']) !!}
        <div class="col-md-7">
           <span class="font-medium-6" id="title"> {!! !empty($lawlistministry->title)?$lawlistministry->title:null !!}</span>
        </div>
    </div>
    
    <div class="form-group  required{{ $errors->has('tis_name') ? 'has-error' : ''}}">
        {!! Form::label('tis_name', 'ผลิตภัณฑ์ :', ['class' => 'col-md-3 text-right']) !!}
        <div class="col-md-7">
           <span class="font-medium-6" id="tis_no"> {!! !empty($lawlistministry->tis_name)?$lawlistministry->tis_name:null !!}</span>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
    
            <fieldset class="white-box">
                <legend class="legend"><h5>ข้อมูลการดำเนินการ</h5></legend>
    
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
    
                        @if( count($lawlistministry->law_listen_ministry_track) >= 1 )
                            @foreach(  $lawlistministry->law_listen_ministry_track as $listentrack )
                                <tr  data-repeater-item>
                                    <td class="text-top text-center">
                                        <span class="td_no">1</span>
                                        {!! Form::hidden('listentrack_id' , $listentrack->id , ['class' => '' , 'required' => false])   !!}
                                    </td>
                                    <td class="text-top">
                                        <div class="form-group col-md-12">
                                            <div class="inputWithIcon">
                                                {!! Form::text('date_track', !empty($listentrack->date_track)?HP::revertDate($listentrack->date_track,true):null , ['class' => 'form-control mydatepicker_edit', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true, 'readonly' => true ] ) !!}
                                                <i class="icon-calender"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-top">
                                        <div class="form-group col-md-12">
                                            {!! Form::select('status_id',App\Models\Law\Basic\LawStatusOperation::where('law_bs_category_operate_id', 1)->orderbyRaw('CONVERT(id USING tis620)')->pluck('title', 'id'),  !empty($listentrack->status_id)?$listentrack->status_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true, 'disabled' => true ]) !!}
                                        </div>
                                    </td>
                                    <td class="text-top">
                                        <div class="form-group col-md-12">
                                            <div class="inputWithIcon">
                                                {!! Form::text('date_due', !empty($listentrack->date_due)?HP::revertDate($listentrack->date_due,true):null, ['class' => 'form-control mydatepicker_edit', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true, 'readonly' => true  ] ) !!}
                                                <i class="icon-calender"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-top">
                                        <div class="form-group col-md-12">
                                            {!! Form::textarea('detail', !empty($listentrack->detail)?$listentrack->detail:null , ['class' => 'form-control', 'rows' => 3, 'readonly' => true ]) !!}
                                        </div>
                                    </td>
                                    <td class="text-top">
    
                                        @if( !empty($listentrack->AttachFileTrack) )
                                            @php
                                                $attach = $listentrack->AttachFileTrack;
                                            @endphp
                                            <div class="form-group col-md-12 operation_attach">
                                                <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                                    {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                                    {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                                </a>
    
                                                <a class="btn btn-danger btn-xs m-l-15 show_tag_a" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/listen/ministry-track/'.$listentrack->id.'/edit') ) !!}" title="ลบไฟล์">
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
                                                        <input type="file" name="file_law_listministry_track" class="check_max_size_file" disabled>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-top text-center">
                                        <button type="button" class="btn btn-primary btn-sm staf_edit" >
                                            <i class="fa fa-pencil"></i>
                                        </button> 
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
                                    {!! Form::hidden('listentrack_id' , null , ['class' => '' , 'required' => false])   !!}
                                </td>
                                <td class="text-top">
                                    <div class="form-group col-md-12">
                                        <div class="inputWithIcon">
                                            {!! Form::text('date_track', '', ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                                            <i class="icon-calender"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-top">
                                    <div class="form-group col-md-12">
                                        {!! Form::select('status_id',App\Models\Law\Basic\LawStatusOperation::where('law_bs_category_operate_id', 1)->orderbyRaw('CONVERT(id USING tis620)')->pluck('title', 'id'), '', ['class' => 'form-control ', 'placeholder'=>'- เลือกการดำเนินการ -', 'required' => true ]) !!}
                                    </div>
                                </td>
                                <td class="text-top">
                                    <div class="form-group col-md-12">
                                        <div class="inputWithIcon">
                                            {!! Form::text('date_due', '', ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
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
                                                    <input type="file" name="file_law_listministry_track" class="check_max_size_file">
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
    
        </div>
    </div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-3">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-departments'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/basic/type-file') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>


@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
  <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
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
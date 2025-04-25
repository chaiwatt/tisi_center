@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style>

    </style>
@endpush


<div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('book_no') ? 'has-error' : ''}}">
            {!! Form::label('book_no', 'เลขที่หนังสือ'.':', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('book_no', null, ['class' => 'form-control', 'placeholder' => 'เลขที่หนังสือ', 'required' => true]) !!}
                {!! $errors->first('book_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('receive_date', 'วันที่รับ'.':', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                <div class="inputWithIcon">
                    {!! Form::text('receive_date', !empty($lawtrackreceive->receive_date)? HP::revertDate($lawtrackreceive->receive_date, true) : null, ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => true ] ) !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('receive_no') ? 'has-error' : ''}}">
            {!! Form::label('receive_no', 'เลขรับ'.':', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('receive_no',  null, ['class' => 'form-control', 'placeholder' => 'เลขรับ', 'required' => true]) !!}
                {!! $errors->first('receive_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('receive_time', 'เวลา'.':', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                <div class="input-group">
                    {!! Form::text('receive_time',  !empty($lawtrackreceive->receive_time)?(\Carbon\Carbon::parse($lawtrackreceive->receive_time)->timezone('Asia/Bangkok')->format('H:i')):null, ['class' => 'form-control text-center','id'=>'start_time', 'required' => true]); !!}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info" title="">น.</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('law_deperment_type') ? 'has-error' : ''}}">
            {!! Form::label('law_deperment_type', 'ประเภทหน่วยงาน', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('law_deperment_type', [ 1 => 'หน่วยงานภายใน (สมอ.)', 2 => 'หน่วยงานภายนอก' ]  , !empty($lawtrackreceive->law_deperment_type)?$lawtrackreceive->law_deperment_type:null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทหน่วยงาน -', 'required' => true ]) !!}
                {!! $errors->first('law_deperment_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group box_law_bs_deperment_id required{{ $errors->has('law_bs_deperment_id') ? 'has-error' : ''}}">
            {!! Form::label('law_bs_deperment_id', 'หน่วยงานเจ้าของเรื่อง', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('law_bs_deperment_id',   App\Models\Law\Basic\LawDepartment::Where('state',1)->Where('type',2)->pluck('title', 'id') , !empty($lawtrackreceive->law_bs_deperment_id)?$lawtrackreceive->law_bs_deperment_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงานเจ้าของเรื่อง -', 'required' => true ]) !!}
                {!! $errors->first('law_bs_deperment_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
            {{-- @php
                dd(!empty($lawtrackreceive->department_id)?$lawtrackreceive->department_id:null);
            @endphp --}}
        <div class="form-group box_sub_departments_id required{{ $errors->has('deperment_id') ? 'has-error' : ''}}">
            {!! Form::label('department_id', 'กลุ่มงานหลัก', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('department_id',  App\Models\Besurv\Department::pluck('depart_name', 'did') , !empty($lawtrackreceive->department_id)?$lawtrackreceive->department_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานหลัก -', 'required' => true , 'id'=>'department_id' ]) !!}
                {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('law_bs_job_type_id') ? 'has-error' : ''}}">
            {!! Form::label('law_bs_job_type_id', 'ประเภทงาน', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('law_bs_job_type_id', App\Models\Law\Basic\LawJobType::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกประเภทงาน -', 'required' => true ]) !!}
                {!! $errors->first('law_bs_job_type_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group box_sub_departments_id {{ $errors->has('sub_departments_id') ? 'has-error' : ''}}">
            {!! Form::label('sub_departments_id', 'กลุ่มงานย่อย', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('sub_departments_id',[], !empty($lawtrackreceive->sub_departments_id)?$lawtrackreceive->sub_departments_id:null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานย่อย -' , 'id'=>'sub_departments_id' ]) !!}
                {!! $errors->first('sub_departments_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group box_law_bs_deperment_other  required {{ $errors->has('department_other') ? 'has-error' : ''}}" style="display: none;">
            {!! Form::label('law_bs_deperment_other', 'อื่นๆระบุ', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::textarea('law_bs_deperment_other', null, ['class' => 'form-control', 'rows'=>'4']) !!}
                {!! $errors->first('law_bs_deperment_other', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
            {!! Form::label('title', 'ชื่อเรื่อง'.':', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'ชื่อเรื่อง', 'required' => true]) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
            {!! Form::label('description', 'คำอธิบาย (ถ้ามี)'.':', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::textarea('description', null , ['class' => 'form-control', 'rows' => 4 ]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="{{ $errors->has('attach_show') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('attach_show', 'ไฟล์เเนบ'.' : ', ['class' => 'col-md-2 control-label'])) !!}
            <div class="col-md-9 m-t-5" >
                <span class="text-warning">อัพโหลดได้เฉพาะไฟล์ .pdf, .docx หรือ .xlsx ไฟล์ละไม่เกิน 10 MB </span> <span class="text-muted">(เพิ่มไฟล์ได้ไม่เกิน 5 ไฟล์)</span>
            </div>
        </div>
    </div>
</div>

<div class="row repeater-form-file">
    <div class="col-md-12"  data-repeater-list="repeater-attach">
        <div class="form-group count_files"  data-repeater-item>
            <div class="col-md-offset-2 col-md-4">
                {!! Form::text('attach_description', null, ['class' => 'form-control', 'placeholder' => 'คำอธิบาย']) !!}
            </div>
            <div class="col-md-4">
                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                    <div class="form-control " data-trigger="fileinput" >
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                        <span class="input-group-text btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="attach_file" class="check_max_size_file">
                        </span>
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>
                    <i class="fa fa-times"></i>
                </button> 
                <button type="button" class="btn btn-success btn-sm btn_file_add" data-repeater-create>
                    <i class="fa fa-plus"></i>
                </button>  
            </div>
        </div>
    </div>
</div>

@if( isset($lawtrackreceive->file_law_track_receives) && ($lawtrackreceive->file_law_track_receives->count() >= 1) )

    <div class="row">
        <div class="col-md-12">
            @foreach ( $lawtrackreceive->file_law_track_receives as $Ifile )

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        {!! Form::text('attach_description_show', !empty($Ifile->caption)?$Ifile->caption:null, ['class' => 'form-control', 'placeholder' => 'คำอธิบาย', 'disabled' => true ]) !!}
                    </div>
                    <div class="col-md-4">
                        <a href="{!! HP::getFileStorage($Ifile->url) !!}" target="_blank" class="m-l-5">
                            {!! !empty($Ifile->filename) ? $Ifile->filename : '' !!}
                            {!! HP::FileExtension($Ifile->filename)  ?? '' !!}
                        </a>
                        <a class="btn btn-danger btn-xs show_tag_a m-l-5 count_files" href="{!! url('law/delete-files/'.($Ifile->id).'/'.base64_encode('law/track/receive/'.$lawtrackreceive->id.'/edit') ) !!}" title="ลบไฟล์">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('created_by_show', 'ผู้บันทึก'.':', ['class' => 'col-md-4 control-label font-medium-6']) !!}
            <div class="col-md-8">
                {!! Form::text('created_by_show', !empty($lawtrackreceive->created_by)? $lawtrackreceive->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('created_by_show', 'วันที่บันทึก'.':', ['class' => 'col-md-4 control-label font-medium-6']) !!}
            <div class="col-md-8">
                {!! Form::text('created_by_show',  !empty($lawtrackreceive->created_at)? HP::revertDate($lawtrackreceive->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-track-receive'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/track/receive')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/inputmask/jquery.inputmask.bundle.js')}}"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#start_time').inputmask('99:99');

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            var sub_departments_id = $('#sub_departments_id').html();
            $('#department_id').change(function (e) {
                $('#sub_departments_id').html('<option value=""> - เลือกกลุ่มงานย่อย - </option>');
                DepartmentsOption();
                $('#sub_departments_id').html(sub_departments_id);
                $('#sub_departments_id').val('').trigger('change.select2');
            });
            DepartmentsOption();

            $('#law_bs_deperment_id').change(function (e) {
                ShowHideDepartmentsOther();
            });
            ShowHideDepartmentsOther();

            //เพิ่มลบไฟล์แนบ
            $('.repeater-form-file').repeater({
                show: function () {
                    let $count_files = $('.count_files').length;
                    if($count_files <= 5){
                        $(this).slideDown();
                        $(this).find('.btn_file_add').remove();
                        BtnDeleteFile();
                    }else{
                        $(this).remove();
                        alert('เพิ่มได้ไม่เกิน 5 ไฟล์ !');
                    }
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    BtnDeleteFile();
                }
            });

            BtnDeleteFile();

            $('#law_deperment_type').change(function (e) { 
                BoxDeparment();
                
            });

            BoxDeparment();

        });

        function BoxDeparment(){

            var type = $('#law_deperment_type').val();

            var type1 = $('.box_sub_departments_id');
            var type2 = $('.box_law_bs_deperment_id');

            if( type == 1){

                type1.show();
                type1.find('#department_id').prop('disabled', false);
                type1.find('#department_id').prop('required', true);
                
                $('.box_law_bs_deperment_other').hide();
                $('#law_bs_deperment_other').prop('disabled', true);
              
                type2.hide();
                type2.find('select').prop('disabled', true);
                type2.find('select').prop('required', false);

            }else{

                type1.hide();
                type1.find('#department_id').prop('disabled', true);
                type1.find('#department_id').prop('required', false);


                type2.show();
                type2.find('select').prop('disabled', false);
                type2.find('select').prop('required', true);

                ShowHideDepartmentsOther();
            }

        }

        function BtnDeleteFile(){

            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }

            $('.btn_file_remove:first').hide();   
            $('.btn_file_add:first').show();   
            check_max_size_file();
        }

        function DepartmentsOption(){
            var deperment_id = $('#department_id').val();
            var sub_id = '{!! (!empty($lawtrackreceive->sub_departments_id)?$lawtrackreceive->sub_departments_id:null) !!}';

            if(deperment_id!=""){//ดึงประเภทตามหมวดหมู่
                    $.ajax({
                        url: "{!! url('/law/funtion/get-sub-departments') !!}" + "?id=" + deperment_id
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#sub_departments_id').append('<option value="'+data.sub_id+'">'+data.sub_departname+'</option>');
                        });
                        if(sub_id!=""){
                            $('#sub_departments_id').val(sub_id).trigger('change.select2')
                        }else{
                            $('#sub_departments_id').val('').trigger('change.select2')
                        }
                    });
                }else{
                    $('#sub_departments_id').html(sub_departments_id);
                    $('#sub_departments_id').val('').trigger('change.select2');
                }
        }

        function ShowHideDepartmentsOther(){
            var law_bs_deperment_id = $('#law_bs_deperment_id').val();
            if(law_bs_deperment_id !=""){
                $.ajax({
                    url: "{!! url('/law/funtion/get-other-departments') !!}" + "?id=" + law_bs_deperment_id
                }).done(function( other ) {
                    if(other){
                        $('.box_law_bs_deperment_other').show();
                        $('#law_bs_deperment_other').prop('disabled', false);
                        $('#law_bs_deperment_other').prop('required', true);
                    }else{
                        $('.box_law_bs_deperment_other').hide();
                        $('#law_bs_deperment_other').prop('disabled', true);
                        $('#law_bs_deperment_other').prop('required', false);
                    }             
        
                });
     
            }
        }
    </script>
@endpush
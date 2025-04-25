@push('css')
 
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
 
    <style>
        .vertical {
            float: left;
            border-right: 2px solid #eee;
        }
        input[type="checkbox"]:disabled {
            cursor: not-allowed;
        }
        .alert-secondary {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }
        
    </style>
@endpush

@php
    $cases_result   = $lawcases->law_cases_result_to;
    $result_section = $lawcases->result_section;

    $offend_books   = $lawcases->offend_books;
    //ความผิดตาม พรบ. 2511
    $offend_act     = !empty($offend_books->offend_act)? $offend_books->offend_act:$lawcases->result_section->pluck('SectionName')->toArray();
    //แจ้งข้อกล่าวหา
    $offend_report  = !empty($offend_books->offend_report)? $offend_books->offend_report:[];

    //สิ่งที่ส่งมาด้วย
    $book_enclosure = !empty($offend_books->book_enclosure)? $offend_books->book_enclosure:[];

    $subdepart_ids  = ['0600','0601','0602','0603','0604'];
    //นิติกร
    $users          = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')->whereIn('reg_subdepart',$subdepart_ids)->pluck('title', 'id');    

@endphp
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend><b>หนังสือแจ้งการกระทำความผิด/หนังสือบันทึกคำให้การ</b></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('book_number', 'เลขที่หนังสือ', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-8">
                            {!! Form::text('book_number', !empty($offend_books->book_number) ? $offend_books->book_number : '', ['class' => 'form-control', 'disabled' => true, 'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึกข้อมูล' ]) !!}
                            {!! $errors->first('book_number', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('book_title', 'เรื่อง', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-8">
                            {!! Form::text('book_title', !empty($offend_books->book_title) ? $offend_books->book_title : 'แจ้งการกระทำความผิดและสิทธิการเปรียบเทียบปรับตามพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. ๒๕๑๑', ['class' => 'form-control'  ]) !!}
                            {!! $errors->first('book_title', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('book_date', 'วันที่จัดทำ'.' :', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-btn bg-white">
                                    {!! Form::select('book_date[book_day]', HP::RangeData(1,31) , !empty($offend_books->book_date['book_day'])?$offend_books->book_date['book_day']:( empty($offend_books)?date('d'):null ),  ['class' => 'form-control', 'placeholder'=>'- วัน -']) !!}
                                </div>
                                <div class="input-group-btn bg-white p-l-15">
                                    {!! Form::select('book_date[book_month]', HP_Law::getMonthThais(), !empty($offend_books->book_date['book_month'])?$offend_books->book_date['book_month']:( empty($offend_books)?date('m'):null ),  ['class' => 'form-control',  'placeholder'=>'- เดือน -']) !!}
                                </div>
                                <div class="input-group-btn bg-white p-l-15">
                                    {!! Form::select('book_date[book_year]', HP::YearListReport(), !empty($offend_books->book_date['book_year'])?$offend_books->book_date['book_year']:( empty($offend_books)?date('Y'):null ),  ['class' => 'form-control', 'placeholder'=>'- ปี -']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row repeater-offend-enclosure">
                <div class="col-md-12">
                    <div class="form-group" >
                        {!! HTML::decode(Form::label('book_enclosure', 'สิ่งที่ส่งมาด้วย', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-7" data-repeater-list="repeater-enclosure">
                            @if( is_array($book_enclosure) && count($book_enclosure) >= 1 )
                                @foreach ( $book_enclosure as $Ienclosure )
                                    <div class="form-group row_enclosure" data-repeater-item>
                                        <div class="col-md-10" >
                                            {!! Form::text('book_enclosure', !empty( $Ienclosure )?$Ienclosure:null, ['class' => 'form-control ' ]) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-label-danger btn-sm btn_enclosure_remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group row_enclosure" data-repeater-item>
                                    <div class="col-md-10" >
                                        {!! Form::text('book_enclosure',  'บันทึกคำให้การของผู้ต้องหา', ['class' => 'form-control ' ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-label-danger btn-sm btn_enclosure_remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
           
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('offend_name', 'ผู้กระทำความผิด', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-8">
                            {!! Form::text('offend_name', !empty($lawcases->offend_name) ? $lawcases->offend_name : null, ['class' => 'form-control ' , 'disabled' => true  ]) !!}
                            {!! $errors->first('offend_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! HTML::decode(Form::label('offend_found', 'พบการกระทำความผิด', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-8">
                            {!! Form::text('offend_found', !empty($offend_books->offend_found) ? $offend_books->offend_found : 'ผลิตภัณฑ์ดังกล่าวไม่เป็นไปตามมาตราฐาน', ['class' => 'form-control ' , 'required' => true  ]) !!}
                            {!! $errors->first('offend_found', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row repeater-offend-act">
                <div class="col-md-12">
                    <div class="form-group required" >
                        {!! HTML::decode(Form::label('offend_act', 'ความผิดตาม พรบ. 2511 ดังนี้', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-9" data-repeater-list="repeater-act">
                            @if( is_array($offend_act) && count($offend_act) >= 1 )
                                @foreach ( $offend_act as $Isection )
                                    <div class="form-group row_act" data-repeater-item>
                                        <div class="col-md-10" >
                                            {!! Form::textarea('offend_act',  !empty( $Isection )?$Isection:null , ['class' => 'form-control', 'rows' => 3 , 'required' => true ]) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-label-danger btn-sm btn_act_remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group row_act" data-repeater-item>
                                    <div class="col-md-10" >
                                        {!! Form::textarea('offend_act',  null, ['class' => 'form-control', 'rows' => 3 , 'required' => true ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-label-danger btn-sm btn_act_remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row repeater-offend-report">
                <div class="col-md-12">
                    <div class="form-group required" >
                        {!! HTML::decode(Form::label('offend_report', 'แจ้งข้อกล่าวหา', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-9" data-repeater-list="repeater-report">
                            @if( is_array($offend_report) && count($offend_report) >= 1 )
                                @foreach ( $offend_report as $Ireport )
                                    <div class="form-group row_report" data-repeater-item>
                                        <div class="col-md-10" >
                                            {!! Form::textarea('offend_report',   !empty( $Ireport )?$Ireport:null , ['class' => 'form-control', 'rows' => 3 , 'required' => true ]) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-label-danger btn-sm btn_report_remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                @endforeach
                            @else
                                <div class="form-group row_report" data-repeater-item>
                                    <div class="col-md-10" >
                                        {!! Form::textarea('offend_report',  null, ['class' => 'form-control', 'rows' => 3 , 'required' => true ]) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-label-danger btn-sm btn_report_remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! HTML::decode(Form::label('lawyer_id', 'นิติกร', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-7">
                            {!! Form::select('lawyer_id', $users,   !empty($offend_books->lawyer_id) ? $offend_books->lawyer_id : $lawcases->lawyer_by,   [  'class' => 'form-control ', 'placeholder'=>'- เลือกนิติกร -',  'required' => true  ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('file') ? 'has-error' : ''}}">
                        {!! Form::label('file', 'ไฟล์หนังสือแจ้งการกระทำความผิด'.' :', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-5">
                            @if( !empty($offend_books->id) )
                                <a class="btn btn-icon btn-sm btn-label-primary btn-circle"  target="_blank"   href="{!! url('/law/export/results/book_charges?id='.$offend_books->id) !!}" >
                                    <i  class="fa fa-file-word-o"  style="font-size: 1.5em;" aria-hidden="true"></i>
                                </a>   
                            @else
                                {!! Form::text('file', null,['class' => 'form-control' ,'disabled' => true , 'placeholder'=>'แสดงเมื่อบันทึกข้อมูล'  ]) !!}
                                {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('file') ? 'has-error' : ''}}">
                        {!! Form::label('file', 'ไฟล์หนังสือบันทึกคำให้การ'.' :', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-5">
                            @if( !empty($offend_books->id) )
                                <a class="btn btn-icon btn-sm btn-label-primary btn-circle"  target="_blank"   href="{!! url('/law/export/results/book_statements?id='.$offend_books->id) !!}" >
                                    <i  class="fa fa-file-word-o"  style="font-size: 1.5em;" aria-hidden="true"></i>
                                </a>   
                            @else
                                {!! Form::text('file', null,['class' => 'form-control' ,'disabled' => true , 'placeholder'=>'แสดงเมื่อบันทึกข้อมูล'  ]) !!}
                                {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </fieldset>
    </div>
</div>

@if( $lawcases->status >= 5 )
    <div class="form-group">
        <div class="col-md-offset-5 col-md-4">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
    
            @can('view-'.str_slug('law-cases-result'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/results') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@endif

@push('js')
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {

            @if ( \Session::has('printing_message'))
                Swal.fire({
                    title: 'บันทึกสำเร็จ',
                    text: "คุณต้องทำรายการต่อหรือไม่ ?",
                    icon: 'success',
                    width: 500,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'กลับหน้าแรก',
                    cancelButtonText: 'ทำรายการต่อ',
                    confirmButtonClass: 'btn btn-primary btn-sm',
                    cancelButtonClass: 'btn btn-danger  btn-sm m-l-5',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '{!! url('law/cases/results') !!}';
                    }
                });
            @endif

            @if (  $lawcases->status < 5 )
                $('.form-printing').find('button[type="submit"]').remove();
                $('.form-printing').find('.icon-close').parent().remove();
                $('.form-printing').find('.fa-copy').parent().remove();
                $('.form-printing').find('input').prop('disabled', true);
                $('.form-printing').find('textarea').prop('disabled', true);
                $('.form-printing').find('select').prop('disabled', true);
                $('.form-printing').find('.bootstrap-tagsinput').prop('disabled', true);
                $('.form-printing').find('span.tag').children('span[data-role="remove"]').remove();
                $('.form-printing').find('button').prop('disabled', true);
                $('.form-printing').find('button').remove();
                $('.form-printing').find('.btn-remove-file').parent().remove();
                $('.form-printing').find('.show_tag_a').hide();
                $('.form-printing').find('.input_show_file').hide();
            @endif

            @if(\Session::has('flash_message'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session()->get('flash_message')}}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            //ความผิดตาม พรบ. 2511 
            $('.repeater-offend-act').repeater();
            BtnDeleteAct();

            $(document).on('click', '.btn_add_act', function(e) {
                   
                var html = '';
                    html += '<div class="form-group row_act" data-repeater-item>';

                    html += '<div class="col-md-10">';
                    html += '<textarea class="form-control" rows="3" required="" name="offend_act"></textarea>';
                    html += '</div>';

                    html += '<div class="col-md-2">';
                    html += '<button type="button" class="btn btn-label-danger btn-sm btn_act_remove"><i class="fa fa-times"></i></button>';
                    html += '</div>';

                    html += '</div>';
 
                var box = $('.repeater-offend-act');
                    box.find('div.row_act:last').after(html);
                BtnDeleteAct();

            });

            $(document).on('click', '.btn_act_remove', function(e) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest( ".row_act" ).remove();
                    BtnDeleteAct();
                }
            });

            //แจ้งข้อกล่าวหา
            $('.repeater-offend-report').repeater();
            BtnDeleteReport();

            $(document).on('click', '.btn_add_report', function(e) {
                   
                var html = '';
                    html += '<div class="form-group row_report" data-repeater-item>';

                    html += '<div class="col-md-10">';
                    html += '<textarea class="form-control" rows="3" required="" name="offend_report"></textarea>';
                    html += '</div>';

                    html += '<div class="col-md-2">';
                    html += '<button type="button" class="btn btn-label-danger btn-sm btn_report_remove"><i class="fa fa-times"></i></button>';
                    html += '</div>';

                    html += '</div>';

                var box = $('.repeater-offend-report');
                    box.find('div.row_report:last').after(html);

                BtnDeleteReport();

            });

            $(document).on('click', '.btn_report_remove', function(e) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest( ".row_report" ).remove();
                    BtnDeleteReport();
                }
            });

            //สิ่งที่ส่งมาด้วย
            $('.repeater-offend-enclosure').repeater();
            BtnDeleteEnclosure();
            $(document).on('click', '.btn_add_enclosure', function(e) {

                var html = '';
                    html += '<div class="form-group row_enclosure" data-repeater-item>';

                    html += '<div class="col-md-10">';
                    html += '<input class="form-control " name="book_enclosure" type="text" id="book_enclosure">';
                    html += '</div>';

                    html += '<div class="col-md-2">';
                    html += '<button type="button" class="btn btn-label-danger btn-sm btn_enclosure_remove"><i class="fa fa-times"></i></button>';
                    html += '</div>';

                    html += '</div>';
 
                var box = $('.repeater-offend-enclosure');
                    box.find('div.row_enclosure:last').after(html);
                BtnDeleteEnclosure();

            });

            $(document).on('click', '.btn_enclosure_remove', function(e) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).closest( ".row_enclosure" ).remove();
                    BtnDeleteEnclosure();
                }
            });

        });

        function BtnDeleteAct(){
            if( $('.btn_act_remove').length >= 2 ){
                $('.btn_act_remove').show();
            }else{
                $('.btn_act_remove').hide();   
            }

            var btn = '<button type="button" class="btn btn-label-success btn-sm m-l-15 btn_add_act"> <i class="fa fa-plus"></i> </button>';

            var box = $('.repeater-offend-act');

            if( box.find('.btn_act_remove').length >= 2 ){
                box.find('.btn_act_remove').show();
            }else{
                box.find('.btn_act_remove').hide();   
            }

            box.find('.btn_add_act').remove();
            box.find('.btn_act_remove:last').after( btn );

            $('.repeater-offend-act').repeater();
        }

        function BtnDeleteReport(){

            var btn = '<button type="button" class="btn btn-label-success btn-sm m-l-15 btn_add_report"> <i class="fa fa-plus"></i> </button>';
            var box = $('.repeater-offend-report');

            if( box.find('.btn_report_remove').length >= 2 ){
                box.find('.btn_report_remove').show();
            }else{
                box.find('.btn_report_remove').hide();   
            }

            box.find('.btn_add_report').remove();
            box.find('.btn_report_remove:last').after( btn );

            $('.repeater-offend-report').repeater();
        }

        function BtnDeleteEnclosure(){

            var btn = '<button type="button" class="btn btn-label-success btn-sm m-l-15 btn_add_enclosure"> <i class="fa fa-plus"></i> </button>';

            var box = $('.repeater-offend-enclosure');

            if( box.find('.btn_enclosure_remove').length >= 2 ){
                box.find('.btn_enclosure_remove').show();
            }else{
                box.find('.btn_enclosure_remove').hide();   
            }

            box.find('.btn_add_enclosure').remove();
            box.find('.btn_enclosure_remove:last').after( btn );

            $('.repeater-offend-enclosure').repeater();
        }
    </script>
@endpush 
@extends('layouts.single')
@section('content')
    <div class="container-fluid">
        @push('css')
        <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    @endpush
    
    <style>
        textarea.form-control {
            border-radius: 0 !important;
            border-top: none !important;
            border-bottom: none !important;
            resize: none;
            overflow: hidden; /* ซ่อน scrollbar */
        }
        .no-hover-animate tbody tr:hover {
            background-color: inherit !important; /* ปิดการเปลี่ยนสี background */
            transition: none !important; /* ปิดเอฟเฟกต์การเปลี่ยนแปลง */
        }
        
        /* กำหนดขนาดความกว้างของ SweetAlert2 */
        .custom-swal-popup {
            width: 500px !important;  /* ปรับความกว้างตามต้องการ */
        }
    
        textarea.non-editable {
            pointer-events: none; /* ทำให้ไม่สามารถคลิกหรือแก้ไขได้ */
            opacity: 0.9; /* กำหนดความทึบของ textarea */
        }
    </style>
    

    
    {{-- <div class="row">
        <div class="col-md-12"> --}}
    <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน <span class="text-warning">(หมดเวลา {{HP::DateTimeFullThai($expiryDateTime)}})</span></h3>

    <div class="clearfix"></div>
    <hr>

    <form action="{{ route('store_by_expert.lab_sur') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form_assessment">
        @csrf
       <input type="hidden" name="assessment_id" value="{{$assessment->id}}">
        <div class="row">
        
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('reference_refno') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('reference_refno', '<span class="text-danger">*</span> '.'เลขคำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-7">
                        @if(isset($app_no))
                        {!! Form::select('auditors_id', 
                            $app_no, 
                            null,
                            ['class' => 'form-control',
                            'id' => 'auditors_id',
                            'placeholder'=>'- เลขคำขอ -', 
                            'required' => true]); !!}
                        {!! $errors->first('auditors_id', '<p class="help-block">:message</p>') !!}
                        @else 
                            <input type="text" class="form-control"    value="{{ $assessment->tracking_to->reference_refno ?? null }}"   disabled >  
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('name','ชื่อผู้ยื่นคำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('name', $assessment->name, ['id' => 'applicant_name', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row " style="margin-top:10px">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('laboratory_name') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('laboratory_name','ชื่อห้องปฏิบัติการ '.' :', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('laboratory_name',   $assessment->laboratory_name , ['id' => 'laboratory_name', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span> '.'ชื่อคณะผู้ตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('auditor',  $assessment->auditors_to->auditor, ['id' => 'auditor', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row " style="margin-top:10px">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('auditor_date') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('auditor_date', '<span class="text-danger">*</span> '.'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('auditor_date',   HP::revertDate($assessment->created_at->format("Y-m-d"),true), ['id' => 'auditor_date', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                    </div>
                </div>
            </div>
    
        </div>
    
        <div class="form-group {{ $errors->has('laboratory_name') ? 'has-error' : ''}}" hidden>
            <label for="laboratory_name" class="col-md-3 control-label">
                <span class="text-danger">*</span> รายงานข้อบกพร่อง :
            </label>
            <div class="col-md-7">
                <div class="row">
                    <label class="col-md-3">
                        <input type="radio" name="bug_report" value="1" class="check check-readonly" data-radio="iradio_square-green" required checked> มี
                    </label>
                    <label class="col-md-3">
                        <input type="radio" name="bug_report" value="2" class="check check-readonly" data-radio="iradio_square-red" required> ไม่มี
                    </label>
                </div>
            </div>
        </div>
    
        <div class="clearfix"></div>
        <div class="row status_bug_report">
            <div class="col-sm-12 m-t-15 "  id="box-required">
                <div class="row">
                    <div class="col-md-12 text-right" style="margin-bottom:5px">
                            <button type="button" class="   btn btn-success btn-sm div_hide" id="plus-row"><i class="icon-plus"></i> เพิ่ม</button>
                    </div>
                </div>
                <table class="table color-bordered-table primary-bordered-table">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">ลำดับ</th>
                        <th class="text-center" width="10%">รายงานที่</th>
                        <th class="text-center" width="10%">ข้อบกพร่อง/ข้อสังเกต</th>
                        <th class="text-center" width="10%">
                            มอก. 17025 : ข้อ
                        </th>
                        <th class="text-center" width="10%">ประเภท</th>
                        <th class="text-center  div_hide " width="5%"> <i class="fa fa-pencil-square-o"></i></th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                        @if ($bug->count() != 0)
                            @foreach($bug as $key => $item)
                                <tr>
                                    <td class="text-center" style="padding: 0px;">
                                        1
                                    </td>
                                    <td style="padding: 0px;">
                                  
                                        <input type="hidden" name="detail[id][]" value="{{ !empty($item->assessment_id) ? $item->assessment_id : null }}" class="form-control">
                                        <textarea name="detail[report][]" class="form-control input_required auto-expand"  rows="5" style="border-right: 1px solid #ccc;" required >{{ $item->report ?? null }}</textarea>
                                    </td>
                                    <td style="padding: 0px;">
                                        <textarea name="detail[notice][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->remark ?? null }}</textarea>
                                    </td>
                                    <td style="padding: 0px;">
                                        <textarea name="detail[no][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->no ?? null }}</textarea>
                                    </td>
                                    <td style="padding-left: 15px;vertical-align:top">
                                        <select name="detail[type][]" class="form-control type input_required select2" required>
                                            <option value="" disabled {{ empty($item->type) ? 'selected' : '' }}>-เลือกประเภท-</option>
                                            <option value="1" {{ $item->type == '1' ? 'selected' : '' }}>ข้อบกพร่อง</option>
                                            <option value="2" {{ $item->type == '2' ? 'selected' : '' }}>ข้อสังเกต</option>
                                        </select>
                                    </td>
                                    <td class="text-center div_hide">
                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach 
                        @else    
                            <tr>
                                <td class="text-center" style="padding: 0px;">
                                    1
                                </td>
                                <td style="padding: 0px;">
                                    <input type="hidden" name="detail[id][]" value="{{ !empty($item->id) ? $item->id : null }}" class="form-control">
                                    <textarea name="detail[report][]" class="form-control input_required auto-expand"  rows="5" style="border-right: 1px solid #ccc;" required ></textarea>
                                </td>
                                <td style="padding: 0px;">
                                    <textarea name="detail[notice][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required></textarea>
                                </td>
                                <td style="padding: 0px;">
                                    <textarea name="detail[no][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required></textarea>
                                </td>
                                <td style="padding-left: 15px;vertical-align:top">
                                    <select name="detail[type][]" class="form-control type input_required select2" required>
                                        <option value="" disabled selected>-เลือกประเภท-</option>
                                        <option value="1" >ข้อบกพร่อง</option>
                                        <option value="2" >ข้อสังเกต</option>
                                    </select>
                                </td>
                                <td class="text-center div_hide">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endif
                    
                    </tbody>
                </table>
            </div>
        </div>
         
         <br>
         <div class="clearfix"></div>
    
         {{-- {{$assessment->degree}} --}}
        

            <div class="form-group">
             <div class="col-md-offset-5 col-md-6">
                <input type="hidden" name="previousUrl" id="previousUrl" value="{{ app('url')->previous() }}">
              
               

         
                 <div id="degree_btn"></div>
         
                 <input type="hidden" id="submit_type" name="submit_type">
                 {{-- <button class="btn btn-success " type="button"  id="confirm" onclick="submit_form('1','confirm');return false;" style="visibility: hidden">
                    <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
                </button> --}}
                <button class="btn btn-primary " type="button" id="save"  onclick="submit_form('1','save');return false;">
                    <i class="fa fa-paper-plane"></i><span id="save_text" style="padding-left:5px">บันทึก</span> 
                </button>
         
                 @can('view-'.str_slug('assessmentlabs'))
                     <a class="btn btn-default" href="{{   app('url')->previous()  }}">
                         <i class="fa fa-rotate-left"></i> ยกเลิก
                     </a>
                 @endcan
             </div>
            </div>
      
    </form>

     
    
    @push('js')
      <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
      <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
        <!-- input calendar thai -->
        <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
        <!-- thai extension -->
        <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
        <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
        <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
        <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
        <script>
            $(document).ready(function () {
                 check_max_size_file();
                //เพิ่มไฟล์แนบ
                $('#attach_add_scope').click(function(event) {
                    $('.other_attach_scope:first').clone().appendTo('#other_attach-box');
                    $('.other_attach_scope:last').find('input').val('');
                    $('.other_attach_scope:last').find('a.fileinput-exists').click();
                    $('.other_attach_scope:last').find('a.view-attach').remove();
                    $('.other_attach_scope:last').find('.attach_remove').remove();
                    $('.other_attach_scope:last').find('.button_remove_scope').html('<button class="btn btn-danger btn-sm attach_remove_scope" type="button"> <i class="icon-close"></i>  </button>');
                    check_max_size_file();
                });
    
                //ลบไฟล์แนบ
                $('body').on('click', '.attach_remove_scope', function(event) {
                    $(this).parent().parent().parent().remove();
                });
    
                //เพิ่มไฟล์แนบ
                $('#attach_add_report').click(function(event) {
                    $('.other_attach_report:first').clone().appendTo('#other_attach_report');
                    $('.other_attach_report:last').find('input').val('');
                    $('.other_attach_report:last').find('a.fileinput-exists').click();
                    $('.other_attach_report:last').find('a.view-attach').remove();
                    $('.other_attach_report:last').find('.attach_remove').remove();
                    $('.other_attach_report:last').find('.button_remove_report').html('<button class="btn btn-danger btn-sm attach_remove_report" type="button"> <i class="icon-close"></i>  </button>');
                    check_max_size_file();
                });
    
                //ลบไฟล์แนบ
                $('body').on('click', '.attach_remove_report', function(event) {
                    $(this).parent().parent().parent().remove();
                });
    
                function autoExpand(textarea) {
                    textarea.style.height = 'auto'; // รีเซ็ตความสูง
                    textarea.style.height = textarea.scrollHeight + 'px'; // กำหนดความสูงตามเนื้อหา
                }
    
                // ฟังก์ชันปรับขนาด textarea ทุกตัวในแถวเดียวกัน
                function syncRowHeight(textarea) {
                    let $row = $(textarea).closest('tr'); // หา tr ที่ textarea อยู่
                    let maxHeight = 0;
    
                    // วนลูปหา maxHeight ใน textarea ทุกตัวในแถว
                    $row.find('.auto-expand').each(function () {
                        this.style.height = 'auto'; // รีเซ็ตความสูงก่อนคำนวณ
                        let currentHeight = this.scrollHeight;
                        if (currentHeight > maxHeight) {
                            maxHeight = currentHeight;
                        }
                    });
    
                    // กำหนดความสูงให้ textarea ทุกตัวในแถวเท่ากัน
                    $row.find('.auto-expand').each(function () {
                        this.style.height = maxHeight + 'px';
                    });
                }
    
                // ดักจับ event input
                $(document).on('input', '.auto-expand', function () {
                    // console.log('aha');
                    autoExpand(this); // ปรับ textarea ที่มีการเปลี่ยนแปลง
                    syncRowHeight(this); // ปรับ textarea ทั้งแถว
                });
    
                // ปรับขนาดทุก textarea เมื่อโหลดหน้าเว็บ
                $('.auto-expand').each(function () {
                    autoExpand(this);
                    syncRowHeight(this);
                });
    
            });
    
        </script>
      <script>
        function  submit_form(degree,submit_type){ 
            $('#submit_type').val(submit_type);
            var bug_report = $("input[name=bug_report]:checked").val(); 
            var vehicle =  $("input[name=vehicle]:checked").val();
            var main_state =  $("input[name=main_state]:checked").val();
            
            if(degree == 0)
            {  // ฉบับร่าง
                    Swal.fire({
                        title:'ยืนยันทำฉบับร่าง !',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'บันทึก',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                                $('#degree_btn').html('<input type="text" name="degree" value="' + degree + '" hidden>');
                                $('#form_assessment').submit();
                            }
                    })
                
    
            }else if(bug_report == 2){
                let i = 4;
                Swal.fire({
                        title:"ยืนยันทำรายการ !",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'บันทึก',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                                $('#degree_btn').html('<input type="text" name="degree" value="'+i+'" hidden>');
                                $('#form_assessment').submit();
                            }
                    })
            }
            else
            {
      
            if(degree == 0){  // ฉบับร่าง
                Swal.fire({
                    title:'ยืนยันทำฉบับร่างรายงานข้อบกพร่อง !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            $('#degree_btn').html('<input type="text" name="degree" value="' + degree + '" hidden>');
                            $('#form_assessment').submit();
                        }
                })
            }else{
                let title = '';
                let l = '';
                if(main_state == 2){
                    title =  'ยืนยันปิดผลการตรวจประเมิน !';
                    l = 8;
                }else{
                    title = 'ยืนยันการเพิ่มข้อบกพร่อง'
                                    if(submit_type == 'confirm'){
                                        title = 'ยืนยันทำรายงานข้อบกพร่องและ<br><span style="color: #f39c12;">อนุญาตผู้ประกอบการส่งรายงานแนวทางแก้ไข</span>'
                                    }
                    // title =  'ยืนยันทำรายงานข้อบกพร่อง !';
                    l = 1;
    
                }
             
                Swal.fire({
                    title:title,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก',
                    customClass: {
                                        popup: 'custom-swal-popup',  // ใส่คลาส CSS เพื่อจัดการความกว้าง
                                    }
                    }).then((result) => {
                        if (result.value) {
                            $('#degree_btn').html('<input type="text" name="degree" value="' + l + '" hidden>');
                            $('#form_assessment').submit();
                        }
                })
            }   
      
           } 
        }
        jQuery(document).ready(function() {
   
     
    
            let check_readonly = '{{ ($assessment->bug_report == 1)  ? 1 : 2 }}';
            if(check_readonly == 1){
                $('.check-readonly').prop('disabled', true);
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
            }
         
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });
            
            $("#auditors_id").change(function(){
                
     
                if($(this).val()!=""){
                    $.ajax({
                        url:'{{ url('certificate/assessment-labs/certi_labs') }}/' + $(this).val()
                    }).done(function( object ) {
                        
                            if(object.auditor != '-'){ 
                                let auditor = object.auditor;
                                $('#applicant_name').val(auditor.name); 
                                $('#laboratory_name').val(auditor.name_standard);
                                $('#Tis').html(auditor.tis); 
                            }else{
                                $('#applicant_name').val(''); 
                                $('#laboratory_name').val(''); 
                                $('#Tis').html(''); 
                            }
    
     
                    });
                }else{
                    $('#applicant_name').val(''); 
                    $('#laboratory_name').val(''); 
                }
    
            });
              //  รายงานข้อบกพร่อง
             $("input[name=bug_report]").on("ifChanged",function(){
                bug_report();
             });
                bug_report();
                function bug_report(){
                var row = $("input[name=bug_report]:checked").val(); 
                    if(row == "1"){ 
                        $('.status_bug_report').show(200); 
                        $('#submit_draft').show(200); 
                        $('#box-required').find('.input_required').prop('required', true);
                        // $('#div_file_scope').hide(400); 
                        $('#checkbox_document').hide(400); 
                        $('.file_scope_required').prop('required', false);
                        $('#confirm').css('visibility', 'visible');
                        // $('#save_text').html('ฉบับร่าง');
                    } else{
                        $('.status_bug_report').hide(400);
                        $('#submit_draft').hide(400); 
                        $('#box-required').find('.input_required').prop('required', false);
                        // $('#div_file_scope').show(200);
                   
                        $('#checkbox_document').show(200);  
                        $('.file_scope_required').prop('required', true);
                        $('#confirm').css('visibility', 'hidden');
                        // $('#save_text').html('บันทึก');
                    }
                }
    
            //เพิ่มแถว
            $('#plus-row').click(function(event) {
              //Clone
              $('#table-body').children('tr:first()').clone().appendTo('#table-body');
              //Clear value
                var row = $('#table-body').children('tr:last()');
                row.find('select.select2').val('');
                row.find('select.select2').prev().remove();
                row.find('select.select2').removeAttr('style');
                row.find('select.select2').select2();
                row.find('input[type="text"],textarea').val('');
                row.find('.file_attachs').html('');
                row.find('.parsley-required').html('');
                row.find('input[type="hidden"]').val('');
              //เลขรัน 
              ResetTableNumber();
       
            });
            //ลบแถว
            $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
            });
            ResetTableNumber();
    
    
    
        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
            $('.other_attach_item:first').clone().appendTo('#other_attach');
            $('.other_attach_item:last').find('input').val('');
            $('.other_attach_item:last').find('a.fileinput-exists').click();
            $('.other_attach_item:last').find('a.view-attach').remove();
            $('.other_attach_item:last').find('.label_other_attach').remove();
            $('.other_attach_item:last').find('button.attach-add').remove();
            $('.other_attach_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i>  </button>');
            check_max_size_file();
        });
    
        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
            $(this).parent().parent().parent().remove();
        });
    
      });
              //รีเซตเลขลำดับ
         function ResetTableNumber(){
          var rows = $('#table-body').children(); //แถวทั้งหมด
          (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
            //เลขรัน
            $(el).children().first().html(index+1);
          });
        }
    
    function  RemoveFlie(id){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '  <input type="file" name="file" required >';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
        Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบไฟล์แนบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                       $.ajax({
                            url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#RemoveFlie').remove();
                                $("#AddFile").append(html);
                                check_max_size_file();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });
    
                    }
                })
         }
    
         function  RemoveFlieScope(id){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="file_scope"  class="file_scope_required">';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
        Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบไฟล์แนบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                       $.ajax({
                            url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#RemoveFlieScope').remove();
                                $("#AddFileScope").append(html);
                                check_max_size_file();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });
    
                    }
                })
         }
    
         
        function  deleteFlieAttachAll(id){
          Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบไฟล์แนบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                       $.ajax({
                            url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#remove_attach_all'+id).remove();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });
    
                    }
                })
         }

    
    </script>
    @endpush
    
    
    </div>
@endsection

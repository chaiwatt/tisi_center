
@extends('layouts.single')
    @section('content')
    @push('css')
    {{-- <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" /> --}}

    <style>
        textarea.auto-expand {
            border-radius: 0 !important;
            border-top: none !important;
            border-bottom: none !important;
            resize: none; 
            overflow: hidden; 
            min-height: 50px; 
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

    @endpush

    <div class="container-fluid">

    
        <form action="{{ route('store_by_ib_expert') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form_ib_assessment">
            @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    {{-- <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน <span class="text-warning">(หมดเวลา )</span></h3> --}}
                    <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน<span class="text-warning">(หมดเวลา {{HP::DateTimeFullThai($expiryDateTime)}})</span></h3>

                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <input type="hidden" name="assessment_id" value="{{$assessment->id}}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" class="form-control" value="{{ $assessment->app_certi_ib_id ?? null  }}" name="app_certi_ib_id"  id="app_certi_ib_id">   
                                <input type="hidden" name="auditors_id" value="{{$auditorId}}">
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right">เลขคำขอ : </label>
                                    <div class="col-md-8">
                                        <input type="text" name="app_no" class="form-control" id="app_no" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right">ชื่อผู้ยื่นคำขอ : </label>
                                    <div class="col-md-8">
                                        {!! Form::text('name', null,  ['class' => 'form-control', 'id'=>'applicant_name','readonly'=>true])!!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4 text-right">ชื่อหน่วยรับรอง/หน่วยตรวจสอบ : </label>
                                    <div class="col-md-8">
                                        {!! Form::text('laboratory_name', null,  ['class' => 'form-control', 'id'=>'laboratory_name','readonly'=>true])!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                                
                    <div class="clearfix"></div>
                    <div class="row ">
                    
                        {{-- <div class="row"> --}}
                            <div class="col-md-12 text-right">
                                    <button type="button" class="   btn btn-success btn-sm div_hide" id="plus-row"><i class="icon-plus"></i> เพิ่ม</button>
                            </div>
                        {{-- </div> --}}
                    
                        <div class="col-sm-12 m-t-15 " >
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                <tr>
                                    <th class="text-center" width="1%">ลำดับ</th>
                                    <th class="text-center" width="13%">รายงานที่</th>
                                    <th class="text-center" width="20%">ข้อบกพร่อง/ข้อสังเกต</th>
                                    <th class="text-center" width="12%">
                                        มอก. <span id="Tis">
                                            {{  !empty($assessment->CertiIBCostTo->FormulaTo->title) ?   str_replace("มอก.","",$assessment->CertiIBCostTo->FormulaTo->title) :''  }}
                                        </span>
                                    </th>
                                    <th class="text-center" width="6%">ประเภท</th>
                                    {{-- <th class="text-center" width="10%">ผู้พบ</th> --}}
                                    <th class="text-center  div_hide " width="3%"> <i class="fa fa-pencil-square-o"></i></th>
                                </tr>
                                </thead>
                                <tbody id="table-body">
                                    @if ($bug->count() !=0)
                                        @foreach($bug as $key => $item)
                                            <tr>
                                                <td class="text-center">
                                                    1
                                                </td>
                                                <td style="padding: 0px;">
                                                    <input type="hidden" name="detail[id][]" class="form-control" value="{{ !empty($item->id) ? $item->id : '' }}">
                                                    <textarea name="detail[report][]" class="form-control input_required auto-expand" rows="5" style="border-right: 1px solid #ccc;"  required>{{ $item->report ?? '' }}</textarea>
                                                </td>
                                                <td style="padding: 0px;">
                                                    <textarea name="detail[notice][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->remark ?? '' }}</textarea>
                                                </td>
                                                <td style="padding: 0px;">
                                                    <textarea name="detail[no][]" class="form-control input_required auto-expand" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required>{{ $item->no ?? '' }}</textarea>
                                                </td>
                                                <td style="padding-left: 15px;vertical-align:top">
                                                    <select name="detail[type][]" class="form-control type input_required select2" required>
                                                        <option value="" disabled selected>-เลือกประเภท-</option>
                                                        <option value="1" {{ ($item->type ?? '') == '1' ? 'selected' : '' }}>ข้อบกพร่อง</option>
                                                        <option value="2" {{ ($item->type ?? '') == '2' ? 'selected' : '' }}>ข้อสังเกต</option>
                                                    </select>
                                                </td>
                                                <td class="text-center div_hide">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach 
                                    @else    
                                        <tr>
                                            <td class="text-center">
                                                1
                                            </td>
                                            <td style="padding: 0px;">
                                                <input type="hidden" name="detail[id][]" class="form-control" value="{{ !empty($item->id) ? $item->id : '' }}">
                                                <textarea name="detail[report][]" class="form-control input_required auto-expand" rows="5" style="border-right: 1px solid #ccc;"  required></textarea>
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
                                                    <option value="1" {{ ($item->type ?? '') == '1' ? 'selected' : '' }}>ข้อบกพร่อง</option>
                                                    <option value="2" {{ ($item->type ?? '') == '2' ? 'selected' : '' }}>ข้อสังเกต</option>
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
                    <div class="row status_bug_report">
                        <div class="col-md-12   ">
                            <div id="other_attach">
                                <div class="form-group other_attach_item">
                                    <div class="col-md-2 text-right">
                                        <label for="#" class="label_other_attach text-right ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ไฟล์แนบ : </label>
                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                {!! Form::file('attachs[]', null) !!}
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                        </div>
                                        {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="col-md-2 text-left">
                                        <button type="button" class="btn btn-sm btn-success attach-add div_hide" id="attach-add">
                                            <i class="icon-plus"></i>&nbsp;เพิ่ม
                                        </button>
                                        <div class="button_remove"></div>
                                    </div>
                                </div>
                            </div>
                    
                    
                            <div class="col-md-12 ">
                                <div class="col-md-2 text-right"></div>
                                <div class="col-md-6">
                                    @if(!is_null($assessment) && (count($assessment->FileAttachAssessment4Many) > 0 ) )
                                        @foreach($assessment->FileAttachAssessment4Many as  $key => $item)
                                        <p id="remove_attach_all{{$item->id}}">
                                            @if( $item->file  !='' && HP::checkFileStorage($attach_path. $item->file ))
                                                <a href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                                                        title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                                                    {!! HP::FileExtension($item->file)  ?? '' !!}
                                                </a>
                                            @endif
                                            <button class="btn btn-danger btn-xs deleteFlie div_hide"
                                                type="button" onclick="deleteFlieAttachAll({{$item->id}})">
                                                <i class="icon-close"></i>
                                            </button>   
                                        </p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12   ">
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    <input type="hidden" name="previousUrl" id="previousUrl" value="{{ $previousUrl ?? null}}">
                                    <div  class="status_bug_report"> 
                                        <label>{!! Form::checkbox('main_state', '2', false, ['class'=>'check','data-checkbox'=>"icheckbox_flat-red"]) !!} 
                                            &nbsp;ปิดผลการตรวจประเมิน&nbsp;
                                        </label>
                                    </div> 
                            
                                    <div id="degree_btn"></div>
                                        <input type="hidden" id="submit_type" name="submit_type">
                                        <div id="degree_btn"></div>
                                        <button class="btn btn-success " type="button"  id="confirm" onclick="submit_form('1','confirm');return false;" style="visibility: hidden">
                                            <i class="fa fa-save"></i><span id="confirm_text" style="padding-left:5px">ยืนยัน</span>
                                        </button>
                                        <button class="btn btn-primary " type="button" id="save"  onclick="submit_form('1','save');return false;">
                                            <i class="fa fa-paper-plane"></i><span id="save_text" style="padding-left:5px">บันทึก</span> 
                                        </button>
                                    
                                        <a class="btn btn-default" href="{{url('/certify/save_assessment')}}"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                </div>
                            </div>
                        </div>
                
                    </div>
                
                
                    </div>    
                </div>
            </div>
        </div>

        </form>
    </div>    

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
        var auditorId = @json($auditorId);
        // console.log(auditorId)
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
        });
 
// console.log(auditorId);
    function  submit_form(degree,submit_type){ 
        $('#submit_type').val(submit_type);
        var bug_report = $("input[name=bug_report]:checked").val(); 
        var vehicle =  $("input[name=vehicle]:checked").val();
        var main_state =  $("input[name=main_state]:checked").val();

        
        if(bug_report == 2)
        {
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
                            $('#form_ib_assessment').submit();
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
                            $('#form_ib_assessment').submit();
                        }
                })
            }else{
                // console.log(submit_type)
                let title = '';
                let l = '';
                if(main_state == 2){
                    title =  'ยืนยันปิดผลการตรวจประเมิน !';
                    l = 8;
                }else{
            

                    title = 'ยืนยันทำรายงานข้อบกพร่อง'

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
                            $('#form_ib_assessment').submit();
                        }
                })
            }   
    
        } 
    }

    function loadCertiIb()
        {
            $.ajax({
                    url:'{{ url('certify/save_assessment-ib/certi_ib') }}/' + auditorId
                }).done(function( object ) {
                    
                        if(object.certi_ib != '-'){ 
                            let certi_ib = object.certi_ib;
                            $('#app_no').val(certi_ib.app_no); 
                            $('#applicant_name').val(certi_ib.name); 
                            $('#laboratory_name').val(certi_ib.name_standard);
                            $('#Tis').html(certi_ib.tis); 
                            $('#app_certi_ib_id').val(certi_ib.app_certi_ib_id); 
                        }else{
                            $('#app_no').val(''); 
                            $('#applicant_name').val(''); 
                            $('#laboratory_name').val(''); 
                            $('#Tis').html(''); 
                            $('#app_certi_ib_id').val(''); 
                        }

                        // if(object.auditors_list != '-'){ 
                        //     let auditors = object.auditors_list;
                        //     $.each(auditors, function( index, data ) {
                        //          $('select.found').append('<option value="'+data.user_id+'">'+ data.temp_users+'</option>');
                        //     });
                        // }
                });
        }

        $(document).ready(function () {
               $('#form_ib_assessment').parsley().on('field:validated', function() {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                })  .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                            image       : "",
                            text  : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                        return true; // Don't submit form for this demo
               });
 

     
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
            orientation: 'bottom'
        });
        

        loadCertiIb();


         
        $('#show-modal-email-to-expert').on('click', function() {
            $('#modal-email-to-expert').modal('show');
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


        $("#auditors_id").change(function(){
            
            // $('select.found').html('<option value="">-เลือกผู้พบ-</option>').select2();
            if($(this).val()!=""){
                $.ajax({
                    url:'{{ url('certify/save_assessment-ib/certi_ib') }}/' + $(this).val()
                }).done(function( object ) {
                    
                        if(object.certi_ib != '-'){ 
                            let certi_ib = object.certi_ib;
                            $('#applicant_name').val(certi_ib.name); 
                            $('#laboratory_name').val(certi_ib.name_standard);
                            $('#Tis').html(certi_ib.tis); 
                            $('#app_certi_ib_id').val(certi_ib.app_certi_ib_id); 
                        }else{
                            $('#applicant_name').val(''); 
                            $('#laboratory_name').val(''); 
                            $('#Tis').html(''); 
                            $('#app_certi_ib_id').val(''); 
                        }

                        // if(object.auditors_list != '-'){ 
                        //     let auditors = object.auditors_list;
                        //     $.each(auditors, function( index, data ) {
                        //          $('select.found').append('<option value="'+data.user_id+'">'+ data.temp_users+'</option>');
                        //     });
                        // }
                });
            }else{
                $('#applicant_name').val(''); 
                $('#laboratory_name').val(''); 
                $('#app_certi_ib_id').val(''); 
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
                    // $('#box-required').find('.input_required').prop('required', true);
                    $('#div_file_scope').hide(400); 
                    $('#checkbox_document').hide(400); 
                    $('.file_scope_required').prop('required', false);
                    $('#confirm').css('visibility', 'visible');
                    $('#save_text').html('ฉบับร่าง');
                } else{
                    $('.status_bug_report').hide(400);
                    $('#submit_draft').hide(400); 
                    // $('#box-required').find('.input_required').prop('required', false);
                    $('#div_file_scope').show(200);
               
                    $('#checkbox_document').show(200);  
                    $('.file_scope_required').prop('required', true);
                    $('#confirm').css('visibility', 'hidden');
                    $('#save_text').html('บันทึก');
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
                        url: "{!! url('/certify/check_certificate-ib/delete_file') !!}"  + "/" + id
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
                        url: "{!! url('/certify/check_certificate-ib/delete_file') !!}"  + "/" + id
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
                        url: "{!! url('/certify/check_certificate-ib/delete_file') !!}"  + "/" + id
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

@endsection


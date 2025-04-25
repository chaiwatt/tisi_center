

  
  <!-- Modal -->
   <div class="modal fade " id="exampleModalReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalReportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalReportLabel">สรุปรายงานและเสนออนุกรรมการฯ

            @if ($applicant->require_scope_update == 1)
  
                <span class="text-warning">(อยู่ระหว่างขอให้แก้ไขขอบข่าย)</span>
            @endif


        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div> 
 
            {!! Form::open(['url' => 'certify/check_certificate/update/report_assessments/'.$report->id, 'method' => 'POST', 'class' => 'form-horizontal report_form  ', 'files' => true]) !!}
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group {{ $errors->has('meet_date') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('meet_date', '<span class="text-danger">*</span> วันที่ประชุม'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                    <div class="col-md-4 text-left">
                        <div class="input-group">
                            {!! Form::text('meet_date',  !empty($report->meet_date) ? HP::revertDate($report->meet_date->format("Y-m-d"),true) :  null,  ['class' => 'form-control mydatepicker check_readonly','required'=>true,'placeholder'=>'dd/mm/yyyy'])!!}
                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                        </div>
                        {!! $errors->first('meet_date', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
              </div>
           </div>
           <div class="row">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> มติคณะอนุกรรมการ'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                   <div class="col-md-7 text-left">
                    <label>{!! Form::radio('report_status', '1', isset($report) && !empty($report->status==2) ? false : true, ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!} &nbsp; เห็นชอบ &nbsp;</label>
                    <label>{!! Form::radio('report_status', '2', isset($report)  &&  !empty($report->status==2) ? true : false , ['class'=>'check check_readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp; ไม่เห็นชอบ &nbsp;</label>
                  </div>
               </div>
             </div>
          </div>

           <div class="row">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('desc') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('desc', 'รายละเอียด'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                   <div class="col-md-6 text-left">
                    {!! Form::textarea('desc', !empty($report->desc) ?$report->desc :  null ,  ['class' => 'form-control report_desc','cols'=>'30','rows'=>'5'])!!}
                       {!! $errors->first('desc', '<p class="help-block">:message</p>') !!}
                   </div>
               </div>
             </div>
          </div>
        
          <div class="row " id="div_file_loa">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('file_loa') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('file_loa', '<span class="text-danger">*</span> ขอบข่ายที่ได้รับการเห็นชอบ'.':<br/><span class="text-danger" style="font-size: 10px;">(.pdf)</span>', ['class' => 'col-md-4 control-label text-right','style'=>"line-height: 16px;"])) !!}
                   <div class="col-md-7 text-left ">

                    
                    {{-- @php
                        dd($report);
                    @endphp --}}
           
                    @if(isset($report) && !is_null($report->file_loa) && $report->file_loa != '')
                            <p class="text-left">
                                <a href="{{url('certify/check/file_client/'.$report->file_loa.'/'.( !empty($report->file_loa_client_name) ? $report->file_loa_client_name : basename($report->file_loa)  ))}}" target="_blank">
                                    {!! HP::FileExtension($report->file_loa)  ?? '' !!} {{basename($report->file_loa_client_name)}}
                                </a>
                                @if ($applicant->require_scope_update != 1)

                                @if ($report->status == null)
                                    <button type="button" class="btn btn-sm btn-info attach-add" id="button_show_request_edit_scope_modal">
                                        <i class="fa fa-pencil-square-o"></i>&nbsp;ขอให้แก้ไข
                                    </button>
                                @endif

                                   
                                @else
                                <span class="text-warning">(อยู่ระหว่างขอให้แก้ไขขอบข่าย)</span>
                                @endif
                                
                            </p> 
                          
            
                    @else 
                            {{-- <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
            
                                    <input type="file" name="file_loa"  id="file_loa" >
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div> --}}
                    @endif
                   </div>
                 

                    
               </div>
              
             </div>
          </div>

           <div class="row">
            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('issue_date') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('', '<span class="text-danger">*</span> ออกให้ตั้งแต่วันที่ :', ['class' => 'col-md-4 control-label text-right'])) !!}
                    <div class="col-md-6">
                        <div class="input-daterange input-group date-range">
                            {!! Form::text('start_date', !empty($report->start_date) ? HP::revertDate($report->start_date,true) : null, ['class' => 'form-control ', 'required' => true , 'placeholder'=>'dd/mm/yyyy']) !!}
                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                            {!! Form::text('end_date', !empty($report->end_date) ? HP::revertDate($report->end_date,true) : null, ['class' => 'form-control ', 'required' => true, 'placeholder'=>'dd/mm/yyyy']) !!}
                        </div>
                        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

           <div class="row ">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                   {!! Form::label('save_date', 'หลักฐานอื่นๆ'.':',['class' => 'col-md-4 control-label  text-right']) !!}
                   <div class="col-md-7 text-left data_hide">
                       <button type="button" class="btn btn-sm btn-success" id="attach-add">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                      </button>
                   </div>
               </div>
             </div>
          </div>
          @if(isset($report) && count($report->files) > 0)
          <div class="row" id="div_report_file">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                   {!! Form::label('', '', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                   <div class="col-md-7 text-left">
                      @foreach ($report->files as $report_file)
                        <p class="text-left">
                            {{ @$report_file->file_desc }}   
                            <a href="{{url('certify/check/file_client/'.$report_file->file.'/'.( !empty($report_file->file_client_name) ? $report_file->file_client_name : basename($report_file->file)  ))}}" target="_blank">
                                {!! HP::FileExtension($report_file->file)  ?? '' !!}
                            </a>
                             @if(isset($report) && $report->status == 2)
                            <button class="btn btn-danger btn-xs  deleteFlie{{$report_file->id}}" type="button" onclick="deleteFlie({{ $report_file->id }})">
                                <i class="icon-close"></i>
                            </button>
                            @endif
                        </p> 
                      @endforeach
                   </div>
               </div>
             </div>
          </div>
          @endif
          <div class="row data_hide">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                   {!! Form::label('', '', ['class' => 'col-md-2 control-label label-filter text-right']) !!}
                   <div class="col-md-9 text-left">
                    <div id="attach-box">
                        <div class="form-group other_attach_item">
                            <div class="col-md-5">
                                {!! Form::text('file_desc[]', null, ['class' => 'form-control m-t-10', 'placeholder' => 'ชื่อไฟล์']) !!}
                            </div>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('file[]', null) !!}
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                  {{-- {{$applicant}} --}}
              
                            <div class="col-md-1 text-left m-t-15" style="margin-top: 3px">
                                <button class="btn btn-danger btn-sm attach-remove" type="button" >
                                    <i class="icon-close"></i>
                                </button>
                            </div>
                         </div>
                    </div>
                   </div>
               </div>
             </div>
          </div>
    
          
        </div>

        @if ($applicant->require_scope_update != 1)
            <div class="modal-footer data_hide">
                <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึก</button>
            </div>
        @endif
    

        {!! Form::close() !!}
    </div>
    </div>
</div>

<div class="modal fade" id="modal-request-edit-scope">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">รายละเอียด</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-left">
                <input type="text" name="" id="app_id" value="{{$cc->app_certi_lab_id}}">
                    {{-- <input type="text" name="" id="notice_id" value="{{$find_notice->id}}"> --}}
                <div class="row">
                    <div class="col-md-12 form-group" >
                        <label for="edit_detail">โปรดระบุเหตุผล:</label>
                        <textarea name="edit_detail" id="edit_detail" class="form-control" row="5"></textarea>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <button type="button" class="btn btn-success pull-right " id="button_request_edit_scope">
                            <span aria-hidden="true">บันทึก</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
 
<script>
    $(document).ready(function () {
    
    $('.report_form').parsley().on('field:validated', function() {
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
 
        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
            $('.other_attach_item:first').clone().appendTo('#attach-box');

            $('.other_attach_item:last').find('input').val('');
            $('.other_attach_item:last').find('a.fileinput-exists').click();
            $('.other_attach_item:last').find('a.view-attach').remove();

            ShowHideRemoveBtn94();

        });

        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
            $(this).parent().parent().remove();
            ShowHideRemoveBtn94();
        });

        ShowHideRemoveBtn94();
    });

    function ShowHideRemoveBtn94() { //ซ่อน-แสดงปุ่มลบ

        if ($('.other_attach_item').length > 1) {
            $('.attach-remove').show();
        } else {
            $('.attach-remove').hide();
        }
    }
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {

            AttachFileLoa();



        $('#button_show_request_edit_scope_modal').on('click', function() {
            $('#modal-request-edit-scope').modal('show');
                // แสดง modal ด้วย id ของมัน
                $('#edit_detail').css({
                    'width': '100%',
                    'height': '150px',
                    'padding': '5px',
                    'box-sizing': 'border-box !important',
                    'border': '1px solid #ccc !important',
                    'border-top': '1px solid #ccc !important',
                    'border-bottom': '1px solid #ccc !important',
                    'border-radius': '4px !important',
                    'background-color': '#e6f7ff', // เปลี่ยนสีพื้นหลังที่นี่
                    'font-size': '16px',
                    'resize': 'none'
            });

            $('#edit_detail').val(''); // โฟกัสไปที่ textarea

        });


                      
      $(document).on('click', '#button_request_edit_scope', function(e) {
            e.preventDefault();

            // รับค่าจากฟอร์ม
            const _token = $('input[name="_token"]').val();
            var app_id = $('#app_id').val();
            var notice_id = $('#notice_id').val();
            var message = $('#edit_detail').val();

            if (message == "") {
                alert("กรุณากรอกเหตผล");
                return;
            }

            // สร้าง overlay
            showOverlay();

            // เรียก AJAX
            $.ajax({
                url: "{{route('save_assessment.api.request_edit_scope')}}",
                method: "POST",
                data: {
                    _token: _token,
                    app_id: app_id,
                    notice_id:notice_id,
                    message: message,
                },
                success: function(result) {
                    console.log(result);
                    $('#modal-request-edit-scope').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
                },
                complete: function() {
                    // ลบ overlay เมื่อคำขอเสร็จสิ้น
                    hideOverlay();
                }
            });
        });


        function showOverlay() {
            // ตรวจสอบว่ามี overlay อยู่หรือยัง
            if ($('#loading-overlay').length === 0) {
                $('body').append(`
                    <div id="loading-overlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(255, 255, 255, 0.4);
                        z-index: 1050;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: black;
                        font-size: 65px;
                        font-family: 'Kanit', sans-serif;
                    ">
                        กำลังบันทึก กรุณารอสักครู่...
                    </div>
                `);
            }
        }


        // ฟังก์ชันสำหรับลบ overlay
        function hideOverlay() {
            $('#loading-overlay').remove();
        }

     });

            //  Attach File
            function  AttachFileLoa(){
            $('#file_loa').change( function () {
                    var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf ',
                        '',
                        'info'
                        )
                    this.value = '';
                    return false;
                    }
                });
        }

        function  deleteFlie(id){
        
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
                            url: "{!! url('/certify/check_certificate/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                           console.log(object);
                            if(object == 'true'){
                                $('#div_report_file').find('.deleteFlie'+id).parent().remove();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
    }
    </script>
@endpush

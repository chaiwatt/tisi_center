  
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalReportLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalReportLabel">สรุปรายงานและเสนอคณะกรรมการฯ  

            @if ($report->CertiCBCostTo->require_scope_update == "1")
            
            <span class="text-danger">อยู่ระหว่างการแก้ไขขอบข่าย</span>
        @endif
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div>
        {!! Form::open(['url' => 'certify/check_certificate-cb/report/'.$report->id, 
                        'class' => 'form-horizontal', 
                        'files' => true,
                        'id'=>"form_report"]) 
        !!}
        <div class="modal-body">
           <div class="row">
             <div class="col-sm-12">
                <div class="form-group {{ $errors->has('meet_date') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('meet_date', '<span class="text-danger">*</span> วันที่ประชุม'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                    <div class="col-md-4 text-left">
                        <div class="input-group">
                            {!! Form::text('report_date',  !empty($report->report_date)  ? HP::revertDate($report->report_date,true) :  null,  ['class' => 'form-control mydatepicker check_readonly','required'=>true,'placeholder'=>'dd/mm/yyyy'])!!}
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
                   {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> มติคณะกรรมการ'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                   <div class="col-md-7 text-left">
                    <label>{!! Form::radio('report_status', '1',  !empty($report->report_status==2) ? false : true, ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!} &nbsp; เห็นชอบ &nbsp;</label>
                    <label>{!! Form::radio('report_status', '2',  !empty($report->report_status==2) ? true : false , ['class'=>'check check_readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp; ไม่เห็นชอบ &nbsp;</label>
                  </div>
               </div>
             </div>
          </div>

           <div class="row">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('details', 'รายละเอียด'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                   <div class="col-md-6 text-left">
                    {!! Form::textarea('details',  $report->details  ??  null ,  ['class' => 'form-control check_readonly','cols'=>'30','rows'=>'5'])!!}
                       {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
                   </div>
               </div>
             </div>
          </div>
        
          <div class="row " id="div_file_loa">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('file_loa') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('file_loa', '<span class="text-danger">*</span> ขอบข่ายที่ได้รับการเห็นชอบ'.':<br/><span class="text-danger" style="font-size: 10px;">(.pdf)</span>', ['class' => 'col-md-4 control-label text-right','style'=>"line-height: 16px;"])) !!}
                   <div class="col-md-7 text-left ">
           
                    @if(isset($report) && !is_null($report->FileAttachReport1To))
                            <p class="text-left">
                                <a href="{{url('certify/check/file_cb_client/'.$report->FileAttachReport1To->file.'/'.( !empty($report->FileAttachReport1To->file_client_name) ? $report->FileAttachReport1To->file_client_name :  basename($report->FileAttachReport1To->file)  ))}}" 
                                    title="{{  !empty($report->FileAttachReport1To->file_client_name) ? $report->FileAttachReport1To->file_client_name : basename($report->FileAttachReport1To->file) }}" target="_blank">
                                    {!! HP::FileExtension($report->FileAttachReport1To->file)  ?? '' !!}
                                </a>
                                @if ($report->CertiCBCostTo->require_scope_update != "1")
                                    <button type="button" class="btn btn-sm btn-success" id="ask_to_edit_scope">
                                        ขอให้แก้ไข
                                    </button>
                                @endif
                               
                            </p> 
                    @else 
                            <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="file_loa"  id="file_loa" class="file_loa check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
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
                            {!! Form::text('start_date', !empty($report->start_date) ? HP::revertDate($report->start_date,true) : null, ['class' => 'form-control mydatepicker', 'required' => true , 'placeholder'=>'dd/mm/yyyy']) !!}
                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                            {!! Form::text('end_date', !empty($report->end_date) ? HP::revertDate($report->end_date,true) : null, ['class' => 'form-control mydatepicker', 'required' => true, 'placeholder'=>'dd/mm/yyyy']) !!}
                        </div>
                        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('issue_date') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('issue_date', '<span class="text-danger">*</span> ออกให้ ณ วันที่ :', ['class' => 'col-md-4 control-label text-right'])) !!}
                    <div class="col-md-4">
                        <div class="input-group">
                            {!! Form::text('issue_date', !empty($report->issue_date) ? HP::revertDate($report->issue_date,true) : null, ['class' => 'form-control mydatepicker', 'required'=>true, 'placeholder'=>'dd/mm/yyyy'])!!}
                            {{-- <span class="input-group-addon"><i class="icon-calender"></i></span> --}}
                        </div>
                        {!! $errors->first('issue_date', '<p class="help-block">:message</p>') !!}
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
          @if(isset($report) && count($report->FileAttachReport2Many) > 0)
          <div class="row" id="div_report_file">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                   {!! Form::label('', '', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                   <div class="col-md-7 text-left">
                      @foreach ($report->FileAttachReport2Many as $item)
                            <p id="remove_attach_all{{$item->id}}">
                                 {{ @$item->file_desc }}
                                 <a href="{{url('certify/check/file_cb_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file)  ))}}" 
                                    title="{{  !empty($item->file_client_name) ? $item->file_client_name : basename($item->file) }}" target="_blank">
                                    {!! HP::FileExtension($item->file)  ?? '' !!}
                                </a> 
                                @if($report->report_status == 2)
                                <button class="btn btn-danger btn-xs deleteFlie div_hide"
                                    type="button" onclick="deleteFlieAttachAll({{$item->id}})">
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
                                        {{-- {!! Form::file('file[]', null) !!} --}}
                                        <input type="file" name="file[]"    class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                  
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
        <div class="modal-footer data_hide">
            
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            @if ($report->CertiCBCostTo->require_scope_update != "1")
                <button type="submit" class="btn btn-primary" onclick="submit_form_report();return false">บันทึก</button>
            @endif
            
        </div>
        {!! Form::close() !!}
    </div>
    </div>
</div>

@include ('certify/cb/check_certificate_cb/modal.modal_review_edit_scope',['report' => $report ])

@push('js')
 
<script>
    function submit_form_report() {
            Swal.fire({
                    title: 'ยืนยันทำรายการ !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            $('#form_report').submit();
                        }
                    })
        }
    $(document).ready(function () {
        check_max_size_file();
               // สรุปรายงานและเสนออนุกรรมการฯ
         $('#form_report').parsley().on('field:validated', function() {
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
        var data_hide = '{{  !empty($report) &&  ($report->report_status == 1) ? 1 : null  }}';

           if(data_hide == 1){
                $('.data_hide').hide ();
                $('.check_readonly').prop('disabled', true);
                $('.check_readonly').parent().removeClass('disabled');
                $('.check_readonly').parent().css('margin-top', '8px');
            }
            
        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
            $('.other_attach_item:first').clone().appendTo('#attach-box');

            $('.other_attach_item:last').find('input').val('');
            $('.other_attach_item:last').find('a.fileinput-exists').click();
            $('.other_attach_item:last').find('a.view-attach').remove();

            ShowHideRemoveBtn94();
            check_max_size_file();
        });

        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
            $(this).parent().parent().remove();
            ShowHideRemoveBtn94();
        });

        ShowHideRemoveBtn94();

        
        $('#ask_to_edit_scope').click(function(event) {
            $('#exampleModaEditCbScope').modal('show');
        });

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
                            url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
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

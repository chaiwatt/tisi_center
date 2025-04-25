  
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalReviewResult" tabindex="-1" role="dialog" aria-labelledby="exampleModalReviewResultLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalReviewResultLabel">มติคณะทบทวน
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div>
        {{-- {!! Form::open(['url' => 'certify/check_certificate-cb/report/'.$report->id, 
                        'class' => 'form-horizontal', 
                        'files' => true,
                        'id'=>"form_save_review"]) 
        !!}
            <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> มติคณะทบทวน'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                    <div class="col-md-7 text-left">
                        <label>{!! Form::radio('report_status', '1',  !empty($report->report_status==1) ? false : true, ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!} &nbsp; เห็นชอบ &nbsp;</label>
                        <label>{!! Form::radio('report_status', '2',  !empty($report->report_status==1) ? true : false , ['class'=>'check check_readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp; ไม่เห็นชอบ &nbsp;</label>
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
            </div>
            <div class="modal-footer data_hide">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary" onclick="submit_form_save_review();return false">บันทึก</button>
            </div>
        {!! Form::close() !!} --}}

        <form action="{{ url('certify/check_certificate-cb/save-review/' . $report->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data" id="form_save_review">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status" class="col-md-4 control-label text-right"><span class="text-danger">*</span> มติคณะทบทวน:</label>
                            <div class="col-md-7 text-left">
                                <label>
                                    <input type="radio" name="report_status" value="1" class="check" data-radio="iradio_square-green" 
                                    {{ !empty($report->report_status==1) ? '' : 'checked' }}> เห็นชอบ
                                </label>
                                <label>
                                    <input type="radio" name="report_status" value="2" class="check check_readonly" data-radio="iradio_square-red"
                                    {{ !empty($report->report_status==1) ? 'checked' : '' }}> ไม่เห็นชอบ
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                            <label for="details" class="col-md-4 control-label text-right">รายละเอียด:</label>
                            <div class="col-md-6 text-left">
                                <textarea name="details" class="form-control check_readonly" cols="30" rows="5"></textarea>
                                @if($errors->has('details'))
                                    <p class="help-block">{{ $errors->first('details') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer data_hide">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary" onclick="submit_form_save_review();return false">บันทึก</button>
            </div>
        </form>
    </div>
    </div>
</div>



@push('js')
 
<script>
    function submit_form_save_review() {
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
                            $('#form_save_review').submit();
                        }
                    })
        }
    $(document).ready(function () {
        check_max_size_file();
               // สรุปรายงานและเสนออนุกรรมการฯ
         $('#form_save_review').parsley().on('field:validated', function() {
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

        // ShowHideRemoveBtn94();

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

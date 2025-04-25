<!-- Modal -->
<div class="modal fade" id="ReviewModal" tabindex="-1" role="dialog" aria-labelledby="ReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" > 
                แต่งตั้งคณะทบทวนฯ
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
           </h4>
        </div>
  @if(!is_null($review))
   {!! Form::open(['url' => 'certify/check_certificate-ib/update_review/'.$review->id,
    'class' => 'form-horizontal', 
    'files' => true,
    'id'=>"review_form"]) !!}
        <div class="modal-body text-center">
            <div class="row form-group">
                <div class=" {{ $errors->has('files') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('files', 'คณะผู้ตรวจประเมิน :', ['class' => 'col-md-5 control-label text-right'])) !!}
                    <div class="col-md-7 text-left">
                        @if (!is_null($review->FileReview1) &&  $review->FileReview1 != '')
                            <p id="deleteFlieAttach">
                                <a href="{{ url('certify/check/files_ib') . '/' . $review->FileReview1->file }}" class=" control-label"  target="_blank" title="{{  basename($review->FileReview1->file) }}">
                                    {!! HP::FileExtension($review->FileReview1->file)  ?? '' !!}
                                </a>     
                                <button class="btn btn-danger btn-xs deleteFlie  {{$review->review == 2 ? 'hide' : '' }} " type="button" onclick="deleteFlieAttach({{ $review->FileReview1->id}})">
                                    <i class="icon-close"></i>
                                </button>   
                            </p> 
                            <div id="AddFilesAttach"></div>           
                        @else
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="evidence" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                         @endif
                    </div>
                </div>
           </div>
           <div class="row form-group">
            <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('attach', 'ผลการตรวจคณะผู้ตรวจประเมิน :', ['class' => 'col-md-5 control-label text-right'])) !!}
                <div class="col-md-7 text-left">
                          @if (!is_null($review->FileReview2) &&  $review->FileReview2 != '')
                            <p id="deleteAttach">
                                <a href="{{ url('certify/check/files_ib') . '/' . $review->FileReview2->file }}" class=" control-label"  target="_blank" title="{{  basename($review->FileReview2->file) }}">
                                    {!! HP::FileExtension($review->FileReview2->file)  ?? '' !!}
                                </a>     
                                <button class="btn btn-danger btn-xs deleteFlie {{$review->review == 2 ? 'hide' : '' }} " type="button" onclick="deleteAttach({{ $review->FileReview2->id}})">
                                    <i class="icon-close"></i>
                                </button>   
                            </p> 
                            <div id="AddAttach"></div>           
                        @else
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attach" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                         @endif
                </div>
            </div>
            </div>
            <div class="checkbox checkbox-success  label-height">
                <input id="review" class="review" type="checkbox" name="review" 
                       value="1"  {{ (isset($review) && $review->review == 2) ? 'checked': '' }}>
                <label for="review  label-height"> &nbsp;  ยืนยันแต่งตั้งคณะทบทวนฯ
               </label>
            </div>
        </div>
   
        @if($certi_ib->review  == 1)
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary " data-dismiss="modal">ยกเลิก</button>
            <button   type="submit" class="btn btn-primary " onclick="submit_form_review();return false">บันทึก</button>
        </div>
        @endif

  {!! Form::close() !!}
  @endif
      </div>
    </div>
  </div>

  
@push('js')
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
<script>
       function submit_form_review() {
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
                        // Text
                        $.LoadingOverlay("show", {
                             image       : "",
                             text        : "กำลังบันทึก กรุณารอสักครู่..."
                        });
                        $('#review_form').submit();
                    }
                })
        }

        
    jQuery(document).ready(function() {
        check_max_size_file();
        var review = '{{  !empty($certi_ib->review)  ? $certi_ib->review : null  }}';
         if(review == 2){
                $('#review').prop('disabled', true);
          }
     });

   function  deleteFlieAttach(id){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="evidence" >';
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
                            url: "{!! url('/certify/estimated_cost-ib/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#deleteFlieAttach').remove();
                               $("#AddFilesAttach").append(html);
                               check_max_size_file();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
         }
         function  deleteAttach(id){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="attach" >';
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
                            url: "{!! url('/certify/estimated_cost-ib/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#deleteAttach').remove();
                               $("#AddAttach").append(html);
                               check_max_size_file();
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
         }
</script>
@endpush
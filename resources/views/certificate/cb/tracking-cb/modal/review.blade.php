
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
     <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css?20200630')}}" rel="stylesheet" />
@endpush

{!! Form::open(['url' => 'certificate/tracking-cb/update_review/'.$review->id,    'method' => 'POST', 'class' => 'form-horizontal  ','id'=>'form_review', 'files' => true]) !!}

<div class="modal fade text-left" id="review" tabindex="-1" role="dialog" aria-labelledby="addBrand">
          <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel1">แต่งตั้งคณะทบทวนฯ
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </h4>
                  </div>
 <div class="modal-body"> 

<div class="row">
    <div class="col-md-12">

<div class="form-group {{ $errors->has('evidence') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('evidence', ' คณะผู้ตรวจประเมิน'.':', ['class' => 'col-md-5 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
           @if(isset($review) && !is_null($review->FileAttachEvidenceTo))
                      <p id="deleteFlieAttach">
                            <a href="{{url('funtions/get-view/'.$review->FileAttachEvidenceTo->url.'/'.( !empty($review->FileAttachEvidenceTo->filename) ? $review->FileAttachEvidenceTo->filename :  basename($review->FileAttachEvidenceTo->url)  ))}}" 
                                title="{{  !empty($review->FileAttachEvidenceTo->filename) ? $review->FileAttachEvidenceTo->filename : basename($review->FileAttachEvidenceTo->url) }}" target="_blank">
                                {!! HP::FileExtension($review->FileAttachEvidenceTo->url)  ?? '' !!}
                            </a> 
                            <button class="btn btn-danger btn-xs deleteFlie {{$review->review == 1 ? 'hide' : '' }} " type="button" onclick="deleteFlieAttach({{ $review->FileAttachEvidenceTo->id}})">
                              <i class="icon-close"></i>
                            </button>   
                      </p> 
                      <div id="AddFilesAttach"></div> 
            @else 
                    <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="evidence"  id="evidence" class="evidence check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
           @endif
    </div>
</div>
<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('attach', 'ผลการตรวจคณะผู้ตรวจประเมิน'.':', ['class' => 'col-md-5 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
           @if(isset($review) && !is_null($review->FileAttachFilesTo))
                       <p id="deleteAttach">
                            <a href="{{url('funtions/get-view/'.$review->FileAttachFilesTo->url.'/'.( !empty($review->FileAttachFilesTo->filename) ? $review->FileAttachFilesTo->filename :  basename($review->FileAttachFilesTo->url)  ))}}" 
                                title="{{  !empty($review->FileAttachFilesTo->filename) ? $review->FileAttachFilesTo->filename : basename($review->FileAttachFilesTo->url) }}" target="_blank">
                                {!! HP::FileExtension($review->FileAttachFilesTo->url)  ?? '' !!}
                            </a> 
                            <button class="btn btn-danger btn-xs deleteFlie {{$review->review == 1 ? 'hide' : '' }} " type="button" onclick="deleteAttach({{ $review->FileAttachFilesTo->id}})">
                              <i class="icon-close"></i>
                            </button>   
                      </p> 
                      <div id="AddAttach"></div>   
            @else 
                    <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="attach"  id="attach" class="attach check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
           @endif
    </div>
</div>
<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
          {!! HTML::decode(Form::label('', '', ['class' => 'col-md-5 control-label text-right'])) !!}
          <div class="col-md-7 text-left">
                    <label>{!! Form::checkbox('review', '1',  (isset($review) && $review->review == 1) || (isset($review) && is_null($review->review))  ?  true :  false, ['class'=>'check checkbox-review','data-checkbox'=>"icheckbox_flat-green"]) !!} 
                              &nbsp;ยืนยันแต่งตั้งคณะทบทวนฯ&nbsp;
                    </label>
          </div>
 </div>

    </div> 
</div>

</div>

@if ($certi->status_id == 6)
<input type="hidden" name="previousUrl" id="previousUrl" value="{{  app('url')->previous()  }}">
<div class="modal-footer">
    <button type="submit" class="btn btn-success" onclick="submit_form();return false">ยืนยัน</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
</div> 
@endif


        </div>
    </div>
</div>
{!! Form::close() !!}
      
@push('js')
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
     <!-- ===== PARSLEY JS Validation ===== -->
     <script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
     <script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>
     <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
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
                                 $('#form_review').submit();
                             }
                         })
             }
         $(document).ready(function () {
             check_max_size_file();
                    // สรุปรายงานและเสนออนุกรรมการฯ
              $('#form_review').parsley().on('field:validated', function() {
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
          });
     </script> 
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
           var review = '{{  !empty($review->review)  ? $review->review : null  }}';
        
            if(review == 1){
                     $('.checkbox-review').prop('disabled', true);
                     $('.checkbox-review').parent().removeClass('disabled');
 
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
                               url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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
                               url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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
    
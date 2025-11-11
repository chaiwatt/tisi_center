

  
  <!-- Modal -->
   <div class="modal fade " id="exampleModalScopeReview" tabindex="-1" role="dialog" aria-labelledby="exampleModalScopeReviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalScopeReviewLabel">ขอบข่ายการรับรอง
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
         </h4>
        </div> 
 
       
        {{-- <div class="modal-body">
            <div class="row " id="div_file_loa">
                <div class="col-sm-12">
                <div class="form-group {{ $errors->has('file_loa') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('file_loa', '<span class="text-danger">*</span> ขอบข่ายที่ได้รับการเห็นชอบ'.':<br/><span class="text-danger" style="font-size: 10px;">(.pdf)</span>', ['class' => 'col-md-4 control-label text-right','style'=>"line-height: 16px;"])) !!}
                    <div class="col-md-7 text-left ">
                        @if(isset($report) && !is_null($report->file_loa) && $report->file_loa != '')
                            <p class="text-left">
                                <a href="{{url('certify/check/file_client/'.$report->file_loa.'/'.( !empty($report->file_loa_client_name) ? $report->file_loa_client_name : basename($report->file_loa)  ))}}" target="_blank">
                                    {!! HP::FileExtension($report->file_loa)  ?? '' !!} {{basename($report->file_loa_client_name)}}
                                </a>
                            </p> 
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </div> --}}

        <div class="modal-body">
            <div class="row " >
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('file_loa') ? 'has-error' : ''}}">
                        
                        <label for="file_loa" class="col-md-4 control-label text-right" style="line-height: 16px;">
                            <span class="text-danger">*</span> ขอบข่ายที่ได้รับการเห็นชอบ:<br/>

                        </label>

                        <div class="col-md-7 text-left ">
                            {{-- @if(isset($report) && !is_null($report->file_loa) && $report->file_loa != '')
                                <p class="text-left">
                                    <a href="{{url('certify/check/file_client/'.$report->file_loa.'/'.( !empty($report->file_loa_client_name) ? $report->file_loa_client_name : basename($report->file_loa)  ))}}" target="_blank">
                                        {!! HP::FileExtension($report->file_loa)  ?? '' !!} {{basename($report->file_loa_client_name)}}
                                    </a>
                                </p> 
                            @endif --}}

                            @php
                                $lastFile = is_array($certi_ib->certi_ib) ? end($certi_ib->FileAttach3) : $certi_ib->FileAttach3->last();
                                // dd( $lastFile );
                            @endphp
                                <div class="form-group">
                                    <div class="col-md-12">
                                            <a href="{!! HP::getFileStorage($attach_path.$lastFile->file) !!}" target="_blank" > {!! HP::FileExtension($lastFile->file)  ?? '' !!} {!! $lastFile->file_client_name !!}</a>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
             <div class="row " style="margin-top: 15px" >
                <div class="col-sm-12">
                    <div class="form-group {{ $errors->has('file_loa') ? 'has-error' : ''}}">
                        
                        <label for="file_loa" class="col-md-4 control-label text-right" >
                            <span class="text-danger">*</span> เลือก ผก:<br/>
                        </label>
                        <div class="col-md-7 text-left ">
                            <select class="form-control " id="signer_id">
                                @foreach ($adminGroups as $adminGroup)
                                    <option value="{{$adminGroup->id}}">{{$adminGroup->name}} </option>
                                @endforeach
                            </select>   
                        </div>
                    </div>
                </div>
             </div>
        </div>

       



        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                
                    <label for="status" class="col-md-4 control-label text-right">
                        <span class="text-danger">*</span> เห็นชอบขอบข่าย:
                    </label>
                    
                    <div class="col-md-7 text-left">
                    
                        <label>
                            <input type="radio" name="scope_review_status" value="1" checked="checked" class="check " data-radio="iradio_square-green">
                            &nbsp; เห็นชอบ &nbsp;
                        </label>
                        
                        <label>
                            <input type="radio" name="scope_review_status" value="2" class="check" data-radio="iradio_square-red">
                            &nbsp; ไม่เห็นชอบ &nbsp;
                        </label>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer ">
            <input type="hidden" id="app_certi_lab_id" value="{{ $certi_ib->id ?? null}}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
             @if ($certi_ib->scope_view_status == null)
                <button  class="btn btn-primary" id="button_scope_for_admin_group" >บันทึก</button>
             @endif
        </div>
       
    </div>
    </div>
</div>


@push('js')
 

<script type="text/javascript">
    jQuery(document).ready(function() {

                      
      $(document).on('click', '#button_scope_for_admin_group', function(e) {
            e.preventDefault();

            // รับค่าจากฟอร์ม
            const _token = $('input[name="_token"]').val();
            var app_certi_ib_id = $('#app_certi_ib_id').val();
            var scope_review_status = $('input[name="scope_review_status"]:checked').val();
        // console.log(scope_review_status)
        //     return;


            // สร้าง overlay
            showOverlay();

            // เรียก AJAX
            // $.ajax({
            //     url: "{{route('save_assessment.api.request_admin_group_scope_sign')}}",
            //     method: "POST",
            //     data: {
            //         _token: _token,
            //         app_certi_lab_id: app_certi_lab_id,
            //         signer_id: $('#signer_id').val()
            //     },
            //     success: function(result) {
            //         console.log(result);
            //         $('#exampleModalScopeReview').modal('hide');
            //     },
            //     error: function(xhr, status, error) {
            //         console.error("Error:", error);
            //         alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
            //     },
            //     complete: function() {
            //         // ลบ overlay เมื่อคำขอเสร็จสิ้น
            //         hideOverlay();
            //     }
            // });

            $.ajax({
                url: "{{ route('save_assessment_ib.api.request_admin_group_scope_sign') }}",
                method: "POST",
                data: {
                    _token: _token,
                    app_certi_ib_id: app_certi_ib_id,
                    app_type:"ib",
                    signer_id: $('#signer_id').val(),
                    scope_review_status: scope_review_status
                },
                success: function(result) {
                    $('#exampleModalScopeReview').modal('hide');
        
                    // แสดง Alert สำเร็จ (SweetAlert)
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: result.message, // ดึงข้อความจาก PHP
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // หลังจาก Alert ปิด ให้ reload หน้า
                        location.reload();
                    });
                },
                error: function(jqXHR) {
                    // jqXHR.responseJSON คือ {status: 'error', message: '...'}
                    
                    var errorMessage = 'เกิดข้อผิดพลาด';
                    
                    // ดึงข้อความ error จาก PHP (ถ้ามี)
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }

                    // แสดง Alert Error (SweetAlert)
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: errorMessage // เช่น "คุณไม่มีสิทธิ์ลงนาม"
                    });
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

  
    </script>
@endpush

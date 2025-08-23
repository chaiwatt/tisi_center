

  
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

       

        <div class="modal-footer ">
            <input type="hidden" id="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
             @if ($applicant->scope_view_status == null)
                <button  class="btn btn-primary" id="button_scope_for_admin_group" >นำส่งลงนาม</button>
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
            var app_certi_lab_id = $('#app_certi_lab_id').val();


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
                url: "{{ route('save_assessment.api.request_admin_group_scope_sign') }}",
                method: "POST",
                data: {
                    _token: _token,
                    app_certi_lab_id: app_certi_lab_id,
                    signer_id: $('#signer_id').val()
                },
                success: function(result) {
                    console.log(result);
                    $('#exampleModalScopeReview').modal('hide');
                    
                    // เพิ่มบรรทัดนี้เพื่อ reload หน้าเว็บ
                    location.reload(); 
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

  
    </script>
@endpush

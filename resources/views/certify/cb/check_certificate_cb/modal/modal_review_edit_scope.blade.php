  
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModaEditCbScope" tabindex="-1" role="dialog" aria-labelledby="exampleModaEditCbScopeLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModaEditCbScopeLabel">ขอแก้ไขขอบข่าย
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div>


            <div class="modal-body">
                <input type="hidden" id="reportId" value="{{$report->id}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                            <label for="message" class="col-md-3 control-label text-right">รายละเอียด:</label>
                            <div class="col-md-9 text-left">
                                <textarea id="message" class="form-control check_readonly" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer data_hide">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="submit_ask_edit_cb_scope" >บันทึก</button>
            </div>

    </div>
    </div>
</div>

@push('js')
 
<script>

    $(document).ready(function () {


    $(document).on('click', '#submit_ask_edit_cb_scope', function(e) {
        const _token = $('input[name="_token"]').val();
    
        reportId = $('#reportId').val();
        message = $('#message').val().trim();
        console.log(message)
        if (!message) {
            alert("กรุณาระบุรายละเอียด");
            return; // หยุดการทำงาน ถ้า message ว่าง
        }


        $.ajax({
            // url:"{{route('api.calibrate')}}",
            url: "{{ url('/certify/check_certificate-cb/ask-to-edit-cb-scope') }}",
            method:"POST",
            data:{
                _token:_token,
                reportId:reportId,
                details:message,
            },
            success:function (result){
            // Refresh หน้าเว็บหลังจากสำเร็จ
            location.reload();

                
            }
        });
        

    });


    });


</script>

@endpush

{!! Form::open([  'method' => 'POST', 'class' => 'form-horizontal', 'url' => '/law/cases/forms_approved/update_form',  'id' => 'form_status' , 'files' => true]) !!}
<div class="modal fade " id="actionStatus"   data-bs-backdrop="static" data-bs-keyboard="false"   aria-labelledby="actionStatusLabel1" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="actionStatusLabel1">
                    ผลพิจารณาคดี    
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>

            <div class="modal-body">
 <div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                 <h3>ลำดับที่ <span id="span_no"></span> : ผลพิจารณา สำหรับ <span id="span_shortname"></span>  </h3>
            </legend>
            <input type="hidden"  id="m_id"  name="id"  value="">  
            <input type="hidden"  id="m_approve_id"  name="approve_id"  value="">   
            <input type="hidden"  id="m_level"  name="level"  value="">   
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('fullname', 'ผู้มีอำนาจพิจารณา', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-4">
                           {!! Form::text('fullname',null, ['id'=>'m_fullname',  'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('position', 'ตำแหน่ง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-4">
                           {!! Form::text('position',null, ['id'=>'m_position', 'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                    </div> 
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('status', 'สถานะ', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-4">
                            <label>{!! Form::radio('status', '1', true , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'status_1']) !!}&nbsp; เห็นชอบ &nbsp;</label>
                           <label>{!! Form::radio('status', '2',  false , ['class'=>'check', 'data-radio'=>'iradio_square-red','id'=>'status_2']) !!}&nbsp; ไม่เห็นชอบ &nbsp;</label>
                        </div>
                        {!! HTML::decode(Form::label('send_position', 'ส่งเรื่องต่อไปยัง', ['class' => 'col-md-2  send_position control-label text-right'])) !!}
                        <div class="col-md-4">
                           {!! Form::text('send_position',null, ['id'=>'m_send_position', 'class' => 'form-control send_position', 'disabled' => true]) !!}
                        </div>
                    </div> 
                </div>
            </div>

            <div class="row mb-3 visually-hidden" id="box_status">
                <div class="col-md-12 required">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('status_cases', 'เนื่องจาก', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-4">
                            {!! Form::select('status_cases', ['3'  => 'ขอข้อมูลเพิ่มเติม (ตีกลับ)','99' => 'ยกเลิก' ]  , null, ['id'=>'status_cases', 'class' => 'form-control ', 'placeholder'=>'- เลือกเนื่องจาก -','required' => false ])  !!}
                        </div>
                        {!! HTML::decode(Form::label('user_id', 'ส่งเรื่องกลับไปยัง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-4">
                            {!! Form::select('user_id',[] , null, ['id'=>'user_id',  'class' => 'form-control ', 'placeholder'=>'- เลือกส่งเรื่องกลับไปยัง -','required' => false ])  !!}
                        </div>
                    </div> 
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('remark', 'ความคิดเห็น', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-4">
                            {!! Form::textarea('remark', null, ['id'=>'m_remark', 'class' => 'form-control ', 'rows'=>3]) !!}
                        </div>
                    </div> 
                </div>
            </div>

        </fieldset>
    </div>
</div>
            </div>
            <div class="modal-footer ">
                <div class="text-center">
                    <button type="button" class="btn btn-primary ml-1" id="form_status_save"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">บันทึก</span></button>
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ยกเลิก</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@push('js')

    <script>
     
        $(document).ready(function () {
       
            $('#form_status_save').click(function(event) {
                     Swal.fire({
                        title: 'ยืนยันการพิจารณา !',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                                $('#form_status').submit();
                            }
                        })
            });

            $('#form_status').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {
                        // Text
                     $.LoadingOverlay("show", {
                        image       : "",
                        text        :   "กำลังบันทึกการพิจารณา กรุณารอสักครู่..." 
                    });
            
                return true;
            });
        });

    </script>

@endpush



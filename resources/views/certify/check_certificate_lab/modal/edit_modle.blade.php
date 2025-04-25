
  
@push('css')
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<!-- Modal -->
<div class="modal fade " id="EditModalExport" tabindex="-1" role="dialog" aria-labelledby="EditModalExportLabel" aria-hidden="true">
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="EditModalExportLabel">ไฟล์แนบท้าย
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h4>
        </div>
        <div class="modal-body">



                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('edit_modal_app_no') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('edit_modal_app_no', '<span class="text-danger">*</span> เลขที่คำขอ:', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6">
                                {!! Form::text('edit_modal_app_no',   null, ['class' => 'form-control','id'=>'edit_modal_app_no','readonly' => true]) !!}
                                {!! $errors->first('edit_modal_app_no', '<p class="help-block">:message</p>') !!}
                                <input type="hidden" id="edit_modal_id">
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-sm-12">
                        <div class=" {{ $errors->has('edit_attach') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('edit_attach', ' หลักฐาน:', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6 control-label text-left">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="edit_attach" id="edit_attach" class="check_max_size_file" accept=".doc,.docx">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2 control-label text-left ">
                                <p class="text-left"><span class="text-danger">(.docx,doc)</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class=" {{ $errors->has('edit_attach_pdf') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6 control-label text-left">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="edit_attach_pdf" id="edit_attach_pdf" class="check_max_size_file" accept=".pdf">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2 control-label text-left ">
                                <p class="text-left"><span class="text-danger">(.pdf)</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        {!! HTML::decode(Form::label('', '<span class="text-danger">*</span> ออกให้ตั้งแต่วันที่:', ['class' => 'col-md-4 control-label text-right'])) !!}
                        <div class="col-md-6">
                            <div class="input-daterange input-group date-range">
                                {!! Form::text('', null, ['class' => 'form-control date', 'required' => true , 'id' => 'edit_modal_start_date']) !!}
                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                {!! Form::text('', null, ['class' => 'form-control date', 'required' => true, 'id' => 'edit_modal_end_date']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        {!! HTML::decode(Form::label('', '', ['class' => 'col-md-4 control-label text-right'])) !!}
                        <div class="col-md-6">
                                 <div class="checkbox checkbox-success">
                                        <input id="state2" type="checkbox"  checked  disabled>
                                        <label for="state2" > เปิดใช้งานขอบข่าย </label>
                                </div>
                        </div>
                    </div>
                </div>




        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button type="button" id="edit_save_evidence" class="btn btn-primary">บันทึก</button>
        </div>
    </div>
</div>
</div>

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

<script>

    $(document).ready(function () {
        check_max_size_file();
 
        $('#edit_attach').change( function () {
            var fileExtension = ['docx','doc'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .docx,.doc");
                this.value = '';
            return false;
            }
        });
        $('#edit_attach_pdf').change( function () {
            var fileExtension = ['pdf'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf");
                this.value = '';
                return false;
            }
        });

 
        $('#edit_save_evidence').click(function (e) { 

            var file_word = $('#edit_attach').prop('files')[0];
            var file_pdf = $('#edit_attach_pdf').prop('files')[0];

            var app_no = $('#edit_modal_app_no').val();
            var id     = $('#edit_modal_id').val();

            var start_date = $('#edit_modal_start_date').val();
            var end_date = $('#edit_modal_end_date').val();


  
             var find_tr    = $('#deleteFlie'+id);
       

            if(  !checkNone(start_date)  ||  !checkNone(end_date) ){
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            } else {
            // Text
            $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");

                    if(checkNone(file_word)){
                        formData.append('file_word', $('#edit_attach')[0].files[0]);
                    }
                    if(checkNone(file_pdf)){
                        formData.append('file_pdf', $('#edit_attach_pdf')[0].files[0]);
                    }

                    formData.append('start_date',start_date);
                    formData.append('end_date',end_date);

                    formData.append('id', id);
                    formData.append('app_no',app_no);
                    formData.append('app_certi_lab_id','{{ !empty($export->certificate_for)?  $export->certificate_for:'' }}');
                    formData.append('ref_id','{{ $certi_lab->id }}');
                    formData.append('form','edit');

 
                    
                $.ajax({
                    type: "POST",
                    url: "{{ url('/certify/certificate_detail/update_document') }}",
                    datatype: "script",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (msgs) {
                        if(msgs.datas.length > 0){
                            $.LoadingOverlay("hide");

                            $('.fileinput').fileinput('clear')
                            $('#edit_modal_start_date').val('');
                            $('#edit_modal_end_date').val('');
 
                            $('#myTable tbody').html('');
                
                            $.each(msgs.datas, function( index, msg ) {
                                    var a_word = '';
                                    if( checkNone(msg.file_word) ){
                                        a_word = '<a  href="'+(msg.file_word)+'" class="attach"   target="_blank"><i  class="fa fa-file-word-o"  style="font-size:20px; color:#0000ff" aria-hidden="true"></i></a>';
                                    }

                                    var a_pdf = '';
                                    if( checkNone(msg.file_pdf) ){
                                        a_pdf = '<a  href="'+(msg.file_pdf)+'" class="attach_pdf"  target="_blank"><i class="fa fa-file-pdf-o" style="font-size:20px; color:red" aria-hidden="true"></i></a>';
                                    }
                       
                                var tr_ = '';
                                    tr_ += '<tr id="deleteFlie'+msg.id+'">'; 
                                    tr_ += '<td class="no-attach"></td>'; 
                                    tr_ += '<td class="text-center">'+(msg.app_no)+(msg.purpose_text)+'</td>'; 
                                    tr_ += '<td class="text-center">'+(a_word)+' '+(a_pdf)+'</td>'; 
                                    tr_ += '<td class="text-center ">'+(msg.start_date_th)+'</td>'; 
                                    tr_ += '<td class="text-center ">'+(msg.end_date_th)+'</td>'; 
                                  
                                    if(msg.state == '1'){
                                        tr_ += '<td class="text-center"><div class="checkbox"> <input class="js-switch" type="checkbox" value="1"  name="state"   checked data-color="#13dafe"  data-item_id="'+msg.id+'"> </div></td>';
                                        tr_ += '<td class="text-center created_at">'+ msg.created_at +'</td>'; 
                                        tr_ += '<td class="text-center"  ><button class="hide_attach btn btn-warning btn-xs edit_modal" type="button"  data-id="'+msg.id+'"  data-app_no="'+msg.app_no+'"  data-start_date="'+msg.start_date+'"  data-end_date="'+msg.end_date+'"><i class="fa fa-pencil-square-o"></i></button></td>'; 
                                    }else{
                                        tr_ += '<td class="text-center"><div class="checkbox"> <input class="js-switch" type="checkbox" value="1"  name="state"   data-color="#13dafe"  data-item_id="'+msg.id+'"> </div></td>';
                                        tr_ += '<td class="text-center created_at">'+ msg.created_at +'</td>'; 
                                        tr_ += '<td class="text-center"  ><button class="hide_attach btn btn-danger btn-xs del-attach" type="button"  data-id="'+msg.id+'"  data-app_no="'+msg.app_no+'"  data-start_date="'+msg.start_date+'"  data-end_date="'+msg.end_date+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>'; 
                                    }
                              
                                    tr_ += '</tr>'; 
                                    $('#myTable tbody').append(tr_);
                                });  
                            
                                
                            resetAttachmentNo();

                            $('.repeater-file').repeater();

                            $(".js-switch").each(function() {
                                    if($(this).parent().find('span').html() == undefined){
                                        new Switchery($(this)[0], {  size: 'small' });
                                    }
                            });
                            $(".js-switch").change( function () {
                                    if($(this).prop('checked')){
                                        $('.js-switch').prop('checked',false)
                                        $(this).prop('checked',true)
                                        $('.switchery-small').remove();
                                        $(".js-switch").each(function( index, data) {
                                            new Switchery($(this)[0], { size: 'small' });
                                        });
                                        button_file_all();
                                        var rows  =   $(this).parent().parent().parent();
                                            $(rows).find("button").removeClass("del-attach");
                                            $(rows).find("button").removeClass("btn-danger");
                                            $(rows).find("button > i").removeClass("fa-trash-o");

                                            $(rows).find("button").addClass("edit_modal");
                                            $(rows).find("button").addClass("btn-warning");
                                            $(rows).find("button > i").addClass("fa-pencil-square-o");
                                    } 
                                });
                            $('#EditModalExport').modal('hide');

                        }   
                    }
                });
         

 
            }

        });

    });


    
    function button_file_all(){ 
     
     var rows  = $('#myTable tbody').children();
        rows.each(function(index, el) {
            $(el).find("button").removeClass("edit_modal");
            $(el).find("button").removeClass("btn-warning");
            $(el).find("button > i").removeClass("fa-pencil-square-o");

            $(el).find("button").addClass("del-attach");
            $(el).find("button").addClass("btn-danger");
            $(el).find("button > i").addClass("fa-trash-o");
       });
     }
  function checkNone(value) {
     return value !== '' && value !== null && value !== undefined;
 }
 //รีเซตเลขลำดับ
 function resetAttachmentNo(){
     $('.no-attach').each(function(index, el) {
         $(el).text(index+1);
     });

 }

</script>

@endpush

  
@push('css')
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush
  
<!-- Modal -->
<div class="modal fade " id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalExportLabel">ไฟล์แนบท้าย 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h4>
            </div>
            <div class="modal-body">

                <div id="div_evidence">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('modal_app_no') ? 'has-error' : ''}}">
                                {!! HTML::decode(Form::label('modal_app_no', '<span class="text-danger">*</span> เลขที่คำขอ:', ['class' => 'col-md-4 control-label text-right'])) !!}
                                <div class="col-md-6">
                                    {!! Form::text('modal_app_no', !empty($export_cb->app_no)?  $export_cb->app_no :null, ['class' => 'form-control','id'=>'modal_app_no','required' => true,'readonly' => true]) !!}
                                    {!! $errors->first('modal_app_no', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-sm-12">
                            <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
                                {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> หลักฐาน:', ['class' => 'col-md-4 control-label text-right'])) !!}
                                <div class="col-md-6 control-label text-left">
                                    <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="attach" id="attach" class="check_max_size_file" accept=".doc,.docx">
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
                            <div class=" {{ $errors->has('attach_pdf') ? 'has-error' : ''}}">
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
                                            <input type="file" name="attach_pdf" id="attach_pdf" class="check_max_size_file" accept=".pdf">
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
                            {!! HTML::decode(Form::label('start_date', '<span class="text-danger">*</span> ออกให้ตั้งแต่วันที่:', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6">
                                <div class="input-daterange input-group date-range">
                                    {!! Form::text('start_date', null, ['class' => 'form-control date', 'required' => true , 'id' => 'modal_start_date']) !!}
                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                    {!! Form::text('end_date', null, ['class' => 'form-control date', 'required' => true, 'id' => 'modal_end_date']) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <div class="modal-footer" style="text-align: center">
                <input type="hidden" name="app_certi_cb_id" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" id="save_evidence" class="btn btn-primary">เพิ่ม</button>
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
    <script>
    
        $(document).ready(function () {
            check_max_size_file();
 
            $('#attach').change( function () {
                var fileExtension = ['docx','doc'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .docx,.doc");
                    this.value = '';
                return false;
                }
            });
            $('#attach_pdf').change( function () {
                var fileExtension = ['pdf'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf");
                    this.value = '';
                    return false;
                }
            });

 
            $('#save_evidence').click(function (e) { 

                var file_word = $('#attach').prop('files')[0];
                var file_pdf = $('#attach_pdf').prop('files')[0];

                var app_no = $('#modal_app_no').val();
                var id     = "";
                var start_date = $('#modal_start_date').val();
                var end_date = $('#modal_end_date').val();

                if(  !checkNone(start_date)  ||  !checkNone(end_date) ){
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
                } else if( checkNone(file_word) || checkNone(file_pdf)  ){
                            // Text
                        $.LoadingOverlay("show", {
                                        image       : "",
                                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                                      });
                    var formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('file_word', $('#attach')[0].files[0]);
                        formData.append('file_pdf', $('#attach_pdf')[0].files[0]);
                        formData.append('modal_app_no', $('#modal_app_no').val());

                    $.ajax({
                        type: "POST",
                        url: "{{ url('/certify/certificate-export-ib/update_document') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                            if (msg != "") {

                               $.LoadingOverlay("hide");

                                $('.js-switch').prop('checked',false);
                                $('.switchery-small').remove();
                                $(".js-switch").each(function( index, data) {
                                        new Switchery($(this)[0], { size: 'small' });
                                });
                                 button_file_all();
                      
                                $('.fileinput').fileinput('clear')
                                $('#modal_start_date').val('');
                                $('#modal_end_date').val('');

                                var input_start_date = '<input type="hidden" value="'+(start_date)+'" name="start_date"/>';
                                var input_end_date = '<input type="hidden" value="'+(end_date)+'" name="end_date"/>';

                                var a_word = '';
                                if( checkNone(msg.file_word) ){
                                    a_word = '<a  href="'+(msg.file_word)+'" class="attach"  target="_blank"><i  class="fa fa-file-word-o"  style="font-size:20px; color:#0000ff" aria-hidden="true"></i></a>';
                                    a_word += '<input type="hidden" value="'+(msg.file_word_odl)+'" name="input_file_word_name"/>';
                                    a_word += '<input type="hidden" value="'+(msg.file_word_path)+'" name="file_word"/>';
                                }

                                var a_pdf = '';
                                if( checkNone(msg.file_pdf) ){
                                    a_pdf = '<a  href="'+(msg.file_pdf)+'" class="attach_pdf"   target="_blank"><i class="fa fa-file-pdf-o" style="font-size:20px; color:red" aria-hidden="true"></i></a>';
                                    a_pdf += '<input type="hidden" value="'+(msg.file_pdf_odl)+'" name="input_file_pdf_name"/>';
                                    a_pdf += '<input type="hidden" value="'+(msg.file_pdf_path)+'" name="file_pdf"/>';
                                }
                                
                                // var state = '{!! Form::checkbox("state", 1, true , ["class" => "js-switch", "data-color" => "#13dafe"]) !!}';
                                var tr_ = '';
                                    tr_ += '<tr data-repeater-item>'; 
                                    tr_ += '<td class="no-attach"></td>'; 
                                    tr_ += '<td class="text-center">'+(app_no)+'</td>'; 
                                    tr_ += '<td class="text-center">'+(a_word)+' '+(a_pdf)+'</td>'; 
                                    tr_ += '<td class="text-center ">'+(input_start_date)+' '+(DateFormateThai(start_date))+'</td>'; 
                                    tr_ += '<td class="text-center ">'+(input_end_date)+' '+(DateFormateThai(end_date))+'</td>'; 
                                    tr_ += '<td class="text-center"><div class="checkbox"> <input class="js-switch" type="checkbox" value="1"  name="state"   checked data-color="#13dafe"  data-certilab_file_id="'+id+'"> </div></td>';
                                    tr_ += '<td  class="text-center created_at" >แสดงเมื่อบันทึกข้อมูล</td>'; 
                                    tr_ += '<td class="text-center"><button class="hide_attach btn btn-warning btn-xs edit_modal" type="button"  data-id="'+id+'"  data-app_no="'+app_no+'">    <i class="fa fa-pencil-square-o"></i></button></td>'; 
                                    tr_ += '</tr>'; 

                                $('#myTable tbody').append(tr_);

                                resetAttachmentNo();

                        
                                $('.repeater-file').repeater();
            
                                $(".js-switch:last").each(function() {
                                    if($(this).parent().find('span').html() == undefined){
                                        new Switchery($(this)[0], { size: 'small' });
                                    }
                                });

                                $(".js-switch").change( function () {
                                    if($(this).prop('checked')){
                                        $('.js-switch').prop('checked',false)
                                        $(this).prop('checked',true)
                                        $('.switchery-small').remove();
                                        $(".js-switch").each(function( index, data) {
                                            new Switchery($(this)[0], {size: 'small' });
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
                                $('#exampleModalExport').modal('hide');

                            }   
                        }
                    });
                    console.log(formData);

                }else{
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
                }

            });

        });

        
        //รีเซตเลขลำดับ
        function resetAttachmentNo(){

            $('.no-attach').each(function(index, el) {
                $(el).text(index+1);
            });

        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
 
@endpush
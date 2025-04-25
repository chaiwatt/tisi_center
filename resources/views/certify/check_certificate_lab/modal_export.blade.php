

  @push('css')
  <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
  @endpush


  <!-- Modal -->
   <div class="modal fade  " id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalExportLabel">หลักฐานแนบท้าย
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div>
        {!! Form::open(['url' => 'certify/check_certificate/update_attach', 'class' => 'form-horizontal', 'files' => true,'id'=>'form_update_attach']) !!}
        <div class="modal-body">


        <div id="div_evidence">
            <div class="col-lg-12">
                 <div class="form-group  pull-right">
                    <button type="button" id="evidence" class="btn btn-default">ประวัติการบันทึก</button>
                </div>
            </div><!-- /.col-lg-1 -->

          <div class="row">
            <div class="col-sm-12">
               <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('attach', '  หลักฐาน '.':', ['class' => 'col-md-4 control-label text-right'])) !!}
                   <div class="col-md-6 control-label text-left">
                            <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attach"  id="attach">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                   </div>
                   <div class="col-md-2 control-label text-left ">
                       <p class="text-left"><span class="text-danger">(.docx)</span></p>
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
                                    <input type="file" name="attach_pdf"   id="attach_pdf">
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

        </div>

        <div id="table_evidence">

            <div class="col-lg-12">
                <div class="form-group  pull-right">

                   <button type="button" id="add_evidence" class="btn btn-primary">เพิ่มหลักฐานแนบท้าย</button>
               </div>
           </div><!-- /.col-lg-1 -->

          @if(count($cc->applicant->cert_cabs_file_all) > 0)
          <div class="row">
            <div class="col-sm-12">
               <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                   {!! Form::label('file', 'ประวัติการบันทึก',['class' => 'col-md-4 control-label  text-right']) !!}
                   <div class="col-md-8 text-left ">
                    <div class="table-responsive">
                        <table class="table color-bordered-table info-bordered-table">
                            <thead>
                            <tr>
                                <th class="text-center" width="2%">#</th>
                                <th class="text-center" width="40%">หลักฐาน</th>
                                <th class="text-center" width="20%"></th>
                                <th class="text-center" width="30%">วันที่บันทึก</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($cc->applicant->cert_cabs_file_all as  $key => $itme)
                                <tr>
                                    <td>{{ $key +1}}.</td>
                                    <td>
                                        <p class="text-left">
                                            @if(!is_null($itme->attach))
                                                <a href="{{url('certify/check/file_client/'.$itme->attach.'/'.( !empty($itme->attach_client_name) ? $itme->attach_client_name :  basename($itme->attach)  ))}}" target="_blank">
                                                   {!! HP::FileExtension($itme->attach)  ?? '' !!}
                                               </a>
                                            @endif
                                            @if(!is_null($itme->attach_pdf))
                                            <a href="{{url('certify/check/file_client/'.$itme->attach_pdf.'/'.( !empty($itme->attach_pdf_client_name) ? $itme->attach_pdf_client_name :  basename($itme->attach_pdf)  ))}}" target="_blank">
                                                {!! HP::FileExtension($itme->attach_pdf)  ?? '' !!}
                                            </a>
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                          <div class="checkbox"><input class="js-switch " name="state" type="checkbox" value="{{$itme->id}}" {{ ($itme->state == 1) ? 'checked ' : '' }}  ></div>
                                     </td>
                                    <td>
                                        {{  HP::DateThai($itme->created_at) ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   </div>
               </div>
             </div>
          </div>
         @endif
        </div>

        </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                    <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>
@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script>

    $(document).ready(function () {

            $("#evidence").click(function(){
                    $('#div_evidence').hide(300);
                    $('#table_evidence').show(300);
             });

             $("#add_evidence").click(function(){
                    $('#div_evidence').show(300);
                    $('#table_evidence').hide(300);
             });
             $("#evidence").click();
        $(".js-switch").each(function() {
                new Switchery($(this)[0], { size: 'small' });
             });

         $(".js-switch").change( function () {

                if($(this).prop('checked')){
                      $('.js-switch').prop('checked',false)
                        $(this).prop('checked',true)
                        $('.switchery-small').remove();
                       $(".js-switch").each(function( index, data) {
                          new Switchery($(this)[0], { secondaryColor :'red', size: 'small' });
                       });
                 }

         });

        $('#attach').change( function () {
            var fileExtension = ['docx','doc'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() !='') {
            alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .docx");
            this.value = '';
            return false;
            }
        });
        $('#attach_pdf').change( function () {
            var fileExtension = ['pdf'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() !='') {
                alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf");
            this.value = '';
            return false;
            }
        });

        var attach = '{{  !is_null($cc->applicant->attach) && $cc->applicant->attach != '' && !is_null($cc->applicant->attach) ? 1 : null  }}';
            if(attach == 1){
                $('.attach_hide').hide();
            }
});

</script>

@endpush


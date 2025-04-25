@php
    $file_image_cover_max = HP::get_upload_max_filesize('15MB');
@endphp

<h5 style="text-muted">อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{$file_image_cover_max}}</h5>

@php
    $config_evidence = [];
    if( !empty($lawcasesform->config_evidence) ){
        $configs_evidences = json_decode($lawcasesform->config_evidencce);
    }else{
        $configs_evidences = App\Models\Config\ConfigsEvidence::whereHas('configs_evidence_groups', function($query){
                                            return $query->where('state', 1);
                                        })
                                        ->Where(function($query){
                                            $query->where('state', 1)->where('evidence_group_id', 6);
                                        })
                                        ->orderBy('ordering')
                                        ->get();
    }
@endphp
<div class="repeater-form">
    <div data-repeater-list="evidences">
        @foreach ( $configs_evidences as $evidences )

            @php
                $attachment      = null;
                $setting_file_id = $evidences->id;

                
                $file_properties = null;

                if(  !empty($evidences->file_properties)  ){
                    $list = [];
                    foreach ( json_decode($evidences->file_properties) as $value) {

                        $list[] = '.'.$value;
                    }
                    $evidences->file_properties_item =  $list;

                }

                $file_properties = !empty($evidences->file_properties_item) ? implode(',', $evidences->file_properties_item ):'';
                if( isset($lawcasesform->id) ){
                    $attachment = App\Models\Law\File\AttachFileLaw::where('ref_table', (new App\Models\Law\Cases\LawCasesForm )->getTable() )
                                    ->where('ref_id', $lawcasesform->id )
                                    ->when($setting_file_id, function ($query, $setting_file_id){
                                        return $query->where('setting_file_id', $setting_file_id);
                                    })
                                    ->first();
                }
            @endphp

            <div class="row" data-repeater-item>
                <div class="col-md-12">
                    <div class="form-group @if($evidences->required == 1) required @endif">
                        {!! HTML::decode(Form::label('evidence_file_config', (!empty($evidences->title)?$evidences->title:null), ['class' => 'col-md-6 control-label evidence_label', 'style' => 'text-align: left !important'])) !!}

                        @if( !is_null($attachment) )
                            <div class="col-md-5" >
                                <a href="{!! HP::getFileStorage($attachment->url) !!}" target="_blank" title="{!! !empty($attachment->filename) ? $attachment->filename : 'ไฟล์แนบ' !!}">
                                    <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                                    <span>{{ ( !empty($attachment->filename) ? $attachment->filename : ' ') }}</span>
                                </a>
                            </div>
                            <div class="col-md-1" >
                                <a class="btn btn-danger btn-xs show_tag_a confirmation" href="{!! url('law/delete-files/'.($attachment->id).'/'.base64_encode('law/cases/forms/'.$lawcasesform->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        @else
                            <div class="col-md-5">
                                {!! Form::hidden('setting_title' ,(!empty($evidences->title)?$evidences->title:null), ['required' => false]) !!}
                                {!! Form::hidden('setting_id' ,(!empty($evidences->id)?$evidences->id:null), ['required' => false]) !!}
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" 
                                               name="evidence_file_config" 
                                               class="evidence_file_config " 
                                               @if($evidences->required == 1) required @endif 
                                               @if(  !empty($evidences->file_properties) )
                                                    accept="{!! $file_properties !!}"
                                                    {{-- data-accept="{!! base64_encode( $evidences->file_properties) !!}" --}}
                                                @endif
                                                     max-size="{{ $evidences->size }}"
                                            >
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>

@php
    $file_other = [];
    if( isset($lawcasesform->id) ){
        $file_other = $lawcasesform->attach_files()->where('section', 'evidence_file_other')->get();
    }
@endphp

<div class="row repeater-form" id="div_attach">
    <div class="col-md-12" data-repeater-list="repeater-file">

        @foreach ( $file_other as $attach )

            <div class="form-group">
                {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.'<br/><span class="font_size">(เพิ่มได้ไม่เกิน 5 ไฟล์)</span>', ['class' => 'col-md-3 control-label personfile-label label-height','style'=>'text-align: left !important'])) !!}
                <div class="col-md-3">
                    {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                </div>
                <div class="col-md-5">
                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank" title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}">
                        <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                        <span>{{ ( !empty($attach->filename) ? $attach->filename : ' ') }}</span>
                    </a>
                </div>
                <div class="col-md-1" >
                    <a class="btn btn-danger btn-xs show_tag_a confirmation" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/forms/'.$lawcasesform->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>

        @endforeach

        <div class="form-group input_show_file" data-repeater-item>
            {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.'<br/><span class="font_size">(เพิ่มได้ไม่เกิน 5 ไฟล์)</span>', ['class' => 'col-md-3 control-label personfile-label label-height','style'=>'text-align: left !important'])) !!}
            <div class="col-md-3">
                {!! Form::text('file_documents', null , ['class' => 'form-control' , 'placeholder' => 'ชื่อเอกสาร']) !!}
            </div>
            <div class="col-md-5">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="evidence_file_other" id="evidence_file_other" class="evidence_file_config" max-size="{{ $file_image_cover_max }}">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-danger btn-outline btn_file_remove" data-repeater-delete type="button">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <button type="button" id="add_file_other" class="btn btn-success btn-outline" data-repeater-create><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>


@php
    $additional_files = [];
    if( isset($lawcasesform->id) ){
        $additional_files = $lawcasesform->attach_files()->where('section', 'additional_files')->get();
    }
@endphp
@if (count($additional_files) > 0)
<div class="row" >
    <div class="col-md-12">
        @foreach ( $additional_files as  $key => $attach )
            <div class="form-group">
                @if ($key==0)
                {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.'<br/><span class="font_size">(เพิ่มได้ไม่เกิน 5 ไฟล์)</span>', ['class' => 'col-md-3 control-label personfile-label label-height','style'=>'text-align: left !important'])) !!}
                @else
                  <div class="col-md-3"> </div>
                @endif
               
                <div class="col-md-3">
               
                    {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                </div>
                <div class="col-md-5">
                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank" title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}">
                        <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                        <span>{{ ( !empty($attach->filename) ? $attach->filename : ' ') }}</span>
                    </a>
                </div>
                <div class="col-md-1" >
                    <a class="btn btn-danger btn-xs show_tag_a confirmation" href="{!! url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/forms/'.$lawcasesform->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>
        @endforeach
    </div>
</div> 
@endif



@push('js')
    <script>
        $(document).ready(function() {

            $('.confirmation').on('click', function () {
                    return  confirm("ต้องการลบแถวนี้หรือไม่ ?");
             });
         


            $('.evidence_file_config').change(function (e) {

                var result = true;

                if(!!$(this).val()){
                    var max_size = "{{ ini_get('upload_max_filesize') }}";
                    var res = max_size.replace("M", "");
                    var filesize = this.files[0].size;
                    var fileName = $(this).val();
                    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                        res = $(this).attr('max-size')!=undefined ? parseInt($(this).attr('max-size')) : res ; //ถ้ามีกำหนดขนาดไฟล์ที่อัพโหลดได้โดยเฉพาะ
                        console.log(res);
                        var size = (this.files[0].size)/1024/1024 ; // หน่วย MB
                    if(size > res ){
                        Swal.fire(
                                'ไฟล์ขนาดต้องไม่เกิน '+res+' MB',
                                '',
                                'info'
                            );
                        $(this).val('');
                        $(this).next(".custom-file-label").html('Choose file')

                        result = false;
                    }
                      /* ตรวจสอบนามสกุลไฟล์ */
                    if($(this).attr('accept')!=undefined){//ถ้ากำหนดนามสกุลไฟล์ที่อัพโหลดได้ไว้
                        let accepts = $(this).attr('accept').split(',');
                        let names  = this.files[0].name.split('.');//ชื่อเต็มไฟล์
                        let ext    = names.at(-1);//นามสกุลไฟล์
                        let result = false;
                        $.each(accepts, function(index, accept) {
                            if('.'+ext==$.trim(accept)){
                                result = true;
                                return false;
                            }
                        });
                        if(result===false){
                            Swal.fire(
                                'อนุญาตให้อัพโหลดไฟล์นามสกุล '+accepts+' เท่านั้น',
                                '',
                                'info'
                            );
                             $(this).val('');
                             $(this).next(".custom-file-label").html('Choose file');
                            return false;
                        }
                    }
                    
                    
                    //   if(!!$(this).attr('accept') && (JSON.parse($(this).attr('accept')).filter((a) => a)).length > 0){
                    //     if ($.inArray(ext, JSON.parse($(this).attr('accept'))) === -1) {//ถ้าเป็นประเภทไฟล์ที่กำหนด และขนาดไม่เกิน 2MB
                    //         alert('นามสกุลไฟล์แนบต้องเป็น '+JSON.parse($(this).attr('accept')).map((number) => '.'+number).join(', ')+' เท่านั้น');
                    //         $(this).val('');
                    //         $(this).next(".custom-file-label").html('Choose file')
                    //         result = false;
                    //     }
                    //   }
                }

                return result;

            });

            resetOrderNoFile();

            $('.repeater-form').repeater({
                show: function () {
                    $(this).slideDown();
                    resetOrderNoFile();
                    var all_row = $('.btn_file_remove').length;
                    if(all_row > 5){
                        Swal.fire(
                                "กรุณาเลือก เอกสารเพิ่มเติม ไม่เกิน 5 แถว",
                                '',
                                'info'
                            );
                        $(this).remove();
                    }
                },
                hide: function (deleteElement) {
                    Swal.fire({
                        title: 'คุณต้องการลบแถวนี้ ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ตกลง',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                               $(this).slideUp(deleteElement);
                                setTimeout(function(){
                                    resetOrderNoFile();
                                }, 400);
                            }
                     })
                    
                    // if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    //     $(this).slideUp(deleteElement);
                    //     setTimeout(function(){
                    //         resetOrderNoFile();
                    //     }, 400);
                    // }
                }
            });
        });

        
        function countFiveFile(addElement){

            var all_row = $('.input_show_file').length;
            if(all_row > 5){
                Swal.fire(
                            "กรุณาเลือก เอกสารเพิ่มเติม ไม่เกิน 5 แถว",
                            '',
                            'info'
                        );
                $(this).slideUp(addElement);
                return false;
            }

        }

        function resetOrderNoFile(){
            if($('.btn_file_remove').length >= 2){
                $('.btn_file_remove').show();
                $('.personfile-label:eq(1), .personfile-label:eq(2), .personfile-label:eq(3), .personfile-label:eq(4)').html(''); 
            }else{
                $('.btn_file_remove').hide();
            }
           
        } 
    </script>
@endpush

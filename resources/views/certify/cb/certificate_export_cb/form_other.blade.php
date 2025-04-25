<div class="white-box">
    <div class="row">
        <div class="col-sm-12">
             <legend><h3 class="box-title">สถานะการออกใบรับรอง</h3></legend>

             <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  สถานะ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-6">
                    {!! Form::select('app_status', 
                      ['18'=>'ออกใบรับรอง และ ลงนาม','19'=>'ลงนามเรียบร้อย'], 
                      null,
                     ['class' => 'form-control',
                     'id' => 'app_status',
                     'placeholder'=>'- เลือกสถานะ -' ]); !!}
                    {!! $errors->first('app_status', '<p class="help-block">:message</p>') !!} 
                  
                </div>
            </div>

            <div class="form-group" id="export_file">
                <label for="requestNumber" class="col-md-3 control-label"><span class="text-danger">*</span> หลักฐาน :</label>
                <div class="col-md-6">
                     @if(isset($export_cb->id) && !is_null($export_cb->attachs)) 
                       <a href="{{url('certify/check/file_cb_client/'.$export_cb->attachs.'/'.( !empty($export_cb->attach_client_name) ? $export_cb->attach_client_name :  basename($export_cb->attachs)  ))}}" target="_blank">
                            {!! HP::FileExtension($export_cb->attachs)  ?? '' !!}
                        </a>
                     @else 
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="attachs" id="attachs" class="check_max_size_file">
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                     @endif
                </div>
            </div>

        </div>
    </div>
</div>


@push('js')
<script type="text/javascript">

    $(document).ready(function() {
        $('#app_status').change(function(){ 
            if($(this).val() == 19){
                $('#export_file').show();                
                $('#attachs').prop('required',true);
            }else{
                $('#export_file').hide();
                $('#attachs').prop('required',false);
            }
        });
        $('#app_status').change();
    });

 </script>


@endpush

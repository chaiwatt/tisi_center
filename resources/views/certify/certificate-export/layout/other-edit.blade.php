<div class="white-box">
    <h4>สถานะการออกใบรับรอง</h4>

    <div class="form-group">
        @php $name = 'status' ; $text = ($lang == "th" ? 'สถานะ': 'Issue this certificate for') @endphp
        <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
        <div class="col-md-6">
            <select name="{{$name}}" id="{{$name}}" class="form-control">
                {{-- <option value="0" {{$certificate->status == 0 ?  'selected' : null}}>จัดทำใบรับรอง</option>
                <option value="1" {{$certificate->status == 1 ?  'selected' : null}}>ตรวจสอบความถูกต้อง</option> --}}
                <option value="2" {{$certificate->status == 2 ?  'selected' : null}}>ออกใบรับรองและลงนาม</option>
                <option value="3" {{$certificate->status == 3 ?  'selected' : null}}>ลงนามเรียบร้อย</option>

            </select>
            {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group" id="export_file">
        <label for="requestNumber" class="col-md-4 control-label"><span class="text-danger">*</span> หลักฐาน :</label>
        <div class="col-md-6">
             @if(isset($certificate) && !is_null($certificate->attachs)) 
                <a href="{{ url('certify/check/files/'. $certificate->attachs) }}"> 
                    {!! HP::FileExtension($certificate->attachs)  ?? '' !!}
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
                        <input type="file" name="attachs" id="attachs">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
             @endif
           
        </div>
    </div>

</div>


@push('js')
<script type="text/javascript">

    $(document).ready(function() {
        $('#status').change(function(){ 
            if($(this).val() == 3){
                $('#export_file').show();                
                $('#attachs').prop('required',true);
            }else{
                $('#export_file').hide();
                $('#attachs').prop('required',false);
            }
        });
        $('#status').change();
    });

 </script>


@endpush

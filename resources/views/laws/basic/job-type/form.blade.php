@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อประเภทงาน', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('title', null , ['class' => 'form-control ', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($jobtype->created_by)? $jobtype->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($jobtype->created_at)? HP::revertDate($jobtype->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<input id="type_submit" name="type_submit" value="" type="hidden">

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="button" id="btn_save_and_close">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @if( !isset($jobtype->id) )
            <button class="btn btn-info" type="button" id="btn_save_and_copy">
                <i class="fa fa-paste"></i> บันทึกและคัดลอก
            </button>
        @endif
        @can('view-'.str_slug('law-basic-job-type'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/basic/job-type') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script>

        $(document).ready(function() {

            $('#btn_save_and_copy').click(function (e) { 
                $('#type_submit').val(1);
                $('#myForm').submit();
            });

            $('#btn_save_and_close').click(function (e) { 
                $('#type_submit').val(0);
                $('#myForm').submit();
            });

            $('#myForm').submit(function() {

                if( $('#type_submit').val() == 1){
                    var formData = new FormData($("#myForm")[0]);
                        formData.append('_token', "{{ csrf_token() }}");

                    $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังบทึกข้อมูล กรุณารอสักครู่..."
                    });

                    $.ajax({
                        method: "POST",
                        url: "{{ url('/law/basic/job-type/save_and_copy') }}",
                        data: formData,
                        contentType : false,
                        processData : false,
                        success : function (obj){

                            if (obj.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'บันทึกสำเร็จ !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $.LoadingOverlay("hide");
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'บันทึกไม่สำเร็จ !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $.LoadingOverlay("hide");
                            }
                        }
                    });

                    return false;
                }
          
            });

        });

    </script>
@endpush

<div id="MdGazette" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">บันทึกข้อมูลเอกสารประกาศราชกิจจาฯ</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_gen_gazette" onsubmit="return false">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_issue', 'ฉบับที่:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('m_issue', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_year', 'ปีที่ประกาศ:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-6">
                                    {!! Form::select('m_year', HP::YearRange(),( date('Y') ), ['class' => 'form-control', 'placeholder'=>'-เลือกปี-', 'required' => 'required']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_announcement_date', 'ประกาศ ณ วันที่', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-6">
                                    <div class="input-group">
                                        {!! Form::text('m_announcement_date', null,  ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_government_gazette_date', 'วันที่ประกาศราชกิจจาฯ', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-6">
                                    <div class="input-group">
                                        {!! Form::text('m_government_gazette_date', null,  ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_file_gazette', 'เอกสารประกาศราชกิจจา'.' :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                            <span class="input-group-text btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="m_file_gazette" id="m_file_gazette" required>
                                            </span>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('m_sign_id', 'ผู้ลงนาม:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('m_sign_id',  App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id') , null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ลงนาม-', 'required' => 'required']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('m_sign_position', 'ตำแหน่ง:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('m_sign_position', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('m_government_gazette_description', 'รายละเอียด'.' :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('m_government_gazette_description', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="clearfix">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_announcement"><i class="fa fa-plus-square"></i> สร้างประกาศ</button>
                <button type="button" class="btn btn-danger  btn-sm waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#m_sign_id').change(function(){

                if($(this).val() != ''){

                    $.ajax({
                        url: "{!! url('section5/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#m_sign_position').val(object.sign_position);
                    });

                }else{
                    $('#m_sign_position').val('');
                }
            });

            $('#btn_save_announcement').click(function (e) {
                $('#form_gen_gazette').submit();   
            });

        });

    </script>
@endpush
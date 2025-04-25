<div id="modal_gazette" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</h4> 
            </div>
            <div class="modal-body form-horizontal">
                <div class="row">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('me_app_name', 'ผู้ยื่นคำขอ:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('me_app_name', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('me_app_taxid', 'เลขผู้เสียภาษี:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('me_app_taxid', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('me_app_no', 'เลขที่คำขอ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('me_app_no', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('me_app_std', 'มอก:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('me_app_std', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('me_app_board_meeting_date', 'วันประชุมคณะอนุฯ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('me_app_board_meeting_date', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('me_issue', 'ฉบับที่:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('me_issue', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('me_year', 'ปีที่ประกาศ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('me_year', HP::YearRange(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('me_announcement_date', 'ประกาศ ณ วันที่', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('me_announcement_date', null,  ['class' => 'form-control mydatepicker']) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group required">
                                {!! Form::label('me_sign_id', 'ผู้ลงนาม:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('me_sign_id',  App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id') , null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ลงนาม-']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('me_sign_position', 'ตำแหน่ง:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('me_sign_position', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br class="clearfix">
                    <p class="text-danger col-md-offset-2" id="show_gazette_edit">* หากแก้ไขข้อมูลประกาศ ระบบจะบันทึกและแก้ไขข้อมูลที่เกี่ยวข้องทุกรายการ </p>

                    {!! Form::hidden('me_edit', 1, ['class' => 'form-control','id' => 'me_edit']) !!}
                    {!! Form::hidden('me_id_edit', null, ['class' => 'form-control','id' => 'me_id_edit']) !!}
                    {!! Form::hidden('me_check_gazette', null, ['class' => 'form-control','id' => 'me_check_gazette']) !!}


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_print_word">ไฟล์ประกาศ</button>
                <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" id="btn_edit_gazette">แก้ไขข้อมูล</button>
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_gazette">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="btn_cancel_gazette">ยกเลิก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="btn_close_gazette" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#me_sign_id').change(function(){ 
                
                if($(this).val() != ''){

                    $.ajax({
                        url: "{!! url('section5/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#me_sign_position').val(object.sign_position);
                    });

                }else{
                    $('#me_sign_position').val('');
                }
            });

            $('#btn_save_gazette').click(function (e) { 
                SaveGazette(); 
            });
        
        });

    </script>
@endpush
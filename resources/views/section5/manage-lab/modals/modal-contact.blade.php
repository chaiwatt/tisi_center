{!! Form::model($labs, [
    'method' => 'PATCH',
    'url' => ['/section5/labs/contact-save', $labs->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}


<div id="MdContact" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px;max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ห้องปฏิบัติการ</h4>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('co_name') ? 'has-error' : ''}}">
                            {!! Form::label('co_name', 'ชื่อผู้ประสานงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_name', !empty( $labs->co_name )?$labs->co_name:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                {!! $errors->first('co_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required {{ $errors->has('co_position') ? 'has-error' : ''}}">
                            {!! Form::label('co_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_position', !empty( $labs->co_position )?$labs->co_position:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                {!! $errors->first('co_position', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('co_mobile') ? 'has-error' : ''}}">
                            {!! Form::label('co_mobile', 'โทรศัพท์มือถือ'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_mobile', !empty( $labs->co_mobile )?$labs->co_mobile:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                {!! $errors->first('co_mobile', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('co_phone') ? 'has-error' : ''}}">
                            {!! Form::label('co_phone', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_phone', !empty( $labs->co_phone )?$labs->co_phone:null,  ['class' => 'form-control co_input_show' ]) !!}
                                {!! $errors->first('co_phone', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('co_fax') ? 'has-error' : ''}}">
                            {!! Form::label('co_fax', 'โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_fax', !empty( $labs->co_fax )?$labs->co_fax:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                {!! $errors->first('co_fax', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('co_email') ? 'has-error' : ''}}">
                            {!! Form::label('co_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('co_email', !empty( $labs->co_email )?$labs->co_email:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                {!! $errors->first('co_email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" type="submit" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


{!! Form::close() !!}
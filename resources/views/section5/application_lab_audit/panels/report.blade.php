<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                บันทึกสรุปรายงาน # {!! $applicationlabaudit->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body" id="box-report">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="white-box">
                                <legend class="legend"><h5>บันทึกสรุปรายงาน</h5></legend>

                                @php
                                    $application_report = $applicationlabaudit->app_report;
                                    $file_attach_report = null;
                                    $file_attach_others = [];
                                    if( !is_null($application_report) ){
                                        $file_attach_report = App\AttachFile::where('section', 'file_attach_report')->where('ref_table', (new App\Models\Section5\ApplicationLabsReport)->getTable())->where('ref_id', $application_report->id )->first();
                                        $file_attach_others = App\AttachFile::where('section', 'file_attach_other')->where('ref_table', (new App\Models\Section5\ApplicationLabsReport)->getTable())->where('ref_id', $application_report->id )->get();
                                    }
                                @endphp
                    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group required{{ $errors->has('file_attach_report') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('file_attach_report', 'เอกสารสรุปรายงาน'.' :', ['class' => 'col-md-4 control-label'])) !!}
                                            <div class="col-md-8" >
                                                @if( is_null($file_attach_report) )
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="file_attach_report" required>
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists delete_personfile"  data-dismiss="fileinput">ลบ</a>
                                                    </div>
                                                    {!! $errors->first('activity_file', '<p class="help-block">:message</p>') !!}
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_attach_report->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_attach_report->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_attach_report->id).'/'.base64_encode('section5/application_lab_audit/lab_report/'.$applicationlabaudit->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group required">
                                            {!! Form::label('report_date', 'วันที่สรุปรายงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    {!! Form::text('report_date', !empty($application_report->report_date)?HP::revertDate($application_report->report_date):HP::revertDate( date('Y-m-d'),true) , ['class' => 'form-control mydatepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group required{{ $errors->has('report_by') ? 'has-error' : ''}}">
                                            {!! Form::label('report_by', 'ผู้จัดทำรายงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('report_by', App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id')->pluck('titels', 'id'),  !empty($application_report->report_by)?$application_report->report_by:(auth()->user()->getKey()), ['class' => 'form-control', 'placeholder' => '-เลือกผู้จัดทำรายงาน-', 'required' => true]) !!}
                                                {!! $errors->first('report_by', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group {{ $errors->has('report_description') ? 'has-error' : ''}}">
                                            {!! Form::label('report_description', 'หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('report_description', !empty($application_report->report_description)?$application_report->report_description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('report_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group repeater-form-file{{ $errors->has('file_attach_other') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('file_attach_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                            <div class="col-md-8" data-repeater-list="repeater-file">
                                                <div class="row" data-repeater-item>
                                                    <div class="col-md-5 col-custom-1">
                                                        {!! Form::text('caption', null, ['class' => 'form-control']) !!}
                                                    </div>
                                                    <div class="col-md-5 col-custom-2">
                                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                            <div class="form-control" data-trigger="fileinput">
                                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                <span class="fileinput-filename"></span>
                                                            </div>
                                                            <span class="input-group-addon btn btn-default btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_attach_other">
                                                            </span>
                                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                                                        </div>
                                                        {!! $errors->first('activity_file', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                    <div class="col-md-2 col-custom-3">
                                                        <button class="btn btn-danger btn_date_remove" data-repeater-delete type="button">
                                                            ลบ
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-custom-4">
                                                <button type="button" class="btn btn-success pull-left" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if( count($file_attach_others) > 0)
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                @if( isset( $applicationlabaudit->approve ) && $applicationlabaudit->approve == true )
                                                    {!! HTML::decode(Form::label('file_attach_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                                @else
                                                    {!! HTML::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
                                                @endif

                                                <div class="col-md-8">
                                                    @foreach (  $file_attach_others  as  $file  )
                                                        <div class="row">
                                                            <div class="col-md-5 col-custom-1">
                                                                {!! Form::text('caption', ( !empty($file->caption)?$file->caption:null ), ['class' => 'form-control', 'disabled' => true]) !!}
                                                            </div>
                                                            <div class="col-md-5 col-custom-2">
                                                                <a href="{!! HP::getFileStorage($file->url) !!}" target="_blank">
                                                                    {!! HP::FileExtension($file->filename)  ?? '' !!}
                                                                </a>
                                                                <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file->id).'/'.base64_encode('section5/application_lab_audit/lab_report/'.$applicationlabaudit->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>

                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('', !empty($application_report->created_by)?$application_report->CreatedName:auth()->user()->FullName, ['class' => 'form-control', 'disabled' => true]) !!}
                                                {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('', !empty($application_report->created_at)?HP::formatDateThaiFull($application_report->created_at):HP::formatDateThaiFull(date('Y-m-d')), ['class' => 'form-control', 'disabled' => true]) !!}
                                                {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

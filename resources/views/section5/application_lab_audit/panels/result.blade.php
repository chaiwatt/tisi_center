<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ผลตรวจประเมิน # {!! $applicationlabaudit->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body" id="box-result">
               
                    <div class="row">
                        <div class="col-md-12">
                            <div class="white-box">
                                <legend class="legend"><h5>ผลตรวจประเมิน</h5></legend>

                                @php
                                    $app_audit = $applicationlabaudit->app_audit;
                                @endphp

                                <div class="row repeater-form-date">
                                    <div class="col-md-6" data-repeater-list="repeater-date">

                                        @if( !is_null($app_audit) && !empty($app_audit->audit_date) )
                                            @php
                                                $audit_date = json_decode($app_audit->audit_date,true);
                                            @endphp
                                            @foreach ( $audit_date as $item )
                                                <div class="form-group required" data-repeater-item>
                                                    {!! Form::label('audit_date', 'วันที่เข้าตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                    <div class="col-md-7">
                                                        <div class="input-group">
                                                            {!! Form::text('audit_date', (!empty($item)? HP::revertDate($item, true) : null), ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
                                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                            {!! $errors->first('audit_date', '<p class="help-block">:message</p>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-danger btn_date_remove pull-right" data-repeater-delete type="button">
                                                            ลบ
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            @php
                                                //ลบค่าออกไปเพราะซ้ำกับอีกตาราง
                                                unset($applicationlabaudit->audit_date);
                                            @endphp
                                            <div class="form-group required" data-repeater-item>
                                                {!! Form::label('audit_date', 'วันที่นัดเข้าตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::text('audit_date', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                        {!! $errors->first('audit_date', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger btn_date_remove pull-right" data-repeater-delete type="button">
                                                        ลบ
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success pull-left" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group required{{ $errors->has('audit_result') ? 'has-error' : ''}}">
                                            {!! Form::label('audit_result', 'ผลตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('audit_result', ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], !empty($app_audit->audit_result)?$app_audit->audit_result:null, ['class' => 'form-control audit_result', 'placeholder' => '-เลือกสถานะ-', 'required' => true, 'id' => 'audit_result']) !!}
                                                {!! $errors->first('audit_result', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box_scope">
                                    <div class="box_table mb-2">

                                        @php
                                            $application_labs_scope_groups = $applicationlabaudit->app_scope_standard()->get()->keyBy('id')->groupBy('tis_id');

                                            $sql_select = "CONCAT(tb3_Tisno, ' : ', tb3_TisThainame) AS standard_title";
                                            $standards = App\Models\Basic\Tis::select('tb3_TisAutono', DB::Raw($sql_select))->whereIn('tb3_TisAutono', $application_labs_scope_groups->keys()->toArray())->pluck('standard_title', 'tb3_TisAutono')->toArray();
                                        @endphp
                                        
                                        @foreach($application_labs_scope_groups as $tis_id => $application_labs_scope_group)
                                            @include('section5/application_lab_audit.form-table', ['application_labs_scope_group' => $application_labs_scope_group, 'tis_id' => $tis_id, 'standards' => $standards])
                                        @endforeach
                                    </div>
                                </div>
                                @php
                                    $file_audit = null;
                                    if( !is_null($app_audit) ){
                                        $file_audit = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabAudit )->getTable() )
                                                                        ->where('ref_id', $app_audit->id )
                                                                        ->where('section', 'audit_file')
                                                                        ->first();
                                    }
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group {{ $errors->has('audit_file') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('audit_file', 'เอกสารการตรวจประเมิน'.' :', ['class' => 'col-md-2 control-label'])) !!}
                                            <div class="col-md-9" >
                                                @if( is_null($file_audit) )
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="audit_file">
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                                                    </div>
                                                    {!! $errors->first('activity_file', '<p class="help-block">:message</p>') !!}
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_audit->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_audit->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_audit->id).'/'.base64_encode('section5/application_lab_audit/'.$applicationlabaudit->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group {{ $errors->has('audit_remark') ? 'has-error' : ''}}">
                                            {!! Form::label('audit_remark', 'หมายเหตุ'.' :', ['class' => 'col-md-2 control-label']) !!}
                                            <div class="col-md-9">
                                                {!! Form::textarea('audit_remark', !empty($app_audit->audit_remark)?$app_audit->audit_remark:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('audit_remark', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('send_mail_status', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-2 control-label']) !!}
                                            <div class="col-md-9">
                                                <div  class="col-md-3" >
                                                    {!! Form::radio('send_mail_status', '2', !empty($app_audit->send_mail_status) && $app_audit->send_mail_status == 2 ?true:null, ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                                    <label for="">ไม่ส่งอีเมลแจ้งผล</label>
                                                </div>
                                                <div  class="col-md-2">
                                                    {!! Form::radio('send_mail_status', '1', !empty($app_audit->send_mail_status) && $app_audit->send_mail_status == 1 ?true:null, ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                                    <label for="">ส่งอีเมลแจ้งผล</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row box_noti_email">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('noti_email', !empty( $app_audit->noti_email )?implode(',',json_decode($app_audit->noti_email,true)):(!empty( $applicationlabaudit->co_email )?$applicationlabaudit->co_email:null),  ['class' => 'form-control noti_email', 'data-role' => "tagsinput", 'id'=> 'noti_email']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }

        .input_show {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }

        .input_show[disabled]{
            background-color: #FFFFFF !important;
        }
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 42px;
            border-radius: 0;
            width: 100% !important;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
        .table td.text-ellipsis {
            max-width: 177px;
        }
        .table td.text-ellipsis a {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 90%;
        }
    </style>
@endpush

    <div class="row">
        <div class="col-md-offset-1  col-md-10 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="text-center">
                        <h3 style="color: black">คำขอรับการแต่งตั้ง</h3>
                        <h3 style="color: black">ผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>
                        <h3 style="color: black">ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511 และที่แก้ไขเพิ่มเติม</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:25px"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $applicationibcb->application_no )?$applicationibcb->application_no:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->application_date)?HP::DateThaiFull($applicationibcb->application_date):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->AcceptDateFull)?$applicationibcb->AcceptDateFull:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->accept_by)?$applicationibcb->AcceptName:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<br>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationibcb->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @php
                        $applicationIbcb = $applicationibcb;
                    @endphp
                    @include ('section5/application-request-form.application-ibcb')
                </div>
            </div>
        </div>
    </div>
</div>

    @php
        $audit =  App\Models\Section5\ApplicationIbcbAudit::where('application_id', $applicationibcb->id)->first();
    @endphp

    @if((isset($applicationibcb->results) && $applicationibcb->results == true) || (isset($applicationibcb->report) && $applicationibcb->report == true) || (isset($applicationibcb->show) && $applicationibcb->show == true))

    <div class="row" id="box-result">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ผลตรวจประเมิน # {!! $applicationibcb->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        <fieldset class="white-box">
                            <legend class="legend"><h5>ผลตรวจประเมิน</h5></legend>

                            <div class="row repeater-form-date">
                                <div class="col-md-6" data-repeater-list="repeater-date">

                                    @if(  !is_null($audit) && !empty($audit->audit_date)  )
                                        @php
                                            $audit_date_json = json_decode( $audit->audit_date , true );
                                        @endphp

                                        @foreach ($audit_date_json as $itemDate )
                                            <div class="form-group required" data-repeater-item>
                                                {!! Form::label('audit_date', 'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        {!! Form::text('audit_date', !empty($itemDate)? HP::revertDate($itemDate, true):null, ['class' => 'form-control mydatepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                        {!! $errors->first('audit_date', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger btn_date_remove pull-left" data-repeater-delete type="button">
                                                        ลบ
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-group required" data-repeater-item>
                                            {!! Form::label('audit_date', 'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    {!! Form::text('audit_date', null, ['class' => 'form-control mydatepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('audit_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger btn_date_remove pull-left" data-repeater-delete type="button">
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
                                        <div class="col-md-7">
                                            {!! Form::select('audit_result', ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], !is_null($audit) && !empty($audit->audit_result)?$audit->audit_result:null , ['class' => 'form-control audit_result', 'placeholder' => '-เลือกสถานะ-', 'required' => true, 'id' => 'audit_result']) !!}
                                            {!! $errors->first('audit_result', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 box_scope">
                                    <label for="checkbox_branch_all"><input type="checkbox" id="checkbox_branch_all"  value="1"> ผ่านทุกรายสาขา</label>
                                </div>
                            </div>

                            <div class="row mb-2 box_scope">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered scope-results" id="table-scope">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5%">รายการที่</th>
                                                    <th class="text-center" width="25%">สาขาผลิตภัณฑ์</th>
                                                    <th class="text-center" width="30%">รายสาขา</th>
                                                    <th class="text-center" width="25%">มาตรฐาน มอก. เลขที่</th>
                                                    <th class="text-center" width="15%">หมายเหตุ</th>
                                                </tr>
                                            </thead>
                                            <tbody data-repeater-list="repeater-scope" class="text-left">
                                                @if( isset($applicationibcb->id) )
                                                    @php
                                                        $scope = App\Models\Section5\ApplicationIbcbScope::where('application_id', $applicationibcb->id )->get();
                                                    @endphp

                                                    @foreach ( $scope as $ks => $Iscope )
                                                        @php
                                                            $bs_branch_group = $Iscope->bs_branch_group;
                                                            $scopes_ties =  $Iscope->ibcb_scopes_tis;

                                                            $tis_details = [];
                                                            if(count($scopes_ties) > 0){
                                                                foreach ($scopes_ties as $scopes_tie) {
                                                                    $branch_title             = !empty($scopes_tie->application_ibcb_scope_detail->bs_branch->title) ? $scopes_tie->application_ibcb_scope_detail->bs_branch->title : '' ;
                                                                    $scopes_tie->branch_title = $branch_title;
                                                                    $tis_details[] = $scopes_tie;
                                                                }
                                                            }
                                                            $tis_details = json_encode($tis_details);

                                                            $scopes_details = $Iscope->scopes_details;
                                                            $remark = !empty($scopes_details->first())?$scopes_details->first()->remark:null;
                                                        @endphp
                                                        <tr>
                                                            <td class="no text-center text-top">{!! $ks+1 !!}</td>
                                                            <td class="text-top">
                                                                {!! !is_null($bs_branch_group)?$bs_branch_group->title:null !!}
                                                            </td>
                                                            <td class="text-top">
                                                                <ul class="list-unstyled">
                                                                    @foreach($scopes_details as $branchs)
                                                                        @php
                                                                            $bs_branch = $branchs->bs_branch;
                                                                        @endphp
                                                                        <li data-repeater-item>
                                                                            <input type="hidden" name="branch_group_id" value="{!! $bs_branch_group->id !!}">
                                                                            <input type="hidden" name="detail_id" value="{!! $branchs->id !!}">
                                                                            <input type="hidden" name="branch_id" value="{!! $branchs->branch_id !!}">
                                                                            <label for="checkbox_branch_{!! $branchs->branch_id !!}"><input type="checkbox" id="checkbox_branch_{!! $branchs->branch_id !!}" name="audit_result" class="audit_result_checkbox" value="1" {!! ($branchs->audit_result == 1)?'checked':'' !!}> {!! !is_null($bs_branch)?$bs_branch->title:null !!}</label>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                            <td class="text-top">
                                                                <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ $Iscope->TisNumberComma }}</a>
                                                                <input type="hidden" class="tis_details" value="{!! base64_encode($tis_details) !!}">
                                                            </td>
                                                            <td class="text-top">
                                                                {!! Form::textarea("remark[{$bs_branch_group->id}]", $remark, ['class' => 'form-control', 'rows'=>'2', 'cols' => "30"]) !!}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @php
                                $file_audit = null;
                                if( !is_null($audit) ){
                                    $file_audit = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbAudit )->getTable() )
                                                                    ->where('ref_id', $audit->id )
                                                                    ->where('section', 'file_application_ibcb_audit')
                                                                    ->first();
                                }
                            @endphp

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('audit_file') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('audit_file', 'เอกสารการตรวจประเมิน'.' :', ['class' => 'col-md-2 control-label'])) !!}
                                        <div class="col-md-6" >

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

                                                {!! $errors->first('audit_file', '<p class="help-block">:message</p>') !!}
                                            @else
                                                <a href="{!! HP::getFileStorage($file_audit->url) !!}" target="_blank">
                                                    {!! HP::FileExtension($file_audit->filename)  ?? '' !!}
                                                </a>
                                                <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_audit->id).'/'.base64_encode('section5/application-ibcb-audit/results/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('audit_remark') ? 'has-error' : ''}}">
                                        {!! Form::label('audit_remark', 'หมายเหตุ'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-6">
                                            {!! Form::textarea('audit_remark', !is_null($audit) && !empty($audit->audit_remark)?$audit->audit_remark:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                            {!! $errors->first('audit_remark', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

    @php
        $report = App\Models\Section5\ApplicationIbcbReport::where('application_id', $applicationibcb->id)->first();
    @endphp

    @if((isset($applicationibcb->report) && $applicationibcb->report == true) || (isset($applicationibcb->show) && $applicationibcb->show == true))

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    บันทึกสรุปรายงาน # {!! $applicationibcb->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">


                        <fieldset class="white-box box-readonly">
                            <legend class="legend"><h5>บันทึกสรุปรายงาน</h5></legend>

                            @php
                                $file_report = null;
                                if( !is_null($report) ){
                                    $file_report = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbReport )->getTable() )
                                                                    ->where('ref_id', $report->id )
                                                                    ->where('section', 'file_attach_report')
                                                                    ->first();
                                }
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required{{ $errors->has('file_attach_report') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('file_attach_report', 'เอกสารสรุปรายงาน'.' :', ['class' => 'col-md-4 control-label'])) !!}
                                        <div class="col-md-8" >

                                            @if( is_null($file_report) )
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
                                                {!! $errors->first('file_attach_report', '<p class="help-block">:message</p>') !!}
                                            @else
                                                <a href="{!! HP::getFileStorage($file_report->url) !!}" target="_blank">
                                                    {!! HP::FileExtension($file_report->filename)  ?? '' !!}
                                                </a>
                                                <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_report->id).'/'.base64_encode('section5/application-ibcb-audit/report/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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
                                                {!! Form::text('report_date', !is_null($report) && !empty($report->report_date)? HP::revertDate($report->report_date, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control mydatepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required' => true]) !!}
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
                                            {!! Form::select('report_by', App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id')->pluck('titels', 'id'), !is_null($report) && !empty($report->report_by)?$report->report_by:(auth()->user()->getKey()), ['class' => 'form-control', 'placeholder' => '-เลือกผู้จัดทำรายงาน-', 'required' => true]) !!}
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
                                            {!! Form::textarea('report_description', !is_null($report) && !empty($report->report_description)?$report->report_description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                            {!! $errors->first('report_description', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $file_report_other = [];
                                if( !is_null($report) ){
                                    $file_report_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbReport )->getTable() )
                                                                    ->where('ref_id', $report->id )
                                                                    ->where('section', 'file_attach_other')
                                                                    ->get();
                                }
                            @endphp

                            <div class="row box_file_report">
                                <div class="col-md-8">
                                    <div class="form-group repeater-form-file">
                                        {!! HTML::decode(Form::label('file_attach_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-8" data-repeater-list="repeater-file">
                                            <div class="row" data-repeater-item>
                                                <div class="col-md-5 col-custom-1">
                                                    {!! Form::text('caption', null, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-6 col-custom-2">
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
                                                <div class="col-md-1 col-custom-3">
                                                    <button class="btn btn-danger btn_file_remove" data-repeater-delete type="button">
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

                            @if( count($file_report_other) > 0)
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            @if( isset( $applicationibcb->approve ) && $applicationibcb->approve == true )
                                                {!! HTML::decode(Form::label('file_attach_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                            @else
                                                {!! HTML::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
                                            @endif

                                            <div class="col-md-8">
                                                @foreach (  $file_report_other  as  $file_other  )
                                                    <div class="row">
                                                        <div class="col-md-5 col-custom-1">
                                                            {!! Form::text('caption', ( !empty($file_other->caption)?$file_other->caption:null ), ['class' => 'form-control', 'disabled' => true]) !!}
                                                        </div>
                                                        <div class="col-md-5 col-custom-2">
                                                            <a href="{!! HP::getFileStorage($file_other->url) !!}" target="_blank">
                                                                {!! HP::FileExtension($file_other->filename)  ?? '' !!}
                                                            </a>
                                                            <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_other->id).'/'.base64_encode('section5/application-ibcb-audit/report/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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
                                    <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
                                        {!! Form::label('created_by', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_by', !is_null($report) && !empty($report->created_by)? $report->CreatedName:auth()->user()->Fullname,  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
                                        {!! Form::label('created_at', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_at', !is_null($report) && !empty($report->created_at)? HP::revertDate($report->created_at, true):HP::revertDate( date('Y-m-d'), true),  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>


                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

    @if((isset($applicationibcb->approve ) && $applicationibcb->approve == true) || (isset($applicationibcb->show) && $applicationibcb->show == true) )

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    พิจารณาอนุมัติ # {!! $applicationibcb->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">


                        <fieldset class="white-box">
                            <legend class="legend"><h5>พิจารณาอนุมัติ</h5></legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required{{ $errors->has('report_approve') ? 'has-error' : ''}}">
                                        {!! Form::label('report_approve', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('report_approve', App\Models\Section5\ApplicationIbcbStatus::whereIn('id',[9,10])->pluck('title', 'id') , !is_null($report) && !empty($report->report_approve)?$report->report_approve:9, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true]) !!}
                                            {!! $errors->first('report_approve', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('report_approve_description') ? 'has-error' : ''}}">
                                        {!! Form::label('report_approve_description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('report_approve_description', !is_null($report) && !empty($report->report_approve_description)?$report->report_approve_description:null,  ['class' => 'form-control', 'rows' => 3]) !!}
                                            {!! $errors->first('report_approve_description', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
                                        {!! Form::label('created_by', 'ผู้อนุมัติ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_by', !is_null($report) && !empty($report->report_approve_by)? $report->ApproveCreatedName:auth()->user()->Fullname,  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
                                        {!! Form::label('created_at', 'วันที่อนุมัติ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_at', !is_null($report) && !empty($report->report_approve_by)? HP::revertDate($report->report_approve_at, true):HP::revertDate( date('Y-m-d'), true),  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row box_remove">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('send_mail_status', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-10">
                                            <div class="col-md-3">
                                                {!! Form::radio('send_mail_status', '2',( !is_null($report) && empty($report->noti_email) ? true:( is_null($report)?true:null ) ), ['class'=>'check send_mail_status', 'id' => 'send_mail_status2', 'data-radio'=>'iradio_flat-blue']) !!}
                                                <label for="send_mail_status2">ไม่ส่งอีเมลแจ้งผล</label>
                                            </div>
                                            <div class="col-md-3">
                                                {!! Form::radio('send_mail_status', '1',( !is_null($report) && !empty($report->noti_email) ? true:( is_null($report)?false:null )), ['class'=>'check send_mail_status', 'id' => 'send_mail_status1', 'data-radio'=>'iradio_flat-blue']) !!}
                                                <label for="send_mail_status1">ส่งอีเมลแจ้งผล</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row noti_email box_remove">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('noti_email', !empty( $applicationibcb->co_email )?$applicationibcb->co_email:null,  ['class' => 'form-control', 'id'=> 'noti_email', 'disabled' => true ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>



                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn btn-primary" type="submit" name="submit_type" value="1">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>

                @if (!isset($hide_draft_btn))
                    <button class="btn btn-success" type="submit" name="submit_type" value="2">
                        <i class="fa fa-clipboard" aria-hidden="true"></i> ฉบับร่าง
                    </button>
                @endif

                @can('view-'.str_slug('application-ibcb-audit'))
                    <a class="btn btn-default show_tag_a" href="{{url('/section5/application-ibcb-audit')}}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

@include ('section5.application-ibcb-audit.modal-scope-branches-tis-details')

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    {{-- <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script> --}}
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script>
        jQuery(document).ready(function() {

            @if ( isset( $applicationibcb->approve ) && $applicationibcb->approve == true )
                $('.box_remove_adit').remove();
                $('.box-readonly').find('.show_tag_a').hide();
                $('.box-readonly').find('input').prop('disabled', true);
                $('.box-readonly').find('textarea').prop('disabled', true);
                $('.box-readonly').find('select').prop('disabled', true);
                $('.box-readonly').find('.box_file_report').hide();
            @endif

            @if ( \Session::has('success_message'))
                Swal.fire({
                    title: 'บันทึกสำเร็จ',
                    text: "คุณต้องทำรายการต่อหรือไม่ ?",
                    icon: 'success',
                    width: 500,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'กลับหน้าแรก',
                    cancelButtonText: 'ทำรายการต่อ',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '{!! url('section5/application-ibcb-audit') !!}'
                    }
                });

            @endif

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });

            BoxAuditType1();

            $('.scope-results').repeater();

            $('.repeater-form-date').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeleteDate();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteDate();
                        }, 500);
                    }


                }
            });
            BtnDeleteDate();

            $('#audit_result').change(function (e) {
                CheckBoxResule();
            });
            CheckBoxResule();

            $('.send_mail_status').on('ifChanged', function(event){
                send_mail_status();
            });
            send_mail_status();

            $('#noti_email').tagsinput({
                // itemText: 'label'
            });

            $('#checkbox_branch_all').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".audit_result_checkbox").prop('checked', true);
                } else {
                    $(".audit_result_checkbox").prop('checked',false);
                }
            });

            $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 500);
                    }
                }
            });
            BtnDeleteFile();

            $(document).on('click', '.open_scope_branches_tis_details', function(){

                $("#table_scope_branches_tis_details").DataTable().clear().destroy();

                open_scope_branches_tis_details($(this));

                $('#table_scope_branches_tis_details').DataTable({
                    searching: true,
                    autoWidth: false,
                    columnDefs: [
                        { className: "text-center col-md-1", targets: 0 },
                        { className: "col-md-2", targets: 1 },
                        { className: "col-md-7", targets: 2 },
                        { className: "col-md-2", targets: 3 },
                        { width: "10%", targets: 0 }
                    ]
                });

                $('#maodal_scope_branches_tis_details').modal('show');

            });

            // $('body').on('change.bs.fileinput', function(){
            //     var spantext = $(this).find('span.fileinput-filename');
            //     var result = spantext.text().slice(0,10);
            //     result = result + "...";
            //     spantext.text(result);
            // });
            $('input[type="file"]').change(function(){
                var file_event = this;
                setTimeout(function(){
                    var spantext = $(file_event).parent().parent().find('span.fileinput-filename');
                    var result = spantext.text().slice(0,10);
                    result = result + "...";
                    spantext.text(result);
                }, 500)

            });
        });
        function send_mail_status(){
            var noti_email = $('.noti_email');

            if($('.send_mail_status:checked').val() == 1){
                noti_email.show();
                noti_email.find('input').prop('disabled', false);
                // noti_email.find('input').prop('required', true);
            }else{
                noti_email.hide();
                noti_email.find('input').prop('disabled', true);
                // noti_email.find('input').prop('required', false);
            }
        }

        function CheckBoxResule(){
            var audit_result = $('#audit_result').val();
            var box_scope = $('.box_scope');
            if( audit_result == 1 ){

                // $('.audit_result_checkbox').prop('checked', true);
                box_scope.show();
                box_scope.find('input').prop('disabled', false);

            }else{
                $('.audit_result_checkbox').prop('checked', false);
                box_scope.hide();
                box_scope.find('input').prop('disabled', true);

                $('#checkbox_branch_all').prop('checked', false);
            }

        }

        function BtnDeleteFile(){

            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }else{
                $('.btn_file_remove').hide();
            }

        }

        function BtnDeleteDate(){

            if( $('.btn_date_remove').length >= 2 ){
                $('.btn_date_remove').show();
            }else{
                $('.btn_date_remove').hide();
            }

        }

        function BoxAuditType1(){
            var audit_type =  ($("input[name=audit_type]:checked").val() == 1 )?'1':'2';
            if( audit_type == '1' ){
                $('.box_audit_type_1').show();
                $('.box_audit_type_1').find('input').prop('disabled', false);
            }else{
                $('.box_audit_type_1').hide();
                $('.box_audit_type_1').find('input').prop('disabled', true);
            }
        }

        function open_scope_branches_tis_details(link_click) {
            let scope_branches_tis = link_click.closest('td').find('input.tis_details').val();
            $('#scope_branches_tis_details').html('');
            if(!!scope_branches_tis){
                scope_branches_tis = JSON.parse(atob(scope_branches_tis));
                let rows = '';
                $.each(scope_branches_tis, function(index, item){

                    rows += `
                        <tr>
                            <td class="text-center">${index+1}</td>
                            <td>${item.tis_no}</td>
                            <td>${item.tis_name}</td>
                            <td>${item.branch_title}</td>
                        </tr>
                    `;
                });
                $('#scope_branches_tis_details').append(rows);
            }
        }

    </script>
@endpush

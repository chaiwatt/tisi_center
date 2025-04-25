@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
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
    </style>
@endpush

<fieldset class="white-box">

    <div class="clearfix" style="margin-top:50px"></div>

    <div class="row">
        <div class="col-md-offset-1  col-md-10 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="text-center">
                        <h3 style="color: black">คำขอรับการขึ้นทะเบียน</h3>
                        <h3 style="color: black">ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>
                        <h3 style="color: black">ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511 และที่แก้ไขเพิ่มเติม</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:25px"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right" >เลขที่คำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty( $application_inspectors->application_no )?$application_inspectors->application_no:null !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($application_inspectors->application_date)?HP::DateThaiFull($application_inspectors->application_date):null !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($application_inspectors->accept_date) ? HP::DateThaiFull($application_inspectors->accept_date) : '-' !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($application_inspectors->accept_by) && !is_null($application_inspectors->accepter) ? $application_inspectors->accepter->FullName : '-' !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:10px"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ข้อมูลคำขอ # {!! $application_inspectors->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body" id="box-request">
                        @include ('section5.application-request-form.application-inspectors')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $audit = App\Models\Section5\ApplicationInspectorAudit::where('application_id', $application_inspectors->id )->first();

        $scope_group = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id )->select('branch_group_id')->groupBy('branch_group_id')->get();
    @endphp

    @if( isset( $application_inspectors->checkings ) && $application_inspectors->checkings == true  )

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        ข้อมูลผลตรวจประเมิน # {!! $application_inspectors->application_no !!}
                        <div class="pull-right">
                            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                        </div>
                    </div>
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body" id="box-result">

                            <fieldset class="scheduler-border" id="box-readonly">
                                <legend class="scheduler-border"><h5>ผลตรวจประเมิน</h5></legend>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group required">
                                            {!! Form::label('audit_date', 'วันที่ตรวจประเมิน', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    {!! Form::text('audit_date', !is_null($audit) && !empty($audit->audit_date)? HP::revertDate($audit->audit_date, true):HP::revertDate( date('Y-m-d'), true)  ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('audit_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group required{{ $errors->has('audit_result') ? 'has-error' : ''}}">
                                            {!! Form::label('audit_result', 'ผลตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('audit_result', ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'],  !is_null($audit) && !empty($audit->audit_result)?$audit->audit_result:null, ['class' => 'form-control audit_result', 'placeholder' => '-เลือกสถานะ-', 'required' => true, 'id' => 'audit_result']) !!}
                                                {!! $errors->first('audit_result', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row box_scope">
                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="myTableScope">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">รายการ</th>
                                                    <th width="20%" class="text-center">สาขา</th>
                                                    <th width="20%" class="text-center">รายสาขา</th>
                                                    <th width="20%" class="text-center">มาตรฐาน มอก. เลขที่</th>
                                                    <th width="25%" class="text-center">หมายเหตุ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $scope_group as $key => $group )
                                                    @php
                                                        $bs_branch_group = $group->bs_branch_group;
                                                    @endphp
                                                    <tr class="repeater-scope">
                                                        <td class="text-center">{!! $key+1 !!}</td>
                                                        <td class="text-top">
                                                            {!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}
                                                        </td>
                                                        <td class="text-top">
                                                            @php
                                                                $scope = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id )->where('branch_group_id', $group->branch_group_id )->get();
                                                                $remark = !empty($scope->first())?$scope->first()->remark:null;
                                                                $scopes_ties = App\Models\Section5\ApplicationInspectorScopeTis::whereIn('inspector_scope_id', $scope->pluck('id') )->get();
                                                            @endphp


                                                            <ul class="list-group list-unstyled" data-repeater-list="repeater-group-{!!  $group->branch_group_id !!}">

                                                                @foreach ( $scope as $scopes )
                                                                    @php
                                                                        $bs_branch = $scopes->bs_branch;
                                                                    @endphp

                                                                    <li data-repeater-item>
                                                                        <input type="hidden" name="scope_id" value="{!!  $scopes->id !!}">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check" type="checkbox" value="1" id="audit_result-{!!  $scopes->id !!}" name="audit_result" {!! $scopes->audit_result == 1?'checked':''   !!} >
                                                                            <label class="form-check-label" for="audit_result-{!!  $scopes->id !!}">
                                                                                {!! !empty( $bs_branch->title )? $bs_branch->title:null !!}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="text-top">
                                                            <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ !empty($scopes_ties)?implode(', ', $scopes_ties->pluck('tis_no')->toArray()):'-' }}</a>
                                                            @foreach($scopes_ties as $scopes_tis)
                                                                @php
                                                                    $branch_title = !empty($scopes_tis->application_inspector_scope->bs_branch->title) ? $scopes_tis->application_inspector_scope->bs_branch->title : '' ;
                                                                @endphp
                                                                <input type="hidden" value="{!! $scopes_tis->tis_name !!}" data-tis_no="{!! $scopes_tis->tis_no !!}" data-branch_title="{!! $branch_title !!}" class="tis_details" disabled>
                                                            @endforeach
                                                        </td>
                                                        <td class="text-top">
                                                            {!! Form::textarea("remark[{$group->branch_group_id}]", $remark, ['class' => 'form-control', 'rows'=>'2', 'cols' => "30"]) !!}
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @php
                                    $file_audit = null;
                                    if( !is_null($audit) ){
                                        $file_audit = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationInspectorAudit )->getTable() )
                                                                        ->where('ref_id', $audit->id )
                                                                        ->where('section', 'file_application_inspectors_audit')
                                                                        ->first();
                                    }
                                @endphp

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group{{ $errors->has('audit_file') ? 'has-error' : ''}}">
                                            {!! Form::label('audit_file', 'เอกสารการตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">

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
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_audit->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_audit->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_audit->id).'/'.base64_encode('section5/application-inspectors-audit/checkings/'.$application_inspectors->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group {{ $errors->has('audit_remark') ? 'has-error' : ''}}">
                                            {!! Form::label('audit_remark', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('audit_remark', !is_null($audit) && !empty($audit->audit_remark)?$audit->audit_remark:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('audit_remark', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row box_noti_email box_remove_adit">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            {!! Form::label('noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('noti_email', null, ['class' => 'form-control', 'id' => 'noti_email', 'disabled' => true]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row box_remove_adit">
                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-6">

                                            @can('edit-'.str_slug('application-inspectors-audit'))
                                                <button class="btn btn-primary show_tag_a" type="submit" name="submit_type" value="1">
                                                    <i class="fa fa-paper-plane"></i> บันทึก
                                                </button>
                                                <button class="btn btn-success show_tag_a" type="submit" name="submit_type" value="2">
                                                    <i class="fa fa-clipboard" aria-hidden="true"></i> ฉบับร่าง
                                                </button>
                                            @endcan
                                            <a class="btn btn-default show_tag_a" href="{{url('/section5/application-inspectors-audit')}}">
                                                <i class="fa fa-rotate-left"></i> ยกเลิก
                                            </a>
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

    @if( (isset( $application_inspectors->approve ) && $application_inspectors->approve == true) || ( isset( $application_inspectors->show ) && $application_inspectors->show == true && in_array($application_inspectors->application_status,[5,6,7,8,9,10])) )

        @php
            $audit_result_arr =  ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'];

            $file_audit = null;
            if( !is_null($audit) ){
                $file_audit = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationInspectorAudit )->getTable() )
                                            ->where('ref_id', $audit->id )
                                            ->where('section', 'file_application_inspectors_audit')
                                            ->first();
            }
            $scope_group_result = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id)
                                                                               ->select('branch_group_id')
                                                                               ->groupBy('branch_group_id')
                                                                               ->get();

        @endphp

        <div class="panel panel-info">
            <div class="panel-heading">
                ผลตรวจประเมิน # {!! $application_inspectors->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <fieldset class="scheduler-border" id="box-readonly">
                        <legend class="scheduler-border"><h5>ผลตรวจประเมิน</h5></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('audit_date', 'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        <label class="control-label"> {!! !is_null($audit) && !empty($audit->audit_date)? HP::revertDate($audit->audit_date, true):'-'  !!} </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('audit_result', 'ผลตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        <label class="control-label"> {!! !is_null($audit) && !empty($audit->audit_result)  && array_key_exists( $audit->audit_result ,  $audit_result_arr ) ?$audit_result_arr[$audit->audit_result]:'-'  !!} </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('audit_file', 'เอกสารการตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        @if(!empty($file_audit))
                                            <label class="control-label">
                                                <a href="{!! HP::getFileStorage($file_audit->url) !!}" target="_blank">
                                                    {!! HP::FileExtension($file_audit->filename)  ?? '' !!}
                                                </a>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('audit_date', 'ผู้ที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        <label class="control-label"> {!! !is_null($audit) && !empty($audit->created_by)? $audit->CreatedName:'-'  !!} </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('audit_date', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        <label class="control-label"> {!! !is_null($audit) && !empty($audit->created_at)? HP::revertDate($audit->created_at, true):'-'  !!} </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h4>สาขา/รายสาขาที่ผ่านการประเมิน</h4>

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <table class="table table-bordered" id="myTableScope">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">รายการ</th>
                                            <th width="20%" class="text-center">สาขา</th>
                                            <th width="20%" class="text-center">รายสาขา</th>
                                            <th width="20%" class="text-center">มาตรฐาน มอก. เลขที่</th>
                                            <th width="25%" class="text-center">หมายเหตุ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($scope_group_result as $key => $group)
                                            @php
                                                $bs_branch_group = $group->bs_branch_group;
                                            @endphp

                                            <tr>
                                                <td class="text-center text-top">{!! $key+1 !!}</td>
                                                <td class="text-top">
                                                    {!! !empty($bs_branch_group->title) ? $bs_branch_group->title:null !!}
                                                </td>
                                                <td class="text-top">
                                                    @php
                                                        $scope_result = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id)
                                                                                                                     ->where('branch_group_id', $group->branch_group_id)
                                                                                                                     ->get();

                                                        $scopes_ties = App\Models\Section5\ApplicationInspectorScopeTis::whereIn('inspector_scope_id', $scope_result->pluck('id') )->get();

                                                    @endphp
                                                    <ul class="list-group list-unstyled">

                                                        @foreach ($scope_result as $scopes)
                                                            @php
                                                                $bs_branch = $scopes->bs_branch;
                                                            @endphp
                                                            <li>
                                                                <div class="form-check">
                                                                    <div class="state icheckbox_flat-blue {!! $scopes->audit_result == 1 ? 'checked' : '' !!}"></div>
                                                                    {!! !empty($bs_branch->title) ? $bs_branch->title:null !!}
                                                                </div>
                                                            </li>

                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-top">
                                                    <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ !empty($scopes_ties)?implode(', ', $scopes_ties->pluck('tis_no')->toArray()):'-' }}</a>
                                                    @foreach($scopes_ties as $scopes_tis)
                                                        @php
                                                            $branch_title = !empty($scopes_tis->application_inspector_scope->bs_branch->title) ? $scopes_tis->application_inspector_scope->bs_branch->title : '' ;
                                                        @endphp
                                                        <input type="hidden" value="{!! $scopes_tis->tis_name !!}" data-tis_no="{!! $scopes_tis->tis_no !!}" data-branch_title="{!! $branch_title !!}" class="tis_details" disabled>
                                                    @endforeach
                                                </td>
                                                <td class="text-top">
                                                    @php
                                                        $scope = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id)->where('branch_group_id', $group->branch_group_id)->first();
                                                    @endphp
                                                    {{ !is_null($scope) ? $scope->remark : null }}
                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </fieldset>

                </div>
            </div>
        </div>

    @endif

    @if( ( isset($application_inspectors->approve ) && $application_inspectors->approve == true) || ( isset( $application_inspectors->show ) && $application_inspectors->show == true &&  in_array($application_inspectors->application_status,[7,8,9,10]) ))

        <div class="panel panel-info">
            <div class="panel-heading">
                ผลพิจารณาอนุมัติ # {!! $application_inspectors->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">

                    <fieldset class="scheduler-border" id="box-approve">
                        <legend class="scheduler-border"><h5>พิจารณาอนุมัติ</h5></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('audit_approve') ? 'has-error' : ''}}">
                                    {!! Form::label('audit_approve', 'ผลตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('audit_approve',  App\Models\Section5\ApplicationInspectorStatus::whereIn('id', [7,8] )->pluck('title', 'id')->all() ,  !is_null($audit) && !empty($audit->audit_approve)?$audit->audit_approve:null, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                                        {!! $errors->first('audit_approve', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('audit_approve_description') ? 'has-error' : ''}}">
                                    {!! Form::label('audit_approve_description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('audit_approve_description', !is_null($audit) && !empty($audit->audit_approve_description)?$audit->audit_approve_description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                        {!! $errors->first('audit_approve_description', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row box_approve_email_status">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('send_mail_status', ' ', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-6 row">
                                        <label class="col-md-6">
                                            {!! Form::radio('approve_send_mail_status', '0', !is_null( $audit ) &&  $audit->approve_send_mail_status == 0 ?true:( empty( $accepts->approve_send_mail_status )?true:false ), ['class'=>'check approve_send_mail_status', 'data-radio'=>'iradio_flat-blue', 'id' => 'approve_send_mail_status0']) !!}
                                            <label for="approve_send_mail_status0">ไม่ส่งอีเมลแจ้งผล</label>
                                        </label>
                                        <label class="col-md-6">
                                            {!! Form::radio('approve_send_mail_status', '1', !is_null( $audit ) &&  $audit->approve_send_mail_status == 1 ?true:false , ['class'=>'check approve_send_mail_status', 'data-radio'=>'iradio_flat-blue', 'id' => 'approve_send_mail_status1']) !!}
                                            <label for="approve_send_mail_status1">ส่งอีเมลแจ้งผล</label>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row box_approve_noti_email">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('approve_noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('approve_noti_email', !empty( $audit->approve_noti_email )?implode(',',json_decode($audit->approve_noti_email,true)):(!empty( $application_inspectors->applicant_email )?$application_inspectors->applicant_email:null) ,  ['class' => 'form-control approve_noti_email', 'data-role' => "tagsinput", 'disabled' => true ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('audit_approve_by') ? 'has-error' : ''}}">
                                    {!! Form::label('audit_approve_by', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('audit_approve_by', !is_null($audit) && !empty($audit->audit_approve_by)?$audit->AuditCreatedName:auth()->user()->Fullname,  ['class' => 'form-control',  'disabled' => true]) !!}
                                        {!! $errors->first('audit_approve_by', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('audit_approve_at') ? 'has-error' : ''}}">
                                    {!! Form::label('audit_approve_at', 'วันทึกบันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('audit_approve_at', !is_null($audit) && !empty($audit->audit_approve_at)?HP::revertDate( $audit->audit_approve_at, true):HP::revertDate( date('Y-m-d'), true),  ['class' => 'form-control',  'disabled' => true]) !!}
                                        {!! $errors->first('audit_approve_at', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">

                                    @can('edit-'.str_slug('application-inspectors-audit'))
                                        <button class="btn btn-primary show_tag_a" type="submit">
                                            <i class="fa fa-paper-plane"></i> บันทึก
                                        </button>
                                    @endcan
                                    <a class="btn btn-default show_tag_a" href="{{url('/section5/application-inspectors-audit')}}">
                                        <i class="fa fa-rotate-left"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                </div>
            </div>
        </div>

    @endif

</fieldset>

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
  <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

  <!-- input file -->
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script>
    jQuery(document).ready(function() {

        @if ( $application_inspectors->approve == true )
            $('.box_remove_adit').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true);
            $('#box-readonly').find('select').prop('disabled', true);
        @endif


        $('#box-request').find('button[type="submit"]').remove();
        $('#box-request').find('.icon-close').parent().remove();
        $('#box-request').find('.fa-copy').parent().remove();
        $('#box-request').find('input').prop('disabled', true);
        $('#box-request').find('textarea').prop('disabled', true);
        $('#box-request').find('select').prop('disabled', true);
        $('#box-request').find('.bootstrap-tagsinput').prop('disabled', true);
        $('#box-request').find('span.tag').children('span[data-role="remove"]').remove();
        $('#box-request').find('button').prop('disabled', true);
        $('#box-request').find('button').remove();
        $('#box-request').find('.btn-remove-file').parent().remove();
        $('#box-request').find('.show_tag_a').hide();
        $('#box-request').find('.input_show_file').hide();
        $('#box-request').find('input').prop('required', false);
        $('#box-request').find('textarea').prop('required', false);
        $('#box-request').find('.icon-calender').closest('.input-group').addClass('form-group').removeClass('input-group');
        $('#box-request').find('.icon-calender').closest('.input-group-addon').remove();

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
                    window.location = '{!! url('section5/application-inspectors-audit') !!}'
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

        $('.repeater-scope').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });

        $('.audit_result').change(function(){
            box_scope();
        });
        box_scope();

        $('.send_mail_status').on('ifChanged', function(event){
            send_mail_status();
        });
        send_mail_status();

        $('#noti_email').tagsinput({
            // itemText: 'label'
        });

        $('.approve_send_mail_status').on('ifChanged', function(event){
            approve_send_mail_status();
        });
        approve_send_mail_status();

        $('#approve_noti_email').tagsinput({
            // itemText: 'label'
        });

        $('#audit_approve').on('change', function () {
            Box_status_mail();
        });
        Box_status_mail();

    });

    function Box_status_mail(){
        var box_scope = $('.box_approve_email_status');
        if($('#audit_approve').val() == 8){
            box_scope.show();
        }else{
            box_scope.hide();
        }
    }

    function box_scope(){
        if($('#audit_result').val() == 1){
            var box_scope = $('.box_scope');
            box_scope.show();
            box_scope.find('input, select, textarea').prop('disabled', false);
        }else{
            var box_scope = $('.box_scope');
            box_scope.hide();
            box_scope.find('input, select, textarea').prop('disabled', true);
        }
    }

    function send_mail_status(){

        if($('.send_mail_status:checked').val() == 1){//ส่งเมล
            var noti_email = $('.box_noti_email');
            noti_email.show();
            noti_email.find('input').prop('disabled', false);
            $('#noti_email').tagsinput('add', '{{ (!empty($application_inspectors->applicant_email) ? $application_inspectors->applicant_email : null) }}');//defaault เมลที่จะส่งแจ้งเตือน
        }else{//ไม่ส่งเมล
            var noti_email = $('.box_noti_email');
            noti_email.hide();
            noti_email.find('input').prop('disabled', true);
            $('#noti_email').tagsinput('removeAll');
        }
    }

    function approve_send_mail_status(){
        if($('.approve_send_mail_status:checked').val() == 1){//ส่งเมล
            var noti_email = $('.box_approve_noti_email');
            noti_email.show();
            noti_email.find('input').prop('disabled', false);
            $('#approve_noti_email').tagsinput('add', '{{ (!empty($application_inspectors->applicant_email) ? $application_inspectors->applicant_email : null) }}');//defaault เมลที่จะส่งแจ้งเตือน
        }else{//ไม่ส่งเมล
            var noti_email = $('.box_approve_noti_email');
            noti_email.hide();
            noti_email.find('input').prop('disabled', true);
            $('#approve_noti_email').tagsinput('removeAll');
        }
    }

  </script>
@endpush

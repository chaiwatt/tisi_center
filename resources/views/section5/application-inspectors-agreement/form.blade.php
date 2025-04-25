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
        $inspectors = $application_inspectors->section5_inspectors;
        $agreement = $application_inspectors->inspector_agreement;
    @endphp

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ข้อมูลเอกสารผู้ตรวจ/ผู้ประเมิน # {!! $application_inspectors->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        <fieldset class="scheduler-border" id="box-document">
                            <legend class="scheduler-border"><h5>บันทึกเอกสารผู้ตรวจ/ผู้ประเมิน</h5></legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('audit_date', 'ชื่อผู้ตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <label class="control-label" style="text-align: left;"> {!! !empty($application_inspectors->applicant_full_name)?$application_inspectors->applicant_full_name:'-'  !!} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('audit_result', 'ชื่อหน่วยงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <label class="control-label" style="text-align: left;"> {!! !empty($application_inspectors->agency_name)?$application_inspectors->agency_name:'-'  !!} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('audit_result', 'ที่อยู่หน่วยงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <label class="control-label" style="text-align: left;"> {!! !empty($application_inspectors->AgencyDataAdress)?$application_inspectors->AgencyDataAdress:'-'  !!} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>สาขา/รายสาขาที่ขึ้นทะเบียน</h4>

                            @php
                                $scope_group_result = App\Models\Section5\InspectorsScope::where('inspectors_id', @$inspectors->id )->where('ref_inspector_application_no', @$application_inspectors->application_no )->select('branch_group_id')->groupBy('branch_group_id')->get();
                            @endphp

                            <div class="row">
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

                                            @foreach ( $scope_group_result as $key => $group )
                                                @php
                                                    $bs_branch_group = $group->bs_branch_group;
                                                @endphp

                                                <tr>
                                                    <td class="text-center text-top">{!! $key+1 !!}</td>
                                                    <td class="text-top">
                                                        {!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}
                                                    </td>
                                                    <td class="text-top">
                                                        @php
                                                            $scope_result = App\Models\Section5\InspectorsScope::where('inspectors_id', $inspectors->id )->where('ref_inspector_application_no', $application_inspectors->application_no )->where('branch_group_id', $group->branch_group_id )->get();
                                                            $scopes_ties = App\Models\Section5\InspectorsScopeTis::whereIn('inspector_scope_id', $scope_result->pluck('id') )->get();                                                
                                                        @endphp
                                                        <div class="col-md-12" >
                                                            <ul class="list-group list-inline-item">

                                                                @foreach ( $scope_result as $scopes )
                                                                    @php
                                                                        $bs_branch = $scopes->bs_branch;
                                                                    @endphp
                                                                    <li>
                                                                        {!! !empty( $bs_branch->title )? $bs_branch->title:null !!}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="text-top">
                                                        <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ !empty($scopes_ties)?implode(', ', $scopes_ties->pluck('tis_no')->toArray()):'-' }}</a>
                                                        @foreach($scopes_ties as $scopes_tis)
                                                            @php
                                                                $branch_title = !empty($scopes_tis->inspector_scope->bs_branch->title) ? $scopes_tis->inspector_scope->bs_branch->title : '' ;
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

                            <hr>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group required">
                                        {!! Form::label('start_date', 'มีผลตั้งแต่วันที่', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                {!! Form::text('start_date', !is_null($agreement) && !empty($agreement->start_date)? HP::revertDate($agreement->start_date, true):null  ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group required">
                                        {!! Form::label('end_date', 'สิ้นสุดวันที่', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                {!! Form::text('end_date', !is_null($agreement) && !empty($agreement->end_date)? HP::revertDate($agreement->end_date, true):null  ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group required">
                                        {!! Form::label('first_date', 'วันที่ขึ้นทะเบียนครั้งแรก', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                {!! Form::text('first_date', !empty($application_inspectors->FirstRegistrationDate)?$application_inspectors->FirstRegistrationDate:null,  ['class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                {!! $errors->first('first_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
                                        {!! Form::label('created_by', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_by', !is_null($agreement) && !empty($agreement->created_by)? $agreement->CreatedName:auth()->user()->Fullname,  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
                                        {!! Form::label('created_at', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('created_at', !is_null($agreement) && !empty($agreement->created_at)? HP::revertDate($agreement->created_at, true):HP::revertDate( date('Y-m-d'), true),  ['class' => 'form-control',  'disabled' => true]) !!}
                                            {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row box_remove_adit">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4">

                                        @can('edit-'.str_slug('application-inspectors-agreement'))
                                            <button class="btn btn-primary show_tag_a" type="submit">
                                                <i class="fa fa-paper-plane"></i> บันทึก
                                            </button>
                                        @endcan
                                        <a class="btn btn-default show_tag_a" href="{{url('/section5/application-inspectors-agreement')}}">
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

    @if( isset( $application_inspectors->attach ) && $application_inspectors->attach == true )

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        ข้อมูลไฟล์แนบเอกสารขึ้นทะเบียนผู้ตรวจ/ผู้ประเมิน # {!! $application_inspectors->application_no !!}
                        <div class="pull-right">
                            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                        </div>
                    </div>
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                            <fieldset class="scheduler-border" >
                                <legend class="scheduler-border"><h5>แนบไฟล์เอกสารขึ้นทะเบียนผู้ตรวจ/ผู้ประเมิน</h5></legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group required{{ $errors->has('agreement_status') ? 'has-error' : ''}}">
                                            {!! Form::label('agreement_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('agreement_status',  [ 1 => 'ออกเอกสารแล้ว', 2 => 'แนบไฟล์เอกสารแล้ว' ] ,  !is_null($agreement) && !empty($agreement->agreement_status)?$agreement->agreement_status:null, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                                                {!! $errors->first('agreement_status', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $file_attach_document = null;
                                    if( !is_null($agreement) ){
                                        $file_attach_document = App\AttachFile::where('ref_table', (new App\Models\Section5\InspectorsAgreement )->getTable() )
                                                                        ->where('ref_id', $agreement->id )
                                                                        ->where('section', 'file_attach_document')
                                                                        ->first();
                                    }
                                @endphp

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group required{{ $errors->has('attach_document') ? 'has-error' : ''}}">
                                            {!! Form::label('attach_document', 'เอกสารขึ้นทะเบียน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">

                                                @if( is_null($file_attach_document) )
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="attach_document" required>
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                                                    </div>
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_attach_document->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_attach_document->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_attach_document->id).'/'.base64_encode('section5/application-inspectors-agreement/attach_document/'.$application_inspectors->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                                            {!! Form::label('description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('description', !is_null($agreement) && !empty($agreement->description)?$agreement->description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
                                            {!! Form::label('created_by', 'ผู้บันทึกเอกสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('created_by', !is_null($agreement) && !empty($agreement->file_created_by)? $agreement->FileCreatedName:auth()->user()->Fullname,  ['class' => 'form-control',  'disabled' => true]) !!}
                                                {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
                                            {!! Form::label('created_at', 'วันที่บันทึกเอกสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('created_at', !is_null($agreement) && !empty($agreement->file_created_at)? HP::revertDate($agreement->file_created_at, true):HP::revertDate( date('Y-m-d'), true),  ['class' => 'form-control',  'disabled' => true]) !!}
                                                {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-4">

                                            @can('edit-'.str_slug('application-inspectors-agreement'))
                                                <button class="btn btn-primary show_tag_a" type="submit">
                                                    <i class="fa fa-paper-plane"></i> บันทึก
                                                </button>
                                            @endcan
                                            <a class="btn btn-default show_tag_a" href="{{url('/section5/application-inspectors-agreement')}}">
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
                    window.location = '{!! url('section5/application-inspectors-agreement') !!}'
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

        $('#start_date').change(function (e) {
            
            var date = $(this).val();

            @if ( empty($application_inspectors->section5_inspectors->inspector_first_date) )
                $('#first_date').val(date);
            @endif

            if( /^\d{2}\/\d{2}\/\d{4}$/i.test( date ) ) {

                var parts = date.split("/");

                var day = parts[0] && parseInt( parts[0], 10 );
                var month = parts[1] && parseInt( parts[1], 10 );
                var year = parts[2] && parseInt( parts[2], 10 );
                var duration = parseInt( 5, 10 );

                if( day <= 31 && day >= 1 && month <= 12 && month >= 1 ) {

                    var expiryDate = new Date( year, month - 1, day );
                    expiryDate.setDate( expiryDate.getDate() - 1 );
                    expiryDate.setFullYear( expiryDate.getFullYear() + duration );
                 
                    var day = ( '0' + expiryDate.getDate() ).slice( -2 );
                    var month = ( '0' + ( expiryDate.getMonth() + 1 ) ).slice( -2 );
                    var year = expiryDate.getFullYear();

                    $("#end_date").val( day + "/" + month + "/" + year );

                } else {
                    console.log('ERROR');
                }

            }
        });

    });

    $('body').on('change.bs.fileinput', function(){
        var spantext = $(this).find('span.fileinput-filename');
        var result = spantext.text().slice(0,24);
        result = result + "...";
        spantext.text(result);
    });

  </script>
@endpush

@push('css')
    <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .form-body input[type="text"]:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
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

<div class="form-body">

    <fieldset class="white-box">
        <legend class="legend"><h5>1.ข้อมูลผู้ยื่นคำขอ</h5></legend>

        <div class="row">
            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('authorized_name') ? 'has-error' : ''}}">
                            {!! Form::label('authorized_name', 'ชื่อ - สกุล', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('authorized_name', !empty( $application_inspectors->applicant_full_name )?$application_inspectors->applicant_full_name:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_taxid', 'เลขประจำตัวประชาชน', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_taxid', !empty( $application_inspectors->applicant_taxid )?$application_inspectors->applicant_taxid:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_taxid', 'วัน/เดือน/ปี เกิด', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_taxid', !empty( $application_inspectors->applicant_date_of_birth )?HP::revertDate($application_inspectors->applicant_date_of_birth, true):null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_position') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_position', 'ตำแหน่ง', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_position', !empty( $application_inspectors->applicant_position )?$application_inspectors->applicant_position:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_phone') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_phone', 'โทรศัพท์', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_phone', !empty( $application_inspectors->applicant_phone )?$application_inspectors->applicant_phone:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_fax') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_fax', 'แฟกซ์', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_fax', !empty( $application_inspectors->applicant_fax )?$application_inspectors->applicant_fax:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_mobile') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_mobile', 'มือถือ', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_mobile', !empty( $application_inspectors->applicant_mobile )?$application_inspectors->applicant_mobile:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_email') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_email', 'E-mail', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('applicant_email', !empty( $application_inspectors->applicant_email )?$application_inspectors->applicant_email:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 col-sm-12 text-right">
                        <h4>หน่วยงาน</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_name') ? 'has-error' : ''}}">
                            {!! Form::label('agency_name', 'ชื่อหน่วยงาน', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_name', !empty( $application_inspectors->agency_name )?$application_inspectors->agency_name:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_taxid') ? 'has-error' : ''}}">
                            {!! Form::label('agency_taxid', 'เลขประจำตัวผู้เสียภาษีอากร', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-7">
                                {!! Form::text('agency_taxid', !empty( $application_inspectors->agency_taxid )?$application_inspectors->agency_taxid:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 col-sm-12 text-right">
                        <h4>ที่ตั้งหน่วยงาน</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_address') ? 'has-error' : ''}}">
                            {!! Form::label('agency_address', 'ที่ตั้งเลขที่', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_address', !empty( $application_inspectors->agency_address )?$application_inspectors->agency_address:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_moo') ? 'has-error' : ''}}">
                            {!! Form::label('agency_moo', 'หมู่ที่', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_moo', !empty( $application_inspectors->agency_moo )?$application_inspectors->agency_moo:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_soi') ? 'has-error' : ''}}">
                            {!! Form::label('agency_soi', 'ตรอก/ซอย', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_soi', !empty( $application_inspectors->agency_soi )?$application_inspectors->agency_soi:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_road') ? 'has-error' : ''}}">
                            {!! Form::label('agency_road', 'ถนน', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_road', !empty( $application_inspectors->agency_road )?$application_inspectors->agency_road:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('AgencySubdistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('AgencySubdistrictName', 'ตำบล/แขวง', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('AgencySubdistrictName', !empty( $application_inspectors->AgencySubdistrictName )?$application_inspectors->AgencySubdistrictName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('AgencyDistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('AgencyDistrictName', 'อำเภอ/เขต', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('AgencyDistrictName', !empty( $application_inspectors->AgencyDistrictName )?$application_inspectors->AgencyDistrictName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('AgencyProvinceName') ? 'has-error' : ''}}">
                            {!! Form::label('AgencyProvinceName', 'จังหวัด', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('AgencyProvinceName', !empty( $application_inspectors->AgencyProvinceName )?$application_inspectors->AgencyProvinceName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('agency_zipcode') ? 'has-error' : ''}}">
                            {!! Form::label('agency_zipcode', 'รหัสไปรษณีย์', ['class' => 'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('agency_zipcode', !empty( $application_inspectors->agency_zipcode )?$application_inspectors->agency_zipcode:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>2.ข้อมูลขอรับบริการ</h5></legend>
        <p style="text-indent:45px">
            ยื่นคำขอต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรม เพื่อรับการขึ้นทะเบียนเป็นผู้ตรวจ และ ผู้ประเมินของผู้สอบการทำผลิตภัณฑ์อุตสาหกรรม<br>
            ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. 2511 และที่แก้ไขเพิ่มเติม ในหมวดอุตสาหกรรม ต่อไปนี้
        </p>

        @php
            $branch_groups = App\Models\Basic\BranchGroup::whereIn('id', App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id)->select('branch_group_id') )->pluck('title','category')->toArray();
            $branch_scopes = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id)
                                                        ->leftjoin((new App\Models\Basic\Branch)->getTable().' AS branch', 'branch.id', '=', 'section5_application_inspectors_scope.branch_id')
                                                        ->selectRaw('section5_application_inspectors_scope.*, branch.title as branch_title')
                                                        ->get()
                                                        ->keyBy('id')
                                                        ->groupBy('branch_group_id')
                                                        ->toArray();
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table-bordered table table-hover primary-table" id="table-branch">
                            <thead>
                                <tr>
                                    <th width="7%" class="text-center">รายการที่</th>
                                    <th width="7%" class="text-center">หมวด</th>
                                    <th width="27%" class="text-center">สาขา</th>
                                    <th width="30%" class="text-center">รายสาขา</th>
                                    <th width="29%" class="text-center">มาตรฐาน มอก. เลขที่</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-branch" id="box_list_branch">
                                @if(!empty($branch_scopes) && count($branch_scopes) > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach($branch_scopes as $key => $scope_groups)

                                        @php
                                            //มอก.ที่ถูกเลือก
                                            $branch_group_ids = collect($scope_groups)->pluck('branch_group_id');
                                            $inspector_scope_ids = App\Models\Section5\ApplicationInspectorScope::whereIn('branch_group_id', $branch_group_ids)->where('application_id', $application_inspectors->id)->select('id');
                                            $scopes_ties = App\Models\Section5\ApplicationInspectorScopeTis::whereIn('inspector_scope_id', $inspector_scope_ids)->get();
                                        @endphp
                                        <tr>
                                            <td class="text-center branch_no text-top">{{ (++$i).'.' }}</td>
                                            <td class="text-top">
                                                {{ 'หมวด '.$key }}
                                            </td>
                                            <td class="text-top">
                                                {{ array_key_exists($key, $branch_groups)?$branch_groups[$key]:null }}
                                            </td>

                                            <td class="text-top">
                                                @php
                                                    $arr = [];
                                                @endphp
                                                @foreach($scope_groups as $k=>$scope)
                                                    @php
                                                        $arr[] =  $scope['branch_title'];
                                                    @endphp
                                                @endforeach
                                                {{ implode(', ', $arr) }}
                                            </td>
                                            <td class="text-ellipsis">
                                                <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ !empty($scopes_ties)?implode(', ', $scopes_ties->pluck('tis_no')->toArray()):'-' }}</a>
                                                @foreach($scopes_ties as $scopes_tis)
                                                    @php
                                                        $branch_title = !empty($scopes_tis->application_inspector_scope->bs_branch->title) ? $scopes_tis->application_inspector_scope->bs_branch->title : '' ;
                                                    @endphp
                                                    <input type="hidden" value="{!! $scopes_tis->tis_name !!}" data-tis_no="{!! $scopes_tis->tis_no !!}" data-branch_title="{!! $branch_title !!}" class="tis_details">
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>3.หลักฐานประกอบการพิจารณา</h5></legend>
        @php
            $app_configs_evidences = !empty($application_inspectors->configs_evidence)?json_decode($application_inspectors->configs_evidence):[];
        @endphp

        <div class="row">
            <div class="col-md-12">

                @foreach($app_configs_evidences as $key=>$app_configs_evidence)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-7">
                                    <h5>{!!  (!empty($app_configs_evidence->title)?$app_configs_evidence->title:null) !!}</h5>
                                </div>
                                @php
                                    $attachment_educational = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationInspector )->getTable() )
                                                                            ->where('tax_number', $application_inspectors->applicant_taxid)
                                                                            ->where('ref_id', $application_inspectors->id )
                                                                            ->when($app_configs_evidence->id, function ($query, $setting_file_id){
                                                                                return $query->where('setting_file_id', $setting_file_id);
                                                                            })->first();
                                @endphp

                                @if( !empty($attachment_educational) )
                                    <div class="col-md-4" >
                                        <a href="{!! HP::getFileStorage($attachment_educational->url) !!}" target="_blank">
                                            {!! HP::FileExtension($attachment_educational->filename)  ?? '' !!}
                                        </a>
                                    </div>
                                    <div class="col-md-1" >

                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @php
                    $file_other = [];
                    if( isset($application_inspectors->id) ){
                        $file_other = App\AttachFile::where('section', 'evidence_file_other')->where('ref_table', (new App\Models\Section5\ApplicationInspector )->getTable() )->where('ref_id', $application_inspectors->id )->get();
                    }
                @endphp

                @foreach ( $file_other as $attach )

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-7">
                                    {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                                </div>
                                <div class="col-md-4">
                                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                        {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>

    </fieldset>

</div>

@include('section5/application-request-form/modals/scope-branches-tis-details')

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script>

        $(document).ready(function() {

            //คลิกลิงค์มาตรฐานมอก.
            $(document).on('click', '.open_scope_branches_tis_details', function(){

                $("#table_scope_branches_tis_details").DataTable().clear().destroy();

                open_scope_branches_tis_details($(this));

                $('#table_scope_branches_tis_details').DataTable({
                    searching: true,
                    autoWidth: false,
                    columnDefs: [
                        { className: "text-center col-md-1", targets: 0 },
                        { className: "col-md-9", targets: 1 },
                        { className: "col-md-2", targets: 2 },
                        { width: "10%", targets: 0 }
                    ]
                });

                $('#maodal_scope_branches_tis_details').modal('show');

            });

        });

        function open_scope_branches_tis_details(link_click) {
            let scope_branches_tis = link_click.closest('td').find('input.tis_details');
            $('#scope_branches_tis_details').html('');
            let rows = '';
            scope_branches_tis.each(function(index, item){
                rows += `
                    <tr>
                        <td class="text-center">${index+1}</td>
                        <td class="">${$(item).data('tis_no')} : ${$(item).val()}</td>
                        <td class="">${$(item).data('branch_title')}</td>
                    </tr>
                `;
            });
            $('#scope_branches_tis_details').append(rows);
        }

    </script>
@endpush

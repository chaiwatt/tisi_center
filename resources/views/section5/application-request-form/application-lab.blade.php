@push('css')
    <style>
        .form-body input[type="text"]:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
        }
    </style>
@endpush

<div class="form-body">

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('applicant_type', 'ประเภทคำขอ', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            <label>{!! Form::radio('applicant_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_flat-blue', 'id' => 'applicant_type_1', 'required' => false, 'disabled' => true]) !!} ขอขึ้นทะเบียนใหม่</label>
                            <label>{!! Form::radio('applicant_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_flat-blue', 'id' => 'applicant_type_2', 'required' => false, 'disabled' => true]) !!} ขอเพิ่มเติมขอบข่าย</label>
                            {{-- <label>{!! Form::radio('applicant_type', '3', false, ['class'=>'check', 'data-radio'=>'iradio_flat-blue', 'id' => 'applicant_type_3']) !!} ขอลดขอบข่าย</label>
                            <label>{!! Form::radio('applicant_type', '4', false, ['class'=>'check', 'data-radio'=>'iradio_flat-blue', 'id' => 'applicant_type_4']) !!} ขอแก้ไขข้อมูล</label> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        @if( !empty($applicationlab->lab_id) )
                            @php
                                $option_lab = App\Models\Section5\Labs::where('id', $applicationlab->lab_id )->select(DB::raw("CONCAT_WS(' : ', lab_code, lab_name) AS lab_title"), 'id')->pluck('lab_title', 'id')->toArray();
                            @endphp
                            {!! Form::label('lab_id', 'ห้องปฏิบัติการ', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_id', !empty( $applicationlab->lab_id ) && array_key_exists( $applicationlab->lab_id,  $option_lab  )? $option_lab[$applicationlab->lab_id]:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <fieldset class="white-box">
        <legend class="legend"><h5>1.ข้อมูลผู้ยื่นคำขอ</h5></legend>
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('authorized_name') ? 'has-error' : ''}}">
                            {!! Form::label('authorized_name', 'ชื่อนิติบุคคล', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('authorized_name', !empty( $applicationlab->applicant_name )?$applicationlab->applicant_name:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_taxid', 'เลขประจำตัวผู้เสียภาษีอากร', ['class' => 'control-label col-md-5']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_taxid', !empty( $applicationlab->applicant_taxid )?$applicationlab->applicant_taxid:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                            {!! Form::label('applicant_taxid', 'วันเกิด/วันที่จดทะเบียนนิติบุคคล', ['class' => 'control-label col-md-5']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_taxid', !empty( $applicationlab->applicant_date_niti )?HP::revertDate($applicationlab->applicant_date_niti, true):null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <h5 class="font-18"><b>ข้อมูลที่ตั้งสำนักงานใหญ่</b></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_address') ? 'has-error' : ''}}">
                            {!! Form::label('hq_address', 'เลขที่', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_address', !empty( $applicationlab->hq_address )?$applicationlab->hq_address:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_building') ? 'has-error' : ''}}">
                            {!! Form::label('hq_building', 'อาคาร/หมู่บ้าน', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_building', !empty( $applicationlab->hq_building )?$applicationlab->hq_building:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_soi') ? 'has-error' : ''}}">
                            {!! Form::label('hq_soi', 'ตรอก/ซอย', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_soi', !empty( $applicationlab->hq_soi )?$applicationlab->hq_soi:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_moo') ? 'has-error' : ''}}">
                            {!! Form::label('hq_moo', 'หมู่ที่', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_moo', !empty( $applicationlab->hq_moo )?$applicationlab->hq_moo:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_road') ? 'has-error' : ''}}">
                            {!! Form::label('hq_road', 'ถนน', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_road', !empty( $applicationlab->hq_road )?$applicationlab->hq_road:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('HqSubDistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('HqSubDistrictName', 'ตำบล/แขวง', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('HqSubDistrictName', !empty( $applicationlab->HqSubDistrictName )?$applicationlab->HqSubDistrictName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('HqDistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('HqDistrictName', 'อำเภอ/เขต', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('HqDistrictName', !empty( $applicationlab->HqDistrictName )?$applicationlab->HqDistrictName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('HqProvinceName') ? 'has-error' : ''}}">
                            {!! Form::label('HqProvinceName', 'จังหวัด', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('HqProvinceName', !empty( $applicationlab->HqProvinceName )?$applicationlab->HqProvinceName:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('hq_zipcode') ? 'has-error' : ''}}">
                            {!! Form::label('hq_zipcode', 'รหัสไปรษณีย์', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('hq_zipcode', !empty( $applicationlab->hq_zipcode )?$applicationlab->hq_zipcode:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <h5 class="font-18"><b>ข้อมูลห้องปฏิบัติการ</b></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('lab_name') ? 'has-error' : ''}}">
                            {!! Form::label('lab_name', 'ชื่อห้องปฏิบัติการขอรับการแต่งตั้ง', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_name', !empty( $applicationlab->lab_name )?$applicationlab->lab_name:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <h5 class="font-18"><b>ข้อมูลที่ตั้งห้องปฏิบัติการ</b></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_address') ? 'has-error' : ''}}">
                            {!! Form::label('lab_address', 'เลขที่', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_address', !empty( $applicationlab->lab_address )?$applicationlab->lab_address:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_building') ? 'has-error' : ''}}">
                            {!! Form::label('lab_building', 'อาคาร/หมู่บ้าน', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_building', !empty( $applicationlab->lab_building )?$applicationlab->lab_building:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_moo') ? 'has-error' : ''}}">
                            {!! Form::label('lab_moo', 'หมู่ที่', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_moo', !empty( $applicationlab->lab_moo )?$applicationlab->lab_moo:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_soi') ? 'has-error' : ''}}">
                            {!! Form::label('lab_soi', 'ตรอก/ซอย', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_soi', !empty( $applicationlab->lab_soi )?$applicationlab->lab_soi:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_road') ? 'has-error' : ''}}">
                            {!! Form::label('lab_road', 'ถนน', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_road', !empty( $applicationlab->lab_road )?$applicationlab->lab_road:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('LabSubdistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('LabSubdistrictName', 'ตำบล/แขวง', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('LabSubdistrictName', !empty( $applicationlab->LabSubdistrictName )?$applicationlab->LabSubdistrictName:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('LabDistrictName') ? 'has-error' : ''}}">
                            {!! Form::label('LabDistrictName', 'อำเภอ/เขต', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('LabDistrictName', !empty( $applicationlab->LabDistrictName )?$applicationlab->LabDistrictName:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('LabProvinceName') ? 'has-error' : ''}}">
                            {!! Form::label('LabProvinceName', 'จังหวัด', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('LabProvinceName', !empty( $applicationlab->LabProvinceName )?$applicationlab->LabProvinceName:null,  ['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_zipcode') ? 'has-error' : ''}}">
                            {!! Form::label('lab_zipcode', 'รหัสไปรษณีย์', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_zipcode', !empty( $applicationlab->lab_zipcode )?$applicationlab->lab_zipcode:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_phone') ? 'has-error' : ''}}">
                            {!! Form::label('lab_phone', 'เบอร์โทรศัพท์', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_phone', !empty( $applicationlab->lab_phone )?$applicationlab->lab_phone:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('lab_fax') ? 'has-error' : ''}}">
                            {!! Form::label('lab_fax', 'เบอร์โทรสาร', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('lab_fax', !empty( $applicationlab->lab_fax )?$applicationlab->lab_fax:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-11 col-md-offset-1">
                        <h5 class="font-18"><b>ข้อมูลผู้ประสานงาน</b></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_name') ? 'has-error' : ''}}">
                            {!! Form::label('co_name', 'ชื่อผู้ประสานงาน', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_name', !empty( $applicationlab->co_name )?$applicationlab->co_name:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_position') ? 'has-error' : ''}}">
                            {!! Form::label('co_position', 'ตำแหน่ง', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_position', !empty( $applicationlab->co_position )?$applicationlab->co_position:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_mobile') ? 'has-error' : ''}}">
                            {!! Form::label('co_mobile', 'โทรศัพท์มือถือ', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_mobile', !empty( $applicationlab->co_mobile )?$applicationlab->co_mobile:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_phone') ? 'has-error' : ''}}">
                            {!! Form::label('co_phone', 'โทรศัพท์', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_phone', !empty( $applicationlab->co_phone )?$applicationlab->co_phone:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_fax') ? 'has-error' : ''}}">
                            {!! Form::label('co_fax', 'โทรสาร', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_fax', !empty( $applicationlab->co_fax )?$applicationlab->co_fax:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('co_email') ? 'has-error' : ''}}">
                            {!! Form::label('co_email', 'E-mail', ['class' => 'control-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('co_email', !empty( $applicationlab->co_email )?$applicationlab->co_email:null,['class' => 'form-control ', 'required' => false, 'disabled' => true ]) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>2.ข้อมูลขอรับบริการ</h5></legend>

        <div class="row">
            <div class="col-md-11">
                <p style="text-indent: 6rem;">ยื่นคำขอต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรมเพื่อรับการแต่งตั้งเป็นผู้ตรวจสอบผลิตภัณฑ์อุตสาหกรรม ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม ดังนี้</p>
            </div>
        </div>

        @php
            $application_labs_scope = App\Models\Section5\ApplicationLabScope::where('application_lab_id', $applicationlab->id)
                                                                                ->with(['test_item' => function ($q){
                                                                                    $orderby  = "CAST(SUBSTRING_INDEX(no,'.',1) as UNSIGNED),";
                                                                                    $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',2),'.',-1) as UNSIGNED),";
                                                                                    $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',3),'.',-1) as UNSIGNED),";
                                                                                    $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',4),'.',-1) as UNSIGNED),";
                                                                                    $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',5),'.',-1) as UNSIGNED)";
                                                                                    $q->orderBy(DB::raw( $orderby ));
                                                                                }]);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

            $sql_select = "CONCAT(tb3_Tisno, ' : ', tb3_TisThainame) AS standard_title";

            $standards  = App\Models\Basic\Tis::select('tb3_TisAutono', DB::Raw($sql_select))->whereIn('tb3_TisAutono', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'tb3_TisAutono')->toArray();
        @endphp

        @foreach($application_labs_scope_groups as $tis_id => $application_labs_scope_group)

            <div class="row">
                <div class="col-md-12">
                    <p>รายการทดสอบ ตามมาตรฐานเลขที่ <u>{{ array_key_exists($tis_id, $standards)?$standards[$tis_id]:null }}</u></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table color-bordered-table info-bordered-table">
                        <thead>
                            <tr>
                                <th width="7%" class="text-center">#</th>
                                <th width="10%" class="text-center align-top">รายการทดสอบ</th>
                                <th width="15%" class="text-center align-top">เครื่องมือที่ใช้</th>
                                <th width="10%" class="text-center">รหัส/หมายเลข</th>
                                <th width="15%" class="text-center">ขีดความสามารถ</th>
                                <th width="10%" class="text-center">ช่วงการ<br>ใช้งาน</th>
                                <th width="10%" class="text-center">ความละเอียดที่อ่านได้</th>
                                <th width="10%" class="text-center">ความคลาดเคลื่อนที่ยอมรับ</th>
                                <th width="10%" class="text-center">ระยะการทดสอบ(วัน)</th>
                                <th width="10%" class="text-center">ค่าใช้จ่ายในการทดสอบ/ชุดละ</th>
                            </tr>
                        </thead>
                        <tbody id="table-body-{{ $tis_id }}">
                            @foreach ($application_labs_scope_group as $key=>$application_labs_scope)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{!! $application_labs_scope->TestItemFullName !!}</td>
                                    <td>{{ $application_labs_scope->TestToolTitle }}</td>
                                    <td>{{ $application_labs_scope->test_tools_no }}</td>
                                    <td>{{ $application_labs_scope->capacity }}</td>
                                    <td>{{ $application_labs_scope->range }}</td>
                                    <td>{{ $application_labs_scope->true_value }}</td>
                                    <td>{{ $application_labs_scope->fault_value }}</td>
                                    <td>{{ $application_labs_scope->test_duration }}</td>
                                    <td>{{ $application_labs_scope->test_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endforeach

        <div class="row">
            <div class="col-md-12">

                <div class="form-group {{ $errors->has('audit_type') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('audit_type', 'ได้รับใบรับรองระบบงานตามมาตรฐาน 17025', ['class' => 'col-md-3 control-label'])) !!}
                    <div class="col-md-2">
                        {!! Form::radio('audit_type', '1', null, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'audit_type-1', 'required' => false, 'disabled' => true]) !!}
                        {!! Html::decode(Form::label('audit_type-1', 'ได้รับ พร้อมแนบหลักฐาน', ['class' => 'control-label text-capitalize'])) !!}
                    </div>
                    <div class="col-md-7">
                        {!! Form::radio('audit_type', '2', true, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'audit_type-2', 'required' => false, 'disabled' => true]) !!}
                        {!! Form::label('audit_type-2', 'ไม่ได้รับทำการตรวจประเมินตาม  ภาคผนวก ก.', ['class' => 'control-label text-capitalize']) !!}
                    </div>
                </div>

            </div>
        </div>

        @if($applicationlab->audit_type == 1)

            @if(  isset($applicationlab->id) && count( App\Models\Section5\ApplicationLabCertificate::where('application_lab_id', $applicationlab->id )->get() ) > 0 )

                @php
                    $list_cer = App\Models\Section5\ApplicationLabCertificate::where('application_lab_id', $applicationlab->id )->get();
                @endphp

                @foreach ( $list_cer as $keyC => $cer )

                    @if($keyC >= 1)
                        <hr>              
                    @endif

                    <div class="row">
                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                {!! Form::label('cer_input_cer_no', 'ใบรับรองเลขที่', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('cer_input_cer_no', !empty($cer->certificate_no)?$cer->certificate_no:null ,  ['class' => 'form-control', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('cer_input_start_date', 'วันที่ได้รับ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('cer_input_start_date', !empty($cer->certificate_start_date)?HP::revertDate($cer->certificate_start_date):null ,  ['class' => 'form-control mydatepicker', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('cer_input_end_date', 'ถึง', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('cer_input_end_date', !empty($cer->certificate_end_date)?HP::revertDate($cer->certificate_end_date):null  ,  ['class' => 'form-control mydatepicker', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                {!! Form::label('cer_input_accereditatio_no', 'หมายเลขการรับรอง', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('cer_input_accereditatio_no', !empty($certificate_export->accereditatio_no)?$certificate_export->accereditatio_no:(!empty($cer->accereditatio_no)?$cer->accereditatio_no:null) ,  ['class' => 'form-control', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('cer_input_file', 'ไฟล์ใบรับรอง', ['class' => 'col-md-5 control-label']) !!}
                                <div class="col-md-7">
                                    @if( !empty($cer->certificate_file) && empty($cer->certificate_id) )
                                        <a href="{!! HP::getFileStorage($cer->certificate_file->url) !!}" target="_blank">
                                            {!! HP::FileExtension($cer->certificate_file->filename)  ?? '' !!}
                                        </a>
                                    @elseif( !empty($cer->certificate_id)  )
                                        <a href="{!! url('/api/v1/certificate?cer='.(!empty($cer->certificate_no)?$cer->certificate_no:null)) !!}"  target="_blank"><span class="text-info"><i class="fa fa-file"></i></span></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   
                @endforeach     

            @else

                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <div class="form-group">
                            {!! Form::label('checking_date', 'ใบรับรองเลขที่', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('checking_date', null ,  ['class' => 'form-control', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('checking_date', 'วันที่ได้รับ', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::text('checking_date', null ,  ['class' => 'form-control mydatepicker', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('checking_date', 'ถึง', ['class' => 'col-md-2 control-label']) !!}
                            <div class="col-md-10">
                                {!! Form::text('checking_date', null ,  ['class' => 'form-control mydatepicker', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <div class="form-group">
                            {!! Form::label('cer_input_accereditatio_no', 'หมายเลขการรับรอง', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('cer_input_accereditatio_no', null ,  ['class' => 'form-control', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('cer_input_file', 'ไฟล์ใบรับรอง', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                </div>

            @endif

        @endif

        @if($applicationlab->audit_type == 2)
            @if(  isset($applicationlab->id) && !empty($applicationlab->audit_date) )

                @php
                    $audit_date = json_decode($applicationlab->audit_date);
                @endphp

                @foreach (  $audit_date as $audit_dates )

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('co_email', 'ช่วงวันที่พร้อมให้เข้าตรวจประเมิน', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                <div class="input-daterange input-group date-range">
                                    <div>
                                        {!! Form::text('audit_date_start',  !empty($audit_dates->audit_date_start)?HP::revertDate($audit_dates->audit_date_start):null,  ['class' => 'form-control audit_date_start','placeholder'=>"dd/mm/yyyy", 'disabled' => true]) !!}
                                    </div>
                                    <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
                                    <div >
                                        {!! Form::text('audit_date_end',  !empty($audit_dates->audit_date_end)?HP::revertDate($audit_dates->audit_date_end):null, ['class' => 'form-control audit_date_end','placeholder'=>"dd/mm/yyyy", 'disabled' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            @else
                <div class="row">
                    <div class="form-group">
                        {!! Form::label('co_email', 'ช่วงวันที่พร้อมให้เข้าตรวจประเมิน', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            <div class="input-daterange input-group date-range">
                                <div>
                                    {!! Form::text('audit_date_start',  null,  ['class' => 'form-control audit_date_start','placeholder'=>"dd/mm/yyyy", 'disabled' => true]) !!}
                                </div>
                                <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
                                <div>
                                    {!! Form::text('audit_date_end',  null, ['class' => 'form-control audit_date_end','placeholder'=>"dd/mm/yyyy", 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        @endif

    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>3. เอกสารแนบ</h5></legend>

        @php

            if( isset($applicationlab->id) && !empty($applicationlab->config_evidencce) ){
                $configs_evidences = json_decode($applicationlab->config_evidencce);
            }else{
                $configs_evidences = DB::table((new App\Models\Config\ConfigsEvidence)->getTable().' AS evidences')
                                        ->leftjoin((new App\Models\Config\ConfigsEvidenceGroup)->getTable().' AS groups', 'groups.id', '=', 'evidences.evidence_group_id')
                                        ->where('groups.id', 3)
                                        ->where('evidences.state', 1)
                                        ->select('evidences.*')
                                        ->orderBy('evidences.ordering')
                                        ->get();

            }

        @endphp

        @foreach ( $configs_evidences as $evidences )
            @php
                $attachment = null;

                if( isset($applicationlab->id) ){
                    $attachment = App\AttachFile::where('ref_table', (new App\Models\Section5\Applicationlab )->getTable() )
                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                    ->where('ref_id', $applicationlab->id )
                                    ->when($evidences->id, function ($query, $setting_file_id){
                                        return $query->where('setting_file_id', $setting_file_id);
                                    })
                                    ->first();
                }

            @endphp

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group @if($evidences->required == 1) required @endif">
                        {!! HTML::decode(Form::label('file_attachment_educational', (!empty($evidences->title)?$evidences->title:null).' : ', ['class' => 'col-md-6 control-label'])) !!}

                        @if( is_null($attachment) )
                            <div class="col-md-4">
                                <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true" ></i></a>
                            </div>
                        @else
                            <div class="col-md-4" >
                                <a href="{!! HP::getFileStorage($attachment->url) !!}" target="_blank">
                                    {!! HP::FileExtension($attachment->filename)  ?? '' !!}
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

        <div class="row">
            <div class="col-md-12">

                @php
                    $file_other = [];
                    if( isset($applicationlab->id) ){
                        $file_other = App\AttachFile::where('section', 'evidence_file_other')->where('ref_table', (new App\Models\Section5\Applicationlab )->getTable() )->where('ref_id', $applicationlab->id )->get();
                    }
                @endphp

                @foreach ( $file_other as $attach )

                    <div class="form-group">
                        {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.' : ', ['class' => 'col-md-6 control-label'])) !!}
                        <div class="col-md-3">
                            {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                        </div>
                        <div class="col-md-2">
                            <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                {!! HP::FileExtension($attach->filename)  ?? '' !!}
                            </a>
                        </div>
                        <div class="col-md-1" >

                        </div>
                    </div>

                @endforeach

            </div>
        </div>

    </fieldset>

</div>

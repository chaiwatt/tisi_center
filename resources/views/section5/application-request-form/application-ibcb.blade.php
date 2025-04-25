@push('css')
    <style>
        .{
            text-align: left !important;
        }

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
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <div class="form-group {{ $errors->has('application_type') ? 'has-error' : ''}}">
                {!! Form::label('application_type', 'ประเภทหน่วยตรวจสอบ', ['class' => 'col-md-4 control-label ']) !!}
                <div class="col-md-8">
                    <label>{!! Form::radio('application_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'application_type_1']) !!} IB</label>
                    {!! str_repeat("&nbsp;",13) !!}
                    <label>{!! Form::radio('application_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'application_type_2']) !!} CB</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <div class="form-group {{ $errors->has('audit_type') ? 'has-error' : ''}}">
                {!! Form::label('audit_type', 'ได้รับใบรับรองระบบงาน', ['class' => 'col-md-4 control-label ']) !!}
                <div class="col-md-8">
                    <label>{!! Form::radio('audit_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'audit_type_1']) !!} ได้รับ มีหลักฐานแนบ</label>
                    {!! str_repeat("&nbsp;",13) !!}
                    <label>{!! Form::radio('audit_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'audit_type_2']) !!} ไม่ได้รับ ทำการตรวจประเมินตาม ภาคผนวก ก.</label>
                </div>
            </div>
        </div>
    </div>

    <fieldset class="white-box">
        <legend class="legend"><h5>ข้อมูลผู้ยื่นคำขอ</h5></legend>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('applicant_name') ? 'has-error' : ''}}">
                    {!! Form::label('applicant_name', 'ผู้ยื่นคำขอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('applicant_name', !empty( $applicationIbcb->applicant_name )?$applicationIbcb->applicant_name:null,['class' => 'form-control input_show',  ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                    {!! Form::label('applicant_taxid', 'เลขประจำตัวผู้เสียภาษีอากร'.' :', ['class' => 'col-md-5 control-label']) !!}
                    <div class="col-md-7">
                        {!! Form::text('applicant_taxid', !empty( $applicationIbcb->applicant_taxid )?$applicationIbcb->applicant_taxid:null,  ['class' => 'form-control input_show',  ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('applicant_date_niti') ? 'has-error' : ''}}">
                    {!! Form::label('applicant_date_niti', 'วันที่จดทะเบียนนิติบุคคล'.' :', ['class' => 'col-md-5 control-label ']) !!}
                    <div class="col-md-7">
                        {!! Form::text('applicant_date_niti_show', !empty($applicationIbcb->applicant_date_niti) ? HP::formatDateThaiFull($applicationIbcb->applicant_date_niti) : null, ['class' => 'form-control input_show']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ที่อยู่สำนักงานใหญ่</h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('hq_address') ? 'has-error' : ''}}">
                    {!! Form::label('hq_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_address', !empty( $applicationIbcb->hq_address )?$applicationIbcb->hq_address:null,['class' => 'form-control']) !!}
                        {!! $errors->first('hq_address', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_building') ? 'has-error' : ''}}">
                    {!! Form::label('hq_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_building', !empty( $applicationIbcb->hq_building )?$applicationIbcb->hq_building:null,  ['class' => 'form-control']) !!}
                        {!! $errors->first('hq_building', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_soi') ? 'has-error' : ''}}">
                    {!! Form::label('hq_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_soi', !empty( $applicationIbcb->hq_soi )?$applicationIbcb->hq_soi:null,  ['class' => 'form-control']) !!}
                        {!! $errors->first('hq_soi', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_moo') ? 'has-error' : ''}}">
                    {!! Form::label('hq_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_moo', !empty( $applicationIbcb->hq_moo )?$applicationIbcb->hq_moo:null,['class' => 'form-control']) !!}
                        {!! $errors->first('hq_moo', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_road') ? 'has-error' : ''}}">
                    {!! Form::label('hq_road', 'ถนน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_road', !empty( $applicationIbcb->hq_road )?$applicationIbcb->hq_road:null,['class' => 'form-control']) !!}
                        {!! $errors->first('hq_road', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_subdistrict_txt') ? 'has-error' : ''}}">
                    {!! Form::label('hq_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_subdistrict_txt', !empty( $applicationIbcb->HQSubdistrictName )?$applicationIbcb->HQSubdistrictName:null,  ['class' => 'form-control hq_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('hq_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_district_txt') ? 'has-error' : ''}}">
                    {!! Form::label('hq_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_district_txt', !empty( $applicationIbcb->HQDistrictName )?$applicationIbcb->HQDistrictName:null,['class' => 'form-control hq_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('hq_district_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_province_txt') ? 'has-error' : ''}}">
                    {!! Form::label('hq_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_province_txt', !empty( $applicationIbcb->HQProvinceName )?$applicationIbcb->HQProvinceName:null,  ['class' => 'form-control hq_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('hq_province_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('hq_zipcode_txt') ? 'has-error' : ''}}">
                    {!! Form::label('hq_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('hq_zipcode_txt', !empty( $applicationIbcb->HQPostcodeName )?$applicationIbcb->HQPostcodeName:null,['class' => 'form-control hq_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('hq_zipcode_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ที่ตั้งหน่วยงาน</h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('ibcb_name') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_name', 'ชื่อหน่วยตรวจสอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_name', !empty( $applicationIbcb->ibcb_name )?$applicationIbcb->ibcb_name:null,['class' => 'form-control']) !!}
                        {!! $errors->first('ibcb_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_address') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_address', !empty( $applicationIbcb->ibcb_address )?$applicationIbcb->ibcb_address:null,['class' => 'form-control', 'required' => true ]) !!}
                        {!! $errors->first('ibcb_address', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('ibcb_building') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_building', !empty( $applicationIbcb->ibcb_building )?$applicationIbcb->ibcb_building:null,  ['class' => 'form-control', ]) !!}
                        {!! $errors->first('ibcb_building', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('ibcb_soi') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_soi', !empty( $applicationIbcb->ibcb_soi )?$applicationIbcb->ibcb_soi:null,  ['class' => 'form-control' ]) !!}
                        {!! $errors->first('ibcb_soi', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('ibcb_moo') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_moo', !empty( $applicationIbcb->ibcb_moo )?$applicationIbcb->ibcb_moo:null,['class' => 'form-control']) !!}
                        {!! $errors->first('ibcb_moo', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_subdistrict_txt') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_subdistrict_txt', !empty( $applicationIbcb->IbcbSubdistrictName )?$applicationIbcb->IbcbSubdistrictName:null,  ['class' => 'form-control ibcb_input_show',  ]) !!}
                        {!! $errors->first('ibcb_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_district_txt') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_district_txt', !empty( $applicationIbcb->IbcbDistrictName )?$applicationIbcb->IbcbDistrictName:null,['class' => 'form-control ibcb_input_show',  ]) !!}
                        {!! $errors->first('ibcb_district_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_province_txt') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_province_txt', !empty( $applicationIbcb->IbcbProvinceName )?$applicationIbcb->IbcbProvinceName:null,  ['class' => 'form-control ibcb_input_show',  ]) !!}
                        {!! $errors->first('ibcb_province_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_zipcode_txt') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_zipcode_txt', !empty( $applicationIbcb->IbcbPostcodeName )?$applicationIbcb->IbcbPostcodeName:null,['class' => 'form-control ibcb_input_show',   ]) !!}
                        {!! $errors->first('ibcb_zipcode_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('ibcb_phone') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_phone', 'เบอร์โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_phone', !empty( $applicationIbcb->ibcb_phone )?$applicationIbcb->ibcb_phone:null,['class' => 'form-control', 'required' => true ]) !!}
                        {!! $errors->first('ibcb_phone', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('ibcb_fax') ? 'has-error' : ''}}">
                    {!! Form::label('ibcb_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('ibcb_fax', !empty( $applicationIbcb->ibcb_fax )?$applicationIbcb->ibcb_fax:null,  ['class' => 'form-control', ]) !!}
                        {!! $errors->first('ibcb_fax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ผู้ประสานงาน</h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('co_name') ? 'has-error' : ''}}">
                    {!! Form::label('co_name', 'ชื่อผู้ประสานงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('co_name', !empty( $applicationIbcb->co_name )?$applicationIbcb->co_name:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                        {!! $errors->first('co_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required {{ $errors->has('co_position') ? 'has-error' : ''}}">
                    {!! Form::label('co_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('co_position', !empty( $applicationIbcb->co_position )?$applicationIbcb->co_position:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
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
                        {!! Form::text('co_mobile', !empty( $applicationIbcb->co_mobile )?$applicationIbcb->co_mobile:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                        {!! $errors->first('co_mobile', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('co_tel') ? 'has-error' : ''}}">
                    {!! Form::label('co_tel', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('co_tel', !empty( $applicationIbcb->co_tel )?$applicationIbcb->co_tel:null,  ['class' => 'form-control co_input_show' ]) !!}
                        {!! $errors->first('co_tel', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('co_fax') ? 'has-error' : ''}}">
                    {!! Form::label('co_fax', 'โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('co_fax', !empty( $applicationIbcb->co_fax )?$applicationIbcb->co_fax:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                        {!! $errors->first('co_fax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('co_email') ? 'has-error' : ''}}">
                    {!! Form::label('co_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('co_email', !empty( $applicationIbcb->co_email )?$applicationIbcb->co_email:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
                        {!! $errors->first('co_email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>


    </fieldset>

    @if( $applicationIbcb->audit_type == 1 )
        <fieldset class="white-box box_audit_type_1">
            <legend class="legend"><h5>ใบรับรองระบบงานตามมาตรฐาน</h5></legend>

            <div class="row">
                <div class="col-md-12">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-certificate">
                            <thead>
                                <tr>
                                    <th class="text-center" width="35%">มอก.</th>
                                    <th class="text-center" width="25%">ใบรับรองเลขที่</th>
                                    <th class="text-center" width="20%">วันที่ออก</th>
                                    <th class="text-center" width="20%">วันที่หมด</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">

                                @if( isset($applicationIbcb->id) )

                                    @php
                                        $certify = App\Models\Section5\ApplicationIbcbCertify::where('application_id', $applicationIbcb->id )->get();
                                    @endphp

                                    @foreach ( $certify as $Icertify )
                                        @php
                                            $tis_standard = $Icertify->tis_standard;
                                        @endphp

                                        <tr>
                                            <td>
                                                {!! !empty($tis_standard->title)?$tis_standard->title:null !!}
                                            </td>
                                            <td>
                                                @if( !empty($Icertify->certificate_id) )
                                                    <a href="{!! url('/api/v1/certificate?cer='.(!empty($Icertify->certificate_no)?$Icertify->certificate_no:null)) !!}"  target="_blank"><span class="text-info">   {!! !empty($Icertify->certificate_no)?$Icertify->certificate_no:null !!}</span></a>
                                                @else
                                                    {!! $Icertify->certificate_no !!}
                                                @endif
                                            </td>
                                            <td>
                                                {!! !empty($Icertify->certificate_start_date)?HP::revertDate($Icertify->certificate_start_date):null !!}
                                            </td>
                                            <td>
                                                {!! !empty($Icertify->certificate_end_date)?HP::revertDate($Icertify->certificate_end_date):null !!}
                                            </td>
                                        </tr>

                                    @endforeach

                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </fieldset>
    @endif


    <fieldset class="white-box">
        <legend class="legend"><h5>ข้อมูลขอรับบริการ</h5></legend>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-1"></label>
                    <div class="col-md-11">
                        <p style="text-indent">ยื่นคำขอต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรม เพื่อรับการแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ตามมาตรา 5
                            แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. 2511 และที่แก้ไขเพิ่มเติมขอบข่ายที่ขอรับการแต่งตั้ง คือ</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-scope">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">รายการที่</th>
                                <th class="text-center" width="30%">สาขาผลิตภัณฑ์</th>
                                <th class="text-center" width="30%">รายสาขา</th>
                                <th class="text-center" width="15%">ISIC NO</th>
                                <th class="text-center" width="20%">มาตรฐาน มอก. เลขที่</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @if( isset($applicationIbcb->id) )
                                @php
                                    $scope = App\Models\Section5\ApplicationIbcbScope::where('application_id', $applicationIbcb->id )->get();
                                @endphp

                                @foreach ( $scope as $ks => $Iscope )
                                    @php
                                        $bs_branch_group = $Iscope->bs_branch_group;
                                        $scopes_ties = $Iscope->ibcb_scopes_tis;

                                        $tis_details = [];
                                        if(count($scopes_ties) > 0){
                                            foreach ($scopes_ties as $scopes_tie) {
                                                $branch_title             = !empty($scopes_tie->application_ibcb_scope_detail->bs_branch->title) ? $scopes_tie->application_ibcb_scope_detail->bs_branch->title : '' ;
                                                $scopes_tie->branch_title = $branch_title;
                                                $tis_details[] = $scopes_tie;
                                            }
                                        }

                                        $tis_details = json_encode($tis_details);
                                        $scopes_details =  $Iscope->scopes_details()->select('branch_id')->groupBy('branch_id')->pluck('branch_id')->toArray();
                                    @endphp
                                    <tr data-repeater-item>
                                        <td class="no text-center text-top">{!! $ks+1 !!}</td>
                                        <td class="text-top">
                                            {!! !is_null($bs_branch_group)?$bs_branch_group->title:null !!}
                                            <input type="hidden" class="branch_group_id" name="branch_group_id" value="{!! !empty($Iscope->branch_group_id)?$Iscope->branch_group_id:null  !!}">
                                            <input type="hidden" name="scope_id" value="{!! !empty($Iscope->id)?$Iscope->id:null  !!}">
                                        </td>
                                        <td class="text-top">
                                            {!! !empty($Iscope->ScopeBranchs)?$Iscope->ScopeBranchs:null  !!}
                                        </td>
                                        <td class="text-top">
                                            {!! !empty($Iscope->isic_no)?$Iscope->isic_no:'-'  !!}
                                        </td>
                                        <td class="text-ellipsis text-top">
                                            <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ $Iscope->TisNumberComma }}</a>
                                            <input type="hidden" class="tis_details" value="{!!  base64_encode($tis_details)  !!}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>รายชื่อผู้ตรวจที่ผ่านการแต่งตั้ง</h5></legend>

        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <button class="btn btn-primary show_tag_a" type="button" id="btn_modal_inspectors"><i class="fa fa-search"></i> ค้นหาผู้ตรวจ</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-inspectors">
                        <thead>
                            <tr>
                                <th class="text-center">ลำดับที่</th>
                                <th width="20%" class="text-center">ชื่อ-นามสกุล</th>
                                <th width="25%" class="text-center">สาขา</th>
                                <th width="25%" class="text-center">รายสาขา</th>
                                <th width="20%" class="text-center">ประเภทผู้ตรวจ</th>
                                <th width="5%" class="text-center">ดู</th>
                            </tr>
                        </thead>
                        <tbody class="text-top">
                            @if( isset($applicationIbcb->id) )

                                @php
                                    $inspectors = App\Models\Section5\ApplicationIbcbInspectors::where('application_id', $applicationIbcb->id )->get();
                                @endphp

                                @foreach ( $inspectors as $ki => $Insp )
                                    @php
                                        $branch_group =  $Insp->scopes()->with(['bs_branch_group'])->select('branch_group_id')->groupBy('branch_group_id')->get();
                                        $groupf = false;
                                    @endphp

                                    @foreach ( $branch_group as $group )
                                        @php
                                            $bs_branch_group = $group->bs_branch_group;

                                            $branch = $Insp->scopes()->with(['bs_branch'])->where('branch_group_id', $group->branch_group_id )->get();
                                        @endphp
                                        @if(  $groupf == false)
                                            @php
                                                $groupf = true;
                                            @endphp
                                            <tr>
                                                <td class="ins_no text-center text-top" rowspan="{!! count($branch_group) !!}">{!! $ki+1 !!}</td>
                                                <td class="text-top" rowspan="{!! count($branch_group) !!}">
                                                    {!! !empty($Insp->InspectorFullName)?$Insp->InspectorFullName:null  !!}
                                                    <div>( {!! !empty($Insp->inspector_taxid)?$Insp->inspector_taxid:null  !!} )</div>
                                                </td>
                                                <td class="text-top">
                                                    {!! $bs_branch_group->title !!}
                                                </td>
                                                <td class="text-top">
                                                    <ul>
                                                        @foreach ( $branch as $Itemb )
                                                            <li>
                                                                {!! !empty($Itemb->bs_branch)?$Itemb->bs_branch->title:null !!}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-center text-top" rowspan="{!! count($branch_group) !!}">{!!  ($Insp->inspector_type==1)?'ผู้ตรวจของหน่วยตรวจ':'ผู้ตรวจอิสระ' !!}</td>
                                                <td class="text-top text-center" rowspan="{!! count($branch_group) !!}">
                                                    <a href="{!! url('section5/inspectors/'.$Insp->inspector_id) !!}" class="btn btn-info btn-xs btn-outline " target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text-top">
                                                    {!! $bs_branch_group->title !!}
                                                </td>
                                                <td class="text-top">
                                                    <ul>
                                                        @foreach ( $branch as $Itemb )
                                                            <li>
                                                                {!! !empty($Itemb->bs_branch)?$Itemb->bs_branch->title:null !!}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>เอกสารแนบ</h5></legend>
        @php

            if( isset($applicationIbcb->id) && !empty($applicationIbcb->config_evidencce) ){
                $configs_evidences = json_decode($applicationIbcb->config_evidencce);
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

                $file_properties = null;

                if(  !empty($evidences->file_properties)  ){
                    $list = [];
                    foreach ( json_decode($evidences->file_properties) as $value) {

                        $list[] = '.'.$value;
                    }
                    $evidences->file_properties_item =  $list;

                }

                $file_properties = !empty($evidences->file_properties_item) ? implode(',', $evidences->file_properties_item ):'';

                $attachment = null;

                if( isset($applicationIbcb->id) ){
                    $attachment = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcb )->getTable() )
                                    ->where('ref_id', $applicationIbcb->id )
                                    ->when($evidences->id, function ($query, $setting_file_id){
                                        return $query->where('setting_file_id', $setting_file_id);
                                    })
                                    ->first();

                }

            @endphp


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('evidence_file_config', (!empty($evidences->title)?$evidences->title:null).' : ', ['class' => 'col-md-7 control-label '])) !!}
                        <div class="col-md-4">

                            @if( !empty($attachment) )
                                <div class="col-md-4">
                                    <a href=" {!! HP::getFileStorage($attachment->url) !!}" target="_blank" title="{!! !empty($attachment->filename) ? $attachment->filename : 'ไฟล์แนบ' !!}">
                                        <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                                    </a>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <span>
                                        <i class="fa fa-folder-open fa-lg" style="color:#a3a3a3;" aria-hidden="true"></i>
                                    </span>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        @endforeach

        @php
            $file_other = [];
            if( !empty($applicationIbcb->id) ){
                $file_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcb )->getTable() )
                                                ->where('ref_id', $applicationIbcb->id )
                                                ->where('section', 'evidence_file_other')
                                                ->get();
            }
        @endphp

        @if( count($file_other) >= 1 )
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-7">เอกสารเพิ่มเติม</label>
                        <div class="col-md-5"></div>
                    </div>
                </div>
            </div>
        @endif

        @foreach ( $file_other as $attach )

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('evidence_file_config', (!empty($attach->caption)?$attach->caption:null).' : ', ['class' => 'col-md-7 control-label '])) !!}
                        <div class="col-md-4">

                            @if( !empty($attach) )
                                <div class="col-md-4">
                                    <a href=" {!! HP::getFileStorage($attach->url) !!}" target="_blank" title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}">
                                        <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                                    </a>
                                </div>

                             @endif

                        </div>
                    </div>
                </div>
            </div>


        @endforeach

    </fieldset>


</div>

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script>
    jQuery(document).ready(function() {

        $(document).on('click', '.open_scope_branches_tis_details', function(){

            $("#table_scope_branches_tis_details").DataTable().clear().destroy();

            open_scope_branches_tis_details($(this));

            $('#table_scope_branches_tis_details').DataTable({
                searching: true,
                autoWidth: false,
                columnDefs: [
                    { className: "text-center", targets: 0 },
                    { width: "10%", targets: 0 }
                ]
            });

            $('#maodal_scope_branches_tis_details').modal('show');

        });

        $('.form-body').find('button[type="submit"]').remove();
        $('.form-body').find('.icon-close').parent().remove();
        $('.form-body').find('.fa-copy').parent().remove();
        $('.form-body').find('input').prop('disabled', true);
        $('.form-body').find('textarea').prop('disabled', true);
        $('.form-body').find('select').prop('disabled', true);
        $('.form-body').find('.bootstrap-tagsinput').prop('disabled', true);
        $('.form-body').find('span.tag').children('span[data-role="remove"]').remove();
        $('.form-body').find('button').prop('disabled', true);
        $('.form-body').find('button').remove();
        $('.form-body').find('.btn-remove-file').parent().remove();
        $('.form-body').find('.show_tag_a').hide();
        $('.form-body').find('.input_show_file').hide();
    });

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
                        <td class="">${item.tis_no} : ${item.tis_name}</td>
                        <td class="">${item.branch_title}</td>
                    </tr>
                `;
            });
            $('#scope_branches_tis_details').append(rows);
        }
    }
    </script>
@endpush

<div class="form-body">

    <fieldset class="white-box">
        <legend class="legend"><h5>1.ข้อมูลผู้ยื่นคำขอ</h5></legend>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('authorized_name') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_name', 'ชื่อผู้ยื่นขอรับการแต่งตั้ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_name', !empty( $application_inspection_unit->authorized_name )?$application_inspection_unit->authorized_name:null,['class' => 'form-control input_show', 'required' => true, 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('agent_village_txt') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_taxid', 'เลขประจำตัวผู้เสียภาษีอากร'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_taxid', !empty( $application_inspection_unit->authorized_taxid )?$application_inspection_unit->authorized_taxid:null,  ['class' => 'form-control input_show', 'required' => true, 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_taxid', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('authorized_date_niti') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_date_niti', 'วันเกิด/วันที่จดทะเบียนนิติบุคคล'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                    <div class="col-md-8">
                        <div class="input-group">
                            {!! Form::text('authorized_date_niti', !empty( $application_inspection_unit->authorized_date_niti )?HP::revertDate($application_inspection_unit->authorized_date_niti):null,  ['class' => 'form-control input_show', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('authorized_date_niti', '<p class="help-block">:message</p>') !!}
                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ที่ตั้ง/สำนักงานใหญ่</h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('authorized_address') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_address', !empty( $application_inspection_unit->authorized_address )?$application_inspection_unit->authorized_address:null,['class' => 'form-control', 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_address', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_building') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_building', !empty( $application_inspection_unit->authorized_building )?$application_inspection_unit->authorized_building:null,  ['class' => 'form-control', 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_building', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_soi') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_soi', !empty( $application_inspection_unit->authorized_soi )?$application_inspection_unit->authorized_soi:null,  ['class' => 'form-control', 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_soi', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_moo') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_moo', !empty( $application_inspection_unit->authorized_moo )?$application_inspection_unit->authorized_moo:null,['class' => 'form-control', 'readonly' => true ]) !!}
                        {!! $errors->first('authorized_moo', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group ">
                    {!! Form::label('authorized_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_address_seach', null,  ['class' => 'form-control authorized_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่', 'disabled' => true ]) !!}
                        {!! $errors->first('authorized_address_seach', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_subdistrict_txt') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_subdistrict_txt', !empty( $application_inspection_unit->AuthorizedSubdistrictName )?$application_inspection_unit->AuthorizedSubdistrictName:null,  ['class' => 'form-control authorized_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('authorized_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_district_txt') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_district_txt', !empty( $application_inspection_unit->AuthorizedDistrictName )?$application_inspection_unit->AuthorizedDistrictName:null,['class' => 'form-control authorized_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('authorized_district_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_province_txt') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_province_txt', !empty( $application_inspection_unit->AuthorizedProvinceName )?$application_inspection_unit->AuthorizedProvinceName:null,  ['class' => 'form-control authorized_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('authorized_province_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('authorized_postcode_txt') ? 'has-error' : ''}}">
                    {!! Form::label('authorized_postcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('authorized_postcode_txt', !empty( $application_inspection_unit->AuthorizedPostcodeName )?$application_inspection_unit->AuthorizedPostcodeName:null,['class' => 'form-control authorized_input_show', 'disabled' => true ]) !!}
                        {!! $errors->first('authorized_postcode_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {!! Form::hidden('authorized_subdistrict_id', !empty( $application_inspection_unit->authorized_subdistrict_id )?$application_inspection_unit->authorized_subdistrict_id:null, [ 'class' => 'authorized_input_show', 'id' => 'authorized_subdistrict_id' ] ) !!}
            {!! Form::hidden('authorized_district_id', !empty( $application_inspection_unit->authorized_district_id )?$application_inspection_unit->authorized_district_id:null, [ 'class' => 'authorized_input_show', 'id' => 'authorized_district_id' ] ) !!}
            {!! Form::hidden('authorized_province_id', !empty( $application_inspection_unit->authorized_province_id )?$application_inspection_unit->authorized_province_id:null, [ 'class' => 'authorized_input_show', 'id' => 'authorized_province_id' ] ) !!}
            {!! Form::hidden('authorized_postcode', !empty( $application_inspection_unit->authorized_postcode )?$application_inspection_unit->authorized_postcode:null, [ 'class' => 'authorized_input_show', 'id' => 'authorized_postcode' ] ) !!}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ที่ตั้งห้องปฏิบัติการ</h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="control-label col-md-4">&nbsp;</label>
                <div class="col-md-8">
                    <div class="form-group ">
                        {!! Form::checkbox('use_address_office', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'use_address_office','required' => false]) !!}
                        <label for="use_address_office" class="font-medium-1">&nbsp;&nbsp; ใช้ที่อยู่เดียวกับที่ตั้งสำนักงานใหญ่</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_address') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_address', !empty( $application_inspection_unit->laboratory_address )?$application_inspection_unit->laboratory_address:null,['class' => 'form-control', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_address', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('laboratory_building') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_building', !empty( $application_inspection_unit->laboratory_building )?$application_inspection_unit->laboratory_building:null,  ['class' => 'form-control', ]) !!}
                        {!! $errors->first('laboratory_building', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('laboratory_soi') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_soi', !empty( $application_inspection_unit->laboratory_soi )?$application_inspection_unit->laboratory_soi:null,  ['class' => 'form-control' ]) !!}
                        {!! $errors->first('laboratory_soi', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('laboratory_moo') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_moo', !empty( $application_inspection_unit->laboratory_moo )?$application_inspection_unit->laboratory_moo:null,['class' => 'form-control']) !!}
                        {!! $errors->first('laboratory_moo', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group ">
                    {!! Form::label('laboratory_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_address_seach', null,  ['class' => 'form-control laboratory_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                        {!! $errors->first('laboratory_address_seach', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_subdistrict_txt') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_subdistrict_txt', !empty( $application_inspection_unit->LaboratorySubdistrictName )?$application_inspection_unit->LaboratorySubdistrictName:null,  ['class' => 'form-control laboratory_input_show', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_district_txt') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_district_txt', !empty( $application_inspection_unit->LaboratoryDistrictName )?$application_inspection_unit->LaboratoryDistrictName:null,['class' => 'form-control laboratory_input_show', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_district_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_province_txt') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_province_txt', !empty( $application_inspection_unit->LaboratoryProvinceName )?$application_inspection_unit->LaboratoryProvinceName:null,  ['class' => 'form-control laboratory_input_show', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_province_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_postcode_txt') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_postcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_postcode_txt', !empty( $application_inspection_unit->LaboratoryPostcodeName )?$application_inspection_unit->LaboratoryPostcodeName:null,['class' => 'form-control laboratory_input_show', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_postcode_txt', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('laboratory_tel') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_tel', 'เบอร์โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_tel', !empty( $application_inspection_unit->laboratory_tel )?$application_inspection_unit->laboratory_tel:null,['class' => 'form-control', 'required' => true ]) !!}
                        {!! $errors->first('laboratory_tel', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('laboratory_fax') ? 'has-error' : ''}}">
                    {!! Form::label('laboratory_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('laboratory_fax', !empty( $application_inspection_unit->laboratory_fax )?$application_inspection_unit->laboratory_fax:null,  ['class' => 'form-control', ]) !!}
                        {!! $errors->first('laboratory_fax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {!! Form::hidden('laboratory_subdistrict_id', !empty( $application_inspection_unit->laboratory_subdistrict_id )?$application_inspection_unit->laboratory_subdistrict_id:null, [ 'class' => 'laboratory_input_show', 'id' => 'laboratory_subdistrict_id' ] ) !!}
            {!! Form::hidden('laboratory_district_id', !empty( $application_inspection_unit->laboratory_district_id )?$application_inspection_unit->laboratory_district_id:null, [ 'class' => 'laboratory_input_show', 'id' => 'laboratory_district_id' ] ) !!}
            {!! Form::hidden('laboratory_province_id', !empty( $application_inspection_unit->laboratory_province_id )?$application_inspection_unit->laboratory_province_id:null, [ 'class' => 'laboratory_input_show', 'id' => 'laboratory_province_id' ] ) !!}
            {!! Form::hidden('laboratory_postcode', !empty( $application_inspection_unit->laboratory_postcode )?$application_inspection_unit->laboratory_postcode:null, [ 'class' => 'laboratory_input_show', 'id' => 'laboratory_postcode' ] ) !!}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><h6>ผู้ประสานงาน </h6></label>
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="control-label col-md-4">&nbsp;</label>
                <div class="col-md-8">
                    <div class="form-group ">
                        {!! Form::checkbox('use_data_contact', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'use_data_contact','required' => false]) !!}
                        <label for="use_data_contact" class="font-medium-1">&nbsp;&nbsp; ใช้ข้อมูลเดียวกับผู้ติดต่อตอนลงทะเบียน</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('contact_name') ? 'has-error' : ''}}">
                    {!! Form::label('contact_name', 'ชื่อผู้ประสานงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_name', !empty( $application_inspection_unit->contact_name )?$application_inspection_unit->contact_name:null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                        {!! $errors->first('contact_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required {{ $errors->has('contact_position') ? 'has-error' : ''}}">
                    {!! Form::label('contact_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_position', !empty( $application_inspection_unit->contact_position )?$application_inspection_unit->contact_position:null,  ['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                        {!! $errors->first('contact_position', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('contact_mobile') ? 'has-error' : ''}}">
                    {!! Form::label('contact_mobile', 'โทรศัพท์มือถือ'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_mobile', !empty( $application_inspection_unit->contact_mobile )?$application_inspection_unit->contact_mobile:null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                        {!! $errors->first('contact_mobile', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('contact_tel') ? 'has-error' : ''}}">
                    {!! Form::label('contact_tel', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_tel', !empty( $application_inspection_unit->contact_tel )?$application_inspection_unit->contact_tel:null,  ['class' => 'form-control contact_input_show' ]) !!}
                        {!! $errors->first('contact_tel', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('contact_fax') ? 'has-error' : ''}}">
                    {!! Form::label('contact_fax', 'โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_fax', !empty( $application_inspection_unit->contact_fax )?$application_inspection_unit->contact_fax:null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                        {!! $errors->first('contact_fax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('contact_email') ? 'has-error' : ''}}">
                    {!! Form::label('contact_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('contact_email', !empty( $application_inspection_unit->contact_email )?$application_inspection_unit->contact_email:null,  ['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                        {!! $errors->first('contact_email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
    
    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>2. ข้อมูลขอรับบริการ</h5></legend>
        <p>ยื่นคำขอรับการแต่งตั้งเป็นผู้ตรวจสอบผลิตภัณฑ์มาตรฐานอุตสาหกรรม ตามมาตรา 5</p>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group required{{ $errors->has('standard_product') ? 'has-error' : ''}}">
                    {!! Form::label('standard_product', 'ตามมาตรฐานผลิตภัณฑ์อุตสาหกรรม '.' :', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::text('standard_product', null,['class' => 'form-control', 'autocomplete' => 'off', 'data-id' => '', 'data-name' => '',  'data-number' => '' ,'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาเลขที่ มอก. หรือ ชื่อ มอก.' ]) !!}
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success" type="button" id="btn_plus_standard"><i class="icon-plus"></i>เพิ่ม</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
               
                <div class="table-responsive repeater-standard">
                    <table class="table-bordered table table-hover primary-table" id="table-standard">
                        <thead>
                            <tr>
                                <th width="5%">ลำดับ</th>
                                <th width="25%">เลขที่ มอก.</th>
                                <th>ชื่อ มอก.</th>
                                <th width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody data-repeater-list="repeater-standard">
                            @isset( $application_inspection_unit->id )
                                @php
                                    $app_standard = App\Models\Sso\ApplicationInspectionUnitStandard::where('app_units_id', $application_inspection_unit->id )->get();
                                @endphp

                                @foreach ( $app_standard as $key => $standard )
                                    <tr>
                                        <td class="standard_no text-center">{!! $key+1 !!}</td>
                                        <td>
                                            {!! !empty($standard->StandardTisNo)?$standard->StandardTisNo:null !!}
                                            {!! Form::hidden('tis_id', !empty( $standard->tis_standards_id )?$standard->tis_standards_id:null, [ 'class' => 'input_tis_tisno' ] ) !!}
                                        </td>
                                        <td>{!! !empty($standard->StandardTisTitle)?$standard->StandardTisTitle:null !!}</td>
                                        <td><button class="btn btn-danger btn_standard_remove" data-repeater-delete type="button">ลบ</button></td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>

                </div>

            </div>
            <div class="col-md-1"></div>
        </div>

    </fieldset>

    <fieldset class="white-box">
        <legend class="legend"><h5>3. เอกสารแนบ</h5></legend>

        <div class="row">
            <div class="col-md-11">
                <div class="form-group required">
                    {!! HTML::decode(Form::label('file_attachment_example', '1.รายการทดสอบที่ขอรับการแต่งตั้งของแต่ละมาตรฐาน และรายละเอียดเครื่องมือที่ใช้ในการทดสอบ'.' : ', ['class' => 'col-md-5 control-label'])) !!}

                    @php
                        $attachment_example = null;
                        if( isset($application_inspection_unit->id) ){
                            $attachment_example = App\AttachFile::where('section', 'file_attachment_example')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->first();
                        }
                    @endphp

                    @if( is_null($attachment_example) )
                        <div class="col-md-4">
                            <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true" ></i></a>
                        </div>
                    @else
                        <div class="col-md-4" >
                            <a href="{{url('section5/get-view/'.$attachment_example->url.'/'.( !empty($attachment_example->filename) ? $attachment_example->filename :  basename($attachment_example->url)  ))}}" target="_blank" title="{!! !empty($attachment_example->filename) ? $attachment_example->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    {!! HTML::decode(Form::label('file_attachment_standard', '2. ใบรับรองความสามารถห้องปฏิบัติการตามมาตรฐาน มอก. 17025 (ถ้ามี)'.' : ', ['class' => 'col-md-5 control-label'])) !!}
                    @php
                        $attachment_standard = null;
                        if( isset($application_inspection_unit->id) ){
                            $attachment_standard = App\AttachFile::where('section', 'file_attachment_standard')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->first();
                        }
                 
                    @endphp

                    @if( is_null($attachment_standard) )
                        <div class="col-md-4">
                            <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true"></i></a>
                        </div>
                    @else

                        <div class="col-md-4" >
                            <a href="{{url('section5/get-view/'.$attachment_standard->url.'/'.( !empty($attachment_standard->filename) ? $attachment_standard->filename :  basename($attachment_standard->url)  ))}}" target="_blank" title="{!! !empty($attachment_standard->filename) ? $attachment_standard->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>

                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    {!! HTML::decode(Form::label('file_attachment_certificate', '3. หนังสือรับรองบริษัท เซ็นชื่อผู้มืออำนาจและลงตราประทับบริษัทฯ ทุกแผ่น (กรณีเป็นหน่วยงานเอกชน)'.' : ', ['class' => 'col-md-5 control-label'])) !!}
                    @php
                        $attachment_certificate = null;
                        if( isset($application_inspection_unit->id) ){
                            $attachment_certificate = App\AttachFile::where('section', 'file_attachment_certificate')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->first();
                        }
                
                    @endphp

                    @if( is_null($attachment_certificate) )
                        <div class="col-md-4">
                            <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true"></i></a>
                        </div>
                    @else

                        <div class="col-md-4" >
                            <a href="{{url('section5/get-view/'.$attachment_certificate->url.'/'.( !empty($attachment_certificate->filename) ? $attachment_certificate->filename :  basename($attachment_certificate->url)  ))}}" target="_blank" title="{!! !empty($attachment_standard->filename) ? $attachment_standard->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif
                
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    {!! HTML::decode(Form::label('file_attachment_attorney', '4.หนังสือมอบอำนาจ (กรณีเป็นหน่วยงานเอกชน)'.' : ', ['class' => 'col-md-5 control-label'])) !!}
                    @php
                        $attachment_attorney = null;
                        if( isset($application_inspection_unit->id) ){
                            $attachment_attorney = App\AttachFile::where('section', 'file_attachment_attorney')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->first();
                        }
                
                    @endphp

                    @if( is_null($attachment_attorney) )
                        <div class="col-md-4">
                            <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true"></i></a>
                        </div>
                    @else

                        <div class="col-md-4" >
                            <a href="{{url('section5/get-view/'.$attachment_attorney->url.'/'.( !empty($attachment_attorney->filename) ? $attachment_attorney->filename :  basename($attachment_attorney->url)  ))}}" target="_blank" title="{!! !empty($attachment_attorney->filename) ? $attachment_attorney->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>

                    @endif
          
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    {!! HTML::decode(Form::label('file_attachment_card', '5. สำเนาบัตรประชาชนของผู้มอบอำนาจและรับมอบอำนาจ (กรณีเป็นหน่วยงานเอกชน)'.' : ', ['class' => 'col-md-5 control-label'])) !!}

                    @php
                        $attachment_card = null;
                        if( isset($application_inspection_unit->id) ){
                            $attachment_card = App\AttachFile::where('section', 'file_attachment_card')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->first();
                        }
                
                    @endphp

                    @if( is_null($attachment_card) )
                        <div class="col-md-4">
                            <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true"></i></a>
                        </div>
                    @else

                        <div class="col-md-4" >
                            <a href="{{url('section5/get-view/'.$attachment_card->url.'/'.( !empty($attachment_card->caption) ? $attachment_card->caption :  basename($attachment_card->url)  ))}}" target="_blank" title="{!! !empty($attachment_card->filename) ? $attachment_card->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>

        <div class="row" id="div_attach">
            <div class="col-md-11">

                @php
                    $file_other = [];
                    if( isset($application_inspection_unit->id) ){
                        $file_other = App\AttachFile::where('section', 'file_attach_other')->where('ref_table', (new App\Models\Sso\ApplicationInspectionUnit )->getTable() )->where('ref_id', $application_inspection_unit->id )->get();
                    }
                @endphp

                @foreach ( $file_other as $attach )

                    <div class="form-group">
                        {!! HTML::decode(Form::label('personfile', 'เอกสารแนบ'.' : ', ['class' => 'col-md-5 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                        </div>
                        <div class="col-md-2">
                            <a href="{{url('section5/get-view/'.$attach->url.'/'.( !empty($attach->caption) ? $attach->caption :  basename($attach->url)  ))}}" target="_blank" title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}">
                                <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>
                            </a>
                        </div>

                    </div>

                @endforeach

            </div>
        </div>

    </fieldset>

</div>
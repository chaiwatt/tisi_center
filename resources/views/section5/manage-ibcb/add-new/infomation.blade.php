<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลหน่วยตรวจสอบ IB/CB</h5></legend>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        {!! Form::label('filter_search', 'กรอกเพื่อค้นหา'.' :', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            <div class="input-group m-t-10">
                                {!! Form::text('filter_search', null,['class' => 'form-control', 'required' => false, 'disabled' => true ]) !!}
                                <span class="input-group-btn">
                                    <button id="btn_search" type="button" class="btn waves-effect waves-light btn-info"><i class="fa fa-search"></i> ค้นหา</button>
                                    {{-- <button id="btn_not_search" type="button" class="btn waves-effect waves-light btn-success">กรอกเอง</button> --}}
                                </span>
                            </div>
                            <p class="m-t-10 text-primary">(ค้นหาจาก: เลขประจำตัวผู้เสียภาษี/เลขประจำตัวผู้เสียภาษี + สาขาผู้ยื่น/ชื่อ-สกุลผู้ยื่น)</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_name') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_name', 'ชื่อผู้ยื่นขอรับการแต่งตั้ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_name', null,['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! Form::hidden('agency_id', null, [ 'class' => 'input_infomation', 'id' => 'agency_id' ] ) !!}
                            {!! $errors->first('applicant_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_taxid') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_taxid', 'เลขประจำตัวผู้เสียภาษีอากร'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_taxid', null,  ['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true , 'id' => 'applicant_taxid']) !!}
                            {!! $errors->first('applicant_taxid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_date_niti') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_date_niti', 'วันที่จดทะเบียนนิติบุคคล'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="input-group">
                                {!! Form::text('applicant_date_niti_show', null,  ['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ,'id' => 'applicant_date_niti_show' ]) !!}
                                {!! $errors->first('applicant_date_niti', '<p class="help-block">:message</p>') !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                {!! Form::hidden('applicant_date_niti', null, [ 'class' => 'form-control input_infomation', 'id' => 'applicant_date_niti' ] ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4"><h5>ที่อยู่สำนักงานใหญ่</h5></label>
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
                            {!! Form::text('hq_address', null,['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('hq_address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_building') ? 'has-error' : ''}}">
                        {!! Form::label('hq_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_building', null,  ['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
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
                            {!! Form::text('hq_soi', null,  ['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('hq_soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_moo') ? 'has-error' : ''}}">
                        {!! Form::label('hq_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_moo', null,['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
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
                            {!! Form::text('hq_road', null,['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('hq_road', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row box_hq_address_seach">
                <div class="col-md-6">
                    <div class="form-group ">
                        {!! Form::label('hq_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_address_seach', null,  ['class' => 'form-control hq_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('hq_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_subdistrict_txt', null,  ['class' => 'form-control hq_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('hq_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('hq_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_district_txt',null,['class' => 'form-control hq_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('hq_district_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_province_txt') ? 'has-error' : ''}}">
                        {!! Form::label('hq_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_province_txt', null,  ['class' => 'form-control hq_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('hq_province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('hq_zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('hq_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('hq_zipcode_txt', null,['class' => 'form-control hq_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('hq_zipcode_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                {!! Form::hidden('hq_subdistrict_id', null, [ 'class' => 'input_infomation hq_input_search', 'id' => 'hq_subdistrict_id' ] ) !!}
                {!! Form::hidden('hq_district_id', null, [ 'class' => 'input_infomation hq_input_search', 'id' => 'hq_district_id' ] ) !!}
                {!! Form::hidden('hq_province_id', null, [ 'class' => 'input_infomation hq_input_search', 'id' => 'hq_province_id' ] ) !!}
                {!! Form::hidden('hq_zipcode', null, [ 'class' => 'input_infomation hq_input_search', 'id' => 'hq_zipcode' ] ) !!}
                {!! Form::hidden('hq_phone', null, [ 'class' => 'input_infomation hq_input_show', 'id' => 'hq_phone' ] ) !!}
                {!! Form::hidden('hq_fax', null, [ 'class' => 'input_infomation hq_input_show', 'id' => 'hq_fax' ] ) !!}
            </div>
            <hr> --}}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-b-0">
                        <label class="control-label col-md-4"><h5>ที่ตั้งหน่วยงาน</h5></label>
                        <div class="col-md-9"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <div class="form-group">
                        <div class="col-md-4 use_address_office_type">
                            {!! Form::radio('use_address_office', '1',null, ['class' => 'form-control check', 'data-radio' => 'iradio_flat-blue', 'id'=>'use_address_office-1']) !!}
                            {!! Form::label('use_address_office-1', 'ที่อยู่เดียวกับที่อยู่สำนักงานใหญ่', ['class' => 'control-label text-capitalize']) !!}
                        </div>
                        <div class="col-md-4 use_address_office_type">
                            {!! Form::radio('use_address_office', '2',null, ['class' => 'form-control check', 'data-radio' => 'iradio_flat-blue', 'id'=>'use_address_office-2']) !!}
                            {!! Form::label('use_address_office-2', 'ที่อยู่เดียวกับที่อยู่ติดต่อได้', ['class' => 'control-label text-capitalize']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::radio('use_address_office', '3',null, ['class' => 'form-control check', 'data-radio' => 'iradio_flat-blue', 'id'=>'use_address_office-3']) !!}
                            {!! Form::label('use_address_office-3', 'ระบุที่ตั้งใหม่', ['class' => 'control-label text-capitalize']) !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('ibcb_name') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_name', 'ชื่อหน่วยงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_name', null,['class' => 'form-control', 'required' => true ]) !!}
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
                            {!! Form::text('ibcb_address', null,['class' => 'form-control input_ibcb_infomation', 'required' => true ]) !!}
                            {!! $errors->first('ibcb_address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('ibcb_building') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_building', null,  ['class' => 'form-control input_ibcb_infomation', ]) !!}
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
                            {!! Form::text('ibcb_soi', null,  ['class' => 'form-control input_ibcb_infomation' ]) !!}
                            {!! $errors->first('ibcb_soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('ibcb_moo') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_moo', null,['class' => 'form-control input_ibcb_infomation']) !!}
                            {!! $errors->first('ibcb_moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        {!! Form::label('ibcb_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_address_seach', null,  ['class' => 'form-control ibcb_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                            {!! $errors->first('ibcb_address_seach', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('ibcb_subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_subdistrict_txt', null,  ['class' => 'form-control input_ibcb_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('ibcb_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('ibcb_district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_district_txt', null,['class' => 'form-control input_ibcb_infomation', 'required' => true, 'readonly' => true ]) !!}
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
                            {!! Form::text('ibcb_province_txt', null,  ['class' => 'form-control input_ibcb_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('ibcb_province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('ibcb_zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_zipcode_txt', null,['class' => 'form-control input_ibcb_infomation', 'required' => true, 'readonly' => true  ]) !!}
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
                            {!! Form::text('ibcb_phone', null,['class' => 'form-control input_ibcb_infomation', 'required' => true ]) !!}
                            {!! $errors->first('ibcb_phone', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('ibcb_fax') ? 'has-error' : ''}}">
                        {!! Form::label('ibcb_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('ibcb_fax', null,  ['class' => 'form-control input_ibcb_infomation', ]) !!}
                            {!! $errors->first('ibcb_fax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                {!! Form::hidden('ibcb_subdistrict_id',null, [ 'class' => 'input_ibcb_infomation', 'id' => 'ibcb_subdistrict_id' ] ) !!}
                {!! Form::hidden('ibcb_district_id', null, [ 'class' => 'input_ibcb_infomation', 'id' => 'ibcb_district_id' ] ) !!}
                {!! Form::hidden('ibcb_province_id', null, [ 'class' => 'input_ibcb_infomation', 'id' => 'ibcb_province_id' ] ) !!}
                {!! Form::hidden('ibcb_zipcode', null, [ 'class' => 'input_ibcb_infomation', 'id' => 'ibcb_zipcode' ] ) !!}
            </div>
            <hr>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4"><h5>ผู้ประสานงาน</h5></label>
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
                            {!! Form::text('co_name', null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                            {!! $errors->first('co_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required {{ $errors->has('co_position') ? 'has-error' : ''}}">
                        {!! Form::label('co_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('co_position', null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
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
                            {!! Form::text('co_mobile', null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                            {!! $errors->first('co_mobile', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('co_tel') ? 'has-error' : ''}}">
                        {!! Form::label('co_tel', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('co_tel', null,  ['class' => 'form-control co_input_show' ]) !!}
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
                            {!! Form::text('co_fax', null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                            {!! $errors->first('co_fax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('co_email') ? 'has-error' : ''}}">
                        {!! Form::label('co_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('co_email', null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
                            {!! $errors->first('co_email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            
        </fieldset>
    </div>
</div>

@push('js')
    <script>

        var users_hq_data = {
                                address_no: "",
                                street: "",
                                moo: "",
                                soi: "",
                                subdistrict: "",
                                district: "",
                                province: "",
                                subdistrict_id: "",
                                district_id: "",
                                province_id: "",
                                zipcode: "",
                                tel: "",
                                fax: ""
                            }

        var users_co_data = {
                                address_no: "",
                                street: "",
                                moo: "",
                                soi: "",
                                subdistrict: "",
                                district: "",
                                province: "",
                                subdistrict_id: "",
                                district_id: "",
                                province_id: "",
                                zipcode: "",
                                tel: "",
                                fax: ""
                            }

        jQuery(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });

            //ค้นหา
            $('#btn_search').click(function (e) {      
                SetInputInfomaion( 1 );
            });

            //กรอกเอง
            $('#btn_not_search').click(function (e) { 
                SetInputInfomaion( 2 );
            });

            SetInputInfomaion( 1 );

            $("#hq_address_seach").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/funtions/search-addreess') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $("#hq_address_seach").on('change', function () {

                if(  $(this).val() != ''){
                    $.ajax({
                        url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                    }).done(function( jsondata ) {
                        if(jsondata != ''){

                            $('#hq_subdistrict_txt').val(jsondata.sub_title);
                            $('#hq_district_txt').val(jsondata.dis_title);
                            $('#hq_province_txt').val(jsondata.pro_title);
                            $('#hq_zipcode_txt').val(jsondata.zip_code);

                            $('#hq_subdistrict_id').val(jsondata.sub_ids);
                            $('#hq_district_id').val(jsondata.dis_id);
                            $('#hq_province_id').val(jsondata.pro_id);
                            $('#hq_zipcode').val(jsondata.zip_code);

                            $("#hq_address_seach").select2("val", "");

                        }
                    });
                }

            });

            $("#ibcb_address_seach").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/funtions/search-addreess') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $("#ibcb_address_seach").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#ibcb_subdistrict_txt').val(jsondata.sub_title);
                        $('#ibcb_district_txt').val(jsondata.dis_title);
                        $('#ibcb_province_txt').val(jsondata.pro_title);
                        $('#ibcb_zipcode_txt').val(jsondata.zip_code);

                        $('#ibcb_subdistrict_id').val(jsondata.sub_ids);
                        $('#ibcb_district_id').val(jsondata.dis_id);
                        $('#ibcb_province_id').val(jsondata.pro_id);
                        $('#ibcb_zipcode').val(jsondata.zip_code);

                    }
                });
            });

            $('#filter_search').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-users") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {
                    
                    $('#agency_id').val(jsondata.id);
                    $('#applicant_name').val(jsondata.name_full);
                    $('#applicant_taxid').val(jsondata.taxid);

                    var applicant_date_niti = '';
                    var applicant_date_niti_show = '';

                    if( jsondata.applicanttype_id != "2" ){
                        applicant_date_niti = jsondata.date_niti;
                        applicant_date_niti_show = jsondata.date_niti_format;
                    }else{
                        applicant_date_niti = jsondata.date_of_birth;
                        applicant_date_niti_show = jsondata.date_of_birth_format;
                    }

                    $('#applicant_date_niti').val(applicant_date_niti);
                    $('#applicant_date_niti_show').val(applicant_date_niti_show);

                    //ที่อยู่สำนักงานใหญ่
                    // $('#hq_address').val(jsondata.hq_address_no);
                    // $('#hq_building').val(jsondata.hq_building);
                    // $('#hq_soi').val(jsondata.hq_soi);
                    // $('#hq_moo').val(jsondata.hq_moo);
                    // $('#hq_road').val(jsondata.hq_street);
                    // $('#hq_subdistrict_txt').val(jsondata.hq_subdistrict_title);
                    // $('#hq_district_txt').val(jsondata.hq_district_title);
                    // $('#hq_province_txt').val(jsondata.hq_province_title);
                    // $('#hq_zipcode_txt').val(jsondata.hq_zipcode);
                    // $('#hq_subdistrict').val(jsondata.hq_subdistrict_id);
                    // $('#hq_district').val(jsondata.hq_district_id);
                    // $('#hq_province').val(jsondata.hq_province_id);
                    // $('#hq_zipcode').val(jsondata.hq_zipcode);
                    // $('#hq_phone').val(jsondata.phone);
                    // $('#hq_fax').val(jsondata.fax);
                    users_hq_data = {
                                        address_no: jsondata.hq_address_no,
                                        building: jsondata.hq_building,
                                        street: jsondata.hq_street,
                                        moo: jsondata.hq_moo,
                                        soi: jsondata.hq_soi,
                                        subdistrict: jsondata.hq_subdistrict_title,
                                        district: jsondata.hq_district_title,
                                        province: jsondata.hq_province_title,
                                        subdistrict_id: jsondata.hq_subdistrict_id,
                                        district_id: jsondata.hq_district_id,
                                        province_id: jsondata.hq_province_id,
                                        zipcode: jsondata.hq_zipcode,
                                        tel: jsondata.phone,
                                        fax: jsondata.fax
                                    }

                    //ผู้ประสานงาน
                    $('#co_name').val(jsondata.contact_full_name);
                    $('#co_position').val(jsondata.contact_position);
                    $('#co_mobile').val(jsondata.contact_phone_number);
                    $('#co_tel').val(jsondata.contact_tel);
                    $('#co_fax').val(jsondata.contact_fax);
                    $('#co_email').val(jsondata.email);

                    users_co_data = {
                                        address_no: jsondata.contact_address_no,
                                        street: jsondata.contact_street,
                                        moo: jsondata.contact_moo,
                                        soi: jsondata.contact_soi,
                                        subdistrict: jsondata.contact_subdistrict_title,
                                        district: jsondata.contact_district_title,
                                        province: jsondata.contact_province_title,
                                        subdistrict_id: jsondata.contact_subdistrict_id,
                                        district_id: jsondata.contact_district_id,
                                        province_id: jsondata.contact_province_id,
                                        zipcode: jsondata.contact_zipcode,
                                        tel: jsondata.contact_tel,
                                        fax: jsondata.contact_fax
                                    }
                    $('#filter_search').val('');
                    tableCer.draw();
                }
            });

            $('#use_address_office-1').on('ifChecked', function(event){
                use_address_offices();
            });

            $('#use_address_office-2').on('ifChecked', function(event){
                use_address_offices();
            });

            $('#use_address_office-3').on('ifChecked', function(event){
                use_address_offices();
            });

            
        });

        function SetInputInfomaion( condition ){

            if( condition == 1){ //ค้นหา

                //Claer Input
                $('input.input_infomation').val('');
                $('input.hq_input_search').val('');
                $('input.co_input_show').val('');
                $('input.input_ibcb_infomation').val('');
                $("#hq_address_seach").select2("val", "");
                $('#filter_search').val(''); 

                //Set Input
                $('#filter_search').prop('disabled', false);    
                $('.input_infomation').prop('readonly', true);

                $('.box_hq_address_seach').hide();
                $('.box_hq_address_seach').prop('disabled', true);

                $('.use_address_office_type').show();


                //Remove Class
                $('#applicant_date_niti_show').removeClass('mydatepicker');


            }else{ //กรอกเอง

                //Claer Input
                $('input.input_infomation').val('');
                $('input.hq_input_search').val('');
                $('input.co_input_show').val('');
                $('input.input_ibcb_infomation').val('');
                $("#hq_address_seach").select2("val", "");
                $('#filter_search').val('');

                users_hq_data = {
                                    address_no: "",
                                    building:"",
                                    street: "",
                                    moo: "",
                                    soi: "",
                                    subdistrict: "",
                                    district: "",
                                    province: "",
                                    subdistrict_id: "",
                                    district_id: "",
                                    province_id: "",
                                    zipcode: "",
                                    tel: "",
                                    fax: ""
                                }

                users_co_data = {
                                    address_no: "",
                                    street: "",
                                    moo: "",
                                    soi: "",
                                    subdistrict: "",
                                    district: "",
                                    province: "",
                                    subdistrict_id: "",
                                    district_id: "",
                                    province_id: "",
                                    zipcode: "",
                                    tel: "",
                                    fax: ""
                                }

                //Set Input
                $('#filter_search').prop('disabled', true);
                $('.input_infomation').prop('readonly', false);

                $('.box_hq_address_seach').show();
                $('.box_hq_address_seach').prop('disabled', false);

                $('.use_address_office_type').hide();

                //Add Class
                $('#applicant_date_niti_show').addClass('mydatepicker');

                $('.mydatepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd/mm/yyyy',
                    language:'th-th',
                });
            }

        }

        function use_address_offices(){

            if( $('#use_address_office-1').is(':checked',true) ){

                var address  = checkNone(users_hq_data.address_no)?users_hq_data.address_no:'';
                var moo      = checkNone(users_hq_data.moo)?users_hq_data.moo:'';
                var soi      = checkNone(users_hq_data.soi)?users_hq_data.soi:'';
                var road     = checkNone(users_hq_data.street)?users_hq_data.street:'';
                var building = checkNone(users_hq_data.building)?users_hq_data.building:'';

                var subdistrict_txt = checkNone(users_hq_data.subdistrict)?users_hq_data.subdistrict:'';
                var district_txt    = checkNone(users_hq_data.district)?users_hq_data.district:'';
                var province_txt    = checkNone(users_hq_data.province)?users_hq_data.province:'';
                var postcode_txt    = checkNone(users_hq_data.zipcode)?users_hq_data.zipcode:'';

                var subdistrict_id = checkNone(users_hq_data.subdistrict_id)?users_hq_data.subdistrict_id:'';
                var district_id    = checkNone(users_hq_data.district_id)?users_hq_data.district_id:'';
                var province_id    = checkNone(users_hq_data.province_id)?users_hq_data.province_id:'';
                var postcode       = checkNone(users_hq_data.zipcode)?users_hq_data.zipcode:'';

                var phone = checkNone(users_hq_data.tel)?users_hq_data.tel:'';
                var fax   = checkNone(users_hq_data.fax)?users_hq_data.fax:'';

                $('#ibcb_address').val(address);
                $('#ibcb_moo').val(moo);
                $('#ibcb_soi').val(soi);
                $('#ibcb_road').val(road);
                $('#ibcb_building').val(building);

                $('#ibcb_subdistrict_txt').val(subdistrict_txt);
                $('#ibcb_district_txt').val(district_txt);
                $('#ibcb_province_txt').val(province_txt);
                $('#ibcb_zipcode_txt').val(postcode_txt);

                $('#ibcb_subdistrict_id').val(subdistrict_id);
                $('#ibcb_district_id').val(district_id);
                $('#ibcb_province_id').val(province_id);
                $('#ibcb_zipcode').val(postcode);
                $('#ibcb_phone').val(phone);
                $('#ibcb_fax').val(fax);

            }else if( $('#use_address_office-2').is(':checked',true) ){

                var address  = checkNone(users_co_data.address_no)?users_co_data.address_no:'';
                var moo      = checkNone(users_co_data.moo)?users_co_data.moo:'';
                var soi      = checkNone(users_co_data.soi)?users_co_data.soi:'';
                var road     = checkNone(users_co_data.street)?users_co_data.street:'';
                var building = checkNone(users_co_data.building)?users_co_data.building:'';

                var subdistrict_txt = checkNone(users_co_data.subdistrict)?users_co_data.subdistrict:'';
                var district_txt    = checkNone(users_co_data.district)?users_co_data.district:'';
                var province_txt    = checkNone(users_co_data.province)?users_co_data.province:'';
                var postcode_txt    = checkNone(users_co_data.zipcode)?users_co_data.zipcode:'';

                var subdistrict_id = checkNone(users_co_data.subdistrict_id)?users_co_data.subdistrict_id:'';
                var district_id    = checkNone(users_co_data.district_id)?users_co_data.district_id:'';
                var province_id    = checkNone(users_co_data.province_id)?users_co_data.province_id:'';
                var postcode       = checkNone(users_co_data.zipcode)?users_co_data.zipcode:'';

                var phone = checkNone(users_co_data.tel)?users_co_data.tel:'';
                var fax   = checkNone(users_co_data.fax)?users_co_data.fax:'';

                $('#ibcb_address').val(address);
                $('#ibcb_moo').val(moo);
                $('#ibcb_soi').val(soi);
                $('#ibcb_road').val(road);
                $('#ibcb_building').val(building);

                $('#ibcb_subdistrict_txt').val(subdistrict_txt);
                $('#ibcb_district_txt').val(district_txt);
                $('#ibcb_province_txt').val(province_txt);
                $('#ibcb_zipcode_txt').val(postcode_txt);

                $('#ibcb_subdistrict_id').val(subdistrict_id);
                $('#ibcb_district_id').val(district_id);
                $('#ibcb_province_id').val(province_id);
                $('#ibcb_zipcode').val(postcode);

                $('#ibcb_phone').val(phone);
                $('#ibcb_fax').val(fax);

            }else{
                $('#ibcb_address').val('');
                $('#ibcb_moo').val('');
                $('#ibcb_soi').val('');
                $('#ibcb_road').val('');
                $('#ibcb_building').val('');
                $('.ibcb_input_show').val('');
            }

        }
    </script>
@endpush
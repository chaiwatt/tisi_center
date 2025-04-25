<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลผู้ตรวจ/ผู้ประเมิน</h5></legend>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        {!! Form::label('filter_search', 'กรอกเลขประจำตัวผู้เสียภาษี/ชื่อ-สกุลผู้ยื่นเพื่อค้นหา'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            <div class="input-group m-t-10">
                                {!! Form::text('filter_search', null,['class' => 'form-control', 'required' => false, 'disabled' => true ]) !!}
                                {!! Form::hidden('inspectors_user_id', null, [ 'class' => 'input_infomation', 'id' => 'inspectors_user_id' ] ) !!}
                                <span class="input-group-btn">
                                    <button id="btn_search" type="button" class="btn waves-effect waves-light btn-info"><i class="fa fa-search"></i> ค้นหา</button>
                                    {{-- <button id="btn_not_search" type="button" class="btn waves-effect waves-light btn-success">กรอกเอง</button> --}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('applicant_taxid', 'ชื่อ - สกุล', ['class' => 'col-md-2 control-label text-left']) !!}
                        <div class="col-md-2">
                            {!! Form::select('applicant_prefix',  App\Models\Basic\Prefix::where('state',1)->pluck('initial', 'id')->all(), null, ['class' => 'form-control input_infomation', 'id'=>'applicant_prefix','placeholder' =>'- เลือกคำนำหน้าชื่อ -', 'required'=> true]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::text('applicant_first_name', null, ['class' => 'form-control input_infomation', 'id' => 'applicant_first_name', 'placeholder' => 'ชื่อ', 'required' => true, 'maxlength' => 191]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::text('applicant_last_name', null, ['class' => 'form-control input_infomation', 'id' => 'applicant_last_name', 'placeholder' => 'นามสกุล', 'required' => true, 'maxlength' => 191]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group required">
                        {!! Form::label('applicant_taxid', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_taxid', null, ['class' => 'form-control input_infomation', 'required' => true ]) !!}
                            {!! $errors->first('applicant_taxid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
    
                <div class="col-md-6">
                    <div class="form-group required">
                        {!! Form::label('applicant_date_of_birth', 'วัน/เดือน/ปี เกิด', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="input-group">
                                {!! Form::text('applicant_date_of_birth', null, ['class' => 'form-control input_infomation', 'required' => true ]) !!}
                                {!! $errors->first('applicant_date_of_birth', '<p class="help-block">:message</p>') !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('applicant_position') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_position', 'ตำแหน่ง', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_position', null, ['class' => 'form-control input_infomation_clear']) !!}
                            {!! $errors->first('applicant_position', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('applicant_phone') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_phone', 'โทรศัพท์', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_phone', null, ['class' => 'form-control input_infomation_clear']) !!}
                            {!! $errors->first('applicant_phone', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('applicant_fax') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_fax', 'แฟกซ์', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_fax', null, ['class' => 'form-control input_infomation_clear']) !!}
                            {!! $errors->first('applicant_fax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_mobile') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_mobile', 'มือถือ', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_mobile', null, ['class' => 'form-control input_infomation_clear', 'required' => true]) !!}
                            {!! $errors->first('applicant_mobile', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_email') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_email', 'E-mail', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_email', null, ['class' => 'form-control input_infomation_clear', 'required' => true]) !!}
                            {!! $errors->first('applicant_email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4 text-left"><h4>ที่อยู่</h4></label>
                        <div class="col-md-9">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('inspectors_address') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_address', null,['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('inspectors_address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('inspectors_soi') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_soi', null,  ['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('inspectors_soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('inspectors_moo') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_moo', null,['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('inspectors_moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('inspectors_road') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_road', 'ถนน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_road', null,['class' => 'form-control input_infomation', 'readonly' => true ]) !!}
                            {!! $errors->first('inspectors_road', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row box_inspectors_address_seach">
                <div class="col-md-6">
                    <div class="form-group ">
                        {!! Form::label('inspectors_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_address_seach', null,  ['class' => 'form-control inspectors_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('inspectors_subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_subdistrict_txt', null,  ['class' => 'form-control inspectors_input_search', 'required' => true, 'disabled' => true ]) !!}
                            {!! $errors->first('inspectors_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('inspectors_district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_district_txt',null,['class' => 'form-control inspectors_input_search', 'required' => true, 'disabled' => true ]) !!}
                            {!! $errors->first('inspectors_district_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('inspectors_province_txt') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_province_txt', null,  ['class' => 'form-control inspectors_input_search', 'required' => true, 'disabled' => true ]) !!}
                            {!! $errors->first('inspectors_province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('inspectors_zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('inspectors_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('inspectors_zipcode_txt', null,['class' => 'form-control inspectors_input_search', 'required' => true, 'disabled' => true ]) !!}
                            {!! $errors->first('inspectors_zipcode_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                {!! Form::hidden('inspectors_subdistrict', null, [ 'class' => 'input_infomation inspectors_input_search', 'id' => 'inspectors_subdistrict' ] ) !!}
                {!! Form::hidden('inspectors_district', null, [ 'class' => 'input_infomation inspectors_input_search', 'id' => 'inspectors_district' ] ) !!}
                {!! Form::hidden('inspectors_province', null, [ 'class' => 'input_infomation inspectors_input_search', 'id' => 'inspectors_province' ] ) !!}
                {!! Form::hidden('inspectors_zipcode', null, [ 'class' => 'input_infomation inspectors_input_search', 'id' => 'inspectors_zipcode' ] ) !!}
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4 text-left"><h4>หน่วยงาน</h4></label>
                        <div class="col-md-9">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('agency_name') ? 'has-error' : ''}}">
                        {!! Form::label('agency_name', 'ชื่อหน่วยงาน', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_name', null,['class' => 'form-control']) !!}
                            {!! $errors->first('agency_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div style="text-align: right !important; padding-top:0px !important; margin-bottom: 5px; font-style: italic; color: gray">(กรอกชื่อหน่วยงาน 10 ตัวอักษรขึ้นไป หรือกรอกเลขประจำตัวผู้เสียภาษีอากรเพื่อค้นหา)</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('agency_taxid') ? 'has-error' : ''}}">
                        {!! Form::label('agency_taxid', 'เลขประจำตัวผู้เสียภาษีอากร', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_taxid', null,['class' => 'form-control', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('agency_taxid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('agency_address') ? 'has-error' : ''}}">
                        {!! Form::label('agency_address', 'ที่ตั้งเลขที่', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_address', null,['class' => 'form-control', 'readonly' => true ]) !!}
                            {!! $errors->first('agency_address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_moo') ? 'has-error' : ''}}">
                        {!! Form::label('agency_moo', 'หมู่ที่', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_moo', null,  ['class' => 'form-control', 'readonly' => true ]) !!}
                            {!! $errors->first('agency_moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_soi') ? 'has-error' : ''}}">
                        {!! Form::label('agency_soi', 'ตรอก/ซอย', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_soi', null,  ['class' => 'form-control', 'readonly' => true ]) !!}
                            {!! $errors->first('agency_soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_road') ? 'has-error' : ''}}">
                        {!! Form::label('agency_road', 'ถนน', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_road', null, ['class' => 'form-control', 'readonly' => true ]) !!}
                            {!! $errors->first('agency_road', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('agency_subdistrict_txt', 'ตำบล/แขวง', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_subdistrict_txt', null, ['class' => 'form-control agency_input_show', 'disabled' => true ]) !!}
                            {!! $errors->first('agency_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('agency_district_txt', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_district_txt', null,['class' => 'form-control agency_input_show', 'disabled' => true ]) !!}
                            {!! $errors->first('agency_district_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_province_txt') ? 'has-error' : ''}}">
                        {!! Form::label('agency_province_txt', 'จังหวัด', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_province_txt', null, ['class' => 'form-control agency_input_show', 'disabled' => true ]) !!}
                            {!! $errors->first('agency_province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('agency_zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('agency_zipcode_txt', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('agency_zipcode_txt', null,['class' => 'form-control agency_input_show', 'disabled' => true ]) !!}
                            {!! $errors->first('agency_zipcode_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {!! Form::hidden('agency_id', null, [ 'class' => 'agency_input_show', 'id' => 'agency_id' ] ) !!}
                {!! Form::hidden('agency_subdistrict', null, [ 'class' => 'agency_input_show', 'id' => 'agency_subdistrict' ] ) !!}
                {!! Form::hidden('agency_district', null, [ 'class' => 'agency_input_show', 'id' => 'agency_district' ] ) !!}
                {!! Form::hidden('agency_province', null, [ 'class' => 'agency_input_show', 'id' => 'agency_province' ] ) !!}
                {!! Form::hidden('agency_zipcode', null, [ 'class' => 'agency_input_show', 'id' => 'agency_zipcode' ] ) !!}
            </div>

        </fieldset>

    </div>
</div>


@push('js')
    <script>
        jQuery(document).ready(function() {

            $('#agency_name').typeahead({
                minLength: 10,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-users") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    $('#agency_name').val(jsondata.name_full);
                    $('#agency_taxid').val(jsondata.taxid);

                    $('#agency_address').val(jsondata.hq_address_no);
                    $('#agency_building').val(jsondata.hq_building);
                    $('#agency_soi').val(jsondata.hq_soi);
                    $('#agency_moo').val(jsondata.hq_moo);
                    $('#agency_road').val(jsondata.hq_street);

                    $('#agency_subdistrict_txt').val(jsondata.hq_subdistrict_title);
                    $('#agency_subdistrict').val(jsondata.hq_subdistrict_id);

                    $('#agency_district_txt').val(jsondata.hq_district_title);
                    $('#agency_district').val(jsondata.hq_district_id);

                    $('#agency_province_txt').val(jsondata.hq_province_title);
                    $('#agency_province').val(jsondata.hq_province_id);

                    $('#agency_zipcode').val(jsondata.hq_zipcode);
                    $('#agency_zipcode_txt').val(jsondata.hq_zipcode);

                    $('#agency_id').val(jsondata.id);

                }
            });

            $('#filter_search').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-users") }}', { applicanttype_id: '2', query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    var prefix_name       = checkNone( jsondata.prefix_name )?jsondata.prefix_name:'';
                    var person_first_name = checkNone( jsondata.person_first_name )?jsondata.person_first_name:'';
                    var person_last_name  = checkNone( jsondata.person_last_name )?jsondata.person_last_name:'';
                    var taxid             = checkNone( jsondata.taxid )?jsondata.taxid:'';
                    var date_of_birth     = checkNone( jsondata.date_of_birth_format )?jsondata.date_of_birth_format:'';

                    var email             = checkNone( jsondata.email )?jsondata.email:'';
                    var phone             = checkNone( jsondata.contact_tel )?jsondata.contact_tel:'';
                    var mobile            = checkNone( jsondata.contact_phone_number )?jsondata.contact_phone_number:'';
                    var fax               = checkNone( jsondata.fax )?jsondata.fax:'';

                    $('#inspectors_user_id').val(jsondata.id);

                    $('#applicant_prefix').val( prefix_name ).trigger('change.select2');
                    if( jsondata.applicanttype_id != "2" ){
                        $('#applicant_first_name').val(jsondata.name_full); 
                    }else{//บุคคลธรรมดา
                        $('#applicant_first_name').val(person_first_name); 
                    }
 
                    $('#applicant_last_name').val(person_last_name); 
                    $('#applicant_taxid').val(taxid); 
                    $('#applicant_date_of_birth').val(date_of_birth);

                    $('#applicant_mobile').val(mobile);
                    $('#applicant_phone').val(phone);
                    $('#applicant_fax').val(fax);
                    $('#applicant_email').val(email);

                    //ที่อยู่
                    $('#inspectors_address').val(jsondata.hq_address_no);
                    $('#inspectors_building').val(jsondata.hq_building);
                    $('#inspectors_soi').val(jsondata.hq_soi);
                    $('#inspectors_moo').val(jsondata.hq_moo);
                    $('#inspectors_road').val(jsondata.hq_street);
                    $('#inspectors_subdistrict_txt').val(jsondata.hq_subdistrict_title);
                    $('#inspectors_district_txt').val(jsondata.hq_district_title);
                    $('#inspectors_province_txt').val(jsondata.hq_province_title);
                    $('#inspectors_zipcode_txt').val(jsondata.hq_zipcode);
                    $('#inspectors_subdistrict').val(jsondata.hq_subdistrict_id);
                    $('#inspectors_district').val(jsondata.hq_district_id);
                    $('#inspectors_province').val(jsondata.hq_province_id);
                    $('#inspectors_zipcode').val(jsondata.hq_zipcode);
                    $('#inspectors_phone').val(jsondata.phone);
                    $('#inspectors_fax').val(jsondata.fax);

                    $('#filter_search').val('');

                }
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

            $("#inspectors_address_seach").select2({
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

            $("#inspectors_address_seach").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#inspectors_subdistrict_txt').val(jsondata.sub_title);
                        $('#inspectors_district_txt').val(jsondata.dis_title);
                        $('#inspectors_province_txt').val(jsondata.pro_title);
                        $('#inspectors_zipcode_txt').val(jsondata.zip_code);

                        $('#inspectors_subdistrict_id').val(jsondata.sub_ids);
                        $('#inspectors_district_id').val(jsondata.dis_id);
                        $('#inspectors_province_id').val(jsondata.pro_id);
                        $('#inspectors_zipcode').val(jsondata.zip_code);

                    }
                });
            });
            
        });

        function SetInputInfomaion( condition ){

            if( condition == 1){ //ค้นหา
                //Claer Input
                $('input.input_infomation').val('');
                $('input.input_infomation_clear').val('');
                $('select.input_infomation').val('').trigger('change.select2');

                //Set Input
                $('#filter_search').prop('disabled', false);    
                $('.input_infomation').prop('readonly', true);

                //Remove Class
                $('#applicant_date_of_birth').datepicker( "destroy" );
                $('#applicant_date_of_birth').removeClass('mydatepicker');

                $('.box_inspectors_address_seach').hide();
                $('.box_inspectors_address_seach').prop('disabled', true);


            }else{
                //Claer Input
                $('input.input_infomation').val('');
                $('input.input_infomation_clear').val('');
                $('select.input_infomation').val('').trigger('change.select2');

                //Set Input
                $('#filter_search').prop('disabled', true);    
                $('.input_infomation').prop('readonly', false);

                //Add Class
                $('#applicant_date_of_birth').addClass('mydatepicker');

                $('.box_inspectors_address_seach').show();
                $('.box_inspectors_address_seach').prop('disabled', false);
            }
        }
    </script>
@endpush
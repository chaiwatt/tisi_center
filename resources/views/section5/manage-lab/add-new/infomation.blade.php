<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลหน่วยตรวจสอบ (LAB)</h5></legend>

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
                    <div class="form-group">
                        <label class="control-label col-md-4"><h4>ข้อมูลผู้ยื่นขอรับการแต่งตั้ง</h4></label>
                        <div class="col-md-9">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('applicant_name') ? 'has-error' : ''}}">
                        {!! Form::label('applicant_name', 'ชื่อผู้ยื่นขอรับการแต่งตั้ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_name', null,['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! Form::hidden('lab_user_id', null, [ 'class' => 'input_infomation', 'id' => 'lab_user_id' ] ) !!}
                            {!! $errors->first('applicant_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required">
                        {!! Form::label('applicant_taxid', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::text('applicant_taxid', null, ['class' => 'form-control input_infomation', 'required' => true ]) !!}
                            {!! $errors->first('applicant_taxid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4"><h4>ข้อมูลห้องปฏิบัติการ</h4></label>
                        <div class="col-md-9">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_name') ? 'has-error' : ''}}">
                        {!! Form::label('lab_name', 'ชื่อห้องปฏิบัติการ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_name', null,['class' => 'form-control input_lab_infomation', 'required' => true ]) !!}
                            {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_address') ? 'has-error' : ''}}">
                        {!! Form::label('lab_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_address', null,['class' => 'form-control input_lab_infomation', 'required' => true ]) !!}
                            {!! $errors->first('lab_address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('lab_building') ? 'has-error' : ''}}">
                        {!! Form::label('lab_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_building', null,  ['class' => 'form-control input_lab_infomation', ]) !!}
                            {!! $errors->first('lab_building', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('lab_soi') ? 'has-error' : ''}}">
                        {!! Form::label('lab_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_soi', null,  ['class' => 'form-control input_lab_infomation' ]) !!}
                            {!! $errors->first('lab_soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('lab_moo') ? 'has-error' : ''}}">
                        {!! Form::label('lab_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_moo', null,['class' => 'form-control input_lab_infomation']) !!}
                            {!! $errors->first('lab_moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        {!! Form::label('lab_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_address_seach', null,  ['class' => 'form-control lab_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                            {!! $errors->first('lab_address_seach', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('lab_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_subdistrict_txt', null,  ['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('lab_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('lab_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_district_txt', null,['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('lab_district_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_province_txt') ? 'has-error' : ''}}">
                        {!! Form::label('lab_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_province_txt', null,  ['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
                            {!! $errors->first('lab_province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('lab_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_zipcode_txt', null,['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true  ]) !!}
                            {!! $errors->first('lab_zipcode_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('lab_phone') ? 'has-error' : ''}}">
                        {!! Form::label('lab_phone', 'เบอร์โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_phone', null,['class' => 'form-control input_lab_infomation', 'required' => true ]) !!}
                            {!! $errors->first('lab_phone', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('lab_fax') ? 'has-error' : ''}}">
                        {!! Form::label('lab_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('lab_fax', null,  ['class' => 'form-control input_lab_infomation', ]) !!}
                            {!! $errors->first('lab_fax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                {!! Form::hidden('lab_subdistrict_id', null, [ 'class' => 'lab_input_show', 'id' => 'lab_subdistrict_id' ] ) !!}
                {!! Form::hidden('lab_district_id', null, [ 'class' => 'lab_input_show', 'id' => 'lab_district_id' ] ) !!}
                {!! Form::hidden('lab_province_id', null, [ 'class' => 'lab_input_show', 'id' => 'lab_province_id' ] ) !!}
                {!! Form::hidden('lab_zipcode', null, [ 'class' => 'lab_input_show', 'id' => 'lab_zipcode' ] ) !!}
            </div>
        
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4"><h4>ผู้ประสานงาน</h4></label>
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
                            {!! Form::text('co_name', null,['class' => 'form-control co_input_show co_input_readonly', 'required' => true ]) !!}
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
        jQuery(document).ready(function() {

            $('#filter_search').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-users") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    $('#lab_user_id').val(jsondata.id);
                    $('#applicant_name').val(jsondata.name_full);
                    $('#applicant_taxid').val(jsondata.taxid);

                    //ที่อยู่
                    $('#lab_name').val(jsondata.name_full);
                    $('#lab_address').val(jsondata.hq_address_no);
                    $('#lab_building').val(jsondata.hq_building);
                    $('#lab_soi').val(jsondata.hq_soi);
                    $('#lab_moo').val(jsondata.hq_moo);
                    $('#lab_road').val(jsondata.hq_street);
                    $('#lab_subdistrict_txt').val(jsondata.hq_subdistrict_title);
                    $('#lab_district_txt').val(jsondata.hq_district_title);
                    $('#lab_province_txt').val(jsondata.hq_province_title);
                    $('#lab_zipcode_txt').val(jsondata.hq_zipcode);
                    $('#lab_subdistrict_id').val(jsondata.hq_subdistrict_id);
                    $('#lab_district_id').val(jsondata.hq_district_id);
                    $('#lab_province_id').val(jsondata.hq_province_id);
                    $('#lab_zipcode').val(jsondata.hq_zipcode);
                    $('#lab_phone').val(jsondata.phone);
                    $('#lab_fax').val(jsondata.fax);

                    //ผู้ประสานงาน
                    $('#co_name').val(jsondata.contact_full_name);
                    $('#co_position').val(jsondata.contact_position);
                    $('#co_mobile').val(jsondata.contact_phone_number);
                    $('#co_tel').val(jsondata.contact_tel);
                    $('#co_fax').val(jsondata.contact_fax);
                    $('#co_email').val(jsondata.email);

                    $('#filter_search').val('');

                    tableCer.draw();
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

        });
        function SetInputInfomaion( condition ){

            if( condition == 1){ //ค้นหา
                //Claer Input
                $('input.input_infomation').val('');
                $('input.lab_input_show').val('');
                $('input.input_lab_infomation').val('');
                $('select.lab_address_seach').val('').trigger('change.select2');
                $('input.co_input_show').val('');
                $('#filter_search').val('');

                //Set Input
                $('#filter_search').prop('disabled', false);    
                $('.input_infomation').prop('readonly', true);

            }else{
                //Claer Input
                $('input.input_infomation').val('');
                $('input.lab_input_show').val('');
                $('input.input_lab_infomation').val('');
                $('select.lab_address_seach').val('').trigger('change.select2');
                $('input.co_input_show').val('');
                $('#filter_search').val('');

                //Set Input
                $('#filter_search').prop('disabled', true);    
                $('.input_infomation').prop('readonly', false);

            }
        }
    </script>
@endpush
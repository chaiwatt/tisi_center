
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h3 class="m-t-0">ส่วนที่ 1 : ข้อมูลผู้ต้องหา/ผู้กระทำความผิด</h3></legend>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        {!! Form::label('filter_search', 'กรอกเพื่อค้นหา'.' :', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            <div class="input-group m-t-10">
                                {!! Form::text('filter_search', null,['class' => 'form-control', 'required' => false, 'disabled' => false ]) !!}
                                <span class="input-group-btn">
                                    <button id="btn_search" type="button" class="btn waves-effect waves-light btn-info"><i class="fa fa-search"></i> ค้นหา</button>
                                </span>
                            </div>
                            <p class="m-t-10 text-primary">(ค้นหาจาก: เลขประจำตัวผู้เสียภาษี/เลขประจำตัวผู้เสียภาษี + สาขา/ชื่อ-สกุลผู้กระทำความผิด)</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'ชื่อผู้กระทำความผิด'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', null,['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ]) !!}
                            {!! Form::hidden('sso_users_id', null, [ 'class' => 'input_infomation', 'id' => 'sso_users_id' ] ) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('taxid') ? 'has-error' : ''}}">
                        {!! Form::label('taxid', 'เลขประจำตัวผู้เสียภาษีอากร'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('taxid', null,  ['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true , 'id' => 'taxid']) !!}
                            {!! $errors->first('taxid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('date_niti') ? 'has-error' : ''}}">
                        {!! Form::label('date_niti', 'วันที่จดทะเบียนนิติบุคคล'.' :', ['class' => 'col-md-4 control-label text-left']) !!}
                        <div class="col-md-8">
                            <div class="input-group">
                                {!! Form::text('date_niti_show', null,  ['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true ,'id' => 'date_niti_show' ]) !!}
                                {!! $errors->first('applicant_date_niti', '<p class="help-block">:message</p>') !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                {!! Form::hidden('date_niti', null, [ 'class' => 'form-control input_infomation', 'id' => 'date_niti' ] ) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('type_id') ? 'has-error' : ''}}">
                        {!! Form::label('type_id', 'ประเภท'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('type_show', null,  ['class' => 'form-control input_infomation', 'required' => true, 'readonly' => true , 'id' => 'type_show']) !!}
                            {!! Form::hidden('type_id', null, [ 'class' => 'input_infomation', 'id' => 'sso_users_id' ] ) !!}
                            {!! $errors->first('type_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <div class="divider divider-left divider-secondary">
                        <div class="divider-text">ที่อยู่สำนักงานใหญ่</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('address_no') ? 'has-error' : ''}}">
                        {!! Form::label('address_no', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('address_no', null,['class' => 'form-control input_infomation']) !!}
                            {!! $errors->first('address_no', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('building') ? 'has-error' : ''}}">
                        {!! Form::label('building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('building', null,  ['class' => 'form-control input_infomation']) !!}
                            {!! $errors->first('building', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('soi') ? 'has-error' : ''}}">
                        {!! Form::label('soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('soi', null,  ['class' => 'form-control input_infomation']) !!}
                            {!! $errors->first('soi', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('moo') ? 'has-error' : ''}}">
                        {!! Form::label('moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('moo', null,['class' => 'form-control input_infomation']) !!}
                            {!! $errors->first('moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('street') ? 'has-error' : ''}}">
                        {!! Form::label('street', 'ถนน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('street', null,['class' => 'form-control input_infomation']) !!}
                            {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row box_address_seach">
                <div class="col-md-6">
                    <div class="form-group ">
                        {!! Form::label('address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('address_seach', null,  ['class' => 'form-control address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('subdistrict_txt') ? 'has-error' : ''}}">
                        {!! Form::label('subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('subdistrict_txt', null,  ['class' => 'form-control address_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('subdistrict_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('district_txt') ? 'has-error' : ''}}">
                        {!! Form::label('district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('district_txt',null,['class' => 'form-control address_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('district_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('province_txt') ? 'has-error' : ''}}">
                        {!! Form::label('province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('province_txt', null,  ['class' => 'form-control address_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('province_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('zipcode_txt') ? 'has-error' : ''}}">
                        {!! Form::label('zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('zipcode_txt', null,['class' => 'form-control address_input_search', 'disabled' => true ]) !!}
                            {!! $errors->first('zipcode_txt', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {!! Form::hidden('subdistrict_id', null, [ 'class' => 'input_infomation address_input_search', 'id' => 'subdistrict_id' ] ) !!}
                {!! Form::hidden('district_id', null, [ 'class' => 'input_infomation address_input_search', 'id' => 'district_id' ] ) !!}
                {!! Form::hidden('province_id', null, [ 'class' => 'input_infomation address_input_search', 'id' => 'province_id' ] ) !!}
                {!! Form::hidden('zipcode', null, [ 'class' => 'input_infomation address_input_search', 'id' => 'zipcode' ] ) !!}
                {!! Form::hidden('phone', null, [ 'class' => 'input_infomation input_show', 'id' => 'phone' ] ) !!}
                {!! Form::hidden('fax', null, [ 'class' => 'input_infomation input_show', 'id' => 'fax' ] ) !!}
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <div class="divider divider-left divider-secondary">
                        <div class="divider-text">ผู้ประสานงาน</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('contact_name') ? 'has-error' : ''}}">
                        {!! Form::label('contact_name', 'ชื่อผู้ประสานงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('contact_name', null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                            {!! $errors->first('contact_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required {{ $errors->has('contact_position') ? 'has-error' : ''}}">
                        {!! Form::label('contact_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('contact_position', null,  ['class' => 'form-control contact_input_show', 'required' => true ]) !!}
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
                            {!! Form::text('contact_mobile', null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                            {!! $errors->first('contact_mobile', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contact_tel') ? 'has-error' : ''}}">
                        {!! Form::label('contact_tel', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('contact_tel', null,  ['class' => 'form-control contact_input_show' ]) !!}
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
                            {!! Form::text('contact_fax', null,['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                            {!! $errors->first('contact_fax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group required{{ $errors->has('contact_email') ? 'has-error' : ''}}">
                        {!! Form::label('contact_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('contact_email', null,  ['class' => 'form-control contact_input_show', 'required' => true ]) !!}
                            {!! $errors->first('contact_email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <div class="divider divider-left divider-secondary">
                        <div class="divider-text">กรรมการบริษัท</div>
                    </div>
                </div>
            </div>

            <div class="row repeater-power">
                <div class="col-md-6"  data-repeater-list="repeater-power" >

                    <div class="form-group" data-repeater-item>
                        {!! Form::label('power', ' ชื่อกรรมการ'.' :', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('power', null,  ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-outline btn-sm btn_power_remove" data-repeater-delete>
                                <i class="fa fa-times"></i>
                            </button> 
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6" >
                    <button type="button" class="btn btn-success btn-outline btn-sm" data-repeater-create>
                        <i class="fa fa-plus"></i>
                    </button> 
                </div>
 
            </div>

        </fieldset>
    </div>
</div>


@push('js')

    <script type="text/javascript">

        $(document).ready(function() {

            $('.repeater-power').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeletePower();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeletePower();
                        }, 500);
                    }
                }
            });

            BtnDeletePower();

            $("#address_seach").select2({
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

            $("#address_seach").on('change', function () {

                if(  $(this).val() != ''){
                    $.ajax({
                        url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                    }).done(function( jsondata ) {
                        if(jsondata != ''){

                            $('#subdistrict_txt').val(jsondata.sub_title);
                            $('#district_txt').val(jsondata.dis_title);
                            $('#province_txt').val(jsondata.pro_title);
                            $('#zipcode_txt').val(jsondata.zip_code);

                            $('#subdistrict_id').val(jsondata.sub_ids);
                            $('#district_id').val(jsondata.dis_id);
                            $('#province_id').val(jsondata.pro_id);
                            $('#zipcode').val(jsondata.zip_code);

                            $("#address_seach").select2("val", "");

                        }
                    });
                }

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
                    
                    $('#sso_users_id').val(jsondata.id);
                    $('#name').val(jsondata.name_full);
                    $('#taxid').val(jsondata.taxid);

                    var date_niti = '';
                    var date_niti_show = '';

                    if( jsondata.applicanttype_id != "2" ){
                        date_niti = jsondata.date_niti;
                        date_niti_show = jsondata.date_niti_format;
                    }else{
                        date_niti = jsondata.date_of_birth;
                        date_niti_show = jsondata.date_of_birth_format;
                    }
                    
                    $('#type_id').val(jsondata.applicanttype_id);
                    $('#type_show').val(jsondata.applicanttype);

                    $('#date_niti').val(date_niti);
                    $('#date_niti_show').val(date_niti_show);

                    //ที่อยู่สำนักงานใหญ่
                    $('#address_no').val(jsondata.hq_address_no);
                    $('#building').val(jsondata.hq_building);
                    $('#soi').val(jsondata.hq_soi);
                    $('#moo').val(jsondata.hq_moo);
                    $('#street').val(jsondata.hq_street);
                    $('#subdistrict_txt').val(jsondata.hq_subdistrict_title);
                    $('#district_txt').val(jsondata.hq_district_title);
                    $('#province_txt').val(jsondata.hq_province_title);
                    $('#zipcode_txt').val(jsondata.hq_zipcode);
                    $('#subdistrict').val(jsondata.hq_subdistrict_id);
                    $('#district').val(jsondata.hq_district_id);
                    $('#province').val(jsondata.hq_province_id);
                    $('#zipcode').val(jsondata.hq_zipcode);

                    //ผู้ประสานงาน
                    $('#contact_name').val(jsondata.contact_full_name);
                    $('#contact_position').val(jsondata.contact_position);
                    $('#contact_mobile').val(jsondata.contact_phone_number);
                    $('#contact_tel').val(jsondata.contact_tel);
                    $('#contact_fax').val(jsondata.contact_fax);
                    $('#contact_email').val(jsondata.email);

                    $('#filter_search').val('');

                    option_license = [];

                    if( jsondata.taxid != '' ){

                        $.LoadingOverlay("show", {
                            image: "",
                            text: "กำลังโหลดข้อมูลใบอนุญาต..."
                        });
    
                        $.ajax({
                            url: "{!! url('/funtions/search-tb4tisilicense') !!}" + "?taxpayer_query=" + jsondata.taxid 
                        }).done(function( object ) {

                            if( checkNone(object) ){
                                $.each(object, function( index, data ) {
                                    option_license.push(data);
                                });
                               
                                LoadSelectTisilicense( $('select.offend_license_number') );
                            }

                            $.LoadingOverlay("hide");
                            
                        });
                    }

                    $('.repeater-cases').find('[data-repeater-item]').slice(1).empty();
                    $('.repeater-power').find('[data-repeater-item]').slice(1).empty();

                    EmptyCases();
                    reBuiltIcheck();


                }
            });

        });
        function BtnDeletePower(){
            if( $('.btn_power_remove').length <= 1 ){
                $('.btn_power_remove:first').hide();   
            }else{
                $('.btn_power_remove').show();
            }
        }

        function LoadSelectTisilicense( select ){

            $(select).html('<option value=""> - เลขที่ใบอนุญาต - </option>');
            if( option_license.length > 0){
                $.each(option_license, function( index, data ) {
                    $(select).append('<option value="'+data.id+'" data-license_no="'+data.license_no+'" data-tisi_id="'+data.tis_id+'" data-tis_no="'+data.tis_no+'" data-tis_name="'+data.tis_name+'">'+data.license_no+'</option>');
                });
            }

        }

        function EmptyCases(){
            $('.repeater-cases').find('input[type=text]').val('');
            $('.repeater-cases').find('select').select2('val','');
        }
    </script>
@endpush
<!-- /.modal-dialog -->
{!! Form::model($ibcb, [
    'method' => 'PATCH',
    'url' => ['/section5/ibcb/update-ibcb-save', $ibcb->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}

<div id="Medit" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ข้อมูลหน่วยตรวจสอบ IB/CB</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="col-md-12">

                    <fieldset class="white-box">
                        <legend class="legend"><h5>ข้อมูลหน่วยตรวจสอบ</h5></legend>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('ibcb_name', 'ชื่อหน่วยงาน:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('ibcb_name', null, ['class' => 'form-control','required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('initial', 'ชื่อย่อหน่วยรับรอง:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('initial', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('name', 'ผู้ยื่นขอ:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('name', null, ['class' => 'form-control','disabled' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('taxid', 'เลขนิติบุคคล:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('taxid', null, ['class' => 'form-control','disabled' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('ibcb_code', 'รหัสหน่วยตรวจสอบ:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('ibcb_code', null, ['class' => 'form-control','disabled' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row{{ $errors->has('emails') ? 'has-error' : ''}}">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        {!! Form::checkbox('cancel_state', '1', (!empty($ibcb->ibcb_end_date) ?true: false), ['class' => 'form-control  check', 'data-checkbox' => 'icheckbox_minimal-blue' ,'id'=>'cancel_state' ]) !!}
                                        <label for="cancel_state" class="control-label"> ยกเลิกการเป็นหน่วยตรวจสอบ</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="box_cancel">
                            <div class="col-md-12">
                                <div class="form-group required">
                                    {!! Form::label('ibcb_end_date', 'วันที่มีผล:', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            {!! Form::text('ibcb_end_date', !empty( $ibcb->ibcb_end_date )?HP::revertDate($ibcb->ibcb_end_date,true):null ,  ['class' => 'form-control mydatepicker']) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="white-box">
                        <legend class="legend"><h5>ที่อยู่</h5></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('ibcb_address') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_address', !empty( $ibcb->ibcb_address )?$ibcb->ibcb_address:null,['class' => 'form-control', 'required' => true ]) !!}
                                        {!! $errors->first('ibcb_address', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('ibcb_building') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_building', !empty( $ibcb->ibcb_building )?$ibcb->ibcb_building:null,  ['class' => 'form-control', ]) !!}
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
                                        {!! Form::text('ibcb_soi', !empty( $ibcb->ibcb_soi )?$ibcb->ibcb_soi:null,  ['class' => 'form-control' ]) !!}
                                        {!! $errors->first('ibcb_soi', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('ibcb_moo') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_moo', !empty( $ibcb->ibcb_moo )?$ibcb->ibcb_moo:null,['class' => 'form-control']) !!}
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
                                        {!! Form::text('ibcb_subdistrict_txt', !empty( $ibcb->IbcbSubdistrictName )?$ibcb->IbcbSubdistrictName:null,  ['class' => 'form-control ibcb_input_show', 'required' => true, 'readonly' => true ]) !!}
                                        {!! $errors->first('ibcb_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('ibcb_district_txt') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_district_txt', !empty( $ibcb->IbcbDistrictName )?$ibcb->IbcbDistrictName:null,['class' => 'form-control ibcb_input_show', 'required' => true, 'readonly' => true ]) !!}
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
                                        {!! Form::text('ibcb_province_txt', !empty( $ibcb->IbcbProvinceName )?$ibcb->IbcbProvinceName:null,  ['class' => 'form-control ibcb_input_show', 'required' => true, 'readonly' => true ]) !!}
                                        {!! $errors->first('ibcb_province_txt', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('ibcb_zipcode_txt') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_zipcode_txt', !empty( $ibcb->ibcb_zipcode )?$ibcb->ibcb_zipcode:null,['class' => 'form-control ibcb_input_show', 'required' => true, 'readonly' => true  ]) !!}
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
                                        {!! Form::text('ibcb_phone', !empty( $ibcb->ibcb_phone )?$ibcb->ibcb_phone:null,['class' => 'form-control', 'required' => true ]) !!}
                                        {!! $errors->first('ibcb_phone', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('ibcb_fax') ? 'has-error' : ''}}">
                                    {!! Form::label('ibcb_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ibcb_fax', !empty( $ibcb->ibcb_fax )?$ibcb->ibcb_fax:null,  ['class' => 'form-control', ]) !!}
                                        {!! $errors->first('ibcb_fax', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="row">
                            {!! Form::hidden('ibcb_subdistrict_id', !empty( $ibcb->ibcb_subdistrict_id )?$ibcb->ibcb_subdistrict_id:null, [ 'class' => 'ibcb_input_show', 'id' => 'ibcb_subdistrict_id' ] ) !!}
                            {!! Form::hidden('ibcb_district_id', !empty( $ibcb->ibcb_district_id )?$ibcb->ibcb_district_id:null, [ 'class' => 'ibcb_input_show', 'id' => 'ibcb_district_id' ] ) !!}
                            {!! Form::hidden('ibcb_province_id', !empty( $ibcb->ibcb_province_id )?$ibcb->ibcb_province_id:null, [ 'class' => 'ibcb_input_show', 'id' => 'ibcb_province_id' ] ) !!}
                            {!! Form::hidden('ibcb_zipcode', !empty( $ibcb->ibcb_zipcode )?$ibcb->ibcb_zipcode:null, [ 'class' => 'ibcb_input_show', 'id' => 'ibcb_zipcode' ] ) !!}
                        </div>

                    </fieldset>

                    <fieldset class="white-box">
                        <legend class="legend"><h5>ผู้ประสานงาน</h5></legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('co_name') ? 'has-error' : ''}}">
                                    {!! Form::label('co_name', 'ชื่อผู้ประสานงาน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('co_name', !empty( $ibcb->co_name )?$ibcb->co_name:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                        {!! $errors->first('co_name', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required {{ $errors->has('co_position') ? 'has-error' : ''}}">
                                    {!! Form::label('co_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('co_position', !empty( $ibcb->co_position )?$ibcb->co_position:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
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
                                        {!! Form::text('co_mobile', !empty( $ibcb->co_mobile )?$ibcb->co_mobile:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                        {!! $errors->first('co_mobile', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('co_phone') ? 'has-error' : ''}}">
                                    {!! Form::label('co_phone', ' โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('co_phone', !empty( $ibcb->co_phone )?$ibcb->co_phone:null,  ['class' => 'form-control co_input_show' ]) !!}
                                        {!! $errors->first('co_phone', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('co_fax') ? 'has-error' : ''}}">
                                    {!! Form::label('co_fax', 'โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('co_fax', !empty( $ibcb->co_fax )?$ibcb->co_fax:null,['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                        {!! $errors->first('co_fax', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('co_email') ? 'has-error' : ''}}">
                                    {!! Form::label('co_email', ' อีเมล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('co_email', !empty( $ibcb->co_email )?$ibcb->co_email:null,  ['class' => 'form-control co_input_show', 'required' => true ]) !!}
                                        {!! $errors->first('co_email', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" type="submit" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>


{!! Form::close() !!}

@push('js')

    <script>
        jQuery(document).ready(function() {
            
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

            $('#cancel_state').on('ifChecked', function(event){
                BoxCancel( 1 );
            });

            $('#cancel_state').on('ifUnchecked', function(event){
                BoxCancel( 0 );
            });
            BoxCancel(  $('input[name="cancel_state"]:checked').val() );
        });

        function BoxCancel( val ){

            if( val == 1 ){
                $('#box_cancel').show();
                $('#box_cancel').find('input[type="text"], textarea, select').prop('required', true);
            }else{
                $('#box_cancel').hide();
                $('#box_cancel').find('input[type="text"], textarea, select').prop('required', false);
                $('#box_cancel').find('input[type="text"]').val('');
            }

        }
    </script>

@endpush

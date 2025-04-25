{!! Form::model($inspector, [
    'method' => 'PATCH',
    'url' => ['/section5/inspectors/infomation-save', $inspector->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}


<div id="MdAddress" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px;max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ที่อยู่</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspectors_address') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_address', !empty( $inspector->inspectors_address )?$inspector->inspectors_address:null,['class' => 'form-control', 'required' => true ]) !!}
                                {!! $errors->first('inspectors_address', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('inspectors_moo') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_moo', !empty( $inspector->inspectors_moo )?$inspector->inspectors_moo:null,['class' => 'form-control']) !!}
                                {!! $errors->first('inspectors_moo', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('inspectors_soi') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_soi', 'ตรอก/ซอย'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_soi', !empty( $inspector->inspectors_soi )?$inspector->inspectors_soi:null,  ['class' => 'form-control' ]) !!}
                                {!! $errors->first('inspectors_soi', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('inspectors_road') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_road', 'ถนน'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_road', !empty( $inspector->inspectors_road )?$inspector->inspectors_road:null,['class' => 'form-control']) !!}
                                {!! $errors->first('inspectors_road', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group ">
                            {!! Form::label('inspector_address_seach', 'ค้นหาที่อยู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspector_address_seach', null,  ['class' => 'form-control inspector_address_seach', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหาที่อยู่' ]) !!}
                                {!! $errors->first('inspector_address_seach', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspector_subdistrict_txt') ? 'has-error' : ''}}">
                            {!! Form::label('inspector_subdistrict_txt', 'แขวง/ตำบล'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspector_subdistrict_txt', !empty( $inspector->InspectorSubdistrictName )?$inspector->InspectorSubdistrictName:null,  ['class' => 'form-control inspector_input_show', 'required' => true, 'readonly' => true ]) !!}
                                {!! $errors->first('inspector_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspector_district_txt') ? 'has-error' : ''}}">
                            {!! Form::label('inspector_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspector_district_txt', !empty( $inspector->InspectorDistrictName )?$inspector->InspectorDistrictName:null,['class' => 'form-control inspector_input_show', 'required' => true, 'readonly' => true ]) !!}
                                {!! $errors->first('inspector_district_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspector_province_txt') ? 'has-error' : ''}}">
                            {!! Form::label('inspector_province_txt', 'จังหวัด'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspector_province_txt', !empty( $inspector->InspectorProvinceName )?$inspector->InspectorProvinceName:null,  ['class' => 'form-control inspector_input_show', 'required' => true, 'readonly' => true ]) !!}
                                {!! $errors->first('inspector_province_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspector_zipcode_txt') ? 'has-error' : ''}}">
                            {!! Form::label('inspector_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspector_zipcode_txt', !empty( $inspector->inspectors_zipcode )?$inspector->inspectors_zipcode:null,['class' => 'form-control inspector_input_show', 'required' => true, 'readonly' => true  ]) !!}
                                {!! $errors->first('inspector_zipcode_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('inspectors_phone') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_phone', 'เบอร์โทรศัพท์'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_phone', !empty( $inspector->inspectors_phone )?$inspector->inspectors_phone:null,['class' => 'form-control', 'required' => true ]) !!}
                                {!! $errors->first('inspectors_phone', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('inspectors_mobile') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_mobile', ' เบอร์โทรศัพท์มือถือ'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_mobile', !empty( $inspector->inspectors_mobile )?$inspector->inspectors_mobile:null,  ['class' => 'form-control', ]) !!}
                                {!! $errors->first('inspectors_mobile', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('inspectors_fax') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_fax', 'เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_fax', !empty( $inspector->inspectors_fax )?$inspector->inspectors_fax:null,['class' => 'form-control' ]) !!}
                                {!! $errors->first('inspectors_fax', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('inspectors_email') ? 'has-error' : ''}}">
                            {!! Form::label('inspectors_email', ' e-Mail'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('inspectors_email', !empty( $inspector->inspectors_email )?$inspector->inspectors_email:null,  ['class' => 'form-control', ]) !!}
                                {!! $errors->first('inspectors_email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {!! Form::hidden('inspectors_subdistrict', !empty( $inspector->inspectors_subdistrict )?$inspector->inspectors_subdistrict:null, [ 'class' => 'inspector_input_show', 'id' => 'inspectors_subdistrict' ] ) !!}
                    {!! Form::hidden('inspectors_district', !empty( $inspector->inspectors_district )?$inspector->inspectors_district:null, [ 'class' => 'inspector_input_show', 'id' => 'inspectors_district' ] ) !!}
                    {!! Form::hidden('inspectors_province', !empty( $inspector->inspectors_province )?$inspector->inspectors_province:null, [ 'class' => 'inspector_input_show', 'id' => 'inspectors_province' ] ) !!}
                    {!! Form::hidden('inspectors_zipcode', !empty( $inspector->inspectors_zipcode )?$inspector->inspectors_zipcode:null, [ 'class' => 'inspector_input_show', 'id' => 'inspectors_zipcode' ] ) !!}
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" type="submit" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{!! Form::close() !!}

@push('js')

    <script>
        jQuery(document).ready(function() {
            
            $("#inspector_address_seach").select2({
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

            $("#inspector_address_seach").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#inspector_subdistrict_txt').val(jsondata.sub_title);
                        $('#inspector_district_txt').val(jsondata.dis_title);
                        $('#inspector_province_txt').val(jsondata.pro_title);
                        $('#inspector_zipcode_txt').val(jsondata.zip_code);

                        $('#inspectors_subdistrict').val(jsondata.sub_ids);
                        $('#inspectors_district').val(jsondata.dis_id);
                        $('#inspectors_province').val(jsondata.pro_id);
                        $('#inspectors_zipcode').val(jsondata.zip_code);

                    }
                });
            });
        });
    </script>

@endpush
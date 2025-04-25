<!-- /.modal-dialog -->
{!! Form::model($labs, [
    'method' => 'PATCH',
    'url' => ['/section5/labs/infomation-save', $labs->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}


<div id="MdAddress" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px;max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ห้องปฏิบัติการ</h4>
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group required{{ $errors->has('lab_name') ? 'has-error' : ''}}">
                            {!! Form::label('lab_name', 'ชื่อห้องปฏิบัติการ'.' :', ['class' => 'col-md-2 control-label']) !!}
                            <div class="col-md-10">
                                {!! Form::text('lab_name', !empty( $labs->lab_name )?$labs->lab_name:null,['class' => 'form-control', 'required' => true ]) !!}
                                {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row{{ $errors->has('emails') ? 'has-error' : ''}}">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                {!! Form::checkbox('cancel_state', '1', (!empty($labs->lab_end_date) ?true: false), ['class' => 'form-control  check', 'data-checkbox' => 'icheckbox_minimal-blue' ,'id'=>'cancel_state' ]) !!}
                                <label for="cancel_state" class="control-label"> ยกเลิกการเป็นหน่วยตรวจสอบ</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="box_cancel">
                    <div class="col-md-6">
                        <div class="form-group required">
                            {!! Form::label('lab_end_date', 'วันที่มีผล:', ['class' => 'control-label text-right col-md-4']) !!}
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::text('lab_end_date', !empty( $labs->lab_end_date )?HP::revertDate($labs->lab_end_date,true):null ,  ['class' => 'form-control mydatepicker']) !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('lab_address') ? 'has-error' : ''}}">
                            {!! Form::label('lab_address', 'เลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_address', !empty( $labs->lab_address )?$labs->lab_address:null,['class' => 'form-control', 'required' => true ]) !!}
                                {!! $errors->first('lab_address', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('lab_building') ? 'has-error' : ''}}">
                            {!! Form::label('lab_building', 'อาคาร/หมู่บ้าน'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_building', !empty( $labs->lab_building )?$labs->lab_building:null,  ['class' => 'form-control', ]) !!}
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
                                {!! Form::text('lab_soi', !empty( $labs->lab_soi )?$labs->lab_soi:null,  ['class' => 'form-control' ]) !!}
                                {!! $errors->first('lab_soi', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('lab_moo') ? 'has-error' : ''}}">
                            {!! Form::label('lab_moo', 'หมู่'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_moo', !empty( $labs->lab_moo )?$labs->lab_moo:null,['class' => 'form-control']) !!}
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
                                {!! Form::text('lab_subdistrict_txt', !empty( $labs->LabSubdistrictName )?$labs->LabSubdistrictName:null,  ['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
                                {!! $errors->first('lab_subdistrict_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('lab_district_txt') ? 'has-error' : ''}}">
                            {!! Form::label('lab_district_txt', 'เขต/อำเภอ'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_district_txt', !empty( $labs->LabDistrictName )?$labs->LabDistrictName:null,['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
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
                                {!! Form::text('lab_province_txt', !empty( $labs->LabProvinceName )?$labs->LabProvinceName:null,  ['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true ]) !!}
                                {!! $errors->first('lab_province_txt', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('lab_zipcode_txt') ? 'has-error' : ''}}">
                            {!! Form::label('lab_zipcode_txt', 'รหัสไปรษณีย์'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_zipcode_txt', !empty( $labs->lab_zipcode )?$labs->lab_zipcode:null,['class' => 'form-control lab_input_show', 'required' => true, 'readonly' => true  ]) !!}
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
                                {!! Form::text('lab_phone', !empty( $labs->lab_phone )?$labs->lab_phone:null,['class' => 'form-control', 'required' => true ]) !!}
                                {!! $errors->first('lab_phone', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('lab_fax') ? 'has-error' : ''}}">
                            {!! Form::label('lab_fax', ' เบอร์โทรสาร'.' :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('lab_fax', !empty( $labs->lab_fax )?$labs->lab_fax:null,  ['class' => 'form-control', ]) !!}
                                {!! $errors->first('lab_fax', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {!! Form::hidden('lab_subdistrict_id', !empty( $labs->lab_subdistrict_id )?$labs->lab_subdistrict_id:null, [ 'class' => 'lab_input_show', 'id' => 'lab_subdistrict_id' ] ) !!}
                    {!! Form::hidden('lab_district_id', !empty( $labs->lab_district_id )?$labs->lab_district_id:null, [ 'class' => 'lab_input_show', 'id' => 'lab_district_id' ] ) !!}
                    {!! Form::hidden('lab_province_id', !empty( $labs->lab_province_id )?$labs->lab_province_id:null, [ 'class' => 'lab_input_show', 'id' => 'lab_province_id' ] ) !!}
                    {!! Form::hidden('lab_zipcode', !empty( $labs->lab_zipcode )?$labs->lab_zipcode:null, [ 'class' => 'lab_input_show', 'id' => 'lab_zipcode' ] ) !!}
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
            
            $("#lab_address_seach").select2({
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

            $("#lab_address_seach").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#lab_subdistrict_txt').val(jsondata.sub_title);
                        $('#lab_district_txt').val(jsondata.dis_title);
                        $('#lab_province_txt').val(jsondata.pro_title);
                        $('#lab_zipcode_txt').val(jsondata.zip_code);

                        $('#lab_subdistrict_id').val(jsondata.sub_ids);
                        $('#lab_district_id').val(jsondata.dis_id);
                        $('#lab_province_id').val(jsondata.pro_id);
                        $('#lab_zipcode').val(jsondata.zip_code);

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

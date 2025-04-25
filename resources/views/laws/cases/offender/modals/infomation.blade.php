{!! Form::model($offender, [
    'method' => 'PATCH',
    'url' => ['/law/cases/offender/infomation-save', $offender->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}

<div class="modal fade" id="OffenderInfomationModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="OffenderInfomationModalLabel1" aria-hidden="true">
    <div  class="modal-dialog  modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><i class="bx bx-x"></i></button>
                <h4 class="modal-title" id="OffenderInfomationModalLabel1">ข้อมูลผู้กระทำความผิด</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group required">
                            {!! Html::decode(Form::label('name', 'ชื่อผู้ประกอบการ'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-10">
                                {!! Form::text('name', (!empty($offender->name)?$offender->name: null), ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('taxid', 'เลขประตัวผู้เสียภาษี'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('taxid', (!empty($offender->taxid)?$offender->taxid: null), ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            {!! Html::decode(Form::label('date_offender', 'วันที่พบการกระทำผิด'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('date_offender', (!empty($offender->date_offender)?HP::revertDate($offender->date_offender, true): null), ['class' => 'form-control mydatepicker']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="divider divider-left divider-secondary">
                            <div class="divider-text">ติดต่อประสานงาน</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('contact_name', 'ชื่อผู้ประสานงาน'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_name', (!empty($offender->contact_name)?$offender->contact_name: null), ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            {!! Html::decode(Form::label('contact_position', 'ตำแหน่ง'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_position', (!empty($offender->contact_position)?$offender->contact_position: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('contact_email', 'อีเมล'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_email', (!empty($offender->contact_email)?$offender->contact_email: null), ['class' => 'form-control']) !!}
                            </div>
                            {!! Html::decode(Form::label('contact_mobile', 'เบอร์มือถือ'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_mobile', (!empty($offender->contact_mobile)?$offender->contact_mobile: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('contact_phone', 'เบอร์โทรศัพท์'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_phone', (!empty($offender->contact_phone)?$offender->contact_phone: null), ['class' => 'form-control']) !!}
                            </div>
                            {!! Html::decode(Form::label('contact_fax', 'เบอร์แฟกซ์'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('contact_fax', (!empty($offender->contact_fax)?$offender->contact_fax: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="divider divider-left divider-secondary">
                            <div class="divider-text">ที่ตั้งสำนักงานใหญ่</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('address_no', 'ที่อยู่'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-10">
                                {!! Form::text('address_no', (!empty($offender->address_no)?$offender->address_no: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('moo', 'หมู่'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('moo', (!empty($offender->moo)?$offender->moo: null), ['class' => 'form-control']) !!}
                            </div>
                            {!! Html::decode(Form::label('soi', 'ซอย'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('soi', (!empty($offender->soi)?$offender->soi: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('building', 'อาคาร/หมู่บ้าน'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('building', (!empty($offender->building)?$offender->building: null), ['class' => 'form-control']) !!}
                            </div>
                            {!! Html::decode(Form::label('street', 'ถนน'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('street', (!empty($offender->street)?$offender->street: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('offend_address_search', 'ค้นหาที่อยู่'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('offend_address_search', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('offend_subdistrict_txt', 'แขวง/ตำบล'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('offend_subdistrict_txt', !empty($offender->bs_subdistrict)?$offender->bs_subdistrict->DISTRICT_NAME: null , ['class' => 'form-control', 'disabled' => true]) !!}
                                {!! Form::hidden('subdistrict_id', (!empty($offender->subdistrict_id)?$offender->subdistrict_id: null), ['class' => 'form-control', 'id' => 'subdistrict_id' ]) !!}
                            </div>
                            {!! Html::decode(Form::label('offend_district_txt', 'เขต/อำเภอ'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('offend_district_txt', !empty($offender->bs_district)?$offender->bs_district->AMPHUR_NAME: null , ['class' => 'form-control', 'disabled' => true]) !!}
                                {!! Form::hidden('district_id', (!empty($offender->district_id)?$offender->district_id: null), ['class' => 'form-control', 'id' => 'district_id']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('offend_province_txt', 'จังหวัด'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('offend_province_txt', !empty($offender->bs_subdistrict)?$offender->bs_subdistrict->PROVINCE_NAME: null , ['class' => 'form-control', 'disabled' => true]) !!}
                                {!! Form::hidden('province_id', (!empty($offender->province_id)?$offender->province_id: null), ['class' => 'form-control', 'id' => 'province_id']) !!}
                            </div>
                            {!! Html::decode(Form::label('zipcode', 'รหัสไปรษณีย์'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('zipcode', (!empty($offender->zipcode)?$offender->zipcode: null), ['class' => 'form-control', 'readonly' => true ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('email', 'อีเมล'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('email', (!empty($offender->email)?$offender->email: null), ['class' => 'form-control']) !!}
                            </div>
                            {!! Html::decode(Form::label('tel', 'เบอร์โทรศัพท์'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('tel', (!empty($offender->tel)?$offender->tel: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Html::decode(Form::label('fax', 'เบอร์แฟกซ์'.':', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-4">
                                {!! Form::text('fax', (!empty($offender->fax)?$offender->fax: null), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@push('js')

    <script>
    
        $(document).ready(function () {

            $("#offend_address_search").select2({
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

            $("#offend_address_search").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#offend_subdistrict_txt').val(jsondata.sub_title);
                        $('#offend_district_txt').val(jsondata.dis_title);
                        $('#offend_province_txt').val(jsondata.pro_title);
                     
                        $('#subdistrict_id').val(jsondata.sub_ids);
                        $('#district_id').val(jsondata.dis_id);
                        $('#province_id').val(jsondata.pro_id);
                        $('#zipcode').val(jsondata.zip_code);

                    }
                });
            });

        });

    </script>

@endpush

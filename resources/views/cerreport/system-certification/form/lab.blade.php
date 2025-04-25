@php
    $certify = $certificate;
@endphp


<div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('app_no', 'เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('app_no', !empty($certify->app_no)? $certify->app_no:null, ['class' => 'form-control','id'=>'app_no','readonly' => true]) !!}
        {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('certificate_no') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('certificate_no', '<span class="text-danger">*</span> ใบรับรองเลขที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('certificate_no', !empty( $certify->certificate_no)? $certify->certificate_no:null, ['class' => 'form-control','id'=>'certificate_no','required' => true]) !!}
        {!! $errors->first('certificate_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('org_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('org_name', '<span class="text-danger">*</span> หน่วยงาน'.':'.'<br/><span class="font_size">(Name laboratory)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-8">
        {!! Form::text('org_name',  !empty( $certify->org_name)? $certify->org_name:null, ['class' => 'form-control','id'=>'org_name','required' => true]) !!}
        {!! $errors->first('org_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('lab_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('lab_name', '<span class="text-danger">*</span> ห้องปฏิบัติการ'.':'.'<br/><span class="font_size">(Laboratory Name)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('lab_name',  !empty( $certify->lab_name)? $certify->lab_name:null, ['class' => 'form-control','id'=>'lab_name','required' => true]) !!}
            <span class="input-group-addon bg-secondary ">TH</span>
        </div>
        {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('lab_name_en') ? 'has-error' : ''}}">
    <div class="col-md-offset-3 col-md-8">
        <div class="input-group">
            {!! Form::text('lab_name_en',  !empty( $certify->lab_name_en)? $certify->lab_name_en:null, ['class' => 'form-control','id'=>'lab_name_en','required' => true]) !!}
            <span class="input-group-addon bg-secondary">EN</span>
        </div>
        {!! $errors->first('lab_name_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('radio_address') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('radio_address', 'ที่อยู่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <label  class="col-md-2" >
        {!! Form::radio('radio_address', '1', !empty( $certify->radio_address) && in_array( $certify->radio_address, [1] )?true:false,  ['class'=>'check radio_address', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;บริษัท&nbsp;
    </label>
    <label  class="col-md-2">
        {!! Form::radio('radio_address', '2', !empty( $certify->radio_address) && in_array( $certify->radio_address, [2] )?true:false, ['class'=>'check radio_address check-readonly', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;สาขา&nbsp;
    </label>
    <label  class="col-md-2">
        {!! Form::radio('radio_address', '3', !empty( $certify->radio_address) && in_array( $certify->radio_address, [3] )?true:false, ['class'=>'check radio_address check-readonly', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;กำหนดเอง&nbsp;
    </label>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('address_no', '<span class="text-danger">*</span> ตั้งอยู่เลขที่'.':'.'<br/><span class="font_size">(Address)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="input-group">
                {!! Form::text('address_no', !empty( $certify->address_no)? $certify->address_no:null, ['class' => 'form-control','id'=>'address_no','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('address_no', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group  mt-0">
                {!! Form::text('address_no_en', !empty( $certify->address_no_en)? $certify->address_no_en:null, ['class' => 'form-control','id'=>'address_no_en','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('address_no_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('address_moo', 'หมู่ที่'.':'.'<br/><span class="font_size">(Moo)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('address_moo', !empty( $certify->address_moo)? $certify->address_moo:null, ['class' => 'form-control','id'=>'address_moo','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('address_moo', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-0">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('address_moo_en', !empty( $certify->address_moo_en)? $certify->address_moo_en:null, ['class' => 'form-control','id'=>'address_moo_en','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('address_moo_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('address_soi', ' ตรอก/ซอย'.':'.'<br/><span class="font_size">(Trok/Sol)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="input-group">
                {!! Form::text('address_soi', !empty( $certify->address_soi)? $certify->address_soi:null, ['class' => 'form-control','id'=>'address_soi','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('address_soi', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('address_soi_en', !empty( $certify->address_soi_en)? $certify->address_soi_en:null, ['class' => 'form-control','id'=>'address_soi_en','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('address_soi_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('address_road', 'ถนน'.':'.'<br/><span class="font_size">(Street/Address_road)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('address_road', !empty( $certify->address_road)? $certify->address_road:null, ['class' => 'form-control','id'=>'address_road','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('address_road', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('address_road_en', !empty( $certify->address_road_en)? $certify->address_road_en:null, ['class' => 'form-control','id'=>'address_road_en','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('address_road_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('address_province', '<span class="text-danger">*</span> จังหวัด'.':'.'<br/><span class="font_size">(Province)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('address_province', !empty( $certify->address_province)? $certify->address_province:null, ['class' => 'form-control','id'=>'address_province','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('address_province', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('address_province_en', !empty( $certify->address_province_en)? $certify->address_province_en:null, ['class' => 'form-control','id'=>'address_province_en','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('address_province_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
       <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('address_district', '<span class="text-danger">*</span> เขต/อำเภอ'.':'.'<br/><span class="font_size">(Arnphoe/Khet)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('address_district', !empty( $certify->address_district)? $certify->address_district:null, ['class' => 'form-control','id'=>'address_district','required' => true]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('address_district', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('address_district_en', !empty( $certify->address_district_en)? $certify->address_district_en:null, ['class' => 'form-control','id'=>'address_district_en','required' => true]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('address_district_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('address_subdistrict', '<span class="text-danger">*</span> แขวง/ตำบล'.':'.'<br/><span class="font_size">(Tambon/Khwaeng)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('address_subdistrict', !empty( $certify->address_subdistrict)? $certify->address_subdistrict:null, ['class' => 'form-control','id'=>'address_subdistrict','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('address_subdistrict', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('address_subdistrict_en', !empty( $certify->address_subdistrict_en)? $certify->address_subdistrict_en:null, ['class' => 'form-control','id'=>'address_subdistrict_en','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('address_subdistrict_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('address_postcode', '<span class="text-danger">*</span> รหัสไปรษณีย'.':'.'<br/><span class="font_size">(Zip code)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('address_postcode', !empty( $certify->address_postcode)? $certify->address_postcode:null, ['class' => 'form-control','id'=>'address_postcode','required' => true]) !!}
            </div>
        </div>
    </div>

</div>

<div class="form-group {{ $errors->has('formula') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('formula', '<span class="text-danger">*</span> มาตรฐาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('formula', !empty( $certify->formula)? $certify->formula:null, ['class' => 'form-control','id'=>'formula','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> TH </span>
        </div>
        {!! $errors->first('formula', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('formula_en') ? 'has-error' : ''}}">
    <div class="col-md-offset-3 col-md-8">
        <div class="input-group">
            {!! Form::text('formula_en', !empty( $certify->formula_en)? $certify->formula_en:null, ['class' => 'form-control','id'=>'formula_en','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> EN </span>
        </div>
        {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('accereditatio_no') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('accereditatio_no', '<span class="text-danger">*</span> หมายเลขการรับรองที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('accereditatio_no', !empty( $certify->accereditatio_no)? $certify->accereditatio_no:null, ['class' => 'form-control','id'=>'accereditatio_no','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> TH </span>
        </div>
        {!! $errors->first('accereditatio_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('accereditatio_no_en') ? 'has-error' : ''}}">
    <div class="col-md-offset-3 col-md-8">
        <div class="input-group">
            {!! Form::text('accereditatio_no_en', !empty( $certify->accereditatio_no_en)? $certify->accereditatio_no_en:null, ['class' => 'form-control','id'=>'accereditatio_no_en','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> EN </span>
        </div>
        {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('certificate_date_start') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('certificate_date_start', 'ออกให้ ณ วันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('certificate_date_start', !empty( $certify->certificate_date_start)? HP::revertDate($certify->certificate_date_start,true):null, ['class' => 'form-control','id'=>'certificate_date_start','disabled' => true ]) !!}
        {!! $errors->first('certificate_date_start', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('certificate_date_end') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('certificate_date_end', 'สิ้นสุดวันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('certificate_date_end', !empty( $certify->certificate_date_end)? HP::revertDate($certify->certificate_date_end,true):null, ['class' => 'form-control','id'=>'certificate_date_end','disabled' => true ]) !!}
        {!! $errors->first('certificate_date_end', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('certificate_date_first') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('certificate_date_first', 'ออกให้ครั้งแรก ณ วันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('certificate_date_first', !empty( $certify->certificate_date_first)? HP::revertDate($certify->certificate_date_first,true):null, ['class' => 'form-control mydatepicker','id'=>'certificate_date_first' ]) !!}
        {!! $errors->first('certificate_date_first', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    {!! HTML::decode(Form::label('', 'ข้อมูลติดต่อ'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#ContractModal">
            แก้ไขข้อมูลติดต่อ
        </button>
    </div>
</div>

@include('cerreport.system-certification.contract-form')

@push('js')
    <script>
        $(document).ready(function () {

            $("input[name=radio_address]").on("ifChanged", function(event) {
                radio_address();
            });

        });

        function radio_address(){
            let checked = checkNone($("input[name=radio_address]:checked").val())?$("input[name=radio_address]:checked").val():'';
            let id = $("#id").val();
            let certificate_type = $("#certificate_type").val();
                
            if( $.inArray( checked , [1,2]) ){    
                $.ajax({
                    url: "{!! url('cerreport/system-certification/get-address') !!}" + "?id=" + id + "&certificate_type=" + certificate_type + "&address=" + checked
                }).done(function( object ) {
                    if(object.data != '-'){
                        let data =  object.data;
                        $('#address_no').val(data.address);
                        $('#address_no_en').val(data.address);

                        $('#address_moo').val(data.allay);
                        $('#address_moo_en').val(data.allay);

                        $('#address_soi').val(data.village_no);
                        $('#address_soi_en').val(data.village_no);

                        $('#address_road').val(data.road);
                        $('#address_road_en').val(data.road);

                        $('#address_province').val(data.province_name);
                        $('#address_province_en').val(data.province_name_en);

                        $('#address_district').val(data.district_name);
                        $('#address_district_en').val(data.district_name_en);

                        $('#address_subdistrict').val(data.subdistrict_name);
                        $('#address_subdistrict_en').val(data.subdistrict_name_en);

                        $('#address_postcode').val(data.postcode);
                    }
                }); 
            }else{
                $('#address_no').val('');
                $('#address_no_en').val('');

                $('#address_moo').val('');
                $('#address_moo_en').val('');

                $('#address_soi').val('');
                $('#address_soi_en').val('');

                $('#address_road').val('');
                $('#address_road_en').val('');

                $('#address_province').val('');
                $('#address_province_en').val('');

                $('#address_district').val('');
                $('#address_district_en').val('');

                $('#address_subdistrict').val('');
                $('#address_subdistrict_en').val('');

                $('#address_postcode').val('');
            }
        }
    </script>
@endpush
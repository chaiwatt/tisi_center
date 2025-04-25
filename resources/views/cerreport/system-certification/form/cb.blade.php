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

<div class="form-group {{ $errors->has('certificate') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('certificate', '<span class="text-danger">*</span> ใบรับรองเลขที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('certificate', !empty($certify->certificate)? $certify->certificate:null, ['class' => 'form-control','id'=>'certificate','required' => true]) !!}
        {!! $errors->first('certificate', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('name_standard') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_standard', '<span class="text-danger">*</span>  ชื่อหน่วยรับรอง'.':'.'<br/><span class=" font_size">(Name laboratory)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('name_standard', !empty($certify->name_standard)? $certify->name_standard:null, ['class' => 'form-control','id'=>'name_standard','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> TH </span>
        </div>
        {!! $errors->first('name_standard', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('name_standard_en') ? 'has-error' : ''}}">
    <div class="col-md-offset-3 col-md-8">
        <div class="input-group">
            {!! Form::text('name_standard_en', !empty($certify->name_standard_en)? $certify->name_standard_en:null, ['class' => 'form-control','id'=>'name_standard_en','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> EN </span>
        </div>
        {!! $errors->first('name_standard_en', '<p class="help-block">:message</p>') !!}
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
            {!! HTML::decode(Form::label('address', '<span class="text-danger">*</span> ตั้งอยู่เลขที่'.':'.'<br/><span class="font_size">(Address)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="input-group">
                {!! Form::text('address', !empty( $certify->address)? $certify->address:null, ['class' => 'form-control','id'=>'address','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group  mt-0">
                {!! Form::text('address_en', !empty( $certify->address_en)? $certify->address_en:null, ['class' => 'form-control','id'=>'address_en','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('address_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('allay', 'หมู่ที่'.':'.'<br/><span class="font_size">(Moo)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('allay', !empty( $certify->allay)? $certify->allay:null, ['class' => 'form-control','id'=>'allay','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('allay', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-0">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('allay_en', !empty( $certify->allay_en)? $certify->allay_en:null, ['class' => 'form-control','id'=>'allay_en','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('allay_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('village_no', ' ตรอก/ซอย'.':'.'<br/><span class="font_size">(Trok/Sol)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="input-group">
                {!! Form::text('village_no', !empty( $certify->village_no)? $certify->village_no:null, ['class' => 'form-control','id'=>'village_no','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('village_no', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('village_no_en', !empty( $certify->village_no_en)? $certify->village_no_en:null, ['class' => 'form-control','id'=>'village_no_en','required' => false]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('village_no_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('road', 'ถนน'.':'.'<br/><span class="font_size">(Street/Road)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('road', !empty( $certify->road)? $certify->road:null, ['class' => 'form-control','id'=>'road','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('road', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('road_en', !empty( $certify->road_en)? $certify->road_en:null, ['class' => 'form-control','id'=>'road_en','required' => false]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('road_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('province_name', '<span class="text-danger">*</span> จังหวัด'.':'.'<br/><span class="font_size">(Province)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('province_name', !empty( $certify->province_name)? $certify->province_name:null, ['class' => 'form-control','id'=>'province_name','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('province_name', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('province_name_en', !empty( $certify->province_name_en)? $certify->province_name_en:null, ['class' => 'form-control','id'=>'province_name_en','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('province_name_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
       <div class="row">
            <div class="form-group">
                {!! HTML::decode(Form::label('amphur_name', '<span class="text-danger">*</span> เขต/อำเภอ'.':'.'<br/><span class="font_size">(Arnphoe/Khet)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                <div class="col-md-6 input-group">
                    {!! Form::text('amphur_name', !empty( $certify->amphur_name)? $certify->amphur_name:null, ['class' => 'form-control','id'=>'amphur_name','required' => true]) !!}
                    <span class="input-group-addon bg-secondary "> TH </span>
                </div>
                {!! $errors->first('amphur_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6 input-group">
                    {!! Form::text('amphur_name_en', !empty( $certify->amphur_name_en)? $certify->amphur_name_en:null, ['class' => 'form-control','id'=>'amphur_name_en','required' => true]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('amphur_name_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('district_name', '<span class="text-danger">*</span> แขวง/ตำบล'.':'.'<br/><span class="font_size">(Tambon/Khwaeng)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('district_name', !empty( $certify->district_name)? $certify->district_name:null, ['class' => 'form-control','id'=>'district_name','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> TH </span>
            </div>
            {!! $errors->first('district_name', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 input-group">
                {!! Form::text('district_name_en', !empty( $certify->district_name_en)? $certify->district_name_en:null, ['class' => 'form-control','id'=>'district_name_en','required' => true]) !!}
                <span class="input-group-addon bg-secondary "> EN </span>
            </div>
            {!! $errors->first('district_name_en', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! HTML::decode(Form::label('postcode', '<span class="text-danger">*</span> รหัสไปรษณีย'.':'.'<br/><span class="font_size">(Zip code)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
            <div class="col-md-6 input-group">
                {!! Form::text('postcode', !empty( $certify->postcode)? $certify->postcode:null, ['class' => 'form-control','id'=>'postcode','required' => true]) !!}
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
    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-8">
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
    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-8">
        <div class="input-group">
            {!! Form::text('accereditatio_no_en', !empty( $certify->accereditatio_no_en)? $certify->accereditatio_no_en:null, ['class' => 'form-control','id'=>'accereditatio_no_en','required' => true]) !!}
            <span class="input-group-addon bg-secondary "> EN </span>
        </div>
        {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('date_start') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('date_start', 'ออกให้ ณ วันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('date_start', !empty( $certify->date_start)? HP::revertDate($certify->date_start,true):null, ['class' => 'form-control','id'=>'date_start','disabled' => true ]) !!}
        {!! $errors->first('date_start', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('check_badge') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('check_badge', 'ต้องการแสดงภาพสัญลักษณ์'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <label  class="col-md-2" >
        {!! Form::radio('check_badge', '1', !empty( $certify->check_badge) && in_array( $certify->check_badge,[1] )? true:( empty( $certify->check_badge)?true:false ), ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!}
            แสดง
     </label>
     <label  class="col-md-2">
        {!! Form::radio('check_badge', '2', !empty( $certify->check_badge) && in_array( $certify->check_badge,[2] )? true:null, ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!}
            ไม่แสดง
    </label>
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

{{-- <div class="form-group {{ $errors->has('date_end') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('date_end', 'สิ้นสุดวันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('date_end', !empty( $certify->date_end)? HP::revertDate($certify->date_end,true):null, ['class' => 'form-control','id'=>'date_end','readonly' => true ]) !!}
        {!! $errors->first('date_end', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

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
                        $('#address').val(data.address);
                        $('#address_en').val(data.address);

                        $('#allay').val(data.allay);
                        $('#allay_en').val(data.allay);

                        $('#village_no').val(data.village_no);
                        $('#village_no_en').val(data.village_no);

                        $('#road').val(data.road);
                        $('#road_en').val(data.road);

                        $('#province_name').val(data.province_name);
                        $('#province_name_en').val(data.province_name_en);

                        $('#amphur_name').val(data.district_name);
                        $('#amphur_name_en').val(data.district_name_en);

                        $('#district_name').val(data.subdistrict_name);
                        $('#district_name_en').val(data.subdistrict_name_en);

                        $('#postcode').val(data.postcode);
                    }
                }); 
            }else{
                $('#address').val('');
                $('#address_en').val('');

                $('#allay').val('');
                $('#allay_en').val('');

                $('#village_no').val('');
                $('#village_no_en').val('');

                $('#road').val('');
                $('#road_en').val('');


                $('#province_name').val('');
                $('#province_name_en').val('');

                $('#amphur_name').val('');
                $('#amphur_name_en').val('');

                $('#district_name').val('');
                $('#district_name_en').val('');

                $('#postcode').val('');
            }
        }
    </script>
@endpush


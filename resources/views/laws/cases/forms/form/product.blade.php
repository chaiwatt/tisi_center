@php
    
    $impound_type_1 = false;
    $impound_type_2 = false;

    if( !isset($lawcasesform->id) ||  (!empty($lawcasesform->offend_impound_type) && $lawcasesform->offend_impound_type=='1') ||  (!empty($lawcasesform->law_cases_impound_to) && $lawcasesform->law_cases_impound_to->impound_status=='1')  ){
        $impound_type_1 = true;
    }

    if( (!empty($lawcasesform->offend_impound_type) && $lawcasesform->offend_impound_type=='2') ||  (!empty($lawcasesform->law_cases_impound_to) && $lawcasesform->law_cases_impound_to->impound_status=='2')  ){
        $impound_type_2 = true;
    }
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_impound_type') ? 'has-error' : ''}}">
            {!! Form::label('offend_impound_type', 'มีผลิตภัณฑ์ยึด-อายัดหรือไม่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label>{!! Form::radio('offend_impound_type', '1', $impound_type_1 , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'impound_status_1']) !!} มี&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label>{!! Form::radio('offend_impound_type', '2', $impound_type_2, ['class'=>'check', 'data-radio'=>'iradio_square-red', 'id'=>'impound_status_2']) !!} ไม่มี</label>
                {!! $errors->first('offend_impound_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="hide-impound">

    {{-- <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('ref_id') ? 'has-error' : ''}}">
                {!! Form::label('ref_id', 'เลขที่อ้างอิงยึด-อายัด (ถ้ามี)', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::select('ref_id', [], null, ['class' => 'form-control ', 'placeholder'=>'- เลือกเลขที่อ้างอิง -', 'id' => 'ref_id']) !!}
                    {!! $errors->first('ref_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div> --}}
    
        
    <div class="row">
        <div class="col-md-10">
            <div class="form-group required{{ $errors->has('location') ? 'has-error' : ''}}">
                <label class="control-label col-md-3">สถานที่ตรวจยึด</label>
                <div class="col-md-7">
                    {!! Form::text('location', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->location:null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'location']) !!}
                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('date_impound') ? 'has-error' : ''}}">
                {!! Form::label('date_impound', 'วันที่ยึด-อายัดของกลางไว้', ['class' => 'col-md-5 control-label text-right']) !!}
                <div class="col-md-7">
                    <div class="inputWithIcon">
                        {!! Form::text('date_impound', !empty($lawcasesform->law_cases_impound_to)?HP::revertDate($lawcasesform->law_cases_impound_to->date_impound, true):null, ['class' => 'form-control mydatepicker  text-center', 'required'=>'required', 'id' => 'date_impound', 'placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-10">
            <div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
                <label class="control-label col-md-3"></label>
                <div class="col-md-7">
                    <input type="checkbox" class="check " id="same_product" value="1" name="same_product" data-checkbox="icheckbox_square-green"   
                     @if(!empty($lawcasesform->same_product) &&  $lawcasesform->same_product == '1') checked @endif
                     >
                    <label for="same_product" id="label_same_product">แสดงผลิตภัณฑ์เดียวกันส่วนที่ 2</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered inner-repeater">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2" width="4%">#</th>
                        <th class="text-center" rowspan="2" width="35%">รายการ</th>
                        <th class="text-center" colspan="2">จำนวน</th>
                        <th class="text-center" rowspan="2">จำนวน<br>ของทั้งหมดที่พบ</th>
                        <th class="text-center" rowspan="2">หน่วย</th>
                        <th class="text-center" rowspan="2">ราคา/หน่วย</th>
                        <th class="text-center" rowspan="2" width="10%">รวมราคา</th>
                        <th class="text-center" rowspan="2" width="5%">จัดการ</th>
                    </tr>
                    <tr>
                        <th class="text-center" width="8%">ยึด</th>
                        <th class="text-center" width="8%">อายัด</th>
                    </tr>
                </thead>
                <tbody id="tbd_product" data-repeater-list="inner-list">
                    @if(!empty($lawcasesimpoundproduct))
                        @foreach ( $lawcasesimpoundproduct as $impound_data )
                            <tr class="product-list" data-repeater-item>
                                <td class="text-top text-center">
                                    <span class="td_no">1</span>
                                    {!! Form::hidden('law_case_impound_id', null) !!}
                                    {!! Form::hidden('impound_product_id', !empty($impound_data)?$impound_data->id:null) !!}
                                </td>
                                <td >{!! Form::textarea('detail', !empty($impound_data)?$impound_data->detail:null, ['class' => 'form-control ', 'rows'=>3]) !!}</td>
                                <td >{!! Form::text('amount_impounds', !empty($impound_data)?$impound_data->amount_impounds:'0', ['class' => 'form-control  cal_impounds text-center check_format_en_and_number']) !!}</td>
                                <td >{!! Form::text('amount_keep', !empty($impound_data)?$impound_data->amount_keep:'0', ['class' => 'form-control  cal_keep text-center check_format_en_and_number']) !!}</td>
                                <td >{!! Form::text('total',!empty($impound_data)?$impound_data->total:'0', ['class' => 'form-control cal_total text-center check_format_en_and_number']) !!}</td>
                                <td >{!! Form::text('unit', !empty($impound_data)?$impound_data->unit:null, ['class' => 'form-control  text-center ']) !!}</td>
                                <td >{!! Form::text('price', !empty($impound_data)?$impound_data->price:0, ['class' => 'form-control input_amount  cal_price text-center']) !!}</td>
                                <td class="text-right">
                                    <span class="total_price">{!!  !empty($impound_data->total) && !empty($impound_data->price) ? number_format(($impound_data->total * $impound_data->price),2) : '0' !!}</span>
                                    {!! Form::hidden('total_price', !empty($impound_data->total) && !empty($impound_data->price)?  number_format(($impound_data->total * $impound_data->price),2) :'0', ['class'=>'cal_total_price']) !!}</td>
                                    {{-- <span class="total_price">{!!  !empty($impound_data)? number_format($impound_data->total_price,2):'0' !!}</span>
                                    {!! Form::hidden('total_price', !empty($impound_data)?number_format($impound_data->total_price,2):'0', ['class'=>'cal_total_price']) !!}</td> --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-outline product_add" id="add_product">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <div class="button_product_remove"></div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="product-list" data-repeater-item>
                            <td class="text-top text-center">
                                <span class="td_no">1</span>
                                {!! Form::hidden('law_case_impound_id', null) !!}
                                {!! Form::hidden('impound_product_id', null) !!}
                            </td>
                            <td >{!! Form::textarea('detail', null, ['class' => 'form-control ', 'rows'=>3]) !!}</td>
                            <td >{!! Form::text('amount_impounds',null, ['class' => 'form-control  cal_impounds text-center check_format_en_and_number']) !!}</td>
                            <td >{!! Form::text('amount_keep', null, ['class' => 'form-control  cal_keep text-center check_format_en_and_number']) !!}</td>
                            <td >{!! Form::text('total', null, ['class' => 'form-control  text-center cal_total check_format_en_and_number']) !!}</td>
                            <td >{!! Form::text('unit', null, ['class' => 'form-control  text-center ']) !!}</td>
                            <td >{!! Form::text('price', null, ['class' => 'form-control  cal_price input_amount text-center']) !!}</td>
                            <td class="text-right"><span class="total_price">0</span>{!! Form::hidden('total_price', '0' , ['class'=>'cal_total_price']) !!}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm btn-outline product_add" id="add_product">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="button_product_remove"></div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">รวมของกลาง</td>
                        <td colspan="2" ></td>
                        <td  class="text-right"><span id="impounds_keep_all">0</span></td>
                        <td></td>
                        <td class="text-right"><span id="price_all"></span></td>
                        <td class="text-right"><span id="total_price_all"></span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('law_basic_resource_id') ? 'has-error' : ''}}">
                {!! Form::label('law_basic_resource_id', 'แหล่งที่มาราคาผลิตภัณฑ์', ['class' => 'col-md-5 control-label text-right']) !!}
                <div class="col-md-7">
                    {!! Form::select('law_basic_resource_id', App\Models\Law\Basic\LawResource::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->law_basic_resource_id:null, ['class' => 'form-control ', 'placeholder'=>'- เลือกแหล่งที่มา -', 'required' => 'required', 'id' => 'law_basic_resource_id']) !!}
                    {!! $errors->first('law_basic_resource_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('total_value') ? 'has-error' : ''}}">
                {!! Form::label('total_value', 'รวมมูลค่าของกลาง/บาท', ['class' => 'col-md-5 control-label text-right']) !!}
                <div class="col-md-5">
                    {!! Form::text('total_value', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->total_value:null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'total_value', 'readonly'=>'readonly']) !!}
                    {!! $errors->first('total_value', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('location_address') ? 'has-error' : ''}}">
                {!! Form::label('location_address', 'สถานที่เก็บผลิตภัณฑ์', ['class' => 'col-md-5 control-label text-right']) !!}
                <div class="col-md-7">
                    <input type="checkbox" class="check item_checkbox" id="same_address_license" value="1" name="same_address_license" data-checkbox="icheckbox_square-green" @if(!empty($lawcasesform->same_address_license) && in_array(1, $lawcasesform->same_address_license)) checked @endif>
                    <label for="same_address_license" id="label_same_address_license"> ข้อมูลเดียวกับใบอนุญาต</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('storage_name') ? 'has-error' : ''}}">
                {!! Form::label('storage_name', 'ชื่อสถานที่', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-10">
                    {!! Form::text('storage_name', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_name:null, ['class' => 'form-control', 'required'=>'required', 'id'=>'storage_name' ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_address_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('storage_address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-3 control-label text-right'])) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_address_no', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_address_no:null, ['class' => 'form-control', 'required'=>'required', 'id'=>'storage_address_no']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('storage_moo', 'หมู่', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_moo', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_moo:null, ['class' => 'form-control', 'id'=>'storage_moo']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('storage_soi', 'ตรอก/ซอย', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_soi', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_soi:null, ['class' => 'form-control', 'id'=>'storage_soi']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('storage_street', 'ถนน', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_street', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_street:null, ['class' => 'form-control', 'id'=>'storage_street']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('storage_address_search', 'ค้นหา', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'storage_address_search' ]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_address_no') ? 'has-error' : ''}}">
                {!! Form::label('storage_subdistrict_txt', 'ตำบล/แขวง', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_subdistrict_txt', !empty($lawcasesform->law_cases_impound_to->storage_subdistricts)?trim($lawcasesform->law_cases_impound_to->storage_subdistricts->DISTRICT_NAME):null, ['class' => 'form-control', 'id'=>'storage_subdistrict_txt', 'readonly'=>'readonly']) !!}
                    {!! Form::hidden('storage_subdistrict_id', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_subdistrict_id:null, ['id'=>'storage_subdistrict_id']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_district_txt') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('storage_district_txt', 'อำเภอ/เขต', ['class' => 'col-md-3 control-label text-right'])) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_district_txt', !empty($lawcasesform->law_cases_impound_to->storage_districts)?trim($lawcasesform->law_cases_impound_to->storage_districts->AMPHUR_NAME):null, ['class' => 'form-control', 'id'=>'storage_district_txt', 'readonly'=>'readonly']) !!}
                    {!! Form::hidden('storage_district_id', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_district_id:null, ['id'=>'storage_district_id']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_province_txt') ? 'has-error' : ''}}">
                {!! Form::label('storage_province_txt', 'จังหวัด', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_province_txt', !empty($lawcasesform->law_cases_impound_to->storage_provinces)?trim($lawcasesform->law_cases_impound_to->storage_provinces->PROVINCE_NAME):null, ['class' => 'form-control', 'id'=>'storage_province_txt', 'readonly'=>'readonly']) !!}
                    {!! Form::hidden('storage_province_id', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_province_id:null, ['id'=>'storage_province_id']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_zipcode') ? 'has-error' : ''}}">
                {!! Form::label('storage_zipcode', 'รหัสไปรษณีย์', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_zipcode', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_zipcode:null, ['class' => 'form-control', 'id'=>'storage_zipcode', 'readonly'=>'readonly']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('storage_tel') ? 'has-error' : ''}}">
                {!! Form::label('storage_tel', 'เบอร์โทร', ['class' => 'col-md-3 control-label text-right']) !!}
                <div class="col-md-9">
                    {!! Form::text('storage_tel', !empty($lawcasesform->law_cases_impound_to)?$lawcasesform->law_cases_impound_to->storage_tel:null, ['class' => 'form-control', 'id'=>'storage_tel', 'required'=>'required']) !!}
                </div>
            </div>
        </div>
    </div>

</div>


@push('js')
    <script>
        $(document).ready(function() {
            $("#storage_address_search").select2({
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

            $("#storage_address_search").on('change', function () {
                $.ajax({
                    url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val()
                }).done(function( jsondata ) {
                    if(jsondata != ''){

                        $('#storage_subdistrict_txt').val(jsondata.sub_title);
                        $('#storage_district_txt').val(jsondata.dis_title);
                        $('#storage_province_txt').val(jsondata.pro_title);
                     
                        $('#storage_subdistrict_id').val(jsondata.sub_ids);
                        $('#storage_district_id').val(jsondata.dis_id);
                        $('#storage_province_id').val(jsondata.pro_id);
                        $('#storage_zipcode').val(jsondata.zip_code);

                    }
                });
            });

            $('#same_address_license').on('ifChecked', function (event) {

                var checked = $('#offend_license_type_1').is(':checked');
                if( checked == true){
                    let offend_name = checkNone($('#offend_factory_name').val())?$('#offend_factory_name').val():$('#offend_name').val();
                   $('#storage_name').val(offend_name); 
                }else{
                   $('#storage_name').val($('#offend_name').val()); 
                }
                $('#storage_address_no').val($('#offend_address').val()); 
                $('#storage_soi').val($('#offend_soi').val()); 
                $('#storage_moo').val($('#offend_moo').val()); 
                $('#storage_street').val($('#offend_street').val()); 
                $('#storage_subdistrict_txt').val($('#offend_subdistrict_txt').val()); 
                $('#storage_subdistrict_id').val($('#offend_subdistrict_id').val()); 
                $('#storage_district_txt').val($('#offend_district_txt').val()); 
                $('#storage_district_id').val($('#offend_district_id').val()); 
                $('#storage_province_txt').val($('#offend_province_txt').val()); 
                $('#storage_province_id').val($('#offend_province_id').val()); 
                $('#storage_zipcode').val($('#offend_zipcode').val()); 
                $('#storage_tel').val($('#offend_tel').val()); 

            });

            $('#same_address_license').on('ifUnchecked', function (event) {

                $('#storage_name').val(''); 
                $('#storage_address_no').val(''); 
                $('#storage_soi').val(''); 
                $('#storage_moo').val(''); 
                $('#storage_street').val(''); 
                $('#storage_subdistrict_txt').val(''); 
                $('#storage_subdistrict_id').val(''); 
                $('#storage_district_txt').val(''); 
                $('#storage_district_id').val(''); 
                $('#storage_province_txt').val(''); 
                $('#storage_province_id').val(''); 
                $('#storage_zipcode').val(''); 
                $('#storage_tel').val(''); 
                
            });
            $('.inner-repeater').repeater();

            $(document).on('click', '#add_product', function(e) {

                var law_case_impound_id = '<input name="law_case_impound_id" type="hidden" value="">';
                var impound_product_id  = '<input name="impound_product_id" type="hidden" value="">';
                var detail              = '<textarea class="form-control "  required rows="3" name="detail" cols="50"></textarea>';
                var amount_impounds     = '<input required  class="form-control cal_impounds text-center check_format_en_and_number" name="amount_impounds" type="text" value="">';
                var amount_keep         = '<input required class="form-control cal_keep text-center check_format_en_and_number" name="amount_keep" type="text" value="">';
                var total                = '<input required class="form-control text-center cal_total  check_format_en_and_number" name="total" type="text" value="">';
                var unit                = '<input required  class="form-control text-center " name="unit" type="text" value="">';
                var price               = '<input required class="form-control cal_price  input_amount text-center " name="price" type="text" value="">';
                var total_price         = '<input class="cal_total_price" name="total_price" type="hidden" value="">';
                var tr = '';
                    tr += '<tr data-repeater-item>';
                    tr += '<td class="text-top text-center"><span class="td_no"></span>'+law_case_impound_id+''+impound_product_id+'</td>';
                    tr += '<td>'+detail+'</td>';
                    tr += '<td>'+amount_impounds+'</td>';
                    tr += '<td>'+amount_keep+'</td>'; 
                    tr += '<td>'+total+'</td>';
                    tr += '<td>'+unit+'</td>';
                    tr += '<td>'+price+'</td>';
                    tr += '<td class="text-right"><span class="total_price">0</span>'+total_price+'</td>';
                    tr += '<td class="text-center"></td>';
                    tr += '<tr>';

                $('#tbd_product').append(tr).slideDown().find('input,textarea');
                IsInputNumber();
                OrderTdNo();
            });

            $('body').on('click', '.btn_product_remove', function(event) {
                if( confirm("ต้องการลบแถวนี้หรือไม่ ?") ){
                    $(this).closest( "tr" ).remove();
                    OrderTdNo();
                    calTotalAll();
                }
            });


            OrderTdNo();

            $(document).on('keyup', '.cal_impounds, .cal_keep', function(e) {

                let tr_row = $(this).parent().parent();

                let input_cal_impounds = tr_row.find('.cal_impounds');
                let input_cal_keep     = tr_row.find('.cal_keep');

                let cal_impounds =  $.isNumeric(input_cal_impounds.val()) ? input_cal_impounds.val() : 0  ; 
                let cal_keep     =  $.isNumeric(input_cal_keep.val()) ? input_cal_keep.val() : 0  ; 
 
                let total_price  = ( parseInt(cal_impounds)+parseInt(cal_keep) )   ;
                //ดักNaN
                $.isNumeric(total_price) ?   tr_row.find('.cal_total').val((total_price)) : tr_row.find('.cal_total').val('0') 
 
                  calPrice(tr_row);
                   calTotalAll();
            });

            $(document).on('keyup', '.cal_total', function(e) {

                   let tr_row = $(this).parent().parent();
                   calPrice(tr_row);
                    calTotalAll();
            });

            
            $(document).on('keyup', '.cal_price', function(e) {
                let tr_row = $(this).parent().parent();
                   calPrice(tr_row);
                   calTotalAll();
         
            });

 
            
            // calTotalPriceAll();
            calTotalAll();
            IsInputNumber();
            $('#impound_status_1').on('ifChecked', function (event) {
                LoadImpound();
            });

            $('#impound_status_2').on('ifChecked', function (event) {
                LoadImpound();
            });
            LoadImpound();
        });
        

        function LoadImpound(){
            var checked    = $('#impound_status_1').prop('checked');
            var impound    = $('.hide-impound');
            var tb_product = $('#tbd_product');
            if( checked == true){
                impound.show(400);
                impound.find('input[required], select[required], textarea[required]').prop('required', true);
                impound.find('input, select, textarea').prop('disabled', false);
                tb_product.find('input, select, textarea').prop('required', true);
            }else{
                impound.hide(400);
                impound.find('input[required], select[required], textarea[required]').prop('required', false);
                impound.find('input, select, textarea').prop('disabled', true);
            }
        }
        
        function OrderTdNo(){

            var btn = '<button class="btn btn-danger btn-outline btn-sm btn_product_remove" type="button"><i class="fa fa-times"></i></button>';
            var i   =   $('.inner-repeater').find('span.td_no').length;
            
            $('.inner-repeater').find('span.td_no').each(function(index, el) {
     
                $(el).text(index+1);

                var tr = $(el).closest( "tr" );
                    tr.find('.product_add').remove();
                if( i >= 2){
                    tr.find('td').last().html(btn);
                }else{
                    tr.find('.btn_product_remove').remove();
                }
       
            });

            // console.log(i);

            var add  = '<button type="button" class="m-l-5 btn btn-success btn-sm btn-outline product_add" id="add_product"><i class="fa fa-plus"></i></button>';

            let last = $('.inner-repeater').find('span.td_no').last();
            let row  = last.closest( "tr" ).addClass('product-list');
                row.find('td').last().append(add);

            $('.inner-repeater').repeater();
        }

        // function calTotalPriceAll(){
        //     $('tr.product-list').each(function(index, el){
        //         let row_total_price = $(el).find('input.cal_total_price').val();
        //         $(el).find('span.total_price').text(addCommas(row_total_price));
        //     });
        // }

        function calPrice(el){
            // $('tr.product-list').each(function(index, el){
                    let    cal_total   = (RemoveCommas($(el).find('.cal_total').val()))  ;
                    let    cal_price   =  $.isNumeric($(el).find('.cal_price').val()) ?  parseFloat(RemoveCommas($(el).find('.cal_price').val())):0   ;
           
                    let  cal_price_all = cal_total * cal_price ;
                    $(el).find('.total_price').html(addCommas(cal_price_all.toFixed(2), 2));
                    $(el).find('.cal_total_price').val(addCommas(cal_price_all.toFixed(2), 2));
            // });
            totals();
        }
        function calTotalAll(){
            let cal_impoundkeep_all = 0;
            let cal_price_all = 0;
            $('tr.product-list').each(function(index, el){

                let cal_impoundkeep = 0;
                    cal_impoundkeep +=  parseInt($.isNumeric(($(el).find('.cal_total').val())) ? $(el).find('.cal_total').val():0 )  ;
                    cal_impoundkeep_all += parseInt(cal_impoundkeep);

                let cal_price = 0;
                    cal_price +=  $.isNumeric($(el).find('.cal_price').val()) ? parseFloat(RemoveCommas($(el).find('.cal_price').val())):0   ;
                    cal_price_all += parseInt(cal_price);
       

            });
             

            $.isNumeric(cal_impoundkeep_all)?
            $('span#impounds_keep_all').text(addCommas(cal_impoundkeep_all)):
            $('span#impounds_keep_all').text('0');

            $.isNumeric(cal_price_all) && cal_price_all > 0 ?   $('span#price_all').text(addCommas(cal_price_all.toFixed(2), 2)) :   $('span#price_all').text('0');

             totals();
        }
        
        function totals() {
            var rows = $('#tbd_product').children(); //แถวทั้งหมด
                var  cal_total_price_all = 0;
                if(rows.length > 0){
                    rows.each(function(index, el) {
                        if(checkNone($(el).find('span.total_price').text())){
                            cal_total_price_all += parseFloat(RemoveCommas($(el).find('span.total_price').text()));
                        }
                    }); 
                }
                $.isNumeric(cal_total_price_all) ? $('#total_value').val(addCommas(addCommas(cal_total_price_all.toFixed(2), 2))) :  $('#total_value').val('0');
                $.isNumeric(cal_total_price_all) ? $('span#total_price_all').text(addCommas(addCommas(cal_total_price_all.toFixed(2), 2))) :  $('span#total_price_all').text('0');

        } 

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function IsInputNumber() {
             // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
             String.prototype.replaceAll = function(search, replacement) {
              var target = this;
              return target.replace(new RegExp(search, 'g'), replacement);
             }; 
              
             var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน 
              var s_inum=new String(inum); 
              var num2=s_inum.split("."); 
              var n_inum=""; 
              if(num2[0]!=undefined){
             var l_inum=num2[0].length; 
             for(i=0;i<l_inum;i++){ 
              if(parseInt(l_inum-i)%3==0){ 
             if(i==0){ 
              n_inum+=s_inum.charAt(i); 
             }else{ 
              n_inum+=","+s_inum.charAt(i); 
             } 
              }else{ 
             n_inum+=s_inum.charAt(i); 
              } 
             } 
              }else{
             n_inum=inum;
              }
              if(num2[1]!=undefined){ 
             n_inum+="."+num2[1]; 
              }
              return n_inum; 
             } 
             // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
             $(".input_amount").on("keypress",function(e){
              var eKey = e.which || e.keyCode;
              if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
             return false;
              }
             }); 
             
             // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
             $(".input_amount").on("change",function(){
              var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                      if(thisVal != ''){
                         if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                     thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                     thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                      }else{ // ถ้าไม่มีคอมม่า
                     thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                      } 
                      thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                      $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                      $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                      }else{
                          $(this).val('');
                      }
             });
   }

    </script>
@endpush
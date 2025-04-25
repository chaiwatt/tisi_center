

<div class="row box_department_type1">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_report_date') ? 'has-error' : ''}}">
            {!! Form::label('offend_report_date', 'วันที่แจ้งเรื่อง', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <div class="inputWithIcon">
                    {!! Form::text('offend_report_date', !empty($lawcasesform->offend_report_date)?HP::revertDate($lawcasesform->offend_report_date, true) :null, ['class' => 'form-control mydatepicker  text-center', 'required'=>'required','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_accept_date') ? 'has-error' : ''}}">
            {!! Form::label('offend_accept_date', 'วันที่ลงรับเรื่อง', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <div class="inputWithIcon">
                    {!! Form::text('offend_accept_date', !empty($lawcasesform->offend_accept_date)?HP::revertDate($lawcasesform->offend_report_date, true) :null, ['class' => 'form-control mydatepicker  text-center', 'required'=>'required', 'placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_date') ? 'has-error' : ''}}">
            {!! Form::label('offend_date', 'วันที่พบการกระทำความผิด', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <div class="inputWithIcon">
                    {!! Form::text('offend_date', !empty($lawcasesform->offend_date)?$lawcasesform->offend_date:null, ['class' => 'form-control mydatepicker  text-center', 'required'=>'required','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('law_basic_offend_type_id') ? 'has-error' : ''}}">
            {!! Form::label('law_basic_offend_type_id', 'สาเหตุที่พบ', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('law_basic_offend_type_id', App\Models\Law\Basic\LawOffendType::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกสาเหตุ -', 'id' => 'law_basic_offend_type_id', 'required'=>'required']) !!}
                {!! $errors->first('law_basic_offend_type_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_applicanttype_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('offend_applicanttype_id', 'ประเภท'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3   control-label'])) !!}
            <div class="col-md-7" >
                @foreach (HP::applicant_types() as $key => $applicant_type)
                            {!! Form::radio('offend_applicanttype_id', $key,($key== 1 ? true :false), ['id' => 'applicanttype_id'.$key,'class'=>'check applicanttype_id', 'data-radio'=>'iradio_square-green']); !!}
                            <label for="offend_applicanttype_id{{ $key }}">{{ $applicant_type }}</label>
                @endforeach
            </div>
        </div>

        <div class="form-group {{ $errors->has('offend_person_type') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label(' ',  ' ',  ['class' => 'col-md-3   control-label'])) !!}
            <div class="col-md-3" >
                {!! Form::select('offend_person_type',
                                ['1'=>'เลขประจำตัวผู้เสียภาษี','2'=>'เลขที่หนังสือเดินทาง','3'=>'เลขทะเบียนธุรกิจคนต่างด้าว'],
                                null,
                                ['class' => 'form-control', 'id'=>'person_type',
                                'placeholder' =>'- เลือกประเภทข้อมูล -',
                                'required'=> true])
                !!}
            </div>
            <div class="col-md-3">
                {!! Form::text('offend_taxid', null, ['class' => 'form-control offend_taxid','id'=>'offend_taxid','required'=> true , 'maxlength' => '13','placeholder'=>'เลขนิติบุคคล']) !!}
                {!! $errors->first('offend_taxid', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-3" >
                <button type="button" id="search" class="btn btn-primary"> ค้นหา </button>
            </div>
        </div>
    </div>
</div>

<div class="row box_offend">
    <div class="col-md-10">
        <div class="form-group required{{ $errors->has('offend_name') ? 'has-error' : ''}}">
            {!! Form::label('offend_name', 'ผู้ประกอบการ/ผู้กระทำความผิด', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('offend_name', null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'offend_name']) !!}
                {!! Form::hidden('offend_condition', null, ['id'=>'offend_condition']) !!}
                {!! $errors->first('offend_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


@php
     if(!empty($lawcasesform->offend_ref_no)){
           $offend_ref_no = [$lawcasesform->offend_ref_no => $lawcasesform->offend_ref_no];
     }else{
            $offend_ref_no= [];
     }
 
@endphp

<div class="row" id="div_offend_ref_no">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_ref_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('offend_ref_no', '<i class="fa fa-exclamation-circle text-warning"></i> '.'เลขที่เอกสาร (อ้างอิง)', ['class' => 'col-md-5 control-label text-right'])) !!}
            <div class="col-md-7">
                {!! Form::select('offend_ref_no',
                   $offend_ref_no, 
                   null,
                  ['class' => 'form-control ', 
                  'required' => false, 
                  'id' => 'offend_ref_no',  
                   'placeholder' => '- เลือก เลขที่เอกสาร (อ้างอิง) -'
                 ]) !!}
                  {!! Form::hidden('offend_ref_tb', null , ['id'=>'offend_ref_tb']) !!}
                {!! $errors->first('offend_ref_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_license_type') ? 'has-error' : ''}}">
            {!! Form::label('offend_license_type', 'ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label>{!! Form::radio('offend_license_type', '1', false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green', 'id'=>'offend_license_type_1']) !!}&nbsp; มี &nbsp;</label>
                <label>{!! Form::radio('offend_license_type', '2', true , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','id'=>'offend_license_type_2']) !!}&nbsp; ไม่มี &nbsp;</label>
                {!! $errors->first('offend_license_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row"  id="div_license_number">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_location_detail') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                <table class="table color-bordered-table info-bordered-table table-bordered table-sm">
                    <thead>
                    <tr>
                        <th class="text-center" width="2%">เลือก</th>
                        <th class="text-center" width="30%">เลขที่ใบอนุญาต</th>
                        <th class="text-center" width="30%">ไฟล์ใบอนุญาต</th>
                        <th class="text-center" width="30%">สถานะใบอนุญาต</th>
                    </tr>
                    </thead>
                    <tbody id="table_tbody_license_number">
                     
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<div id="box_offend">
<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_address') ? 'has-error' : ''}}">
            {!! Form::label('offend_address', 'ที่ตั้งสำนักงานใหญ่/ที่อยู่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::textarea('offend_address', null , ['class' => 'form-control ', 'required' => 'required', 'rows'=>1, 'id'=>'offend_address']) !!}
                {!! Form::hidden('offend_factory_name', null, ['id'=>'offend_factory_name', 'disabled'=>'disabled']) !!}
                {!! $errors->first('offend_address', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('offend_moo', 'หมู่', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_moo', !empty($lawcasesform)?$lawcasesform->offend_moo:null, ['class' => 'form-control', 'id'=>'offend_moo']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_building') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('offend_building', 'อาคาร/หมู่บ้าน', ['class' => 'col-md-5 control-label text-right'])) !!}
            <div class="col-md-7">
                {!! Form::text('offend_building', !empty($lawcasesform)?$lawcasesform->offend_building:null, ['class' => 'form-control', 'id'=>'offend_building']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('offend_soi', 'ตรอก/ซอย', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_soi', !empty($lawcasesform)?$lawcasesform->offend_soi:null, ['class' => 'form-control', 'id'=>'offend_soi']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('offend_street', 'ถนน', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_street', !empty($lawcasesform)?$lawcasesform->offend_street:null, ['class' => 'form-control', 'id'=>'offend_street']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('offend_address_search', 'ค้นหา', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'offend_address_search' ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_subdistrict_txt') ? 'has-error' : ''}}">
            {!! Form::label('offend_subdistrict_txt', 'ตำบล/แขวง', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_subdistrict_txt', !empty($lawcasesform->offend_subdistricts)?trim($lawcasesform->offend_subdistricts->DISTRICT_NAME):null, ['class' => 'form-control', 'required' => 'required', 'id'=>'offend_subdistrict_txt', 'readonly'=>'readonly']) !!}
                {!! Form::hidden('offend_subdistrict_id', !empty($lawcasesform)?$lawcasesform->offend_subdistrict_id:null, ['id'=>'offend_subdistrict_id']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_district_txt') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('offend_district_txt', 'อำเภอ/เขต', ['class' => 'col-md-5 control-label text-right'])) !!}
            <div class="col-md-7">
                {!! Form::text('offend_district_txt', !empty($lawcasesform->offend_districts)?trim($lawcasesform->offend_districts->AMPHUR_NAME):null, ['class' => 'form-control', 'required' => 'required', 'id'=>'offend_district_txt', 'readonly'=>'readonly']) !!}
                {!! Form::hidden('offend_district_id', !empty($lawcasesform)?$lawcasesform->offend_district_id:null, ['id'=>'offend_district_id']) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_province_txt') ? 'has-error' : ''}}">
            {!! Form::label('offend_province_txt', 'จังหวัด', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_province_txt', !empty($lawcasesform->offend_provinces)?trim($lawcasesform->offend_provinces->PROVINCE_NAME):null, ['class' => 'form-control', 'required' => 'required', 'id'=>'offend_province_txt', 'readonly'=>'readonly']) !!}
                {!! Form::hidden('offend_province_id', !empty($lawcasesform)?$lawcasesform->offend_province_id:null, ['id'=>'offend_province_id']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_zipcode') ? 'has-error' : ''}}">
            {!! Form::label('offend_zipcode', 'รหัสไปรษณีย์', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_zipcode', !empty($lawcasesform)?$lawcasesform->offend_zipcode:null, ['class' => 'form-control', 'required' => 'required', 'id'=>'offend_zipcode', 'readonly'=>'readonly']) !!}
            </div>
        </div>
    </div>
</div>
</div>

<div id="box_contact">

<div class="row">
    <div class="col-md-6">
        <div class="form-group  required{{ $errors->has('offend_email') ? 'has-error' : ''}}">
            {!! Form::label('offend_email', 'อีเมล', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_email', null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'offend_email']) !!}
                {!! $errors->first('offend_email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_tel') ? 'has-error' : ''}}">
            {!! Form::label('offend_tel', 'เบอร์โทร', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_tel', null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'offend_tel']) !!}
                {!! $errors->first('offend_tel', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row" id="box_url">
    @if(!empty($lawcasesform->offend_power))
        @foreach ((array)$lawcasesform->offend_power as $key => $item )
            <div class="box_url col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('offend_power') ? 'has-error' : ''}}">
                        <div class="col-md-5 text-right">
                            <label class="url_remove">กรรมการบริษัท</label>
                        </div>
                        <div class="col-md-6">
                            {!! Form::text('offend_power[]', !empty($item)?$item:null , ['class' => 'form-control ','placeholder' => 'กรอกชื่อกรรมการ']) !!}
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success btn-sm btn-outline url_remove" id="url_add">
                                <i class="fa fa-plus"></i>
                            </button>
                            <div class="button_remove"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        @endforeach
    @else
        <div class="box_url col-md-12">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('offend_power') ? 'has-error' : ''}}">
                    <div class="col-md-5 text-right">
                        <label class="url_remove">กรรมการบริษัท</label>
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('offend_power[]', null, ['class' => 'form-control ','placeholder' => 'กรอกชื่อกรรมการ']) !!}
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success btn-sm btn-outline url_remove" id="url_add">
                            <i class="fa fa-plus"></i>
                        </button>
                        <div class="button_remove"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_contact_name') ? 'has-error' : ''}}">
            {!! Form::label('offend_contact_name', 'ชื่อ-สกุลผู้ประสานงาน', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_contact_name', null , ['class' => 'form-control ', 'required' => 'required','id'=>'offend_contact_name']) !!}
                {!! $errors->first('offend_contact_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_contact_tel') ? 'has-error' : ''}}">
            {!! Form::label('offend_contact_tel', 'เบอร์โทร', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_contact_tel', null , ['class' => 'form-control ', 'required' => 'required','id'=>'offend_contact_tel']) !!}
                {!! $errors->first('offend_contact_tel', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_contact_email') ? 'has-error' : ''}}">
            {!! Form::label('offend_contact_email', 'อีเมล', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_contact_email', null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'offend_contact_email']) !!}
                {!! $errors->first('offend_contact_email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('tb3_tisnos') ? 'has-error' : ''}}">
            {!! Form::label('tb3_tisnos', 'มาตรฐานผลิตภัณฑ์อุตสาหกรรม', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tb3_tisnos',  null , ['class' => 'form-control', 'required' =>  true  ,'id'=>'tb3_tisnos' ]) !!}
                {!! Form::hidden('tb3_tis_thainames', null , ['id'=>'tb3_tis_thainames']) !!}
                {!! $errors->first('tb3_tisnos', '<p class="help-block">:message</p>') !!}
            </div>
        </div> 
    </div> 
    <div class="col-md-6">
        <button type="button" class="btn btn-info" id="choose_tisno">เลือก</button>
      </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_location_detail') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                <table class="table color-bordered-table info-bordered-table table-bordered table-sm" id="table_tisno">
                    <thead>
                        <tr>
                            <th class="text-center" width="2%">#</th>
                            <th class="text-center" width="30%">เลข มอก.</th>
                            <th class="text-center" width="60%">ผลิตภัณฑ์อุตสาหกรรม</th>
                            <th class="text-center" width="8%">ลบ</th>
                        </tr>
                    </thead>
                    <tbody id="table_tbody_tisno">
                        @if (!empty($lawcasesform) && count($lawcasesform->cases_standards) > 0)
                            @foreach ($lawcasesform->cases_standards as $key => $item)
                                    <tr>
                                        <td class="text-top text-center">
                                            {!! ($key+1) !!}
                                        </td>
                                        <td class="text-top text-center">
                                            <input type="hidden"  name="standard[tb3_tisno][]" value="{!!  $item->tb3_tisno !!}">
                                            {!!  $item->tb3_tisno !!}
                                        </td>
                                        <td class="text-top text-center">
                                            {!!  !empty($item->tis->tb3_TisThainame) ?  $item->tis->tb3_TisThainame: ''  !!}
                                        </td>
                                        <td class="text-top text-center">
                                            <button type="button" class="btn btn-link  remove-row"><i class="fa fa-close text-danger"></i></button>
                                        </td>
                                    </tr>
                            @endforeach 
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('law_basic_arrest_id') ? 'has-error' : ''}}">
            {!! Form::label('law_basic_arrest_id', 'มีการจับกุม', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('law_basic_arrest_id',
                 App\Models\Law\Basic\LawArrest::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                  null,
                   ['class' => 'form-control ', 
                   'required' => true, 
                   'placeholder'=>'- เลือก -', 
                   'id' => 'law_basic_arrest_id']) !!}
                {!! $errors->first('law_basic_arrest_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('law_basic_section_id') ? 'has-error' : ''}}">
            {!! Form::label('law_basic_section_id', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('law_basic_section_id[]',
                 App\Models\Law\Basic\LawSection::where('section_type',1)->where('state',1)->orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id'), 
                  null, 
                 ['class' => ' ', 
                    'id' => 'law_basic_section_id',
                   'multiple'=>'multiple',
                   'required' => true
                 ]) !!}
                {!! $errors->first('law_basic_section', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="col-md-5 text-right">
            <h3>รายละเอียดเพิ่มเติม</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_location_detail') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::label('offend_location_detail', 'รายละเอียดเกี่ยวกับสถานที่ที่ตรวจพบการกระทำความผิด (เช่น ตรวจพบผลิตภัณฑ์อย่างไร สถานที่ดังกล่าว ประกอบกิจการอะไร ระยะเวลาที่ประกอบกิจการ)', ['class' => 'col-md-12']) !!}
                {!! Form::textarea('offend_location_detail', null, ['class' => 'form-control ', 'rows'=>3]) !!}
                {!! $errors->first('offend_location_detail', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_product_detail') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::label('offend_product_detail', 'รายละเอียดเกี่ยวกับผลิตภัณฑ์ที่ไม่เป็นไปตามมาตรฐาน/มีเหตุอันควรเชื่อว่าไม่เป็นไปตามมาตรฐานที่พนักงานตรวจสอบพบ', ['class' => 'col-md-12']) !!}
                {!! Form::textarea('offend_product_detail', null, ['class' => 'form-control ', 'rows'=>3]) !!}
                {!! $errors->first('offend_product_detail', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

 
@push('js')
    <script>
        $(document).ready(function() {

          
            $('body').on('click', '.fa-exclamation-circle', function(){
                Swal.fire({
                        title: 'เชื่อมโยงข้อมูลจากระบบตรวจติดตามออนไลน์ (e-surveillance)',
                        width: 600 
                        });
            });

            $('#tb3_tisnos').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-tb3tis") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {
                    $('#tb3_tisnos').val(jsondata.tb3_tisno);
                    // $('#tis_ids').val(jsondata.id);
                    $('#tb3_tis_thainames').val(jsondata.tb3_tis_thainame);
                }
            });



             $("#choose_tisno").click(function() {
                if($('#tb3_tisnos').val() == ''){
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกมาตรฐานผลิตภัณฑ์อุตสาหกรรม',
                        showConfirmButton: false,
                        timer: 2500
                    });

                }else{
                    var tb3_tisno = $('#tb3_tisnos').val();
                    var tb3_tis_thainames = $('#tb3_tis_thainames').val();
                    var table = $('#table_tbody_tisno');
 
                        var tbody =  '<tr>';
                            tbody += '<td class="text-center text-top"></td>';
                            tbody += '<td class="text-center text-top">' + tb3_tisno ;
                            tbody += '<input type="hidden"   name="standard[tb3_tisno][]" value="' + tb3_tisno + '">';
                            tbody +=  '</td>';
                            tbody += '<td>' + tb3_tis_thainames + '</td>';
                            tbody += '<td class="text-center text-top">';
                            tbody += '<button type="button" class="btn btn-link  remove-row"><i class="fa fa-close text-danger"></i></button>';          
                            tbody += '</td>';
                            tbody += '</tr>';
                                    
                            table.append(tbody);
                            ResetTableNumber();
                        $("#tb3_tisnos").val('').change();
                        $('#tb3_tis_thainames').val('');
                   
                    
                }    

            });

            // ลบแถว
            $('body').on('click', '.remove-row', function(){
                $(this).parent().parent().remove();
                ResetTableNumber();
            });

             
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
                     
                        $('#offend_subdistrict_id').val(jsondata.sub_ids);
                        $('#offend_district_id').val(jsondata.dis_id);
                        $('#offend_province_id').val(jsondata.pro_id);
                        $('#offend_zipcode').val(jsondata.zip_code);

                    }
                });
            });

            let offend_power_box = $('.box_url:not(:first-child)');
            let btn_offend = '<button class="btn btn-danger btn-outline btn-sm btn_url_remove" type="button"><i class="fa fa-times"></i></button>';
                offend_power_box.find('.url_remove').remove();
                offend_power_box.find('.button_remove').html(btn_offend);

            //เพิ่ม url
            $('#url_add').click(function(event) {
                $('.box_url:first').clone().appendTo('#box_url').slideDown();
                var btn = '<button class="btn btn-danger btn-outline btn-sm btn_url_remove" type="button"><i class="fa fa-times"></i></button>';
                var box_url = $('.box_url:last');
                box_url.find('input').val('');
                box_url.find('.url_remove').remove();
                box_url.find('.button_remove').html(btn);
            });

            //ลบ url
            $('body').on('click', '.btn_url_remove', function(event) {
                $(this).parent().parent().parent().remove(); 
            });


            $('#offend_license_type_1').on('ifChecked', function (event) {
                LoadOffend();
                // $('#box_contact').find('input, select, textarea').val('');
            });
            $('#offend_license_type_2').on('ifChecked', function (event) {
                LoadOffend();
                // $('#box_offend').find('input, select, textarea').val('');

            });
            ResetTableNumber();
            LoadOffend();
            // OptionDisable();

            $('body').on('change', '#offend_ref_no', function(){
                table_tbody_license_numbers();     
        
              });

            $(".offend_taxid").on("keypress",function(e){
                var applicanttype_id  =  $('.applicanttype_id:checked').val();
                if(applicanttype_id == 5){
                var k = e.keyCode;/* เช็คตัวเลข 0-9 */
                    if (k>=48 && k<=57) {
                            return true;
                        }
                        /* เช็คคีย์อังกฤษ a-z, A-Z */
                        if ((k>=65 && k<=90) || (k>=97 && k<=122)) {
                            return true;
                        }
                        /* เช็คคีย์ไทย ทั้งแบบ non-unicode และ unicode */
                        if ((k>=161 && k<=255) || (k>=3585 && k<=3675)) {
                            return false;
                        }
                    }else{
                        var eKey = e.which || e.keyCode;
                        if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                            return false;
                        }
                    }
                });



                $("#search").click(function () {

                    var row               = $(this).val();
                    var applicanttype_id  =  $('.applicanttype_id:checked').val();
                    const cars            = ["","นิติบุคคล", "บุคคลธรรมดา", "คณะบุคคล", "ส่วนราชการ", "อื่นๆ"];
                    var tax_number        = $('#offend_taxid').val();
                        if(tax_number != ""){

                        if(applicanttype_id == 1 || applicanttype_id == 2  || applicanttype_id == 3  || applicanttype_id == 4 ){ //  นิติบุคคล     บุคคลธรรมดา     คณะบุคคล     ส่วนราชการ
                            tax_number = tax_number.toString().replace(/\D/g,'');
                            if(tax_number.length >= 13){
                                    get_taxid(tax_number);
                            }else{
                                Swal.fire({
                                    position: 'center',
                                    width: 600,
                                    title: 'กรุณากรอกเลข'+cars[applicanttype_id]+'ให้ครบ!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }else{  //   อื่นๆ
                            get_taxid(tax_number);
                        }

                    }else{
                        Swal.fire({
                                position: 'center',
                                width: 600,
                                title: 'กรุณากรอกเลข'+cars[applicanttype_id]+'ให้ครบ!',
                                showConfirmButton: false,
                                timer: 1500
                    });
                    }
                });

                $('#person_type').val('1');
                $('#person_type').children('option[value="2"]').remove();
                $('#person_type').children('option[value="3"]').remove();
                $('#person_type').select2();
                $(".applicanttype_id").on("ifChanged",function(){

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
             
                    box_api_null();


                  var applicanttype_id =  $('.applicanttype_id:checked').val();
                  if(applicanttype_id == 1){
                      $('#person_type').val('1');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขนิติบุคคล');
                      $('#offend_taxid').attr('maxlength', '13');
                  }else    if(applicanttype_id == 2){
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขประจำตัวประชาชน');
                      $('#offend_taxid').attr('maxlength', '13');
                   }else    if(applicanttype_id == 3){
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขคณะบุคคล');
                      $('#offend_taxid').attr('maxlength', '13');
                   }else    if(applicanttype_id == 4){
                    $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขส่วนราชการ');
                      $('#offend_taxid').attr('maxlength', '13');
                    }else    if(applicanttype_id == 5){
                       $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').append('<option value="2">เลขที่หนังสือเดินทาง</option>');
                      $('#person_type').append('<option value="3">เลขทะเบียนธุรกิจคนต่างด้าว</option>');
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขอื่นๆ');
                      $('#offend_taxid').attr('maxlength', '30');
                  }else{
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#offend_taxid').attr('placeholder', 'เลขประจำตัวประชาชน');
                      $('#offend_taxid').attr('maxlength', '13');
                  }
            });
            //ข้อมูลจาก api ห้ามแก้ไข
            offend_condition();
                
        });


        function LoadOffend(){

             var checked = $('input[name="offend_license_type"]:checked').val();
                if( checked == '1'){ 
                    $('#div_license_number').show(400);
                }else{
                    $('#div_license_number').hide(400);
                } 
        }

        function offend_condition(){

             var offend_condition = $('#offend_condition').val();
                if( offend_condition == '1'){ 
                    $('#offend_taxid').prop('readonly', true);
                    $('#offend_name').prop('readonly', true);
                    $('#offend_address').prop('readonly',true);
                    $('#offend_moo').prop('readonly',true);
                    $('#offend_building').prop('readonly',true);
                    $('#offend_street').prop('readonly',true);
                    $('#offend_soi').prop('readonly',true);
                    $('#offend_address_search').prop('disabled',true);
                    $('#offend_address_search').select2('destroy'); 
                }else{
                    $('#offend_taxid').prop('readonly', false);
                    $('#offend_name').prop('readonly', false);
                    $('#offend_address').prop('readonly',false);
                    $('#offend_moo').prop('readonly',false);
                    $('#offend_building').prop('readonly',false);
                    $('#offend_street').prop('readonly',false);
                    $('#offend_soi').prop('readonly',false);
                    $('#offend_soi').prop('readonly',false);
                    $('#offend_address_search').prop('disabled',false);
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
                } 
        }


            function offend_null(){

                $('.offend_taxid').val('');
                $('#offend_name').val('');
                $('#offend_ref_no').val('').select2();
                $('#offend_name').val(''); 
                $('#offend_address').val(''); 
                $('#offend_moo').val(''); 
                $('#offend_soi').val(''); 
                $('#offend_building').val(''); 
                $('#offend_street').val(''); 
                $('#offend_subdistrict_txt').val(''); 
                $('#offend_district_txt').val(''); 
                $('#offend_province_txt').val(''); 
                $('#offend_zipcode').val(''); 
                $('#offend_subdistrict_id').val('');  
                $('#offend_district_id').val(''); 
                $('#offend_province_id').val(''); 
                $('#offend_tel').val(''); 
                $('#offend_email').val(''); 
             }

            function box_api_null(){
                $('#offend_taxid').prop('readonly', false);
                $('#offend_name').prop('readonly', false);
                $('#offend_address').prop('readonly',false);
                $('#offend_moo').prop('readonly',false);
                $('#offend_building').prop('readonly',false);
                $('#offend_street').prop('readonly',false);
                $('#offend_soi').prop('readonly',false);
                $('#offend_soi').prop('readonly',false);
                $('#offend_address_search').prop('disabled',false);

                $('#offend_taxid').val('');
                $('#offend_name').val(''); 
                $('#offend_address').val(''); 
                $('#offend_moo').val(''); 
                $('#offend_soi').val(''); 
                $('#offend_building').val(''); 
                $('#offend_street').val(''); 
                $('#offend_subdistrict_txt').val(''); 
                $('#offend_district_txt').val(''); 
                $('#offend_province_txt').val(''); 
                $('#offend_zipcode').val(''); 
                $('#offend_subdistrict_id').val('');  
                $('#offend_district_id').val(''); 
                $('#offend_province_id').val(''); 
                $('#offend_condition').val('0'); 
             }


             function get_offend_ref_tb(){  
    
                    if(checkNone($('#offend_taxid').val())){
                        $.ajax({
                                method: "GET",
                                url: "{{ url('law/cases/forms/get_offend_ref_tb') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "offend_taxid": $('#offend_taxid').val()
                                }
                            }).success(function (msg) { 
                                    if(msg.message == true){
                                        $.each(msg.datas, function( index, data ) {
                                            $('#offend_ref_no').append('<option value="'+data.auto_id_doc+'">'+data.auto_id_doc+'</option>');
                                        });
                                     }
                            });  
                    }
             }
             
             function table_tbody_license_numbers(){
      
                   $('#table_tbody_license_number').html('');
                   $('#offend_ref_tb').val('');
                   if(
                         checkNone($('#offend_ref_no').val())  ||  checkNone($('#offend_taxid').val())  
                    ){
                    $.ajax({
                                method: "GET",
                                url: "{{ url('law/cases/forms/license_numbers') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": "{{ !empty($lawcasesform->id) ?  $lawcasesform->id : '' }}",
                                    "offend_ref_no": $('#offend_ref_no').val(),
                                    "offend_taxid": $('#offend_taxid').val()
                                }
                            }).success(function (msg) { 
                                if(msg.message == true){
                                        $('#offend_ref_tb').val(msg.offend_ref_tb);
                                    $.each(msg.datas, function( index, data ) {
                                     var html = '';
                                        html += '<tr>';

                                        html += '<td class="text-top text-center">';
                                            if(data.check == '1'){
                                                html += ' <input type="checkbox"   name="licenses[license_number][]" checked class="license_number" value="'+data.license_no+'" >';
                                            }else{
                                                html += ' <input type="checkbox"   name="licenses[license_number][]" class="license_number license_number_readonly" value="'+data.license_no+'" >';
                                            }
                                        html += '</td>';

                                        html += '<td class="text-top ">';
                                                html += data.license_no;
                                        html += '</td>';

                                        html += '<td class="text-top text-center">';
                                                html += '<a href="'+data.url+'" target="_blank">';
                                                html += '   <i class="fa fa-file-pdf-o" style="font-size:35px; color:orange" aria-hidden="true"></i>';
                                                html += '</a>';
                                        html += '</td>';
 
                                        html += '<td class="text-top text-center">';
                                            if(data.status == '1'){
                                                html += '<b style="color:green">ใช้งาน</b>';
                                            }else{
                                                html += '<b style="color:red">ไม่ใช้งาน</b>';
                                            }
                                        html += '</td>';

                                        html += '</tr>';
                                        $('#table_tbody_license_number').append(html);
                                  });    
                                }else{
                                    var html = '';
                                        html += '<tr>';
                                        html += '<td class="text-top text-center" colspan="4">';
                                                html += '<b style="color:red">ไม่พบใบอนุญาต</b>';
                                        html += '</td>';
                                        html += '</tr>';
                                        $('#table_tbody_license_number').append(html);
                                }
                            });
                    }
             }
            
 
        function ResetTableNumber(){
                var rows = $('#table_tbody_tisno').children(); //แถวทั้งหมด
                if(rows.length==0){
                    $('#tb3_tisnos').prop('required', true);
                }else{
                    $('#tb3_tisnos').prop('required', false);
                }
                rows.each(function(index, el) {
                    var key = (index+1);
                    //เลขรัน
                    $(el).children().first().html(key);
                });
               
        }

        function get_taxid(tax_number){
            var applicanttype_id =  $('.applicanttype_id:checked').val();
            const cars = ["","นิติบุคคล", "บุคคลธรรมดา", "คณะบุคคล", "ส่วนราชการ", "อื่นๆ"];
                 // Text
                 $.LoadingOverlay("show", {
                                image       : "",
                                text        : "กำลังโหลด..."
                        });
                        $.ajax({
                            url: "{!! url('law/funtion/get_taxid') !!}",
                            method:"POST",
                            data:{
                                _token: "{{ csrf_token() }}",
                                tax_id:tax_number,
                                applicanttype_id:applicanttype_id
                                },
                            success:function (result){
                                $.LoadingOverlay("hide");

                                if(result.check_api == true  ){

                                        if(result.type == 1){ //นิติบุคคล
                                            if(result.type == applicanttype_id){//เช็คว่าตรงกับที่เลือกไหม
                                                    data_pid(tax_number);
                                            }else{
                                                    Swal.fire({
                                                        title: result.status,
                                                        showCancelButton: true,
                                                        showConfirmButton: true,
                                                        width: 1500,
                                                        confirmButtonText: 'ยืนยัน',
                                                        cancelButtonText: 'ยกเลิก',
                                                    }).then((result) => {
                                                            /* Read more about isConfirmed, isDenied below */
                                                            if (result.value) {
                                                                $('.applicanttype_id[value="1"]').prop('checked', true);
                                                                $('.applicanttype_id').iCheck('update');
                                                                $('.branch_type[value="1"]').prop('checked', true);
                                                                $('.branch_type').iCheck('update');
                                                                $('#branch_type1').prop('disabled', false);
                                                                $('#person_type').children('option[value!=""]').remove();
                                                                $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                                                                $('#person_type').val('1');
                                                                $('#person_type').select2();
                                                                data_pid(tax_number);
                                                            }
                                                    });
                                            }
                                        }else   if(result.type == 2){  // บุคคลธรรมดา
                                                        if(result.person == 1 ){//เสียชีวิต
                                                            Swal.fire({
                                                                title: result.status,
                                                                width: 1500,
                                                                showConfirmButton: true,
                                                                confirmButtonText: 'ยืนยัน',
                                                            });

                                                        }else{
                                                            if(result.type == applicanttype_id){//เช็คว่าตรงกับที่เลือกไหม
                                                                data_pid(tax_number);
                                                            }else{
                                                                Swal.fire({
                                                                        title: result.status,
                                                                        showCancelButton: true,
                                                                        width: 1500,
                                                                        confirmButtonText: 'ยืนยัน',
                                                                        cancelButtonText: 'ยกเลิก',
                                                                }).then((result) => {
                                                                    /* Read more about isConfirmed, isDenied below */
                            
                                                                    if (result.value) {
                                                                        $('.applicanttype_id[value="2"]').prop('checked', true);
                                                                        $('.applicanttype_id').iCheck('update');
                                                                        $('#person_type').children('option[value!=""]').remove();
                                                                        $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                                                                        $('#person_type').val('1');
                                                                        $('#person_type').select2();
                                                                        data_pid(tax_number);
                                                                    }
                                                                });
                                                            }
                                                        }
                                            }else   if(result.type == 3){  // คณะบุคคล

                                                if(result.type == applicanttype_id){
                                                    data_pid(tax_number);
                                                }else{
                                                    Swal.fire({
                                                    title:  result.status,
                                                    showDenyButton: true,
                                                    showCancelButton: true,
                                                    width: 1500,
                                                    confirmButtonText: 'ยืนยัน',
                                                    cancelButtonText: 'ยกเลิก',
                                                }).then((result) => {
                                                        if (result.value) {
                                                            $('.applicanttype_id[value="3"]').prop('checked', true);
                                                                $('.applicanttype_id').iCheck('update');
                                                            $('.branch_type[value="1"]').prop('checked', true);
                                                            $('.branch_type').iCheck('update');
                                                            $('#branch_type1').prop('disabled', false);
                                                            $('#person_type').children('option[value!=""]').remove();
                                                            $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                                                            $('#person_type').val('1');
                                                            $('#person_type').select2();
                                                            data_pid(tax_number);
                                                        }
                                                    });
                                                }
                                
                                            }
                                }else{
                                    $.LoadingOverlay("hide");
                                    Swal.fire({
                                        icon: 'warning',
                                        width: 600,
                                        position: 'center',
                                        title: 'ไม่พบข้อมูล'+cars[applicanttype_id],
                                        showConfirmButton: true,
                                    });
                                    $('#person_type').val('0');
                                }
                            },
                            error:function(data){
                                $.LoadingOverlay("hide");
                                Swal.fire({
                                    icon: 'warning',
                                    width: 1200,
                                    position: 'center',
                                    title: 'ขออภัยไม่สามารถเชื่อมโยงข้อมูลจากหน่วยงานได้ และระบบตรวจสอบไม่พบเลขดังกล่าวในฐานข้อมูล กรุณาระบุข้อมูลเอง',
                                    showConfirmButton: true,
                                });
                                $('#person_type').val('0');
                            }
                        });
        }

        // 1.	การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก
        function data_pid(tax_number) {
            $.LoadingOverlay("show", {
                image       : "",
                text        : "กำลังโหลด..."
            });
            const cars = ["","นิติบุคคล", "บุคคลธรรมดา", "คณะบุคคล", "ส่วนราชการ", "อื่นๆ"];
            var applicanttype_id =  $('.applicanttype_id:checked').val();
            
                if(applicanttype_id == 1 || applicanttype_id == 2 || applicanttype_id == 3){
                  $.ajax({
                    url: "{!! url('law/funtion/datatype') !!}",
                    method:"POST",
                    data:{
                          _token: "{{ csrf_token() }}",
                          applicanttype_id:applicanttype_id,
                          tax_id:tax_number
                        },
                    success:function (result){
                        if(checkNone(result.name) && result.length != 0){
                            if(applicanttype_id == 1){  //นิติบุคคล
                                    $('#offend_name').val(result.name);

                            }else if(applicanttype_id == 2){ //บุคคลธรรมดา
                            var name = '';
                                name += checkNone(result.JuristicType) ?result.JuristicType:'';
                                name += checkNone(result.name) ? result.name:'';
                                name += checkNone(result.name_last) ? (' '+result.name_last):'';
                                $('#offend_name').val(name);
                            }else if(applicanttype_id == 3){ //คณะบุคคล
                                $('#offend_name').val(result.name);
                            }else{
                                $('#offend_name').val('');
                            }

                            $('#offend_address').val(result.address);
                            $('#offend_soi').val(result.soi);
                            if(result.moo != 0){
                                $('#offend_moo').val(result.moo);
                            }else{
                                $('#offend_moo').val('');
                            }
                              $('#offend_street').val(result.road);
                              $('#offend_subdistrict_txt').val(result.tumbol);
                              $('#offend_district_txt').val(result.ampur);
                              $('#offend_province_txt').val(result.province);
                              $('#offend_subdistrict_id').val(result.tumbol_id);  
                              $('#offend_district_id').val(result.ampur_id); 
                              $('#offend_province_id').val(result.province_id); 

                              $('#offend_zipcode').val(result.zipcode); 
                            
                            $.LoadingOverlay("hide");

                            //ข้อมูลจาก api ไม่ให้แก้ไข
                            $('#offend_taxid').prop('readonly', true);
                            $('#offend_name').prop('readonly', true);
                            $('#offend_address').prop('readonly',true);
                            $('#offend_moo').prop('readonly',true);
                            $('#offend_building').prop('readonly',true);
                            $('#offend_street').prop('readonly',true);
                            $('#offend_soi').prop('readonly',true);
                            $('#offend_address_search').prop('disabled',true);
                            $('#offend_address_search').select2('destroy'); 
                            $('#offend_condition').val('1'); 
                        }else{
                            $.LoadingOverlay("hide");
                            Swal.fire({
                                icon: 'warning',
                                width: 600,
                                position: 'center',
                                title: 'ไม่พบข้อมูล'+cars[applicanttype_id],
                                showConfirmButton: true,
                            });
                        }
        
                      },
                        error:function(data){
                            $.LoadingOverlay("hide");

                                Swal.fire({
                                    icon: 'warning',
                                    width: 600,
                                    position: 'center',
                                    title: 'ไม่พบข้อมูล'+cars[applicanttype_id],
                                    showConfirmButton: true,
                                });
                            }
                   });
                }else{
                    $.LoadingOverlay("hide");
                    box_api_null();

                }


            }




    </script>
@endpush




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



{{-- <div class="row" id="row_license_number" style="display:{{ (!empty($lawcasesform) && ($lawcasesform->offend_license_type=='2'))?' none':''}}">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_license_number') ? 'has-error' : ''}}">
            {!! Form::label('offend_license_number', 'เลขที่ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!} 
            <div class="col-md-7">
                {!! Form::text('offend_license_number', null , ['class' => 'form-control', 'id'=>'offend_license_number', 'placeholder' => 'ค้นจาก เลขใบอนุญาต/ชื่อผู้ได้รับใบอนุญาต/TAXID']) !!}
                {!! Form::hidden('offend_tb4_tisilicense_id', null, ['id'=>'offend_tb4_tisilicense_id']) !!}
                {!! $errors->first('offend_license_number', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-6">
            {!! Form::label('offend_license_number', 'ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!}
            <p id="show_license">
                @if(!empty($license_pdf))
                    <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/{{ $license_pdf->license_pdf }}" target="_blank">
                        <i class="fa fa-file-pdf-o" style="font-size:35px; color:orange" aria-hidden="true"></i>
                    </a>
                    &nbsp [ <b style="color:green">ใช้งาน</b> ]
                @else
                    <i class="fa fa-file-text" style="font-size:35px; color:#92b9b9" aria-hidden="true"></i>
                    &nbsp [ <b style="color:red">ไม่พบไฟล์</b> ]
                @endif
             </p>
        </div>
        <div class="col-md-6">
            {!! Form::checkbox('offend_license_notify', '1', (!empty($lawcasesform->offend_license_notify)?true:(empty($lawcasesform->offend_license_notify)?false:null)), ['class'=>'check', 'id' => 'offend_license_notify', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
            <label for="offend_license_notify">มีหนังสือแจ้งเตือนพักใช้หรือไม่?</label>
        </div>
    </div>
</div> --}}

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('location_address') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-5 control-label text-right']) !!}
            <div class="col-md-7">
                <input type="checkbox" class="check " id="foreign" value="1" name="foreign" data-checkbox="icheckbox_square-green"    @if(!empty($lawcasesform->foreign) &&  $lawcasesform->foreign == '1') checked @endif>
                <label for="foreign" id="label_foreign"> ต่างชาติ (ระบุเลขพาสปอร์ต)</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_taxid') ? 'has-error' : ''}}">
            {!! Form::label('offend_taxid', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-5 control-label','id'=>'label_offend_taxid']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_taxid', null , ['class' => 'form-control offend_taxid  ', 'required' => 'required', 'id'=>'offend_taxid', 'maxlength' => '13', 'placeholder' => 'ค้นจาก เลขประจำตัวผู้เสียภาษี/TAXID']) !!}

                {!! $errors->first('offend_taxid', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('offend_name') ? 'has-error' : ''}}">
            {!! Form::label('offend_name', 'ผู้ประกอบการ/ผู้กระทำความผิด', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('offend_name', null , ['class' => 'form-control ', 'required' => 'required', 'id'=>'offend_name']) !!}
                {!! Form::hidden('offend_sso_users_id', null, ['id'=>'offend_sso_users_id']) !!}
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
                {!! Form::hidden('', null , ['id'=>'check_tb3_tisnos']) !!}
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
 
{{-- <div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('tb3_tis_thainame') ? 'has-error' : ''}}">
            {!! Form::label('tb3_tis_thainame', 'ผลิตภัณฑ์อุตสาหกรรม', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tb3_tis_thainame', !empty($tis_thainame)?$tis_thainame->tb3_TisThainame:null , ['class' => 'form-control ','id'=>'tb3_tis_thainame', 'disabled'=>'disabled']) !!}
                {!! $errors->first('tb3_tis_thainame', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div> --}}

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
    {{-- <button type="button" class="btn btn-info" id="choose_section">เลือก</button> --}}
    </div>
</div>
{{-- <div class="row">
    <div class="col-md-10">
        <div class="form-group {{ $errors->has('offend_location_detail') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                <table class="table color-bordered-table info-bordered-table table-bordered table-sm" id="table_section">
                    <thead>
                    <tr>
                        <th class="text-center" width="2%">#</th>
                        <th class="text-center" width="30%">ฝ่าฝืนตามมาตรา</th>
                        <th class="text-center" width="60%">คำอธิบายมาตรา</th>
                        <th class="text-center" width="8%">ลบ</th>
                    </tr>
                    </thead>
                    <tbody id="table_tbody_section">
                        @php
                            $sectionlists = $lawcasesform->Sectionlist;
                        @endphp
                        @if (!empty($sectionlists) && count($sectionlists) > 0)
                            @foreach ($sectionlists as $key => $sectionlist)
                                <tr>
                                    <td class="text-center section_list_no"> {{ ($key+1)}}</td>
                                        <input type="hidden" class="section_hidden" name="law_basic_section_id[]" value="{{ !empty($sectionlist->id)?$sectionlist->id:null }}">
                                    <td class="text-center">
                                        {{ !empty($sectionlist->number)?$sectionlist->number:null }}
                                    </td>
                                    <td>
                                        {{ !empty($sectionlist->title)?$sectionlist->title:null }}
                                    </td>
                                    <td class="text-center text-top">
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
</div> --}}
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
                    return $.get('{{ url("funtions/search_sub_department_tb3tis") }}', { query: query,owner_depart_type: $('#owner_depart_type').val(),owner_sub_department_id: $('#owner_sub_department_id').val() }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {
                    $('#tb3_tisnos').val(jsondata.tb3_tisno);
                    if(jsondata.tb3_tisno != ''){
                        $('#check_tb3_tisnos').val(jsondata.tb3_tisno);
                    }else{
                        $('#check_tb3_tisnos').val('');
                    }
                
                    // $('#tis_ids').val(jsondata.id);
                    $('#tb3_tis_thainames').val(jsondata.tb3_tis_thainame);
                }  
            });



             $("#choose_tisno").click(function() {
                // || $('#check_tb3_tisnos').val() == ''
                if($('#tb3_tisnos').val() == '' ){
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
                        $('#check_tb3_tisnos').val('');
                   
                    
                }    

            });

            // ลบแถว
            $('body').on('click', '.remove-row', function(){
                $(this).parent().parent().remove();
                ResetTableNumber();
            });
            // $("#choose_section").click(function() {
                
            //     if($('#law_basic_section_id').val() == ''){
            //         Swal.fire({
            //             position: 'center',
            //             icon: 'warning',
            //             title: 'กรุณาเลือกฝ่าฝืนตามมาตรา',
            //             showConfirmButton: false,
            //             timer: 2500
            //         });

            //     }else{
            //         var section_id = $('#law_basic_section_id').val();
            //         var table = $('#table_tbody_section');

            //         $.ajax({
            //             url: "{!! url('law/cases/forms/section-relation/') !!}" + "/" + section_id
            //         }).done(function( jsondata ) {
    
            //             var tbody =  '<tr>';
            //                 tbody += '<td class="text-center section_list_no"></td>';
            //                 tbody += '<input type="hidden" class="section_hidden" name="law_basic_section_id[]" value="' + jsondata.id + '">';
            //                 tbody += '<td class="text-center">' + jsondata.number + '</td>';
            //                 tbody += '<td>' + jsondata.title + '</td>';
            //                 tbody += '<td class="text-center text-top">';
            //                 tbody += '<button type="button" class="btn btn-link  remove-row"><i class="fa fa-close text-danger"></i></button>';          
            //                 tbody += '</td>';
            //                 tbody += '</tr>';
                                    
            //             table.append(tbody);
            //             OrderSectionNo();
            //             OptionDisable();
            //         $("#law_basic_section_id").val('').change();

            //         });
                    
            //     }    

            // });

            //ลบแถว
            // $('body').on('click', '.remove-row', function(){
            //     $(this).parent().parent().remove();
            //     OrderSectionNo();
            // });



             
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

            // มีการจับกุม
            $("#law_basic_arrest_id").on('change', function () {
                         // ส่วนร่วมในคดี
                         var m_bs_reward_group_id = $('#m_bs_reward_group_id');
                         var url  = '{{ url('/law/cases/forms/get_m_bs_reward_group') }}' ;
                if($(this).val() != ''){
                             var id  = $(this).val();

                            $.ajax({
                                url: url,
                                type: 'GET',
                                cache: false,
                                success: function(data) {
                                    var checks = [];
                                    if (id == '1') { //ไม่มีการจับกุม

                             

                                        if (data.reward_group_not.length > 0) {
                                            if($('#myTable-staff tbody').find('.basic_reward_group_id').length > 0){
                                                          $('#myTable-staff tbody').find('.basic_reward_group_id').each(function(index, el) {
                                                               const check2 = data.check2.includes(parseInt($(el).val()));
                                                                  if(check2 === false ){
                                                                     checks.push(parseInt($(el).val()));
                                                                  }
                                                          }); 

                                               }
                                               if(checks.length > 0){
                                                            Swal.fire({ 


                                                                    icon: 'warning',
                                                                    title: 'กรุณาตรวจสอบ/แก้ไข',
                                                                    html: 'ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี เนื่องจากท่านมีการเปลี่ยนแปลงประเภทการจับกุม',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'ตรวจสอบ',
                                                                    cancelButtonText: 'ยกเลิก'
                                                            }).then((result) => {
                                                                if (result.value) { 
                                                                    table_staff.search('').columns().search('').draw(); //clear ตัวค้นหา รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
                                                                    $('select[name="myTable-staff_length"]').val('-1');
                                                                    $('select[name="myTable-staff_length"]').change();
                                                                      $.each(checks,function(index, item){
                                                                              var reward_group =   $('#myTable-staff tbody').find('.basic_reward_group_id[value="'+item+'"]');
                                                                               $(reward_group).closest( "tr" ).find('.reward_group').html('(กรุณาตรวจสอบ/แก้ไข)');
                                                                               $(reward_group).closest( "tr" ).find('.reward_group').addClass('text-danger reward_group_danger');
                                                                               $(reward_group).val('');
                                                                            // table_staff
                                                                            //         .row($('#myTable-staff tbody').find('.basic_reward_group_id[value="'+item+'"]').closest( "tr" ) )
                                                                            //         .remove()
                                                                            //         .draw();
                                                                        });
                                                                    
                                                                        OrderStaffNo();
                                                                        $('select[name="myTable-staff_length"]').val('10');
                                                                        $('select[name="myTable-staff_length"]').change();
                                                                        get_m_bs_reward_group(data.reward_group_not);
                                                                         $('html, body').animate({
                                                                                      scrollTop:   $( "#myTable-staff" ).offset().top
                                                                          }, 2000);
                                                                  
                                                                } else if (   result.dismiss === Swal.DismissReason.cancel ) {
                                                                       $("#law_basic_arrest_id").val('2').select2(); 
                                                                        get_m_bs_reward_group(data.reward_group);
                                                                }else{
                                                                        $("#law_basic_arrest_id").val('2').select2(); 
                                                                        get_m_bs_reward_group(data.reward_group);
                                                                }
                                                            })
                                                }else{
                                                    get_m_bs_reward_group(data.reward_group_not);
                                                }
                                        }
                                    
                                    }else{  // มีการจับกุม
                                              if (data.reward_group.length > 0) {
                                               if($('#myTable-staff tbody').find('.basic_reward_group_id').length > 0){
                                                         $('#myTable-staff tbody').find('.basic_reward_group_id').each(function(index, el) {
                                                               const check1 = data.check1.includes(parseInt($(el).val()));
                                                                  if(check1 === false ){
                                                                     checks.push(parseInt($(el).val()));
                                                                  }
                                                          }); 
                                                 }
                                                if(checks.length > 0){
                                                            Swal.fire({
                                                                    icon: 'warning',
                                                                    title: 'กรุณาตรวจสอบ/แก้ไข',
                                                                    html: 'ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี เนื่องจากท่านมีการเปลี่ยนแปลงประเภทการจับกุม',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'ตรวจสอบ',
                                                                    cancelButtonText: 'ยกเลิก'
                                                            }).then((result) => { 
                                                                if (result.value) {
                                                                            table_staff.search('').columns().search('').draw(); //clear ตัวค้นหา รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
                                                                            $('select[name="myTable-staff_length"]').val('-1');
                                                                            $('select[name="myTable-staff_length"]').change();
                                                                            $.each(checks,function(index, item){
                                                                                    var reward_group =   $('#myTable-staff tbody').find('.basic_reward_group_id[value="'+item+'"]');
                                                                                    $(reward_group).closest( "tr" ).find('.reward_group').html('(กรุณาตรวจสอบ/แก้ไข)');
                                                                                    $(reward_group).closest( "tr" ).find('.reward_group').addClass('text-danger reward_group_danger');
                                                                                    $(reward_group).val('');
                                                                                   
                                                                                    // table_staff
                                                                                    //         .row($('#myTable-staff tbody').find('.basic_reward_group_id[value="'+item+'"]').closest( "tr" ) )
                                                                                    //         .remove()
                                                                                    //         .draw(); 
                        
                                                                                }); 
                                                                           
                                                                             $('select[name="myTable-staff_length"]').val('10');
                                                                            $('select[name="myTable-staff_length"]').change();
                                                                            OrderStaffNo();
                                                                            get_m_bs_reward_group(data.reward_group);
                                                                            $('html, body').animate({
                                                                                      scrollTop:   $( "#myTable-staff" ).offset().top
                                                                           }, 2000);
                                                                } else if (   result.dismiss === Swal.DismissReason.cancel ) {
                                                                       $("#law_basic_arrest_id").val('1').select2(); 
                                                                        get_m_bs_reward_group(data.reward_group);
                                                                }else{
                                                                        $("#law_basic_arrest_id").val('1').select2(); 
                                                                        get_m_bs_reward_group(data.reward_group_not);
                                                                }
                                                            })
                                                }else{
                                                      get_m_bs_reward_group(data.reward_group);
                                                }
                                         }
                                    }

                                }
                            });
                }
            }); 

          
            // $('#offend_license_number').typeahead({
            //     minLength: 3,
            //     source:  function (query, process) {
            //         return $.get('{{ url("funtions/search-tb4tisilicense") }}', { query: query }, function (data) {
            //             return process(data);
            //         });
            //     },
            //     autoSelect: true,
            //     afterSelect: function (jsondata) {

            //         var box_offend = $('#box_offend'); 
            //         var box_contact = $('#box_contact');
            //         //ไม่ให้แก้เลขใบอนุญาต
            //         $('#offend_license_number').keyup(function (e) { 
            //             box_offend.find('input, select, textarea').prop('readonly', false);
            //             box_offend.find('input, select, textarea').val('');
            //             box_contact.find('input, select, textarea').val('');

            //             $("#offend_address_search").select2({
            //                 dropdownAutoWidth: true,
            //                 width: '100%',
            //                 ajax: {
            //                     url: "{{ url('/funtions/search-addreess') }}",
            //                     type: "get",
            //                     dataType: 'json',
            //                     delay: 250,
            //                     data: function (params) {
            //                         return {
            //                             searchTerm: params // search term
            //                         };
            //                     },
            //                     results: function (response) {
            //                         return {
            //                             results: response
            //                         };
            //                     },
            //                     cache: true,
            //                 },
            //                 placeholder: 'คำค้นหา',
            //                 minimumInputLength: 1,
            //             });
            //         });
                  
            //         //มีใบอนุญาตห้ามแก้ไขข้อมู,
            //         box_offend.find('input, select, textarea').prop('readonly', true);
            //         box_offend.find('#offend_address_search').select2('destroy'); 

            //         $('#offend_license_number').val(jsondata.license_no);
            //         $('#offend_sso_users_id').val(jsondata.sso_user_id); 
            //         $('#offend_tb4_tisilicense_id').val(jsondata.id); 
            //         $('#offend_name').val(jsondata.trade_name); 
            //         $('#offend_taxid').val(jsondata.taxid); 
            //         $('#offend_factory_name').val(jsondata.factory_name); 

            //         $('#offend_address').val(jsondata.address_no); 
            //         $('#offend_moo').val(jsondata.moo); 
            //         $('#offend_soi').val(jsondata.soi); 
            //         $('#offend_building').val(jsondata.building); 
            //         $('#offend_street').val(jsondata.street); 

            //         $('#offend_subdistrict_id').val(jsondata.subdistrict_id); 
            //         $('#offend_subdistrict_txt').val(jsondata.subdistrict); 
            //         $('#offend_district_id').val(jsondata.district_id); 
            //         $('#offend_district_txt').val(jsondata.district); 
            //         $('#offend_province_id').val(jsondata.province_id); 
            //         $('#offend_province_txt').val(jsondata.province); 
            //         $('#offend_zipcode').val(jsondata.zipcode); 
                  
            //         $('#offend_tel').val(jsondata.contact_tel); 
            //         $('#offend_email').val(jsondata.email); 
            //         $('#offend_contact_name').val(jsondata.contact_name); 
            //         $('#offend_contact_tel').val(jsondata.contact_phone_number); 
            //         $('#offend_contact_email').val(jsondata.email);

            //         $('#tb3_tisno').val(jsondata.tis_no);
            //         $('#tis_id').val(jsondata.tis_id);
            //         $('#tb3_tis_thainame').val(jsondata.tis_name);


            //         let file  = '';
            //         if(jsondata.license_pdf){
            //             file += '<a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/'+jsondata.license_pdf+'" target="_blank">';
            //             file += '<i class="fa fa-file-pdf-o" style="font-size:35px; color:orange" aria-hidden="true"></i>';
            //             file += '</a>';
            //             file += '&nbsp [ <b style="color:green">ใช้งาน</b> ]';
            //         }else{
            //             file +='<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
            //             file += '&nbsp [ <b style="color:red">ไม่พบไฟล์</b> ]';
            //         }
            //         $('p#show_license').empty();
            //         $('p#show_license').append(file);
            //     }
            // });


            $('#offend_taxid').typeahead({
                minLength: 13,
                source:  function (query, process) {

                var box_offend = $('#box_offend'); 
                var box_contact = $('#box_contact');
                    box_offend.find('input, select, textarea').not( "#offend_taxid" ).val('');
                    box_contact.find('input, select, textarea').val('');

                    return $.get('{{ url("funtions/get-ssouser") }}', { query: query,branch_type: '1' }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                     //มีใบอนุญาตห้ามแก้ไขข้อมู,
                    //  $('#box_offend').find('input, select, textarea').prop('readonly', true);
                     $('#offend_name').prop('readonly', true);
                     $('#box_offend').find('#offend_address_search').select2('destroy'); 
                    $('#box_offend').find('#offend_address, #offend_moo, #offend_building, #offend_soi, #offend_street, #offend_address_search').prop('readonly', true);
                    // $('#box_offend').find('#offend_address_search').prop('disabled', true);
                
                    $('#offend_taxid').val(jsondata.tax_number);
                    $('#offend_sso_users_id').val(jsondata.id); 
                    $('#offend_name').val(jsondata.name_show); 
                    $('#offend_address').val(jsondata.contact_address_no); 
                    $('#offend_moo').val(jsondata.contact_moo); 
                    $('#offend_soi').val(jsondata.contact_soi); 
                    $('#offend_building').val(jsondata.contact_building); 
                    $('#offend_street').val(jsondata.contact_street); 
                    $('#offend_subdistrict_txt').val(jsondata.contact_subdistrict); 
                    $('#offend_district_txt').val(jsondata.contact_district); 
                    $('#offend_province_txt').val(jsondata.contact_province); 
                    $('#offend_zipcode').val(jsondata.contact_zipcode); 
                    $('#offend_subdistrict_id').val(jsondata.contact_subdistrict_id);  
                    $('#offend_district_id').val(jsondata.contact_district_id); 
                    $('#offend_province_id').val(jsondata.contact_province_id); 
                    $('#offend_tel').val(jsondata.contact_tel); 
                    $('#offend_email').val(jsondata.email); 
 
                    get_offend_ref_tb();
                    table_tbody_license_numbers();

                      $('#location').val(jsondata.name_show); 
                      $('#same_address_license').prop('checked',true);  
                      $('#same_address_license').iCheck('update');  

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
              
                    }
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

            // ต่างชาติ (ระบุเลขพาสปอร์ต)
            foreign();
            $('#foreign').on('ifChecked', function (event) {
                offend_null();
                foreign();
            });
            $('#foreign').on('ifUnchecked', function (event) {
                offend_null();
                foreign();
            });
            $('body').on('change', '#offend_ref_no', function(){
                table_tbody_license_numbers();     
        
              });
            $(".offend_taxid").on("keypress",function(e){
                    var foreign  =  $('#foreign:checked').val();
                    if(foreign == 1){
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

                
              $(".offend_taxid").on("change",function(e){
                        if($(this).val() == ''){
                             $('#offend_name').prop('readonly', false);
                              $('#box_offend').find('#offend_address, #offend_moo, #offend_building, #offend_soi, #offend_street, #offend_address_search').prop('readonly', false);
                              $('#box_offend').find('#offend_address_search').select2('destroy'); 
                              $('#box_offend').find("#offend_address_search").select2({
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
                });
        });

        function LoadOffend(){

             var checked = $('input[name="offend_license_type"]:checked').val();
            //  console.log(checked);
                if( checked == '1'){ 
                    $('#div_license_number').show(400);
                }else{
                    $('#div_license_number').hide(400);
                }
            
          
            // var same_address_license = $('#label_same_address_license'); 
            // var checked = $('#offend_license_type_1').prop('checked');
            // var license = $('#row_license_number'); 
            // var box_offend = $('#box_offend');      
            // var box_contact = $('#box_contact');      

            // if( checked == true){
            //     license.show(400);
            //     license.find('input, select, textarea').prop('disabled', false);
            //     license.find('input[required], select[required], textarea[required]').prop('required', true);
            //     same_address_license.text('ข้อมูลเดียวกับใบอนุญาต');
            // }else{
            //     license.hide(400);
            //     license.find('input, select, textarea').prop('disabled', true);
            //     license.find('input[required], select[required], textarea[required]').prop('required', false);
            //     same_address_license.text('ข้อมูลเดียวกับผู้ประกอบการ/ผู้กระทำความผิด');

            //     box_offend.find('input, select, textarea').prop('readonly', false);
            // }

        }


        function foreign(){

               var checked = $('#foreign').is(':checked');
         
                if( checked == true){ 
                    $('#label_offend_taxid').html('เลขพาสปอร์ต');
                    $('.offend_taxid').attr('placeholder','ค้นจาก เลขพาสปอร์ต');
                    $('.offend_taxid').attr('maxlength','20');
                    $('.offend_taxid').attr('id','');
                    // $('.offend_taxid').removeClass('check_format_en_and_number');
              
                }else{
                    $('#label_offend_taxid').html('เลขประจำตัวผู้เสียภาษี');
                    $('.offend_taxid').attr('placeholder','ค้นจาก เลขประจำตัวผู้เสียภาษี/TAXID');
                    $('.offend_taxid').attr('maxlength','13');
                    $('.offend_taxid').attr('id','offend_taxid');
                    // $('.offend_taxid').addClass('check_format_en_and_number');
                
                } 
                if($('.offend_taxid').val() == ''){
                             $('#offend_name').prop('readonly', false);
                              $('#box_offend').find('#offend_address, #offend_moo, #offend_building, #offend_soi, #offend_street, #offend_address_search').prop('readonly', false);
                              $('#box_offend').find('#offend_address_search').select2('destroy'); 
                              $('#box_offend').find("#offend_address_search").select2({
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
                $('#offend_sso_users_id').val(''); 
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
            
 
            
        // function OrderSectionNo(){

        //     $('#table_tbody_section').find('.section_list_no').each(function(index, el) {
        //         var uniqid = Math.floor(Math.random() * 1000000);
        //         $(el).closest( "tr" ).attr('data-row', uniqid);
        //         $(el).text(index+1);
        //     });
        //     OptionDisable();


        // }
        // function OptionDisable(){

        //     $("#law_basic_section_id option").prop('disabled', false);
        //     $('#table_tbody_section').find('.section_hidden').each(function(index, el) {
        //         $("#law_basic_section_id option[value='"+ el.value + "']").prop('disabled', true); 
        //     });

        // }
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

         function get_m_bs_reward_group(datas){
            // ส่วนร่วมในคดี
             var m_bs_reward_group_id = $('#m_bs_reward_group_id');
                m_bs_reward_group_id.html('<option value="">- เลือกส่วนร่วมในคดี -</option>');
                $.each(datas,function(index, value){
                    m_bs_reward_group_id.append('<option value="'+value.id+'">'+value.title+'</option>');
               });
        }


    </script>
@endpush


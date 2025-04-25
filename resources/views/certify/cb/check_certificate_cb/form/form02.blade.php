
<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">

<input type="hidden" name="_token" value="{{ csrf_token() }}"/>

<div class="form-group {{ $errors->has('type_standard') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('type_standard', ' ตามมาตรฐานเลข'.':'.'<br/><span class="  font_size">(According to TIS)</span>', ['class' => 'col-md-3 control-label label-height'])) !!}
    <div class="col-md-4" >
        {!! Form::select('type_standard',
        App\Models\Bcertify\Formula::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
        null,
       ['class' => 'form-control',
       'id'=>'type_standard',
        'required' => true,
        'placeholder' =>'- เลือกตามมาตรฐานเลข -']) !!}
       {!! $errors->first('type_standard', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('standard_change') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_standard', 'วัตถุประสงค์ในการยื่นคำขอ'.':'.'<br/><span class=" font_size">(Apply to NSC for)</span>', ['class' => 'col-md-3 control-label label-height'])) !!}
    <label  class="col-md-2 label-height" >
        {!! Form::radio('standard_change', '1', true, ['class'=>'check ', 'data-radio'=>'iradio_square-green']) !!}
         &nbsp;ขอใบรับรอง  <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class=" font_size" >(initial)</span>
     </label>
     <label  class="col-md-2 label-height">
        {!! Form::radio('standard_change', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;ต่ออายุใบรับรอง<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class=" font_size">(Renew certifcale)</span>
    </label>
    <label  class="col-md-2 label-height">
        {!! Form::radio('standard_change', '3', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;ขยายขอบข่าย <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class=" font_size">(re-certificate)</span>
    </label>
    <label  class="col-md-3 label-height">
        {!! Form::radio('standard_change', '4', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
        &nbsp;การเปลี่ยนแปลงมาตรฐาน  <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class=" font_size">(Standard change)</span>
    </label>
</div>

@if(!empty($certi_cb->standard_change) && $certi_cb->standard_change != 1)
    <div id="box_ref_application_no" >
        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('app_no', 'อ้างอิงเลขที่คำขอ'.':'.'<br/><span class=" font_size">(Application No.)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
            <div class="col-md-4">
                {!! Form::text('app_no', null, ['class' => 'form-control', 'id' => 'app_no']) !!}
                {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('app_certi_cb_export_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('app_certi_cb_export_id', 'ใบรับรองเลขที่'.':'.'<br/><span class="  font_size">(Certificate No)</span>', ['class' => 'col-md-3 control-label label-height'])) !!}
            <div class="col-md-4">
                {!! Form::text('app_certi_cb_export_id', null, ['class' => 'form-control', 'id' => 'app_certi_cb_export_id']) !!}
                {!! $errors->first('app_certi_cb_export_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('accereditation_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('accereditation_no', 'หมายเลขการรับรองที่'.':'.'<br/><span class="  font_size">(Accreditation No. Calibration)</span>', ['class' => 'col-md-3 control-label label-height'])) !!}
            <div class="col-md-4">
                {!! Form::text('accereditation_no', null, ['class' => 'form-control', 'id' => 'accereditation_no']) !!}
                {!! $errors->first('accereditation_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
@endif

<div class="form-group {{ $errors->has('branch_type') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('branch_type', 'ประเภทสาขา'.':'.'<br/><span class=" font_size">(Branch Type)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-4" >
        <div class="iradio_square-blue {!! (@$certi_cb->branch_type == 1)?'checked':'' !!}"></div>
        <label for="branch_type1">&nbsp;สำนักงานใหญ่&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <div class="iradio_square-blue {!! (@$certi_cb->branch_type != 1)?'checked':'' !!}"></div>
        <label for="branch_type2">&nbsp;สาขา</label>
        <input type="hidden" name="branch_type" value="{!! (@$certi_cb->branch_type == 1)?1:2 !!}" />
    </div>
</div>

<div class="form-group {{ $errors->has('petitioner') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('petitioner', ' สาขา'.':'.'<br/><span class="  font_size">(ฺBranch)</span>', ['class' => 'col-md-3 control-label label-height'])) !!}
    <div class="col-md-6" >
        {!! Form::select('petitioner',
              App\Models\Bcertify\CertificationBranch::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
        null,
       ['class' => 'form-control',
       'id'=>'petitioner',
        'required' => true,
        'placeholder' =>'- เลือกสาขาเข้าขอรับการรับรอง -']) !!}
       {!! $errors->first('petitioner', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('name_standard') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_standard', 'ชื่อหน่วยรับรอง'.':'.'<br/><span class=" font_size">(์Name of Certification body)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-6">
        {!! Form::text('name_standard', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name_standard', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('name_en_unit') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_en_unit', 'ชื่อหน่วยรับรอง (ENG)'.':'.'<br/><span class=" font_size">(Name of Certification body)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-6">
        {!! Form::text('name_en_unit', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name_en_unit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('name_short_standard') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('name_short_standard', 'ชื่อย่อหน่วยรับรอง'.':'.'<br/><span class=" font_size">(Short Name of Certification body)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
    <div class="col-md-6">
        {!! Form::text('name_short_standard', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name_short_standard', '<p class="help-block">:message</p>') !!}
    </div>
</div>
 
<div class="form-group {{ $errors->has('checkbox_address') ? 'has-error' : ''}}">
    {!! Form::label('checkbox_address', 'ที่อยู่: ', ['class' => 'col-md-3 control-label  label-height']) !!}
    <div class="col-md-6 m-t-5">
        <div class="checkbox checkbox-success  label-height">
            <input id="checkbox_address" class="checkbox_address" type="checkbox" name="checkbox_address"
                   value="1"  {{ (isset($certi_cb) && $certi_cb->checkbox_address == 1) ? 'checked': '' }}>
            <label for="checkbox_address  label-height"> &nbsp;ใช่ที่อยู่ตามที่อยู่จดทะเบียน &nbsp;
                <br/> &nbsp;  &nbsp; <span class=" font_size">(Use Head offlce address)</span>
           </label>
        </div>
    </div>
</div>

<div class="col-md-12"><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <label for="">ที่ตั้งหน่วยงาน</label> </div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_number', ' เลขที่'.':'.'<br/><span class=" font_size">(Address)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'required','id'=>'address']) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('allay') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('allay', 'หมู่ที่'.':'.'<br/><span class=" font_size">(Mool)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('allay', null, ['class' => 'form-control','id'=>'allay']) !!}
                {!! $errors->first('allay', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('village_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_soi', 'ตรอก/ซอย'.':'.'<br/><span class=" font_size">(Trok/Sol)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('village_no', null, ['class' => 'form-control','id'=>'village_no']) !!}
                {!! $errors->first('village_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('road') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_street', 'ถนน'.':'.'<br/><span class=" font_size">(Street/Road)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('road', null, ['class' => 'form-control','id'=>'road']) !!}
                {!! $errors->first('road', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('province') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_city', 'จังหวัด'.':'.'<br/><span class=" font_size">(Province)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::select('province_id',
                  App\Models\Basic\Province::orderbyRaw('CONVERT(PROVINCE_NAME USING tis620)')->pluck('PROVINCE_NAME','PROVINCE_ID'),
                   null,
                 ['class' => 'form-control',
                  'id'=>'province',
                  'placeholder' =>'- เลือกจังหวัด -']) !!}
               {!! $errors->first('province_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('amphur_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('amphur_id', 'เขต/อำเภอ'.':'.'<br/><span class=" font_size">(Arnphoe/Khet)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {{-- {!! Form::select('amphur_id',
                 App\Models\Basic\Amphur::orderbyRaw('CONVERT(AMPHUR_NAME USING tis620)')->where('PROVINCE_ID',@$certi_cb->province_id)->pluck('AMPHUR_NAME','AMPHUR_ID'),
                 null,
                ['class' => 'form-control', 'id'=>'amphur',
                    'placeholder' =>'- เลือกอำเภอ -']) !!}
                {!! $errors->first('amphur_id', '<p class="help-block">:message</p>') !!} --}}
                {!! Form::text('amphur_id', null, ['class' => 'form-control', 'required' => 'required','id'=>'amphur_id']) !!}
                {!! $errors->first('amphur_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('district_id ') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('district_id ', 'แขวง/ตำบล'.':'.'<br/><span class=" font_size">(Zip code)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {{-- {!! Form::select('district_id',
                App\Models\Basic\District::orderbyRaw('CONVERT(DISTRICT_NAME USING tis620)')->where('AMPHUR_ID',@$certi_cb->amphur_id)->pluck('DISTRICT_NAME','DISTRICT_ID'),
                null,
               ['class' => 'form-control', 'id'=>'district',
                   'placeholder' =>'-  เลือกแขวง/ตำบล -']) !!}
               {!! $errors->first('district_id', '<p class="help-block">:message</p>') !!} --}}
               {!! Form::text('district_id', null, ['class' => 'form-control', 'required' => 'required','id'=>'district_id']) !!}
               {!! $errors->first('district_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('postcode') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('postcode', ' รหัสไปรษณีย์'.':'.'<br/><span class=" font_size">(Tambon/Khwaeng)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('postcode', null, ['class' => 'form-control', 'required' => 'required','id'=>'postcode']) !!}
                {!! $errors->first('postcode', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_tel', 'โทรศัพท์'.':'.'<br/><span class=" font_size">(Telephone)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('tel', null, ['class' => 'form-control', 'required' => 'required','id'=>'tel']) !!}
                {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('tel_fax') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('fax', 'โทรสาร'.':'.'<br/><span class=" font_size">(Fax)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('tel_fax', null, ['class' => 'form-control','id'=>'tel_fax']) !!}
                {!! $errors->first('tel_fax', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="col-md-12"><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <label for="">ที่ตั้งหน่วยงาน EN</label> </div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_address_no_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_address_no_eng', ' เลขที่'.':'.'<br/><span class=" font_size">(Address)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_address_no_eng', null, ['class' => 'form-control', 'id'=>'cb_address_no_eng']) !!}
                {!! $errors->first('cb_address_no_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_moo_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_moo_eng', 'หมู่ที่'.':'.'<br/><span class=" font_size">(Mool)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_moo_eng', null, ['class' => 'form-control','id'=>'cb_moo_eng']) !!}
                {!! $errors->first('cb_moo_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_soi_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_soi_eng', 'ตรอก/ซอย'.':'.'<br/><span class=" font_size">(Trok/Sol)</span>', ['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_soi_eng', null, ['class' => 'form-control','id'=>'cb_soi_eng']) !!}
                {!! $errors->first('cb_soi_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_street_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_street_eng', 'ถนน'.':'.'<br/><span class=" font_size">(Street/Road)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_street_eng', null, ['class' => 'form-control','id'=>'cb_street_eng']) !!}
                {!! $errors->first('cb_street_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_province_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_city', 'จังหวัด'.':'.'<br/><span class=" font_size">(Province)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::select('cb_province_eng',
                  App\Models\Basic\Province::orderbyRaw('CONVERT(PROVINCE_NAME_EN USING tis620)')->pluck('PROVINCE_NAME_EN','PROVINCE_ID'),
                   null,
                 ['class' => 'form-control',
                  'id'=>'cb_province_eng',
                  'placeholder' =>'- เลือกจังหวัด -']) !!}
               {!! $errors->first('cb_province_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_amphur_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_amphur_eng', 'เขต/อำเภอ'.':'.'<br/><span class=" font_size">(Arnphoe/Khet)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_amphur_eng', null, ['class' => 'form-control', 'id'=>'cb_amphur_eng']) !!}
                {!! $errors->first('cb_amphur_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_district_eng ') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_district_eng ', 'แขวง/ตำบล'.':'.'<br/><span class=" font_size">(Zip code)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
               {!! Form::text('cb_district_eng', null, ['class' => 'form-control', 'id'=>'cb_district_eng']) !!}
               {!! $errors->first('cb_district_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_postcode_eng') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_postcode_eng', ' รหัสไปรษณีย์'.':'.'<br/><span class=" font_size">(Tambon/Khwaeng)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_postcode_eng', null, ['class' => 'form-control', 'id'=>'cb_postcode_eng']) !!}
                {!! $errors->first('cb_postcode_eng', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_latitude') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_latitude', 'พิกัดที่ตั้ง (ละติจูด)'.':'.'<br/><span class=" font_size">(latitude)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_latitude', null, ['class' => 'form-control', 'id'=>'cb_latitude']) !!}
                {!! $errors->first('cb_latitude', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cb_longitude') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cb_longitude', 'พิกัดที่ตั้ง (ลองจิจูด)'.':'.'<br/><span class=" font_size">(longitude)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('cb_longitude', null, ['class' => 'form-control','id'=>'cb_longitude']) !!}
                {!! $errors->first('cb_longitude', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


    <div class="col-md-12"><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <label for="">ข้อมูลสำหรับการติดต่อ (Contact information)</label> </div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('contactor_name') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('contact', ' ชื่อบุคคลที่ติดต่อ'.':'.'<br/><span class=" font_size">(Contact Person)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('contactor_name', null, ['class' => 'form-control', 'required' => 'required','id'=>'contactor_name']) !!}
                {!! $errors->first('contactor_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_tel', ' Email'.':',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('email', $tis_data->agent_email  ?? null, ['class' => 'form-control','required'=>"required","placeholder"=>"Email@gmail.com",'id'=>"address_email",'readonly'=>true]) !!}
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('contact_tel') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('contact_mobile', ' โทรศัพท์ผู้ติดต่อ'.':'.'<br/><span class=" font_size">(Telephone)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('contact_tel', null, ['class' => 'form-control','required'=>"required",'id'=>'contact_tel']) !!}
                {!! $errors->first('contact_tel', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('telephone', 'โทรศัพท์มือถือ'.':'.'<br/><span class=" font_size">(Mobile)</span>',['class' => 'col-md-5 control-label label-height'])) !!}
            <div class="col-md-7">
                {!! Form::text('telephone', $tis_data->trader_mobile  ?? '-' , ['class' => 'form-control','id'=>"telephone",'readonly'=>true]) !!}
                {{-- <p style="color: red;">กรณีที่ต้องการเปลี่ยน e-mail และ เบอร์โทรศัพท์มือถือ กรุณาติดต่อเจ้าหน้าที่</p> --}}
                {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function () {
        $('.check-readonly').prop('disabled', true);//checkbox ความคิดเห็น
         $('.check-readonly').parent().removeClass('disabled');
         $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});

    });
</script>

@endpush

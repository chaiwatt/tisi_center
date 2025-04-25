@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@php
    $config = HP::getConfig();
@endphp

<div class="col-md-12">
    <div class="white-box">

        <h3 class="box-title m-b-0">เพิ่มข้อมูลผู้ใช้งาน</h3>
        <p class="text-muted m-b-30 font-13">เพิ่มข้อมูลผู้ประกอบการ (SSO)</p>

        {!! Form::hidden('jform[juristic_status]', null, [ 'class' => '', 'id' => 'juristic_status' ] ) !!}
        {!! Form::hidden('jform[check_api]', null, [ 'class' => '', 'id' => 'check_api' ] ) !!}
        <div class="form-group {{ $errors->has('trader_type') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('jform[applicanttype_id]', 'ประเภทการลงทะเบียน'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3   control-label'])) !!}
            <div class="col-md-9" >
                @foreach (HP::applicant_types() as $key => $applicant_type)
                            {!! Form::radio('jform[applicanttype_id]', $key,($key== 1 ? true :false), ['id' => 'applicanttype_id'.$key,'class'=>'check applicanttype_id', 'data-radio'=>'iradio_square-green']); !!}
                            <label for="applicanttype_id{{ $key }}">{{ $applicant_type }}</label>
                @endforeach

            </div>
        </div>

        <div class="form-group {{ $errors->has('person_type') ? 'has-error' : ''}}">
            {{-- {!! HTML::decode(Form::label('jform[person_type]', 'ประเภทบุคคล'.' : <span class="text-danger">*</span>',  ['class' => 'col-md-3   control-label'])) !!} --}}
            {!! HTML::decode(Form::label(' ',  ' ',  ['class' => 'col-md-3   control-label'])) !!}
            <div class="col-md-3" >
                {!! Form::select('jform[person_type]',
                                ['1'=>'เลขประจำตัวผู้เสียภาษี','2'=>'เลขที่หนังสือเดินทาง','3'=>'เลขทะเบียนธุรกิจคนต่างด้าว'],
                                null,
                                ['class' => 'form-control', 'id'=>'person_type',
                                'placeholder' =>'- เลือกประเภทข้อมูลที่ใช้ลงทะเบียน -',
                                'required'=> true])
                !!}
            </div>
            <div class="col-md-4" >
                {!! Form::text('jform[tax_number]', null, ['class' => 'form-control check_format_en_and_number','id'=>'tax_number','required'=> true , 'maxlength' => '13','placeholder'=>'เลขนิติบุคคล']) !!}
                {!! $errors->first('jform[tax_number]', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-2" >
                <button type="button" id="search" class="btn btn-primary"> ค้นหา </button>
            </div>
        </div>
<div class="div_profile">

        <div class="form-group {{ $errors->has('jform[date_birthday]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('jform[date_birthday]', ' <span id="span_date_birthday">วันที่จดทะเบียน</span>'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-3" >
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! Form::text('jform[date_birthday]', null, ['class' => 'form-control datepicker','id'=>'date_of_birth','placeholder'=>'วันที่จดทะเบียน','required'=> true]) !!}
                    {!! $errors->first('jform[date_birthday]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group {{ $errors->has('jform[prefix_name]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('jform[prefix_name]', 'ชื่อผู้ประกอบการ'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-3 div_legal_entity" >  <!-- นิติบุคคล    -->
                {!! Form::select('jform[prefix_name]',
                ['1'=>'บริษัทจำกัด','2'=>'บริษัทมหาชนจำกัด','3'=>'ห้างหุ้นส่วนจำกัด','4'=>'ห้างหุ้นส่วนสามัญนิติบุคคล'],
                null,
                ['class' => 'form-control', 'id'=>'prefix_name',
               'placeholder' =>'- เลือกประเภทการทะเบียน -',
               'required'=> true
               ]) !!}
            </div>
            <div class="col-md-6 div_legal_entity" >   <!-- นิติบุคคล    -->
                {!! Form::text('jform[name]', null, ['class' => 'form-control','id'=>'name','placeholder'=>'เช่น บริษัท XXX จำกัด','required'=> true]) !!}
                {!! $errors->first('jform[name]', '<p class="help-block">:message</p>') !!}
            </div>

            <div class="col-md-3 div_natural_person" >   <!-- บุคคลธรรมดา     -->
                {!! Form::select('jform[person_prefix_name]',
                App\Models\Basic\Prefix::where('state',1)->pluck('initial', 'id')->all(),
                null,
                ['class' => 'form-control',
                'id'=>'person_prefix_name',
                'placeholder' =>'- เลือกคำนำหน้าชื่อ -',
                'required'=> true]) !!}
            </div>
            <div class="col-md-3 div_natural_person" >   <!-- บุคคลธรรมดา     -->
                {!! Form::text('jform[person_first_name]', null, ['class' => 'form-control','id'=>'person_first_name','placeholder'=>'ชื่อ','required'=> false]) !!}
                {!! $errors->first('jform[person_first_name]', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-3 div_natural_person" >   <!-- บุคคลธรรมดา     -->
                {!! Form::text('jform[person_last_name]', null, ['class' => 'form-control','id'=>'person_last_name','placeholder'=>'นามสกุล','required'=> false]) !!}
                {!! $errors->first('jform[person_last_name]', '<p class="help-block">:message</p>') !!}
            </div>


            <div class="col-md-9 div_natural_faculty" >   <!-- คณะบุคคล    -->
                {!! Form::text('jform[faculty_name]', null, ['class' => 'form-control','id'=>'faculty_name','placeholder'=>'ชื่อคณะบุคคล','required'=> false]) !!}
                {!! $errors->first('jform[faculty_name]', '<p class="help-block">:message</p>') !!}
            </div>

            <div class="col-md-9 div_natural_service" >   <!-- ส่วนราชการ     -->
                {!! Form::text('jform[service_name]', null, ['class' => 'form-control','id'=>'service_name','placeholder'=>'ชื่อส่วนราชการ','required'=> false]) !!}
                {!! $errors->first('jform[service_name]', '<p class="help-block">:message</p>') !!}
            </div>

            <div class="col-md-9 div_natural_another" >   <!-- อื่นๆ     -->
                {!! Form::text('jform[another_name]', null, ['class' => 'form-control','id'=>'another_name','placeholder'=>'ชื่ออื่นๆ','required'=> false]) !!}
                {!! $errors->first('jform[another_name]', '<p class="help-block">:message</p>') !!}
            </div>

        </div>

<div id="div_branch_type">
        <div class="form-group {{ $errors->has('jform[branch_type]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('jform[branch_type]', 'ประเภทสาขา'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label branch_code'])) !!}
            <div class="col-md-4" >
                {!! Form::radio('jform[branch_type]', '1', true, ['class'=>'check branch_type', 'data-radio'=>'iradio_square-blue','id'=>'branch_type1']) !!}
                <label for="branch_type1">&nbsp;สำนักงานใหญ่&nbsp;&nbsp;</label>
                {!! Form::radio('jform[branch_type]', '2', false, ['class'=>'check branch_type', 'data-radio'=>'iradio_square-blue','id'=>'branch_type2']) !!}
                <label for="branch_type2">&nbsp;สาขา&nbsp;&nbsp;</label>
            </div>
        </div>

        <div class="form-group div_branch_code{{ $errors->has('jform[branch_code]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('jform[branch_code]', 'รหัสสาขา'.' : <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label branch_code'])) !!}
            <div class="col-md-4" >
                {!! Form::text('jform[branch_code]', null, ['class' => 'form-control check_format_en','id'=>'branch_code','placeholder'=>'รหัสสาขา','required'=> false]) !!}
                {!! $errors->first('jform[branch_code]', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <legend><h4 id="label_address_no"> ที่ตั้งสำนักงานใหญ่</h4></legend>

        {{--  start  ที่ตั้งสำนักงานใหญ่ --}}
        <div class="form-group {{ $errors->has('jform[address_no]') ? 'has-error' : ''}}">
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('jform[address_no]', 'เลขที่'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('jform[address_no]', null, ['class' => 'form-control','id'=>'address_no','required'=> true]) !!}
                    {!! $errors->first('jform[address_no]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('jform[building]', 'อาคาร/หมู่บ้าน', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('jform[building]', null, ['class' => 'form-control','id'=>'building']) !!}
                    {!! $errors->first('jform[building]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('jform[soi]', 'ตรอก/ซอย', ['class' => 'col-md-12 '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('jform[soi]', null, ['class' => 'form-control','id'=>'soi']) !!}
                    {!! $errors->first('jform[soi]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('jform[moo]') ? 'has-error' : ''}}">
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[moo]', 'หมู่', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[moo]', null, ['class' => 'form-control','id'=>'moo']) !!}
                          {!! $errors->first('jform[moo]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[street]', 'ถนน', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[street]', null, ['class' => 'form-control','id'=>'street','required'=> false]) !!}
                          {!! $errors->first('jform[street]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>

        </div>
        <div class="form-group {{ $errors->has('address_search') ? 'has-error' : ''}}">
            <div class="col-md-8">
                {!! Form::label('address_search', 'ค้นหา', ['class' => 'col-md-12']) !!}
                <div class="col-md-12 ">
                    {!! Form::text('address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'address_search' ]) !!}
                </div>
            </div>
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('jform[subdistrict]', 'แขวง/ตำบล'.' <span class="text-danger">*</span>', ['class' => 'col-md-12 '])) !!}
                <div class="col-md-12 " >
                        {!! Form::text('jform[subdistrict]', null, ['class' => 'form-control','id'=>'subdistrict','required'=> true,'readonly'=>true]) !!}
                        {!! $errors->first('jform[subdistrict]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('jform[district]') ? 'has-error' : ''}}">
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[district]', 'เขต/อำเภอ'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[district]', null, ['class' => 'form-control','id'=>'district','required'=> true,'readonly'=>true]) !!}
                          {!! $errors->first('jform[district]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[province]', 'จังหวัด'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[province]', null, ['class' => 'form-control','id'=>'province','required'=> true,'readonly'=>true]) !!}
                          {!! $errors->first('jform[province]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-2" >
                    {!! HTML::decode(Form::label('jform[zipcode]', 'รหัสไปรษณีย์'.' <span class="text-danger">*</span>', ['class' => 'col-md-12 '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[zipcode]', null, ['class' => 'form-control ','id'=>'zipcode','required'=> true]) !!}
                          {!! $errors->first('jform[zipcode]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
        </div>
        <div class="form-group {{ $errors->has('jform[latitude]') ? 'has-error' : ''}}">
            <div class="col-md-4" >
                  {!! HTML::decode(Form::label('jform[latitude]', 'พิกัดที่ตั้ง (ละติจูด)'.'  <span class="text-danger">*</span>', ['class' => 'label_latitude col-md-12'])) !!}
                  <div class="col-md-12 " >
                        {!! Form::text('jform[latitude]', null, ['class' => 'form-control input_number','id'=>'latitude','required'=> true]) !!}
                        {!! $errors->first('jform[latitude]', '<p class="help-block">:message</p>') !!}
                  </div>
            </div>
            <div class="col-md-4" >
                  {!! HTML::decode(Form::label('jform[longitude]', 'พิกัดที่ตั้ง (ลองจิจูด)'.'  <span class="text-danger">*</span>', ['class' => 'label_longitude col-md-12'])) !!}
                  <div class="col-md-12 " >
                        {!! Form::text('jform[longitude]', null, ['class' => 'form-control input_number','id'=>'longitude','required'=> true]) !!}
                        {!! $errors->first('jform[longitude]', '<p class="help-block">:message</p>') !!}
                  </div>
            </div>
            <div class="col-md-4" >
                  {!! HTML::decode(Form::label(' ', "&nbsp;", ['class' => 'col-md-12 '])) !!}
                  <div class="col-md-12 " >
                            <a class="btn btn-default" id="show_map" onclick="return false">
                                ค้นหาจากแผนที่
                            </a>
                  </div>
            </div>
      </div>
        {{--  end  ที่ตั้งสำนักงานใหญ่ --}}

        <br>
        <legend><h4 > ที่อยู่ที่สามารถติดต่อได้</h4></legend>
        {{--  start  ที่อยู่ที่สามารถติดต่อได้ --}}
        <div class="form-group">
              <div class="col-md-12">
                  <div class="checkbox checkbox-success p-t-0">
                      <input   type="checkbox" id="checkbox_contact_address_no">
                            <label for="checkbox_contact_address_no" > &nbsp;&nbsp;<span class="checkbox_contact_address_no">ที่เดียวกับที่ตั้งสำนักงานใหญ่</span>
                      </label>
                  </div>
              </div>
        </div>
        <div class="form-group {{ $errors->has('jform[contact_address_no]') ? 'has-error' : ''}}">
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_address_no]', 'เลขที่'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_address_no]', null, ['class' => 'form-control','id'=>'contact_address_no','required'=> true]) !!}
                          {!! $errors->first('jform[contact_address_no]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_building]', 'อาคาร/หมู่บ้าน', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_building]', null, ['class' => 'form-control','id'=>'contact_building']) !!}
                          {!! $errors->first('jform[contact_building]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_soi]', 'ตรอก/ซอย', ['class' => 'col-md-12 '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_soi]', null, ['class' => 'form-control','id'=>'contact_soi']) !!}
                          {!! $errors->first('jform[contact_soi]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
        </div>
        <div class="form-group {{ $errors->has('jform[contact_moo]') ? 'has-error' : ''}}">
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_moo]', 'หมู่', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_moo]', null, ['class' => 'form-control','id'=>'contact_moo']) !!}
                          {!! $errors->first('jform[contact_moo]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_street]', 'ถนน', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_street]', null, ['class' => 'form-control','id'=>'contact_street','required'=> false]) !!}
                          {!! $errors->first('jform[contact_street]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
        </div>
        <div class="form-group {{ $errors->has('contact_address_search') ? 'has-error' : ''}}">
            <div class="col-md-8">
                {!! Form::label('contact_address_search', 'ค้นหา', ['class' => 'col-md-12']) !!}
                <div class="col-md-12 ">
                    {!! Form::text('contact_address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'contact_address_search' ]) !!}
                </div>
            </div>
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('jform[contact_subdistrict]', 'แขวง/ตำบล'.' <span class="text-danger">*</span>', ['class' => 'col-md-12 '])) !!}
                <div class="col-md-12 " >
                        {!! Form::text('jform[contact_subdistrict]', null, ['class' => 'form-control','id'=>'contact_subdistrict','required'=> true,'readonly'=>true]) !!}
                        {!! $errors->first('jform[contact_subdistrict]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="form-group {{ $errors->has('jform[contact_district]') ? 'has-error' : ''}}">
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_district]', 'เขต/อำเภอ'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_district]', null, ['class' => 'form-control','id'=>'contact_district','required'=> true,'readonly'=>true]) !!}
                          {!! $errors->first('jform[contact_district]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-4" >
                    {!! HTML::decode(Form::label('jform[contact_province]', 'จังหวัด'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_province]', null, ['class' => 'form-control','id'=>'contact_province','required'=> true,'readonly'=>true]) !!}
                          {!! $errors->first('jform[contact_province]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
              <div class="col-md-2" >
                    {!! HTML::decode(Form::label('jform[contact_zipcode]', 'รหัสไปรษณีย์'.' <span class="text-danger">*</span>', ['class' => 'col-md-12 '])) !!}
                    <div class="col-md-12 " >
                          {!! Form::text('jform[contact_zipcode]', null, ['class' => 'form-control ','id'=>'contact_zipcode','required'=> true]) !!}
                          {!! $errors->first('jform[contact_zipcode]', '<p class="help-block">:message</p>') !!}
                    </div>
              </div>
        </div>
        {{-- end  ที่อยู่ที่สามารถติดต่อได้ --}}

        <div class="row">
            <div class="col-sm-12">
                <legend><h4>ข้อมูลผู้ติดต่อ</h4></legend>
                {{--  start  ข้อมูลผู้ติดต่อ --}}
                <div class="form-group {{ $errors->has('jform[contact_tax_id]') ? 'has-error' : ''}}">
                    <div class="col-md-3" >
                        {!! HTML::decode(Form::label('jform[contact_tax_id]', 'เลขบัตรประจำตัวประชาชน'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                              {!! Form::text('jform[contact_tax_id]', null, ['class' => 'form-control tax_id_format','id'=>'contact_tax_id','required'=> true]) !!}
                              {!! $errors->first('jform[contact_tax_id]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-3" >
                        {!! HTML::decode(Form::label('jform[contact_prefix_name]', 'ชื่อผู้ติดต่อ'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                              {!! Form::select('jform[contact_prefix_name]',
                              App\Models\Basic\Prefix::where('state',1)->pluck('initial', 'id')->all(),
                              null,
                              ['class' => 'form-control', 'id'=>'contact_prefix_name',
                             'placeholder' =>'- เลือกคำนำหน้าชื่อ -',
                             'required'=> true]) !!}
                        </div>
                    </div>
                    <div class="col-md-3" >
                            {!! HTML::decode(Form::label('', '&nbsp;', ['class' => 'col-md-12 '])) !!}
                            <div class="col-md-12 " >
                                  {!! Form::text('jform[contact_first_name]', null, ['class' => 'form-control','id'=>'first_name','placeholder'=>'ชื่อ','required'=> true]) !!}
                                  {!! $errors->first('jform[contact_first_name]', '<p class="help-block">:message</p>') !!}
                            </div>
                    </div>
                    <div class="col-md-3" >
                            {!! HTML::decode(Form::label('', '&nbsp;', ['class' => 'col-md-12 '])) !!}
                            <div class="col-md-12 " >
                                  {!! Form::text('jform[contact_last_name]', null, ['class' => 'form-control','id'=>'last_name','placeholder'=>'นามสกุล','required'=> true]) !!}
                                  {!! $errors->first('jform[contact_last_name]', '<p class="help-block">:message</p>') !!}
                            </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('jform[contact_tel]') ? 'has-error' : ''}}">
                    <div class="col-md-4" >
                        {!! HTML::decode(Form::label('jform[contact_position]', 'ตำแหน่ง', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                            {!! Form::text('jform[contact_position]', null, ['class' => 'form-control','id'=>'contact_position','required'=> false]) !!}
                            {!! $errors->first('jform[contact_position]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4" >
                        {!! HTML::decode(Form::label('jform[contact_tel]', 'เบอร์โทรศัพท์', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                            {!! Form::text('jform[contact_tel]', null, ['class' => 'form-control ','id'=>'tel','required'=> false]) !!}
                            {!! $errors->first('jform[contact_tel]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4" >
                        {!! HTML::decode(Form::label('jform[contact_phone_number]', 'เบอร์โทรศัพท์มือถือ'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                            {!! Form::text('jform[contact_phone_number]', null, ['class' => 'form-control phone_number_format','id'=>'phone_number','required'=> true]) !!}
                            {!! $errors->first('jform[contact_phone_number]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('jform[email]') ? 'has-error' : ''}}">
                    <div class="col-md-4" >
                        {!! HTML::decode(Form::label('jform[contact_fax]', 'โทรสาร', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                            {!! Form::text('jform[contact_fax]', null, ['class' => 'form-control','id'=>'fax','required'=> false]) !!}
                            {!! $errors->first('jform[contact_fax]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-6" >
                        {!! HTML::decode(Form::label('jform[email]', 'e-Mail'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                        <div class="col-md-12 " >
                            {!! Form::email('jform[email]', null, ['class' => 'form-control','id'=>'email','placeholder'=>'ระบุ E-mail ที่ใช้งานได้จริง เพื่อรับข่าวสารจาก สมอ.','required'=> true]) !!}
                            {!! $errors->first('jform[email]', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('jform[personfile]') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('jform[personfile]', 'เอกสารแนบการยืนยันตัวตน'.' : <span class="text-danger">*</span>', ['class' => 'label_personfile col-md-3 control-label'])) !!}
                    <div class="col-md-6" >
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="personfile"  id="personfile" required>
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists delete_personfile"  data-dismiss="fileinput">ลบ</a>
                        </div>

                        {!! $errors->first('activity_file', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                {{-- end  ข้อมูลผู้ติดต่อ --}}

            </div>
        </div>


    </div>
</div>


</div>

    </div>
</div>




<div class="col-md-6 div_profile">
    <div class="white-box">
        <h3 class="box-title m-b-0">เปลี่ยนรหัสผ่าน</h3>
        <p class="text-muted m-b-30 font-13"> ถ้าไม่เปลียนปล่อยว่างไว้ </p>

        <div class="form-group">
            <label class="control-label col-sm-5">ชื่อผู้ใช้งาน:</label>
            <div class="col-md-7">
                  <p class="form-control-static username" id="p_username"> {{ isset($user) ? $user->username : '' }} </p>
                  {!! Form::hidden('jform[username]', null, ['class' => 'form-control ','id'=>'username','placeholder'=>'','readonly'=> true]) !!}
            </div>
        </div>

        <div class="form-group required">
            {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-5 control-label']) !!}
            <div class="col-sm-7">
                {!! Form::password('password', ['class' => 'form-control','required'=> true]) !!}
                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="white-box">
        <h3 class="box-title m-b-0">ลงชื่อเข้าใช้งาน 2 ขั้นตอน</h3>
        <p class="text-muted m-b-30 font-13">Google Authenticator แก้ไขจากเปิดเป็นปิดเท่านั้น</p>

        <div class="form-group">
            <label class="control-label col-sm-7">การใช้งาน ลงชื่อเข้าใช้งาน 2 ขั้นตอน :</label>
            <div class="col-md-5">
                    {!! Form::checkbox('jform[google2fa_status]', 1, true, ['class' => 'google2fa_checkbox','id'=>'google2fa_checkbox', 'data-color' => '#13dafe']) !!}
            </div>
        </div>

    </div>

</div>

<div class="col-md-6 div_profile">

    <div class="col-md-12">
        <div class="white-box">
            <h3 class="box-title m-b-0">กลุ่มผู้ใช้งาน</h3>
            <p class="text-muted m-b-30 font-13"> จัดการกลุ่มผู้ใช้งาน </p>

            <div class="form-group">
                {!! Form::label('roles', ' ', ['class' => 'col-sm-1 control-label']) !!}
                <div class="col-sm-11">

                @foreach ($roles as $role)
                    @if($role->label!='trader')
                        @continue
                    @endif
                    <div class="checkbox checkbox-success">
                        {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $trader_roles), ['class' => 'form-control roles']) !!}
                        <label for="roles">&nbsp;{{ $role->name }}</label>
                    </div>
                @endforeach

                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <div class="modal-body">
                <style>
                    .controls {
                        margin-top: 10px;
                        border: 1px solid transparent;
                        border-radius: 2px 0 0 2px;
                        box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        height: 32px;
                        outline: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                    }

                    #pac-input {
                        background-color: #fff;
                        font-size: 15px;
                        font-weight: 300;
                        margin-left: 12px;
                        padding: 0 11px 0 13px;
                        text-overflow: ellipsis;
                        width: 300px;
                    }

                    #pac-input:focus {
                        border-color: #4d90fe;
                    }

                </style>

                <input id="pac-input" class="controls"  type="text" placeholder="Search Box">
                <div id="map" style="height: 400px;"></div>
                <input id="lat1" class="controls" type="text"   placeholder="ละติจูด" disabled>
                <input id="lng1" class="controls" type="text"  placeholder="ลองติจูด" disabled>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success"  id="button-modal-default">
                     <span aria-hidden="true">ยืนยัน</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('user-sso'))
            <a class="btn btn-default" href="{{url('/sso/user-sso')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkwr5rmzY9btU08sQlU9N0qfmo8YmE91Y&libraries=places&callback=initAutocomplete"   async defer></script>
<script>
    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.
    var markers = [];
    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 13.7563309, lng: 100.50176510000006},
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });
        markers = new google.maps.Marker({
            position: {lat: 13.7563309, lng: 100.50176510000006},
            map: map,
        });

        google.maps.event.addListener(map, 'click', function (event) {
            markers.setMap(null);

            markers = new google.maps.Marker({
                position: { lat: event.latLng.lat(), lng: event.latLng.lng() },
                map: map,
            });

            $('#lat1').val(event.latLng.lat());
            $('#lng1').val(event.latLng.lng());
        });

        searchBox.addListener('places_changed', function () {
            markers.setMap(null);
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                 $('#lat1').val(place.geometry.location.lat());
                 $('#lng1').val(place.geometry.location.lng());

                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers = new google.maps.Marker({
                    position: { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
                    map: map,
                });

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    // bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

    }
</script>
  <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{ asset('plugins/components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{asset('js/mask/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/components/icheck/icheck.min.js')}}"></script>
<script src="{{asset('plugins/components/icheck/icheck.init.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {
            applicanttype();

            $('form').submit(function() {
                $(this).find("button[type='submit']").prop('disabled', true);
            });

            $('#show_map').click(function(){
                $('#modal-default').modal('show');
            });
            $('#button-modal-default').click(function(){
                if( $('#lat1').val() != ""){
                    $('#latitude').val( $('#lat1').val());
                }else{
                    $('#latitude').val('');
                }
                if( $('#lng1').val() != ""){
                    $('#longitude').val( $('#lng1').val());
                }else{
                    $('#longitude').val('');
                }
                $('#modal-default').modal('hide');
            });



                   // เช็ค e-mail
                   $("#email").change(function(event) {
                     var  email = $(this).val();
                     if(checkNone(email)){
                         $.ajax({
                                url: "{!! url('sso/check_email') !!}",
                                method:"POST",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    email:email
                                    },
                                success:function (result){
                                    if(result.check == true){  // เช็ค E-Mail  ในระบบ
                                        Swal.fire({
                                                    title: result.status,
                                                    width: 1000,
                                                    showDenyButton: true,
                                                    showCancelButton: false,
                                                    confirmButtonText: 'OK'
                                                 });
                                        $('#email').val('');
                                    }else   if(result.check_email == false){   // เช็ครูปแบบ E-Mail
                                        Swal.fire({
                                                    title: result.status_email,
                                                    width: 1000,
                                                    showDenyButton: true,
                                                    showCancelButton: false,
                                                    confirmButtonText: 'OK'
                                                 });
                                        $('#email').val('');
                                    }
                                }
                         });
                    }
                });
                $("#branch_code").change(function(event) {
                     var  branch_code = $(this).val();
                     if(checkNone(branch_code)){
                        $.ajax({
                                url: "{!! url('sso/check_branch_code') !!}",
                                method:"POST",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    branch_code:branch_code,
                                    tax_number:$('#tax_number').val()
                                    },
                                success:function (result){
                                    if(result.check == true){
                                        Swal.fire({
                                                    title: result.status,
                                                    width: 1000,
                                                    showDenyButton: true,
                                                    showCancelButton: false,
                                                    confirmButtonText: 'OK'
                                                 });
                                        $('#branch_code').val('');
                                    }
                                }
                         });

                    }
                });



                    // เช็ค password
                $("#password").change(function(event) {
                     var  password = $(this).val();
                     if(checkNone(password)){
                        var html =   check_password_and_number(password);
                        if(html != ''){
                            Swal.fire({
                                title:'กรุณากรอกรูปแบบรหัสผ่านใหม่และความยาวไม่น้อยกว่า 8 อักษร',
                                html:html,
                                width: 700,
                                showDenyButton: true,
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            });
                            $('#password').val('');
                        }
                    }
                });


                 // เช็ค เบอร์โทรศัพท์มือถือ
                $("#phone_number").change(function(event) {
                     var  phone_number = $(this).val();
                     if(checkNone(phone_number)){
                        phone_number = phone_number.toString().replace(/\D/g,'');
                        if(phone_number.length < 10){
                            Swal.fire({
                                        title: 'กรุณากรอกเบอร์โทรศัพท์มือถือให้ครบ 10 หลัก',
                                        width: 400,
                                        showDenyButton: true,
                                        showCancelButton: false,
                                        confirmButtonText: 'OK'
                                      });
                            $('#phone_number').val('');
                        }
                     }
                });


            // ข้อมูลผู้ติดต่อ
            $("#contact_tax_id").keyup(function(event) {
                var tax_id        = $('#contact_tax_id').val() ;
                var applicanttype_id =  $('.applicanttype_id:checked').val();
                if(applicanttype_id != 2){
                    if(tax_id != ""){
                        tax_id = tax_id.toString().replace(/\D/g,'');
                        if(tax_id.length >= 13){
                            $.ajax({
                                url: "{!! url('sso/datatype') !!}",
                                method:"POST",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    applicanttype_id:'2',
                                    tax_id:tax_id
                                    },
                                success:function (result){
                                    if(checkNone(result.name) && result.length != 0){
                                        $('#first_name').val(result.name);
                                        $('#last_name').val(result.name_last);
                                        $('#contact_prefix_name').val(result.prefix_id).select2();
                                    }else{
                                        $('#first_name').val('');
                                        $('#last_name').val('');
                                        $('#contact_prefix_name').val('').select2();
                                    }
                                }
                            });
                    }else{
                                        $('#first_name').val('');
                                        $('#last_name').val('');
                                        $('#contact_prefix_name').val('').select2();
                    }
                    }else{
                                        $('#first_name').val('');
                                        $('#last_name').val('');
                                        $('#contact_prefix_name').val('').select2();
                    }
                 }
             });



            $('#checkbox_contact_address_no').click(function(){
                checkbox_contact_address_no();
            });




            // ข้อมูลผู้ติดต่อ
            $("#tax_number,#branch_code").keyup(function(event) {

                        var tax_number        = $('#tax_number').val() ;
                        $('#username').val('');
                    if(tax_number != ""){
                        tax_number              = tax_number.toString().replace(/\D/g,'');
                        var applicanttype_id    =  $('.applicanttype_id:checked').val();
                        var branch_type         =  $('.branch_type:checked').val();
                        var branch_code         =  $('#branch_code').val();
                        if(branch_type == "2"){
                            //$('#username').val(tax_number+branch_code);
                            //$('#p_username').html(tax_number+branch_code);
                        }else{
                            $('#username').val(tax_number);
                            $('#p_username').html(tax_number);
                        }
                    }
             });


            $(".check_format_en_and_number").on("keypress",function(e){
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
            $('.div_profile').hide();
            $('.tax_id_format').inputmask('9-9999-99999-99-9');
            $('.phone_number_format').inputmask('999-999-9999');




            $("#search").click(function () {
                  data_value_null();
                  var row               = $(this).val() ;
                  var applicanttype_id  =  $('.applicanttype_id:checked').val();
                  const cars            = ["","นิติบุคคล", "บุคคลธรรมดา", "คณะบุคคล", "ส่วนราชการ", "อื่นๆ"];
                  var tax_number        = $('#tax_number').val() ;
     if(tax_number != ""){

    if(applicanttype_id == 1 || applicanttype_id == 2  || applicanttype_id == 3  || applicanttype_id == 4 ){ //  นิติบุคคล     บุคคลธรรมดา     คณะบุคคล     ส่วนราชการ
                    tax_number = tax_number.toString().replace(/\D/g,'');
                   if(tax_number.length >= 13){
                                // Text
                                $.LoadingOverlay("show", {
                                        image       : "",
                                        text        : "กำลังโหลด..."
                                });
                                if(applicanttype_id == 4){ // ส่วนราชการ
                                    $.ajax({
                                        url: "{!! url('sso/get_taxid') !!}",
                                        method:"POST",
                                        data:{
                                            _token: "{{ csrf_token() }}",
                                            tax_id:tax_number,
                                            applicanttype_id:applicanttype_id
                                            },
                                        success:function (result){

                                            $.LoadingOverlay("hide");
                                            if(result.check == true){
                                                   Swal.fire({
                                                        title: 'ขออภัยเลข '+ tax_number +' ขึ้นทะเบียนในระบบประเภท'+ result.applicant_type + ' <br>ต้องการลงทะเบียนเป็นสาขาหรือไม่',
                                                        width: 800,
                                                        showDenyButton: true,
                                                        showCancelButton: true,
                                                        confirmButtonText: 'ใช่',
                                                        cancelButtonText: 'ยกเลิก',
                                                        denyButtonText: 'ไม่',
                                                    }).then((result) => {

                                                        if (result.value===true) {//ใช่
                                                            $('.applicanttype_id[value="4"]').prop('checked', true);
                                                            $('.applicanttype_id').iCheck('update');
                                                            $('.branch_type[value="2"]').prop('checked', true);
                                                            $('.branch_type').iCheck('update');
                                                            $('#person_type').children('option[value!=""]').remove();
                                                            $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                                                            $('#person_type').val('1');
                                                            $('#person_type').select2();
                                                            //$('#p_username').html('<i>สร้างให้อัตโนมัติหลังบันทึก</i>');//Username
                                                            get_next_username_branch();
                                                            data_pid(tax_number);
                                                        }else if(result.value===false){//ไม่

                                                        }else{//ยกเลิก
                                                            location = '{{ url('sso/user-sso') }}';
                                                        }
                                                    });

                                            }else if(result.check_api == true  ){

                                                    if(result.type == 1){ //นิติบุคคล
                                                                Swal.fire({
                                                                    title: result.status,
                                                                    showDenyButton: true,
                                                                    showCancelButton: true,
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
                                                                        } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                            window.location.assign("{{ url('sso/user-sso') }}");
                                                                        }
                                                                });
                                                    }else   if(result.type == 2){  // บุคคลธรรมดา
                                                                    if(result.person == 1 ){
                                                                        Swal.fire({
                                                                            title: result.status,
                                                                            width: 1500,
                                                                            showDenyButton: true,
                                                                            showCancelButton: true,
                                                                            confirmButtonText: 'กลับ',
                                                                            cancelButtonText: 'ยกเลิก',
                                                                        }).then((result) => {

                                                                                if (result.value) {
                                                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                                                }
                                                                        });

                                                                    }else{
                                                                      Swal.fire({
                                                                                title: result.status,
                                                                                showDenyButton: true,
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
                                                                            } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                                window.location.assign("{{ url('sso/user-sso') }}");
                                                                            }
                                                                      });
                                                                    }
                                                       }else   if(result.type == 3){  // คณะบุคคล
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
                                                                    } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                                    }
                                                            });

                                                       }
                                            }else{

                                                        var title = 'เลขส่วนราชการ '+ tax_number + ' ไม่มีข้อมูลการลงทะเบียน ท่านต้องการลงทะเบียนหรือไม่?';
                                                        Swal.fire({
                                                            title: title,
                                                            showDenyButton: true,
                                                            showCancelButton: true,
                                                            width: 1500,
                                                            confirmButtonText: 'ยืนยัน',
                                                            cancelButtonText: 'ยกเลิก',
                                                        }).then((result) => {
                                                                if (result.value) {
                                                                    $('#check_api').val('');
                                                                    $('#personfile').prop('required', true);
                                                                    $('.label_personfile').find('span').html('*');
                                                                    $('.branch_type[value="1"]').prop('checked', true);
                                                                    $('.branch_type').iCheck('update');
                                                                    $('#branch_type1').prop('disabled', false);
                                                                    data_pid(tax_number);
                                                                } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                    window.location.assign("{{ url('sso/user-sso') }}");
                                                                }
                                                        });
                                            }
                                        }
                                    });

                        }else if(applicanttype_id == 3){ // คณะบุคคล
                            $.ajax({
                                    url: "{!! url('sso/get_legal_faculty') !!}",
                                    method:"POST",
                                    data:{
                                        _token: "{{ csrf_token() }}",
                                        tax_id:tax_number
                                        },
                                    success:function (result){
                                        $.LoadingOverlay("hide");
                                                if(result.check == true  ){ //  เลขคณะบุคคลมีข้อมูลการลงทะเบียน
                                                    Swal.fire({
                                                        title: 'ขออภัยเลข '+ tax_number +' ขึ้นทะเบียนในระบบประเภท'+ result.applicant_type  ,
                                                        width: 800,
                                                        showDenyButton: true,
                                                        showCancelButton: true,
                                                        confirmButtonText: 'กลับ',
                                                        denyButtonText: 'ไม่',
                                                        cancelButtonText: 'ยกเลิก',
                                                    }).then((result) => {

                                                            if (result.value) {
                                                                    window.location.assign("{{ url('sso/user-sso') }}");
                                                            }
                                                    });

                                                }else{ //  เลขคณะบุคคลมีข้อมูลไม่มีการลงทะเบียน
                                                        
                                                        var faculty_title_allow  = '{{ $config->faculty_title_allow }}';
                                                        var faculty_title_allows = faculty_title_allow.split(',');
                                                        
                                                        if(faculty_title_allows.indexOf(result.branch_title) !== -1){  //ลงทะเบียนคณะบุคคลมีสำนักงานใหญ่
                                                            Swal.fire({
                                                                title: 'เลขคณะบุคคล '+ tax_number + ' ไม่มีข้อมูลการลงทะเบียน ท่านต้องการลงทะเบียนหรือไม่?',
                                                                showDenyButton: true,
                                                                showCancelButton: true,
                                                                width: 1500,
                                                                confirmButtonText: 'ยืนยัน',
                                                                cancelButtonText: 'ยกเลิก',
                                                            }).then((result) => {
                                                                    if (result.value) {
                                                                        $('.branch_type[value="1"]').prop('checked', true);
                                                                        $('.branch_type').iCheck('update');
                                                                        $('#branch_type1').prop('disabled', false);
                                                                        data_pid(tax_number);
                                                                    } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                                    }
                                                            });
                                                        }else{
                                                            $('.div_profile').hide();
                                                            $('#div_cancel').show();
                                                            $('#div_sign_up').hide();
                                                            Swal.fire('ขออภัยเลข'+ tax_number +' ไม่ใช่เลขคณะบุคคล');
                                                        }
                                                }
                                    }
                            });


                        }else if(applicanttype_id == 2){ // บุคคลธรรมดา
                             $.LoadingOverlay("hide");
                            $.ajax({
                                    url: "{!! url('sso/get_tax_number') !!}",
                                    method:"POST",
                                    data:{
                                        _token: "{{ csrf_token() }}",
                                        tax_id:tax_number,
                                        },
                                    success:function (result){

                                        if((result.check == true && result.person != true) ||  result.person != true){
                                            Swal.fire({
                                                title: result.person,
                                                width: 800,
                                                showDenyButton: true,
                                                showCancelButton: true,
                                                confirmButtonText: 'กลับ',
                                                cancelButtonText: 'ยกเลิก',
                                            }).then((result) => {

                                                    if (result.value) {
                                                            window.location.assign("{{ url('sso/user-sso') }}");
                                                    }
                                            });
                                        }else if(result.check == true  ){
                                            Swal.fire({
                                                title: 'ขออภัยเลข '+ tax_number + ' มีการลงทะเบียนแล้ว ท่านต้องการกลับหน้าจัดการผู้ประกอบการ หรือไม่?',
                                                width: 700,
                                                showDenyButton: true,
                                                showCancelButton: true,
                                                confirmButtonText: 'กลับ',
                                                denyButtonText: 'ไม่',
                                                cancelButtonText: 'ยกเลิก',
                                            }).then((result) => {

                                                    if (result.value) {
                                                            window.location.assign("{{ url('sso/user-sso') }}");
                                                    }
                                            });

                                        }else{
                                            data_pid(tax_number);
                                        }

                                    }
                            });
                            $('.branch_type[value="1"]').prop('checked', true);
                            $('.branch_type').iCheck('update');
                            $('#branch_type1').prop('disabled', false);
                        }else   if(applicanttype_id == 1){  // นิติบุคคล
                            $.ajax({
                                    url: "{!! url('sso/get_legal_entity') !!}",
                                    method:"POST",
                                    data:{
                                        _token: "{{ csrf_token() }}",
                                        tax_id:tax_number
                                        },
                                    success:function (result){
                                         $.LoadingOverlay("hide");
                                        if(result.juristic_status != false){
                                                if(result.check == true  ){ //  เลขนิติบุคคลมีข้อมูลการลงทะเบียน
                                                        if(result.juristic_status == 1 || result.juristic_status == 2 || result.juristic_status == 3){
                                                            Swal.fire({
                                                                title: 'เลขนิติบุคคล '+ tax_number + ' มีการลงทะเบียนสำนักงานใหญ่แล้วจะไม่สามารถเลือกประเภทสาขา: สำนักงานใหญ่',
                                                                showDenyButton: true,
                                                                showCancelButton: true,
                                                                width: 1500,
                                                                confirmButtonText: 'ยืนยัน',
                                                                cancelButtonText: 'ยกเลิก',
                                                            }).then((result) => {
                                                                    /* Read more about isConfirmed, isDenied below */
                                                                    if (result.value) {
                                                                        $('.branch_type[value="2"]').prop('checked', true);
                                                                        $('.branch_type').iCheck('update');
                                                                        $('#branch_type1').prop('disabled', true);
                                                                        $('#branch_type1').parent().removeClass('disabled');
                                                                        $('#branch_type1').parent().css('margin-top', '8px');
                                                                        data_pid(tax_number);
                                                                    } else if (  result.dismiss === Swal.DismissReason.cancel ) {
                                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                                    }
                                                            });
                                                        }else{
                                                            $('.div_profile').hide();
                                                            $('#div_cancel').show();
                                                            $('#div_sign_up').hide();
                                                            Swal.fire('ขออภัยเลขนิติบุคคล '+ tax_number +' สถานะ'+ result.juristic_status );
                                                        }

                                                }else{ //  เลขนิติบุคคลมีข้อมูลไม่มีการลงทะเบียน

                                                        if(result.juristic_status == 1 || result.juristic_status == 2 || result.juristic_status == 3){
                                                            Swal.fire({
                                                                title: 'เลขนิติบุคคล '+ tax_number + ' ไม่มีข้อมูลการลงทะเบียน ท่านต้องการลงทะเบียนหรือไม่?',
                                                                showDenyButton: true,
                                                                showCancelButton: true,
                                                                width: 1500,
                                                                confirmButtonText: 'ยืนยัน',
                                                                cancelButtonText: 'ยกเลิก',
                                                            }).then((result) => {
                                                                    /* Read more about isConfirmed, isDenied below */
                                                                    if (result.value) {
                                                                        $('.branch_type[value="1"]').prop('checked', true);
                                                                        $('.branch_type').iCheck('update');
                                                                        $('#branch_type1').prop('disabled', false);
                                                                        data_pid(tax_number);
                                                                    } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                                    }
                                                            });
                                                        }else{
                                                            $('.div_profile').hide();
                                                            $('#div_cancel').show();
                                                            $('#div_sign_up').hide();
                                                            Swal.fire('ขออภัยเลขนิติบุคคล '+ tax_number +' สถานะ'+ result.juristic_status );

                                                        }



                                                }
                                        }else{
                                            $('.div_profile').hide();
                                            $('#div_cancel').show();
                                            $('#div_sign_up').hide();
                                            Swal.fire('ขออภัยไม่พบเลขนิติบุคคล '+ tax_number );

                                        }
                                    }
                            });


                        }

                    }else{
                        data_value_null();
                            setinputreadonly(false);

                            Swal.fire({
                                position: 'center',
                                width: 600,
                                title: 'กรุณากรอกเลข'+cars[applicanttype_id]+'ให้ครบ!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                    }

}else{  //   อื่นๆ
            // Text
            $.LoadingOverlay("show", {
                    image       : "",
                    text        : "กำลังโหลด..."
            });
            $.ajax({
                url: "{!! url('sso/get_taxid') !!}",
                method:"POST",
                data:{
                    _token: "{{ csrf_token() }}",
                    tax_id:tax_number,
                    applicanttype_id:applicanttype_id
                    },
                success:function (result){
                    $.LoadingOverlay("hide");

                    if(result.check == true){//มีข้อมูลในระบบแล้ว

                        if(result.applicant_type=='อื่นๆ'){//ถ้าเป็นประเภทอื่นๆ ตรงกับที่เลือก

                            var title = 'เลขอื่นๆ '+ tax_number + ' มีการลงทะเบียนแล้ว ต้องการลงทะเบียนเป็นสาขาหรือไม่?';
                            Swal.fire({
                                title: title,
                                showDenyButton: true,
                                showCancelButton: true,
                                width: 1500,
                                confirmButtonText: 'ยืนยัน',
                                cancelButtonText: 'ยกเลิก',
                            }).then((result) => {
                                if (result.value) {
                                    $('#check_api').val('');
                                    $('#personfile').prop('required', true);
                                    $('.label_personfile').find('span').html('*');

                                    $('.branch_type[value="2"]').prop('checked', true);
                                    $('.branch_type').iCheck('update');
                                    $('#branch_type1').prop('disabled', true);
                                    $('#branch_type2').prop('disabled', false);
                                    data_pid(tax_number);
                                    get_next_username_branch();
                                } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                    window.location.assign("{{ url('sso/user-sso') }}");
                                }
                            });

                        }else {
                            Swal.fire({
                                title: 'ขออภัยเลข '+ tax_number +' ขึ้นทะเบียนในระบบประเภท'+ result.applicant_type  ,
                                width: 800,
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'กลับ',
                                cancelButtonText: 'ยกเลิก',
                            }).then((result) => {
                                if (result.value) {
                                    window.location.assign("{{ url('sso/user-sso') }}");
                                }
                            });
                        }

                    }else if(result.check_api == true  ){

                            if(result.type == 1){ //นิติบุคคล
                                        Swal.fire({
                                            title: result.status,
                                            showDenyButton: true,
                                            showCancelButton: true,
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
                                                } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                    window.location.assign("{{ url('sso/user-sso') }}");
                                                }
                                        });
                            }else   if(result.type == 2){  // บุคคลธรรมดา
                                            if(result.person == 1 ){
                                                Swal.fire({
                                                    title: result.status,
                                                    width: 1500,
                                                    showDenyButton: true,
                                                    showCancelButton: true,
                                                    confirmButtonText: 'กลับ',
                                                    cancelButtonText: 'ยกเลิก',
                                                }).then((result) => {

                                                        if (result.value) {
                                                                window.location.assign("{{ url('sso/user-sso') }}");
                                                        }
                                                });

                                            }else{
                                                Swal.fire({
                                                        title: result.status,
                                                        showDenyButton: true,
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
                                                    } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                        window.location.assign("{{ url('sso/user-sso') }}");
                                                    }
                                                });
                                            }
                                }else   if(result.type == 3){  // คณะบุคคล
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
                                            } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                                window.location.assign("{{ url('sso/user-sso') }}");
                                            }
                                    });

                                }
                    }else{

                                var title = 'เลขอื่นๆ '+ tax_number + ' ไม่มีข้อมูลการลงทะเบียน ท่านต้องการลงทะเบียนหรือไม่?';
                                Swal.fire({
                                    title: title,
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    width: 1500,
                                    confirmButtonText: 'ยืนยัน',
                                    cancelButtonText: 'ยกเลิก',
                                }).then((result) => {
                                        if (result.value) {
                                            $('#check_api').val('');
                                            $('#personfile').prop('required', true);
                                            $('.label_personfile').find('span').html('*');
                                            $('.branch_type[value="1"]').prop('checked', true);
                                            $('.branch_type').iCheck('update');
                                            $('#branch_type1').prop('disabled', false);
                                            data_pid(tax_number);
                                                var check_ID = checkID(tax_number);
                                                if(check_ID === false){
                                                    $(".tax_id_format").inputmask();
                                                }   
                                        } else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                            window.location.assign("{{ url('sso/user-sso') }}");
                                        }
                                });
                    }
                }
            });



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
                    applicanttype();
                    setinputreadonly(false);
                    data_value_null();
                    $('#tax_number').val('');
                    $('#tax_number').prop('readonly', false);
                    $('#person_type').prop('readonly',false);
                  var applicanttype_id =  $('.applicanttype_id:checked').val();
                  if(applicanttype_id == 1){
                      $('#person_type').val('1');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขนิติบุคคล');
                      $('#tax_number').attr('maxlength', '13');
                  }else    if(applicanttype_id == 2){
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขประจำตัวประชาชน');
                      $('#tax_number').attr('maxlength', '13');
                   }else    if(applicanttype_id == 3){
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขคณะบุคคล');
                      $('#tax_number').attr('maxlength', '13');
                   }else    if(applicanttype_id == 4){
                    $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขส่วนราชการ');
                      $('#tax_number').attr('maxlength', '13');
                    }else    if(applicanttype_id == 5){
                       $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').append('<option value="2">เลขที่หนังสือเดินทาง</option>');
                      $('#person_type').append('<option value="3">เลขทะเบียนธุรกิจคนต่างด้าว</option>');
                    //   $('#person_type').val('');
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขอื่นๆ');
                      $('#tax_number').attr('maxlength', '30');
                  }else{
                      $('#person_type').children('option[value!=""]').remove();
                      $('#person_type').children('option[value="2"]').remove();
                      $('#person_type').children('option[value="3"]').remove();
                      $('#person_type').append('<option value="1">เลขประจำตัวผู้เสียภาษี</option>');
                      $('#person_type').val('1');
                      $('#person_type').select2();
                      $('#tax_number').attr('placeholder', 'เลขประจำตัวประชาชน');
                      $('#tax_number').attr('maxlength', '13');
                  }
            });



            // Date Picker Thai
            $('.datepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            $('.google2fa_checkbox').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            //ที่เดียวกับที่ตั้งสำนักงานใหญ่
            $('#checkbox_head_address_no').change(function(event) {
                if($(this).prop('checked')){
                    $('#contact_address_no').val($('#address_no').val());
                    $('#contact_building').val($('#building').val());
                    $('#contact_soi').val($('#soi').val());
                    $('#contact_moo').val($('#moo').val());
                    $('#contact_street').val($('#street').val());
                    $('#contact_subdistrict').val($('#subdistrict').val());
                    $('#contact_district').val($('#district').val());
                    $('#contact_province').val($('#province').val());
                    $('#contact_zipcode').val($('#zipcode').val());
                }
            });


        $("#address_search").select2({
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

        $("#address_search").on('change', function () {
            $.ajax({
                url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val() + '?khet=1'
            }).done(function( jsondata ) {

                console.log(jsondata);
                
                if(jsondata != ''){

                    $('#subdistrict').val(jsondata.sub_title);
                    $('#district').val(jsondata.dis_title);
                    $('#province').val(jsondata.pro_title);
                    $('#zipcode').val(jsondata.zip_code);

                    $("#address_search").select2('val','');

                }
            });
        });

        $("#contact_address_search").select2({
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

        $("#contact_address_search").on('change', function () {
            $.ajax({
                url: "{!! url('/funtions/get-addreess/') !!}" + "/" + $(this).val() + '?khet=1'
            }).done(function( jsondata ) {
                if(jsondata != ''){

                    $('#contact_subdistrict').val(jsondata.sub_title);
                    $('#contact_district').val(jsondata.dis_title);
                    $('#contact_province').val(jsondata.pro_title);
                    $('#contact_zipcode').val(jsondata.zip_code);

                    $("#contact_address_search").select2('val','');

                }
            });
        });

        });

        // เช็คความถูกต้องเลข 13 หลัก
        function checkID(id){
            if(id.substring(0,1)== 0) return false;
            if(id.length != 13) return false;
            for(i=0, sum=0; i < 12; i++)
            sum += parseFloat(id.charAt(i))*(13-i);
            if((11-sum%11)%10!=parseFloat(id.charAt(12))) return false;
            return true;
        }

                  // 1.	การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก
                  function data_pid(tax_number) {
                  // Text
                  $.LoadingOverlay("show", {
                        image       : "",
                        text        : "กำลังโหลด..."
                  });
                  var applicanttype_id =  $('.applicanttype_id:checked').val();
         if(applicanttype_id == 1 || applicanttype_id == 2 || applicanttype_id == 3){

                  $.ajax({
                    url: "{!! url('sso/datatype') !!}",
                    method:"POST",
                    data:{
                          _token: "{{ csrf_token() }}",
                          applicanttype_id:applicanttype_id,
                          tax_id:tax_number
                        },
                    success:function (result){
                          $.LoadingOverlay("hide");
                          $('#tax_number').prop('readonly', true);
                          $('#check_api').val('1');
                          $('#personfile').prop('required', false);
                          $('.label_personfile').find('span').html('');
                            console.log(result);
                       if(checkNone(result.name) && result.length != 0){

                        if(applicanttype_id == 1 && (result.juristic_status != 1  &&  result.juristic_status != 2)  ){
                                Swal.fire({
                                        position: 'center',
                                            icon: 'success',
                                            title:  'เลขนิติบุคคล '+ tax_number + ' ไม่สามารถลงทะเบียนได้ ' + result.juristic_status,
                                            showConfirmButton: false,
                                            width: 1500,
                                            timer: 3000
                                });

                        }else{
                               $('.div_profile').show();
                               $('#div_cancel').hide();
                               $('#div_sign_up').show();
                               $('#username').val(tax_number);
                               $('#p_username').html(tax_number);
                               $('#date_of_birth').val(result.RegisterDate);
                              if(applicanttype_id == 1){  //นิติบุคคล
                                    $('#prefix_name').val(result.prefix_id).select2();
                                    $('#name').val(result.name);
                                    $('#contact_tax_id').val('');
                                    $('#first_name').val('');
                                    $('#last_name').val('');
                                    $('#contact_prefix_name').val('').select2();
                                    $('#juristic_status').val(result.juristic_status);
                                    get_next_username_branch();

                              }else   if(applicanttype_id == 2){ //บุคคลธรรมดา
                                    $('#person_first_name').val(result.name);
                                    $('#person_last_name').val(result.name_last);
                                    $('#person_prefix_name').val(result.prefix_id).select2();

                                    $('#contact_tax_id').val(tax_number);
                                    $('#first_name').val(result.name);
                                    $('#last_name').val(result.name_last);
                                    $('#contact_prefix_name').val(result.prefix_id).select2();
                                    $('#juristic_status').val('');
                            }else   if(applicanttype_id == 3){ //คณะบุคคล
                                    $('#name').val('');
                                    $('#prefix_text').val('');
                                    $('#contact_tax_id').val('');
                                    $('#first_name').val('');
                                    $('#last_name').val('');
                                    $('#faculty_name').val(result.name);

                                    $('#contact_prefix_name').val('').select2();
                                    $('#juristic_status').val('');
                                    get_next_username_branch();
                              }else{
                                    $('#contact_tax_id').val('');
                                    $('#first_name').val('');
                                    $('#last_name').val('');
                                    $('#contact_prefix_name').val('').select2();
                                    $('#juristic_status').val('');
                                    get_next_username_branch();
                              }

                              $('#address_no').val(result.address);
                              $('#soi').val(result.soi);
                              if(result.moo != 0){
                                $('#moo').val(result.moo);
                              }else{
                                $('#moo').val('');
                              }


                              $('#street').val(result.road);
                              $('#subdistrict').val(result.tumbol);
                              $('#district').val(result.ampur);
                              $('#province').val(result.province);
                              $('#zipcode').val(result.zipcode);
                              // $('#country_code').val(result.country_code);

                              $('#email').val(result.email);
                                contact_readonly(true);//ไม่ให้แก้ไขที่อยู่ที่ดึงมา
                              setinputreadonly(true);
                            }
                        }else{
                            $('.div_profile').hide();
                            const cars = ["","นิติบุคคล", "บุคคลธรรมดา", "คณะบุคคล", "ส่วนราชการ", "อื่นๆ"];
                           	Swal.fire({
                                        position: 'center',
                                        title: 'ไม่พบข้อมูล'+cars[applicanttype_id],
                                        showConfirmButton: false,
                                        timer: 1500
					                });
                              data_value_null();
                              setinputreadonly(false);
                        }
                      }
                   });
                }else{

                    $.LoadingOverlay("hide");
                    $('#tax_number').prop('readonly', true);
                    $('#check_api').val('');
                    $('#personfile').prop('required', true);
                    $('.label_personfile').find('span').html('*');
                    $('.div_profile').show();
                    $('#div_cancel').hide();
                    $('#div_sign_up').show();

                    if($('.branch_type[value="1"]').prop('checked')){//สำนักงานใหญ่
                        $('#username').val(tax_number);
                        $('#p_username').html(tax_number);
                    }
                    setinputreadonly(false);
                    contact_readonly(false);//ให้แก้ไขที่อยู่ที่ไม่ได้ดึงมา
                }

                applicanttype();
                branch_type();



            }
        function applicanttype() {
                  if($('.applicanttype_id:checked').val() == 1){
                        $('.div_legal_entity').show(); //  นิติบุคคล
                        $('.div_natural_person').hide(); //  บุคคลธรรมดา
                        $('.div_natural_faculty').hide();  //   คณะบุคคล
                        $('.div_natural_service').hide(); //  ส่วนราชการ
                        $('.div_natural_another').hide();  //  อื่นๆ

                        $('#prefix_name').prop('required', true); //  นิติบุคคล
                        $('#name').prop('required', true);  //  นิติบุคคล
                        $('#person_prefix_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_first_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_last_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#faculty_name').prop('required', false);  //   คณะบุคคล
                        $('#service_name').prop('required', false);  //  ส่วนราชการ
                        $('#another_name').prop('required', false);  //  อื่นๆ

                        $('#span_date_birthday').html('วันที่จดทะเบียน');
                        $('#date_of_birth').attr('วันที่จดทะเบียน');
                        $('#label_address_no').html('ที่ตั้งสำนักงานใหญ่');
                        $('.checkbox_contact_address_no').html('ที่เดียวกับที่ตั้งสำนักงานใหญ่');

                         $('#div_branch_type').show(); // ประเภทสาขา


                        $('.label_latitude').html('พิกัดที่ตั้ง (ลองจิจูด) <span class="text-danger">*</span>');
                        $('.label_longitude').html('พิกัดที่ตั้ง (ละติจูด) <span class="text-danger">*</span>');
                        $('#latitude').prop('required', true);
                        $('#longitude').prop('required', true);

                  }else    if($('.applicanttype_id:checked').val() == 2){
                        $('.div_legal_entity').hide(); //  นิติบุคคล
                        $('.div_natural_person').show(); //  บุคคลธรรมดา
                        $('.div_natural_faculty').hide();  //   คณะบุคคล
                        $('.div_natural_service').hide(); //  ส่วนราชการ
                        $('.div_natural_another').hide();  //  อื่นๆ

                        $('#prefix_name').prop('required',false); //  นิติบุคคล
                        $('#name').prop('required', false);  //  นิติบุคคล
                        $('#person_prefix_name').prop('required', true);  //  บุคคลธรรมดา
                        $('#person_first_name').prop('required', true);  //  บุคคลธรรมดา
                        $('#person_last_name').prop('required', true);  //  บุคคลธรรมดา
                        $('#faculty_name').prop('required', false);  //   คณะบุคคล
                        $('#service_name').prop('required', false);  //  ส่วนราชการ
                        $('#another_name').prop('required', false);  //  อื่นๆ

                        $('#span_date_birthday').html('วันเกิด');
                        $('#date_of_birth').attr('วันเกิด');
                        $('#label_address_no').html('ที่อยู่ตามทะเบียนบ้าน');
                        $('.checkbox_contact_address_no').html('ที่เดียวกับที่อยู่ตามทะเบียนบ้าน');


                        $('#div_branch_type').hide(); // ประเภทสาขา

                        $('.label_latitude').html('พิกัดที่ตั้ง (ละติจูด)');
                        $('.label_longitude').html('พิกัดที่ตั้ง (ลองจิจูด)');
                        $('#latitude').prop('required', false);
                        $('#longitude').prop('required', false);

                    }else    if($('.applicanttype_id:checked').val() == 3){

                        $('.div_legal_entity').hide(); //  นิติบุคคล
                        $('.div_natural_person').hide(); //  บุคคลธรรมดา
                        $('.div_natural_faculty').show();  //   คณะบุคคล
                        $('.div_natural_service').hide(); //  ส่วนราชการ
                        $('.div_natural_another').hide();  //  อื่นๆ

                        $('#prefix_name').prop('required',false); //  นิติบุคคล
                        $('#name').prop('required', false);  //  นิติบุคคล
                        $('#person_prefix_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_first_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_last_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#faculty_name').prop('required', true);  //   คณะบุคคล
                        $('#service_name').prop('required', false);  //  ส่วนราชการ
                        $('#another_name').prop('required', false);  //  อื่นๆ

                        $('#span_date_birthday').html('วันที่จดทะเบียน');
                        $('#date_of_birth').attr('วันที่จดทะเบียน');
                        $('#label_address_no').html('ที่ตั้งคณะบุคคล');
                        $('.checkbox_contact_address_no').html('ที่เดียวกับที่ตั้งคณะบุคคล');

                        $('#div_branch_type').hide(); // ประเภทสาขา

                        $('.label_latitude').html('พิกัดที่ตั้ง (ละติจูด)');
                        $('.label_longitude').html('พิกัดที่ตั้ง (ลองจิจูด)');
                        $('#latitude').prop('required', false);
                        $('#longitude').prop('required', false);

                     }else    if($('.applicanttype_id:checked').val() == 4){

                        $('.div_legal_entity').hide(); //  นิติบุคคล
                        $('.div_natural_person').hide(); //  บุคคลธรรมดา
                        $('.div_natural_faculty').hide();  //   คณะบุคคล
                        $('.div_natural_service').show(); //  ส่วนราชการ
                        $('.div_natural_another').hide();  //  อื่นๆ

                        $('#prefix_name').prop('required',false); //  นิติบุคคล
                        $('#name').prop('required', false);  //  นิติบุคคล
                        $('#person_prefix_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_first_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_last_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#faculty_name').prop('required', false);  //   คณะบุคคล
                        $('#service_name').prop('required', true);  //  ส่วนราชการ
                        $('#another_name').prop('required', false);  //  อื่นๆ

                        $('#span_date_birthday').html('วันที่จดทะเบียน');
                        $('#date_of_birth').attr('วันที่จดทะเบียน');
                        $('#label_address_no').html('ที่อยู่/ที่ตั้งส่วนราชการ');
                        $('.checkbox_contact_address_no').html('ที่เดียวกับที่อยู่/ที่ตั้งส่วนราชการ');

                        $('#div_branch_type').show(); // ประเภทสาขา

                        $('.label_latitude').html('พิกัดที่ตั้ง (ละติจูด)');
                        $('.label_longitude').html('พิกัดที่ตั้ง (ลองจิจูด)');
                        $('#latitude').prop('required', false);
                        $('#longitude').prop('required', false);


                    }else    if($('.applicanttype_id:checked').val() == 5){

                        $('.div_legal_entity').hide(); //  นิติบุคคล
                        $('.div_natural_person').hide(); //  บุคคลธรรมดา
                        $('.div_natural_faculty').hide();  //   คณะบุคคล
                        $('.div_natural_service').hide(); //  ส่วนราชการ
                        $('.div_natural_another').show();  //  อื่นๆ

                        $('#prefix_name').prop('required',false); //  นิติบุคคล
                        $('#name').prop('required', false);  //  นิติบุคคล
                        $('#person_prefix_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_first_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#person_last_name').prop('required', false);  //  บุคคลธรรมดา
                        $('#faculty_name').prop('required', false);  //   คณะบุคคล
                        $('#service_name').prop('required', false);  //  ส่วนราชการ
                        $('#another_name').prop('required', true);  //  อื่นๆ

                        $('#span_date_birthday').html('วันที่จดทะเบียน/วันเกิด');
                        $('#date_of_birth').attr('วันที่จดทะเบียน/วันเกิด');
                        $('#label_address_no').html('ที่อยู่/ที่ตั้งอื่นๆ');
                        $('.checkbox_contact_address_no').html('ที่เดียวกับที่อยู่/ที่ตั้งอื่นๆ');

                        $('#div_branch_type').show(); // ประเภทสาขา

                        $('.label_latitude').html('พิกัดที่ตั้ง (ละติจูด)');
                        $('.label_longitude').html('พิกัดที่ตั้ง (ลองจิจูด)');
                        $('#latitude').prop('required', false);
                        $('#longitude').prop('required', false);

                  }
                //    data_value_null();

            }

        function contact_readonly(readonly){
            if($('#address_no').val()!=''){
                $('#address_no').prop('readonly', readonly);
            }else{
                $('#address_no').prop('readonly', false);
            }
            $('#building, #soi, #moo, #street').prop('readonly', readonly);
            $('#address_search').select2('enable', !readonly);
            
        }

        function setinputreadonly(value) {
                var applicanttype_id =     $('.applicanttype_id:checked').val();
                if(applicanttype_id == 1 || applicanttype_id == 2){
                    $('#person_type').prop('readonly',value);
                    $('#date_of_birth').prop('readonly',value);
                    if(value === true){
                            $('#date_of_birth').datepicker('remove');
                    }else{
                            $('#date_of_birth').datepicker('update');
                            $('.datepicker').datepicker({
                                autoclose: true,
                                toggleActive: true,
                                todayHighlight: true,
                                language:'th-th',
                                format: 'dd/mm/yyyy'
                            });
                    }

                    $('#name').prop('readonly',value);
                    if($('#prefix_name').val() == ""){
                            $('#prefix_name').prop('readonly',false);
                    }else{
                            $('#prefix_name').prop('readonly',value);
                    }
                    $('#prefix_name').select2();


                    if($('#person_prefix_name').val() == ""){
                            $('#person_prefix_name').prop('readonly',false);
                    }else{
                            $('#person_prefix_name').prop('readonly',value);
                    }
                    $('#person_prefix_name').select2();
                    $('#person_first_name').prop('readonly',value);
                    $('#person_last_name').prop('readonly',value);
                }else if(applicanttype_id == 3){
                     $('#date_of_birth').datepicker('remove');
                     $('#date_of_birth').prop('readonly',true);
                     $('#faculty_name').prop('readonly',true);
                }else if(applicanttype_id == 4){
                    $('#date_of_birth').prop('readonly',false);
                     $('#date_of_birth').datepicker('update');
                     $('.datepicker').datepicker({
                        autoclose: true,
                        toggleActive: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                     });
                     $('#service_name').prop('readonly',false);
                }else if(applicanttype_id == 5){
                      $('#date_of_birth').prop('readonly',false);
                      $('#date_of_birth').datepicker('update');
                      $('.datepicker').datepicker({
                        autoclose: true,
                        toggleActive: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                     });
                     $('#another_name').prop('readonly',false);
                }
            }

            function data_value_null() {
                        $('#date_of_birth').val('');
                        $('#branch_code').val('');

                        $('#prefix_name').val('').select2();
                        $('#name').val('');

                        $('#person_prefix_name').val('').select2();
                        $('#person_first_name').val('');
                        $('#person_last_name').val('');


                        $('#faculty_name').val('');

                        $('#service_name').val('');
                        $('#another_name').val('');

                        $('#address_no').val('');
                        $('#building').val('');
                        $('#soi').val('');
                        $('#moo').val('');
                        $('#street').val('');
                        $('#subdistrict').val('');
                        $('#district').val('');
                        $('#province').val('');
                        $('#zipcode').val('');
                        $('#latitude').val('');
                         $('#longitude').val('');

                        // $('#country_code').val('');

                        $('#checkbox_contact_address_no').prop('checked', false);
                        $('#contact_address_no').val('');
                        $('#contact_building').val('');
                        $('#contact_soi').val('');
                        $('#contact_moo').val('');
                        $('#contact_street').val('');
                        $('#contact_subdistrict').val('');
                        $('#contact_district').val('');
                        $('#contact_province').val('');
                        $('#contact_zipcode').val('');
                        // $('#contact_country_code').val('');



                        $('#contact_tax_id').val('');
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#contact_prefix_name').val('').select2();
                        $('#juristic_status').val('');
                        $('#check_api').val('');
                        $('#personfile').prop('required', true);
                        $('.label_personfile').find('span').html('*');

                        $('.div_profile').hide();
                        // $('#tax_number').val('');
                        $('#password2').val('');
                        $('#password').val('');
                        $('#username').val('');
                        $('#p_username').html('');
                        $('.delete_personfile').click();
                        $('#div_cancel').show();
                        $('#div_sign_up').hide();
                        $('#contact_prefix_name').val('').select2();
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#contact_position').val('');
                        $('#tel').val('');
                        $('#phone_number').val('');
                        $('#fax').val('');
                        $('#email').val('');
                        $('#checkbox-signup').prop('checked', false);
                        $('.roles').prop('checked', false);
                        $('#google2fa_checkbox').prop('checked', true);
              }
              function checkbox_contact_address_no() {
                  if($('#checkbox_contact_address_no').is(':checked')){
                        $('#contact_address_no').val($('#address_no').val());
                        $('#contact_building').val($('#building').val());
                        $('#contact_soi').val($('#soi').val());
                        $('#contact_moo').val($('#moo').val());
                        $('#contact_street').val($('#street').val());
                        $('#contact_subdistrict').val($('#subdistrict').val());
                        $('#contact_district').val($('#district').val());
                        $('#contact_province').val($('#province').val());
                        $('#contact_zipcode').val($('#zipcode').val());
                        // $('#contact_country_code').val($('#country_code').val());
                  } else {
                        $('#contact_address_no').val('');
                        $('#contact_building').val('');
                        $('#contact_soi').val('');
                        $('#contact_moo').val('');
                        $('#contact_street').val('');
                        $('#contact_subdistrict').val('');
                        $('#contact_district').val('');
                        $('#contact_province').val('');
                        $('#contact_zipcode').val('');
                        // $('#contact_country_code').val('');
                 }
            }

            // รหัสสาขา
            function branch_type() {
                var row =  $('.branch_type:checked').val();
                if(row == 2){
                    $('.div_branch_code').show();
                    //$('#branch_code').prop('required', true);
                }else{
                    $('.div_branch_code').hide();
                    //$('#branch_code').prop('required', false);
                }
            }

            //ชื่อผู้ใช้งานของสาขา
            function get_next_username_branch(){
                let tax_number  = $('#tax_number').val();
                let branch_type = $('input[type="radio"][name*="branch_type"]:checked').val();
                if(checkNone(tax_number) && branch_type==2){//เลขประจำตัวผู้เสียภาษี และเป็นสาขา
                    $.ajax({
                        url: "{!! url('sso/get_next_username_branch') !!}",
                        method: "POST",
                        data:{
                            _token: "{{ csrf_token() }}",
                            tax_number: tax_number
                        },
                        success: function (result){
                            if(result.hasOwnProperty('username')){
                                $('#p_username').html(result.username);
                            }
                        }
                    });
                }
            }

            function checkNone(value) {
                return value !== '' && value !== null && value !== undefined;
            }

            function checkPassword(value) {
                if(value.match(/[A-Z]/g) != null  && value.match(/[a-z]/g) != null && value.match(/[0-9]/g) != null){
                    console.log('false');
                    return false ;

                }else{
                    console.log('true');
                    return true;

                }

            }

            function check_password_and_number(value) {
                    var html = '';
                    var  password = value.toString();
                if(value.match(/[A-Z]/g) == null  || value.match(/[a-z]/g) == null || value.match(/[0-9]/g) == null){
                    if(value.match(/[A-Z]/g) == null){
                        html += '<p>-อักษรภาษาอังกฤษตัวพิมพ์ใหญ่ อย่างหน่อย 1 ตัว</p>';
                    }
                    if(value.match(/[a-z]/g) == null){
                        html += '<p>-อักษรภาษาอังกฤษตัวพิมพ์เล็ก อย่างหน่อย 1 ตัว</p>';
                    }
                    if(value.match(/[0-9]/g) == null){
                        html += '<p>-ตัวเลข  อย่างหน่อย 1 ตัว</p>';
                    }
                }
                   if(password.length < 8){
                        html += '<p>-คุณกรอกรหัสผ่านได้ '+password.length +' อักษร</p>';
                   }
                   return html ;

            }
            function checkLetterPassword(password) {
                var   password = password.toString();
                return  password.length ;
            }


    </script>
@endpush



<div class="white-box" id="box-readonly">
    <div class="row">
        <div class="col-sm-12">
            <legend><h3 class="box-title">ข้อมูลใบรับรอง (คำขอหลัก@if (@$export_lab->CertiLabTo != null): {{$export_lab->CertiLabTo->app_no}}
                @endif
                )</h3></legend>

            {{-- <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span> ออกใบรับรองฉบับนี้ให้'.':'.'<br/><span class=" font_size">(Applicant)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                    @if(isset($app_no))
                        {!! Form::select('app_certi_lab_id',   $app_no,  !empty($export_lab->app_certi_lab_id) ?$export_lab->app_certi_lab_id:null, ['class' => 'form-control','id' => 'app_certi_lab_id','placeholder'=>'- เลขคำขอ -',  'required' => true]); !!}
                        {!! $errors->first('app_certi_lab_id', '<p class="help-block">:message</p>') !!}
                    @else
                        {!! Form::hidden('app_certi_lab_id', !empty($export_lab->app_certi_lab_id) ?$export_lab->app_certi_lab_id:null, ['class' => 'form-control','id'=>'app_certi_lab_id']) !!}
                        {!! Form::text('title', !empty($export_lab->request_number) ?$export_lab->request_number:null, ['class' => 'form-control','id'=>'title','disabled' => true]) !!}
                        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                    @endif
                    {!! Form::hidden('id', null, ['class' => 'form-control','id'=>'id']) !!}
                </div>
            </div>

            @php
                // dd($export_lab);
            @endphp

            <div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::text('title_en', !empty($export_lab->title_en) ?$export_lab->title_en:null, ['class' => 'form-control','id'=>'title_en','required' => false,'disabled' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                      </div>
                    {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('app_no', !empty($export_lab->request_number) ?$export_lab->request_number:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly' => true]) !!}
                    {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div> --}}
{{-- {{$export_lab->certificate_to}} --}}
            {{-- <div class="form-group {{ $errors->has('certificate') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certificate', '<span class="text-danger">*</span> ใบรับรองเลขที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('certificate', !empty($export_lab->certificate_to) ?$export_lab->certificate_to:null, ['class' => 'form-control','id'=>'certificate','required' => true]) !!}
                    {!! $errors->first('certificate', '<p class="help-block">:message</p>') !!}
                </div>
            </div> --}}

            <div class="form-group {{ $errors->has('lab_name') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('lab_name', '<span class="text-danger">*</span> ชื่อห้องปฏิบัติการ'.':'.'<br/><span class=" font_size">(Name laboratory)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                       <div class="  input-group">
                        {!! Form::text('lab_name',  !empty($export_lab->lab_name) ?$export_lab->lab_name:null, ['class' => 'form-control','id'=>'name_standard','required' =>true]) !!}
                        <span class="input-group-addon bg-secondary "> TH </span>
                      </div>
                    {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('lab_name_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                       <div class="  input-group">
                        {!! Form::text('lab_name_en', !empty($export_lab->lab_name_en) ?$export_lab->lab_name_en:null, ['class' => 'form-control','id'=>'name_standard_en','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                      </div>
                    {!! $errors->first('lab_name_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('radio_address') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('address', 'ที่อยู่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <label  class="col-md-2" >
                    {!! Form::radio('radio_address', '1', true, ['class'=>'check radio_address', 'data-radio'=>'iradio_square-green']) !!}
                        บริษัท
                 </label>
                 <label  class="col-md-2">
                    {!! Form::radio('radio_address', '2', false, ['class'=>'check radio_address check-readonly', 'data-radio'=>'iradio_square-green']) !!}
                        สาขา
                </label>
                <label  class="col-md-2">
                    {!! Form::radio('radio_address', '3', false, ['class'=>'check radio_address check-readonly', 'data-radio'=>'iradio_square-green']) !!}
                      กำหนดเอง
                </label>
            </div>

            <div class="row {{ $errors->has('address_no') ? 'has-error' : ''}}">

                <div class="col-md-6">
                    {!! HTML::decode(Form::label('address_no', ' ตั้งอยู่เลขที่'.':'.'<br/><span class=" font_size">(Address)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address_no', !empty($export_lab->address_no) ?$export_lab->address_no:null, ['class' => 'form-control not-allowed','id'=>'address_no','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('address_no', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address_no_en', !empty($export_lab->address_no_en) ?$export_lab->address_no_en:null, ['class' => 'form-control','id'=>'address_no_en','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                        </div>
                        {!! $errors->first('address_no_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('address_moo', 'หมู่ที่'.':'.'<br/><span class=" font_size">(Mool)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_moo',  !empty($export_lab->address_moo) ?$export_lab->address_moo:null, ['class' => 'form-control','id'=>'allay','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('address_moo', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_moo_en',  !empty($export_lab->address_moo_en) ?$export_lab->address_moo_en:null, ['class' => 'form-control','id'=>'allay_en','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('address_moo_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row {{ $errors->has('address_soi') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    {!! HTML::decode(Form::label('address_soi', ' ตรอก/ซอย'.':'.'<br/><span class=" font_size">(Trok/Sol)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address_soi',  !empty($export_lab->address_soi) ?$export_lab->address_soi:null, ['class' => 'form-control','id'=>'village_no','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('address_soi', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('address_soi_en',  !empty($export_lab->address_soi_en) ?$export_lab->address_soi_en:null, ['class' => 'form-control','id'=>'address_soi_en','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                          </div>
                        {!! $errors->first('address_soi_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('address_road', ' ถนน'.':'.'<br/><span class=" font_size">(Street/Road)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_road', !empty($export_lab->address_road) ?$export_lab->address_road:null, ['class' => 'form-control','id'=>'road','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('address_road', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_road_en', !empty($export_lab->address_road_en) ?$export_lab->address_road_en:null, ['class' => 'form-control','id'=>'address_road_en','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('address_road_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row {{ $errors->has('address_province') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    {!! HTML::decode(Form::label('address_province', '<span class="text-danger">*</span> จังหวัด'.':'.'<br/><span class=" font_size">(Province)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('address_province', !empty($export_lab->address_province) ?$export_lab->address_province:null, ['class' => 'form-control','id'=>'province_name','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                          </div>
                        {!! $errors->first('address_province', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('address_province_en', !empty($export_lab->address_province_en) ?$export_lab->address_province_en:null, ['class' => 'form-control','id'=>'address_province_en','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                          </div>
                        {!! $errors->first('address_province_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('address_district', '<span class="text-danger">*</span> เขต/อำเภอ'.':'.'<br/><span class=" font_size">(Arnphoe/Khet)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_district', !empty($export_lab->address_district) ?$export_lab->address_district:null, ['class' => 'form-control','id'=>'amphur','required' => true]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('address_district', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_district_en', !empty($export_lab->address_district_en) ?$export_lab->address_district_en:null, ['class' => 'form-control','id'=>'address_district_en','required' => true]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('address_district_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row {{ $errors->has('address_subdistrict') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    {!! HTML::decode(Form::label('address_subdistrict', '<span class="text-danger">*</span> แขวง/ตำบล'.':'.'<br/><span class=" font_size">(Tambon/Khwaeng)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address_subdistrict',  !empty($export_lab->address_subdistrict) ?$export_lab->address_subdistrict:null, ['class' => 'form-control','id'=>'district','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('address_subdistrict', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address_subdistrict_en',  !empty($export_lab->address_subdistrict_en) ?$export_lab->address_subdistrict_en:null, ['class' => 'form-control','id'=>'address_subdistrict_en','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                        </div>
                        {!! $errors->first('address_subdistrict_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                   <div class="row">
                        {!! HTML::decode(Form::label('address_postcode', '<span class="text-danger">*</span> รหัสไปรษณีย'.':'.'<br/><span class=" font_size">(Zip code)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('address_postcode', !empty($export_lab->address_postcode) ?$export_lab->address_postcode:null, ['class' => 'form-control','id'=>'postcode','required' => true]) !!}
                                {{-- <span class="input-group-addon bg-secondary "> TH </span> --}}
                            </div>
                            {!! $errors->first('address_postcode', '<p class="help-block">:message</p>') !!}
                        </div>
                   </div>
                </div>
            </div>


            <div class="form-group {{ $errors->has('formula') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('formula', '<span class="text-danger">*</span> มาตรฐาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                        {!! Form::text('formula', !empty($export_lab->formula) ?$export_lab->formula:null, ['class' => 'form-control','id'=>'formula','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> TH </span>
                      </div>
                    {!! $errors->first('formula', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('formula_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                        {!! Form::text('formula_en', !empty($export_lab->formula_en) ?$export_lab->formula_en:null, ['class' => 'form-control','id'=>'formula_en','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                    </div>
                    {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('accereditatio_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('accereditatio_no', '<span class="text-danger">*</span> หมายเลขการรับรองที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                      {!! Form::text('accereditatio_no', !empty($export_lab->accereditatio_no) ?$export_lab->accereditatio_no:null, ['class' => 'form-control','id'=>'accereditatio_no','required' => true]) !!}
                      <span class="input-group-addon bg-secondary "> TH </span>
                    </div>
                  {!! $errors->first('accereditatio_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('accereditatio_no_en') ? 'has-error' : ''}}">
              {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
              <div class="col-md-7">
                     <div class="input-group">
                      {!! Form::text('accereditatio_no_en', !empty($export_lab->accereditatio_no_en) ?$export_lab->accereditatio_no_en:null, ['class' => 'form-control','id'=>'accereditatio_no_en','required' => true]) !!}
                      <span class="input-group-addon bg-secondary "> EN </span>
                    </div>
                  {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
              </div>
          </div>

            <div class="form-group {{ $errors->has('certificate_date_start') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certificate_date_start', 'ออกให้ ณ วันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-2">
                    {!! Form::text('certificate_date_start', !empty($export_lab->certificate_date_start) ?$export_lab->certificate_date_start:null, ['class' => 'form-control mydatepicker','id'=>'date_start' ]) !!}
                    {!! $errors->first('certificate_date_start', '<p class="help-block">:message</p>') !!}
                </div>
                {!! HTML::decode(Form::label('certificate_date_end', 'สิ้นสุดวันที่'.' :', ['class' => 'col-md-2 control-label'])) !!}
                <div class="col-md-2">
                    {!! Form::text('certificate_date_end', !empty($export_lab->certificate_date_end) ?$export_lab->certificate_date_end:null, ['class' => 'form-control mydatepicker','id'=>'date_end' ]) !!}
                    {!! $errors->first('certificate_date_end', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>
    </div>
</div>


@php
    /*$status = ['1' => 'อยู่ระหว่างจัดทำลงนามใบรับรองระบบงาน', '2'=>'นำส่งใบรับรองระบบงานเพื่อลงนาม'];
    if((!empty($export_lab->cer_type) && $export_lab->cer_type == 1) || empty($export_lab->cer_type) ){
        $status = ['0'=>'จัดทำใบรับรอง และอยู่ระหว่างลงนาม', '3'=>'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'];
    }else if((empty($export_lab->cer_type) || @$export_lab->cer_type == 2) && (in_array(@$export_lab->status, [3,4])) ){
        $status = ['3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'];
    }*/

    $status_set = ['A'  => ['0' => 'จัดทำใบรับรอง และอยู่ระหว่างลงนาม', '3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'],
                   'B1' => ['1' => 'อยู่ระหว่างจัดทำลงนามใบรับรองระบบงาน', '2' => 'นำส่งใบรับรองระบบงานเพื่อลงนาม'],
                   'B2' => ['3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน']
                  ];

    $config = HP::getConfig();
    $check_electronic_certificate = property_exists($config, 'check_electronic_certificate') ? $config->check_electronic_certificate : 0 ;

    if(!empty($export_lab->cer_type) && is_numeric($export_lab->cer_type)){//แก้ไขใบรับรอง
        $export_lab->cer_type = !empty($export_lab->cer_type) ? (int)$export_lab->cer_type : $check_electronic_certificate ;
        if($export_lab->cer_type==1){//แบบกระดาษ
            $status = $status_set['A'];
        }elseif($export_lab->cer_type==2){//แบบอิเล็กทรอนิกส์
            if(in_array($export_lab->status, [0, 1, 2])){
                $status = $status_set['B1'];
            }elseif(in_array($export_lab->status, [3, 4])){
                $status = $status_set['B2'];
            }
        }
    }else{//สร้างใบรับรองใหม่
        if($check_electronic_certificate==0){//แบบกระดาษ
            $status = $status_set['A'];
        }elseif($check_electronic_certificate==1){//แบบอิเล็กทรอนิกส์
            $status = $status_set['B1'];
        }
    }

@endphp

<div class="white-box">
    <div class="row">
        <div class="col-sm-12">

           <h3 class="box-title">อัพเดทสถานะการจัดทำใบรับรองระบบงาน</h3>
            <hr>

            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('request_number') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('request_number', '<span class="text-danger">*</span> เลขที่คำขอ :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('request_number', !empty($export_lab->request_number) ?$export_lab->request_number:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly' => true]) !!}
                        {!! $errors->first('request_number', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            {{-- <div class="col-sm-12">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> สถานะ :', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                    <div class="col-md-7">
                        {!! Form::select('status', $status, !empty($export_lab->status) ?$export_lab->status:null, ['class' => 'form-control',  'placeholder'=>'- เลือกสถานะ -', 'id'=>'status', 'required' => true]); !!}
                    </div>
                </div>
            </div> --}}

            {{-- {{$status}} --}}

            

            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status" class="col-md-4 control-label label-filter text-right">
                        <span class="text-danger">*</span> สถานะ :
                    </label>
                    <div class="col-md-7">
                        @if (isset($status))
                            <select name="status" id="status" class="form-control" required>
                                <option value="">- เลือกสถานะ -</option>
                                @foreach($status as $key => $value)
                                    <option value="{{ $key }}" {{ !empty($export_lab->status) && $export_lab->status == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </div>
            
                
           



            <div class="col-sm-12" id="certificate_section" style="display: none;">
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label(' ', '<span class="text-danger">*</span>  ไฟล์ใบรับรอง :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        @if(!empty($export_lab->certificate_newfile))
                            <p class="text-left">
                                <a href="{!! url('funtions/get-view').'/'.$export_lab->certificate_path.'/'.$export_lab->certificate_newfile.'/'.$export_lab->certificate_no.'_'.date('Ymd_hms').'.pdf'   !!}" target="_blank">
                                   <img src="{{ url('images/icon-certification.jpg') }}" width="15px" >
                               </a>
                                @if(@$export_lab->cer_type == 1)
                                    <a class="btn btn-danger btn-xs show_tag_a del-cerfile" href="{!! url('certify/certificate-export-lab/delete_file_certificate/'.$export_lab->id) !!}"
                                        title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                               @endif
                            </p>
                        @elseif(!empty($export_lab->attachs))
                            <p class="text-left">
                                <a href="{{url('certify/check/file_client/'.$export_lab->attachs.'/'.( !empty($export_lab->attachs_client_name) ? $export_lab->attachs_client_name :  basename($export_lab->attachs)  ))}}" target="_blank">
                                    {!! HP::FileExtension($export_lab->attachs)  ?? '' !!}
                                </a>
                                @if(@$export_lab->cer_type == 1)
                                    <a class="btn btn-danger btn-xs show_tag_a del-cerfile" href="{!! url('certify/certificate-export-lab/delete_file_certificate/'.$export_lab->id) !!}"
                                        title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </p>


                        @elseif(@$export_lab->cer_type == 1  || empty($export_lab->cer_type))
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="certificate_file" id="attachs" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $disabledSign = false;

                // if( isset($export_lab->purpose_type) && $export_lab->purpose_type != 1 ){
                //     $disabledSign = true;
                // }


            @endphp

            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_id') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_id', '<span class="text-danger">*</span> ผู้ลงนาม :', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                    <div class="col-md-7">
                        {!! Form::select('sign_id',
                        App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id'),
                         !empty($export_lab->sign_id) ?$export_lab->sign_id:null,
                         ['class' => 'form-control select2',
                         'placeholder'=>'- เลือกผู้ลงนาม -',
                         'id' =>'sign_id',
                         'disabled' => $disabledSign ]); !!}
                        <input type="hidden" name="sign_name" id="sign_name" value="{!! !empty($export_lab->sign_name) ?$export_lab->sign_name:null !!}">
                        {!! $errors->first('sign_id', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_position') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_position', 'ตำแหน่ง :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('sign_position', !empty($export_lab->sign_position) ?$export_lab->sign_position:null, ['class' => 'form-control','id'=>'sign_position','required' => false,'readonly' => true, 'disabled' => $disabledSign]) !!}
                        {!! $errors->first('sign_position', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_instead') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_instead', ' ', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        <label class="m-t-10"><input type="checkbox" class="check" name="sign_instead" value="1" {!! !empty($export_lab->sign_instead) && ($export_lab->sign_instead == 1)? 'checked':'' !!} {!! $disabledSign == true?'disabled':'' !!}> &nbsp;ปฏิบัติราชการแทนเลขาธิการ</label>
                        {!! $errors->first('sign_instead', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

           <div class="col-sm-12">
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label(' ', 'ไฟล์แนบท้าย :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        @if (!empty($cert_labs_file_all) && $cert_labs_file_all->count() > 0)
                        @foreach ($cert_labs_file_all->where('state',1) as $certilab_file)

                            <p class="text-left">
                                @if(!is_null($certilab_file->attach))
                                    <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach) !!}" target="_blank">
                                        {!! HP::FileExtension($certilab_file->attach) ?? '' !!}
                                    </a>
                                @endif
                                @if(!is_null($certilab_file->attach_pdf))
                                    <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach_pdf) !!}" target="_blank">
                                        {!! HP::FileExtension($certilab_file->attach_pdf) ?? '' !!}
                                    </a>
                                @endif
                            </p>

                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@push('js')
    <script type="text/javascript">

        $(document).ready(function() {


            $('#sign_id').change(function(){

                if($(this).val() != ''){

                    $.ajax({
                        url: "{!! url('certify/certificate-export-lab/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#sign_position').val(object.sign_position);
                        $('#sign_name').val(object.sign_name);
                    });

                }else{
                    $('#sign_position').val('-');
                    $('#sign_name').val('-');
                }
            });

            $('#status').change(function(){
                certificate_section();
            });certificate_section();

            let status = '{{ (isset($export_lab->status) && $export_lab->status >= 3)  ? 1 : 2 }}';
            if(status == 1){
                $('#box-readonly').find('input[type="text"]').prop('disabled', true);
                $('#box-readonly').find('input[type="text"]').prop('required', false);
                $('input[name=radio_address]:checked').addClass("check-readonly");
            }
            
           $(document).on("click", ".del-cerfile", function(e) {
                e.preventDefault();
                var link = $(this).attr('href');
                Swal.fire({
                    icon: 'error'
                    , title: 'ยืนยันการลบแถวและไฟล์แนบ !'
                    , showCancelButton: true
                    , confirmButtonColor: '#3085d6'
                    , cancelButtonColor: '#d33'
                    , confirmButtonText: 'บันทึก'
                    , cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = link;
                    }
                })
            });
            
        });

        function certificate_section(){
            if($('#status').val() == 3 || $('#status').val() == 4){
                $('#certificate_section').show();
                $('#attachs').prop('required',true);
            }else{
                $('#certificate_section').hide();
                $('#attachs').prop('required',false);
            }
        }

    </script>
@endpush

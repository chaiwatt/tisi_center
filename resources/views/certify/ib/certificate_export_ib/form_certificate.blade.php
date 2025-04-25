<div class="white-box" id="box-readonly">
    <div class="row">
        <div class="col-sm-12">
            <legend><h3 class="box-title">ข้อมูลใบรับรอง</h3></legend>

            {{-- <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  ออกใบรับรองฉบับนี้ให้'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-9">
                    @if(isset($app_no))
                    {!! Form::select('app_certi_ib_id', $app_no,  !empty( $export_ib->app_certi_ib_id)? $export_ib->app_certi_ib_id:null, ['class' => 'form-control', 'id' => 'app_certi_ib_id', 'placeholder'=>'- เลขคำขอ -', 'required' => true]); !!}
                    {!! $errors->first('app_certi_ib_id', '<p class="help-block">:message</p>') !!}
                    @else
                        {!! Form::hidden('app_certi_ib_id', !empty( $export_ib->app_certi_ib_id)? $export_ib->app_certi_ib_id:null, ['class' => 'form-control','id'=>'app_certi_ib_id','disabled' => true]) !!}
                        {!! Form::text('title', !empty( $export_ib->app_no)? $export_ib->app_no:null, ['class' => 'form-control','id'=>'title','disabled' => true ]) !!}
                        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                    @endif

                    {!! Form::hidden('id', null, ['class' => 'form-control','id'=>'id']) !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('name_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                    <div class="  input-group">
                        {!! Form::text('name_en', !empty( $export_ib->name_en)? $export_ib->name_en:null, ['class' => 'form-control','id'=>'name_en','required' => false ,'disabled' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                    </div>
                    {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('app_no', !empty( $export_ib->app_no)? $export_ib->app_no:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly'=>true]) !!}
                    {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div> --}}

            <div class="form-group {{ $errors->has('certificate') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certificate', '<span class="text-danger">*</span> ใบรับรองเลขที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('certificate', !empty( $export_ib->certificate)? $export_ib->certificate:null, ['class' => 'form-control','id'=>'certificate','required' => true]) !!}
                    {!! $errors->first('certificate', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


            <div class="form-group {{ $errors->has('name_unit') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('name_unit', '<span class="text-danger">*</span>  ชื่อหน่วยตรวจสอบ'.':'.'<br/><span class=" font_size">(Name laboratory)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                       <div class="  input-group">
                        {!! Form::text('name_unit',  !empty( $export_ib->name_unit)? $export_ib->name_unit:null, ['class' => 'form-control','id'=>'name_unit','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> TH </span>
                      </div>
                    {!! $errors->first('name_unit', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('name_unit_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-9">
                       <div class="  input-group">
                        {!! Form::text('name_unit_en',  !empty( $export_ib->name_unit_en)? $export_ib->name_unit_en:null, ['class' => 'form-control','id'=>'name_unit_en','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                      </div>
                    {!! $errors->first('name_unit_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('radio_addressaddress') ? 'has-error' : ''}}">
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

            <div class="row {{ $errors->has('address') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    {!! HTML::decode(Form::label('address', '<span class="text-danger">*</span> ตั้งอยู่เลขที่'.':'.'<br/><span class=" font_size">(Address)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('address', !empty( $export_ib->address)? $export_ib->address:null, ['class' => 'form-control','id'=>'address','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('address_en', !empty( $export_ib->address_en)? $export_ib->address_en:null, ['class' => 'form-control','id'=>'address_en','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                          </div>
                        {!! $errors->first('address_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('allay', ' หมู่ที่'.':'.'<br/><span class=" font_size">(Mool)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('allay', !empty( $export_ib->allay)? $export_ib->allay:null, ['class' => 'form-control','id'=>'allay','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('allay', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('allay_en', !empty( $export_ib->allay_en)? $export_ib->allay_en:null, ['class' => 'form-control','id'=>'allay_en','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('allay_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row {{ $errors->has('village_no') ? 'has-error' : ''}}">

                <div class="col-md-6">
                    {!! HTML::decode(Form::label('village_no', ' ตรอก/ซอย'.':'.'<br/><span class=" font_size">(Trok/Sol)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('village_no', !empty( $export_ib->village_no)? $export_ib->village_no:null, ['class' => 'form-control','id'=>'village_no','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('village_no', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('village_no_en', !empty( $export_ib->village_no_en)? $export_ib->village_no_en:null, ['class' => 'form-control','id'=>'village_no_en','required' => false]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                        </div>
                        {!! $errors->first('village_no_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('road', 'ถนน'.':'.'<br/><span class=" font_size">(Street/Road)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('road', !empty( $export_ib->road)? $export_ib->road:null, ['class' => 'form-control','id'=>'road','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('road', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('road_en', !empty( $export_ib->road_en)? $export_ib->road_en:null, ['class' => 'form-control','id'=>'road_en','required' => false]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('road_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row {{ $errors->has('province_name') ? 'has-error' : ''}}">
                <div class="col-md-6">
                    {!! HTML::decode(Form::label('province_name', '<span class="text-danger">*</span> จังหวัด'.':'.'<br/><span class=" font_size">(Province)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('province_name', !empty( $export_ib->province_name)? $export_ib->province_name:null, ['class' => 'form-control','id'=>'province_name','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                        </div>
                        {!! $errors->first('province_name', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                        <div class="  input-group">
                            {!! Form::text('province_name_en', !empty( $export_ib->province_name_en)? $export_ib->province_name_en:null, ['class' => 'form-control','id'=>'province_name_en','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                        </div>
                        {!! $errors->first('province_name_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                   <div class="row">
                        {!! HTML::decode(Form::label('amphur_name', '<span class="text-danger">*</span> เขต/อำเภอ'.':'.'<br/><span class=" font_size">(Arnphoe/Khet)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('amphur_name', !empty( $export_ib->amphur_name)? $export_ib->amphur_name:null, ['class' => 'form-control','id'=>'amphur_name','required' => true]) !!}
                                <span class="input-group-addon bg-secondary "> TH </span>
                            </div>
                            {!! $errors->first('amphur_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row">
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('amphur_name_en', !empty( $export_ib->amphur_name_en)? $export_ib->amphur_name_en:null, ['class' => 'form-control','id'=>'amphur_name_en','required' => true]) !!}
                                <span class="input-group-addon bg-secondary "> EN </span>
                            </div>
                            {!! $errors->first('amphur_name_en', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="row {{ $errors->has('district_name') ? 'has-error' : ''}}">

                <div class="col-md-6">
                    {!! HTML::decode(Form::label('district_name', '<span class="text-danger">*</span> แขวง/ตำบล'.':'.'<br/><span class=" font_size">(Tambon/Khwaeng)</span>', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('district_name', !empty( $export_ib->district_name)? $export_ib->district_name:null, ['class' => 'form-control','id'=>'district_name','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> TH </span>
                          </div>
                        {!! $errors->first('district_name', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-6 control-label  label-height'])) !!}
                    <div class="col-md-6  form-group">
                          <div class="  input-group">
                            {!! Form::text('district_name_en', !empty( $export_ib->district_name_en)? $export_ib->district_name_en:null, ['class' => 'form-control','id'=>'district_name_en','required' => true]) !!}
                            <span class="input-group-addon bg-secondary "> EN </span>
                          </div>
                        {!! $errors->first('district_name_en', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        {!! HTML::decode(Form::label('postcode', '<span class="text-danger">*</span> รหัสไปรษณีย'.':'.'<br/><span class=" font_size">(Zip code)</span>', ['class' => 'col-md-4 control-label  label-height'])) !!}
                        <div class="col-md-6  form-group">
                            <div class="  input-group">
                                {!! Form::text('postcode', !empty( $export_ib->postcode)? $export_ib->postcode:null, ['class' => 'form-control','id'=>'postcode','required' => true]) !!}
                                {{-- <span class="input-group-addon bg-secondary "> TH </span> --}}
                            </div>
                            {!! $errors->first('postcode', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group {{ $errors->has('formula') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('formula', '<span class="text-danger">*</span> มาตรฐาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                        {!! Form::text('formula', !empty( $export_ib->formula)? $export_ib->formula:null, ['class' => 'form-control','id'=>'formula','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> TH </span>
                    </div>
                    {!! $errors->first('formula', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('formula_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                        {!! Form::text('formula_en', !empty( $export_ib->formula_en)? $export_ib->formula_en:null, ['class' => 'form-control','id'=>'formula_en','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                    </div>
                    {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('accereditatio_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('accereditatio_no', '<span class="text-danger">*</span> มาตรฐาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-7">
                    <div class="  input-group">
                        {!! Form::text('accereditatio_no', !empty( $export_ib->accereditatio_no)? $export_ib->accereditatio_no:null, ['class' => 'form-control','id'=>'accereditatio_no','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> TH </span>
                    </div>
                    {!! $errors->first('accereditatio_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('accereditatio_no_en') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
                <div class="col-md-7">
                    <div class="input-group">
                        {!! Form::text('accereditatio_no_en', !empty( $export_ib->accereditatio_no_en)? $export_ib->accereditatio_no_en:null, ['class' => 'form-control','id'=>'accereditatio_no_en','required' => true]) !!}
                        <span class="input-group-addon bg-secondary "> EN </span>
                    </div>
                    {!! $errors->first('formula_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('date_start') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('date_start', 'ออกให้ ณ วันที่'.' :', ['class' => 'col-md-3 control-label'])) !!}
                <div class="col-md-2">
                    {!! Form::text('date_start', !empty( $export_ib->date_start)? $export_ib->date_start:null, ['class' => 'form-control mydatepicker','id'=>'date_start' ]) !!}
                    {!! $errors->first('date_start', '<p class="help-block">:message</p>') !!}
                </div>
                {{-- {!! HTML::decode(Form::label('date_end', 'สิ้นสุดวันที่'.' :', ['class' => 'col-md-2 control-label'])) !!}
                <div class="col-md-2">
                    {!! Form::text('date_end', !empty( $export_ib->date_end)? $export_ib->date_end:null, ['class' => 'form-control mydatepicker','id'=>'date_end' ]) !!}
                    {!! $errors->first('date_end', '<p class="help-block">:message</p>') !!}
                </div> --}}
            </div>

        </div>
    </div>
</div>




@php
    /*$status = ['1' => 'อยู่ระหว่างจัดทำลงนามใบรับรองระบบงาน', '2'=>'นำส่งใบรับรองระบบงานเพื่อลงนาม'];
    if((!empty($export_ib->cer_type) && $export_ib->cer_type == 1) || empty($export_ib->cer_type) ){
       $status = ['0'=>'จัดทำใบรับรอง และอยู่ระหว่างลงนาม', '3'=>'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'];
    }else if((empty($export_ib->cer_type) || @$export_ib->cer_type == 2) && (in_array(@$export_ib->status, [3,4])) ){
       $status = ['3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'];
    }*/

    $status_set = ['A'  => ['0' => 'จัดทำใบรับรอง และอยู่ระหว่างลงนาม', '3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน'],
                   'B1' => ['1' => 'อยู่ระหว่างจัดทำลงนามใบรับรองระบบงาน', '2' => 'นำส่งใบรับรองระบบงานเพื่อลงนาม'],
                   'B2' => ['3' => 'ลงนามใบรับรองเรียบร้อย', '4' => 'จัดส่งใบรับรองระบบงาน']
                  ];

    $config = HP::getConfig();
    $check_electronic_certificate = property_exists($config, 'check_electronic_certificate') ? $config->check_electronic_certificate : 0 ;

    if(!empty($export_ib->cer_type) && is_numeric($export_ib->cer_type)){//แก้ไขใบรับรอง
        $export_ib->cer_type = !empty($export_ib->cer_type) ? (int)$export_ib->cer_type : $check_electronic_certificate ;
        if($export_ib->cer_type==1){//แบบกระดาษ
            $status = $status_set['A'];
        }elseif($export_ib->cer_type==2){//แบบอิเล็กทรอนิกส์
            if(in_array($export_ib->status, [0, 1, 2])){
                $status = $status_set['B1'];
            }elseif(in_array($export_ib->status, [3, 4])){
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

<div class="white-box" id="box_readonly">
    <div class="row">
        <div class="col-sm-12">

           <h3 class="box-title">อัพเดทสถานะการจัดทำใบรับรองระบบงาน</h3>
            <hr>

            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('app_no', !empty($export_ib->app_no) ?$export_ib->app_no:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly' => true]) !!}
                        {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('status', '<span class="text-danger">*</span> สถานะ :', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                    <div class="col-md-7">
                        {!! Form::select('status', $status, !empty($export_ib->status) ?$export_ib->status:null, ['class' => 'form-control',  'placeholder'=>'- เลือกสถานะ -', 'id'=>'status', 'required' => true]); !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12" id="certificate_section" style="display: none;">
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label(' ', '<span class="text-danger">*</span>  ไฟล์ใบรับรอง :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        @if(!empty($export_ib->certificate_newfile))
                            <p class="text-left">
                                <a href="{!! url('funtions/get-view').'/'.$export_ib->certificate_path.'/'.$export_ib->certificate_newfile.'/'.$export_ib->certificate_no.'_'.date('Ymd_hms').'.pdf'   !!}" target="_blank">
                                   <img src="{{ url('images/icon-certification.jpg') }}" width="15px" >
                               </a>
                                @if(@$export_ib->cer_type == 1)
                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('certify/certificate-export-ib/delete_file_certificate/'.$export_ib->id) !!}"
                                        title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                               @endif
                            </p>
                        @elseif(!empty($export_ib->attachs))
                            <p class="text-left">
                                <a href="{{url('certify/check/file_ib_client/'.$export_ib->attachs.'/'.( !empty($export_ib->attach_client_name) ? $export_ib->attach_client_name :  basename($export_ib->attachs)  ))}}" target="_blank">
                                    {!! HP::FileExtension($export_ib->attachs)  ?? '' !!}
                                </a>
                                @if(@$export_ib->cer_type == 1)
                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('certify/certificate-export-ib/delete_file_certificate/'.$export_ib->id) !!}"
                                        title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </p>


                        @elseif(@$export_ib->cer_type == 1  || empty($export_ib->cer_type))
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attachs" id="attachs" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_id') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_id', '<span class="text-danger">*</span> ผู้ลงนาม :', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
                    <div class="col-md-7">
                        {!! Form::select('sign_id',
                        App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id'),
                         !empty($export_ib->sign_id) ?$export_ib->sign_id:null,
                         ['class' => 'form-control select2',
                         'placeholder'=>'- เลือกผู้ลงนาม -',
                         'id' =>'sign_id' ]); !!}
                        <input type="hidden" name="sign_name" id="sign_name" value="{!! !empty($export_ib->sign_name) ?$export_ib->sign_name:null !!}">
                        {!! $errors->first('sign_id', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_position') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_position', 'ตำแหน่ง :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        {!! Form::text('sign_position', !empty($export_ib->sign_position) ?$export_ib->sign_position:null, ['class' => 'form-control','id'=>'sign_position','required' => true,'readonly' => true]) !!}
                        {!! $errors->first('sign_position', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-12 box_sign">
                <div class="form-group {{ $errors->has('sign_instead') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('sign_instead', ' ', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        <label class="m-t-10"><input type="checkbox" class="check" name="sign_instead" value="1" {!! !empty($export_ib->sign_instead) && ($export_ib->sign_instead == 1)? 'checked':'' !!}> &nbsp;ปฏิบัติราชการแทนเลขาธิการ</label>
                        {!! $errors->first('sign_instead', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>

           <div class="col-sm-12">
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label(' ', 'ไฟล์แนบท้าย :', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-7">
                        @if (isset($certiib_file_all) && count($certiib_file_all) > 0)
                        @foreach ($certiib_file_all->where('state',1) as $certiib_file)

                             <p class="text-left">
                                 @if(!is_null($certiib_file->attach))
                                     <a href="{!! HP::getFileStorage($attach_path.$certiib_file->attach) !!}" target="_blank">
                                         {!! HP::FileExtension($certiib_file->attach) ?? '' !!}
                                     </a>
                                 @endif
                                 @if(!is_null($certiib_file->attach_pdf))
                                     <a href="{!! HP::getFileStorage($attach_path.$certiib_file->attach_pdf) !!}" target="_blank">
                                         {!! HP::FileExtension($certiib_file->attach_pdf) ?? '' !!}
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
                        url: "{!! url('certify/certificate-export-ib/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#sign_position').val(object.sign_position);
                        $('#sign_name').val(object.sign_name);
                    });
                }else{
                    $('#sign_position').val('-');
                    $('#sign_name').val('-');
                }
            });
            let status = '{{ (isset($export_ib->status) && $export_ib->status >= 3)  ? 1 : 2 }}';
            if(status == 1){
                $('#box-readonly').find('input[type="text"]').prop('disabled', true);
                $('#box-readonly').find('input[type="text"]').prop('required', false);
                $('#box_readonly').find('input[type="text"],input[type="select"],input[type="checkbox"]').prop('disabled', true);
                $('#box_readonly').find('input[type="text"],input[type="select"],input[type="checkbox"]').prop('required', false);
                $('#box_readonly').find('.show_tag_a').hide();
                $('input[name=check_badge]').addClass("check-readonly");
                $('input[name=radio_address]:checked').addClass("check-readonly");
            }
            $('#status').change(function(){
                certificate_section();
            });certificate_section();

        function certificate_section(){
            if($('#status').val() == 3 || $('#status').val() == 4){
                $('#certificate_section').show();
                $('#attachs').prop('required',true);
            }else{
                $('#certificate_section').hide();
                $('#attachs').prop('required',false);
            }
        }


        });

    </script>


@endpush

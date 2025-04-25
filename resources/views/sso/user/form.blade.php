@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
     <!-- Data Table CSS -->
 {{-- <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/> --}}
    <style type="text/css">
        .label-editor{
            border-radius: .25em;
            padding: .2em .6em .3em;
        }
    </style>
@endpush

@php
    $staff = auth()->user();
@endphp

<div class="col-md-12">
    <div class="white-box">

        <h3 class="box-title m-b-0">แก้ไขข้อมูลผู้ใช้งาน
            @if($user->applicanttype_id==1)
                <button id="btn-dbd" data-toggle="modal" data-target="#modal-dbd" type="button" class="btn btn-primary pull-right"><i class="mdi mdi-compare"></i> เปรียบเทียบข้อมูลกับกรมพัฒนาธุรกิจการค้า</button>
                @include('sso/user/modal/dbd')
            @elseif($user->applicanttype_id==2)
                <button id="btn-dopa" data-toggle="modal" data-target="#modal-dopa" type="button" class="btn btn-primary pull-right"><i class="mdi mdi-compare"></i> เปรียบเทียบข้อมูลกับกรมการปกครอง</button>
                @include('sso/user/modal/dopa')
            @elseif($user->applicanttype_id==3)
                <button id="btn-rd" data-toggle="modal" data-target="#modal-rd" type="button" class="btn btn-primary pull-right"><i class="mdi mdi-compare"></i> เปรียบเทียบข้อมูลกับกรมสรรพากร</button>
                @include('sso/user/modal/rd')
            @endif
        </h3>
        <p class="text-muted m-b-30 font-13">
            แก้ไขข้อมูลผู้ประกอบการ (SSO)
        </p>

        <div class="form-group">
            <label class="col-md-3">ประเภทการลงทะเบียน : *</label>
            <div class="col-md-9">
                @foreach (HP::applicant_types() as $key => $applicant_type)
                    <div class="radio radio-primary radio-inline">
                        {!! Form::radio('applicanttype_id', $key, null, ['id' => 'applicanttype_id'.$key, 'disabled' => true]); !!}
                        <label for="applicanttype_id{{ $key }}">{{ $applicant_type }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">

            <label class="col-md-3">ชื่อผู้ประกอบการ : *</label>

            @php
                $prefixs = [];
                $prefix_type = '';
                $name_input_width = 'col-md-9';
                if($user->applicanttype_id==1){
                    $prefixs = HP::company_prefixs();
                    $prefix_type = '- ประเภทการจดทะเบียน -';
                    $name_input_width = 'col-md-6';
                }elseif ($user->applicanttype_id==2) {
                    $prefixs = HP::person_prefixs();
                    $prefix_type = '- คำนำหน้าชื่อ -';
                    $name_input_width = 'col-md-6';

                    if($user->prefix_name=='4' && array_key_exists(4, $prefixs->toArray())){ //อื่นๆ แสดงข้อความแทน อื่นๆ
                        $prefixs[4] = $user->prefix_text;
                    }
                }
            @endphp
            @if(in_array($user->applicanttype_id, [1, 2]))
                <div class="col-md-3">
                    {!! Form::select('prefix_name', $prefixs, null, ['class' => 'form-control', 'placeholder' => $prefix_type, 'disabled' => true]); !!}
                    @if ($errors->has('prefix_name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('prefix_name') }}</strong>
                        </span>
                    @endif
                </div>
            @endif

            <div class="{{ $name_input_width }}">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'ชื่อสถานประกอบการ เช่น บริษัท ตัวอย่าง จำกัด', 'id' => 'name', 'disabled' => true]); !!}
            </div>

        </div>

        <div class="form-group">

            <label class="col-md-3">เลขประจำตัวผู้เสียภาษี : *</label>
            <div class="col-md-3">
                {!! Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => '', 'disabled' => true]); !!}
                @if ($errors->has('tax_number'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('tax_number') }}</strong>
                    </span>
                @endif
            </div>

            <label class="col-md-3 input-niti">วันที่จดทะเบียนนิติบุคคล : *</label>
            <div class="col-md-3 input-niti">
                <div class="input-group">
                    {!! Form::text('date_niti', null, ['class' => 'form-control datepicker', 'placeholder' => '']); !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                </div>
                @if ($errors->has('date_niti'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('date_niti') }}</strong>
                    </span>
                @endif
            </div>

            <label class="col-md-3 input-birth">วันที่เกิด : *</label>
            <div class="col-md-3 input-birth">
                <div class="input-group">
                    {!! Form::text('date_of_birth', null, ['class' => 'form-control datepicker', 'placeholder' => '']); !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                </div>
                @if ($errors->has('date_of_birth'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                    </span>
                @endif
            </div>

        </div>

        <div class="form-group" id="box-branch">

            <label class="col-md-3">ประเภทสาขา : *</label>
            <div class="col-md-3">

                <div class="radio radio-primary radio-inline">
                    {!! Form::radio('branch_type', 1, null, ['id' => 'branch_type1', 'disabled' => true]); !!}
                    <label for="branch_type1">สำนักงานใหญ่</label>
                </div>

                <div class="radio radio-primary radio-inline">
                    {!! Form::radio('branch_type', 2, null, ['id' => 'branch_type2', 'disabled' => true]); !!}
                    <label for="branch_type2">สาขา</label>
                </div>

                @if ($errors->has('branch_type'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('branch_type') }}</strong>
                    </span>
                @endif

            </div>

            <label class="col-md-3">รหัสสาขา : *</label>
            <div class="col-md-3">
                {!! Form::text('branch_code', null, ['class' => 'form-control', 'placeholder' => 'รหัสสาขา', 'disabled' => true]); !!}
                @if ($errors->has('branch_code'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('branch_code') }}</strong>
                    </span>
                @endif
            </div>

        </div>

        <h5 class="box-title" style="font-size: 20px;" id="head-address">ที่ตั้งสำนักงานใหญ่</h5>

        <div class="form-group">
            <div class="col-md-8">
                {!! HTML::decode(Form::label('address_no', 'เลขที่'.'  <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('address_no', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('address_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="col-md-4">
                {!! HTML::decode(Form::label('soi', 'ตรอก/ซอย', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('soi', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('soi', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4" >
                {!! HTML::decode(Form::label('moo', 'หมู่', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('moo', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('moo', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('street', 'ถนน', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('street', null, ['class' => 'form-control', 'required'=> false]) !!}
                    {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8">
                {!! Form::label('address_search', 'ค้นหา', ['class' => 'col-md-12']) !!}
                <div class="col-md-12 ">
                    {!! Form::text('address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'address_search' ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('subdistrict', 'แขวง/ตำบล'.' <span class="text-danger">*</span>', ['class' => 'col-md-12 '])) !!}
                <div class="col-md-12">
                    {!! Form::text('subdistrict', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('subdistrict', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('district', 'เขต/อำเภอ'.' <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12">
                    {!! Form::text('district', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('district', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('province', 'จังหวัด'.' <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('province', null, ['class' => 'form-control', 'required' => true ]) !!}
                    {!! $errors->first('province', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-2">
                {!! HTML::decode(Form::label('zipcode', 'รหัสไปรษณีย์'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('zipcode', null, ['class' => 'form-control', 'id' => 'zipcode', 'required' => true]) !!}
                    {!! $errors->first('zipcode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('tel', 'เบอร์โทรศัพท์', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('tel', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('fax', 'เบอร์โทรสาร', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('fax', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('fax', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <h5 class="box-title" style="font-size: 20px;">ที่อยู่ที่สามารถติดต่อได้</h5>

        <div class="form-group">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="checkbox checkbox-success">
                        <input type="checkbox" id="checkbox_head_address_no">
                        <label for="checkbox_head_address_no">&nbsp;&nbsp;<span class="checkbox_head_address_no">ที่เดียวกับที่ตั้งสำนักงานใหญ่</span></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_address_no', 'เลขที่'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_address_no', null, ['class' => 'form-control', 'required'=> true]) !!}
                    {!! $errors->first('contact_address_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_building', 'อาคาร/หมู่บ้าน', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('contact_building', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('contact_building', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_soi', 'ตรอก/ซอย', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12 " >
                    {!! Form::text('contact_soi', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('contact_soi', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_moo', 'หมู่', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_moo', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('contact_moo', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_street', 'ถนน', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_street', null, ['class' => 'form-control', 'required' => false]) !!}
                    {!! $errors->first('contact_street', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>

        <div class="form-group">
            <div class="col-md-8">
                {!! Form::label('contact_address_search', 'ค้นหา', ['class' => 'col-md-12']) !!}
                <div class="col-md-12 ">
                    {!! Form::text('contact_address_search', null, ['class' => 'form-control', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ตำบล/แขวง,อำเภอ/เขต,จังหวัด,รหัสไปรษณีย์', 'id'=>'contact_address_search' ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_subdistrict', 'แขวง/ตำบล'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_subdistrict', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('contact_subdistrict', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_district', 'เขต/อำเภอ'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_district', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('contact_district', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_province', 'จังหวัด'.' <span class="text-danger">*</span>', ['class' => 'col-md-12  '])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_province', null, ['class' => 'form-control', 'required' => true ]) !!}
                    {!! $errors->first('contact_province', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-2">
                {!! HTML::decode(Form::label('contact_zipcode', 'รหัสไปรษณีย์'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_zipcode', null, ['class' => 'form-control', 'required' => true]) !!}
                    {!! $errors->first('contact_zipcode', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <h5 class="box-title" style="font-size: 20px;">ข้อมูลผู้ติดต่อ</h5>

        <div class="form-group">
            <div class="col-md-3">
                {!! HTML::decode(Form::label('contact_tax_id', 'เลขบัตรประจำตัวประชาชน', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                      {!! Form::text('contact_tax_id', null, ['class' => 'form-control tax_id_format', 'required' => false]) !!}
                      {!! $errors->first('contact_tax_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                {!! HTML::decode(Form::label('contact_prefix_name', 'ชื่อผู้ติดต่อ', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::select('contact_prefix_name',
                                     App\Models\Basic\Prefix::where('state', 1)->pluck('initial', 'id')->all(),
                                     null,
                                     ['class' => 'form-control',
                                      'id'=>'contact_prefix_name',
                                      'placeholder' =>'- เลือกคำนำหน้าชื่อ -',
                                      'required'=> false
                                     ])
                    !!}
                </div>
            </div>
            <div class="col-md-3">
                {!! HTML::decode(Form::label('', '&nbsp;', ['class' => 'col-md-12 '])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_first_name', null, ['class' => 'form-control', 'placeholder' => 'ชื่อ', 'required' => false]) !!}
                    {!! $errors->first('contact_first_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                    {!! HTML::decode(Form::label('', '&nbsp;', ['class' => 'col-md-12'])) !!}
                    <div class="col-md-12">
                          {!! Form::text('contact_last_name', null, ['class' => 'form-control', 'placeholder' => 'นามสกุล', 'required'=> false]) !!}
                          {!! $errors->first('contact_last_name', '<p class="help-block">:message</p>') !!}
                    </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_position', 'ตำแหน่ง', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_position', null, ['class' => 'form-control', 'required' => false]) !!}
                    {!! $errors->first('contact_position', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_tel', 'เบอร์โทรศัพท์', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_tel', null, ['class' => 'form-control', 'required' => false]) !!}
                    {!! $errors->first('contact_tel', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_phone_number', 'เบอร์โทรศัพท์มือถือ', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_phone_number', null, ['class' => 'form-control phone_number_format', 'id' => 'phone_number', 'required' => false]) !!}
                    {!! $errors->first('contact_phone_number', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('contact_fax', 'โทรสาร', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::text('contact_fax', null, ['class' => 'form-control', 'id' => 'fax', 'required'=> false]) !!}
                    {!! $errors->first('contact_fax', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-6" >
                {!! HTML::decode(Form::label('email', 'e-Mail'.' <span class="text-danger">*</span>', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'ระบุ E-mail ที่ใช้งานได้จริง เพื่อรับข่าวสารจาก สมอ.', 'required' => true]) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                {!! HTML::decode(Form::label('attach_file', 'เอกสารแนบการยืนยันตัวตน', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    @php
                        $attach_count = 0;

                        $corporatefiles = json_decode($user->corporatefile);
                        $personfiles    = json_decode($user->personfile);
                        $attachs = array_merge((array)$corporatefiles, (array)$personfiles);
                    @endphp

                    @if (!empty($attachs))

                        @foreach ($attachs as $key => $file)

                            @php
                                $file_url = HP::getFileStorage('media/com_user/'.$user->username.'/'.$file->realfile);
                            @endphp

                            @if(!empty($file_url))
                                <a href="{{ $file_url }}" target="_blank">
                                    <i class="mdi mdi-file-pdf text-danger"></i> {{ $file->filename }}
                                </a>
                                @php
                                    $attach_count++;
                                @endphp
                            @endif

                        @endforeach

                    @endif

                    @if($attach_count===0)
                        <i class="text-muted">ไม่มี</i>
                    @endif

                    <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="personfile">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                {!! HTML::decode(Form::label('attach_file', 'สถานะการตรวจสอบข้อมูลกับหน่วยงานที่เกี่ยวข้อง', ['class' => 'col-md-12'])) !!}
                <div class="col-md-12">
                    {!! $user->check_api==1 ? '<b class="text-success">ตรวจสอบแล้ว</b>' : '<span class="text-danger">ไม่ได้ตรวจสอบ</span>' !!}
                </div>
            </div>

        </div>

        <h5 class="box-title" style="font-size: 20px;">ชื่อผู้ใช้งาน สถานะการยืนยันตัวตนและใช้งาน</h5>

        <div class="form-group">
            <label class="control-label col-md-3"><span class="pull-left">ชื่อผู้ใช้งาน:</span></label>
            <div class="col-md-9">
                <p class="form-control-static"> {{ $user->username }} </p>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3">สถานะการใช้งาน : *</label>
            <div class="col-md-9">
                <div class="radio radio-success radio-inline">
                    {!! Form::radio('block', 0, null, ['id' => 'block0']); !!}
                    <label for="block0">ใช้งาน</label>
                </div>
                <div class="radio radio-danger radio-inline">
                    {!! Form::radio('block', 1, null, ['id' => 'block1']); !!}
                    <label for="block1">บล็อค</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3">สถานะการยืนยันตัวตน : *</label>
            <div class="col-md-9">
                <div class="radio radio-danger radio-inline">
                    {!! Form::radio('state', 1, null, ['id' => 'state1', 'disabled' => true]); !!}
                    <label for="state1">รอยืนยันตัวตนทาง E-mail</label>
                </div>
                <div class="radio radio-success radio-inline">
                    {!! Form::radio('state', 2, null, ['id' => 'state2', 'disabled' => ($user->state===3 ? false : true)]); !!}
                    <label for="state2">ยืนยันตัวตนแล้ว</label>
                </div>
                <div class="radio radio-warning radio-inline">
                    {!! Form::radio('state', 3, null, ['id' => 'state3', 'disabled' => ($user->state===3 ? false : true)]); !!}
                    <label for="state3">รอเจ้าหน้าที่เปิดใช้งาน</label>
                </div>
                <div class="radio radio-danger radio-inline">
                    {!! Form::radio('state', 4, null, ['id' => 'state4', 'disabled' => ($user->state===2 ? true : false)]); !!}
                    <label for="state4">ไม่ใช้งาน</label>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="col-md-6">

    @if($staff->isAdmin())
        <div class="white-box">
            <h3 class="box-title m-b-0">เปลี่ยนรหัสผ่าน</h3>
            <p class="text-muted font-13"> ถ้าไม่เปลี่ยนปล่อยว่างไว้ </p>

            <div class="form-group">
                {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-5 control-label']) !!}
                <div class="col-sm-7">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">เปลี่ยนรหัสผ่านล่าสุด</label>
                <div class="col-sm-7">
                    <p class="form-control-static"> {{ !is_null($user->lastResetTime) ? HP::dateTimeFormatN($user->lastResetTime) : '-' }} </p>
                </div>
            </div>
        </div>
    @endif

    <div class="white-box">
        <h3 class="box-title m-b-0">ลงชื่อเข้าใช้งาน 2 ขั้นตอน</h3>
        <p class="text-muted m-b-30 font-13">Google Authenticator แก้ไขจากเปิดเป็นปิดเท่านั้น</p>

        <div class="form-group">
            <label class="control-label col-sm-7">การใช้งาน ลงชื่อเข้าใช้งาน 2 ขั้นตอน :</label>
            <div class="col-md-5">
                @if($user->google2fa_status==1)
                    {!! Form::checkbox('google2fa_status', 1, true, ['class' => 'google2fa_checkbox', 'data-color' => '#13dafe']) !!}
                @else
                    <p class="form-control-static"> <b class="text-danger">ปิดอยู่</b> </p>
                @endif
            </div>
        </div>

    </div>

    <div class="white-box">
        <h3 class="box-title m-b-0">เหตุผลที่แก้ไขข้อมูล <span class="text-danger">*</span></h3>

        <div class="form-group m-t-10">
            {{-- <label class="col-md-12">เหตุผลที่แก้ไขข้อมูล :</label> --}}
            <div class="col-md-12 m-l-0 p-l-5">
                <textarea class="form-control col-md-12" name="remark" id="remark" placeholder="กรอกเหตุผลที่แก้ไขข้อมูลที่นี่" required></textarea>
            </div>
        </div>


        @if(!empty($user->user_history_group_many) && count($user->user_history_group_many) > 0 )

            @php
                $prefix =App\Models\Basic\Prefix::where('state', 1)->pluck('initial', 'id')->toArray();
                $applicant_types = App\Models\Sso\User::applicant_type_list();
                $state = (new App\Models\Sso\User)->states();
                $block = ['0'=>'ใช้งาน','1'=>'บล็อค'];
                $check_apis = ['0'=>'<span class="text-danger">ไม่ได้ตรวจสอบ</span>', '1'=>'<span class="text-success">ตรวจสอบแล้ว</span>'];
                $enables    = ['0' => '<span class="text-danger">ปิด</span>', '1' => '<span class="text-success">เปิด</span>'];
                $juristic_status_list = App\Models\Sso\User::JuristicStatusList();
            @endphp

            <a onclick="return false;" class="btn btn-link" data-toggle="modal" data-target="#historyModal">ประวัติการแก้ไขข้อมูล</a>

            <div class="modal fade" id="historyModal" role="dialog" aria-labelledby="addBrand">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="historyModalLabel1">
                                ประวัติการแก้ไขข้อมูล
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </h4>
                        </div>

                        <div class="modal-body">
                            <table class="table color-bordered-table info-bordered-table" width="100%" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%" class="text-center">#</th>
                                        <th width="19%" class="text-center">วันที่ดำเนินการ</th>
                                        <th width="19%" class="text-center">ผู้ดำเนินการ</th>
                                        <th width="20%" class="text-center">เหตุผล</th>
                                        <th width="20%" class="text-center">แก้ไข</th>
                                        <th width="15%" class="text-center">จาก</th>
                                        <th width="15%" class="text-center">เป็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->user_history_group_many as $key => $history)

                                        @if (count($history->user_history_many) > 0)
                                            @foreach ($history->user_history_many as $key1 => $item)

                                                @php
                                                    if($key1 == 0){
                                                        $editor = '';//ผู้แก้ไข
                                                        if($history->editor_type=='staff'){//เจ้าหน้าที่
                                                            $editor = !empty($history->user_created->FullName) ? $history->user_created->FullName : null ;
                                                            $editor = '<span class="label label-editor label-primary" title="เจ้าหน้าที่"><b><i class="fa fa-user-md"></i> '.$editor.'</b></span>';
                                                        }elseif($history->editor_type=='owner'){//แก้ไขโดยเจ้าของบัญชีเอง
                                                            $editor = '<span class="label label-editor label-info" title="ผู้ประกอบการ"><b><i class="fa fa-user"></i> เจ้าของบัญชี</b></span>';
                                                        }elseif(strpos($history->editor_type, "system:")===0){//แก้ไขโดยระบบ
                                                            $editor = '<span class="label label-editor label-success" title="ระบบ"><b><i class="mdi mdi-robot"></i> '.str_replace('system:', 'ระบบ ', $history->editor_type).'</b></span>';
                                                        }
                                                    }
                                                @endphp

                                                <tr valign="top">
                                                    <td valign="top" class="text-center"> {!! $key1 == 0 ? ($key +1).'.' : null !!}</td>
                                                    <td valign="top">{!! $key1 == 0 && !empty($history->created_at) ? HP::DateTimeThaiAndTime($history->created_at) : null !!}</td>
                                                    <td valign="top">{!! $key1 == 0 ? $editor : null !!}</td>
                                                    <td valign="top">{!! $key1 == 0 && !empty($history->remark) ? $history->remark : null !!}</td>
                                                    <td valign="top">{!! $item->DataFieldName ?? null !!}</td>
                                                    <td valign="top">
                                                        @if ($item->data_field == 'applicanttype_id')
                                                            {!! array_key_exists($item->data_old, $applicant_types) ? $applicant_types[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'contact_prefix_name')
                                                            {!! array_key_exists($item->data_old,$prefix) ? $prefix[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'date_of_birth' || $item->data_field == 'date_niti')  <!-- วันที่เกิด  , วันที่จดทะเบียน -->
                                                            {!! !empty($item->data_old) ? HP::revertDate($item->data_old,true) : null !!}
                                                        @elseif ($item->data_field == 'state' )  <!-- สถานะการยืนยันตัวตน -->
                                                            {!! array_key_exists($item->data_old, $state) ? $state[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'block' )  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_old, $block) ? $block[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'google2fa_status')  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_old, $enables) ? $enables[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'juristic_status')  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_old, $juristic_status_list) ? $juristic_status_list[$item->data_old] : null !!}
                                                        @elseif ($item->data_field == 'personfile')  <!-- ไฟล์แนบ -->

                                                            @php
                                                                $attach_count = 0;
                                                                $attachs = json_decode($item->data_old);
                                                            @endphp

                                                            @if (!empty($attachs))

                                                                @foreach ($attachs as $file)

                                                                    @php
                                                                        $file_url = HP::getFileStorage('media/com_user/'.$user->username.'/'.$file->realfile);
                                                                    @endphp

                                                                    @if(!empty($file_url))
                                                                        <a href="{{ $file_url }}" target="_blank">
                                                                            <i class="mdi mdi-file-pdf text-danger"></i> {{ $file->filename }}
                                                                        </a>
                                                                    @else
                                                                        <span title="ไม่มีไฟล์">
                                                                            <i class="mdi mdi-file-pdf text-danger"></i> {{ $file->filename }}
                                                                        </span>
                                                                    @endif

                                                                    @php
                                                                        $attach_count++;
                                                                    @endphp

                                                                @endforeach

                                                            @endif

                                                            @if($attach_count===0)
                                                                <i class="text-muted">ไม่มี</i>
                                                            @endif

                                                        @else
                                                            {!! $item->data_old ?? null !!}
                                                        @endif
                                                    </td>
                                                    <td valign="top">
                                                        @if ($item->data_field == 'applicanttype_id')
                                                            {!! array_key_exists($item->data_new, $applicant_types) ? $applicant_types[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'contact_prefix_name')
                                                            {!! array_key_exists($item->data_new, $prefix) ? $prefix[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'date_of_birth' || $item->data_field == 'date_niti')  <!-- วันที่เกิด , วันที่จดทะเบียน -->
                                                            {!! !empty($item->data_new) ? HP::revertDate($item->data_new,true) : null !!}
                                                        @elseif ($item->data_field == 'state')  <!-- สถานะการยืนยันตัวตน -->
                                                            {!! array_key_exists($item->data_new, $state) ? $state[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'block')  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_new, $block) ? $block[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'check_api')  <!-- เช็คข้อมูลจาก API -->
                                                            {!! array_key_exists((int)$item->data_new, $check_apis) ? $check_apis[(int)$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'google2fa_status')  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_new, $enables) ? $enables[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'juristic_status')  <!-- สถานะการใช้งาน -->
                                                            {!! array_key_exists($item->data_new, $juristic_status_list) ? $juristic_status_list[$item->data_new] : null !!}
                                                        @elseif ($item->data_field == 'personfile')  <!-- ไฟล์แนบ -->

                                                            @php
                                                                $attach_count = 0;
                                                                $attachs = json_decode($item->data_new);
                                                            @endphp

                                                            @if (!empty($attachs))

                                                                @foreach ($attachs as $file)

                                                                    @php
                                                                        $file_url = HP::getFileStorage('media/com_user/'.$user->username.'/'.$file->realfile);
                                                                    @endphp

                                                                    @if(!empty($file_url))
                                                                        <a href="{{ $file_url }}" target="_blank">
                                                                            <i class="mdi mdi-file-pdf text-danger"></i> {{ $file->filename }}
                                                                        </a>
                                                                    @else
                                                                        <span title="ไม่มีไฟล์">
                                                                            <i class="mdi mdi-file-pdf text-danger"></i> {{ $file->filename }}
                                                                        </span>
                                                                    @endif

                                                                    @php
                                                                        $attach_count++;
                                                                    @endphp

                                                                @endforeach

                                                            @endif

                                                            @if($attach_count===0)
                                                                <i class="text-muted">ไม่มี</i>
                                                            @endif

                                                        @else
                                                            {!! $item->data_new  ?? null !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                           @endforeach
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer ">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">ปิด</button>
                        </div>

                    </div>
                </div>
        </div>
        @endif
    </div>

</div>

<div class="col-md-6">

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
                        {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $trader_roles), ['class' => 'form-control']) !!}
                        <label for="roles">&nbsp;{{ $role->name }}</label>
                    </div>
                @endforeach

                </div>
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
  <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{ asset('plugins/components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    {{-- <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script> --}}
    <script>
         $(document).ready(function () {
            //    $('#myTable').DataTable( {
            //         dom: 'Brtip',
            //         pageLength:5,
            //         processing: true,
            //         lengthChange: false,
            //         ordering: false,
            //         order: [[ 0, "desc" ]]
            //     });

            //ซ่อน HTML ตามประเภท
            var applicanttype_id = '{{ isset($user) ? $user->applicanttype_id : '' }}';
            if(applicanttype_id==2){//บุคคลธรรมดา
                $('.input-birth').show();
                $('#box-branch, .input-niti').hide();
                $('#head-address').text('ที่อยู่ตามทะเบียนบ้าน');
            }else{ //ประเภทนอกเหนือจากบุคคลธรรมดา
                $('.input-birth').hide();
                $('#box-branch, .input-niti').show();
                $('#head-address').text('ที่ตั้งสำนักงานใหญ่');
            }
            if(applicanttype_id==4 || applicanttype_id==5){//เป็นส่วนราชการและอื่นๆให้แก้ไขชื่อได้
                $('#name').prop('disabled', false);
            }

            //เมื่อเปลี่ยนประเภทสาขา
            $('input[name="branch_type"]').change(function(event) {
                var branch_type = $('input[name="branch_type"]:checked').val();
                var branch_code_label = $('input[name="branch_code"]').parent().prev();
                var branch_code_input = $('input[name="branch_code"]');
                if(branch_type==1){
                    $(branch_code_label).hide();
                    $(branch_code_input).hide();
                    $(branch_code_input).val('');
                }else if(branch_type==2) {
                    $(branch_code_label).show();
                    $(branch_code_input).show();
                }
            });

            //ไม่ให้กดบันทึกซ้ำๆ
            $('form').submit(function() {
                $(this).find("button[type='submit']").prop('disabled', true);
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

            //เมื่อคีย์อีเมล
            $('#email').change(function(event) {
                var email_now = '{{ isset($user) ? $user->email : '' }}';
                var email     = $(this).val();
                if(email!=email_now && email!=''){
                    $.ajax({
                        url: "{!! url('sso/user-sso/checkemailexits') !!}",
                        type: 'POST',
                        data: { email: email, _token: '{{ csrf_token() }}' },
                    }).done(function(response) {
                        if(response == "already")
                        {
                            alert('อีเมล์ "' + email + '" ถูกใช้งานแล้ว');
                            $('#email').val('');
                        }
                    });
                }

            });

            @if($staff->isAdmin())
                //เปิดให้แก้ไขเฉพาะ Admin
                $('input[name="applicanttype_id"]').prop('disabled', false);
                $('#name').prop('disabled', false);
                $('input[name="branch_code"]').prop('disabled', false);
                $('input[name="branch_type"]').prop('disabled', false);
                $('input[name="state"]').prop('disabled', false);
                $('select[name="prefix_name"]').prop('disabled', false);
            @endif

            @if(in_array($user->applicanttype_id, ['4', '5']) || $user->state==3)
                //ถ้าเป็นส่วนราชการ หรืออื่นๆ
                $.ajax({
                    url: "{!! url('sso/user-sso/auto-edit-applicanttype') !!}",
                    type: 'POST',
                    data: { id: {{ $user->id }}, _token: '{{ csrf_token() }}' },
                }).done(function(response) {

                    if(typeof response=='object' && response.hasOwnProperty('operation')){
                        if(response.operation=='changed'){
                            Swal.fire({
                                title: 'แจ้งเพื่อทราบ! เรื่องเปลี่ยนแปลงประเภทการลงทะเบียน',
                                html: '<span class="font-20">'+response.msg+'</span>',
                                width: 1000,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'รับทราบ'
                            }).then((result) => {
                                window.location = ''; //รีเฟรชหน้านี้
                            });
                        }
                    }

                    if(typeof response=='object' && response.hasOwnProperty('status') && response.hasOwnProperty('message_list')){
                        if(response.status=='fail' && response.not_found_count!=3){

                            $('input[name="applicanttype_id"][value="{{ $user->applicanttype_id }}"]').prop('disabled', false);
                            $('input[name="applicanttype_id"][value="4"]').prop('disabled', false);
                            $('input[name="applicanttype_id"][value="5"]').prop('disabled', false);

                            let message_show = '';
                            $.each(response.message_list, function (index, message) { 
                                 message_show += '<div><div>'+message.service_name +'</div> '+message.msg+'<hr></div>';
                            });

                            Swal.fire({
                                title: 'เกิดข้อขัดข้องในการเชื่อมโยงข้อมูล',
                                html: '<span class="font-20">'+message_show+'</span>',
                                width: 1000,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'รับทราบ'
                            }).then((result) => {
                                //window.location = ''; //รีเฟรชหน้านี้
                            });
                        }
                    }

                });
            @endif

        });
    </script>
@endpush

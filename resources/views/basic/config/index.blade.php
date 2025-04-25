@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .note-group-select-from-files {
            display: none;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">ตั้งค่าระบบ</h3>

        <div class="clearfix"></div>
        <hr>

        <!-- Nav tabs -->
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-home"></i>
                  </span>
                  <span class="hidden-xs">ทั่วไป</span>
                </a>
            </li>
            {{-- <li role="presentation">
                <a href="#industry" aria-controls="industry" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-direction"></i>
                  </span>
                  <span class="hidden-xs">เชื่อมโยงกับกระทรวง</span>
                </a>
            </li> --}}
            <li role="presentation">
                <a href="#tisi" aria-controls="tisi" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-direction"></i>
                  </span>
                  <span class="hidden-xs">เชื่อมโยง API กลางสมอ.</span>
                </a>
            </li>
            <li role="presentation">
                <a href="#login" aria-controls="login" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-direction"></i>
                  </span>
                  <span class="hidden-xs">การ Login</span>
                </a>
            </li>
            {{-- <li role="presentation">
                <a href="#reCaptcha" aria-controls="reCaptcha" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-direction"></i>
                  </span>
                  <span class="hidden-xs">reCaptcha Google</span>
                </a>
            </li> --}}
            <li role="presentation">
                <a href="#rolse" aria-controls="rolse" role="tab" data-toggle="tab">
                    <span class="visible-xs">
                        <i class="ti-direction"></i>
                    </span>
                    <span class="hidden-xs">ตั้งค่าการลงทะเบียน</span>
                </a>
            </li>
            <li role="presentation">
                <a href="#sso" aria-controls="sso" role="tab" data-toggle="tab">
                    <span class="visible-xs">
                        <i class="ti-direction"></i>
                    </span>
                    <span class="hidden-xs">ตั้งค่าเชื่อม SSO</span>
                </a>
            </li>
            <li role="presentation">
              <a href="#digital_signing" aria-controls="digital_signing" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                      <i class="ti-direction"></i>
                  </span>
                  <span class="hidden-xs">ตั้งค่าเชื่อม สพร.</span>
              </a>
            </li>
            <li role="presentation">
                <a href="#api_check" aria-controls="api_check" role="tab" data-toggle="tab">
                    <span class="visible-xs">
                        <i class="ti-direction"></i>
                    </span>
                    <span class="hidden-xs">ตั้งค่าเช็คข้อมูลกับ API</span>
                </a>
           </li>
        </ul>

        {!! Form::model($config, ['url' => '/basic/config', 'class' => 'form-horizontal']) !!}

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade active in" id="home">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('url_register') ? 'has-error' : ''}}">
                        {!! Form::label('url_register', 'URL ลงทะเบียนเข้าใช้งานระบบ (เจ้าหน้าที่):', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('url_register', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('url_register', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('url_elicense_trader') ? 'has-error' : ''}}">
                        {!! Form::label('url_elicense_trader', 'URL สำหรับเข้าใช้งานระบบ e-License (ผู้ประกอบการ):', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('url_elicense_trader', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('url_elicense_trader', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('url_elicense_staff') ? 'has-error' : ''}}">
                        {!! Form::label('url_elicense_staff', 'URL สำหรับเข้าใช้งานระบบ e-License (เจ้าหน้าที่):', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('url_elicense_staff', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('url_elicense_staff', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('refresh_dashboard_value') ? 'has-error' : ''}}">
                        {!! Form::label('refresh_dashboard_value', 'ระยะเวลาที่รีเฟรชข้อมูลหน้า Dashboard:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-3">
                            {!! Form::text('refresh_dashboard_value', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('refresh_dashboard_unit', ['M'=>'นาที', 'H'=>'ชั่วโมง'], null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('refresh_dashboard_value', '<p class="help-block">:message</p>') !!}
                        {!! $errors->first('refresh_dashboard_unit', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="form-group required {{ $errors->has('refresh_notification') ? 'has-error' : ''}}">
                        {!! Form::label('refresh_notification', 'ระยะเวลาที่รีเฟรชข้อมูลแจ้งเตือน:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-3">
                            {!! Form::number('refresh_notification', null, ['class' => 'form-control', 'min' => '5', 'required' => true]) !!}
                        </div>
                        <div class="col-md-3">
                            วินาที
                        </div>
                        {!! $errors->first('refresh_dashboard_value', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4">Cookie Login (เจ้าหน้าที่)</label></label>
                        <div class="col-md-6">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_electronic_certificate') ? 'has-error' : ''}}">
                        {!! Form::label('check_electronic_certificate', 'เปิดใช้งานใบรับรองระบบงานแบบดิจิทัล:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {{ Form::checkbox('check_electronic_certificate', '1', 
                            !empty($config->check_electronic_certificate)  && $config->check_electronic_certificate == 1  ? true :  false,
                             ['class'=>'switch']) }}
                            {!! $errors->first('check_electronic_certificate', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('officer_name_cookie_login') ? 'has-error' : ''}}">
                        {!! Form::label('officer_name_cookie_login', 'ชื่อ Cookie ที่เก็บค่า Session Login:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('officer_name_cookie_login', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('officer_name_cookie_login', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('officer_domain_cookie_login') ? 'has-error' : ''}}">
                        {!! Form::label('officer_domain_cookie_login', 'Domain ของ Cookie ที่เก็บค่า Session Login:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('officer_domain_cookie_login', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('officer_domain_cookie_login', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            หมายเหตุ: หากต้องการแสดงผลในข้อมูลติดต่อสอบถาม
                        </label>
                        <div class="col-md-8">
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target=".modal-upload-file"><i class="fa fa-link"></i> แนบไฟล์</button>
                        </div>
                    </div>

                    @include('basic.config.modal-upload')

                    <div class="form-group {{ $errors->has('info_contact') ? 'has-error' : ''}}">
                        <div class="col-md-4">
                            {!! Form::label('info_contact', 'ข้อมูลติดต่อสอบถาม:', ['class' => 'control-label pull-right']) !!}
                            <div class="col-md-12 p-r-0">
                                <a href="{{ $config->url_sso.'contact' }}" class="pull-right" target="_blank" title="มีผลหลังบันทึกข้อมูลแล้ว">
                                    <u><i class="mdi mdi-link-variant"></i> หน้าแสดงผล</u>
                                </a>
                            </div>

                        </div>
                        <div class="col-md-6">
                            {!! Form::textarea('info_contact', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('info_contact', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>
            </div>

            {{-- <div role="tabpanel" class="tab-pane fade" id="industry">
                <div class="col-md-12">

                  <div class="form-group {{ $errors->has('industry_auth_url') ? 'has-error' : ''}}">
                    {!! Form::label('industry_auth_url', 'URL Authentication:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_auth_url', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_auth_url', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('industry_client_id') ? 'has-error' : ''}}">
                    {!! Form::label('industry_client_id', 'ClientID:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_client_id', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_client_id', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('industry_client_secret') ? 'has-error' : ''}}">
                    {!! Form::label('industry_client_secret', 'ClientSecret:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_client_secret', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_client_secret', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('industry_juristic_url') ? 'has-error' : ''}}">
                    {!! Form::label('industry_juristic_url', 'URL เรียกข้อมูลนิติบุคคล:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_juristic_url', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_juristic_url', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('industry_personal_url') ? 'has-error' : ''}}">
                    {!! Form::label('industry_personal_url', 'URL เรียกข้อมูลบุคคลธรรมดา:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_personal_url', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_personal_url', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('active_check_iindustry') ? 'has-error' : ''}}">
                    {!! Form::label('active_check_iindustry', 'ตรวจสอบการลงทะเบียนใน i-industry:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      <label>{!! Form::radio('active_check_iindustry', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
                      <label>{!! Form::radio('active_check_iindustry', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

                      {!! $errors->first('active_check_iindustry', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('industry_ijuristicid_url') ? 'has-error' : ''}}">
                    {!! Form::label('industry_ijuristicid_url', 'URL เช็คการลงทะเบียน i industry:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('industry_ijuristicid_url', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('industry_ijuristicid_url', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                </div>
            </div> --}}

            <div role="tabpanel" class="tab-pane fade" id="tisi">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('tisi_api_corporation_url') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_corporation_url', 'URL ดึงข้อมูลนิติบุคคล:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_corporation_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_corporation_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('tisi_api_person_url') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_person_url', 'URL ดึงข้อมูลบุคคล:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_person_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_person_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('tisi_api_house_url') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_house_url', 'URL ดึงข้อมูลทะเบียนบ้าน:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_house_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_house_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('tisi_api_factory_url') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_factory_url', 'URL ดึงข้อมูลทะเบียนโรงงาน:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_factory_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_factory_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('tisi_api_factory_url2') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_factory_url2', 'URL ดึงข้อมูลทะเบียนโรงงาน (ค้นจากเลขทะเบียนเดิมได้):', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_factory_url2', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_factory_url2', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('tisi_api_faculty_url') ? 'has-error' : ''}}">
                        {!! Form::label('tisi_api_faculty_url', 'URL ดึงข้อมูลคณะบุคคล:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('tisi_api_faculty_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tisi_api_faculty_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="login">
                <div class="col-md-12">

                    <div class="form-group">
                        <label class="control-label col-md-5">ไซต์ SSO ผปก.</label></label>
                        <div class="col-md-6">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_login_fail_amount') ? 'has-error' : ''}} required">
                        {!! Form::label('sso_login_fail_amount', 'จำนวนครั้งที่กรอกผิดแล้วให้ระบบ Lock ไม่ให้ใช้งาน:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::number('sso_login_fail_amount', null, ['class' => 'form-control', 'min' => 0, 'required' => true]) !!}
                           {!! $errors->first('sso_login_fail_amount', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_login_fail_lock_time') ? 'has-error' : ''}} required">
                        {!! Form::label('sso_login_fail_lock_time', 'เวลาที่ให้ Lock ไว้(นาที):', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::number('sso_login_fail_lock_time', null, ['class' => 'form-control', 'min' => 1, 'required' => true]) !!}
                           {!! $errors->first('sso_login_fail_lock_time', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>
            </div>

            {{-- <div role="tabpanel" class="tab-pane fade" id="reCaptcha">
                <div class="col-md-12">

                  <div class="form-group {{ $errors->has('recaptcha_site_key') ? 'has-error' : ''}}">
                    {!! Form::label('recaptcha_site_key', 'คีย์ของเว็บไซต์:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('recaptcha_site_key', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('recaptcha_site_key', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('recaptcha_secret_key') ? 'has-error' : ''}}">
                    {!! Form::label('recaptcha_secret_key', 'คีย์ลับ:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                      {!! Form::text('recaptcha_secret_key', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('recaptcha_secret_key', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                </div>
            </div> --}}

            <div role="tabpanel" class="tab-pane fade" id="rolse">
              <div class="col-md-12">

                <div class="form-group {{ $errors->has('recaptcha_site_key') ? 'has-error' : ''}}">
                  {!! Form::label('recaptcha_site_key', 'สิทธิ์ลงทะเบียนระบบ e-Surveillance:', ['class' => 'col-md-4 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::select('esurv[]',
                      App\Role::where('label','trader')->pluck('name','id'),
                      $esurv,
                     ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือก e-Surveillance -']) !!}
                    {!! $errors->first('department_type_id', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="form-group {{ $errors->has('recaptcha_site_key') ? 'has-error' : ''}}">
                  {!! Form::label('recaptcha_site_key', 'สิทธิ์ลงทะเบียนระบบ e-Accreditation:', ['class' => 'col-md-4 control-label']) !!}
                  <div class="col-md-6">
                     {!! Form::select('certify[]',
                     App\Role::where('label','trader')->pluck('name','id'),
                     $certify,
                      ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือก e-Accreditation -']) !!}
                      {!! $errors->first('department_type_id', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="form-group {{ $errors->has('faculty_title_allow') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('faculty_title_allow', 'คำนำหน้าผู้ประกอบการ (vTitleName) <br>ที่มาจาก API กรมสรรพากร ที่จัดเป็นคณะบุคคล:', ['class' => 'col-md-4 control-label'])) !!}
                    <div class="col-md-6">
                        {!! Form::text('faculty_title_allow', null, ['class' => 'form-control', 'data-role' => "tagsinput"]) !!}
                        {!! $errors->first('faculty_title_allow', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

              </div>
          </div>

            <div role="tabpanel" class="tab-pane fade" id="sso">
                <div class="col-md-12">

                    <div class="form-group {{ $errors->has('sso_google2fa_status') ? 'has-error' : ''}}">
                        {!! Form::label('sso_google2fa_status', 'Google Authenticator ในไซต์ SSO (ผปก.):', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">

                            <div class="radio radio-danger pull-left ">
                                {!! Form::radio('sso_google2fa_status', 0, null, ['id' => 'sso_google2fa_status0']) !!}
                                <label for="sso_google2fa_status0">ปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-purple pull-left m-l-5">
                                {!! Form::radio('sso_google2fa_status', 1, null, ['id' => 'sso_google2fa_status1']) !!}
                                <label for="sso_google2fa_status1">เปิดใช้งาน (ไม่บังคับ)</label>
                            </div>

                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('sso_google2fa_status', 2, null, ['id' => 'sso_google2fa_status2']) !!}
                                <label for="sso_google2fa_status2">เปิดใช้งาน (บังคับทุกคน)</label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('url_sso') ? 'has-error' : ''}}">
                        {!! Form::label('url_sso', 'URL Single Sign On (ผปก.):', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('url_sso', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('url_sso', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-5">Cookie Login</label></label>
                        <div class="col-md-7">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_name_cookie_login') ? 'has-error' : ''}}">
                        {!! Form::label('sso_name_cookie_login', 'ชื่อ Cookie ที่เก็บค่า Session การ Login:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_name_cookie_login', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_name_cookie_login', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_domain_cookie_login') ? 'has-error' : ''}}">
                        {!! Form::label('sso_domain_cookie_login', 'Domain ของ Cookie ที่เก็บค่า Session การ Login:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_domain_cookie_login', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_domain_cookie_login', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-5">สำหรับไซต์ e-Surveillance เชื่อมไป SSO</label>
                        <div class="col-md-7">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_esurveillance_app_name') ? 'has-error' : ''}}">
                        {!! Form::label('sso_esurveillance_app_name', 'app_name:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_esurveillance_app_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_esurveillance_app_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_esurveillance_app_secret') ? 'has-error' : ''}}">
                        {!! Form::label('sso_esurveillance_app_secret', 'app_secret:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_esurveillance_app_secret', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_esurveillance_app_secret', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-5">สำหรับไซต์ e-Accreditation เชื่อมไป SSO</label>
                        <div class="col-md-7">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_eaccreditation_app_name') ? 'has-error' : ''}}">
                        {!! Form::label('sso_eaccreditation_app_name', 'app_name:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_eaccreditation_app_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_eaccreditation_app_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_eaccreditation_app_secret') ? 'has-error' : ''}}">
                        {!! Form::label('sso_eaccreditation_app_secret', 'app_secret:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_eaccreditation_app_secret', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_eaccreditation_app_secret', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-5">สำหรับไซต์ Law เชื่อมไป SSO</label>
                        <div class="col-md-7">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_law_app_name') ? 'has-error' : ''}}">
                        {!! Form::label('sso_law_app_name', 'app_name:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_law_app_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_law_app_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_law_app_secret') ? 'has-error' : ''}}">
                        {!! Form::label('sso_law_app_secret', 'app_secret:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_law_app_secret', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_law_app_secret', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-5">สำหรับระบบขึ้นทะเบียนตามมาตรา 5</label>
                        <div class="col-md-7">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('sso_section5_app_name') ? 'has-error' : ''}}">
                        {!! Form::label('sso_section5_app_name', 'app_name:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sso_section5_app_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('sso_section5_app_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="digital_signing">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('digital_signing_api_token') ? 'has-error' : ''}}">
                        {!! Form::label('digital_signing_api_token', 'API ขอ Token:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('digital_signing_api_token', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('digital_signing_api_token', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_api_document_id') ? 'has-error' : ''}}">
                        {!! Form::label('digital_signing_api_document_id', 'API ขอ DocumentID:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('digital_signing_api_document_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('digital_signing_api_document_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_api_esgnatures') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_api_esgnatures', 'API ลงลายมือชื่ออิเล็กทรอนิกส์:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_api_esgnatures', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_api_esgnatures', '<p class="help-block">:message</p>') !!}
                      </div>
                   </div>

                   <div class="form-group {{ $errors->has('digital_signing_api_downlaod_signed') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_api_downlaod_signed', 'API Downlaod PDF Signed:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_api_downlaod_signed', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_api_downlaod_signed', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>
                    <div class="form-group {{ $errors->has('digital_signing_api_revoked') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_api_revoked', 'API เพิกถอนการใช้งานเอกสาร:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_api_revoked', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_api_revoked', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>
                    <div class="form-group {{ $errors->has('digital_signing_api_attachment') ? 'has-error' : ''}}">
                        {!! Form::label('digital_signing_api_attachment', 'API Upload เอกสารแนบ:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('digital_signing_api_attachment', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('digital_signing_api_attachment', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div>

                    <div class="form-group {{ $errors->has('digital_signing_consumer_secret') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_consumer_secret', 'ConsumerSecret:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_consumer_secret', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_consumer_secret', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_agent_id') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_agent_id', 'AgentID:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_agent_id', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_agent_id', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_consumer_key') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_consumer_key', 'Consumer-Key:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_consumer_key', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_consumer_key', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_cb') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_cb', 'ห้องหน่วยรับรอง:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_cb', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_cb', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_ib') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_ib', 'หน่วยตรวจสอบ:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_ib', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_ib', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                    <div class="form-group {{ $errors->has('digital_signing_lab') ? 'has-error' : ''}}">
                      {!! Form::label('digital_signing_lab', 'ห้องปฏิบัติการ:', ['class' => 'col-md-5 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('digital_signing_lab', null, ['class' => 'form-control']) !!}
                          {!! $errors->first('digital_signing_lab', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>

                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="api_check">
                <div class="col-md-12">

                    <div class="form-group m-b-0">
                        <label class="control-label col-md-6"><b>ตรวจติดตามออนไลน์ e-Surveillance</b></label>
                        <div class="col-md-6">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_asurv_accept_export') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_asurv_accept_export', 'ระบบรับคำขอการทำผลิตภัณฑ์เพื่อส่งออก (20 ตรี):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept_export', 1, null, ['id' => 'check_api_asurv_accept_export1']) !!}
                                <label for="check_api_asurv_accept_export1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept_export', 0, null, ['id' => 'check_api_asurv_accept_export0']) !!}
                                <label for="check_api_asurv_accept_export0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_asurv_accept_import') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_asurv_accept_import', 'ระบบรับคำขอการทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (20 ทวิ):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept_import', 1, null, ['id' => 'check_api_asurv_accept_import1']) !!}
                                <label for="check_api_asurv_accept_import1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept_import', 0, null, ['id' => 'check_api_asurv_accept_import0']) !!}
                                <label for="check_api_asurv_accept_import0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_asurv_accept21_export') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_asurv_accept21_export', 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อส่งออก (21 ตรี):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21_export', 1, null, ['id' => 'check_api_asurv_accept21_export1']) !!}
                                <label for="check_api_asurv_accept21_export1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21_export', 0, null, ['id' => 'check_api_asurv_accept21_export0']) !!}
                                <label for="check_api_asurv_accept21_export0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_asurv_accept21_import') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_asurv_accept21_import', 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21_import', 1, null, ['id' => 'check_api_asurv_accept21_import1']) !!}
                                <label for="check_api_asurv_accept21_import1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21_import', 0, null, ['id' => 'check_api_asurv_accept21_import0']) !!}
                                <label for="check_api_asurv_accept21_import0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_asurv_accept21own_import') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_asurv_accept21own_import', 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อนำเข้ามาใช้เอง (21):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21own_import', 1, null, ['id' => 'check_api_asurv_accept21own_import1']) !!}
                                <label for="check_api_asurv_accept21own_import1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_asurv_accept21own_import', 0, null, ['id' => 'check_api_asurv_accept21own_import0']) !!}
                                <label for="check_api_asurv_accept21own_import0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-b-0">
                        <label class="control-label col-md-6"><b>รับรองระบบงาน e-Accreditation</b></label>
                        <div class="col-md-6">
                            <p class="form-control-static">&nbsp;</p>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_certify_check_certificate') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_certify_check_certificate', 'ระบบตรวจสอบคำขอรับใบรับรองห้องปฏิบัติการ (LAB):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate', 1, null, ['id' => 'check_api_certify_check_certificate1']) !!}
                                <label for="check_api_certify_check_certificate1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate', 0, null, ['id' => 'check_api_certify_check_certificate0']) !!}
                                <label for="check_api_certify_check_certificate0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_certify_check_certificate_ib') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_certify_check_certificate_ib', 'ระบบตรวจสอบคำขอรับใบรับรองหน่วยตรวจ (IB):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate_ib', 1, null, ['id' => 'check_api_certify_check_certificate_ib1']) !!}
                                <label for="check_api_certify_check_certificate_ib1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate_ib', 0, null, ['id' => 'check_api_certify_check_certificate_ib0']) !!}
                                <label for="check_api_certify_check_certificate_ib0">ไม่เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('check_api_certify_check_certificate_cb') ? 'has-error' : ''}}">
                        {!! Form::label('check_api_certify_check_certificate_cb', 'ระบบตรวจสอบคำขอรับหน่วยรับรอง (CB):', ['class' => 'col-md-6 control-label']) !!}
                        <div class="col-md-6">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate_cb', 1, null, ['id' => 'check_api_certify_check_certificate_cb1']) !!}
                                <label for="check_api_certify_check_certificate_cb1">เปิดใช้งาน</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('check_api_certify_check_certificate_cb', 0, null, ['id' => 'check_api_certify_check_certificate_cb0']) !!}
                                <label for="check_api_certify_check_certificate_cb0">ไม่เปิดใช้งาน</label>
                            </div>
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
                @can('view-'.str_slug('config_term'))
                    <a class="btn btn-default" href="{{ url()->previous() }}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<!-- icheck -->
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- summernote -->
<script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<!-- tagsinput -->
<script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function() {

        @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        @endif
    // Switchery
    $(".switch").each(function() {
      new Switchery($(this)[0], {color: '#13dafe'})
    });



        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });

        console.log($('.js-switch').length);

        //ข้อมูลการติดต่อ
        $('#info_contact').summernote({
            placeholder: 'กรอกข้อมูลที่นี่.....',
            fontNames: ['Lato', 'Arial', 'Courier New'],
            height: 300
        });

        $('.note-btn ').click(function (e) {

            var title = $(this).data('original-title');
            if( title == "Picture"){

            }

        });

        $('#btn_upload_file').click(function (e) {

            var upload_file = $('#upload_file').prop('files')[0];

            if( upload_file != '' && upload_file != 'undefined' && upload_file != undefined ){
                var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('file', $('#upload_file')[0].files[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ url('/basic/config/upload_file') }}",
                    datatype: "script",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (msg) {
                        if (msg == "success") {

                            $('.fileinput').fileinput('clear')

                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            GetFile();

                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'บันทึกไม่สำเร็จ',
                                showConfirmButton: true,
                                // timer: 1500
                            });

                            GetFile();
                        }
                    }
                });
            }else{
                alert('กรุณาเลือกไฟล์');
            }


        });
        GetFile();

        $('body').on('click', '.btn_copy', function(){

            var txt =  $(this).text();
            navigator.clipboard.writeText( txt );
            Swal.fire({
                        icon: 'success',
                        title: 'Copy',
                        showConfirmButton: false,
                        timer: 1000
                    });
        });


        $('body').on('click', '.btn_delete_file', function(){

            var id =  $(this).val();

            $.ajax({
                url: '{{ url('basic/config/delete-file') }}'+ '/'+ id,
                type: 'GET',
                // dataType: 'json',
                cache: false,
                success: function(data) {

                    if( data == 'success' ){

                        Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ',
                                showConfirmButton: false,
                                timer: 1500
                            });

                        GetFile();
                    }else{
                        Swal.fire({
                                icon: 'error',
                                title: 'ลบไม่สำเร็จ',
                                showConfirmButton: true,
                                // timer: 1500
                            });
                    }

                }
            });
        });


    });

    function GetFile(){
            //
        $('#box_file').html('');

        $.ajax({
            url: '{{ url('basic/config/get-file') }}',
            type: 'GET',
            // dataType: 'json',
            cache: false,
            success: function(data) {

                $('#box_file').html(data);

            }
        });
    }



</script>

@endpush

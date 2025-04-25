@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">คณะกรรมการ {{ $board->id }}</h3>
                    @can('view-'.str_slug('board'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    @push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
<style type="text/css">
  .img{
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
  }
</style>
@endpush

    <!-- Nav tabs -->
    <ul class="nav customtab nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">
              <span class="visible-xs">
                <i class="ti-home"></i>
              </span>
              <span class="hidden-xs">ข้อมูลส่วนตัว</span>
            </a>
        </li>
        <li role="presentation">
          <a href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false">
            <span class="visible-xs">
              <i class="ti-user"></i>
            </span>
            <span class="hidden-xs">สาขา/หน่วยงาน</span>
          </a>
        </li>
        <li role="presentation">
          <a href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false">
            <span class="visible-xs">
              <i class="ti-email"></i>
            </span>
            <span class="hidden-xs">บัญชีธนาคาร</span>
          </a>
        </li>
    </ul>

        {!! Form::model($board, [
                        'method' => 'PATCH',
                        'url' => ['/tis/board', $board->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="home1">
            <div class="col-md-9">

              <div class="form-group {{ $errors->has('prefix_name') ? 'has-error' : ''}}">
                {!! Form::label('prefix_name', 'คำนำหน้าชื่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('prefix_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('prefix_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                {!! Form::label('first_name', 'ชื่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('first_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                {!! Form::label('last_name', 'นามสกุล :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('last_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                {!! Form::label('birth_date', 'วันเกิด :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('birth_date', null, ['class' => 'form-control mydatepicker']) !!}
                  {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('identity_number') ? 'has-error' : ''}}">
                {!! Form::label('identity_number', 'เลขบัตรประชาชน :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('identity_number', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('identity_number', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('qualification') ? 'has-error' : ''}}">
                {!! Form::label('qualification', 'คุณวุฒิ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('qualification', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('qualification', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('institute') ? 'has-error' : ''}}">
                {!! Form::label('institute', 'สถาบันศึกษา :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('institute', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('institute', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('contact') ? 'has-error' : ''}}">
                {!! Form::label('contact', 'สถานที่ติดต่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                  {!! Form::textarea('contact', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=>'5'] : ['class' => 'form-control', 'rows'=>'5']) !!}
                  {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
                {!! Form::label('tel', 'เบอร์โทรศัพท์มือถือ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('tel', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'E-mail :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::email('email', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

            </div>
            <div class="col-md-3">

              @php $picture = json_decode(@$board->picture); @endphp

              <div class="col-sm-12 text-center">

                <div id="image-show" class="col-sm-12" style="margin-bottom:5px;">

                  @if(@$picture->file_name!='' && HP::checkFileStorage($attach_path.@$picture->croppied))
                    <img src="{{ HP::getFileStorage($attach_path.$picture->croppied) }}" class="img" />
                  @else
                    <img src="{{ asset('plugins/images/user-placeholder.jpg') }}" class="img" />
                  @endif
                </div>

  							<div id="upload-demo" class="col-sm-12 hide"></div>
  						</div>

              <div class="col-sm-12 text-center">

  								{{-- <span class="btn btn-default btn-file">
  									<span class="fileinput-new"><i class="fa fa-camera"></i> เลือกภาพ</span>
  									<span class="fileinput-exists"></span>
  									<input id="upload" name="picture_origin" type="file" class="form-control" accept=".gif, .jpg, .png, .jpeg"/>
  								</span> --}}

              </div>

              <input id="croppied" name="picture_croppied" type="hidden"/>
              <input id="top" name="pic[top]" type="hidden"/>
              <input id="left" name="pic[left]" type="hidden"/>
              <input id="bottom" name="pic[bottom]" type="hidden"/>
              <input id="right" name="pic[right]" type="hidden"/>
              <input id="zoom" name="pic[zoom]" type="hidden"/>

            </div>
            <div class="clearfix"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="profile1">
            <div class="col-md-12">

                <div class="form-group {{ $errors->has('board_type_ids[]') ? 'has-error' : ''}}">
                  {!! Form::label('board_type_ids[]', 'ประเภทของคณะกรรมการ', ['class' => 'col-md-4 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::select('board_type_ids[]', App\Models\Basic\BoardType::pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder'=>'- เลือกประเภทของคณะกรรมการ -']) !!}
                    {!! $errors->first('board_type_ids[]', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="form-group {{ $errors->has('product_group_ids[]') ? 'has-error' : ''}}">
                  {!! Form::label('product_group_ids[]', 'กลุ่มผลิตภัณฑ์/สาขา', ['class' => 'col-md-4 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::select('product_group_ids[]', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                    {!! $errors->first('product_group_ids[]', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="clearfix"></div>
                <div class="clearfix"></div>

                <div class="col-md-12" id="work-box">

                  @foreach ($works as $key => $work)

                    <div class="row work-item">

                      <div class="col-md-2">
                        {!! Form::label('positions[]', ($key+1).'.ตำแหน่ง', ['class' => 'control-label pull-right']) !!}
                      </div>
                      <div class="col-md-10">
                        <div class="row">
                          <div class="col-md-8">
                            {!! Form::text('positions[]', $work->position, ['class' => 'form-control', 'placeholder'=>'กรอกชื่อตำแหน่ง']) !!}
                          </div>
                          {!! $errors->first('positions[]', '<p class="help-block">:message</p>') !!}
                          <div class="col-md-4">

                            {{-- @if($key==0)
                              <button class="btn btn-success pull-right btn-sm" id="work-add" type="button">
                                  <i class="icon-plus"></i> เพิ่ม
                              </button>
                            @else
                              <button class="btn btn-danger pull-right btn-sm work-remove" type="button">
                                  <i class="icon-close"></i> ลบ
                              </button>
                            @endif --}}

                          </div>
                        </div>
                      </div>

                      <br class="clearfix">
                      <br class="clearfix">

                      {{-- <div class="col-md-2"></div> --}}
                      <div class="col-md-12">
                        <div class="white-box">
                          <div class="row">

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('department_ids[]') ? 'has-error' : ''}}">
                                {!! Form::label('department_ids[]', 'หน่วยงาน/บริษัท', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::select('department_ids[]', App\Models\Basic\Department::pluck('title', 'id'), $work->department_id, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงาน -']) !!}
                                  {!! $errors->first('department_ids[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('responsibles[]') ? 'has-error' : ''}}">
                                {!! Form::label('responsibles[]', 'หน้าที่รับผิดชอบ', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('responsibles[]', $work->responsible, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('responsibles[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('abodes[]') ? 'has-error' : ''}}">
                                {!! Form::label('abodes[]', 'สำนัก/กอง/ศูนย์', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('abodes[]', $work->abode, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('abodes[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('experiences[]') ? 'has-error' : ''}}">
                                {!! Form::label('experiences[]', 'ประสบการณ์ (ปี)', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('experiences[]', $work->experience, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('experiences[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('belong_tos[]') ? 'has-error' : ''}}">
                                {!! Form::label('belong_tos[]', 'สังกัดกรม', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('belong_tos[]', $work->belong_to, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('belong_tos[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('phones[]') ? 'has-error' : ''}}">
                                {!! Form::label('phones[]', 'โทรศัพท์', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('phones[]', $work->phone, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('phones[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('ministrys[]') ? 'has-error' : ''}}">
                                {!! Form::label('ministrys[]', 'กระทรวง', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('ministrys[]', $work->ministry, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('ministrys[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              <div class="form-group {{ $errors->has('faxs[]') ? 'has-error' : ''}}">
                                {!! Form::label('faxs[]', 'โทรสาร', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                  {!! Form::text('faxs[]', $work->fax, ['class' => 'form-control', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                  {!! $errors->first('faxs[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div>

                            </div>

                            <div class="col-md-6">

                              {{-- <div class="form-group {{ $errors->has('status[]') ? 'has-error' : ''}}">
                                {!! Form::label('status[0]', 'สถานะ', ['class' => 'col-md-5 control-label']) !!}
                                <div class="col-md-7">
                                  <label>{!! Form::radio('status['.$key.']', '1', (int)$work->status=='1'?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
                                  <label>{!! Form::radio('status['.$key.']', '0', (int)$work->status=='0'?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
                                  {!! $errors->first('status[]', '<p class="help-block">:message</p>') !!}
                                </div>
                              </div> --}}

                            </div>


                          </div>

                        </div>
                      </div>

                    </div>
                  @endforeach
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="messages1">
            <div class="col-md-12">

              <div class="form-group {{ $errors->has('bank_account') ? 'has-error' : ''}}">
                {!! Form::label('bank_account', 'เลขที่บัญชีธนาคาร', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('bank_account', null, ['class' => 'form-control']) !!}
                  {!! $errors->first('bank_account', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('bank_name') ? 'has-error' : ''}}">
                {!! Form::label('bank_name', 'ชื่อธนาคาร', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('bank_name', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('bank_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('bank_branch') ? 'has-error' : ''}}">
                {!! Form::label('bank_branch', 'สาขาธนาคาร', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('bank_branch', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('bank_branch', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('type_account') ? 'has-error' : ''}}">
                {!! Form::label('type_account', 'ประเภทบัญชี', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::select('type_account', HP::BankAccounts(), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทบัญชี -']) !!}
                  {!! $errors->first('type_account', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>

 {!! Form::close() !!}

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- input calendar -->
<script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<!-- Crop Image -->
<script src="{{ asset('js/croppie.js') }}"></script>

<script type="text/javascript">

    var $uploadCrop;

    $(document).ready(function() {

      $('input,select,textarea').attr('disabled','disabled');

        //ปฎิทิน
        $('.mydatepicker').datepicker({
          autoclose: true,
          todayHighlight: true,
          format: 'dd/mm/yyyy'
        });

        //เพิ่มตำแหน่งงาน
        $('#work-add').click(function() {

          $('#work-box').children(':first').clone().appendTo('#work-box'); //Clone Element

          var last_new = $('#work-box').children(':last');

          //Clear value text
          $(last_new).find('input[type="text"]').val('');

          //Clear value select
          $(last_new).find('select').val('');
          $(last_new).find('select').prev().remove();
          $(last_new).find('select').removeAttr('style');
          $(last_new).find('select').select2();

          //Clear Radio
          $(last_new).find('.check').each(function(index, el) {
            $(el).prependTo($(el).parent().parent());
            $(el).removeAttr('style');
            $(el).parent().find('div').remove();
            $(el).iCheck();
            $(el).parent().addClass($(el).attr('data-radio'));
          });

          //Change Button
          $(last_new).find('button').removeClass('btn-success');
          $(last_new).find('button').addClass('btn-danger work-remove');
          $(last_new).find('button').html('<i class="icon-close"></i> ลบ');

          resetOrder();

        });

        //ลบตำแหน่ง
        $('body').on('click', '.work-remove', function() {

          $(this).parent().parent().parent().parent().remove();

          resetOrder();

        });

        //Crop image
        $uploadCrop = $('#upload-demo').croppie({

    			enableExif: true,

    			viewport: {

    				width: 140,

    				height: 140,

    			},

    			boundary: {

    				width: 200,

    				height: 200

    			}

    		});

        $('#upload').on('change', function () {

          $('#upload-demo').removeClass('hide');
          $('#image-show').addClass('hide');

    			var reader = new FileReader();

    			reader.onload = function (e) {

    				$uploadCrop.croppie('bind', {

    					url: e.target.result

    				}).then(function(){

    					console.log('jQuery bind complete');

    				});

    			}

    			reader.readAsDataURL(this.files[0]);

    		});

        $('#form-save').click(function(event) {

           //เลื่อนมาแถบแรก
            $('.tab-pane').removeClass('active in');
            $('#home1').addClass('active in');

            //คัดลอกข้อมูลภาพที่ Crop
            CropFile();

        });

    });

    function resetOrder(){//รีเซตลำดับของตำแหน่ง

      $('#work-box').children().each(function(index, el) {
        $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
        $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
      });

    }

    function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

      var croppied = $uploadCrop.croppie('get');

      $('#top').val(croppied.points[1]);
      $('#left').val(croppied.points[0]);
      $('#bottom').val(croppied.points[3]);
      $('#right').val(croppied.points[2]);
      $('#zoom').val(croppied.zoom);

			$uploadCrop.croppie('result', {

				type: 'canvas',

				size: 'viewport'

			}).then(function (resp) {

        $('#croppied').val(resp);

			});
		}
</script>

@endpush


                </div>
            </div>
        </div>
    </div>

@endsection

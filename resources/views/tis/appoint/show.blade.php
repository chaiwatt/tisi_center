@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดการแต่งตั้งคณะกรรมการ {{ $appoint->id }}</h3>
                    @can('view-'.str_slug('appoint'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                   @push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
@endpush

  {!! Form::model($appoint, [
                        'method' => 'PATCH',
                        'url' => ['/tis/appoint', $appoint->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

<div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
    {!! Form::label('product_group_id', 'สาขาผลิตภัณฑ์ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('product_group_id', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -'] : ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
        {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('command') ? 'has-error' : ''}}">
    {!! Form::label('command', 'คำสั่งที่ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('command', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('command', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('command_type') ? 'has-error' : ''}}">
    {!! Form::label('command_type', 'ประเภทคำสั่ง :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('command_type', ['1'=>'คำสั่งกระทรวงอุตสาหกรรม', '2'=>'คำสั่งคณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรม', '3'=>'คำสั่งคณะกรรมการวิชาการรายสาขา'], null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'- เลือกประเภทคำสั่ง -'] : ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทคำสั่ง -']) !!}
        {!! $errors->first('command_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
    {!! Form::label('subject', 'เรื่อง :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('subject', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('board_position') ? 'has-error' : ''}}">
    {!! Form::label('board_position', 'คณะที่ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('board_position', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('board_position', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อคณะ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('board_type_id') ? 'has-error' : ''}}">
    {!! Form::label('board_type_id', 'ประเภทคณะกรรมการ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('board_type_id', App\Models\Basic\BoardType::pluck('title', 'id'), null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'- เลือกประเภทคณะกรรมการ -'] : ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทคณะกรรมการ -']) !!}
        {!! $errors->first('board_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
  {!! Form::label('attach', '&nbsp;', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-8">&nbsp;</div>
</div>

<div id="board-box">

    @foreach ((array)$department_sets as $key => $department_set)

      <div class="board_item">
        <h5 style="margin-left: 90px">ชุดที่ {{ ($key)?$key+1:'1' }}</h5>
        {!! Form::hidden('department_set[]', ($key)?$key+1:'1') !!}
        @php
            $style_show = ($department_set['appoint_department_ids'] && $department_set['appoint_department_ids'][0]==NULL)?"display:none":'';
        @endphp
      <div class="form-group {{ $errors->has('appoint_department_id') ? 'has-error' : ''}} appoint_department" style="{{$style_show}}">
          {!! Form::label('appoint_department_id', 'หน่วยงานสำหรับแต่งตั้งกรรมการ :', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-6">
              {!! Form::select('appoint_department_ids[]', App\Models\Basic\AppointDepartment::pluck('title', 'id'), ($department_set && $department_set['appoint_department_ids'])?$department_set['appoint_department_ids']:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'- เลือกหน่วยงานสำหรับแต่งตั้งกรรมการ -', 'id'=>'appoint_department_id'] : ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงานสำหรับแต่งตั้งกรรมการ -', 'id'=>'appoint_department_id']) !!}
              {!! $errors->first('appoint_department_id', '<p class="help-block">:message</p>') !!}
          </div>

      </div>

      <div class="form-group {{ $errors->has('board_id') ? 'has-error' : ''}}">
          {!! Form::label('board_id', 'รายชื่อคณะกรรมการ :', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-6">
              {!! Form::select('board_ids['.$key.'][]', App\Models\Tis\Board::get()->pluck('full_name', 'id'), $department_set['board_ids'], ('' == 'required') ? ['multiple'=>'multiple', 'required' => 'required', 'data-placeholder'=>'- เลือกรายชื่อคณะกรรมการ -', 'id'=>'board_id'] : ['multiple'=>'multiple', 'data-placeholder'=>'- เลือกรายชื่อคณะกรรมการ -', 'id'=>'board_id']) !!}
              {!! $errors->first('board_id', '<p class="help-block">:message</p>') !!}
          </div>

          <div class="col-md-2">&nbsp;</div>
      </div>
      </div>

    @endforeach

</div>

<div class="form-group {{ $errors->has('secretary') ? 'has-error' : ''}}">
    {!! Form::label('secretary', 'ชื่อเลขานุการ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('secretary', null, ['class' => 'form-control', 'data-role'=>'tagsinput', 'placeholder'=>'กรอกชื่อเลขานุการ']) !!}
        {!! $errors->first('secretary', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('secretary_assistant') ? 'has-error' : ''}}">
    {!! Form::label('secretary_assistant', 'ชื่อผู้ช่วยเลขานุการ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('secretary_assistant', null, ['class' => 'form-control', 'data-role'=>'tagsinput', 'placeholder'=>'กรอกชื่อผู้ช่วยเลขานุการ']) !!}
        {!! $errors->first('secretary_assistant', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('publish_date') ? 'has-error' : ''}}">
    {!! Form::label('publish_date', 'วันที่ประกาศ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('publish_date', null, ('' == 'required') ? ['class' => 'form-control datepicker', 'required' => 'required', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"] : ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
        {!! $errors->first('publish_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
  {!! Form::label('attach', 'ไฟล์แนบเอกสารที่เกี่ยวข้อง :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-8">
    {{-- <button type="button" class="btn btn-sm btn-success" id="attach-add">
      <i class="icon-plus"></i>&nbsp;เพิ่ม
    </button> --}}
  </div>
</div>

<div id="other_attach-box">

  @foreach ($attachs as $key => $attach)

  <div class="form-group other_attach_item">
    <div class="col-md-4">
      {!! Form::hidden('attach_filenames[]', $attach->file_name); !!}
    </div>
    <div class="col-md-3">
      {!! Form::text('attach_notes[]', $attach->file_note, ['class' => 'form-control', 'placeholder' => 'คำอธิบายไฟล์แนบ(ถ้ามี)']) !!}
    </div>
    <div class="col-md-3">

      @if($attach->file_client_name)
        <span><small>{{ $attach->file_client_name }}</small></span>
      @endif
    </div>

    <div class="col-md-2">

      @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
        <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
      @endif

    </div>

  </div>

  @endforeach

</div>

 {!! Form::close() !!}

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

  <!-- tag input -->
  <script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

  <!-- input file -->
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

  <script type="text/javascript">

    $(document).ready(function() {

      $('input,select').attr('disabled','disabled');

      //ปฎิทิน
      $('.datepicker').datepicker();

      //tagsinput width
      $('div.bootstrap-tagsinput').addClass('col-md-12');

      //เพิ่มไฟล์แนบ
      $('#attach-add').click(function(event) {
        $('.other_attach_item:first').clone().appendTo('#other_attach-box');

        $('.other_attach_item:last').find('input').val('');
        $('.other_attach_item:last').find('a.fileinput-exists').click();
        $('.other_attach_item:last').find('a.view-attach').remove();

        ShowHideRemoveBtn();
      });

      //ลบไฟล์แนบ
      $('body').on('click', '.attach-remove', function(event) {
        $(this).parent().parent().remove();
        ShowHideRemoveBtn();
      });

      ShowHideRemoveBtn();


      //เพิ่มกลุ่ม
      $('#depart-add').click(function(event) {
        $('.board_item:first').clone().appendTo('#board-box');
        $('.board_item:last').find('select').val('');
        $('.board_item:last').find('select').prev().remove();
        $('.board_item:last').find('select').removeAttr('style');
        $('.board_item:last').find('select').select2();
        $('.board_item:last').find('.appoint_department').show();
        resetOrder();
        ShowHideRemoveBtnDepart();
      });

      //เพิ่มกลุ่ม
      $('#board-add').click(function(event) {
        $('.board_item:first').clone().appendTo('#board-box');
        $('.board_item:last').find('select').val('');
        $('.board_item:last').find('select').prev().remove();
        $('.board_item:last').find('select').removeAttr('style');
        $('.board_item:last').find('select').select2();
        $('.board_item:last').find('.appoint_department').hide();
        resetOrder();
        ShowHideRemoveBtnDepart();
      });

      //ลบกลุ่ม
      $('body').on('click', '.board-remove', function(event) {
        $(this).parent().parent().parent().remove();
        resetOrder();
        ShowHideRemoveBtnDepart();
      });

      ShowHideRemoveBtnDepart();


    });

    function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

      if ($('.other_attach_item').length > 1) {
        $('.attach-remove').show();
      } else {
        $('.attach-remove').hide();
      }

    }

    function ShowHideRemoveBtnDepart() { //ซ่อน-แสดงปุ่มลบ

      if ($('.board_item').length > 1) {
        $('.board-remove').show();
      } else {
        $('.board-remove').hide();
      }

    }

     function resetOrder(){//รีเซตลำดับของตำแหน่ง

      $('#board-box').children().each(function(index, el) {
        $(el).find('h5').text('ชุดที่ '+(index+1)).next('input[type="hidden"]').val(index+1);
        $(el).find('select[name^="board_ids"]').attr('name', 'board_ids['+(index)+'][]');
      });

    }

  </script>

@endpush
                </div>
            </div>
        </div>
    </div>

@endsection

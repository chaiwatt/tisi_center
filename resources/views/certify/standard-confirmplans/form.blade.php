@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
        
<style type="text/css">
    .panel-body-info {
        border: #00bbd9 1px solid;
    }
    .not-allowed {cursor: not-allowed;}
  </style>
@endpush

<div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('std_type',
        App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
          null, 
        ['class' => 'form-control',
        'disabled'=> true,
        'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name',    null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('method_id',
          App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
            null,
        ['class' => 'form-control',
        'disabled'=> true,
        'placeholder'=>'- เลือกวิธีการ -']) !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name_eng',    null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time',     null,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
    <div class="col-md-7">
        {!! Form::select('industry_target',
          App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
            null,  
          ['class' => 'form-control',
          'disabled'=> true,
          'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -']) !!}
        {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@php
    $attach = $standardconfirmplans->AttachFileAttachTo;
@endphp
@if (!empty($attach))
<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9 m-t-10">
            {!! !empty($attach->caption) ? $attach->caption : '' !!}
            <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                {!! !empty($attach->filename) ? $attach->filename : '' !!}
            </a>
    </div>
</div>
@endif

<div class="form-group {{ $errors->has('reason') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('reason', 'เหตุผลและความจำเป็น'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('reason', null,  ['class' => 'form-control ','disabled'=>true]) !!}  
    </div>
</div>

{{-- <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('name', 'ความต้องการจาก'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('name', null,  ['class' => 'form-control ','disabled'=>true]) !!}  
    </div>
</div> --}}

<div class="form-group {{ $errors->has('plan_startdate') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('plan_startdate', 'กำหนด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            {!! Form::text('plan_startdate', null, ['class' => 'form-control date', 'disabled' => true]) !!}
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('plan_enddate', null, ['class' => 'form-control date', 'disabled' => true]) !!}
          </div>
        {!! $errors->first('plan_enddate', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('period') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('period', 'ระยะเวลา'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
          <div class=" input-group ">
            {!! Form::text('period', null, ['class' => 'form-control input_number', 'disabled' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> เดือน </span>
          </div>
          {!! $errors->first('period', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('budget') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('budget', 'งบประมาณ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class=" input-group ">
            {!! Form::text('budget', null, ['class' => 'form-control amount', 'disabled' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> บาท </span>
        </div>
        {!! $errors->first('budget', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!--.row-->
<div class="row" id="input_disabled">
    <div class="col-md-12">
        <div class="panel panel-info ">
            <div class="panel-heading"> ส่วนพิจารณา</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body panel-body-info">

 <div class="form-group {{ $errors->has('status_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status_id', 'สถานะการพิจารณา'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        {!! Form::select('status_id',
         [
           // '3' => 'นำส่งแผน',
            '4' => 'บรรจุแผน',
            '5' => 'ไม่อนุมัติแผน',
            '6' => 'แจ้งแก้ไขแผน'
        ], 
        null,
        ['class' => 'form-control text-center',
        'id'=>'status_id',
        'required'=>true,
             'placeholder'=>'- เลือกสถานะการพิจารณา -']) !!}
        {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
    </div>
</div>
 
<div class="form-group {{ $errors->has('confirm_detail') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_detail', 'รายละเอียด'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
         {!! Form::textarea('confirm_detail', null, [ 'rows' => 2,'cols'=>'40','required'=>false]) !!} 
        {!! $errors->first('confirm_detail', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_attach', 'เอกสารแนบเพิ่มเติม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        @php
            $confirm_attach = $standardconfirmplans->AttachFileConfirmAttachTo;
        @endphp
        @if (!empty($confirm_attach))
            {!! !empty($confirm_attach->caption) ? $confirm_attach->caption : '' !!}
              <a href="{{url('funtions/get-view/'.$confirm_attach->url.'/'.( !empty($confirm_attach->filename) ? $confirm_attach->filename :  basename($confirm_attach->url)  ))}}" target="_blank" 
                title="{!! !empty($confirm_attach->filename) ? $confirm_attach->filename : 'ไฟล์แนบ' !!}" >
                 {!! !empty($confirm_attach->filename) ? $confirm_attach->filename : '' !!}
            </a>
        @else
            <div class="col-md-6 text-light">
                   {!! Form::text('document_details', null, ['class' => 'form-control ', 'placeholder' => 'รายละเอียดเอกสาร']) !!}
            </div>
            <div class="col-md-6">
                   <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                          <div class="form-control" data-trigger="fileinput">
                          <i class="glyphicon glyphicon-file fileinput-exists"></i>
                          <span class="fileinput-filename"></span>
                          </div>
                          <span class="input-group-addon btn btn-default btn-file">
                          <span class="fileinput-new">เลือกไฟล์</span>
                          <span class="fileinput-exists">เปลี่ยน</span>
                          <input type="file" name="confirm_attach" class="confirm_attach check_max_size_file" >
                          </span> 
                          <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                   </div>
                   {!! $errors->first('confirm_attach', '<p class="help-block">:message</p>') !!}
            </div>
        @endif
    </div>
</div>


<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'วันที่พิจารณา'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-6">
        {{ !empty($standardconfirmplans->user_confirm->FullName) ?  HP::DateTimeFullThai($standardconfirmplans->updated_at)  : HP::DateTimeFullThai(date('Y-m-d H:i:s')) }}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'ผู้พิจารณา'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-6">
        {{ !empty($standardconfirmplans->user_confirm->FullName) ?  $standardconfirmplans->user_confirm->FullName : auth()->user()->FullName }}
    </div>
</div>


                </div>
            </div>
        </div>
    </div>
</div>
<!--./row-->


@if( !empty($standardconfirmplans) && ($standardconfirmplans->status_id >= 4)) 
<div class="clearfix"></div>
 <a    href="{{url('/certify/standard-confirmplans')}}"  class="btn btn-default btn-lg btn-block" style="color:black">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
@else 
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standardconfirmplans'))
            <a class="btn btn-default" href="{{url('/certify/standard-confirmplans')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
</div>
@endif 




@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

    <script>
        $(document).ready(function () {
          /*  $('#status_id').change(function(){
                if($(this).val()=='3'){
                    $(this).attr("required", false);
                }else{
                    $(this).attr("required", true);
                }
            });
            $('#status_id').change();
            $('#status_id option[value="3"]').attr("disabled", true);
            */
        });
    </script>
@endpush

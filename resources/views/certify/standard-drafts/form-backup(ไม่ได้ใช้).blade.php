@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
<style type="text/css">
.bootstrap-tagsinput {
    width: 100% !important;
  }
    .font-16{
        font-size:16px;
    }
    .font-14{
        font-size:10px;
    }
</style>
@endpush

<div class="form-group {{ $errors->has('draft_year') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('draft_year', 'ร่างแผนปี'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        {!! Form::select('draft_year',
       HP::Years(),
       null,
       ['class' => 'form-control',
       'id'=>'draft_year',
       'required'=> true,
       'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('draft_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('board') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('board', 'คณะกรรมการเฉพาะด้าน'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('board[]',
          App\CommitteeInDepartment::orderbyRaw('CONVERT(name USING tis620)')->pluck('name', 'id'),
           null,
         ['class' => 'select2-multiple',
            'multiple' => 'multiple',
           'data-placeholder'=>'- เลือกคณะกรรมการเฉพาะด้าน -']) !!}
        {!! $errors->first('board', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('status_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status_id', 'สถานะ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('status_id',
       ['1'=>'ร่างมาตรฐาน','2'=>'คกก. เห็นชอบร่างมาตรฐาน','3'=>'คกก. ไม่เห็นชอบร่างมาตรฐาน'],
       null,
       ['class' => 'form-control',
       'id'=>'status_id',
       'required'=> true,
       'placeholder'=>'- เลือกสถานะ -']) !!}
        {!! $errors->first('status_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'ผู้จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-10">
        {{ !empty($standarddraft->user_created->FullName) ?  $standarddraft->user_created->FullName : auth()->user()->FullName }}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'วันที่จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-10">
        {{ !empty($standarddraft->user_created->FullName) ?  HP::DateTimeFullThai($standarddraft->created_at)  : HP::DateTimeFullThai(date('Y-m-d H:i:s')) }}
    </div>
</div>

@if(!empty($standarddraft) && count($standarddraft->TisiEstandardDraftPlanMany) > 0)

@foreach ($standarddraft->TisiEstandardDraftPlanMany as $plan)
@php
    $offers  =  $plan->estandard_offers_to;
@endphp
@if (!is_null($offers))

<div class="row">
    <div class="col-md-12">
     <div class="panel block4">
        <div class="panel-group" id="accordion{{$plan->offer_id}}">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion{{$plan->offer_id}}" href="#collapse{{$plan->offer_id}}"> <dd>  {{ $offers->refno.' : '.$offers->title  }} </dd>  </a>
                    </h4>
                </div>

<div id="collapse{{$plan->offer_id}}" class="panel-collapse collapse in">
    <div class="row form-group">
        <div class="container-fluid">
   {!! Form::hidden('list[offer_id]['.$plan->offer_id .']', $plan->offer_id ); !!}
    <div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[std_type]['.$plan->offer_id.']',
            App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
            $plan->std_type ?? null,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
            {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-3">
              {!! Form::text('list[tis_number]['.$plan->offer_id.']',     $plan->tis_number ?? null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('list[tis_book]['.$plan->offer_id.']',     $plan->tis_book ?? null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('list[tis_year]['.$plan->offer_id.']',
            HP::Years(),
            $plan->tis_year ?? null,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกปีมาตรฐาน -']) !!}
           {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-8">
            {!! Form::text('list[tis_name]['.$plan->offer_id.']',   $plan->tis_name ?? null ,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('list[tis_name_eng]['.$plan->offer_id.']',     $plan->tis_name_eng ?? null ,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[method_id]['.$plan->offer_id.']',
              App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
              $plan->method_id ?? null,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกวิธีการ -']) !!}
            {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
            {!! Form::text('list[confirm_time]['.$plan->offer_id.']',     $plan->confirm_time ?? null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
        <div class="col-md-8">
            {!! Form::select('list[industry_target]['.$plan->offer_id.']',
              App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
               $plan->industry_target ?? null,
              ['class' => 'form-control',
              'required'=> true,
              'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -']) !!}
            {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
        @php
            $attach = $plan->AttachFileAttachTo;
        @endphp
        @if (!empty($attach))
        <div class="col-md-9 m-t-10">
                {!! !empty($attach->caption) ? $attach->caption : '' !!}
                <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank"
                    title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                    {!! !empty($attach->filename) ? $attach->filename : '' !!}
                </a>
        </div>
        @else
            <div class="col-md-4 text-light">
                    {!! Form::text('list[document_details]['.$plan->offer_id.']',   null , ['class' => 'form-control ', 'placeholder' => 'รายละเอียดเอกสาร']) !!}
            </div>
            <div class="col-md-5">
                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="list[attach][{{$plan->offer_id}}]" class="attach check_max_size_file" >
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                    {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}
            </div>
        @endif
    </div>

    <div class="form-group {{ $errors->has('objectve') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('objectve', 'เหตุผลและความจำเป็น'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('objectve', $offers->objectve,  ['class' => 'form-control ','disabled'=>true]) !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('name', 'ความต้องการจาก'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('name', $offers->name,  ['class' => 'form-control ','disabled'=>true]) !!}
        </div>
    </div>


    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('name', 'เจ้าหน้าที่ที่รับมอบหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[assign_id]['.$plan->offer_id.']',
                 App\User::select(DB::raw("CONCAT(reg_intital,'',reg_fname,' ',reg_lname) AS titels"),'runrecno AS id')
                 ->where('reg_subdepart',$offers->department_id)
                 ->orderbyRaw('CONVERT(titels USING tis620)')
                 ->pluck('titels', 'id'),
                 $plan->assign_id ?? null,
                ['class' => 'form-control',
                'required'=> true,
                'placeholder'=>'- เลือกเจ้าหน้าที่ที่รับมอบหมาย -']) !!}
                {!! $errors->first('assign_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

        </div>
    </div>
</div>
            </div>
        </div>
    </div>
   </div>
</div>
@endif
@endforeach

@else

@if(!empty($estandard_offers) && count($estandard_offers) > 0)
@foreach ($estandard_offers as $offers)
<div class="row">
    <div class="col-md-12">
     <div class="panel block4">
        <div class="panel-group" id="accordion{{$offers->id}}">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion{{$offers->id}}" href="#collapse{{$offers->id}}"> <dd>  {{ $offers->refno.' : '.$offers->title  }} </dd>  </a>
                    </h4>
                </div>

<div id="collapse{{$offers->id}}" class="panel-collapse collapse in">
    <div class="row form-group">
        <div class="container-fluid">
   {!! Form::hidden('list[offer_id]['.$offers->id .']', $offers->id ); !!}
    <div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[std_type]['.$offers->id.']',
            App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
              $offers->std_type,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
            {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-3">
              {!! Form::text('list[tis_number]['.$offers->id.']', null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('list[tis_book]['.$offers->id.']', null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('list[tis_year]['.$offers->id.']',
            HP::Years(),
            null,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกปีมาตรฐาน -']) !!}
           {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-8">
            {!! Form::text('list[tis_name]['.$offers->id.']',   $offers->title ?? null ,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('list[tis_name_eng]['.$offers->id.']',     $offers->title_eng ?? null ,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[method_id]['.$offers->id.']',
              App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
            null,
            ['class' => 'form-control',
            'required'=> true,
            'placeholder'=>'- เลือกวิธีการ -']) !!}
            {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
            {!! Form::text('list[confirm_time]['.$offers->id.']', null,  ['class' => 'form-control ','required'=>true]) !!}
            {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
        <div class="col-md-8">
            {!! Form::select('list[industry_target]['.$offers->id.']',
              App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
               null,
              ['class' => 'form-control',
              'required'=> true,
              'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -']) !!}
            {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4 text-light">
                   {!! Form::text('list[document_details]['.$offers->id.']', null, ['class' => 'form-control ', 'placeholder' => 'รายละเอียดเอกสาร']) !!}
        </div>
        <div class="col-md-5">
                   <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                          <div class="form-control" data-trigger="fileinput">
                          <i class="glyphicon glyphicon-file fileinput-exists"></i>
                          <span class="fileinput-filename"></span>
                          </div>
                          <span class="input-group-addon btn btn-default btn-file">
                          <span class="fileinput-new">เลือกไฟล์</span>
                          <span class="fileinput-exists">เปลี่ยน</span>
                          <input type="file" name="list[attach][{{$offers->id}}]" class="attach check_max_size_file" >
                          </span>
                          <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                   </div>
                   {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('objectve') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('objectve', 'เหตุผลและความจำเป็น'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('objectve', $offers->objectve,  ['class' => 'form-control ','disabled'=>true]) !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('name', 'ความต้องการจาก'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::text('name', $offers->name,  ['class' => 'form-control ','disabled'=>true]) !!}
        </div>
    </div>


    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('name', 'เจ้าหน้าที่ที่รับมอบหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            {!! Form::select('list[assign_id]['.$offers->id.']',
                 App\User::select(DB::raw("CONCAT(reg_intital,'',reg_fname,' ',reg_lname) AS titels"),'runrecno AS id')
                 ->where('reg_subdepart',$offers->department_id)
                 ->orderbyRaw('CONVERT(titels USING tis620)')
                 ->pluck('titels', 'id'),
                null,
                ['class' => 'form-control',
                'required'=> true,
                'placeholder'=>'- เลือกเจ้าหน้าที่ที่รับมอบหมาย -']) !!}
                {!! $errors->first('assign_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

        </div>
    </div>
</div>
            </div>
        </div>
    </div>
   </div>
</div>
@endforeach
@else
@php
    $check = 'false';
@endphp
@endif

@endif

@if (isset($check))
<div class="form-group ">
    <div class="alert alert-danger text-center" role="alert">
         ไม่รายการความเห็นการกำหนดมาตรฐานการตรวจสอบและรับรอง
    </div>
</div>
@else
<div class="form-group div_hide">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standarddrafts'))
            <a class="btn btn-default" href="{{url('/certify/standard-drafts')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
@endif


@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <script type="text/javascript">
        $(document).ready(function() {


        });

</script>
@endpush

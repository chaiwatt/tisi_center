

@if (!is_null($standardplan))

<div class="form-group {{ $errors->has('projectid') ? 'has-error' : ''}}">
    {!! HTML::decode( Form::label('projectid', 'รหัสงาน (Project ID)'.' :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('projectid',  !empty($setstandard->projectid) ? $setstandard->projectid :  'อยู่ระหว่างกำหนดมาตรฐาน', ['class' => 'form-control','placeholder'=>'รอดำเนิน','disabled'=>true]) !!}
        {!! $errors->first('projectid', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('std_type',
        App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
        !empty($standardplan->std_type) ? $standardplan->std_type : null, 
        ['class' => 'form-control ',
        'disabled'=> true,
        'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>
    
<div class="form-group  {{ $errors->has('list[start_std]') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('list[start_std]', 'การกำหนดมาตรฐาน : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-9">
        <label>{!! Form::radio('start_std', '1', is_null($standardplan->start_std) ||  $standardplan->start_std == 1, ['class'=> "check start_std_check", 'data-id' => "#start_std", 'data-radio'=>'iradio_square-green']) !!} กำหนดใหม่ &nbsp;&nbsp;</label>
        <label>{!! Form::radio('start_std', '2', $standardplan->start_std == 2  , ['class'=> "check start_std_check", 'data-id' => "#start_std", 'data-radio'=>'iradio_square-green']) !!} ทบทวน &nbsp;&nbsp;</label>
    </div>
</div>
@if (!empty($standardplan->start_std) && $standardplan->start_std == 2)
<div class="form-group {{ $errors->has('list[ref_std]') ? 'has-error' : ''}}"  >
    {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('ref_std',
            App\Models\Certify\Standard::selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id'),
            !empty($standardplan->ref_std) ? $standardplan->ref_std : null, 
            ['class' => 'form-control',
            'disabled' => true,
            'placeholder'=>'- เลือกมาตรฐาน -']) !!}
        {!! $errors->first('list[ref_std]', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('tis_number',   !empty($standardplan->tis_number) ? $standardplan->tis_number : null, ['class' => 'form-control', 'id'=>'tis_number']) !!}
        {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('tis_book',   !empty($standardplan->tis_book) ? $standardplan->tis_book : null, ['class' => 'form-control', 'id'=>'tis_book','placeholder' => 'เล่ม ถ้ามี']) !!}
        {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('tis_year',
                        HP::Years(),
                        !empty($standardplan->tis_year) ? $standardplan->tis_year : null,
                        ['class' => 'form-control',
                        'id'=>'tis_year',
                         'placeholder' => '- เลือกปีมาตรฐาน -'
                        ])
        !!}
        {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name',  !empty($standardplan->tis_name) ? $standardplan->tis_name : null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name_eng',   !empty($standardplan->tis_name_eng) ? $standardplan->tis_name_eng : null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>

 
<div class="form-group {{ $errors->has('ref_document') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('ref_document', 'เอกสารอ้างอิง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('ref_document',   !empty($standardplan->ref_document) ? $standardplan->ref_document : null , ['class' => 'form-control ' ,'disabled'=>true]) !!}
        {!! $errors->first('ref_document', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reason') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('reason', 'เหตุผลและความจำเป็น'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('reason',  !empty($standardplan->reason_to->title) ?  $standardplan->reason_to->title: null    ,  ['class' => 'form-control ','disabled'=>true]) !!}  
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time',   !empty($standardplan->confirm_time) ? $standardplan->confirm_time : null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
    <div class="col-md-7">
        {!! Form::select('industry_target',
                          App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),//อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต
                          !empty($standardplan->industry_target) ? $standardplan->industry_target : null,
                        ['class' => 'form-control',
                         'disabled'=>true,
                         'placeholder' => '- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -'
                        ])
        !!}
        {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9  repeater-form-file" >
        <div class="row" data-repeater-list="repeater-attach_step1">
            @php
                $attach = $standardplan->AttachFileAttachTo;
            @endphp
            @if (!empty($attach))
                <p>
                    {!! !empty($attach->caption) ? $attach->caption : '' !!}
                    <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                        title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                        {!! HP::FileExtension($attach->filename)  ?? '' !!}
                    </a>
                </p>
            @endif
            @php
                $attach_step1 = $setstandard->AttachFileSetStandardsDetailsAttachTo;
            @endphp
            @if (!empty($attach_step1) && count($attach_step1) > 0)
            @foreach ($attach_step1 as $step1)
                    <p>
                        {!! !empty($step1->caption) ? $step1->caption : '' !!}
                        <a href="{!! HP::getFileStorage($step1->url) !!}" target="_blank">
                             {!! HP::FileExtension($step1->filename)  ?? '' !!}
                        </a>
                    </p>
            @endforeach
            
            @endif
            <div class="form-group repeater_form_file" data-repeater-item>
                <div class="col-md-4">
                    {!! Form::text('file_attach_step1_documents', null,['class' => 'form-control']) !!}
                </div>
                <div class="col-md-5">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                            <span class="input-group-text btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="attach_step1">
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete type="button">
                        ลบ
                    </button>
                    <button type="button" class="btn btn-success btn-sm btn_file_add" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                </div>
            </div>
       </div>
    </div>
</div> 


@endif
 
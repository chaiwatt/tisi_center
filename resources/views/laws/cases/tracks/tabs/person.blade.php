<div class="form-group" >
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="text-center" width="2%">#</th>
                        <th class="text-center" width="30%">มาตราความผิด</th>
                        <th class="text-center" width="30%">บทกำหนดลงโทษ</th>
                        <th class="text-center" width="30%">อำนาจพิจารณาเปรียบเทียบปรับ</th>
                    </tr>
                </thead>
                <tbody id="table_tbody_section">
                    @if (!empty($law_result) && count($law_result->law_case_result_section_many) > 0)
                        @foreach ($law_result->law_case_result_section_many as $key => $item )
                            <tr>
                                <td class="text-center text-top"> {!! ($key+1)!!}</td>
                                <td class="text-top">
                                    {!! !empty($item->section_to->number)  &&  !empty($item->section_to->title) ?  $item->section_to->number.' : '.$item->section_to->title : ''  !!}
                                </td>
                                <td class="text-top">
                                    {!! !empty($item->punish_to->number)  &&  !empty($item->punish_to->title) ?  $item->punish_to->number.' : '.$item->punish_to->title : ''  !!}
                                </td>
                                <td class="text-top">
                                    {!! !empty($item->PowerName) ?  $item->PowerName : ''  !!}     
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> 
    </div>
</div>

<div class="form-group">
    {!! Form::label('person', 'การดำเนินการงานคดี'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        <div class="checkbox checkbox-warning">
            <input id="person"  name="person"  type="checkbox" value="1" disabled @if( !empty($law_result) && in_array(  $law_result->person, [1]) ) checked @endif>
            <label for="person">&nbsp;ดำเนินการทางอาญา&nbsp;</label>
        </div>
        <div class="checkbox checkbox-warning">
            <input id="license" name="license" type="checkbox" value="1" disabled @if( !empty($law_result) && in_array(  $law_result->license, [1]) ) checked @endif>
            <label for="license">&nbsp;ดำเนินการปกครอง(ใบอนุญาต)&nbsp;</label>
        </div>
        <div class="checkbox checkbox-warning">
            <input id="product" name="product" type="checkbox" value="1" disabled @if( !empty($law_result) && in_array(  $law_result->product, [1]) ) checked @endif>
            <label for="product">&nbsp;ดำเนินการของกลาง (ผลิตภัณฑ์)&nbsp;</label>
        </div>
    </div>
</div>

@php
    //หลักฐานผลพิจารณา
    $attachs_consider = $law_result->AttachFileConsider;
    //บันทึกพิจารณาคดี
    $attachs_consider_result = $result->AttachFileConsiderResult;
    //เปรียบเทียบปรับ
    $attachs_consider_compares = $result->AttachFileConsiderCompares;
    //ข้อเท็จจริงการเปรียบเทียบปรับ
    $attachs_consider_comparison_facts = $result->AttachFileConsiderComparisonFacts;
    //ไฟล์เเนบ
    $attachs_result_others   = !empty($result->AttachFileOther)? $result->AttachFileOther:[];
@endphp

@if (!empty($attachs_consider))
    <div class="form-group">
        <div class="col-md-offset-4">
            <p class="text-muted"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('case_number', 'หลักฐานผลพิจารณา(ถ้ามี)'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($attachs_consider->url) !!}" target="_blank">
                {!! !empty($attachs_consider->filename) ? $attachs_consider->filename : '' !!}
                {!! HP::FileExtension($attachs_consider->url) ?? '' !!}
            </a>
        </div>
    </div>
@endif
@if (!empty($attachs_consider_result))
    <div class="form-group">
        {!! Form::label('case_number', 'บันทึกพิจารณาคดี'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($attachs_consider_result->url) !!}" target="_blank">
                {!! !empty($attachs_consider_result->filename) ? $attachs_consider_result->filename : '' !!}
                {!! HP::FileExtension($attachs_consider_result->url) ?? '' !!}
            </a>
        </div>
    </div>
@endif
@if (!empty($attachs_consider_compares))
    <div class="form-group">
        {!! Form::label('case_number', 'เปรียบเทียบปรับ'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($attachs_consider_compares->url) !!}" target="_blank">
                {!! !empty($attachs_consider_compares->filename) ? $attachs_consider_compares->filename : '' !!}
                {!! HP::FileExtension($attachs_consider_compares->url) ?? '' !!}
            </a>
        </div>
    </div>
@endif
@if (!empty($attachs_consider_comparison_facts))
    <div class="form-group">
        {!! Form::label('case_number', 'ข้อเท็จจริงการเปรียบเทียบปรับ'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($attachs_consider_comparison_facts->url) !!}" target="_blank">
                {!! !empty($attachs_consider_comparison_facts->filename) ? $attachs_consider_comparison_facts->filename : '' !!}
                {!! HP::FileExtension($attachs_consider_comparison_facts->url) ?? '' !!}
            </a>
        </div>
    </div>
@endif
@if (count($attachs_result_others) > 0)
    <div class="form-group">
        {!! Form::label('case_number', 'ไฟล์เเนบ(อื่นๆ)'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            @foreach ($attachs_result_others as $attachs_result_other)
                <p>     
                    <a href="{!! HP::getFileStorage($attachs_result_other->url) !!}" target="_blank">
                        {!! !empty($attachs_result_other->filename) ? $attachs_result_other->filename : '' !!}
                        {!! HP::FileExtension($attachs_result_other->url) ?? '' !!}
                    </a>
                </p>
            @endforeach
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('remark', !empty($result->remark) ? $result->remark : null , ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3', 'disabled' => true]); !!}
        {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('', 'ผู้บันทึก'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
         {!! Form::text('', !empty($result->CreatedName) ? $result->CreatedName :   auth()->user()->FullName, ['class' => 'form-control ', 'disabled' => true ]) !!}
        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('', 'วันที่บันทึก'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
         {!! Form::text('',!empty($result->created_at) ?HP::DateTimeThai($result->created_at) : HP::DateTimeThai(date('Y-m-d H:i:s')), ['class' => 'form-control ', 'disabled' => true ]) !!}
        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
    </div>
</div>
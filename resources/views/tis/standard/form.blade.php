@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

<style>
  .tis_force input{
    background-color: rgb(225, 236, 233);
  }
</style>

@endpush

<div class="row">
    <div class="col-md-12">

        <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
            {!! Form::label('title', 'ชื่อมาตรฐาน (TH) :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required {{ $errors->has('title_en') ? 'has-error' : ''}}">
            {!! Form::label('title_en', 'ชื่อมาตรฐาน (EN) :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:0.9em;'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
                {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

    </div>

    <div class="col-md-5">

        {{-- <div class="form-group {{ $errors->has('tis_force') ? 'has-error' : ''}}">
        {!! Form::label('tis_force', ' ', ['class' => 'col-md-5 control-label']) !!}
        <div class="col-md-7">
            <label>{!! Form::radio('tis_force', 'ท', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'tis_force1']) !!} ทั่วไป</label>
            <label>{!! Form::radio('tis_force', 'บ', false, ['class'=>'check', 'data-radio'=>'iradio_square-red', 'id'=>'tis_force2']) !!} บังคับ</label>

            {!! $errors->first('tis_force', '<p class="help-block">:message</p>') !!}
        </div>
        </div> --}}

        <div class="form-group required {{ $errors->has('tis_no') ? 'has-error' : ''}} required">
            {!! Form::label('tis_no', 'เลขที่ มอก. :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tis_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required {{ $errors->has('tis_year') ? 'has-error' : ''}}">
            {!! Form::label('tis_year', 'ปีของมอก. :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tis_year', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        {{-- <div class="form-group required {{ $errors->has('issue_date') ? 'has-error' : ''}}">
        {!! Form::label('issue_date', 'วันที่ประกาศใช้ :', ['class' => 'col-md-5 control-label']) !!}
        <div class="col-md-7">
            {!! Form::text('issue_date', null, ['class' => 'form-control datepicker', 'required' => 'required', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
            {!! $errors->first('issue_date', '<p class="help-block">:message</p>') !!}
        </div>
        </div> --}}

        <div class="form-group {{ $errors->has('tis_book') ? 'has-error' : ''}}">
            {!! Form::label('tis_book', 'เล่มที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tis_book', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required{{ $errors->has('tis_tisno') ? 'has-error' : ''}}">
            {!! Form::label('tis_tisno', 'เลข มอก. (แบบเดิม) :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tis_tisno', null, ['class' => 'form-control', 'required' =>  true]) !!}
                {!! $errors->first('tis_tisno', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group required{{ $errors->has('tis_tisshortno') ? 'has-error' : ''}}">
            {!! Form::label('tis_tisshortno', 'เลข มอก. (แบบย่อ) :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('tis_tisshortno', null,  ['class' => 'form-control', 'required' =>  true]) !!}
                {!! $errors->first('tis_tisshortno', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('refer') ? 'has-error' : ''}}">
            {!! Form::label('refer', 'ข้อมูลการอ้างอิง :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">

                <div id="refer-box">

                    @foreach ((array)$refers as $key => $refer)

                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-10">
                                {!! Form::text('refer[]', !empty($refer)?$refer:null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-2">
                            @if($key==0)
                                <button type="button" class="btn btn-sm btn-success pull-right" id="add-refer">
                                <i class="icon-plus"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger pull-right remove-refer">
                                <i class="icon-close"></i>
                                </button>
                            @endif
                            </div>
                        </div>

                    @endforeach

                </div>

                {!! $errors->first('refer', '<p class="help-block">:message</p>') !!}

            </div>
        </div>

        <div class="form-group {{ $errors->has('ics') ? 'has-error' : ''}}">
            {!! Form::label('ics', 'ICS :', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::select('ics[]', App\Models\Basic\Ics::selectRaw('CONCAT(code," ",title_en) As title, id')->pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'ics', 'data-placeholder'=>'- เลือก ICS -']) !!}
                {!! $errors->first('ics', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
            {!! Form::label('isbn', 'ISBN :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('isbn', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('isbn', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('government_gazette') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
            {{-- <label class="control-label"><b>ประกาศเป็นมาตรฐานบังคับ</b></label> --}}
                <div class="checkbox checkbox-danger">
                        {!! Form::checkbox('government_gazette', 'y', !empty($standard) && $standard->government_gazette=='y'?true:false , ['class' => 'form-control', 'id'=>'government_gazette']) !!}
                        <label for="government_gazette" style="padding-left:10px"> มาตรฐานที่ประกาศราชกิจจาแล้ว</label>
                </div>
            </div>
        </div>

        <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label class="control-label"><b>ประกาศกระทรวง</b></label>
            </div>
        </div>

        <div class="form-group {{ $errors->has('minis_dated') ? 'has-error' : ''}}">
            {!! Form::label('minis_dated', 'ลงวันที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('minis_dated', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off']) !!}
                {!! $errors->first('minis_dated', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('issue_date') ? 'has-error' : ''}}">
            {!! Form::label('issue_date', 'วันที่มาตรฐานมีผลใช้งาน :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('issue_date', null, ['class' => 'form-control', 'autocomplete'=>'off', 'readonly'=>'readonly']) !!}
                {!! $errors->first('issue_date', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('amount_date') ? 'has-error' : ''}}">
            {!! Form::label('amount_date', 'จำนวนวันที่มีผลนับจากประกาศราชกิจจา :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::number('amount_date', null, ['class' => 'form-control', 'autocomplete'=>'off']) !!}
                {!! $errors->first('amount_date', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('minis_no') ? 'has-error' : ''}}">
            {!! Form::label('minis_no', 'ฉบับที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('minis_no', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('minis_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label class="control-label"><b>ราชกิจจานุเบกษา</b></label>
            </div>
        </div>

        <div class="form-group {{ $errors->has('gaz_date') ? 'has-error' : ''}}">
            {!! Form::label('gaz_date', 'วันที่ประกาศ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('gaz_date', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
                {!! $errors->first('gaz_date', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('gaz_no') ? 'has-error' : ''}}">
            {!! Form::label('gaz_no', 'เล่ม :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('gaz_no', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('gaz_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('gaz_space') ? 'has-error' : ''}}">
            {!! Form::label('gaz_space', 'ตอนที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('gaz_space', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'style' => 'font-size:small'] : ['class' => 'form-control', 'style' => 'font-size:small']) !!}
                {!! $errors->first('gaz_space', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
            {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
            {{-- <label class="control-label"><b>ประกาศเป็นมาตรฐานบังคับ</b></label> --}}
                <div class="checkbox checkbox-success">
                    {!! Form::checkbox('announce_compulsory', 'y', !empty($standard) && $standard->announce_compulsory=='y'?true:false , ['class' => 'form-control', 'id'=>'tis_force1']) !!}
                    <label for="announce_compulsory" style="text-decoration-line: underline; padding-left:10px"> กมอ. มีมติให้เป็นมาตรฐานบังคับ</label>
                </div>
            </div>
        </div>

        <div class="row tis_force">
            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    <label class="control-label"><b>ประกาศกฤษฎีกา/ประกาศกฎกระทรวง</b></label>
                </div>
            </div>

            <div class="form-group {{ $errors->has('minis_dated_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('minis_dated_compulsory', 'ลงวันที่ :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('minis_dated_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('minis_dated_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('issue_date_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('issue_date_compulsory', 'วันที่มีผลบังคับใช้ :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('issue_date_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('issue_date_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('amount_date_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('amount_date_compulsory', 'จำนวนวัน :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::number('amount_date_compulsory', null, ['class' => 'form-control', 'autocomplete'=>'off', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('amount_date_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            {{-- <div class="form-group {{ $errors->has('minis_no_compulsory') ? 'has-error' : ''}}">
            {!! Form::label('minis_no_compulsory', 'ฉบับที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('minis_no_compulsory', ( isset($standard)?$standard->minis_no_compulsory:null ), ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'disabled'=>'disabled']) !!}
                {!! $errors->first('minis_no_compulsory', '<p class="help-block">:message</p>') !!}
            </div>
            </div> --}}

            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                {!! Form::label('', '', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                <label class="control-label"><b>ราชกิจจานุเบกษา</b></label>
                </div>
            </div>

            <div class="form-group {{ $errors->has('gaz_date_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('gaz_date_compulsory', 'วันที่ประกาศ :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('gaz_date_compulsory', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'disabled'=>'disabled']) !!}
                    {!! $errors->first('gaz_date_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('gaz_no_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('gaz_no_compulsory', 'เล่ม :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('gaz_no_compulsory', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('gaz_no_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('gaz_space_compulsory') ? 'has-error' : ''}}">
                {!! Form::label('gaz_space_compulsory', 'ตอนที่ :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('gaz_space_compulsory', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('gaz_space_compulsory', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-7">

        <div class="form-group {{ $errors->has('board_type_id') ? 'has-error' : ''}}">
            {!! Form::label('board_type_id', 'คณะที่จัดทำ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('board_type_id', App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->orderbyRaw('cast(board_position as unsigned)')->where('state',1)->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกคณะที่จัดทำ -']) !!}
                {!! $errors->first('board_type_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('standard_type_id') ? 'has-error' : ''}}">
            {!! Form::label('standard_type_id', 'ประเภท มอก. :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('standard_type_id', App\Models\Basic\StandardType::selectRaw('CONCAT(title," (",acronym,")") As title, id')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภท มอก. -']) !!}
                {!! $errors->first('standard_type_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        {{-- <div class="form-group {{ $errors->has('standard_format_id') ? 'has-error' : ''}}">
        {!! Form::label('standard_format_id', 'ทั่วไป/บังคับ :', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::select('standard_format_id', App\Models\Basic\StandardFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกรูปแบบ มอก. -']) !!}
                {!! $errors->first('standard_format_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div> --}}

        <div class="form-group {{ $errors->has('set_format_id') ? 'has-error' : ''}}">
            {!! Form::label('set_format_id', 'ใหม่/ทบทวน :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('set_format_id', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือก ใหม่/ทบทวน -']) !!}
                {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
            {!! Form::label('method_id', 'วิธีจัดทำ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('method_id', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกวิธีจัดทำ -']) !!}
                {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('method_id_detail') ? 'has-error' : ''}}">
            {!! Form::label('method_id_detail', 'รายละเอียดย่อยของวิธีจัดทำ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('method_id_detail', [], !empty($standard) && $standard->method_id_detail?$standard->method_id_detail:null, ['class' => 'form-control', 'placeholder'=>'- เลือกรายละเอียดย่อยของวิธีจัดทำ -']) !!}
                {!! $errors->first('method_id_detail', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
            {!! Form::label('product_group_id', 'กลุ่มผลิตภัณฑ์/สาขา :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('product_group_id', App\Models\Basic\ProductGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('industry_target_id') ? 'has-error' : ''}}">
            {!! Form::label('industry_target_id', 'อุตสาหกรรมเป้าหมาย :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('industry_target_id', App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย -']) !!}
                {!! $errors->first('industry_target_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('staff_group_id') ? 'has-error' : ''}}">
            {!! Form::label('staff_group_id', 'กลุ่มเจ้าหน้าที่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('staff_group_id', App\Models\Basic\StaffGroup::pluck('order', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มเจ้าหน้าที่ -']) !!}
                {!! $errors->first('staff_group_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('staff_responsible') ? 'has-error' : ''}}">
            {!! Form::label('staff_responsible', 'ชื่อเจ้าหน้าที่รับผิดชอบ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('staff_responsible', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                {!! $errors->first('staff_responsible', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
            {!! Form::label('remark', 'หมายเหตุ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 2]) !!}
                {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
            </div>
        </div>


        @isset( $set_std  )
            @php
                $set_attach_path = 'tis_attach/set_standard/';
            @endphp
            <div class="form-group">
                {!! Form::label('attach', 'ไฟล์แนบระบบกำหนดมาตรฐาน :', ['class' => 'col-md-5 control-label']) !!}

                @foreach ( $set_std as $set_standard )

                    @php
                        $set_attachs = !empty($set_standard->attach)?json_decode($set_standard->attach):[];
                    @endphp

                    @foreach ( $set_attachs as $key=> $set_attach )
                        @if( $key== 0 )
                            <div class="col-md-1">
                                @if($set_attach->file_name !='' && HP::checkFileStorage($set_attach_path.$set_attach->file_name))
                                    <a href="{{ HP::getFileStorage($set_attach_path.$set_attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                @endif
                            </div>
                            <div class="col-md-6 view-filename">{{ !empty($set_attach->file_client_name)?$set_attach->file_client_name:'' }}</div>
                        @endif

                    @endforeach

                @endforeach

            </div>

            @foreach ( $set_std as  $set_standard )

                @php
                    $set_attachs = !empty($set_standard->attach)?json_decode($set_standard->attach):[];
                @endphp
                @foreach ( $set_attachs as $key => $set_attach )
                    @if( $key != 0 )
                        <div class="form-group">
                            <div class="col-md-5"></div>
                            <div class="col-md-1">
                                @if($set_attach->file_name !='' && HP::checkFileStorage($set_attach_path.$set_attach->file_name))
                                    <a href="{{ HP::getFileStorage($set_attach_path.$set_attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                @endif
                            </div>
                            <div class="col-md-6 view-filename">{{ !empty($set_attach->file_client_name)?$set_attach->file_client_name:'' }}</div>
                        </div>
                    @endif
                @endforeach

            @endforeach

        @endisset

        <div class="form-group">
            {!! Form::label('attach', 'ไฟล์แนบ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <button type="button" class="btn btn-sm btn-success" id="attach-add">
                <i class="icon-plus"></i>&nbsp;เพิ่ม
                </button>
            </div>
        </div>

        <div id="other_attach-box">
            @isset( $attachs )
                @foreach ((array)$attachs as $key => $attach)

                    <div class="other_attach_item">
                        <div class="form-group">
                            <div class="col-md-5"></div>
                            <div class="col-md-1">
                                @if($attach['file_name']!='' && HP::checkFileStorage($attach_path.$attach['file_name']))
                                    <a href="{{ HP::getFileStorage($attach_path.$attach['file_name']) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                @endif
                            </div>
                            <div class="col-md-4 view-filename">{{ !empty($attach['file_client_name'])?$attach['file_client_name']:'' }}</div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5">
                                {!! Form::select('attach_notes['.$key.']', App\Models\Basic\SetAttach::Where('state',1)->pluck('title', 'id'), $attach['file_note']??null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อรายการไฟล์แนบ -']) !!}
                                {!! Form::hidden('attach_filenames['.$key.']', (!empty($attach['file_name'])? $attach['file_name']:null)   ) !!}
                            </div>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i><span class="fileinput-filename"></span></div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('attachs['.$key.']', null) !!}
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger btn-sm attach-remove" type="button">
                                <i class="icon-close"></i>
                                </button>
                            </div>
                        </div>
                        <hr>
                    </div>
                @endforeach
            @endisset
        </div>

        <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! Form::label('state', 'สถานะ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ใช้งาน</label>
                <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ยกเลิก</label>

                {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </div>
        </div>


        <div class="box_cancel">

            <div class="form-group {{ $errors->has('cancel_date') ? 'has-error' : ''}}">
                {!! Form::label('cancel_date', 'วันที่ประกาศยกเลิก :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('cancel_date', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'required'=> false]) !!}
                    {!! $errors->first('cancel_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('cancel_reason') ? 'has-error' : ''}}">
                {!! Form::label('cancel_reason', 'เหตุผลที่ยกเลิก :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::textarea('cancel_reason', null, ['class' => 'form-control', 'rows' => 2 , 'required'=>false]) !!}
                    {!! $errors->first('cancel_reason', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('cancel_minis_no') ? 'has-error' : ''}}">
                {!! Form::label('cancel_minis_no', 'ประกาศกระทรวงฯ ฉบับที่ :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('cancel_minis_no', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('cancel_minis_no', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('cancel_attach') ? 'has-error' : ''}}">
                {!! Form::label('cancel_attach', 'ไฟล์ของแจ้งยกเลิก :', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="cancel_attach" id="cancel_attach" class="check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                </div>
            </div>

            @if( isset($standard) && !empty($standard->cancel_attach) )
                @php
                    $cancel_attach = !empty($standard->cancel_attach)?json_decode($standard->cancel_attach):[];
                @endphp
                @foreach ( $cancel_attach as $cancel_attachs )
                    <div class="form-group">
                        <div class="col-md-5"></div>
                        <div class="col-md-1">
                            @if($cancel_attachs->file_name !='' && HP::checkFileStorage($attach_path.$cancel_attachs->file_name))
                                <a href="{{ HP::getFileStorage($attach_path.$cancel_attachs->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 view-filename">{{ !empty($cancel_attachs->file_client_name)?$cancel_attachs->file_client_name:'' }}</div>
                    </div>
                @endforeach
            @endif


        </div>
        <div class="form-group {{ $errors->has('publishing_status') ? 'has-error' : ''}}">
            {!! Form::label('publishing_status', 'สถานะเผยแพร่ :', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                <label>{!! Form::radio('publishing_status', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
                <label>{!! Form::radio('publishing_status', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

                {!! $errors->first('publishing_status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

    </div>

</div>

<br class="clearfix">

<div class="form-group">
    <div class="col-md-offset-4 col-md-4"></div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i> บันทึก</button>
        @can('view-'.str_slug('standard'))
            <a class="btn btn-default" href="{{ app('url')->previous()  }}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var id = "{{ $standard->id  ?? null }}";

            // alert(id);
            $("#ics").select2({
                allowClear:true,
                matcher: function(term, text) {
                    return text.toUpperCase().indexOf(term.toUpperCase())==0;
                }
            });


            $('input[name="state"]').on('ifChecked', function(event){
                state_type($(this).val())
            });
            state_type($('input[name="state"]:checked').val());

            //ปฎิทินไทย
            $('.datepicker').datepicker();

            $('#amount_date').change(function(){
                var amountDate = $(this).val();
                if(amountDate){
                    if($('#gaz_date').val()!=""){
                    var array = $('#gaz_date').val().split("/");
                    var gazDate = array[1]+"/"+array[0]+"/"+parseInt(array[2]-543);
                    var newDate = newDayAdd(gazDate,parseInt(amountDate));
                    $('#issue_date').val(newDate);
                    }else{
                    alert("วันที่ประกาศใช้ในราชกิจจานุเบกษา ไม่มีค่า");
                    }
                } else {
                    $('#issue_date').val('');
                }
            });

            //ทั่วไป-บังคับ
            $('#tis_force1').on('click', function (event) {
                ShowHideForce();
            });

            //เมื่อเพิ่มข้อมูลอ้างอิง
            $('#add-refer').click(function(){

                $('#refer-box').children(':first').clone().appendTo('#refer-box'); //Clone Element

                //edit button
                var last_new = $('#refer-box').children(':last');
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger remove-refer');
                $(last_new).find('button').html('<i class="icon-close"></i>');

            });

            //เมื่อลบข้อมูลอ้างอิง
            $('body').on('click', '.remove-refer', function(event) {
                $(this).parent().parent().remove();
            });

            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#other_attach-box');

                var last_new = $('.other_attach_item:last');

                $(last_new).find('.view-filename').text('');
                $(last_new).find('.view-attach').remove();
                $(last_new).find('input[type="hidden"]').val('');
                $(last_new).find('span.fileinput-filename').text('');
                // $(last_new).find('i.fileinput-exists').remove();

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                ShowHideRemoveBtn();
                orderKeyAttach();

            });


            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().parent().remove();
                ShowHideRemoveBtn();
                orderKeyAttach();
            });

            ShowHideRemoveBtn();
            ShowHideForce();



            $('#method_id').change(function(){
                    var data_val = $(this).val();
                    var method_detail_val = "<?php echo !empty($standard) && $standard->method_id_detail?$standard->method_id_detail:null; ?>";
                    if(data_val!=""){
                    $.ajax({
                        type: "GET",
                        url: "{{url('/tis/standard/add_method_detail')}}",
                        datatype: "html",
                        data: {
                            method_id: data_val,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            var opt;
                            opt += "<option value=''>- เลือกรายละเอียดย่อยของวิธีจัดทำ -</option>";
                            $.each(list, function (key, val) {
                                opt += "<option value='" + key + "'>" + val + "</option>";
                            });
                            $("#method_id_detail").html(opt).trigger("change");
                            if(method_detail_val){
                            $("#method_id_detail").find('option[value="'+method_detail_val+'"]').attr('selected','selected').trigger("change");
                            }
                        }
                    });
                    }
            });

            $('#method_id').change();

            $('#tis_force1').click(function() {

                if($('#tis_force1').prop('checked')){

                    var government_gazette =   $('#government_gazette:checked').val()?'y':'n';

                    var data_val = id;
                    if(data_val!=""){
                        $.ajax({
                            type: "POST",
                            url: "{{ url('tis/note_std_draft/save_note_std_draft') }}",
                            datatype: "html",
                            data: {
                                std_id: data_val,
                                government_gazette: government_gazette,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                var response = data;
                                if(response.status=="success"){
                                    alert(response.message_data);
                                } else if (response.status=="already_have") {
                                    alert(response.message_data);
                                } else {
                                    alert('เกิดข้อผิดพลาดในการบันทึก กรุณาคลิ๊กที่ปุ่ม กมอ. มีมติให้เป็นมาตรฐานบังคับ อีกครั้ง')
                                }
                            }
                        });
                    }

                }

            });

        });

        function state_type( vals ){
            if(vals == 1){
                $('.box_cancel').find('input, select, textarea').prop('disabled', true);
                $('.box_cancel').hide();
            }else{
                $('.box_cancel').find('input, select, textarea').prop('disabled', false);
                $('.box_cancel').show();
            }
        }

        function orderKeyAttach() {
            var rows = $('#other_attach-box').children(); //แถวทั้งหมด
            rows.each(function(index, el) {

                var attach_filenames = $(el).find('div > input[type="hidden"]');
                    attach_filenames.attr('name', 'attach_filenames[' + index + ']');
                var attachs = $(el).find('div > div.fileinput > span > input');
                    attachs.attr('name', 'attachs[' + index + ']');
                var attach_notes = $(el).find('div > select');
                    attach_notes.attr('name', 'attach_notes[' + index + ']');

            });
        }

        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 1) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }

        }

        function ShowHideForce(){

            if($('#tis_force1').prop('checked')){//ทั่วไป
                $('.tis_force').show(500);
                //  $('#issue_date_compulsory').attr('required', true);
            }else{
                //  $('#issue_date_compulsory').attr('required', false);
                // $('input[name$="_compulsory"]').val('');
                $('.tis_force').hide(500);
            }

        }

        function newDayAdd(inputDate,addDay){
            var d = new Date(inputDate);
            d.setDate(d.getDate()+addDay);
            mkMonth=d.getMonth()+1;
            mkMonth=new String(mkMonth);
            if(mkMonth.length==1){
                mkMonth="0"+mkMonth;
            }
            mkDay=d.getDate();
            mkDay=new String(mkDay);
            if(mkDay.length==1){
                mkDay="0"+mkDay;
            }
            mkYear=d.getFullYear();
            return mkDay+"/"+mkMonth+"/"+parseInt(mkYear+543); // แสดงผลลัพธ์ในรูปแบบ วัน/เดือน/ปี ไทย
        }

    </script>
@endpush

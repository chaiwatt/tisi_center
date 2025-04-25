@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style type="text/css">
        ::placeholder {
        text-align: center; 
        }
        /* or, for legacy browsers */
        ::-webkit-input-placeholder {
        text-align: center;
        }

        :-moz-placeholder { /* Firefox 18- */
        text-align: center;  
        }

        ::-moz-placeholder {  /* Firefox 19+ */
        text-align: center;  
        }

        :-ms-input-placeholder {  
        text-align: center; 
        }

        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 42px;
            border-radius: 0;
            width: 100% !important;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
    </style>
@endpush

<div class="form-group required {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Form::label('std_type', 'ประเภทมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('std_type', App\Models\Bcertify\Standardtype::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกประเภทมาตรฐาน-'] : ['class' => 'form-control', 'placeholder' => '-เลือกประเภทมาตรฐาน-']) !!}

        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group required {{ $errors->has('format_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('format_id', 'รูปแบบ :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-7">
        <label>{!! Form::radio('format_id', '1',null, ['class'=> "check", 'data-radio'=>'iradio_square-green' ,'required'=>'required']) !!} กำหนดใหม่ &nbsp;&nbsp;</label>
        <label>{!! Form::radio('format_id', '2',null, ['class'=> "check", 'id' => 'format_id-2', 'data-radio'=>'iradio_square-green','required'=>'required']) !!} ทบทวน &nbsp;&nbsp;</label>
    </div>
</div>

<div class="form-group {{ $errors->has('standard_id') ? 'has-error' : ''}}" id="box_std" style="display: none;">
    {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('standard_id',
            App\Models\Certify\Standard::selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id'),
            null,
            ['class' => 'form-control',
            'id'=>'standard_id',
            'placeholder'=>'- เลือกมาตรฐาน -']) !!}
        {!! $errors->first('standard_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_no') ? 'has-error' : ''}}">
    {!! Form::label('std_no', 'เลขมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-2">
        {!! Form::text('std_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'ระบุเลขมาตรฐาน'] : ['class' => 'form-control', 'placeholder' => 'ระบุเลขมาตรฐาน']) !!}
        {!! $errors->first('std_no', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('std_book', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'เล่ม'] : ['class' => 'form-control', 'placeholder' => 'เล่ม']) !!}
        {!! $errors->first('std_book', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::select('std_year', HP::Years(), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกปีมาตรฐาน-'] : ['class' => 'form-control', 'placeholder' => '-เลือกปีมาตรฐาน-']) !!}
        {!! $errors->first('std_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_title') ? 'has-error' : ''}}">
    {!! Form::label('std_title', 'ชื่อมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('std_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('std_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_title_en') ? 'has-error' : ''}}">
    {!! Form::label('std_title_en', 'ชื่อมาตรฐาน (eng):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('std_title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('std_title_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!! Form::label('method_id', 'วิธีการ:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('method_id', App\Models\Basic\Method::where('state',1)->pluck('title','id'), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกวิธีการ-'] : ['class' => 'form-control', 'placeholder' => '-เลือกวิธีการ-']) !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('ref_document') ? 'has-error' : ''}}">
    {!! Form::label('ref_document', 'เอกสารอ้างอิง:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('ref_document', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('ref_document', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('reason') ? 'has-error' : ''}}">
    {!! Form::label('reason', 'เหตุผลเเละความจำเป็น:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('reason', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('reason', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time', null ,  ['class' => 'form-control']) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('industry_target',
                          App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),//อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต
                          null,
                        ['class' => 'form-control',
                         'placeholder' => '- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -'
                        ])
        !!}
        {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('ics') ? 'has-error' : ''}}">
    {!! Form::label('ics', 'ICS :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('ics[]', App\Models\Basic\Ics::selectRaw('CONCAT(code," ",title_en) As title, id')->pluck('title', 'id'), !empty($standard_ics)?$standard_ics:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'ics', 'data-placeholder'=>'- เลือก ICS -']) !!}
        {!! $errors->first('ics', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_force') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('std_force', 'สถานะมาตรฐาน :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-7">
        <label>{!! Form::radio('std_force', 'ท',null, ['class'=> "check", 'data-radio'=>'iradio_square-green' ,'required'=>'required']) !!} ทั่วไป &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</label>
        <label>{!! Form::radio('std_force', 'บ',null, ['class'=> "check", 'data-radio'=>'iradio_square-green', 'required'=>'required']) !!} บังคับ &nbsp;&nbsp;</label>
    </div>
</div>

<div class="form-group required {{ $errors->has('std_abstract') ? 'has-error' : ''}}">
    {!! Form::label('std_abstract', 'บทคัดย่อ (TH):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('std_abstract', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=> '2'] : ['class' => 'form-control', 'rows'=> '2']) !!}
        {!! $errors->first('std_abstract', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_abstract_en') ? 'has-error' : ''}}">
    {!! Form::label('std_abstract_en', 'บทคัดย่อ (EN):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('std_abstract_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=> '2'] : ['class' => 'form-control', 'rows'=> '2']) !!}
        {!! $errors->first('std_abstract_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">สถานะการจัดทำมาตรฐาน</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body panel-body-info">

                    <div class="form-group required {{ $errors->has('status_id') ? 'has-error' : ''}}">
                        {!! Form::label('status_id', 'ขั้นตอนการดำเนินงาน:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            @php
                            $arr_status = [
                            '4'=> 'อยู่ระหว่างจัดทำมาตรฐานการรับรอง',
                            '5'=> 'แจ้งระบุเลข ISBN',
                            '6'=> 'ดำเนินการ และเสนอผู้มีอำนาจลงนาม',
                            '7'=> 'ลงนามเรียบร้อย',
                            '8'=> 'เสนอราชกิจจานุเบกษา',
                            ];
                            @endphp
                            {!! Form::select('status_id', $arr_status, null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'status_id'] : ['class' => 'form-control', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'status_id']) !!}
                            {!! $errors->first('status_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('isbn_no') ? 'has-error' : ''}}">
                        {!! Form::label('isbn_no', 'ไฟล์หน้าปก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            <button class="btn" style="background-color:white" name="submit" type="submit" value="print" id="print" onclick="submit_form('print')">
                                    <i class="glyphicon glyphicon-download-alt fa-2x icon_print" style="top:-5px"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="box_sendmail_isbn" style="display:none">
                        <div class="form-group required{{ $errors->has('user_by') ? 'has-error' : ''}}">
                            {!! Form::label('user_by', 'แจ้งเจ้าหน้าที่กรอกเลข ISBN :', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::select('user_by[]', App\User::selectRaw('CONCAT(reg_fname," ",reg_lname," (",reg_email,") ") As title, runrecno')->whereHas(
                                    'data_list_roles', function($q){
                                        $q->where('role_id', 33);
                                    }
                                )->pluck('title', 'runrecno'), !empty($standard_sendmail)?$standard_sendmail:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'user_by', 'data-placeholder'=>'- เลือก เจ้าหน้าที่กรอกเลข ISBN -']) !!}
                                {!! $errors->first('user_by', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('isbn_no') ? 'has-error' : ''}}">
                        {!! Form::label('isbn_no', 'เลข ISBN:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('isbn_no', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'disabled'=>true] : ['class' => 'form-control', 'disabled'=>true]) !!}
                            {!! $errors->first('isbn_no', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group isbn_by {{ $errors->has('isbn_by') ? 'has-error' : ''}}">
                        {!! Form::label('isbn_by', 'ผู้ดำเนินการระบุเลข:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('isbn_by',null, ['class' => 'form-control', 'disabled'=>true]) !!}
                            {!! $errors->first('isbn_by', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('std_sign_date') ? 'has-error' : ''}}">
                        {!! Form::label('std_sign_date', 'วันที่ลงนามการจัดทำมาตรฐาน'.':', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('std_sign_date', null, ('' == 'required') ? ['class' => 'form-control mydatepicker',
                                'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                                {!! $errors->first('judgement_date', '<p class="help-block">:message</p>') !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group {{ $errors->has('std_signname') ? 'has-error' : ''}}">
                        {!! HTML::decode(Form::label('std_signname','ผู้ลงนาม :', ['class' => 'col-md-3 control-label label-filter text-right'])) !!}
                        <div class="col-md-7">
                            {!! Form::select('std_signname', App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id'),null,  ['class' => 'form-control select2', 'placeholder'=>'- เลือกผู้ลงนาม -',  'id' =>'sign_id']); !!}
                            {!! $errors->first('std_signname', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
        
                    <div class="form-group {{ $errors->has('std_signposition') ? 'has-error' : ''}}">
                        {!! HTML::decode(Form::label('std_signposition', 'ตำแหน่ง :', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-7">
                            {!! Form::text('std_signposition',null, ['class' => 'form-control','id'=>'sign_position']) !!}
                            {!! $errors->first('std_signposition', '<p class="help-block">:message</p>') !!}
                        </div>                   
                    </div>

                    <div class="form-group {{ $errors->has('other_attach') ? 'has-error' : ''}}">
                        {!! Form::label('std_file', 'ไฟล์การลงนามการจัดทำมาตรฐาน'.':', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            @if(isset($standard) && !is_null($standard->std_file))
                                <a href="{!! HP::getFileStorage($standard->std_file) !!}" target="_blank">
                                    {!! HP::FileExtension($standard->std_file) ?? '' !!}
                                </a>
                            @else
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    {!! Form::file('std_file', null, ['required']) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('std_page') ? 'has-error' : ''}}">
                        {!! Form::label('std_page', 'จำนวนหน้า:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-4">
                            {!! Form::text('std_page', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('std_page', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('std_price') ? 'has-error' : ''}}">
                        {!! Form::label('std_price', 'ราคา:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                            {!! Form::text('std_price', null, ('' == 'required') ? ['class' => 'form-control amount text-right', 'required' => 'required'] : ['class' => 'form-control amount text-right']) !!}
                            {!! $errors->first('std_price', '<p class="help-block">:message</p>') !!}
                            <span class="input-group-addon">บาท</i></span>
                        </div>
                        </div>
                    </div>

             

                    <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
                        {!! Form::label('remark', 'หมายเหตุ : ', ['class' => " control-label col-md-3"]) !!}
                        <div class="col-md-7">
                            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 4, 'v-model' => 'form.remark']) !!}
                            {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('gazette_state') ? 'has-error' : ''}}">
                        {!! HTML::decode(Form::label('', '&nbsp;', ['class' => 'col-md-3 control-label label-height'])) !!}
                        <div class="col-md-6">
                            <div class="checkbox checkbox-success  label-height">
                                <input id="gazette_state" class="gazette_state checkbox" type="checkbox" name="gazette_state" value="1" {!! !empty($standard)?(($standard->gazette_state ==1)?'checked':''):''  !!}>
                                <label for="gazette_state"  class="label-height">&nbsp;ประกาศในราชกิจจานุเบกษา&nbsp;</label>
                            </div>
                        </div>
                    </div>


                    <div class="box_gazette" style="display:none">

 
                        <div class="form-group  {{ $errors->has('gazette_book') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('gazette_book', 'ฉบับ:', ['class' => 'col-md-3 control-label  text-left'])) !!}
                            <div class="col-md-3">
                                {!! Form::text('gazette_book', null, ['class' => 'form-control','readonly'=>true]) !!}
                                {!! $errors->first('gazette_book', '<p class="help-block">:message</p>') !!}
                            </div>
                            {!! Form::label('gazette_govbook', 'ที่:', ['class' => 'col-md-1 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('gazette_govbook', null,['class' => 'form-control','readonly'=>true]) !!}
                                {!! $errors->first('gazette_govbook', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
    
                        <div class="form-group {{ $errors->has('gazette_no') ? 'has-error' : ''}}">
                            {!! Form::label('gazette_no', 'เล่ม:', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('gazette_no', null, ['class' => 'form-control','readonly'=>true]) !!}
                                {!! $errors->first('gazette_no', '<p class="help-block">:message</p>') !!}
                            </div>
                            {!! Form::label('gazette_section', 'ตอน:', ['class' => 'col-md-1 control-label']) !!}
                            <div class="col-md-3">
                                {!! Form::text('gazette_section', null, ['class' => 'form-control','readonly'=>true]) !!}
                                {!! $errors->first('gazette_section', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
    
                        <div class="form-group  {{ $errors->has('gazette_post_date') ? 'has-error' : ''}}">
                            {!! Form::label('gazette_post_date', 'วันที่ประกาศในราชกิจจานุเบกษา:', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('gazette_post_date', null, ('' == 'required') ? ['class' => 'form-control',
                                    'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','readonly'=>true] : ['class' => 'form-control', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','readonly'=>true]) !!}
                                    {!! $errors->first('gazette_post_date', '<p class="help-block">:message</p>') !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group {{ $errors->has('gazette_effective_date') ? 'has-error' : ''}}">
                            {!! Form::label('gazette_effective_date', 'วันที่มีผลใช้งาน/บังคับ:', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('gazette_effective_date', null, ('' == 'required') ? ['class' => 'form-control ',
                                    'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','readonly'=>true] : ['class' => 'form-control ', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','readonly'=>true]) !!}
                                    {!! $errors->first('gazette_effective_date', '<p class="help-block">:message</p>') !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group {{ $errors->has('gazette_file') ? 'has-error' : ''}}">
                            {!! Form::label('gazette_file', 'ไฟล์ประกาศในราชกิจจานุเบกษา:', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-6">
                                @if(isset($standard) && !is_null($standard->gazette_file))
                                    <a href="{!! HP::getFileStorage($standard->gazette_file) !!}" target="_blank">
                                        {!! HP::FileExtension($standard->gazette_file) ?? '' !!}
                                    </a>
                                @else
                            
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('gazette_file', null, ['required','readonly'=>true]) !!}
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                @endif

                            </div>
                        </div>
    
                    </div>
                    
              
                    <div class="form-group {{ $errors->has('publish_state') ? 'has-error' : ''}}">
                        {!! HTML::decode(Form::label('publish_state', 'สถานะการเผยแพร่:', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-2">
                            {!! Form::radio('publish_state', '1', true, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-orange', 'id'=>'publish_state-1', 'required' => false]) !!}
                            {!! Html::decode(Form::label('publish_state-1', 'รอเผยแพร่', ['class' => 'control-label text-capitalize'])) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::radio('publish_state', '2', null, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-orange', 'id'=>'publish_state-2', 'required' => false]) !!}
                            {!! Form::label('publish_state-2', 'เผยแพร่', ['class' => 'control-label text-capitalize']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::radio('publish_state', '3', null, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-orange', 'id'=>'publish_state-3', 'required' => false]) !!}
                            {!! Form::label('publish_state-3', 'ยกเลิก', ['class' => 'control-label text-capitalize']) !!}
                        </div>
                    </div>

                    <div class="form-group required show_if_publish_state_3 {{ $errors->has('revoke_date') ? 'has-error' : ''}}">
                        {!! Form::label('revoke_date', 'วันที่ประกาศยกเลิก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('revoke_date', null, ('' == 'required') ? ['class' => 'form-control mydatepicker',
                                'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'required' => 'required']) !!}
                                {!! $errors->first('revoke_date', '<p class="help-block">:message</p>') !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required show_if_publish_state_3 {{ $errors->has('revoke_remark') ? 'has-error' : ''}}">
                        {!! Form::label('revoke_remark', 'เหตุผลที่ยกเลิก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-7">
                            {!! Form::text('revoke_remark', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('revoke_remark', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group show_if_publish_state_3 {{ $errors->has('revoke_book') ? 'has-error' : ''}}">
                        {!! Form::label('revoke_book', 'ประกาศกระทรวงฯ ฉบับที่:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-3">
                            {!! Form::text('revoke_book', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('revoke_book', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group show_if_publish_state_3 {{ $errors->has('revoke_file') ? 'has-error' : ''}}">
                        {!! Form::label('revoke_file', 'ไฟล์ประกาศการยกเลิก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                            @if(isset($standard) && !is_null($standard->revoke_file))
                            <a href="{!! HP::getFileStorage($standard->revoke_file) !!}" target="_blank">
                                {!! HP::FileExtension($standard->revoke_file) ?? '' !!}
                            </a>
                            @else
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    {!! Form::file('revoke_file', null, ['required']) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
                        {!! Form::label('created_by', 'ผู้บันทึก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-3">
                            <span>{{ !empty($standard->CreatedName)?$standard->CreatedName:(auth()->user()->FullName) }}</span>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : ''}}">
                        {!! Form::label('created_at', 'วันที่บันทึก:', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-3">
                            <span>{{ HP::DateTimeFullThai(date('Y-m-d H:m:s')) }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="submit"  id="standard_pdf">


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit" onclick="submit_form('submit')">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('certifystandard'))
        <a class="btn btn-default" href="{{url('/certify/standards')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
        @endcan
    </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });
        $('#approve_noti_email').tagsinput({
            // itemText: 'label'
        });

        //Check if checkbox is checked or not
        var checkboxChecked = $('#publish_state-3').is(':checked');

        if(checkboxChecked) {
            $('.show_if_publish_state_3').show(300);
        }else{
            $('.show_if_publish_state_3').hide(300);
            $('#revoke_date').attr('required',false)
            $('#revoke_remark').attr('required',false)
        }

        $('#publish_state-3').on('ifChecked', function (event) {
            $('.show_if_publish_state_3').show(300);
            $('#revoke_date').attr('required',true)
            $('#revoke_remark').attr('required',true)
        });

        $('#publish_state-3').on('ifUnchecked', function (event) {
            $('.show_if_publish_state_3').hide(300);
            $('#revoke_date').attr('required',false)
            $('#revoke_remark').attr('required',false)
        });

        $('#format_id-2').on('ifChecked', function (event) {
            $('#box_std').show(300);
            $('#standard_id').attr('required',true)
        });

        $('#format_id-2').on('ifUnchecked', function (event) {
            $('#box_std').hide(300);
            $('#standard_id').attr('required',false);
            $('#standard_id').val('').change();
        });

        var format_id_checked = $('#format_id-2').is(':checked');
        
        if(format_id_checked) {
            $('#box_std').show(300);
            $('#standard_id').attr('required',true)
        }else{
            $('#box_std').hide(300);
            $('#standard_id').attr('required',false)
        }

        $('#gazette_state').change(function (e) {   
            box_gazette();     
        });
        box_gazette();


        $('body').on("change", "#isbn_no",function (event) {
            button_print();
        });
        button_print();

        $('body').on("change", "#status_id",function (event) {
            button_print();
            sendmail();
        });
        sendmail();
        
        $('body').on("keyup change blur keypress", ".amount",function (event) {

            if(event.which >= 37 && event.which <= 40){
                event.preventDefault();
            }

            $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        });

        $('#sign_id').change(function(){ 
            
            if($(this).val() != ''){
                $.ajax({
                    url: "{!! url('certify/standards/sign_position') !!}" + "/" +  $(this).val()
                }).done(function( object ) {
                    $('#sign_position').val(object.sign_position);
                });
            }else{
                $('#sign_position').val('-');
            }
        });

    });

    function submit_form(status) {

    $('#standard_pdf').val(status);
            if(status  == 'print'){
                $('#standard_form').attr('target', '_blank');
                $('#standard_form').submit();
            }else{
                $('#standard_form').attr('target', '');
                $('#standard_form').submit();
            }
    }

    function box_gazette(){
        var gazette_state = $('#gazette_state').is(':checked');

            if(gazette_state){               
                $('.box_gazette').show(200);
            }else{
                $('.box_gazette').hide(400);
                $('.box_gazette').find('input').val('');      
            }
    }  

    function button_print(){
        var isbn_no = $('#isbn_no').val();
        var status_id = $('#status_id').val();
        if(status_id == 5 || (!!isbn_no)){               
            $('#print').attr('disabled',false);
            $(".icon_print").css("color","#0000FF");      
        }else{
            $('#print').attr('disabled',true);
            $(".icon_print").css("color","#DCDCDC");     
        }
    }  

    function sendmail(){
        var status_id = $('#status_id').val();
        if(status_id == 5){ 
            $('#box_sendmail_isbn').show();   
            $('#user_by').prop('required', true);
        }else{
            $('#box_sendmail_isbn').hide();
            $('#user_by').prop('required', false)
        }
    }  

</script>

@endpush



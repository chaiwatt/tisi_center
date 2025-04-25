
@php
    $Standardtype     = App\Models\Bcertify\Standardtype::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    $Step1StatusIDArr = [  '4'=> 'อยู่ระหว่างจัดทำมาตรฐานการรับรอง', '5'=> 'แจ้งระบุเลข ISBN' ];
    $UserRoleISBN     = App\User::selectRaw('CONCAT(reg_fname," ",reg_lname," (",reg_email,") ") As title, runrecno')->whereHas( 'subdepart', function($q){   $q->where('dpis_id', 13050); })->pluck('title', 'runrecno');
    $StepStatus1      = isset($standard) && in_array( $standard->status_id, [ 4,5]  )?$standard->status_id:( isset($standard) && $standard->status_id > 5?5:null );
@endphp


<div class="form-group required {{ $errors->has('step_status_1') ? 'has-error' : ''}}">
    {!! Form::label('step_status_1', 'ขั้นตอนการดำเนินงาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('step_status_1', $Step1StatusIDArr, $StepStatus1, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_1'] : ['class' => 'form-control', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_1']) !!}
        {!! $errors->first('step_status_1', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('isbn_no') ? 'has-error' : ''}}">
    {!! Form::label('isbn_no', 'ไฟล์หน้าปก:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        <button class="btn" style="background-color:white" name="submit" type="submit" value="print" id="print" onclick="submit_form('print')">
            <i class="fa fa-download fa-xl"></i>
        </button>
    </div>
</div>

<div id="box_sendmail_isbn" style="display:none">
    <div class="form-group required{{ $errors->has('user_by') ? 'has-error' : ''}}">
        {!! Form::label('user_by', 'แจ้งเจ้าหน้าที่กรอกเลข ISBN :', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::select('user_by[]', $UserRoleISBN , !empty($standard_sendmail)?$standard_sendmail:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'user_by', 'data-placeholder'=>'- เลือก เจ้าหน้าที่กรอกเลข ISBN -']) !!}
            {!! $errors->first('user_by', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('std_sign_date') ? 'has-error' : ''}}">
    {!! Form::label('std_sign_date', 'วันที่ลงนามการจัดทำมาตรฐาน'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        <div class="input-group">
            {!! Form::text('std_sign_date', null, ('' == 'required') ? ['class' => 'form-control mydatepicker', 'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
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

<div class="form-group required{{ $errors->has('std_page') ? 'has-error' : ''}}">
    {!! Form::label('std_page', 'จำนวนหน้า:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('std_page', null, ['class' => 'form-control', 'required' => 'required'] ) !!}
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
        {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 4]) !!}
        {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
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

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary step_save" type="button">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('certifystandard'))
            <a class="btn btn-default" href="{{url('/certify/standards')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
        @if( $step_tap_disabled >= 5 )
            <input type='button' class='btn btn-next btn-fill btn-success' name='next' value='Next' />
        @endif
    </div>
</div>

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('body').on("change", "#step_status_1",function (event) {
                button_print();
                sendmail();
            });
            sendmail();
        });

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
            var status_id = $('#step_status_1').val();
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

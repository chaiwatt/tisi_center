@php
    $Step4StatusIDArr  = [ '7'=> 'ลงนามเรียบร้อย','8'=> 'เสนอราชกิจจานุเบกษา' ];
    $StepStatus4       =  isset($standard) && in_array( $standard->status_id, [ 7,8]  )?$standard->status_id:( isset($standard) && $standard->status_id > 8?8:null );
@endphp

<div class="form-group required {{ $errors->has('status_id') ? 'has-error' : ''}}">
    {!! Form::label('status_id', 'ขั้นตอนการดำเนินงาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('step_status_4', $Step4StatusIDArr,  $StepStatus4 , ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_4'] : ['class' => 'form-control', 'placeholder' => '-เลือกขั้นตอนการดำเนินงาน-' ,'id'=>'step_status_4']) !!}
        {!! $errors->first('step_status_4', '<p class="help-block">:message</p>') !!}
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

<div class="show_if_publish_state_3">
    <div class="form-group required  {{ $errors->has('revoke_date') ? 'has-error' : ''}}">
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

    <div class="form-group required {{ $errors->has('revoke_remark') ? 'has-error' : ''}}">
        {!! Form::label('revoke_remark', 'เหตุผลที่ยกเลิก:', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::text('revoke_remark', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('revoke_remark', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group  {{ $errors->has('revoke_book') ? 'has-error' : ''}}">
        {!! Form::label('revoke_book', 'ประกาศกระทรวงฯ ฉบับที่:', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!! Form::text('revoke_book', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('revoke_book', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('revoke_file') ? 'has-error' : ''}}">
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
        @if( $step_tap_disabled >= 7 )
            <input type='button' class='btn btn-previous btn-fill btn-warning' name='back' value='Back' />
        @endif
        <button class="btn btn-primary step_save" type="button">
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
    <script type="text/javascript">
        $(document).ready(function() {

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

        });

        
        function box_gazette(){
            var gazette_state = $('#gazette_state').is(':checked');

            if(gazette_state){               
                $('.box_gazette').show(200);
            }else{
                $('.box_gazette').hide(400);
                $('.box_gazette').find('input').val('');      
            }
        }  
    </script>
@endpush
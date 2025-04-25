@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'เรื่อง:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('committee') ? 'has-error' : ''}}">
    {!! Form::label('committee', 'เจ้าของเรื่อง:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('committee', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('committee', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('gazette_book') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('gazette_book', 'ฉบับ:'.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label  text-left'])) !!}
    <div class="col-md-3">
        {!! Form::text('gazette_book', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_book', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-1">
        {!! Form::label('gazette_govbook', 'ที่:', ['class' => 'col-md-12 control-label']) !!}
    </div>
    <div class="col-md-3">
        {!! Form::text('gazette_govbook', null, ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_govbook', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('gazette_no') ? 'has-error' : ''}}">
    {!! Form::label('gazette_no', 'เล่ม:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('gazette_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_no', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-1">
        {!! Form::label('gazette_space', 'ตอน:', ['class' => 'col-md-12 control-label']) !!}
    </div>
    <div class="col-md-3">
        {!! Form::text('gazette_space', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_space', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('gazette_date') ? 'has-error' : ''}}">
    {!! Form::label('gazette_date', 'วันที่ประกาศราชกิจจานุเบกษา'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <div class="input-group">
            {!! Form::text('gazette_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required', 'id' => 'gazette_date','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
            {!! $errors->first('gazette_date', '<p class="help-block">:message</p>') !!}
            <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
    </div>
</div>

<div class="form-group required{{ $errors->has('enforce_day') ? 'has-error' : ''}}">
    {!! Form::label('enforce_day', 'จำนวนวันที่มีผลนับจากประกาศ'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <div class="input-group">
            {!! Form::text('enforce_day', null, ['class' => 'form-control number_only','required' => 'required', 'id' => 'enforce_day', 'autocomplete' => 'off']) !!}
            {!! $errors->first('enforce_day', '<p class="help-block">:message</p>') !!}
            <span class="input-group-addon">วัน</span>
        </div>
    </div>
</div>


<div class="form-group {{ $errors->has('enforce_date') ? 'has-error' : ''}}">
    {!! Form::label('enforce_date', 'วันที่มีผลบังคับใช้'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <div class="input-group">
            {!! Form::text('enforce_date', null, ['class' => 'form-control ', 'required' => 'required', 'readonly' => true, 'id' => 'enforce_date', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
            {!! $errors->first('enforce_date', '<p class="help-block">:message</p>') !!}
            <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
    </div>
</div>

<div class="form-group required {{ $errors->has('gazette_signname') ? 'has-error' : ''}}">
    {!! Form::label('gazette_signname', 'ผู้ลงนาม:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('gazette_signname', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_signname', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('gazette_position') ? 'has-error' : ''}}">
    {!! Form::label('gazette_position', 'ตำแหน่งผู้ลงนาม:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('gazette_position', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gazette_position', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@php
$file_gazette = [];
if( !empty($gazette) ){
    $file_gazette = App\AttachFile::where('ref_table', (new App\Models\Certify\Gazette )->getTable() )
                                    ->where('ref_id', $gazette->id)
                                    ->where('section', 'file_gazette')
                                    ->first();
}
@endphp
<div class="form-group required{{ $errors->has('gazette_attach') ? 'has-error' : ''}}">
    {!! Form::label('gazette_attach', 'ไฟล์ประกาศในราชกิจจานุเบกษา'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        @if(!empty($gazette) && !is_null($file_gazette))
            <a href="{!! HP::getFileStorage($file_gazette->url) !!}" target="_blank">
                {!! HP::FileExtension($file_gazette->url) ?? '' !!}
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
                {!! Form::file('file_gazette', null, ['required']) !!}
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
        @endif
    </div>
</div>

<div class="form-group required {{ $errors->has('std_type_id') ? 'has-error' : ''}}">
    {!! Form::label('std_type_id', 'ประเภทมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('std_type_id', App\Models\Bcertify\Standardtype::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกประเภทมาตรฐาน-'] : ['class' => 'form-control', 'placeholder' => '-เลือกประเภทมาตรฐาน-']) !!}
        {!! $errors->first('std_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('gaz_page') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('gaz_page', 'จำนวน(หน้า):', ['class' => 'col-md-3 control-label  text-left'])) !!}
    <div class="col-md-3">
        {!! Form::text('gaz_page', null, ('' == 'required') ? ['class' => 'form-control number_only', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('gaz_page', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('standard_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('standard_id', 'มาตรฐานที่ประกาศ :', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-6">
        @php
        $gazette_standard = isset($gazette_standard) ? $gazette_standard : [] ;
        $certify_standards = App\Models\Certify\Standard::whereIn('id', $gazette_standard)->selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id');
        @endphp
        {!! Form::select('standard_id[]', $certify_standards,!empty($gazette_standard)?$gazette_standard:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'standard_id', 'data-placeholder' => '-เลือกมาตรฐานที่ประกาศ-', 'required' => true]); !!}
        {!! $errors->first('standard_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('send_tis', '1', 
           isset($gazette->send_tis) ? (boolean)$gazette->send_tis : true, 
         ['class'=>'check send_tis','id'=>'send_tis', 'data-checkbox'=>"icheckbox_flat-green"]) !!}
         <label for="send_tis"> &nbsp;นำส่งข้อมูลให้กับ ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร</label>
    </div>
</div>



<div class="form-group">
    {!! Form::label('', 'ผู้บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('', !empty($gazette->created_by)?$gazette->CreatedName:auth()->user()->FullName, ['class' => 'form-control', 'disabled' => true]) !!}
        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('', 'วันที่บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('', !empty($gazette->created_at)?HP::formatDateThaiFull($gazette->created_at):HP::formatDateThaiFull(date('Y-m-d')), ['class' => 'form-control', 'disabled' => true]) !!}
        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<hr>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('gazette'))
            <a class="btn btn-default" href="{{url('/certify/gazette')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });


        $('body').on("keyup", "#enforce_day",function (event) {
            var amountDate = $(this).val();
                if(amountDate){
                    if($('#gazette_date').val()!=""){
                    var array = $('#gazette_date').val().split("/");
                    var gazDate = array[1]+"/"+array[0]+"/"+parseInt(array[2]-543);
                    var newDate = newDayAdd(gazDate,parseInt(amountDate));
                    $('#enforce_date').val(newDate);
                    }else{
                    alert("วันที่ประกาศใช้ในราชกิจจานุเบกษา ไม่มีค่า");
                    }
                } else {
                    $('#enforce_date').val('');
                }
        });

        $(".number_only").on("keypress keyup blur",function (event) {    
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        });

        $('#std_type_id').change(function(event) {

            $('#standard_id').children('option[value!=""]').remove();

            $.ajax({
                url: "{{ url('certify/gazette/get_json_by_standard') }}/"+$(this).val(),
            }).success(function (res) {

                $.each(res, function(index, item) {
                    $('#standard_id').append('<option value="'+item.id+'">'+item.std_full+' '+item.std_title+'</option>');
                });
                $('#standard_id').trigger('change.select2');
            });

        });

    });

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

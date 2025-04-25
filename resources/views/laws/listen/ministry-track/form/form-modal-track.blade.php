@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('date_on') ? 'has-error' : ''}}">
    {!! Form::label('date_on', 'ลงวันที่', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <div class="inputWithIcon">
            {!! Form::text('date_on', null, ['class' => 'form-control mydatepicker',  'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
            {!! $errors->first('date_on', '<p class="help-block">:message</p>') !!}
            <i class="icon-calender"></i>
        </div>
        {!! $errors->first('date_on', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('date_announcement', 'วันที่ประกาศ'.':', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        <div class="inputWithIcon">
            {!! Form::text('date_announcement', null,  ['class' => 'form-control mydatepicker','required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
            {!! $errors->first('date_announcement', '<p class="help-block">:message</p>') !!}
            <i class="icon-calender"></i>
        </div>
        {!! $errors->first('date_announcement', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('date_effective') ? 'has-error' : ''}}">
    {!! Form::label('amount', 'จำนวนวันที่มีผล'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('amount', null, ['class' => 'form-control amount','required' => 'required',]) !!}
        {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('date_effective', 'วันที่มีผลบังคับใช้', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        <div class="inputWithIcon">
            {!! Form::text('date_effective', null,  ['class' => 'form-control', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off', 'readonly' => true, 'required' => 'required']) !!}
            {!! $errors->first('date_effective', '<p class="help-block">:message</p>') !!}
            <i class="icon-calender"></i>
        </div>
        {!! $errors->first('date_effective', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('section') ? 'has-error' : ''}}">
    {!! Form::label('book', 'เล่ม'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('book', null, ['class' => 'form-control']) !!}
        {!! $errors->first('book', '<p class="help-block">:message</p>') !!}
    </div>
    {!! Form::label('section', 'ตอนที่'.':', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('section', null, ['class' => 'form-control']) !!}
        {!! $errors->first('section', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('file_listen_ministry_result') ? 'has-error' : ''}}">
    {!! Form::label('file_listen_ministry_result', 'ไฟล์ประกาศราชกิจจานุเบกษา'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        @if (!empty($listenresults->AttachFileGazette))
            @php
                $attachs_result= $listenresults->AttachFileGazette;
            @endphp
            <a href="{!! HP::getFileStorage($attachs_result->url) !!}" target="_blank">{!! !empty($attachs_result->filename) ? $attachs_result->filename : '' !!}</a>
            {!! HP::FileExtension($attachs_result->url) ?? '' !!}
        @else
            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">เลือกไฟล์</span>
                    <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="file_listen_ministry_result" class="check_max_size_file" required>
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
            </div>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
        <input type="checkbox" class="check" id="status_close"  value="1" name="status_close" data-checkbox="icheckbox_square-green" @if(!empty($lawlistministry->status_close) && ($lawlistministry->status_close == 1)) checked @endif>
        <label for="status_close">ปิดงาน</label>
    </div>
</div> 

<div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
    {!! Form::label('detail', 'รายละเอียดอื่นๆ (ถ้ามี)'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('detail', null, ['class' => 'form-control', 'rows'=> '3','id'=>'comment_more']) !!}
        {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show',auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script>
        $(document).ready(function() {
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            $(".amount").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            });

            $('#sign_id').change(function(){ 
                if($(this).val() != ''){
                    $.ajax({
                        url: "{!! url('certify/certificate-export-cb/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#sign_position').val(object.sign_position);
                    });
                }else{
                    $('#sign_position').val('-');
                }
            });

            $('#date_announcement').change(function (e) { 
                CalExpireDate($(this).val());
            });

            $('body').on("keyup change", "#amount",function (event) {//คำนวณวันที่
                CalExpireDate($('#date_announcement').val());
            });

        });

        function CalExpireDate(date){

            var result = '';
            if( checkNone(date) ){

                var amount = parseInt( $("#amount").val() );
                    amount = checkNone(amount) && amount != 0 ?(amount + 1):1;
                var dates = date.split("/");
                var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);

                if( checkNone(amount) && !isNaN(amount) ){
                    date_start.setDate(date_start.getDate() + (amount)); // + 1 วัน
                }else{
                    date_start.setDate(date_start.getDate() + 1);
                }

                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

                var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
                var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
                var DB = str_pad(date_start.getDate() + 1);

                result = DB+'/'+MB+'/'+YB;

            }
            return $('#date_effective').val(result);

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }
  </script>
@endpush
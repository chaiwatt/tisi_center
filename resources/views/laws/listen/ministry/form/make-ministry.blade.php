

@php
    $option_listen_type = App\Models\Law\Basic\LawListenType::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    $option_signer      = App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->whereJsonContains('main_group','06')->where('state',1)->pluck('name','id');
@endphp

<div class="col-md-10 offset-md-1">

    <div class="form-group {{ $errors->has('ref_no') ? 'has-error' : ''}}">
        {!! Form::label('ref_no', 'เลขอ้างอิง', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('ref_no', !empty($lawlistministry->ref_no)?$lawlistministry->ref_no:null, ['class' => 'form-control', 'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึกข้อมูล', 'disabled'=>true]) !!}
            {!! $errors->first('ref_no', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group  required{{ $errors->has('type') ? 'has-error' : ''}}">
        {!! Form::label('type', 'ประเภท', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::select('listen_type',  $option_listen_type ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภท -', 'required' => true, 'id' => 'listen_type']) !!}
            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group {{ $errors->has('book_no') ? 'has-error' : ''}}">
        {!! Form::label('book_no', 'เลขที่หนังสือ:', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-3">
            {!! Form::text('book_no', null, ['class' => 'form-control ', 'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึกข้อมูล', 'disabled'=>true]) !!}
            {!! $errors->first('book_no', '<p class="help-block">:message</p>') !!}
        </div>
        {!! Form::label('book_date', 'วันที่หนังสือ:', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('book_date',  !empty($lawlistministry->book_date)? HP::revertDate($lawlistministry->book_date,true):HP::revertDate(date('Y-m-d'),true), ['class' => 'form-control mydatepicker', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                {!! $errors->first('book_date', '<p class="help-block">:message</p>') !!}
                <i class="icon-calender"></i>
            </div>
        </div>
    </div>

    <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'ชื่อเรื่องประกาศ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('title', null, ['class' => 'form-control ', 'required' => 'required']) !!}
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('tis_type') ? 'has-error' : ''}}">
        <div class="col-md-offset-4 col-md-7">
            <input type="checkbox" name="tis_type" value="1" class="check" id="tis_type" data-checkbox="icheckbox_square-green" @if(!empty($lawlistministry->tis_type) && ($lawlistministry->tis_type == 1)) checked @endif>
            <label for="tis_type">เลือก มอก. ในฐานข้อมูล</label>
        </div>
    </div>

    <div class="form-group box_tisi">
        {!! Form::label('filter_standard', 'ค้นหา มอก.', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('filter_standard', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group required{{ $errors->has('tis_name') ? 'has-error' : ''}}">
        {!! Form::label('tis_name', 'ผลิตภัณฑ์', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('tis_name', null, ['class' => 'form-control ', 'required' => 'required']) !!}
            {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group required{{ $errors->has('tis_no') ? 'has-error' : ''}}">
        {!! Form::label('tis_no', 'เลข มอก.', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('tis_no', null, ['class' => 'form-control ', 'required' => 'required']) !!}
            {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
     <div class="form-group {{ $errors->has('url') ? 'has-error' : ''}}">
        {!! Form::label('url', 'แบบรับฟังความเห็น', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('url',null, ['class' => 'form-control', 'readonly'=>true, 'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึกข้อมูล']) !!}
            {!! $errors->first('url', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group required{{ $errors->has('amount') ? 'has-error' : ''}}">
        {!! Form::label('amount', 'แสดงความเห็นได้ภายใน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-3">
            <div class="input-group">
                {!! Form::text('amount', 60, ['class' => 'form-control text-center amount', 'required' => 'required']) !!}
                <span class="input-group-addon bg-info b-0 text-white"> วัน </span>
                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        {!! Form::label('date_due', 'ครบกำหนด:', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_due',  !empty($lawlistministry->date_due)? HP::revertDate($lawlistministry->date_due,true):null , ['class' => 'form-control', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off' ,'readonly' => true ]) !!}
                {!! $errors->first('date_due', '<p class="help-block">:message</p>') !!}
                <i class="icon-calender"></i>
            </div>
        </div>
    </div>
    
    <div class="form-group required{{ $errors->has('sign_id') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('sign_id', 'ผู้ลงนาม :', ['class' => 'col-md-4 control-label label-filter text-right'])) !!}
        <div class="col-md-4">
            {!! Form::select('sign_id', $option_signer ,null,  ['class' => 'form-control select2', 'placeholder'=>'- เลือกผู้ลงนาม -',  'id' =>'sign_id']); !!}
            {!! $errors->first('sign_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
 
    <div class="form-group {{ $errors->has('sign_position') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('sign_position', 'ตำแหน่ง :', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-4">
            {!! Form::text('sign_position', null, ['class' => 'form-control','id'=>'sign_position','readonly' => true]) !!}
            {!! $errors->first('sign_position', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group {{ $errors->has('sign_img') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('sign_img', ' ', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-7">
            <input type="checkbox" name="sign_img" value="1" class="check" id="sign_img" data-checkbox="icheckbox_square-green" @if(!empty($lawlistministry->sign_img) && ($lawlistministry->sign_img == 1)) checked @endif>
            <label for="sign_img">แสดงภาพถ่ายลายเซ็น</label>
        </div>
    </div>
    
    <div class="form-group {{ $errors->has('file_signer') ? 'has-error' : ''}}" id="box_sign_img" style="display:none;">
        {!! HTML::decode(Form::label('file_signer', 'ไฟล์แนบลายเซ็น', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-7" id="file_signer"></div>
    </div>
    
    <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
        {!! Form::label('remark', 'หมายเหตุ:', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::textarea('remark', null, ('required' == '') ? ['class' => 'form-control', 'required' => 'required', 'rows'=> '2'] : ['class' => 'form-control', 'rows'=> '2']) !!}
            {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('created_by_show',auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-8">
            {!! Form::text('created_by_show', HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-4 col-md-3">

            <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-listen-ministry'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/listen/ministry') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>

</div>

@push('js')
  <script>
     $(document).ready(function() {

        $(".amount").on("keypress",function(e){
            var eKey = e.which || e.keyCode;
            if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                return false;
            }
        });

        //แสดงภาพภ่ายลายเซ็น
        $('#sign_img').on('ifChanged', function(event){
            var sign_id = $('#sign_id').val();
            if($('#sign_img').is(':checked')){
                $("#box_sign_img").show(200);

                if( sign_id == '' ){
                    $('#file_signer').html('<p class="text-danger m-t-5"><em>กรุณาเลือกผู้ลงนาม</em></p>');
                }
            }else{
                $("#box_sign_img").hide(200);
            }
        });

        $('#sign_id').change(function(){ 
            if($(this).val() != ''){
                $.ajax({
                    url: "{!! url('law/listen/ministry/sign_position') !!}" + "/" +  $(this).val()
                }).done(function( object ) {
                    $('#sign_position').val(object.sign_position);
                    $('#file_signer').html(object.file_signer);
                });
            }else{
                $('#sign_position').val('-');
            }
        });

        $('#tis_type').on('ifChanged', function(event){
            loadBoxTis();
        });

        $("#filter_standard").select2({
            dropdownAutoWidth: true,
            width: '100%',
            ajax: {
                url: "{{ url('/law/funtion/search-standards-td3') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                },
                results: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true,
            },
            placeholder: 'คำค้นหา',
            minimumInputLength: 1,
        });

        $("#filter_standard").change(function(){ 

            $('#tis_no').val('');
            $('#tis_name').val('');

            if( checkNone($(this).val()) ){

                var selected = $(this).select2('data').text;

                if( checkNone(selected) ){
                   
                    var tis = selected.split(':');

                    $('#tis_no').val( $.trim( tis[0]) );
                    $('#tis_name').val( $.trim( tis[1]) );
                    ;
                }
               
            }

        });

        loadBoxTis();

        $('#amount').change(function (e) { 
            CalExpireDate($("#book_date").val()); 
        });

        $('#amount').keyup(function (e) { 
            CalExpireDate($("#book_date").val());
        });

        $('#book_date').change(function (e) { 
            CalExpireDate($(this).val());
        });

        CalExpireDate($("#book_date").val());
    
    });

    function loadBoxTis(){

        var box_tisi = $('.box_tisi');

        $('#filter_standard').select2('val','');

        if( $('#tis_type').is(':checked') ){
            box_tisi.show(200);
            $('#tis_no').prop('readonly', true);
            $('#tis_name').prop('readonly', true);

        }else{
            box_tisi.hide(200);
            $('#tis_no').prop('readonly', false);
            $('#tis_name').prop('readonly', false);
        }

    }

    function CalExpireDate(date){

        var result = '';
        if( checkNone(date) ){

            var amount = parseInt( $("#amount").val() );
                amount = checkNone(amount) && amount != 0 ?amount:1;
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
            var DB = str_pad(date_start.getDate());

            result = DB+'/'+MB+'/'+YB;

        }
        return $('#date_due').val(result);

    }

    function str_pad(str) {
        if (String(str).length === 2) return str;
        return '0' + str;
    }
  </script>
@endpush
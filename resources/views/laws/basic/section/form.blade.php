@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('number') ? 'has-error' : ''}}">
    {!! Form::label('number', 'เลขมาตรา', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('number', null, ['class' => 'form-control ', 'required' => 'required']) !!}
        {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'คำอธิบายมาตรา', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::textarea('title', null , ['class' => 'form-control ', 'required' => 'required', 'rows'=>'3' ]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('conditon_cert') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-8">
        <input type="checkbox" class="check" id="conditon_cert"  value="1" name="conditon_cert" data-checkbox="icheckbox_square-green" @if(!empty($law_section->conditon_cert) &&  $law_section->conditon_cert = 1)checked @endif>
            <label for="conditon_cert">มีใบอนุญาต</label>
    </div>
</div> 

<div class="form-group required{{ $errors->has('section_type') ? 'has-error' : ''}}">
    {!! Form::label('section_type', 'ประเภทมาตราความผิด'.':', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('section_type', App\Models\Law\Basic\LawSection::list_section_type(), null, ['class' => 'form-control', 'id' => 'section_type', 'required' => 'required', 'placeholder'=>'-เลือกประเภทมาตราความผิด-']); !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('date_announce') ? 'has-error' : ''}}">
    {!! Form::label('date_announce', 'วันที่ประกาศ'.':', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-3">
        <div class="inputWithIcon">
            {!! Form::text('date_announce', null, ['class' => 'form-control mydatepicker ', 'required' => 'required', 'id' => 'start_date','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
            {!! $errors->first('date_announce', '<p class="help-block">:message</p>') !!}
            <i class="icon-calender"></i>
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
    {!! Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::textarea('remark', null , ['class' => 'form-control ', 'rows'=>'3' ]) !!}
        {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label class="m-l-15">{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required box_adjustment_type{{ $errors->has('adjustment_type') ? 'has-error' : ''}}">
    {!! Form::label('adjustment_type', 'ประเภทอัตรา', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('adjustment_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue']) !!} ไม่เกิน</label>
        <label class="m-l-15">{!! Form::radio('adjustment_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue']) !!} ช่วงอัตราต่ำสุด / สูงสุด </label>
        <label class="m-l-15">{!! Form::radio('adjustment_type', '3', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue']) !!} ไม่มี </label>

        {!! $errors->first('adjustment_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group box_adjustment {{ $errors->has('adjustment') ? 'has-error' : ''}}">
    {!! Form::label('adjustment', 'อัตราค่าปรับ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('adjustment', null , ['class' => 'form-control input_number', 'required' => 'required' ]) !!}
        {!! $errors->first('adjustment', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group box_adjustment_max {{ $errors->has('adjustment_max') ? 'has-error' : ''}}">
    {!! Form::label('adjustment_max', 'อัตราค่าปรับสูงสุด', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('adjustment_max', null , ['class' => 'form-control input_number']) !!}
        {!! $errors->first('adjustment_max', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($law_section->created_by)? $law_section->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($law_section->created_at)? HP::revertDate($law_section->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-sections'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/basic/section')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

  <script src="{{ asset('js/function.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

        // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
        $(".input_number").on("keypress",function(e){
            var eKey = e.which || e.keyCode;
            if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                return false;
            }
        }); 

        $("input[name='adjustment_type']").on("ifChanged", function(event) {
            condition();
        });

        $("#adjustment_max").on("blur",function(e){ 
            let min     = $('#adjustment').val();
            let min_val = parseFloat( RemoveCommas( checkNone(min)?min:"0" ) );

            let max     = $(this).val();
            let max_val = parseFloat( RemoveCommas( checkNone(max)?max:"0" ) );

            if( max_val <= min_val ){
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'กรุณกรอกอัตราค่าปรับสูงสุดให้มากกว่าอัตราค่าปรับต่ำสุด ?',
                    showConfirmButton: true,
                });
            }
        });

        $('#section_type').change(function (e) { 
            condition();
        });

        condition();
    });

    function condition(){

        var box                 = $('.box_adjustment');
        var box_max             = $('.box_adjustment_max');
        var box_adjustment_type = $('.box_adjustment_type');
        
        var section_type = $('#section_type').val();

        if( checkNone(section_type) && section_type == 2 ){
            var type         = $("input[name=adjustment_type]:checked").val();

            box_adjustment_type.show();
            box_adjustment_type.find('input').prop('disabled', false);
            
            if( type == 1 ){

                box.show();
                box.find('#adjustment').prop('disabled', false);
                box.find('#adjustment').prop('required', true);

                box_max.hide();
                box_max.find('#adjustment_max').prop('disabled', true);
                box_max.find('#adjustment_max').prop('required', false);

            }else if(  type == 2  ){

                box.show();
                box.find('#adjustment').prop('disabled', false);
                box.find('#adjustment').prop('required', true);

                box_max.show();
                box_max.find('#adjustment_max').prop('disabled', false);
                box_max.find('#adjustment_max').prop('required', true);
            }else{

                box.hide();
                box.find('#adjustment').prop('disabled', true);
                box.find('#adjustment').prop('required', false);

                box_max.hide();
                box_max.find('#adjustment_max').prop('disabled', true);
                box_max.find('#adjustment_max').prop('required', false);

            }
        }else{
            box_adjustment_type.hide();
            box_adjustment_type.find('input').prop('disabled', true);

            box.hide();
            box.find('#adjustment').prop('disabled', true);
            box.find('#adjustment').prop('required', false);

            box_max.hide();
            box_max.find('#adjustment_max').prop('disabled', true);
            box_max.find('#adjustment_max').prop('required', false);

        }

    }

    function checkNone(value) {
        return value !== '' && value !== null && value !== undefined;
    }

</script>

@endpush
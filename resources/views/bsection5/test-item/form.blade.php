@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

@php
    $list_standard = App\Models\Basic\Tis::select('tb3_Tisno', 'tb3_TisThainame', 'tb3_TisAutono')->orderBy('tb3_Tisno')->get();

    $option_standard = [];
    foreach ($list_standard as $key => $item ) {
        $number = $item->tb3_Tisno;
        $option_standard[$item->getKey()] = $number.' : '.(strip_tags($item->tb3_TisThainame));
    }


@endphp

<div class="form-group required {{ $errors->has('tis_id') ? 'has-error' : ''}}">
    {!! Form::label('tis_id', 'มอก.', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('tis_id',  $option_standard  , null, ['class' => 'form-control', 'placeholder'=>'- เลือกมอก. -', 'required' => 'required']) !!}
        {!! $errors->first('tis_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'ประเภท', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('type', '1', true, ['class'=>'check', 'data-radio'=>'icheckbox_flat-green', 'id' => 'condition_1', 'required' => 'required']) !!} หัวข้อทดสอบ</label>
        <label>{!! Form::radio('type', '2', false, ['class'=>'check', 'data-radio'=>'icheckbox_flat-green', 'id' => 'condition_2', 'required' => 'required']) !!} หัวข้อทดสอบย่อย</label>
        <label>{!! Form::radio('type', '3', false, ['class'=>'check', 'data-radio'=>'icheckbox_flat-green', 'id' => 'condition_3']) !!} รายการทดสอบ</label>
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
    {!! Form::label('no', 'ข้อ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('no', null, ['class' => 'form-control']) !!}
        {!! $errors->first('no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'หัวข้อ/รายการทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('unit_id') ? 'has-error' : ''}}">
    {!! Form::label('unit_id', 'หน่วย', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('unit_id', App\Models\Bsection5\Unit::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วย -']) !!}
        {!! $errors->first('unit_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group{{ $errors->has('criteria') ? 'has-error' : ''}}">
    {!! Form::label('criteria', 'เกณฑ์กำหนด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('criteria', null, ['class' => 'form-control']) !!}
        {!! $errors->first('criteria', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group box_parent {{ $errors->has('parent_id') ? 'has-error' : ''}}">
    {!! Form::label('parent_id', 'อยู่ภายใต้หัวข้อทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('parent_id', isset($testitem->id)? App\Models\Bsection5\TestItem::whereIn('type', [1,2])->where('tis_id', $testitem->tis_id)->select(DB::raw("CONCAT( '(',CASE WHEN type = 1  THEN  'หัวข้อทดสอบ' WHEN type = 2 THEN 'หัวข้อทดสอบย่อย' END, ') '  ,IF(no IS NULL, '', no) , IF(no IS NULL, '', ' '),title) AS no"),'id')->orderBy('main_topic_id')->pluck('no', 'id'):[], null, ['class' => 'form-control', 'placeholder'=>'- เลือกภายใต้หัวข้อทดสอบ -']) !!}
        {!! $errors->first('parent_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('test_method_id') ? 'has-error' : ''}}">
    {!! Form::label('test_method_id', 'วิธีทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('test_method_id', App\Models\Bsection5\TestMethod::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกวิธีทดสอบ -']) !!}
        {!! $errors->first('test_method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('test_tools_ids') ? 'has-error' : ''}}">
    {!! Form::label('test_tools_ids', 'เครื่องมือทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('test_tools_ids[]', App\Models\Bsection5\TestTool::pluck('title', 'id'), ( isset($tools) && is_array($tools) && count($tools) > 0 ?$tools:null ), ['class' => '', 'data-placeholder'=>'- เลือกเครื่องมือทดสอบ -', 'multiple' => 'multiple']) !!}
        {!! $errors->first('test_tools_ids', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('input_result') ? 'has-error' : ''}}">
    {!! Form::label('input_result', 'กรอกผลการทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('input_result', '1', true, ['class'=>'check', 'data-radio'=>'icheckbox_flat-green', 'required' => 'required']) !!} ได้</label>
        <label>{!! Form::radio('input_result', '2', false, ['class'=>'check', 'data-radio'=>'icheckbox_flat-green']) !!} ไม่ได้</label>
        {!! $errors->first('input_result', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('amount_test_list') ? 'has-error' : ''}}">
    {!! Form::label('amount_test_list', 'จำนวนครั้งในการทดสอบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('amount_test_list', HP::RangeData(1,10), null, ['class' => 'form-control', 'placeholder'=>'- เลือกจำนวนครั้งในการทดสอบ -']) !!}
        {!! $errors->first('amount_test_list', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('bsection5-testitem'))
            <a class="btn btn-default show_tag_a" href="{{url('/bsection5/test_item')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>
        $(document).ready(function () {

            $("#unit_id").select2({
                formatResult: formatState,
                formatSelection: formatState,
                escapeMarkup: function(m) { return m; }
            });


            $('#condition_1').on('ifChecked', function(event){
                BoxCondition();
            });

            $('#condition_2').on('ifChecked', function(event){
                BoxCondition();
            });

            $('#condition_3').on('ifChecked', function(event){
                BoxCondition();
            });

            BoxCondition();

            $('#tis_id').change(function (e) { 
                $("#parent_id").html('<option value=""> -เลือกภายใต้หัวข้อทดสอบ- </option>');

                $.ajax({
                    url: "{!! url('/bsection5/test_item/main/get-data-item') !!}" + "?tis_id=" + $(this).val()
                }).done(function( object ) {
                    $.each(object, function( index, data ) {
                        $("#parent_id").append('<option value="'+index+'">'+data+'</option>');
                    });
                });
            });

        });

        function BoxCondition(){

            if( $('#condition_1').is(':checked',true) ){

                $('.box_parent').hide();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', false);

            }else if( $('#condition_2').is(':checked',true) || $('#condition_3').is(':checked',true)  ){

                $('.box_parent').show();
                $('.box_parent').find('input, select, hidden, checkbox').prop('disabled', false);

            }

        }

        function formatState (option) {
            if (!option.id) return option.text; // optgroup
            return option.text;
        }
    </script>
@endpush

<div class="form-group {{ $errors->has('projectid') ? 'has-error' : ''}}">
    {!! Form::label('projectid', 'รหัสงาน (Project ID) :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('projectid',null, ['class' => 'form-control ', 'disabled' => true, 'placeholder'=>' อยู่ระหว่างรับเรื่อง ']) !!}
        {!! $errors->first('projectid', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('format_id') ? 'has-error' : ''}}">
    {!! HTML::decode( Form::label('format_id', 'รูปแบบ :'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('format_id', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => true, 'placeholder'=>'- เลือกรูปแบบ -']) !!}
        {!! $errors->first('format_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!!  HTML::decode(Form::label('method_id', 'วิธีการ :'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('method_id', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => true, 'placeholder'=>'- เลือกวิธีการ -']) !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class=" form-group">
    <div class="  {{ $errors->has('plan_time') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('plan_time', 'ประมาณการจำนวนครั้งการประชุม :'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
        <div class="col-md-4">
             <div class="input-group" >
                {!! Form::text('plan_time',null, ['class' => 'form-control text-right', 'required' => true]) !!}
                <span class="input-group-addon bg-secondary  b-0 text-dark"> ครั้ง </span>
            </div>
        </div>
    </div>
</div>

<div class=" form-group">
    <div class="  {{ $errors->has('estimate_cost') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('estimate_cost', 'การประมาณการค่าใช้จ่าย :', ['class' => 'col-md-3  control-label '])) !!}
        <div class="col-md-4">
             <div class="input-group" >
                {!! Form::text('estimate_cost',!empty($setstandard->estimate_cost )? number_format($setstandard->estimate_cost,2):null, ['class' => 'form-control amount text-right', 'required' => true]) !!}
                <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
            </div>
        </div>
    </div>
</div>

<div class=" form-group">
    <div class="  {{ $errors->has('commitee_id') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('commitee_id', '<span class="select-label">คณะวิชาการกำหนด :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
        <div class="col-md-4">
            <button type="button" class="btn btn-sm btn-success select-add"  id="select-add">
                <i class="icon-plus"></i>&nbsp;เพิ่ม
            </button>
        </div>
    </div>
</div>
<div id="select-box">

    @if(!empty($setstandard_commitees) && count($setstandard_commitees) > 0)
        @foreach ($setstandard_commitees as $key => $commitee)
            <div class="form-group expert_item{{ $errors->has('method_id') ? 'has-error' : ''}}">
                <div class="col-md-3"> </div>

                <div class="col-md-7">
                    {!! Form::select('commitee_id[]', App\CommitteeSpecial::pluck('committee_group', 'id'), $commitee->commitee_id, ['class' => 'form-control select2', 'placeholder'=>'- เลือกคณะวิชาการกำหนด -', 'required' => true]) !!}
                    {!! $errors->first('commitee_id', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger btn-sm select-remove" type="button"> <i class="icon-close"></i> ลบ </button>
                </div>
            </div>
        @endforeach
    @else
        <div class="form-group expert_item{{ $errors->has('method_id') ? 'has-error' : ''}}">
            <div class="col-md-3"> </div>

            <div class="col-md-7">
                {!! Form::select('commitee_id[]', App\CommitteeSpecial::pluck('committee_group', 'id'), null, ['class' => 'form-control select2', 'placeholder'=>'- เลือกคณะวิชาการกำหนด -']) !!}
                {!! $errors->first('commitee_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger btn-sm select-remove" type="button"> <i class="icon-close"></i> ลบ </button>
            </div>
        </div>
    @endif
</div>

@push('js')

  <script>
    $(document).ready(function () {

        //เพิ่มคณะวิชาการกำหนด
        $('#select-add').click(function(event) {

            $('.expert_item:first').clone().appendTo('#select-box');

            var select_last_new = $('.expert_item:last');

            $(select_last_new).find('select').val('');
            $(select_last_new).find('select').prev().remove();
            $(select_last_new).find('select').removeAttr('style');
            $(select_last_new).find('select').select2();

            ShowHideRemove();
        });

        //ลบคณะวิชาการกำหนด
        $('body').on('click', '.select-remove', function(event) {
            $(this).parent().parent().remove();
            ShowHideRemove();
         });

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

        ShowHideRemove();

    });

    function ShowHideRemove(){
        if($('.select-remove').length > 1){
            $('.select-remove').show();
        }else{
            $('.select-remove').hide();
        }
    }

</script>
@endpush

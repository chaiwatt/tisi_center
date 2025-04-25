@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อหน่วยงาน', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => 255]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('tis_id') ? 'has-error' : ''}}">
    {!! Form::label('tis_id', 'มอก.', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('tis_id[]',App\Models\Basic\Tis::select(DB::Raw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) AS title, tb3_TisAutono'))->pluck('title', 'tb3_TisAutono'), null,['class' => 'select2 select2-multiple ', 'multiple'=>'multiple', 'data-placeholder'=>'- เลือก มอก. -','id'=>'tisi_no']) !!}
        {!! $errors->first('tis_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('address_no') ? 'has-error' : ''}}">
    {!! Form::label('address_no', 'เลขที่, อาคาร, ชั้น, เลขที่ห้อง, ชื่อหมู่บ้าน', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::textarea('address_no', null, ['class' => 'form-control', 'rows' => '2', 'required' => true, 'maxlength' => 150]) !!}
        {!! $errors->first('address_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('moo') ? 'has-error' : ''}}">
    {!! Form::label('moo', 'หมู่', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('moo', null, ['class' => 'form-control', 'maxlength' => 80]) !!}
        {!! $errors->first('moo', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('soi') ? 'has-error' : ''}}">
    {!! Form::label('soi', 'ตรอก/ซอย', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('soi', null, ['class' => 'form-control', 'maxlength' => 80]) !!}
        {!! $errors->first('soi', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('street') ? 'has-error' : ''}}">
    {!! Form::label('street', 'ถนน', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('street', null, ['class' => 'form-control', 'maxlength' => 80]) !!}
        {!! $errors->first('street', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('province_id') ? 'has-error' : ''}}">
    {!! Form::label('province_id', 'จังหวัด', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('province_id', App\Models\Basic\Province::whereNull('state')->pluck('PROVINCE_NAME', 'PROVINCE_ID'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกจังหวัด -', 'required' => true]) !!}
        {!! $errors->first('province_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('district_id') ? 'has-error' : ''}}">
    {!! Form::label('district_id', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('district_id', isset($amphurs)?$amphurs:[], null, ['class' => 'form-control', 'placeholder'=>'- เลือกอำเภอ -', 'required' => true]) !!}
        {!! $errors->first('district_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('subdistrict_id') ? 'has-error' : ''}}">
    {!! Form::label('subdistrict_id', 'ตำบล/แขวง', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::select('subdistrict_id', isset($districts)?$districts:[], null, ['class' => 'form-control', 'placeholder'=>'- เลือกตำบล -', 'required' => true]) !!}
        {!! $errors->first('subdistrict_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('zipcode') ? 'has-error' : ''}}">
    {!! Form::label('zipcode', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('zipcode', null, ['class' => 'form-control', 'maxlength' => 5]) !!}
        {!! $errors->first('zipcode', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
    {!! Form::label('tel', 'เบอร์โทร', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('tel', null, ['class' => 'form-control', 'maxlength' => 30]) !!}
        {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('mobile') ? 'has-error' : ''}}">
    {!! Form::label('mobile', 'มือถือ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('mobile', null, ['class' => 'form-control', 'maxlength' => 30]) !!}
        {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('fax') ? 'has-error' : ''}}">
    {!! Form::label('fax', 'แฟกซ์', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('fax', null, ['class' => 'form-control', 'maxlength' => 30]) !!}
        {!! $errors->first('fax', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'E-mail', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show', !empty($lawdepartment->created_by)? $lawdepartment->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label font-medium-6']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($lawdepartment->created_at)? HP::revertDate($lawdepartment->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-department-stakeholder'))
            <a class="btn btn-default show_tag_a" href="{{ url('/law/basic/department-stakeholder') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script type="text/javascript">

        jQuery(document).ready(function($) {

            //เมื่อเลือกจังหวัด
            $('#province_id').change(function () {

                $('#district_id, #subdistrict_id').children(":not([value=''])").remove();

                var url = '{{ url('basic/amphur/list') }}/'+$(this).val();
                $.ajax({
                    'type': 'GET',
                    'url': url,
                    'success': function (datas) {

                        $.each(datas, function(index, data) {
                            $('#district_id').append('<option value="'+index+'">'+data+'</option>');
                        });

                    }
                });

            });

            //เมื่อเลือกอำเภอ
            $('#district_id').change(function () {

                $('#subdistrict_id').children(":not([value=''])").remove();

                var url = '{{ url('basic/district/list') }}/'+$(this).val();
                $.ajax({
                    'type': 'GET',
                    'url': url,
                    'success': function (datas) {

                        $.each(datas, function(index, data) {
                            $('#subdistrict_id').append('<option value="'+index+'">'+data+'</option>');
                        });

                    }
                });

            });

        });
    </script>
@endpush

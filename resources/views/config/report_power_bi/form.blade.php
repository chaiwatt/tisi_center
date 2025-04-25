@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

@php
    $groups = App\Models\Config\ConfigsReportPowerBIGroup::pluck('title', 'id');
@endphp

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อรายงาน <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('group_id') ? 'has-error' : ''}}">
    {!! Form::label('group_id', 'กลุ่ม URL <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'], false) !!}
    <div class="col-md-6">
        {!! Form::select('group_id', $groups, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือก กลุ่มเมนู-']) !!}
        {!! $errors->first('group_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('url') ? 'has-error' : ''}} required">
    {!! Form::label('url', 'URL:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="input-group">
            {!! Form::text('url', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            <span class="input-group-btn">
                <button type="button" class="btn waves-effect waves-light btn-info" id="url-preview" title="ดูตัวอย่างในป๊อปอัพ"><i class="mdi mdi-eye"></i></button>
            </span>
        </div>
        {!! $errors->first('url', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('roles', 'กลุ่มผู้ใช้งาน:', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        @php
            $roles = App\Role::where('label', 'staff')->get();
            $role_checkeds = isset($ssourl) ? $ssourl->roles->pluck('role_id')->toArray() : [];
        @endphp
        @if(count($roles) > 0)

            <div class="checkbox checkbox-success">
                {!! Form::checkbox('role_all', 1, null, ['class' => 'form-control', 'id' => 'role_all']) !!}
                {!! Html::decode(Form::label('role_all', '<b>&nbsp;กลุ่มผู้ใช้งานทั้งหมด (รวมที่จะสร้างใหม่ด้วย)</b>')) !!}
            </div>

            @foreach ($roles as $role)
                <div class="checkbox checkbox-success">
                    {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $role_checkeds), ['class' => 'form-control role', 'id' => 'roles'.$role->id]) !!}
                    {!! Html::decode(Form::label('roles'.$role->id, "&nbsp;{$role->name}")) !!}
                </div>
            @endforeach
        @endif

    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('SsoUrl'))
            <a class="btn btn-default" href="{{url('/config/report-power-bi')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

<div id="preview-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">ตัวอย่างรายงานจาก Power BI</h4>
            </div>
            <div id="preview-body" class="modal-body">
                {{-- ส่วนแสดงข้อมูล --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>

        $(document).ready(function() {

            //เมื่อคลิกปุ่ม preview URL
            $('#url-preview').click(function(event) {
                $('#preview-modal').modal('show');
                $('#preview-body').html('<div class="text-center h2 text-muted"><i class="fa fa-spin fa-spinner"></i> กำลังโหลดข้อมูล......</div>');

                $.ajax({
                    url: "{{ url('config/report-power-bi/preview-url') }}/" + btoa($('#url').val()),
                }).success(function (msg) {
                    $('#preview-body').html(msg);
                });
            });

            //เลือกกลุ่มผู้ใช้งานทั้งหมด
            $('#role_all').change(function(event) {
                if($(this).prop('checked')){//เลือก
                    $('.role').prop('checked', true);
                    $('.role').prop('disabled', true);
                }else{//ไม่เลือก
                    $('.role').prop('checked', false);
                    $('.role').prop('disabled', false);
                }
            });
            if($('#role_all').prop('checked')){
                $('#role_all').change();
            }

        });

    </script>
@endpush

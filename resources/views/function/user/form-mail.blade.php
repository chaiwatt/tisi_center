@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('plugins/components/summernote/summernote.css') }}" />
@endpush

<div class="form-group required{{ $errors->has('invite') ? 'has-error' : ''}}">
    {!! Form::label('invite', 'เรื่อง :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('invite', null, ['class' => 'form-control', 'required' => true]) !!}
        {!! $errors->first('invite', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('quality') ? 'has-error' : ''}}">
    {!! Form::label('quality', 'เนื้อหา :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('quality', null, ['class' => 'form-control textarea', 'required' => true]) !!}
        {!! $errors->first('quality', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  required{{ $errors->has('sender_name') ? 'has-error' : ''}}">
    {!! Form::label('sender_name', 'ชื่อผู้ส่ง :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('sender_name', null, ['class' => 'form-control', 'required' => true]) !!}
        {!! $errors->first('sender_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group{{ $errors->has('password_type') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('send_type', 'ประเภทการส่ง', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                {!! Form::radio('send_type', '1', null, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'password_type-1', 'required' => true]) !!}
                {!! Html::decode(Form::label('send_type-1', 'ส่งรหัสผ่าน', ['class' => 'control-label font-medium-1 text-capitalize'])) !!}
            </div>
            <div class="col-md-4">
                {!! Form::radio('send_type', '2', true, ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'password_type-2', 'required' => true]) !!}
                {!! Form::label('send_type-2', 'ทั่วไป', ['class' => 'control-label font-medium-1 text-capitalize']) !!}
            </div>
        </div>
    </div>
</div>
<div class="box_password">
    <div class="form-group{{ $errors->has('password_type') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('password_type', 'รหัสผ่าน', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::radio('password_type', '1', null, ['class' => 'form-control check password_type', 'data-radio' => 'iradio_flat-blue', 'id'=>'password_type-1', 'required' => true]) !!}
                    {!! Html::decode(Form::label('password_type-1', 'สุ่มรหัสผ่าน 8 หลัก', ['class' => 'control-label font-medium-1 text-capitalize'])) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::radio('password_type', '2', null, ['class' => 'form-control check password_type', 'data-radio' => 'iradio_flat-blue', 'id'=>'password_type-2', 'required' => true]) !!}
                    {!! Form::label('password_type-2', 'เลขบัตรประจำตัวประชาชน', ['class' => 'control-label font-medium-1 text-capitalize']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::radio('password_type', '3', null, ['class' => 'form-control check password_type', 'data-radio' => 'iradio_flat-blue', 'id'=>'password_type-3', 'required' => true]) !!}
                    {!! Form::label('password_type-3', 'กรองเอง', ['class' => 'control-label font-medium-1 text-capitalize']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('new_password') ? 'has-error' : ''}}">
        {!! Form::label('new_password', 'ระบุรหัสผ่าน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('new_password', null, ['class' => 'form-control', 'required' => true]) !!}
            {!! $errors->first('new_password', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="box_nomal">
    <div class="form-group {{ $errors->has('name_send') ? 'has-error' : ''}}">
        {!! Form::label('name_send', 'ชื่อผู้รับ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('name_send', null, ['class' => 'form-control']) !!}
            {!! $errors->first('name_send', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('emails') ? 'has-error' : ''}}">
        {!! Form::label('emails', 'Email ผู้รับ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::email('emails', null, ['class' => 'form-control']) !!}
            {!! $errors->first('emails', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

@isset($data)
    @php
        $attach_path = 'SendMails/File/';
    @endphp
    @if($data->file_attach !='' && HP::checkFileStorage($attach_path.$data->file_attach))

        <div class="form-group {{ $errors->has('file_attach') ? 'has-error' : ''}}">
            {!! Form::label('file_attach', 'แนบไฟล์ :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6" >
                <a href="{{ HP::getFileStorage($attach_path.$data->file_attach) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
            </div>
        </div>
    @else
        <div class="form-group {{ $errors->has('file_attach') ? 'has-error' : ''}}">
            {!! Form::label('file_attach', 'แนบไฟล์ :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6" >
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                        <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        {!! Form::file('file_attach', null) !!}
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
        
            </div>
        </div>
    @endif


@endisset


@if( !isset($data) )
    <div class="form-group {{ $errors->has('file_attach') ? 'has-error' : ''}}">
        {!! Form::label('file_attach', 'แนบไฟล์ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6" >
            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                    <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">เลือกไฟล์</span>
                    <span class="fileinput-exists">เปลี่ยน</span>
                    {!! Form::file('file_attach', null) !!}
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
            </div>

        </div>
    </div>
@endif


<div class="form-group {{ $errors->has('information') ? 'has-error' : ''}}">
    {!! Form::label('information', 'ข้อมูลการติดต่อ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('information', null, ['class' => 'form-control textarea','rows' => 2]) !!}
        {!! $errors->first('information', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@php
    $i = 0;
@endphp
@if( !isset($data) )
    <div class="clearfix"></div>
    <hr>
    <div class="row box_password">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-borderless" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><input type="checkbox" id="checkall"></th>
                            <th class="text-center" width="30%">ชื่อ - สกุล</th>
                            <th class="text-center" width="30%">Email</th>
                            <th class="text-center" width="30%">Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($user)
                            @foreach ( $user as $item )
                                @if( filter_var( $item->agent_email, FILTER_VALIDATE_EMAIL) ||  strpos( $item->agent_email, '@') )
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>{!! $i !!}</td>
                                        <td><input type="checkbox" name="user_trader_id[]" class="user_trader_id" value="{!! $item->trader_autonumber !!}" checked></td>
                                        <td>{!! $item->trader_operater_name !!}</td>
                                        <td>{!! $item->agent_email !!}</td>
                                        <td>{!! $item->trader_username !!}</td>
                                    </tr>
                                @endif
                            
                            @endforeach 
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@isset($data)
    @php
        $list = !empty($data->data_multi)? json_decode($data->data_multi):[];
    @endphp
    <div class="clearfix"></div>
    <hr>
    <div class="row box_password">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-borderless" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">ชื่อ - สกุล</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($list)
                            @php
                                $i = 0;
                            @endphp
                            @foreach ( $list as $item )
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td>{!! $i !!}</td>
                                    <td>{!! isset($item->trader_operater_name)?$item->trader_operater_name:null !!}</td>
                                    <td>{!! isset($item->agent_email)?$item->agent_email:null !!}</td>
                                    <td>{!! isset($item->trader_username)?$item->trader_username:null !!}</td>
                                    <td>{!! isset($item->status)?$item->status:null !!}</td>
                                </tr>
                                
                            @endforeach   
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endisset


@if( !isset($data) )
    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
            <button class="btn btn-primary" type="submit" id="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            <a class="btn btn-default" href="{{url('/page/send-mails/user')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        </div>
    </div>
@endif


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script type="text/javascript">

        jQuery(document).ready(function($) {
            $('input[name="password_type"]').on('ifChecked', function(event){
                DisableInput($(this).val())
            });
            DisableInput($('input[name="password_type"]:checked').val());

            $('input[name="send_type"]').on('ifChecked', function(event){
                DisableBox($(this).val());
            });
            DisableBox($('input[name="send_type"]:checked').val());

                        //เลือกทั้งหมด
            $('#checkall').change(function(event) {

                if($(this).prop('checked')){//เลือกทั้งหมด
                    $('#myTable').find('input.user_trader_id').prop('checked', true);
                }else{
                    $('#myTable').find('input.user_trader_id').prop('checked', false);
                }

            });

            $('.textarea').summernote({
                placeholder: 'เขียนเนื้อหาข่าวที่นี่...',
                fontNames: ['Lato', 'Arial', 'Courier New'],
                height: 300

            });

        });

        function DisableBox(val){
            if(val == 1){
                $('.box_password').find('textarea,input').prop('disabled', false);
                $('.box_nomal').find('textarea,input').prop('disabled', true);

                $('.box_password').find('textarea,input').prop('required', true);
                $('.box_nomal').find('textarea,input').prop('required', false);

                $('.box_password').show();
                $('.box_nomal').hide();

                // DisableInput($('input[name="password_type"]:checked').val());
            }else if(val == 2){
                $('.box_password').find('textarea,input').prop('disabled', true);
                $('.box_nomal').find('textarea,input').prop('disabled', false);

                $('.box_password').find('textarea,input').prop('required', false);
                $('.box_nomal').find('textarea,input').prop('required', true);

                $('.box_password').hide();
                $('.box_nomal').show();
                // DisableInput($('input[name="password_type"]:checked').val());
            }else{
                $('.box_password').find('textarea,input').prop('disabled', true);
                $('.box_nomal').find('textarea,input').prop('disabled', true);
                // DisableInput($('input[name="password_type"]:checked').val());
            }

            $('#checkall').prop('required', false);
        }

        function DisableInput(val){
            if(val == 3){
                $('#new_password').prop('required', true);
                $('#new_password').prop('disabled', false);
            }else{
                $('#new_password').prop('required', false);
                $('#new_password').prop('disabled', true);
            }
        }
    </script>

@endpush

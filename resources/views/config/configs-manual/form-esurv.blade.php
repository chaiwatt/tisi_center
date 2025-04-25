@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endpush

@php
    $tisi_esurv = App\Models\Config\ConfigsManual::where('site', 'tisi-esurv' )->get()
@endphp

<div data-repeater-list="repeater-esurv">

    @if( count($tisi_esurv) > 0 )

        @foreach ( $tisi_esurv as $item )

        <div class="well" data-repeater-item>

                @can('edit-'.str_slug('configs-manual'))
                    <div class="form-group">
                        <button class="btn btn-danger pull-right" type="button" data-repeater-delete>
                            <i class="fa fa-close"></i> ลบ
                        </button>
                    </div>
                @endcan

                <input type="hidden" name="id" value="{!! $item->id !!}">

                <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('title', 'ชื่อคู่มือ', ['class' => 'col-md-3 control-label'], false) !!}
                    <div class="col-md-6">
                        {!! Form::text('title', !empty($item->title)?$item->title:null , ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group required">
                    {!! Form::label('upload_file', 'แนบไฟล์:', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-6">

                        @if( !empty($item->file) )
                            @php
                                $attach = json_decode($item->file);
                            @endphp
        
                            <a href="{!! HP::getFileStorage($item->file_url) !!}" target="_blank" class="show_tag_a">
                                {!! HP::FileExtension($attach->file_client_name)  ?? '' !!}
                            </a>  
                            @can('edit-'.str_slug('configs-manual'))
                                <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('config/manual/delete-files/'.$item->id) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            @endcan

                            <div class="fileinput fileinput-new input-group" data-provides="fileinput" style="display: none;">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="upload_file" id="upload_file" class="upload_file" disabled >
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @else
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="upload_file" id="upload_file" required>
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
                    {!! Form::label('details', 'รายละเอียด', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-6">
                        {!! Form::textarea('details', !empty($item->details)?$item->details:null, ['class' => 'form-control', 'rows' => 2]) !!}
                        {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

            </div>
            
        @endforeach

    @else

        <div class="well" data-repeater-item>

            @can('edit-'.str_slug('configs-manual'))
                <div class="form-group">
                    <button class="btn btn-danger pull-right" type="button" data-repeater-delete>
                        <i class="fa fa-close"></i> ลบ
                    </button>
                </div>
            @endcan

            <input type="hidden" name="id" value="">

            <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title', 'ชื่อคู่มือ', ['class' => 'col-md-3 control-label'], false) !!}
                <div class="col-md-6">
                    {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required">
                {!! Form::label('upload_file', 'แนบไฟล์:', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="upload_file" id="upload_file" required>
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                </div>
            </div>

            <div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('details', 'รายละเอียด', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::textarea('details', null, ['class' => 'form-control', 'rows' => 2]) !!}
                    {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>
        
    @endif

</div>

<div>
    <button type="button" class="btn btn-success pull-right" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        @can('add-'.str_slug('configs-manual'))
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
        @endcan

        @can('view-'.str_slug('configs-manual'))
            <a class="btn btn-default show_tag_a" href="{{url('/config/manual')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script>

        $(document).ready(function () {

        });
    </script>
@endpush

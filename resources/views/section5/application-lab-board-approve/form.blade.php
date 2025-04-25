@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }

        .input_show {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }

        .input_show[disabled]{
            background-color: #FFFFFF !important;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $applicationlab->application_no )?$applicationlab->application_no:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->application_date)?HP::DateThaiFull($applicationlab->application_date):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->accept_date) ? HP::DateThaiFull($applicationlab->accept_date) : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->accept_by) && !is_null($applicationlab->accepter) ? $applicationlab->accepter->FullName : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<br>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationlab->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @include ('section5/application-request-form.application-lab')
                </div>
            </div>
        </div>
    </div>
</div>


@php
    $approve = App\Models\Section5\ApplicationLabBoardApprove::where('app_id', $applicationlab->id )->first();
@endphp

@if( (isset($applicationlab->approve) && $applicationlab->approve == true) || (isset($applicationlab->show) && $applicationlab->show == true)  )
    <div class="row" id="box-approve">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ผลเสนอคณะอนุกรรมการ
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        <fieldset class="white-box">
                            <legend class="legend"><h5>บันทึกผลเสนอคณะอนุกรรมการ</h5></legend>

                            @php
                                $file_approve = null;
                                $file_approve_other = [];
                                if( !is_null($approve) ){
                                    $file_approve = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_application_labs_board_approve')
                                                                    ->first();

                                    $file_approve_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_approve_other')
                                                                    ->get();
                                }
                            @endphp

                            <div class="row repeater-file">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="row">

                                        <div class="form-group required{{ $errors->has('lab_name') ? 'has-error' : ''}}">
                                            {!! Form::label('lab_name', ' ชื่อห้องปฏิบัติการ'.' :', ['class' => 'control-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('lab_name', !empty( $applicationlab->lab_name )?$applicationlab->lab_name:null,['class' => 'form-control input_show', 'required' => false, 'disabled' => true ]) !!}
                                                {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('board_meeting_result') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('board_meeting_result', 'มติคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                            <div class="col-md-2">
                                                {!! Form::radio('board_meeting_result', '1', ( !is_null($approve) && $approve->board_meeting_result == 1 ? true:( is_null($approve)?true:null ) ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'board_meeting_result-1', 'required' => true]) !!}
                                                {!! Html::decode(Form::label('board_meeting_result-1', 'ผ่าน', ['class' => 'control-label text-capitalize'])) !!}
                                            </div>
                                            <div class="col-md-7">
                                                {!! Form::radio('board_meeting_result', '2', ( !is_null($approve) && $approve->board_meeting_result == 2 ? true:null ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'board_meeting_result-2', 'required' => false]) !!}
                                                {!! Form::label('board_meeting_result-2', 'ไม่ผ่าน', ['class' => 'control-label text-capitalize']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            {!! Form::label('board_meeting_date', 'วันที่ประชุมคณะอนุกรรมการ', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    {!! Form::text('board_meeting_date', ( !is_null($approve) && !empty($approve->board_meeting_date)? HP::revertDate($approve->board_meeting_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('board_meeting_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        @if( is_null($file_approve) )
                                            <div class="form-group">
                                                {!! Form::label('file_approve', 'เอกสารมติคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                                <div class="col-md-4">

                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                            <span class="input-group-text btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_approve">
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row" data-repeater-list="repeater-file-approve">
                                        @if( count($file_approve_other) > 0 )

                                            @foreach ( $file_approve_other as $other )
                                                <div class="form-group">
                                                    {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                                    <div class="col-md-4">
                                                        {!! Form::text('file_approve_documents', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                                            {!! HP::FileExtension($other->filename)  ?? '' !!}
                                                        </a>
                                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($other->id).'/'.base64_encode('section5/application-lab-board-approve/approve/'.$applicationlab->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    </div>

                                                </div>
                                            @endforeach

                                        @endif
                                        <div class="form-group" data-repeater-item>
                                            {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('file_approve_documents', null,['class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                        <span class="input-group-text btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="file_approve_other">
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete type="button">
                                                    ลบ
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm btn_file_add" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group {{ $errors->has('board_meeting_description') ? 'has-error' : ''}}">
                                            {!! Form::label('board_meeting_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('board_meeting_description', (!is_null($approve) && !empty($approve->board_meeting_description)? $approve->board_meeting_description:null  ),  ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('board_meeting_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">

                                                @can('edit-'.str_slug('application-lab-approve'))
                                                    <button class="btn btn-primary show_tag_a" type="submit">
                                                        <i class="fa fa-paper-plane"></i> บันทึก
                                                    </button>
                                                @endcan
                                                <a class="btn btn-default show_tag_a" href="{{url('/section5/application-lab-board-approve')}}">
                                                    <i class="fa fa-rotate-left"></i> ยกเลิก
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if( (isset($applicationlab->tisi_approve) && $applicationlab->tisi_approve == true) || (isset($applicationlab->show) && $applicationlab->show == true) )
    <div class="row" id="box-result">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ผลเสนอ กมอ.
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        @php
                            $approve = $applicationlab->board_approve;
                        @endphp

                        <fieldset class="white-box">
                            <legend class="legend"><h5>บันทึกผลเสนอ กมอ.</h5></legend>

                            @php
                                $file_tisi_approve = null;
                                $file_tisi_approve_other = [];
                                if( !is_null($approve) ){
                                    $file_tisi_approve = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabBoardApprove)->getTable())
                                                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_application_labs_tisi_board_approve')
                                                                    ->first();

                                    $file_tisi_approve_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabBoardApprove)->getTable())
                                                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_tisi_approve_other')
                                                                    ->get();
                                }
                            @endphp

                            <div class="row repeater-file">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="row">

                                        <div class="form-group required{{ $errors->has('lab_name') ? 'has-error' : ''}}">
                                            {!! Form::label('lab_name', ' ชื่อห้องปฏิบัติการ'.' :', ['class' => 'control-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('lab_name', !empty( $applicationlab->lab_name )?$applicationlab->lab_name:null,['class' => 'form-control input_show', 'required' => false, 'disabled' => true ]) !!}
                                                {!! $errors->first('lab_name', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('tisi_board_meeting_result') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('tisi_board_meeting_result', 'มติคณะกมอ.'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                            <div class="col-md-2">
                                                {!! Form::radio('tisi_board_meeting_result', '1', ( !is_null($approve) && $approve->tisi_board_meeting_result == 1 ? true:( empty($approve->tisi_board_meeting_result)?true:null ) ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'tisi_board_meeting_result-1', 'required' => true]) !!}
                                                {!! Html::decode(Form::label('tisi_board_meeting_result-1', 'ผ่าน', ['class' => 'control-label text-capitalize'])) !!}
                                            </div>
                                            <div class="col-md-7">
                                                {!! Form::radio('tisi_board_meeting_result', '2', ( !is_null($approve) && $approve->tisi_board_meeting_result == 2 ? true:null ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'tisi_board_meeting_result-2', 'required' => false]) !!}
                                                {!! Form::label('tisi_board_meeting_result-2', 'ไม่ผ่าน', ['class' => 'control-label text-capitalize']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            {!! Form::label('tisi_board_meeting_date', 'วันที่ประชุมกมอ.', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    {!! Form::text('tisi_board_meeting_date', ( !is_null($approve) && !empty($approve->tisi_board_meeting_date)? HP::revertDate($approve->tisi_board_meeting_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('tisi_board_meeting_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        @if( is_null($file_tisi_approve) )
                                            <div class="form-group">
                                                {!! Form::label('file_tisi_approve', 'เอกสารมติคณะกมอ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                                <div class="col-md-4">

                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                            <span class="input-group-text btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_tisi_approve">
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        {{-- <div class="form-group @if( is_null($file_tisi_approve) )  required @endif">
                                            {!! Form::label('file_tisi_approve', 'เอกสารมติคณะกมอ.'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">

                                                @if( is_null($file_tisi_approve) )
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                            <span class="input-group-text btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_tisi_approve" required>
                                                            </span>
                                                        </span>
                                                    </div>
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_tisi_approve->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_tisi_approve->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_tisi_approve->id).'/'.base64_encode('section5/application-lab-board-approve/gazette/'.$applicationlab->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                @endif

                                            </div>

                                        </div> --}}

                                    </div>

                                    <div class="row" data-repeater-list="repeater-file-tisi-approve">
                                        @if( count($file_tisi_approve_other) > 0 )

                                            @foreach($file_tisi_approve_other as $other )
                                                <div class="form-group">
                                                    {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                                    <div class="col-md-4">
                                                        {!! Form::text('file_approve_documents', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                                            {!! HP::FileExtension($other->filename)  ?? '' !!}
                                                        </a>
                                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($other->id).'/'.base64_encode('section5/application-lab-board-approve/tisi_approve/'.$applicationlab->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    </div>

                                                </div>
                                            @endforeach

                                        @endif
                                        <div class="form-group" data-repeater-item>
                                            {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('file_approve_documents', null,['class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                        <span class="input-group-text btn-file">
                                                            <span class="fileinput-new">เลือกไฟล์</span>
                                                            <span class="fileinput-exists">เปลี่ยน</span>
                                                            <input type="file" name="file_approve_other">
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete type="button">
                                                    ลบ
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm btn_file_add2" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group {{ $errors->has('tisi_board_meeting_description') ? 'has-error' : ''}}">
                                            {!! Form::label('tisi_board_meeting_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('tisi_board_meeting_description', (!is_null($approve) && !empty($approve->tisi_board_meeting_description)? $approve->tisi_board_meeting_description:null), ['class' => 'form-control', 'rows' => 4]) !!}
                                                {!! $errors->first('tisi_board_meeting_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">

                                                @can('edit-'.str_slug('application-lab-approve'))
                                                    <button class="btn btn-primary show_tag_a" type="submit">
                                                        <i class="fa fa-paper-plane"></i> บันทึก
                                                    </button>
                                                @endcan
                                                <a class="btn btn-default show_tag_a" href="{{url('/section5/application-lab-board-approve')}}">
                                                    <i class="fa fa-rotate-left"></i> ยกเลิก
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if( (isset($applicationlab->gazette) && $applicationlab->gazette == true) || (isset($applicationlab->show) && $applicationlab->show == true) )

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    บันทึกประกาศราชกิจจานุเบกษา # {!! $applicationlab->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        <fieldset class="white-box">
                            <legend class="legend"><h5>บันทึกประกาศราชกิจจานุเบกษา</h5></legend>

                            @php
                                $file_gazette = null;
                                if( !is_null($approve) ){
                                    $file_gazette = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationLabBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationlab->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_attach_government_gazette')
                                                                    ->first();
                                }

                                $certificate_end_date  = null;
                                if($applicationlab->audit_type == 1){
                                    $certificate_end_date = $applicationlab->application_certificate->max('certificate_end_date');
                                    $certificate_end_date = HP::revertDate($certificate_end_date, true);
                                }
                            @endphp

                            <div class="form-group required">
                                {!! Form::label('government_gazette_date', 'วันที่ประกาศราชกิจจา', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('government_gazette_date', ( !is_null($approve) && !empty($approve->government_gazette_date)? HP::revertDate($approve->government_gazette_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        {!! $errors->first('government_gazette_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required">
                                {!! Form::label('lab_start_date', 'วันที่มีผลเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('lab_start_date', ( !is_null($approve) && !empty($approve->lab_start_date)? HP::revertDate($approve->lab_start_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        {!! $errors->first('lab_start_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required">
                                {!! Form::label('lab_end_date', 'วันที่สิ้นสุดเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('lab_end_date', ( !is_null($approve) && !empty($approve->lab_end_date)? HP::revertDate($approve->lab_end_date, true):$certificate_end_date  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        {!! $errors->first('lab_end_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required">
                                {!! Form::label('file_gazette', 'เอกสารประกาศราชกิจจา'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">

                                    @if( is_null($file_gazette) )
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                <span class="input-group-text btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_gazette" required>
                                                </span>
                                            </span>
                                        </div>
                                    @else
                                        <a href="{!! HP::getFileStorage($file_gazette->url) !!}" target="_blank">
                                            {!! HP::FileExtension($file_gazette->filename)  ?? '' !!}
                                        </a>
                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_gazette->id).'/'.base64_encode('section5/application-lab-board-approve/gazette/'.$applicationlab->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                                    @endif

                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group {{ $errors->has('government_gazette_description') ? 'has-error' : ''}}">
                                    {!! Form::label('government_gazette_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('government_gazette_description', (!is_null($approve) && !empty($approve->government_gazette_description)? $approve->government_gazette_description:null  ),  ['class' => 'form-control', 'rows' => 4]) !!}
                                        {!! $errors->first('government_gazette_description', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4">

                                        @can('edit-'.str_slug('application-lab-approve'))
                                            <button class="btn btn-primary show_tag_a" type="submit">
                                                <i class="fa fa-paper-plane"></i> บันทึก
                                            </button>
                                        @endcan
                                        <a class="btn btn-default show_tag_a" href="{{url('/section5/application-lab-board-approve')}}">
                                            <i class="fa fa-rotate-left"></i> ยกเลิก
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });


            $('.repeater-file').repeater({
                show: function () {
                    $(this).slideDown();

                    $(this).find('.btn_file_add').hide();
                    $(this).find('.btn_file_add2').hide();

                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

            LoadBtnAddFileApprove();

            @if ( \Session::has('success_message'))
                Swal.fire({
                    title: 'บันทึกสำเร็จ',
                    text: "คุณต้องทำรายการต่อหรือไม่ ?",
                    icon: 'success',
                    width: 500,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'กลับหน้าแรก',
                    cancelButtonText: 'ทำรายการต่อ',
                    confirmButtonClass: 'btn btn-danger btn-sm m-r-10',
                    cancelButtonClass: 'btn btn-primary btn-sm ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '{!! url('section5/application-lab-board-approve') !!}'
                    }
                });

            @endif
        });

        function LoadBtnAddFileApprove(){

            $('.btn_file_add').each(function(index, el) {

                if( index >= 1){
                    $(el).hide();
                }

            });

            $('.btn_file_add2').each(function(index, el) {

                if( index >= 1){
                    $(el).hide();
                }

            });

        }
    </script>
@endpush

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
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
        .table td.text-ellipsis {
            max-width: 177px;
        }
        .table td.text-ellipsis a {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 90%;
        }
    </style>
@endpush

    <div class="row">
        <div class="col-md-offset-1  col-md-10 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="text-center">
                        <h3 style="color: black">คำขอรับการแต่งตั้ง</h3>
                        <h3 style="color: black">ผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>
                        <h3 style="color: black">ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511 และที่แก้ไขเพิ่มเติม</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:25px"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $applicationibcb->application_no )?$applicationibcb->application_no:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->application_date)?HP::DateThaiFull($applicationibcb->application_date):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->AcceptDateFull)?$applicationibcb->AcceptDateFull:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationibcb->accept_by)?$applicationibcb->AcceptName:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<br>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationibcb->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @php
                        $applicationIbcb = $applicationibcb;
                    @endphp
                    @include ('section5/application-request-form.application-ibcb')
                </div>
            </div>
        </div>
    </div>
</div>


@php
    $approve = App\Models\Section5\ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id )->first();
@endphp

@if( (isset( $applicationibcb->approve ) && $applicationibcb->approve == true) || (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true) || (isset( $applicationibcb->show ) && $applicationibcb->show == true) )

    <div class="row" id="box-approve">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">

                <div class="panel-heading">
                    บันทึกผลเสนอคณะอนุกรรมการ # {!! $applicationibcb->application_no !!}
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
                                    $file_approve = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationibcb->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_application_ibcb_board_approve')
                                                                    ->first();

                                    $file_approve_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationibcb->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_approve_other')
                                                                    ->get();
                                }

                                $diabled_approve = (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true)?true:false;
                                $required_approve = (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true)?false:true;

                            @endphp


                            <div class="row repeater-file">
                                <div class="col-md-10 col-md-offset-1">

                                    <div class="row">

                                        <div class="form-group required{{ $errors->has('applicant_name') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_name', ' ชื่อหน่วยตรวจสอบ'.' :', ['class' => 'control-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('applicant_name', !empty( $applicationibcb->applicant_name )?$applicationibcb->applicant_name:null,['class' => 'form-control input_show', 'required' => false, 'disabled' => true ]) !!}
                                                {!! $errors->first('applicant_name', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('board_meeting_result') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('board_meeting_result', 'มติคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                            <div class="col-md-2">
                                                {!! Form::radio('board_meeting_result', '1', ( !is_null($approve) && $approve->board_meeting_result == 1 ? true:( is_null($approve)?true:null ) ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'board_meeting_result-1', 'required' => false, 'disabled' => $diabled_approve]) !!}
                                                {!! Html::decode(Form::label('board_meeting_result-1', 'ผ่าน', ['class' => 'control-label text-capitalize'])) !!}
                                            </div>
                                            <div class="col-md-7">
                                                {!! Form::radio('board_meeting_result', '2', ( !is_null($approve) && $approve->board_meeting_result == 2 ? true:null ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'board_meeting_result-2', 'required' => false, 'disabled' =>  $diabled_approve]) !!}
                                                {!! Form::label('board_meeting_result-2', 'ไม่ผ่าน', ['class' => 'control-label text-capitalize']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            {!! Form::label('board_meeting_date', 'วันที่ประชุมคณะอนุกรรมการ', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    {!! Form::text('board_meeting_date', ( !is_null($approve) && !empty($approve->board_meeting_date)? HP::revertDate($approve->board_meeting_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => $required_approve, 'disabled' =>  $diabled_approve]) !!}
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
                                                                <input type="file" name="file_approve" {!! $diabled_approve == true ? 'disabled':'' !!}>
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
                                                        {!! Form::text('file_approve_documents', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => $diabled_approve]) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                                            {!! HP::FileExtension($other->filename)  ?? '' !!}
                                                        </a>
                                                    </div>

                                                </div>
                                            @endforeach

                                        @endif
                                        <div class="form-group" data-repeater-item>
                                            {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('file_approve_documents', null,['class' => 'form-control', 'disabled' => $diabled_approve]) !!}
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
                                                            <input type="file" name="file_approve_other" {!! $diabled_approve == true ? 'disabled':'' !!}>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group {{ $errors->has('board_meeting_description') ? 'has-error' : ''}}">
                                            {!! Form::label('board_meeting_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('board_meeting_description', (!is_null($approve) && !empty($approve->board_meeting_description)? $approve->board_meeting_description:null  ),  ['class' => 'form-control', 'rows' => 4, 'disabled' => $diabled_approve]) !!}
                                                {!! $errors->first('board_meeting_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">

                                                @can('edit-'.str_slug('application-ibcb-approve'))
                                                    <button class="btn btn-primary show_tag_a" type="submit">
                                                        <i class="fa fa-paper-plane"></i> บันทึก
                                                    </button>
                                                @endcan
                                                <a class="btn btn-default show_tag_a" href="{{url('/section5/application-ibcb-board-approve')}}">
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

@if( (isset( $applicationibcb->tisi_approve ) && $applicationibcb->tisi_approve == true) || (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true) || (isset( $applicationibcb->show ) && $applicationibcb->show == true) )

    <div class="row" id="box-approve-tisi">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">

                <div class="panel-heading">
                    ผลเสนอ กมอ. # {!! $applicationibcb->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        @php
                            $file_tisi_approve = null;
                            $file_tisi_approve_other = [];
                            if( !is_null($approve) ){
                                $file_tisi_approve = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbBoardApprove)->getTable())
                                                                ->where('tax_number', $applicationibcb->applicant_taxid)
                                                                ->where('ref_id', $approve->id )
                                                                ->where('section', 'file_application_ibcbs_tisi_board_approve')
                                                                ->first();

                                $file_tisi_approve_other = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbBoardApprove)->getTable())
                                                                ->where('tax_number', $applicationibcb->applicant_taxid)
                                                                ->where('ref_id', $approve->id )
                                                                ->where('section', 'file_tisi_approve_other')
                                                                ->get();
                            }

                            $diabled_approve_tisi  = (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true)?true:false;
                            $required_approve_tisi = (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true)?false:true;
                        @endphp

                        <fieldset class="white-box">
                            <legend class="legend"><h5>บันทึกผลเสนอ กมอ.</h5></legend>

                            <div class="row repeater-file">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="row">

                                        <div class="form-group required">
                                            {!! Form::label('tisi_applicant_name', 'ชื่อหน่วยตรวจสอบ :', ['class' => 'control-label col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('tisi_applicant_name', !empty($applicationibcb->applicant_name) ? $applicationibcb->applicant_name : null, ['class' => 'form-control input_show', 'required' => false, 'disabled' => true ]) !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('tisi_board_meeting_result') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('tisi_board_meeting_result', 'มติคณะ กมอ. :&nbsp;&nbsp;', ['class' => 'col-md-3 control-label'])) !!}
                                            <div class="col-md-2">
                                                {!! Form::radio('tisi_board_meeting_result', '1', ( !is_null($approve) && $approve->tisi_board_meeting_result == 1 ? true:( empty( $approve->tisi_board_meeting_result )?true:null ) ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'tisi_board_meeting_result-1', 'required' => true, 'disabled' => $diabled_approve_tisi]) !!}
                                                {!! Html::decode(Form::label('tisi_board_meeting_result-1', 'ผ่าน', ['class' => 'control-label text-capitalize'])) !!}
                                            </div>
                                            <div class="col-md-7">
                                                {!! Form::radio('tisi_board_meeting_result', '2', ( !is_null($approve) && $approve->tisi_board_meeting_result == 2 ? true:null ), ['class' => 'form-control check ', 'data-radio' => 'iradio_flat-blue', 'id'=>'tisi_board_meeting_result-2', 'required' => false, 'disabled' => $diabled_approve_tisi]) !!}
                                                {!! Form::label('tisi_board_meeting_result-2', 'ไม่ผ่าน', ['class' => 'control-label text-capitalize']) !!}
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            {!! Form::label('tisi_board_meeting_date', 'วันที่ประชุม กมอ. :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    {!! Form::text('tisi_board_meeting_date', ( !is_null($approve) && !empty($approve->tisi_board_meeting_date)? HP::revertDate($approve->tisi_board_meeting_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true, 'disabled' => $diabled_approve_tisi]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('tisi_board_meeting_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('file_tisi_approve', 'เอกสารมติคณะ กมอ. :', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">

                                                @if(is_null($file_tisi_approve))
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                            <span class="input-group-text btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_tisi_approve" {!! $diabled_approve_tisi == true ? 'disabled':'' !!}>
                                                            </span>
                                                        </span>
                                                    </div>
                                                @else
                                                    <a href="{!! HP::getFileStorage($file_tisi_approve->url) !!}" target="_blank">
                                                        {!! HP::FileExtension($file_tisi_approve->filename)  ?? '' !!}
                                                    </a>
                                                    @if($diabled_approve_tisi == false)
                                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_tisi_approve->id).'/'.base64_encode('section5/application-ibcb-board-approve/tisi_approve/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" data-repeater-list="repeater-file-tisi-approve">
                                        @if( count($file_tisi_approve_other) > 0 )

                                            @foreach($file_tisi_approve_other as $other )
                                                <div class="form-group">
                                                    {!! Form::label('file_approve_other', 'เอกสารอื่นๆ :&nbsp;&nbsp;', ['class' => 'col-md-3 control-label']) !!}
                                                    <div class="col-md-4">
                                                        {!! Form::text('file_approve_documents', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                                            {!! HP::FileExtension($other->filename)  ?? '' !!}
                                                        </a>
                                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($other->id).'/'.base64_encode('section5/application-ibcb-board-approve/tisi_approve/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    </div>

                                                </div>
                                            @endforeach

                                        @endif
                                        <div class="form-group" data-repeater-item>
                                            {!! Form::label('file_approve_other', 'เอกสารอื่นๆ :&nbsp;&nbsp;', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('file_approve_documents', null,['class' => 'form-control', 'disabled' => $diabled_approve_tisi]) !!}
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
                                                            <input type="file" name="file_approve_other" {!! $diabled_approve_tisi == true ? 'disabled':'' !!}>
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
                                            {!! Form::label('tisi_board_meeting_description', 'รายละเอียด/หมายเหตุ :&nbsp;&nbsp;', ['class' => 'col-md-3 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('tisi_board_meeting_description', (!is_null($approve) && !empty($approve->tisi_board_meeting_description)? $approve->tisi_board_meeting_description:null), ['class' => 'form-control', 'rows' => 4, 'disabled' => $diabled_approve_tisi]) !!}
                                                {!! $errors->first('tisi_board_meeting_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">

                                                @can('edit-'.str_slug('application-ibcb-approve'))
                                                    <button class="btn btn-primary show_tag_a" type="submit">
                                                        <i class="fa fa-paper-plane"></i> บันทึก
                                                    </button>
                                                @endcan
                                                <a class="btn btn-default show_tag_a" href="{{url('/section5/application-ibcb-board-approve')}}">
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

@if(  (isset( $applicationibcb->gazette ) && $applicationibcb->gazette == true) || (isset( $applicationibcb->show ) && $applicationibcb->show == true) )

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    บันทึกประกาศราชกิจจานุเบกษา # {!! $applicationibcb->application_no !!}
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
                                    $file_gazette = App\AttachFile::where('ref_table', (new App\Models\Section5\ApplicationIbcbBoardApprove )->getTable() )
                                                                    ->where('tax_number', $applicationibcb->applicant_taxid)
                                                                    ->where('ref_id', $approve->id )
                                                                    ->where('section', 'file_attach_government_gazette')
                                                                    ->first();
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
                                {!! Form::label('start_date', 'วันที่มีผลเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('start_date', ( !is_null($approve) && !empty($approve->start_date)? HP::revertDate($approve->start_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required">
                                {!! Form::label('end_date', 'วันที่สิ้นสุดเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('end_date', ( !is_null($approve) && !empty($approve->end_date)? HP::revertDate($approve->end_date, true):null  ),  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required">
                                {!! Form::label('file_gazette', 'เอกสารประกาศราชกิจจา'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-4">

                                    @if( is_null($file_gazette) )
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <span class="fileinput-filename" style="white-space: nowrap;"></span>
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
                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('section5/delete-files/'.($file_gazette->id).'/'.base64_encode('section5/application-ibcb-board-approve/gazette/'.$applicationibcb->id) ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

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

                                        @can('edit-'.str_slug('application-ibcb-approve'))
                                            <button class="btn btn-primary show_tag_a" type="submit">
                                                <i class="fa fa-paper-plane"></i> บันทึก
                                            </button>
                                        @endcan
                                        <a class="btn btn-default show_tag_a" href="{{url('/section5/application-ibcb-board-approve')}}">
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

@include ('section5.application-ibcb-board-approve.modal-scope-branches-tis-details')

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
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>

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
                    $(this).find('.btn_file_add, .btn_file_add2').hide();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

            //ซ่อนปุ่มลบอันแรก
            $('.repeater-file').find('.btn_file_remove').hide();


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
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '{!! url('section5/application-ibcb-board-approve') !!}'
                    }
                });

            @endif

            $(document).on('click', '.open_scope_branches_tis_details', function(){

                $("#table_scope_branches_tis_details").DataTable().clear().destroy();

                open_scope_branches_tis_details($(this));

                $('#table_scope_branches_tis_details').DataTable({
                    searching: true,
                    autoWidth: false,
                    columnDefs: [
                        { className: "text-center col-md-1", targets: 0 },
                        { className: "col-md-9", targets: 1 },
                        { className: "col-md-2", targets: 2 },
                        { width: "10%", targets: 0 }
                    ]
                });

                $('#maodal_scope_branches_tis_details').modal('show');

            });

            //เมื่อเลือกวันที่มีผลเป็นหน่วยตรวจสอบ บวก 3 ปีเป็นวันที่สิ้นสุด
           /* $('#start_date').change(function(event) {

                var dates = $(this).val();
                    dates = dates.split('/');

                if(dates.length==3){
                    var s = new Date(dates[2]-543, parseInt(dates[1])-1, dates[0]);
                    var d = String(parseInt(s.getDate())).padStart(2, '0');
                    var m = String(parseInt(s.getMonth())+1).padStart(2, '0');
                    var y = parseInt(s.getFullYear());
                    var full_date = d+'/'+m+'/'+(y+3+543);
                    $('#end_date').val(full_date);
                }

            }); */

            $('#start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#end_date').val(expire_date);
                }else{
                    $('#end_date').val('');
                }
            });

        });

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = str_pad(date_start.getDate());

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

        function LoadBtnAddFileApprove(){

            $('.btn_file_add').each(function(index, el) {

                if( index >= 1){
                    $(el).hide();
                }

            });

        }

        function open_scope_branches_tis_details(link_click) {
            let scope_branches_tis = link_click.closest('td').find('input.tis_details').val();
            $('#scope_branches_tis_details').html('');
            if(!!scope_branches_tis){
                scope_branches_tis = JSON.parse(atob(scope_branches_tis));
                let rows = '';
                $.each(scope_branches_tis, function(index, item){
                    rows += `
                        <tr>
                            <td class="text-center">${index+1}</td>
                            <td class="">${item.tis_no} : ${item.tis_name}</td>
                            <td class="">${item.branch_title}</td>
                        </tr>
                    `;
                });
                $('#scope_branches_tis_details').append(rows);
            }
        }

    </script>
@endpush

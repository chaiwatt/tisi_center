@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .input-show {
            height: 27px;
            padding: 3px 7px;
            font-size: 18px;
            line-height: 1.5;
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
        }
        .input-show[disabled]{
          background-color: #FFFFFF;
        }

      </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลมาตรฐาน (มอก.) {{ $standard->id }}</h3>
                        @can('view-'.str_slug('standard'))
                            <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                            </a>
                        @endcan
                    <div class="clearfix"></div>
                    <hr>   
                    {!! Form::model($standard, ['method' => 'PATCH', 'url' => ['/tis/standard', $standard->id], 'class' => 'form-horizontal', 'files' => true ]) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                                {!! Form::label('title', 'ชื่อมาตรฐาน (TH) :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('title', (!empty($standard->title)?$standard->title:null), ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
                                {!! Form::label('title_en', 'ชื่อมาตรฐาน (EN) :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('title_en', (!empty($standard->title_en)?$standard->title_en:null), ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group {{ $errors->has('tis_no') ? 'has-error' : ''}}">
                                {!! Form::label('tis_no', 'เลขที่ มอก. :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('tis_no', null, [ 'class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('tis_book') ? 'has-error' : ''}}">
                                {!! Form::label('tis_book', 'เล่มที่ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('tis_book', null, ['class' => 'form-control input-show']) !!}
                                    {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('refer') ? 'has-error' : ''}}">
                                {!! Form::label('refer', 'ข้อมูลการอ้างอิง :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    <div id="refer-box">
                                        @foreach ($refers as $key => $refer)
                                            <div class="row" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    {!! Form::text('refer[]', $refer, ['class' => 'form-control input-show']) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group {{ $errors->has('tis_year') ? 'has-error' : ''}}">
                                {!! Form::label('tis_year', 'ปีของมอก. :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('tis_year', null,  ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('ics') ? 'has-error' : ''}}">
                                {!! Form::label('ics', 'ICS :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('ics', null, ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                {!! Form::label('isbn', 'ISBN :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('isbn', null, ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('board_type_id') ? 'has-error' : ''}}">
                                {!! Form::label('board_type_id', 'คณะที่จัดทำ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('board_type_id', App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->where('state',1)->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทคณะกรรมการ -']) !!}
                                    {!! $errors->first('board_type_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
                                {!! Form::label('method_id', 'วิธีจัดทำ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('method_id', App\Models\Basic\Method::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกวิธีจัดทำ -']) !!}
                                    {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
                                {!! Form::label('method_id_detail', 'รายละเอียดย่อยของวิธีจัดทำ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('method_id_detail', [], null, ['class' => 'form-control', 'placeholder'=>'- เลือกรายละเอียดย่อยของวิธีจัดทำ -']) !!}
                                    {!! $errors->first('method_id_detail', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('industry_target_id') ? 'has-error' : ''}}">
                                {!! Form::label('industry_target_id', 'อุตสาหกรรมเป้าหมาย :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('industry_target_id', App\Models\Basic\IndustryTarget::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกอุตสาหกรรมเป้าหมาย -']) !!}
                                    {!! $errors->first('industry_target_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('staff_responsible') ? 'has-error' : ''}}">
                                {!! Form::label('staff_responsible', 'ชื่อเจ้าหน้าที่รับผิดชอบ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('staff_responsible', null, ['class' => 'form-control input-show']) !!}
                                    {!! $errors->first('staff_responsible', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('set_format_id') ? 'has-error' : ''}}">
                                {!! Form::label('set_format_id', 'ใหม่/ทบทวน :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('set_format_id', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกรูปแบบการกำหนด มอก. -']) !!}
                                    {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('standard_type_id') ? 'has-error' : ''}}">
                                {!! Form::label('standard_type_id', 'ประเภท มอก. :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('standard_type_id', App\Models\Basic\StandardType::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภท มอก. -']) !!}
                                    {!! $errors->first('standard_type_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
                                {!! Form::label('product_group_id', 'กลุ่มผลิตภัณฑ์/สาขา :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('product_group_id', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
                                    {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('staff_group_id') ? 'has-error' : ''}}">
                                {!! Form::label('staff_group_id', 'กลุ่มเจ้าหน้าที่ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('staff_group_id', App\Models\Basic\StaffGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มเจ้าหน้าที่ -']) !!}
                                    {!! $errors->first('staff_group_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
                                {!! Form::label('remark', 'หมายเหตุ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 2]) !!}
                                    {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('government_gazette') ? 'has-error' : ''}}">
                                {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-danger">
                                            {!! Form::checkbox('government_gazette', 'y', !empty($standard) && $standard->government_gazette=='y'?true:false , ['class' => 'form-control', 'id'=>'government_gazette']) !!}
                                            <label for="government_gazette" style="padding-left:10px"> มาตรฐานที่ประกาศราชกิจจาแล้ว</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('announce_compulsory', 'y', $standard->announce_compulsory=='y'?true:false , ['class' => 'form-control', 'id'=>'tis_force1']) !!}
                                        <label for="announce_compulsory" style="text-decoration-line: underline; padding-left:10px"> กมอ. มีมติให้เป็นมาตรฐานบังคับ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                                {!! Form::label('state', 'สถานะ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ใช้งาน</label>
                                    <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ยกเลิก</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    @isset( $set_std  )
                        @php
                            $set_attach_path = 'tis_attach/set_standard/';
                        @endphp

                        <hr>
                        <p><b>ไฟล์แนบระบบกำหนดมาตรฐาน</b></p>
                        <div class="row">
                            <div class="col-md-6">

                                @foreach ( $set_std as  $set_standard )
                    
                                    @php
                                        $set_attachs = !empty($set_standard->attach)?json_decode($set_standard->attach):[];
                                    @endphp
                                    @foreach ( $set_attachs as $key => $set_attach )

                                        <div class="form-group">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-1">
                                                @if($set_attach->file_name !='' && HP::checkFileStorage($set_attach_path.$set_attach->file_name))
                                                    <a href="{{ HP::getFileStorage($set_attach_path.$set_attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-md-6 view-filename">{{ !empty($set_attach->file_client_name)?$set_attach->file_client_name:'' }}</div>
                                        </div>

                                    @endforeach 
                                    
                                @endforeach

                            </div>
                        </div>

                    @endisset
                    
                    <hr>
                    <p><b>ไฟล์แนบ</b></p>

                    @isset( $attachs )
                        @foreach ((array)$attachs as $key => $attach)
                            @if(is_object($attach))
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-6">
                                                {!! Form::select('attach_notes['.$key.']', App\Models\Basic\SetAttach::Where('state',1)->pluck('title', 'id'), $attach->file_note??null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อรายการไฟล์แนบ -']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-1">
                                                @if( !empty($attach->file_name) && HP::checkFileStorage($attach_path.$attach->file_name))
                                                    <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-md-5 view-filename">{{ !empty($attach->file_client_name)?$attach->file_client_name:'' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-6">
                                                {!! Form::select('attach_notes['.$key.']', App\Models\Basic\SetAttach::Where('state',1)->pluck('title', 'id'), $attach['file_note']??null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อรายการไฟล์แนบ -']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-1">
                                                @if( !empty($attach['file_name']) && HP::checkFileStorage($attach_path.$attach['file_name']))
                                                    <a href="{{ HP::getFileStorage($attach_path.$attach['file_name']) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-md-5 view-filename">{{ !empty($attach['file_client_name'])?$attach['file_client_name']:'' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
 
                        @endforeach
                    @endisset

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-7">
                                    <label class="control-label"><b>ประกาศกระทรวง</b></label>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('minis_dated') ? 'has-error' : ''}}">
                                {!! Form::label('minis_dated', 'ลงวันที่ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('minis_dated', null, ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('minis_no') ? 'has-error' : ''}}">
                                {!! Form::label('minis_no', 'ฉบับที่ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('minis_no', null,  ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('issue_date') ? 'has-error' : ''}}">
                                {!! Form::label('issue_date', 'วันที่มีผลบังคับใช้ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('issue_date', null, ['class' => 'form-control input-show']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-7">
                                    <label class="control-label"><b>ราชกิจจานุเบกษา</b></label>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('gaz_date') ? 'has-error' : ''}}">
                                {!! Form::label('gaz_date', 'วันที่ประกาศ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('gaz_date', null, ['class' => 'form-control input-show']) !!}
                                    {!! $errors->first('gaz_date', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('gaz_space') ? 'has-error' : ''}}">
                                {!! Form::label('gaz_space', 'ตอนที่ :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('gaz_space', null,  ['class' => 'form-control input-show']) !!}
                                    {!! $errors->first('gaz_space', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('gaz_no') ? 'has-error' : ''}}">
                                {!! Form::label('gaz_no', 'เล่ม :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('gaz_no', null, ['class' => 'form-control input-show']) !!}
                                    {!! $errors->first('gaz_no', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tis_force">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        <label class="control-label"><b>ประกาศกฤษฎีกา/ประกาศกฎกระทรวง</b></label>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('minis_dated_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('minis_dated_compulsory', 'ลงวันที่ :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('minis_dated_compulsory', null, ['class' => 'form-control input-show']) !!}
                                        {!! $errors->first('minis_dated_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('issue_date_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('issue_date_compulsory', 'วันที่มีผลบังคับใช้ :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('issue_date_compulsory', null, ['class' => 'form-control input-show']) !!}
                                        {!! $errors->first('issue_date_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('amount_date_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('amount_date_compulsory', 'จำนวนวัน :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::number('amount_date_compulsory', null, ['class' => 'form-control input-show']) !!}
                                        {!! $errors->first('amount_date_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('isbn') ? 'has-error' : ''}}">
                                    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        <label class="control-label"><b>ราชกิจจานุเบกษา</b></label>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('gaz_date_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('gaz_date_compulsory', 'วันที่ประกาศ :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('gaz_date_compulsory', null, ['class' => 'form-control  input-show']) !!}
                                        {!! $errors->first('gaz_date_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('gaz_no_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('gaz_no_compulsory', 'เล่ม :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('gaz_no_compulsory', null,  ['class' => 'form-control  input-show']) !!}
                                        {!! $errors->first('gaz_no_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('gaz_space_compulsory') ? 'has-error' : ''}}">
                                    {!! Form::label('gaz_space_compulsory', 'ตอนที่ :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('gaz_space_compulsory', null,  ['class' => 'form-control  input-show']) !!}
                                        {!! $errors->first('gaz_space_compulsory', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box_cancel">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('cancel_date') ? 'has-error' : ''}}">
                                    {!! Form::label('cancel_date', 'วันที่ประกาศยกเลิก :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('cancel_date', null, ['class' => 'form-control  input-show']) !!}
                                        {!! $errors->first('cancel_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                    
                                <div class="form-group {{ $errors->has('cancel_reason') ? 'has-error' : ''}}">
                                    {!! Form::label('cancel_reason', 'เหตุผลที่ยกเลิก :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('cancel_reason', null, ['class' => 'form-control', 'rows' => 2 , 'required'=>false]) !!}
                                        {!! $errors->first('cancel_reason', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                    
                                <div class="form-group {{ $errors->has('cancel_minis_no') ? 'has-error' : ''}}">
                                    {!! Form::label('cancel_minis_no', 'ประกาศกระทรวงฯ ฉบับที่ :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('cancel_minis_no', null, ['class' => 'form-control input-show']) !!}
                                        {!! $errors->first('cancel_minis_no', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                @if( isset($standard) && !empty($standard->cancel_attach) )
                                    @php
                                        $cancel_attach = !empty($standard->cancel_attach)?json_decode($standard->cancel_attach):[];
                                    @endphp
                                    @foreach ( $cancel_attach as $cancel_attachs )
                                        <div class="form-group">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-1">
                                                @if($cancel_attachs->file_name !='' && HP::checkFileStorage($attach_path.$cancel_attachs->file_name))
                                                    <a href="{{ HP::getFileStorage($attach_path.$cancel_attachs->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-md-6 view-filename">{{ !empty($cancel_attachs->file_client_name)?$cancel_attachs->file_client_name:'' }}</div>
                                        </div>
                                    @endforeach 
                                @endif
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('input[name="state"]').on('ifChecked', function(event){
                state_type($(this).val())
            });
            state_type($('input[name="state"]:checked').val());

            $('input,select,textarea').attr('disabled','disabled');

        //ปฎิทินไทย
            $('.datepicker').datepicker();

            //ทั่วไป-บังคับ
            $('#tis_force1').on('click', function (event) {
                ShowHideForce();
            });

            //เมื่อเพิ่มข้อมูลอ้างอิง
            $('#add-refer').click(function(){

                $('#refer-box').children(':first').clone().appendTo('#refer-box'); //Clone Element

                //edit button
                var last_new = $('#refer-box').children(':last');
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger remove-refer');
                $(last_new).find('button').html('<i class="icon-close"></i>');

            });

            //เมื่อลบข้อมูลอ้างอิง
            $('body').on('click', '.remove-refer', function(event) {
                $(this).parent().parent().remove();
            });

            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#other_attach-box');

                $('.other_attach_item:last').find('input').val('');
                $('.other_attach_item:last').find('a.fileinput-exists').click();
                $('.other_attach_item:last').find('a.view-attach').remove();
                $('.other_attach_item:last').find('.view-filename').text('');

                ShowHideRemoveBtn();

            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().remove();
                ShowHideRemoveBtn();
            });

            ShowHideRemoveBtn();
            ShowHideForce();

            $('#method_id').change(function(){
                    var data_val = $(this).val();
                    if(data_val!=""){
                    $.ajax({
                        type: "GET",
                        url: "{{url('/tis/standard/add_method_detail')}}",
                        datatype: "html",
                        data: {
                            method_id: data_val,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            var opt;
                            opt += "<option value=''>- เลือกรายละเอียดย่อยของวิธีจัดทำ -</option>";
                            $.each(list, function (key, val) {
                                opt += "<option value='" + key + "'>" + val + "</option>";
                            });
                            $("#method_id_detail").html(opt).trigger("change");
                        }
                    });
                    }
            });

        });

        function state_type( vals ){
            if(vals == 1){
                $('.box_cancel').find('input, select, textarea').prop('disabled', true);
                $('.box_cancel').hide();
            }else{
                $('.box_cancel').find('input, select, textarea').prop('disabled', false);
                $('.box_cancel').show();
            }
        }

        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 1) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }

        }

        function ShowHideForce(){

            if($('#tis_force1').prop('checked')){//ทั่วไป

                $('.tis_force').show(500);
            //  $('#issue_date_compulsory').attr('required', true);
            }else{
            //  $('#issue_date_compulsory').attr('required', false);
                $('input[name$="_compulsory"]').val('');
                $('.tis_force').hide(500);
            }

        }

    </script>
@endpush




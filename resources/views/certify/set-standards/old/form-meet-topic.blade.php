@foreach ($meetingstandards as $key => $meetingstandard)
    <div class="panel-group accordion-id" id="accordion">
        <div class="panel panel-info">
            <div class="panel card-collaps">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-meeting-{{ $meetingstandard->id }}"> หัวข้อการประชุม {{ $meetingstandard->title }}</a>
                    </h4>
                </div>

                <div id="collapse-meeting-{{ $meetingstandard->id }}" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <div class="request-form">

                            <div class="form-group {{ $errors->has('meeting_type_id') ? 'has-error' : ''}}">
                                {!! Html::decode(Form::label('meeting_type_id', 'วาระการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-8">
                                    {!! Form::select('meeting_type_id', App\Models\Bcertify\Meetingtype::where('state',1)->pluck('title', 'id'), !empty($meetingstandard->meeting_type_id)?$meetingstandard->meeting_type_id:null, ['class' => 'form-control', 'placeholder' => '-เลือกวาระการประชุม-']); !!}
                                    {!! $errors->first('meeting_type_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Html::decode(Form::label('tis_name', 'วันที่นัดหมายการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-6">
                                    <div class="input-daterange input-group">
                                        {!! Form::text('start_date', $meetingstandard->MeetingTimeText, ['class' => 'form-control','id'=>'start_date']); !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Html::decode(Form::label('meeting_place', 'สถานที่นัดหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-8">
                                    {!! Form::text('meeting_place', $meetingstandard->meeting_place, ['class' => 'form-control']); !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Html::decode(Form::label('meeting_detail', 'รายละเอียดการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('meeting_detail', $meetingstandard->meeting_detail, ['class' => 'form-control', 'rows'=>'2']); !!}
                                </div>
                            </div>

                            @php
                                $file_meetingstandard = [];
                                if( !empty($meetingstandard) ){
                                    $file_meetingstandard = App\AttachFile::where('ref_table', (new App\Models\Certify\MeetingStandard )->getTable() )
                                                                    ->where('ref_id', $meetingstandard->id)
                                                                    ->where('section', 'file_meeting_standard')
                                                                    ->get();
                                }
                            @endphp

                            @if( count($file_meetingstandard) > 0 ) {{-- ถ้ามีไฟล์แนบ --}}

                                <div class="form-group">
                                    {!! Form::label('file_meet', 'เอกสารการประชุม', ['class' => 'col-md-3 control-label']) !!}
                                    <div class="col-md-3">
                                        <p class="form-control-static">
                                            @foreach ($file_meetingstandard as $other)
                                                <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                                    {{ !empty($other->caption) ? $other->caption : null }} {!! HP::FileExtension($other->filename)  ?? '' !!}
                                                </a>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>

                            @endif

                            <div class="form-group">
                                {!! Html::decode(Form::label('experts_id', '<span class="select-label">คณะวิชาการกำหนด :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
                                <div class="col-md-6">
                                    <ul class="m-b-0 p-l-20" style="list-style-type: decimal;">
                                        @foreach ($meetingstandard->meeting_commitees as $key => $meeting_commitee)
                                            @if(!is_null($meeting_commitee->committee))
                                                <li>{{  $meeting_commitee->committee->committee_group  }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- ผลการประชุม --}}
                            <div class="form-group">
                                {!! Html::decode(Form::label('', '<span class="select-label"><b>สรุปผลการประชุม</b></span>', ['class' => 'col-md-3 control-label'])) !!}
                            </div>

                            @if(!is_null($meetingstandard->record))

                                @php
                                    $record = $meetingstandard->record;
                                @endphp

                                <div class="form-group">
                                    {!! Html::decode(Form::label('record_date', 'วันที่ดำเนินการ : <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                    <div class="col-md-6">
                                        <div class="input-daterange input-group">
                                            {!! Form::text('record_date', $record->MeetingTimeText, ['class' => 'form-control']); !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('experts_id') ? 'has-error' : ''}}">
                                    {!! Html::decode(Form::label('experts_id', '<span class="select-label">ผู้เข้าร่วม :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('experts_id[]', App\Models\Certify\RegisterExpert::pluck('head_name', 'id'), $record->experts->pluck('experts_id'), ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder' => '-เลือกผู้เข้าร่วม-']); !!}
                                    </div>
                                </div>

                                <div class=" form-group">
                                    <div class="  {{ $errors->has('cost_sum') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('cost_sum', 'ค่าใช้จ่ายในการประชุม :', ['class' => 'col-md-3 control-label '])) !!}
                                        <div class="col-md-4">
                                             <div class="input-group" >
                                                {!! Form::text('cost_sum', number_format($record->costs->pluck('cost')->sum(), 2), ['class' => 'form-control text-right amount','id'=>'amount_bill_all']) !!}
                                                <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Html::decode(Form::label('meeting_detail', 'รายละเอียดการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('meeting_detail', $record->meeting_detail, ['class' => 'form-control', 'rows'=>'2']); !!}
                                    </div>
                                </div>

                                @php
                                    $file_records = App\AttachFile::where('ref_table', (new App\Models\Certify\MeetingStandardRecord)->getTable())
                                                                 ->where('ref_id', $record->id)
                                                                 ->where('section', 'file_meeting_standard_record')
                                                                 ->get();
                                @endphp

                                @if( count($file_records) > 0 ) {{-- ถ้ามีไฟล์แนบ --}}

                                    <div class="form-group">
                                        {!! Form::label('file_meet', 'เอกสารการประชุม', ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">
                                                @foreach ($file_records as $file_record)
                                                    <a href="{!! HP::getFileStorage($file_record->url) !!}" target="_blank">
                                                        {{ !empty($file_record->caption) ? $file_record->caption : null }} {!! HP::FileExtension($file_record->filename)  ?? '' !!}
                                                    </a>
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>

                                @endif

                            @else
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="alert alert-primary"><i class="fa fa-info-circle"></i> ยังไม่ได้บันทึกผลการประชุม </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

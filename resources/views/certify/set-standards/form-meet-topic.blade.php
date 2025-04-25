<style>
    .inline-block {
        display: inline-block;
    }
</style>
@if (count($setstandard->certify_setstandard_meeting_type_many) > 0)
 

@foreach ($setstandard->certify_setstandard_meeting_type_many as $key => $meetingstandard)
@php
    $meetingstandard_commitees =  !empty($meetingstandard->meeting_standard_to->meeting_commitees) ?   $meetingstandard->meeting_standard_to->meeting_commitees->pluck('commitee_id') : null;
@endphp
 
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                 {{  !empty($meetingstandard->meetingtype_to->title) ?  $meetingstandard->meetingtype_to->title : ''}}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse" href="#collapse-meeting-{{ $meetingstandard->id }}"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in"  id="collapse-meeting-{{ $meetingstandard->id }}" aria-expanded="true">
                <div class="panel-body">
  
 <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'วาระการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            {!! Form::text('',    !empty($meetingstandard->meetingtype_to->title) ?  $meetingstandard->meetingtype_to->title : null , ['class' => 'form-control', 'disabled' => true]) !!}
    </div>
</div>
 <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'วันที่นัดหมายการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            {!! Form::text('', 
            !empty($meetingstandard->meeting_standard_to->start_date)  && !empty($meetingstandard->meeting_standard_to->end_date)  ?   HP::DateFormatGroupTh($meetingstandard->meeting_standard_to->start_date,$meetingstandard->meeting_standard_to->end_date)   : null ,
             ['class' => 'form-control', 'disabled' => true]) !!}
    </div>
</div>
<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'สถานที่นัดหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            {!! Form::text('',    !empty($meetingstandard->meeting_standard_to->meeting_place) ?  $meetingstandard->meeting_standard_to->meeting_place : null , ['class' => 'form-control', 'disabled' => true]) !!}
    </div>
</div>
<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'รายละเอียดการประชุม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            {!! Form::textarea('',    !empty($meetingstandard->meeting_standard_to->meeting_detail) ?  $meetingstandard->meeting_standard_to->meeting_detail : null , ['class' => 'form-control', 'rows'=>'2', 'disabled' => true]) !!}
    </div>
</div>
@if (!empty($meetingstandard->meeting_standard_to->AttachFileMeetingStandardAttachTo))
<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'เอกสารการประชุม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            @php
                $attachs = $meetingstandard->meeting_standard_to->AttachFileMeetingStandardAttachTo;
            @endphp
            @if (!empty($attachs) && count($attachs) > 0)
                @foreach ($attachs as $attach)
                        <p>
                            {!! !empty($attach->caption) ? $attach->caption : '' !!}
                            <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                {!! HP::FileExtension($attach->filename)  ?? '' !!}
                            </a>
                        </p>
                @endforeach
            @endif
    </div>
</div>
@endif
<div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', 'คณะวิชาการกำหนด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('',
        App\CommitteeSpecial::pluck('committee_group', 'id'),
         !empty($meetingstandard_commitees) ? $meetingstandard_commitees : null, 
         ['class' => 'select2-multiple',
          'multiple'=>'multiple', 
          'data-placeholder' => '-เลือกคณะวิชาการกำหนด-',
           'disabled' => true]); !!}
    </div>
</div>

<h3 style="color:black;"><b>ผลการประชุม</b></h3>
@php
    $record =   !empty($meetingstandard->meeting_standard_to->record) ? $meetingstandard->meeting_standard_to->record : null ;
@endphp
@if (!is_null($record))
@php
    if(!empty($meetingstandard->setstandard_to->projectid)  &&  !empty($meetingstandard->meetingtype_to->title)){
       $setstandard_title =  $meetingstandard->setstandard_to->projectid.' ('.$meetingstandard->meetingtype_to->title.')';
       $record_cost =    App\Models\Certify\MeetingStandardRecordCost::where('meeting_record_id',$record->id)->where('expense_other',$setstandard_title)->where('setstandard_id', $meetingstandard->setstandard_id )->first();
    }
       $names =      !empty($record->meeting_record_participant_many) ?  $record->meeting_record_participant_many->pluck('id') : null ; 
@endphp
 
    @if (!is_null($record_cost))
        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'วันที่ดำเนินการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-8">
                    {!! Form::text('', 
                    !empty($record->start_date)  && !empty($record->end_date)  ?   HP::DateFormatGroupTh($record->start_date,$record->end_date)   : null ,
                    ['class' => 'form-control', 'disabled' => true]) !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'ผู้เข้าร่วม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-8">
                {!! Form::select('',
                      App\Models\Certify\CertifySetstandardMeetingRecordParticipant::pluck('name', 'id'),
                   $names, 
                 ['class' => 'select2-multiple',
                  'multiple'=>'multiple', 
                  'data-placeholder' => '-เลือกผู้เข้าร่วม-',
                   'disabled' => true]); !!}
                   
            </div>
        </div>
        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'ค่าใช้จ่ายในการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-4">
                <div class="input-group" >
                    {!! Form::text('', (!empty($record_cost->cost) ?  number_format($record_cost->cost,2) : null), ['class' => 'form-control text-right ','disabled'=>true]) !!}
                    <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
                </div>
            </div>
        </div> 
         <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'รายละเอียดการประชุม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-8">
                {!! Form::textarea('',    !empty($record->meeting_detail) ?  $record->meeting_detail : null , ['class' => 'form-control', 'rows'=>'2', 'disabled' => true]) !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'เอกสารการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-8">
                @if (!empty($record->AttachFileMeetingStandardAttachTo))
                    @php
                          $attachs = $record->AttachFileMeetingStandardAttachTo;
                    @endphp
                    @if (!empty($attachs) && count($attachs) > 0)
                        @foreach ($attachs as $attach)
                                <p>
                                    {!! !empty($attach->caption) ? $attach->caption : '' !!}
                                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                        {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                    </a>
                                </p>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('', 'สถานะ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-8 ">
                    @if ($record_cost->status == 1)
                            <span class="badge bg-success">ผ่าน</span>
                    @elseif ($record_cost->status == 2)
                             <span class="badge bg-warning">มีข้อคิดเห็น (สืบเนื่อง)</span>
                    @elseif ($record_cost->status == 3)
                            <span class="badge bg-danger">ไม่ได้พิจารณาในการประชุมครั้งนี้</span>
                    @elseif ($record_cost->status == 4)
                            <span class="badge bg-danger">อื่นๆ</span>
                    @endif
            </div>
        </div>       
    @else
    
    <div class="alert alert-warning">
        <h4 class="alert-heading"><i class="fa fa-exclamation-circle"></i>  </h4>
    </div>
        
    @endif

@else

<div class="alert alert-warning">
    <h4 class="alert-heading"><i class="fa fa-exclamation-circle"></i> อยู่ระหว่างผลการประชุม </h4>
</div>
    
@endif


                </div>
            </div>
        </div>
    </div>
</div>


 
@endforeach

@else

<div class="alert alert-warning">
    <h4 class="alert-heading inline-block">
        <i class="fa fa-exclamation-circle"></i> 
        อยู่ระหว่างวาระการประชุม 
    </h4>
    <a href="{{ url('certify/meeting-standards/create') }}" class="inline-block">
        <b> >> คลิกเพื่อนัดหมายการประชุม << </b>
    </a>
</div>
    
@endif


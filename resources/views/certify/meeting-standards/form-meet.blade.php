<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', 'หัวข้อการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('title',null, ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('meeting_type_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_type_id', 'วาระการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('meeting_type_id',App\Models\Bcertify\Meetingtype::where('state',1)->pluck('title', 'id'), !empty($meetingstandard->meeting_type_id)?$meetingstandard->meeting_type_id:null, ['class' => 'form-control', 'placeholder' => '-เลือกวาระการประชุม-', 'required' => true]); !!}
        {!! $errors->first('meeting_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    {!! Html::decode(Form::label('start_date', 'วันที่นัดหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
          <span class="input-group-addon bg-info b-0 text-white"> เริ่ม </span>
          {!! Form::text('start_date',!empty($meetingstandard->start_date)?HP::revertDate($meetingstandard->start_date,true):null , ['class' => 'form-control','id'=>'start_date', 'required' => true]); !!}
          <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
          </div>
        </div>
      </div>

    <div class="col-md-2">
        {!! Form::time('start_time', null, ['class' => 'form-control text-center','id'=>'start_time', 'required' => true]); !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label label-filter']) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('end_date', !empty($meetingstandard->end_date)?HP::revertDate($meetingstandard->end_date,true):null , ['class' => 'form-control','id'=>'end_date', 'required' => true]); !!}
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2">
       {!! Form::time('end_time', null, ['class' => 'form-control text-center','id'=>'end_time', 'required' => true]); !!}
    </div>
</div>


<div class="form-group {{ $errors->has('meeting_place') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_place', 'สถานที่นัดหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('meeting_place',null, ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('meeting_place', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('meeting_detail') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_detail', 'รายละเอียดการประชุม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
          {!! Form::textarea('meeting_detail', null, ['class' => 'form-control', 'rows'=>'2']); !!}
    </div>
</div>


<div class="form-group register_expert_item{{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach_meeting', '<span class="select-label">เอกสารการประชุม :</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-8">
        <button type="button" class="btn btn-sm btn-success attach-meet-add"  id="attach-meet-add">
            <i class="icon-plus"></i>&nbsp;เพิ่ม
        </button>
    </div>
</div>

    @php
        $file_meetingstandard = [];
        if( !empty($meetingstandard) ){
            $file_meetingstandard = App\AttachFile::where('ref_table', (new App\Models\Certify\MeetingStandard )->getTable() )
                                            ->where('ref_id', $meetingstandard->id )
                                            ->where('section', 'file_meeting_standard')
                                            ->get();
        }
    @endphp

<div id="attach-meet-box">
    @if( count($file_meetingstandard) > 0 )
        @foreach ( $file_meetingstandard as $other )

            <div class="form-group">
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('file_meet', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                </div>
                <div class="col-md-3">
                    <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">{!! HP::FileExtension($other->filename)  ?? '' !!}</a>
                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('certify/delete-files/'.($other->id).'/'.base64_encode('certify/meeting-standards/'.$meetingstandard->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>

        @endforeach
    @endif

    <div class="form-group other_attach_meet_item">
        {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            {!! Form::text('file_desc[]', null, ['class' => 'form-control', 'placeholder' => 'ชื่อไฟล์']) !!}
       </div>
       <div class="col-md-4">
            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                   <div class="form-control" data-trigger="fileinput">
                       <i class="glyphicon glyphicon-file fileinput-exists"></i>
                       <span class="fileinput-filename"></span>
                   </div>
                   <span class="input-group-addon btn btn-default btn-file">
                       <span class="fileinput-new">เลือกไฟล์</span>
                       <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="file_meet[]" class="check_max_size_file">
                    </span>
                   <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
           </div>
       </div>
       <div class="col-md-1">
            <div class="button_meet_remove"></div>
       </div>
   </div>
</div>



<div class="form-group {{ $errors->has('commitee_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('commitee_id', '<span class="select-label">คณะวิชาการกำหนด :</span>'.'<span class="text-danger select-label">*</span> <div class="font-10 text-danger"><b>(ถ้าแก้ไข)</b> กรุณาเลือกผู้เข้าร่วมหลังบันทึกวาระการประชุม</div>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-6">
        {!! Form::select('commitee_id[]', App\CommitteeSpecial::pluck('committee_group', 'id'), !empty($meetingstandard_commitees) ? $meetingstandard_commitees : null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder' => '-เลือกผู้เข้าร่วม-', 'required' => true]); !!}
        {!! $errors->first('commitee_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('setstandard_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('setstandard_id', '<span class="select-label">Project ที่เกี่ยวข้อง :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-6">
        {!! Form::select('setstandard_id[]',App\Models\Certify\SetStandards::pluck('projectid', 'id'), !empty($meetingstandard_project)?$meetingstandard_project:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder' => '-เลือก Projectที่เกี่ยวข้อง-', 'required' => true]); !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<input type="hidden" name="type" value="meet" />


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit" id="button-form-meet">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        <a class="btn btn-default" href="{{url('/certify/meeting-standards')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>

@push('js')
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>

<script>

    $(document).ready(function () {

             //เพิ่มไฟล์แนบ
             $('#attach-meet-add').click(function(event) {
                 $('.other_attach_meet_item:first').clone().appendTo('#attach-meet-box');

            var last_new = $('.other_attach_meet_item:last');
                $(last_new).find('input').val('');
                $(last_new).find('a.fileinput-exists').click();
                $(last_new).find('a.view-meet-attach').remove();
                $(last_new).find('button.attach-meet-add').remove();
                $(last_new).find('.button_meet_remove').html('<button class="btn btn-danger btn-sm attach-meet-remove" type="button"> <i class="icon-close"></i> ลบ </button>');
                 check_max_size_file();
             });

             //ลบไฟล์แนบ
             $('body').on('click', '.attach-meet-remove', function(event) {
                 $(this).parent().parent().parent().remove();
             });

             check_max_size_file();
         });



</script>
@endpush

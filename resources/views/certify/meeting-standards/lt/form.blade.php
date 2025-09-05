{{-- AppointedCommitteeLtController --}}
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.css')}}" rel="stylesheet">

<style type="text/css">
    .bootstrap-tagsinput {
        width: 100% !important;
    }
    .font-16{
        font-size:16px;
    }
    .font-14{
        font-size:10px;
    }
    .table>thead>tr>th {
        padding: 2px;

    }
    .btn-default, .btn-default.disabled {
        background: #e5ebec;
        border: 2px solid #e5ebec;
    }
    .form-file-group {
        display: flex; 
        align-items: center;
    }
    .fileinput-custom {
        margin-bottom: 0;
    }
</style>
@endpush

<div class="form-group {{ $errors->has('doc_type') ? 'has-error' : '' }}">
    <label for="doc_type" class="col-md-3 control-label">ประเภท:</label>
    <div class="col-md-8">
        <select name="doc_type" id="doc_type" class="select2 form-control" data-placeholder="- เลือกประเภท -">
            <option></option>
            <option value="1" {{ old('doc_type') == '1' ? 'selected' : '' }}>พิจารณาคำขอ</option>
            <option value="2" {{ old('doc_type') == '2' ? 'selected' : '' }}>พิจารณามาตรฐาน</option>
        </select>
        @if ($errors->has('doc_type'))
            <p class="help-block">{{ $errors->first('doc_type') }}</p>
        @endif
    </div>
</div>


<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', 'การประชุมครั้งที่'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('title', $meetingstandard->title ?? null, ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
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
        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
            {!! Form::text('start_time', !empty($meetingstandard->start_time)? date("H:i", strtotime($meetingstandard->start_time)):null , ['class' => 'form-control text-center','id'=>'start_time', 'required' => true]); !!}
             <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span>
        </div>
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
        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
            {!! Form::text('end_time',  !empty($meetingstandard->end_time)? date("H:i", strtotime($meetingstandard->end_time)):null, ['class' => 'form-control text-center','id'=>'end_time', 'required' => true]); !!}
             <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span>
        </div>
    </div>
</div>

{{-- สถานที่นัดหมาย --}}
<div class="form-group {{ $errors->has('meeting_place') ? 'has-error' : ''}}">
    <label for="meeting_place" class="col-md-3 control-label">สถานที่นัดหมาย : <span class="text-danger">*</span></label>
    <div class="col-md-8">
        <input type="text" name="meeting_place" id="meeting_place" class="form-control" value="{{ $meetingstandard->meeting_place ?? old('meeting_place') }}" required>
        {!! $errors->first('meeting_place', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{{-- รายละเอียดการประชุม --}}
<div class="form-group {{ $errors->has('meeting_detail') ? 'has-error' : ''}}" hidden>
    <label for="meeting_detail" class="col-md-3 control-label">รายละเอียดการประชุม : </label>
    <div class="col-md-8">
        <textarea name="meeting_detail" id="meeting_detail" class="form-control" rows="2" >{{ $meetingstandard->meeting_detail ?? old('meeting_detail') }}</textarea>
    </div>
</div>
 

@php
    // ดึงข้อมูลสำหรับ options มาเตรียมไว้
    $committeeOptions = App\CommitteeSpecial::pluck('committee_group', 'id');
@endphp

<div class="form-group {{ $errors->has('commitee_id') ? 'has-error' : '' }}">
    <label for="commitee_id" class="col-md-3 control-label">
        <span class="select-label">คณะประชุม :</span>
        <span class="text-danger select-label">*</span>
    </label>

    <div class="col-md-8">
        <select name="commitee_id" id="commitee_id" class="form-control select2" required data-placeholder="-เลือกผู้เข้าร่วม-">
            <option value="">-เลือกผู้เข้าร่วม-</option>
            @foreach ($committeeOptions as $id => $committee_group)
                <option value="{{ $id }}" 
                    {{ (isset($meetingstandard_commitees) && is_array($meetingstandard_commitees) && in_array($id, $meetingstandard_commitees)) ? 'selected' : '' }}>
                    {{ $committee_group }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('commitee_id'))
            <p class="help-block">{{ $errors->first('commitee_id') }}</p>
        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('draft_plan_id') ? 'has-error' : '' }}" id="draft_std_wrapper">
    <label for="draft_plan_id" class="col-md-3 control-label">
        <span class="select-label">ร่างแผนมาตรฐาน:</span>
        <span class="text-danger select-label">*</span>
    </label>

    <div class="col-md-8">
        <select name="draft_plan_id[]" id="draft_plan_id" class="select2-multiple" multiple required data-placeholder="-เลือกแผนร่างมาตรฐาน-">     
            @foreach ($draftPlans as $plan)
                <option value="{{ $plan->id }}" 
                    {{ (is_array(old('draft_plan_id')) && in_array($plan->id, old('draft_plan_id'))) ? 'selected' : '' }}>
                    {{ $plan->estandard_offers_to->standard_name . ' ('.$plan->estandard_offers_to->refno.')' ?? 'N/A' }} 
                </option>
            @endforeach
        </select>

        @if ($errors->has('draft_plan_id'))
            <p class="help-block">{{ $errors->first('draft_plan_id') }}</p>
        @endif
    </div>
</div>


<div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}" id="std_wrapper">
    {!! Html::decode(Form::label('detail', '<span class="select-label">มาตรฐาน :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-8">
        @if(count($setstandard_meeting_types) > 0 )
            @php
                if(!empty($meetingstandard) && $meetingstandard->status_id >= 4){
                    $standards =  App\Models\Certify\SetStandards::pluck('projectid', 'id');
                }
            @endphp
            @foreach($setstandard_meeting_types as $item)
                @php
                    $projectids =  App\Models\Certify\CertifySetstandardMeetingType::where('meetingtype_id',$item->meetingtype_id)->where('setstandard_meeting_id',@$meetingstandard->id)->pluck('setstandard_id');
                @endphp
                    <div hidden>
                        @php
                            $meetingTypes = App\Models\Bcertify\Meetingtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
                            $selectedId = $meetingTypes->keys()->first();
                        @endphp

                        {!! Form::select('detail[meetingtype_id][]',
                            $meetingTypes,
                            $selectedId,
                            [
                                'class' => 'form-control select2 meetingtype_id',
                                'required' => true
                            ]
                        ) !!}
                    </div>
                    <div>
                        {{-- <select name="detail[projectid][{{ $item->meetingtype_id }}][]" class="select2-multiple select2 projectid" multiple> --}}
                        <select name="detail[projectid][{{ $item->meetingtype_id }}][]" class="select2-multiple select2 projectid" id="standard_project_id" multiple required data-placeholder="-เลือกมาตรฐาน-">
                            {{-- @foreach($standards as $standard)
                                    <option value="{{ $standard->id }}" @if($projectids->contains('id', $standard->id)) selected @endif>
                                    {{ str_replace('Req', 'CSD', $standard->estandard_plan_to->estandard_offers_to->refno) }} {{ $standard->estandard_plan_to->tis_name }} 
                                    </option>
                                @endforeach --}}
                        </select>

                        @if($errors->has('projectid'))
                            <p class="help-block">{{ $errors->first('projectid') }}</p>
                        @endif
                    </div>
            @endforeach  
        @endif
    </div>
</div>



 @if (!empty($meetingstandard))
   @if (!empty($meetingstandard->meeting_group))
        <div class="form-group {{ $errors->has('draft_plan_id') ? 'has-error' : '' }}">
            <label for="draft_plan_id" class="col-md-3 control-label">
                <span class="select-label">มาตรฐาน :</span>
                <span class="text-danger select-label">*</span>
            </label>

            <div class="col-md-8">
            

                {{-- แปลงข้อมูล JSON string ให้กลายเป็น Array/Object ของ PHP --}}
                @php
                    $meetingGroupItems = json_decode($meetingstandard->meeting_group);
                @endphp

                {{-- 2. ตรวจสอบอีกครั้งว่าหลังจากแปลงแล้วมีข้อมูลจริงๆ (ไม่ใช่ array ว่าง) --}}
                @if (!empty($meetingGroupItems))
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ลำดับ</th>
                                <th>รหัสร่างแผน (ID)</th>
                                <th>สถานะ (Status)</th>
                                <th>หมายเหตุ (Note)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- 3. วนลูป (Loop) ข้อมูลเพื่อสร้างแถวในตาราง --}}
                            @foreach ($meetingGroupItems as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->status ?? 'ยังไม่มีสถานะ' }}</td>
                                    <td>{{ $item->note ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @endif
                
            </div>
        </div>
    @endif
  @endif


<div class="form-group {{ $errors->has('commitee_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label '])) !!}
        <div class="col-md-9">
                <span id="committee_lists"></span>
        </div>
 </div>

<div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารประกอบการประชุม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9" >
           @if (!empty($meetingstandard->AttachFileMeetingStandardAttachTo))
                @php
                
                    $attachs = $meetingstandard->AttachFileMeetingStandardAttachTo;
                @endphp
                @if (!empty($attachs) && count($attachs) > 0)
                    @foreach ($attachs as $key=>$attach)
                        @php
                            $caption = !empty($attach->caption) ? $attach->caption : '';
                            $filename = !empty($attach->filename) ? $attach->filename : '';
                            $old_id = !empty($attach->id) ? $attach->id : '';
                        @endphp
                        
                        <p id="show-file-{{ $key }}">
                            <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                {!! $caption !!}
                                {!! HP::FileExtension($filename)  ?? '' !!}
                            </a>
                            <button type="button" class="btn btn-xs btn-warning switch-edit edit-file" data-edit-to="#edit-file-{{ $key }}" data-show-to="#show-file-{{ $key }}">
                                แก้ไข
                            </button>
                            <a class="btn btn-danger btn-xs" href="{!! url('funtions/delete-file', [base64_encode($old_id)]) !!}">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </p>

                        <div class="repeater-form-file-old" style="display: none;" id="edit-file-{{ $key }}">
                            <div class="row" data-repeater-list="repeater-attach-old">
                                <div class="form-group form-file-group repeater_form_file4" data-repeater-item>
                                    <div class="col-md-11">
                                        <div class="col-md-5">
                                            {!! Form::hidden("repeater-attach-old[{$key}][old_id]", $old_id) !!}
                                            {!! Form::text("repeater-attach-old[{$key}][file_desc]", $caption, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="col-md-7">
                                            <div class="fileinput fileinput-custom input-group fileinput-exists" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <span class="fileinput-filename">{{ $filename }}</span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    {{-- <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span> --}}
                                                    <span class="input-group-text btn-file">
                                                        <span class="fileinput-new">เลือกไฟล์</span>
                                                        <span class="fileinput-exists">เปลี่ยน</span>
                                                        <input type="file" name="repeater-attach-old[{{ $key }}][file_meet]" class="check_max_size_file">
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-xs btn-warning switch-edit show-file" data-show-to="#show-file-{{ $key }}" data-edit-to="#edit-file-{{ $key }}"><i class="icon-plus"></i>ยกเลิก</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    @endforeach
                @endif
            @endif
            <div class="repeater-form-file">
                <div class="row" data-repeater-list="repeater-attach">
                    <div class="form-group form-file-group repeater_form_file4" data-repeater-item>
                        <div class="col-md-11">
                            <div class="col-md-5">
                                {!! Form::text('file_desc', null,['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-7">
                                <div class="fileinput fileinput-custom fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                        <span class="input-group-text btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="file_meet" class="check_max_size_file">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success btn-sm btn_file_add meetingstandard_remove" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                            <button class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete type="button">
                                ลบ
                            </button>              
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>


<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ผู้บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
         {!!     !empty($meetingstandard->user_created->FullName) ? $meetingstandard->user_created->FullName :    auth()->user()->FullName  !!}   
    </div>
</div>
<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'วันที่บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 ">
          {!!    !empty($meetingstandard->updated_at) ? HP::DateTimeThai($meetingstandard->updated_at) :  HP::DateTimeThai(date('Y-m-d H:i:s'))   !!}   
    </div>
</div>



@if ( !empty($meetingstandard->status_id) && $meetingstandard->status_id ==  2)
    <a class="btn btn-default btn-block" href="{{ url('/certify/meeting-standards') }}">
        <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>  
@else  
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
@endif


@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('plugins/components/moment/moment.js')}}"></script>
  <!-- Clock Plugin JavaScript -->
  <script src="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>

  <script>
    
    $(document).ready(function () {

        $('#draft_std_wrapper').hide();
        $('#std_wrapper').hide();

        @if(!empty($meetingstandard->status_id) && $meetingstandard->status_id ==  2)
               $('#form-meetingstandard').find('input, select, textarea').attr('disabled', true);
               $('#form-meetingstandard').find('.meetingstandard_remove').remove();
          @endif

        $('.clockpicker').clockpicker({
            donetext: 'Done',
        }).find('input').change(function() {
            console.log(this.value);
        });
        $('.date-range').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
        });


                     //เหตุผลและความจำเป็น
             $(document).on('change', '#commitee_id', function(){
                $('#committee_lists').html('');
                if(checkNone($(this).val())){
                            $.ajax({
                                type: 'get',
                                url: "{!! url('certify/meeting-standards/get_committee_lists') !!}" ,
                                data:{id:  $(this).val()}
                            }).done(function( object ) { 
                                if(object.message == true){
                                    $.each(object.datas, function (key,val) {
                                        $('#committee_lists').append('<p>'+(key+1)+'. '+val.name+' ('+val.committee_group+')</p>');
                                    });
                                } 
                            }); 
                 } 
            });     
            $('#commitee_id').change();

        

        $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 500);
                    }
                }
            });
            BtnDeleteFile();
            check_max_size_file();
            ResetTableNumber();

            
            //เพิ่มแถว
            $('#addCostInput').click(function(event) {
                var data_list = $('.meetingtype_id').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data_list == 0){
                        Swal.fire('หมดรายการวาระการประชุม !!')
                        return false;
                }
              //Clone
                $('#table_body').children('tr:first()').clone().appendTo('#table_body');
                //Clear value
                    var row = $('#table_body').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"]').val('');
                    row.find('.projectid').prop('name','detail[projectid][][]');
                ResetTableNumber();
                data_list_disabled();
            });

           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              data_list_disabled();
            });

            $('body').on('change', '.meetingtype_id', function(){
                    if(  $(this).val() != ''){
                        var id = $(this).val();
                        $(this).parent().parent().find('.projectid').prop('name','detail[projectid]['+id+'][]');
                        data_list_disabled();
                    }
            });
            $('.meetingtype_id').change();
            

            $('body').on('click', '.switch-edit', function(){
                if($(this).hasClass('edit-file')){
                    $($(this).data('show-to')).hide();
                    $($(this).data('edit-to')).show();
                    $($(this).data('edit-to')).find('input').attr('disabled', false);
                }else{
                    $($(this).data('edit-to')).hide();
                    $($(this).data('show-to')).show();
                    $($(this).data('edit-to')).find('input').attr('disabled', true);
                }
            });


    });
    function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            } 
              $('.btn_file_remove:first').hide();   
              $('.btn_file_add:first').show();   
              check_max_size_file();
     }
    function ResetTableNumber(){
            var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
        }    
          
        function data_list_disabled(){
                $('.meetingtype_id').children('option').prop('disabled',false);
                $('.meetingtype_id').each(function(index , item){
                    var data_list = $(item).val();
                    $('.meetingtype_id').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
        }
 

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
             }


// $(document).on('change', '#doc_type', function() {
//     var doc_type_id = $(this).val();
//     // **เปลี่ยนเป้าหมายมาเป็น select ที่ถูกต้อง**
//     var setStandardSelect = $('#draft_plan_id');

//     if (!doc_type_id) {
//         setStandardSelect.empty().trigger('change');
//         $('#draft_std_wrapper').hide(); // ซ่อน wrapper
//         $('#std_wrapper').hide();       // ซ่อน wrapper
//         return;
//     }
//     setStandardSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true).trigger('change');

//     if(doc_type_id == 1){
//         $('#draft_std_wrapper').show(); // แสดง wrapper ของประเภท 1
//         $('#std_wrapper').hide();       // ซ่อน wrapper ของประเภท 2
//         $.ajax({
//             url: "{{ route('certify.meeting-standards.lt.plan-list') }}", // ตรวจสอบว่า route ถูกต้อง
//             type: 'GET',
//             data: { doc_type_id: doc_type_id },
//             success: function(response) {
//                 setStandardSelect.empty().prop('disabled', false);
//                 if (response && response.length > 0) {
//                     $.each(response, function(index, plan) {
//                         let planText = 'N/A';
//                         if (plan.estandard_offers_to) {
//                             planText = `${plan.estandard_offers_to.standard_name} (${plan.estandard_offers_to.refno})`;
//                         }
//                         var newOption = new Option(planText, plan.id, false, false);
//                         setStandardSelect.append(newOption);
//                     });
//                 }
//                 setStandardSelect.trigger('change');
//             },
//             error: function(xhr, status, error) {
//                 console.error("AJAX Error: " + status + error);
//                 setStandardSelect.empty().prop('disabled', false);
//                 setStandardSelect.trigger('change');
//             }
//         });
//     }
//     else
//     {
//         $('#draft_std_wrapper').hide(); // ซ่อน wrapper ของประเภท 1
//         $('#std_wrapper').show();       // แสดง wrapper ของประเภท 2

//         // ใช้ class 'projectid' ที่อยู่ใน '#std_wrapper' เป็นเป้าหมาย
//         var standardSelect = $('#std_wrapper').find('.projectid'); // <== เป้าหมายที่ 2 (ที่ถูกต้อง)
//         standardSelect.empty().prop('disabled', true).trigger('change');

//         $.ajax({
//             url: "{{ route('certify.meeting-standards.lt.std-list') }}", // ตรวจสอบว่า route ถูกต้อง
//             type: 'GET',
//             data: { doc_type_id: doc_type_id },
//             success: function(response) {
//                 setStandardSelect.empty().prop('disabled', false);

//                 if (response && response.length > 0) {
//                     // วนลูปข้อมูล standards ที่ได้จาก server
//                     $.each(response, function(index, standard) {
//                         let stdText = 'ข้อมูลไม่ครบถ้วน';
//                         // ตรวจสอบว่ามีข้อมูลที่ต้องการครบหรือไม่
//                         if (standard.estandard_plan_to && standard.estandard_plan_to.estandard_offers_to) {
//                             let standardName = standard.estandard_plan_to.estandard_offers_to.standard_name;
//                             let refno = standard.estandard_plan_to.estandard_offers_to.refno;
//                             stdText = `${standardName} (${refno})`;
//                         }
//                         // สร้าง Option ใหม่ โดย value คือ id ของ SetStandards
//                         var newOption = new Option(stdText, standard.id, false, false);
//                         setStandardSelect.append(newOption);
//                     });
//                 }
                
//                 setStandardSelect.trigger('change');
//             },
//             error: function(xhr, status, error) {
//                  console.error("AJAX Error: " + status + error);
//                 setStandardSelect.empty().prop('disabled', false).trigger('change');
//             }
//         });
//     }


// });

$(document).on('change', '#doc_type', function() {
    var doc_type_id = $(this).val();
    
    // กำหนดตัวแปรของ select ทั้งสองไว้ก่อนเพื่อง่ายต่อการเรียกใช้
    var draftPlanSelect = $('#draft_plan_id');
    var standardSelect = $('#standard_project_id');

    // ซ่อน wrapper และยกเลิก require ทั้งหมดก่อนหากไม่มีการเลือก
    if (!doc_type_id) {
        $('#draft_std_wrapper').hide();
        $('#std_wrapper').hide();
        draftPlanSelect.prop('required', false); // เพิ่ม/ลบ: ยกเลิก require
        standardSelect.prop('required', false);  // เพิ่ม/ลบ: ยกเลิก require
        return;
    }

    // --- กรณี 1: พิจารณาคำขอ ---
    if (doc_type_id == 1) {
        $('#draft_std_wrapper').show();
        $('#std_wrapper').hide();
        
        draftPlanSelect.prop('required', true);   // เพิ่ม/ลบ: กำหนดให้ require
        standardSelect.prop('required', false);   // เพิ่ม/ลบ: ยกเลิก require
        
        draftPlanSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true).trigger('change');

        $.ajax({
            url: "{{ route('certify.meeting-standards.lt.plan-list') }}",
            type: 'GET',
            success: function(response) {
                draftPlanSelect.empty().prop('disabled', false);
                if (response && response.length > 0) {
                    $.each(response, function(index, plan) {
                        let planText = 'N/A';
                        if (plan.estandard_offers_to) {
                            planText = `${plan.estandard_offers_to.standard_name} (${plan.estandard_offers_to.refno})`;
                        }
                        var newOption = new Option(planText, plan.id, false, false);
                        draftPlanSelect.append(newOption);
                    });
                }
                draftPlanSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error (plan-list): " + status + error);
                draftPlanSelect.empty().prop('disabled', false).trigger('change');
            }
        });
    } 
    // --- กรณี 2: พิจารณามาตรฐาน ---
    else if (doc_type_id == 2) {
        $('#draft_std_wrapper').hide();
        $('#std_wrapper').show();
        
        draftPlanSelect.prop('required', false);  // เพิ่ม/ลบ: ยกเลิก require
        standardSelect.prop('required', true);    // เพิ่ม/ลบ: กำหนดให้ require
        
        standardSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true).trigger('change');

        $.ajax({
            url: "{{ route('certify.meeting-standards.lt.std-list') }}",
            type: 'GET',
            success: function(response) {
                standardSelect.empty().prop('disabled', false);
                if (response && response.length > 0) {
                    $.each(response, function(index, standard) {
                        let standardText = 'ข้อมูลไม่ครบถ้วน';
                        if (standard.estandard_plan_to && standard.estandard_plan_to.estandard_offers_to) {
                            let refno = standard.estandard_plan_to.estandard_offers_to.refno || '';
                            let tisName = standard.estandard_plan_to.tis_name;
                            let newRefno = refno.replace('Req', 'CSD');
                            standardText = `${newRefno} ${tisName}`;
                        }
                        var newOption = new Option(standardText, standard.id, false, false);
                        standardSelect.append(newOption);
                    });
                }
                standardSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error (std-list): " + status, error);
                standardSelect.empty().prop('disabled', false).trigger('change');
            }
        });
    }
});
</script>
@endpush

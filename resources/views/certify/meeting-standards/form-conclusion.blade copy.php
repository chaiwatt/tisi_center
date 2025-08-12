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
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 5px 3px;
        vertical-align: text-top;
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

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', 'หัวข้อการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('title',null, ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    {!! Html::decode(Form::label('start_date', 'วันที่ดำเนินการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
          <span class="input-group-addon bg-info b-0 text-white"> เริ่ม </span>
          {!! Form::text('start_date',!empty($meetingstandard_commitees->start_date)?HP::revertDate($meetingstandard_commitees->start_date,true):HP::revertDate($meetingstandard->start_date,true) , ['class' => 'form-control','id'=>'start_date', 'required' => true]); !!}
          <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
          </div>
        </div>
      </div>

    <div class="col-md-2">
        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
            {!! Form::text('start_time', (!empty($meetingstandard_commitees->start_time)?  date("H:i", strtotime($meetingstandard_commitees->start_time)) : (!empty($meetingstandard->start_time)? date("H:i", strtotime($meetingstandard->start_time)):null)) , ['class' => 'form-control text-center','id'=>'start_time', 'required' => true]); !!}
             <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span>
        </div>
    </div>
</div>


<div class="form-group">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label label-filter']) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('end_date', !empty($meetingstandard_commitees->end_date)?HP::revertDate($meetingstandard_commitees->end_date,true):HP::revertDate($meetingstandard->end_date,true) , ['class' => 'form-control','id'=>'end_date', 'required' => true]); !!}
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group clockpicker " data-placement="bottom" data-align="top" data-autoclose="true">
            {!! Form::text('end_time', (!empty($meetingstandard_commitees->end_time)?  date("H:i", strtotime($meetingstandard_commitees->end_time)): (!empty($meetingstandard->end_time)? date("H:i", strtotime($meetingstandard->end_time)):null)), ['class' => 'form-control text-center','id'=>'end_time', 'required' => true]); !!}
             <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span>
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('meeting_place') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_place', 'สถานที่นัดหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('meeting_place',!empty($meetingstandard_commitees->meeting_place)?$meetingstandard_commitees->meeting_place:$meetingstandard->meeting_place, ['class' => 'form-control ', 'required' => true]) !!}
        {!! $errors->first('meeting_place', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('meeting_detail') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_detail', 'รายละเอียดการประชุม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
          {!! Form::textarea('meeting_detail', !empty($meetingstandard_commitees->meeting_detail)?$meetingstandard_commitees->meeting_detail:$meetingstandard->meeting_detail, ['class' => 'form-control', 'rows'=>'2']); !!}
    </div>
</div>  

<div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารการประชุม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9  repeater-form-file" >
            @if (!empty($meetingstandard_record->AttachFileMeetingStandardAttachTo))
                @php
                    $attachs = $meetingstandard_record->AttachFileMeetingStandardAttachTo;
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

<div class="form-group {{ $errors->has('commitee_id') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('commitee_id', '<span class="select-label">คณะกรรมการ :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-9">
     <table class="table color-bordered-table primary-bordered-table">
            <thead>
                <tr>
                    <th class="text-center" width="2%"   rowspan="2">ลำดับ</th>
                    <th class="text-center" width="38%"  rowspan="2">ชื่อคณะกรรมการ</th>
                    <th class="text-center" width="30%"  colspan="2">สถานะการเข้ารวม</th>
                    <th class="text-center" width="30%"  rowspan="2">รายละเอียด</th>
                </tr>
                 <tr>
                    <th class="text-center" width="15%">เข้ารวม</th>
                    <th class="text-center" width="15%">ไม่เข้ารวม</th>
                </tr>
            </thead>
            <tbody>
                @if(count($meetingstandard_commitees) > 0 )
                    @foreach($meetingstandard_commitees as $key => $item)   
 
                    <tr>
                        <td  class="text-center">
                            {{ $key+1 }}
                        </td>
                        <td>
                            {!!  !empty($item->register_expert_to->head_name)  &&  !empty($item->committee->committee_group) ?  '<p>'.$item->register_expert_to->head_name.' <span class="text-danger">('.$item->committee->committee_group.')</span></p>' : null !!}
                              <input type="hidden" name="commitees[ids][{{ $item->id}}]" value="{{ $item->id  ?? null }}" >
                        </td>
                        <td  class="text-center">
                            {!! Form::radio('commitees[participate]['.$item->id.']', '1', is_null($item->participate) || ( !is_null($item) && $item->participate == 1), ['class'=> "check start_std_check", 'data-radio'=>'iradio_square-green']) !!}
                        </td>
                        <td  class="text-center">
                            {!! Form::radio('commitees[participate]['.$item->id.']', '2', ( !is_null($item) && $item->participate == 2), ['class'=> "check start_std_check", 'data-radio'=>'iradio_square-green']) !!}
                        </td>
                        <td>
                            {!! Form::textarea('commitees[detail]['.$item->id.']',  !empty($item->detail) ?  $item->detail : null , ['class' => 'form-control', 'rows'=>'1']); !!}
                        </td>
                    </tr>
                    @endforeach  
                @endif
            </tbody>
   
        </table>
    </div>
</div>

<div class="form-group {{ $errors->has('commitee_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('commitee_id', '<span class="select-label">ผู้เข้าร่วมประชุม :', ['class' => 'col-md-3 control-label '])) !!}
        <div class="col-md-9">
         <table class="table color-bordered-table primary-bordered-table">
                <thead>
                    <tr>
                        <th class="text-center" width="2%">ลำดับ</th>
                        <th class="text-center" width="34%">ชื่อผู้เข้าร่วม</th>
                        <th class="text-center" width="34%">หน่วยงาน</th>
                        <th class="text-center meetingstandard_remove" width="5%">
                            <button type="button" class="btn btn-success btn-sm "  id="add_participants"><i class="icon-plus"></i>เพิ่ม</button>  
                        </th>
                    </tr>
                </thead>
                <tbody id="table_body">
                    @if(count($record_participants) > 0 )
                        @foreach($record_participants as $key => $item)   
                        <tr>
                            <td  class="text-center">
                                {{ $key+1 }}
                            </td>
                            <td>
                                  {!! Form::text('participants[name][]',$item->name ?? null, ['class' => 'form-control']); !!}
                            </td>
                            <td >
                                {!! Form::select('participants[department_id][]',
                                App\Models\Basic\Department::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                   $item->department_id ?? null, 
                                ['class' => 'form-control select2 department_id', 
                                'placeholder'=>'- เลือกหน่วยงาน -']); !!}
                            </td>
                            <td  class="text-center meetingstandard_remove">
                                <button type="button" class="btn btn-danger btn-xs remove-participants "><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach  
                    @endif
                </tbody>
       
            </table>
        </div>
    </div>

<div class=" {{ $errors->has('detail') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('detail', '<span class="select-label">วาระการประชุม :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-9">
        <table class="table color-bordered-table primary-bordered-table">
            <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    <th class="text-center" width="48%">ProjectID / ค่าใช้จ่าย</th>
                    <th class="text-center" width="20%">สถานะการประชุม</th>
                    <th class="text-center" width="20%">คำใช้จ่าย</th>
                    {{-- <th class="text-center" width="10%">
                          <button type="button" class="btn btn-success btn-sm "  id="addCost"><i class="icon-plus"></i>เพิ่ม</button>  
                    </th> --}}
                </tr>
            </thead>
            <tbody id="table_body_cost">
                @if(count($setstandard_meeting_types) > 0 )
                    @foreach($setstandard_meeting_types as  $item)
                    @php
                        if(!empty($item->setstandard_to->projectid)  &&  !empty($item->meetingtype_to->title)){
                           $setstandard_title =  $item->setstandard_to->projectid.' ('.$item->meetingtype_to->title.')';
                           $record_cost =    App\Models\Certify\MeetingStandardRecordCost::where('meeting_record_id',$meetingstandard_record->id)->where('expense_other',$setstandard_title)->where('setstandard_id', $item->setstandard_id )->first();
                        }
                    
                   @endphp
                    <tr>
                        <td  class="text-center">
                            1
                        </td>
                        <td>
                                {!!  !empty($item->setstandard_to->projectid)  &&  !empty($item->meetingtype_to->title) ?  '<p>'.$item->setstandard_to->projectid.' <span class="text-danger">('.$item->meetingtype_to->title.')</span></p>' : null !!}
                                <input type="hidden" name="meeting[setstandard_title][]"  class="setstandard_title"
                                 value="{!! $setstandard_title ?? null !!}" 
                                 >
                                 <input type="hidden" name="meeting[setstandard_id][]"  class="setstandard_id"  value="{!!  $item->setstandard_id ?? null !!}"      >
                                <input type="hidden" name="meeting[cost_id][]"  class="cost_id"  >
                        </td> 
                        <td>
                       
                            {!! Form::select('meeting[status][]',
                                 ['1'=>'ผ่าน','2'=>'มีข้อคิดเห็น (สืบเนื่อง)','3'=>'ไม่ได้พิจารณาในการประชุมครั้งนี้','4'=>'อื่น ๆ'],
                                 $record_cost->status ??  null, 
                                ['class' => 'form-control select2 status', 
                                'required' => true,
                                'placeholder'=>'- เลือกสถานะการประชุม -']); !!}
                        </td>
                        <td>
                              {!! Form::text('meeting[cost][]',  !empty($record_cost->cost) ?   number_format($record_cost->cost,2)  : null , ['class' => 'form-control cost amount  meeting_type_cost text-right ', 'readonly'=>true]) !!}
                        </td>
                        {{-- <td>

                        </td> --}}
                    </tr>
                    @endforeach  
                @endif
                @if(count($meeting_types) > 0 )
                      @foreach($meeting_types as  $item)
                            <tr>
                                <td  class="text-center">
                                    1
                                </td>
                                <td>
                                    {!! Form::text('meeting[expense_other][]',  !empty($item->expense_other) ?   $item->expense_other  : null, ['class' => 'form-control expense_other', 'placeholder'=>'ค่าใช้จ่ายอื่นๆ (ถ้ามี)']) !!}
                                    <input type="hidden" name="meeting[cost_id][]"  class="cost_id" >
                                </td>
                                <td>
                                
                                </td>
                                <td>
                                    {!! Form::text('meeting[cost][]',  !empty($item->cost) ?   number_format($item->cost,2)  : null, ['class' => 'form-control cost  amount text-right ']) !!}
                                </td>
                                {{-- <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-xs remove-cost"><i class="fa fa-trash"></i></button>
                                </td> --}}
                            </tr>
                       @endforeach  
                @endif
            </tbody>
   
        </table>
    </div>
</div>


<div class="form-group required{{ $errors->has('amount') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('amount', 'เบี้ยการประชุม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class=" input-group ">
        {!! Form::text('amount', !empty($meetingstandard_record->amount)?  number_format($meetingstandard_record->amount,2): null, ['class' => 'form-control amount text-right', 'placeholder'=>'กรุณากรอกเบี้ยประชุมรวม', 'id'=>'amount','required'=> true]); !!}
            <span class="input-group-addon bg-secondary b-0  "> บาท </span>
        </div>
    </div> 
</div>

<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ผู้บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
         {!!     !empty($meetingstandard_record->user_created->FullName) ? $meetingstandard_record->user_created->FullName :    auth()->user()->FullName  !!}   
    </div>
</div>
<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'วันที่บันทึก'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 ">
          {!!    !empty($meetingstandard_record->updated_at) ? HP::DateTimeThai($meetingstandard_record->updated_at) :  HP::DateTimeThai(date('Y-m-d H:i:s'))   !!}   
    </div>
</div>

@if ( !empty($meetingstandard_record->status_id) && $meetingstandard_record->status_id ==  2)
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
    <!-- Clock Plugin JavaScript -->
    <script src="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>

  <script>
    
    $(document).ready(function () {
        @if(!empty($meetingstandard_record->status_id) && $meetingstandard_record->status_id ==  2)
               $('#form-meetingstandard').find('input, select, textarea').attr('disabled', true);
               $('#form-meetingstandard').find('.meetingstandard_remove').remove();
          @endif
            

        $('.date-range').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done',
        }).find('input').change(function() {
            console.log(this.value);
        });
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
            $('#add_participants').click(function(event) {
              //Clone
                $('#table_body').children('tr:first()').clone().appendTo('#table_body');
                //Clear value
                    var row = $('#table_body').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"]').val('');
                ResetTableNumber();
 
            });

           //ลบแถว
           $('body').on('click', '.remove-participants', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
            });


            //เพิ่มแถว
            $('#addCost').click(function(event) {
              //Clone
                $('#table_body_cost').children('tr:last()').clone().appendTo('#table_body_cost');
                //Clear value
                    var row = $('#table_body_cost').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"]').val('');
                    ResetTableCostNumber();
 
            });

           //ลบแถว
           $('body').on('click', '.remove-cost', function(){
              $(this).parent().parent().remove();
              ResetTableCostNumber();
            });
            ResetTableCostNumber();

            IsInputNumber();

            $('.status').change(function(event) {
                checkCost();
            });
            $('#amount').keyup(function(event) {
                checkCost();
            });
            $('#amount').change(function(event) {
                checkCost();
            });
            $('#amount').blur(function(event) {
                checkCost();
            });
    });

    function checkCost(){
           var rows         = $('#table_body_cost').children(); //แถวทั้งหมด
           var status_all   = rows.find('select.status').length; // สถานะการประชุมทั้งหมด
           var status       =  rows.find('select.status option[value!=""]:selected').length; // สถานะการประชุม
           var amount = $('#amount').val();
            if(checkNone(amount) && status_all == status){
                var number1       =  rows.find('select.status option[value="1"]:selected').length; // จำนวนผ่าน
                var number2       =  rows.find('select.status option[value="2"]:selected').length; // จำนวนมีข้อคิดเห็น (สืบเนื่อง)
                var sum           = (parseFloat(number1) + parseFloat(number2));
                var cost          = RemoveCommas(amount) ;
                var integer      = (parseFloat(cost) / parseFloat(sum));
                rows.find('select.status').each(function(index, el) {
                    if($(el).val() == 1 || $(el).val() == 2){
                        $(el).parent().parent().find('.meeting_type_cost').val(addCommas(integer.toFixed(2), 2));
                    }else{
                        $(el).parent().parent().find('.meeting_type_cost').val('');
                    }
 
                });
        
            }else{
                rows.find('.meeting_type_cost').val('');
            }
     }


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
            (rows.length==1)?$('.remove-participants').hide():$('.remove-participants').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
     }    
     function ResetTableCostNumber(){
            var rows = $('#table_body_cost').children(); //แถวทั้งหมด
            (rows.find('.remove-cost').length==1)?$('.remove-cost').hide():$('.remove-cost').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1); 
                $(el).find('.cost_id').val(index);
                $(el).find('.setstandard_id').prop('name','meeting[setstandard_id]['+index+']');
                $(el).find('.setstandard_title').prop('name','meeting[setstandard_title]['+index+']');
                $(el).find('.status').prop('name','meeting[status]['+index+']');
                $(el).find('.expense_other').prop('name','meeting[expense_other]['+index+']');
                $(el).find('.cost').prop('name','meeting[cost]['+index+']');
            });
     }      
     
     function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

     function IsInputNumber() {
                   // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                   String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                   };

                   var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน
                    var s_inum=new String(inum);
                    var num2=s_inum.split(".");
                    var n_inum="";
                    if(num2[0]!=undefined){
                   var l_inum=num2[0].length;
                   for(i=0;i<l_inum;i++){
                    if(parseInt(l_inum-i)%3==0){
                   if(i==0){
                    n_inum+=s_inum.charAt(i);
                   }else{
                    n_inum+=","+s_inum.charAt(i);
                   }
                    }else{
                   n_inum+=s_inum.charAt(i);
                    }
                   }
                    }else{
                   n_inum=inum;
                    }
                    if(num2[1]!=undefined){
                   n_inum+="."+num2[1];
                    }
                    return n_inum;
                   }
                   // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                   $(".amount").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   });

                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ
                   $(".amount").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                               if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                           thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }else{ // ถ้าไม่มีคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                   });
         }

        
</script>
@endpush

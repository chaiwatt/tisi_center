
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', 'หัวข้อการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('title',null, ['class' => 'form-control ', 'disabled' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('meeting_type_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_type_id', 'วาระการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('meeting_type_id',App\Models\Bcertify\Meetingtype::where('state',1)->pluck('title', 'id'), !empty($meetingstandard_record->meeting_type_id)?$meetingstandard_record->meeting_type_id:null, ['class' => 'form-control', 'placeholder' => '-เลือกวาระการประชุม-', 'disabled' => true]); !!}
        {!! $errors->first('meeting_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    {!! Html::decode(Form::label('start_date', 'วันที่ดำเนินการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            <span class="input-group-addon bg-info b-0 text-white"> เริ่ม </span>
            {!! Form::text('start_date', !empty($meetingstandard_record->start_date) ? HP::revertDate($meetingstandard_record->start_date, true) : '', ['class' => 'form-control','id'=>'start_date', 'required' => true]); !!}
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
        </div>
      </div>

    <div class="col-md-2">
        {!! Form::time('start_time', !empty($meetingstandard_record->start_time) ? $meetingstandard_record->start_time : '', ['class' => 'form-control text-center','id'=>'start_time', 'required' => true]); !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label label-filter']) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('end_date', !empty($meetingstandard_record->end_date) ? HP::revertDate($meetingstandard_record->end_date,true) : '', ['class' => 'form-control','id'=>'end_date', 'required' => true]); !!}
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2">
       {!! Form::time('end_time', !empty($meetingstandard_record->end_time) ? $meetingstandard_record->end_time : '', ['class' => 'form-control text-center','id'=>'end_time', 'required' => true]); !!}
    </div>
</div>



<div class="form-group {{ $errors->has('meeting_detail') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('meeting_detail', 'รายละเอียดการประชุม'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::textarea('meeting_detail', !empty($meetingstandard_record->meeting_detail)?$meetingstandard_record->meeting_detail:'', ['class' => 'form-control', 'rows'=>'2', 'required' => true]); !!}
    </div>
</div>


<div class="form-group">
    {!! Html::decode(Form::label('attach', '<span class="select-label">เอกสารการประชุม :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-8">
        <button type="button" class="btn btn-sm btn-success attach-add"  id="attach-add">
            <i class="icon-plus"></i>&nbsp;เพิ่ม
        </button>
    </div>
</div>


    @php
        $file_meetingstandard_record = [];

        if( !empty($meetingstandard_record) ){
            $file_meetingstandard_record = App\AttachFile::where('ref_table', (new App\Models\Certify\MeetingStandardRecord )->getTable() )
                                            ->where('ref_id', $meetingstandard_record->id )
                                            ->where('section', 'file_meeting_standard_record')
                                            ->get();

        }
    @endphp

<div id="attach-box">

    @if( count($file_meetingstandard_record) > 0 )
        @foreach ( $file_meetingstandard_record as $other )

            <div class="form-group">
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('file_meet', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                </div>
                <div class="col-md-3">
                    <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                        {!! HP::FileExtension($other->filename)  ?? '' !!}
                    </a>
                    <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('certify/delete-files/'.($other->id).'/'.base64_encode('certify/meeting-standards/'.$meetingstandard->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>

        @endforeach
    @endif

    <div class="form-group other_attach_item">
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
                        <input type="file" name="file[]" class="check_max_size_file">
                    </span>
                   <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
           </div>
       </div>
       <div class="col-md-1">
            <div class="button_remove"></div>
       </div>
   </div>
</div>


<div class="form-group {{ $errors->has('experts_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('experts_id', '<span class="select-label">ผู้เข้าร่วม :</span>'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label '])) !!}
    <div class="col-md-6">
        @php
            $meetingstandard_commitees = isset($meetingstandard_commitees) ? $meetingstandard_commitees : [] ;
            $query_committee_lists = App\CommitteeLists::whereIn('committee_special_id', $meetingstandard_commitees)->select('expert_id');
            $experts = App\Models\Certify\RegisterExpert::whereIn('id', $query_committee_lists)->pluck('head_name', 'id');
        @endphp
        {!! Form::select('experts_id[]', $experts, !empty($meetingstandard_record_experts)?$meetingstandard_record_experts:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'data-placeholder' => '-เลือกผู้เข้าร่วม-', 'required' => true]); !!}
        {!! $errors->first('experts_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


    <table class="table color-bordered-table primary-bordered-table">
        <thead>
            <tr>
                <th class="text-center" width="2%">#</th>
                <th class="text-center" width="38%">ค่าใช้จ่าย</th>
                <th class="text-center" width="20%">จำนวน(บาท)</th>
                <th class="text-center" width="5%">
                    <button type="button" class="btn btn-sm btn-success "  id="plus-row">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="table-body">
            @if(!empty($meetingstandard_record_costs) && count($meetingstandard_record_costs) > 0)
                @foreach ($meetingstandard_record_costs as $key => $meetingstandard_record_cost)
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        {!! Form::text('expense_other[]',!empty($meetingstandard_record_cost->expense_other )?$meetingstandard_record_cost->expense_other:null , ['class' => 'form-control ', 'required' => true]) !!}
                        {!! Form::hidden('setstandard_id[]',!empty($meetingstandard_record_cost->setstandard_id )?$meetingstandard_record_cost->setstandard_id:null , ['class' => 'form-control ']) !!}
                    </td>
                    <td>{!! Form::text('cost[]',!empty($meetingstandard_record_cost->cost )? number_format($meetingstandard_record_cost->cost,2):null , ['class' => 'form-control amount text-right ', 'required' => true]) !!}</td>
                    <td class="text-center">
                        @if (empty($meetingstandard_record_cost->setstandard_id))
                            <button type="button" class="btn btn-danger btn-sm remove-row row_amount" ><i class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else

                @if(!empty($setstandards) && count($setstandards) > 0)
                    @foreach ($setstandards as $key => $setstandard)
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                             {!! Form::text('expense_other[]',!empty($setstandard->projectid )?'เบี้ยประชุม '.'('.$setstandard->projectid.')':null , ['class' => 'form-control ', 'required' => true]) !!}
                             {!! Form::hidden('setstandard_id[]',!empty($setstandard->id )?$setstandard->id:null , ['class' => 'form-control ']) !!}
                        </td>
                        {{-- <td>{!! Form::text('cost[]',!empty($setstandard->estimate_cost )? number_format($setstandard->estimate_cost,2):null , ['class' => 'form-control amount text-right', 'required' => true]) !!}</td> --}}
                        <td>{!! Form::text('cost[]', null, ['class' => 'form-control amount text-right', 'required' => true]) !!}</td>
                        <td></td>
                    </tr>
                    @endforeach
                @endif
                    <tr>
                        <td class="text-center">1</td>
                        <td>  {!! Form::text('expense_other[]', null, ['class' => 'form-control', 'placeholder' => 'ค่าใช้จ่ายอื่นๆ']) !!}  </td>
                        <td>  {!! Form::text('cost[]', null, ['class' => 'form-control amount row_amount text-right']) !!} </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row" ><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
            @endif
        </tbody>
        <footer>
                <tr>
                    <td colspan="2" class="text-right">รวม</td>
                    <td>
                        {!! Form::text('amount_import[]', null, ['class' => 'form-control total_amount text-right','id'=>'total_amount','disabled' => true]) !!}
                    </td>
                    <td>
                         บาท
                    </td>
                </tr>
        </footer>
    </table>

    <input type="hidden" name="type" value="report" />

    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">

            <button class="btn btn-primary" type="submit" id="button-form-report">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            <a class="btn btn-default" href="{{url('/certify/meeting-standards')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>

        </div>
    </div>

@push('js')
<script>

    $(document).ready(function () {

        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
            $('.other_attach_item:first').clone().appendTo('#attach-box');

            var row_new = $('.other_attach_item:last');
              $(row_new).find('input').val('');
              $(row_new).find('a.fileinput-exists').click();
              $(row_new).find('a.view-attach').remove();
              $(row_new).find('button.attach-add').remove();
              $(row_new).find('.button_remove').html('<button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i> ลบ </button>');
              check_max_size_file();
        });

        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
            $(this).parent().parent().parent().remove();
        });
        check_max_size_file();


        $('#plus-row').click(function(event) {

          $('#table-body').children('tr:last()').clone().appendTo('#table-body');
            var row = $('#table-body').children('tr:last()');
                row.find('input[type="text"]').val('');

          ResetTableNumber();
        });


        $('body').on("keyup change blur keypress", ".amount",function (event) {

            if(event.which >= 37 && event.which <= 40){
                event.preventDefault();
            }

            $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
                AmountTotal();
        });
        AmountTotal();



        $('body').on('click', '.remove-row', function(){

          $(this).parent().parent().remove();
          ResetTableNumber();
          AmountTotal();
        });
        ResetTableNumber();


    });


    function ResetTableNumber(){
        var rows = $('#table-body').children();
            rows.each(function(index, el) {
                $(el).children().first().html(index+1);
            });

            ($('.row_amount').length==1)?$('.remove-row').hide():$('.remove-row').show();
    }

    function AmountTotal(){

        var total = 0.00;

            $('.amount').each(function(index, el) {
                if( $(el).val() != '' ){
                    total += parseFloat(RemoveCommas($(el).val()));
                }
            });
            $('.total_amount').val(addCommas(total.toFixed(2), 2));

    }


</script>
@endpush

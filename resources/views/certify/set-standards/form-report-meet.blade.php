 
     @php 
        $sum_cost = 0;
        $quantity_status = 0;
        $sum_cost_status = 0;
    @endphp
@if (count($setstandard->certify_setstandard_meeting_type_many) > 0)

@foreach ($setstandard->certify_setstandard_meeting_type_many as $key => $meetingstandard)
@php
     $record =   !empty($meetingstandard->meeting_standard_to->record) ? $meetingstandard->meeting_standard_to->record : null ;
     if(!is_null($record) && !empty($meetingstandard->setstandard_to->projectid)  &&  !empty($meetingstandard->meetingtype_to->title)){
       $setstandard_title =  $meetingstandard->setstandard_to->projectid.' ('.$meetingstandard->meetingtype_to->title.')';
      // $cost =    App\Models\Certify\MeetingStandardRecordCost::where('meeting_record_id',$record->id)->where('expense_other',$setstandard_title)->where('setstandard_id', $meetingstandard->setstandard_id )->value('cost');
      // if(!is_null($cost)){
      //      $sum_cost += $cost;
      // }

       $record_cost =    App\Models\Certify\MeetingStandardRecordCost::where('meeting_record_id',$record->id)->where('expense_other',$setstandard_title)->whereIn('status',[1, 2])->value('cost');
       if(!is_null($record_cost)){
            $sum_cost_status += $record_cost;
            $quantity_status += 1;
       }
    }
@endphp

@endforeach
@endif
 
{!! Form::hidden('amount_sum', (!empty($quantity_status)?$quantity_status:0) , ['class' => 'form-control text-right amount', 'readonly'=>true]) !!}
{!! Form::hidden('cost_sum', (!empty($sum_cost_status)?$sum_cost_status:0) , ['class' => 'form-control text-right amount','id'=>'amount_bill_all','readonly'=>true]) !!}
<div class="form-group">
    <div class="  {{ $errors->has('amount_sum') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('amount_sum', 'สรุปผลการประชุม :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-9">
            <div class="table-responsive">
                <table class="table color-bordered-table info-bordered-table">
                    <thead>
                    <tr>
                        <th class="text-center"  width="50%">รายละเอียด</th>
                        <th class="text-center"  width="25%">แผน</th>
                        <th class="text-center"  width="25%">ผล</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p>
                                    จำนวนครั้งการประชุมทั้งหมด (ครั้ง)
                                </p>
                            </td>
                            <td  class="text-center">
                                    {{ $setstandard->plan_time }}
                            </td>
                            <td  class="text-center">
                                    {{ $quantity_status  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    ค่าใช้จ่ายในการประชุมทั้งหมด (บาท)
                                </p>
                            </td>
                            <td  class="text-center">
                                {!!  (!empty($standardplan->budget) ? number_format($standardplan->budget,2) : '0.00')  !!}
                            </td>
                            <td  class="text-center">
                                {!!  (!empty($sum_cost_status) ? number_format($sum_cost_status,2) : '0.00')  !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



 {{-- <div class="form-group">
    <div class="  {{ $errors->has('amount_sum') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('amount_sum', 'จำนวนครั้งในการประชุมทั้งหมด :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
            <div class="input-group" >
                {!! Form::text('amount_sum', (!empty($setstandard_summeeting) ? $setstandard_summeeting->amount_sum : count($setstandard->certify_setstandard_meeting_type_many)) , ['class' => 'form-control text-right amount', 'readonly'=>true]) !!}
                <span class="input-group-addon bg-secondary  b-0 text-dark"> ครั้ง </span>
            </div>
        </div>
    </div>
</div>

<div class=" form-group">
    <div class="  {{ $errors->has('cost_sum') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('cost_sum', 'ค่าใช้จ่ายในการประชุมทั้งหมด :', ['class' => 'col-md-3 control-label '])) !!}
        <div class="col-md-4">
                <div class="input-group" > 
                {!! Form::text('cost_sum',(!empty($setstandard_summeeting) ? number_format($setstandard_summeeting->cost_sum,2) :  (!empty($sum_cost) ? number_format($sum_cost,2) : null)) , ['class' => 'form-control text-right amount','id'=>'amount_bill_all','readonly'=>true]) !!}
                <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
            </div>
        </div>
    </div>
</div> --}}

<div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('detail', 'รายละเอียด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6">
            {!! Form::textarea('detail', (!empty($setstandard_summeeting) ? $setstandard_summeeting->detail : ''), ['class' => 'form-control assessment_desc', 'rows'=>'2', 'required' => true]); !!}
    </div>
</div>
 

<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9  repeater-form-file4" >
        <div class="row" data-repeater-list="repeater-attach_step4">
            @php
                $attach_step4 = $setstandard->AttachFileSetStandardsAttachTo;
            @endphp
            @if (!empty($attach_step4) && count($attach_step4) > 0)
            @foreach ($attach_step4 as $step4)
                    <p>
                        {!! !empty($step4->caption) ? $step4->caption : '' !!}
                        <a href="{!! HP::getFileStorage($step4->url) !!}" target="_blank">
                             {!! HP::FileExtension($step4->filename)  ?? '' !!}
                        </a>
                    </p>
            @endforeach
            
            @endif
            <div class="form-group repeater_form_file4" data-repeater-item>
                <div class="col-md-4">
                    {!! Form::text('file_attach_step4_documents', null,['class' => 'form-control']) !!}
                </div>
                <div class="col-md-5">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                            <span class="input-group-text btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="attach_step4">
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-danger btn-sm btn_file_remove4" data-repeater-delete type="button">
                        ลบ
                    </button>
                    <button type="button" class="btn btn-success btn-sm btn_file_add4" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                </div>
            </div>
       </div>
    </div>
</div>  
 
@if(!is_null($history->details_one))
@php 
$details_one = json_decode($history->details_one);
@endphp 
<div class="row">
<div class="col-md-3 text-right">
<p class="text-nowrap">ชื่อคณะผู้ตรวจประเมิน :</p>
</div>
<div class="col-md-9 text-left">
 
     <p> 
        {!! $details_one->no ?? null !!}
    </p>
 
</div>
</div>
@endif
<div class="row">
    <div class="col-md-3 text-right">
    <p class="text-nowrap">วันที่ตรวจประเมิน :</p>
    </div>
    <div class="col-md-9 text-left">
     
         <p> 
            <span>{!! $history->DataBoardAuditorDateTitle ?? '-'!!}</span>
        </p>
     
    </div>
 </div>


 @if(!is_null($history->file))
 @php 
$file = json_decode($history->file);
@endphp 
 
@if(!is_null($file))
 <div class="row">
   <div class="col-md-5 text-right">
       <p class="text-nowrap">บันทึก ลมอ.  แต่งตั้งคณะผู้ตรวจประเมิน</p>
   </div>
   <div class="col-md-7 text-left">
             <a href="{{url('funtions/get-view/'.$file->url.'/'.( !empty($file->filename) ? $file->filename : basename($file->new_filename) ))}}" target="_blank">
                 {!! HP::FileExtension($file->filename)  ?? '' !!}
             </a>  
   </div>
 </div>
 @endif
 @endif
 
 @if(!is_null($history->attachs))
 @php 
$attachs = json_decode($history->attachs);
@endphp 
 
@if(!is_null($attachs))
 <div class="row">
   <div class="col-md-5 text-right">
       <p class="text-nowrap">กำหนดการตรวจประเมิน</p>
   </div>
   <div class="col-md-7 text-left">
             <a href="{{url('funtions/get-view/'.$attachs->url.'/'.( !empty($attachs->filename) ? $attachs->filename : basename($attachs->new_filename) ))}}" target="_blank">
                 {!! HP::FileExtension($attachs->filename)  ?? '' !!}
             </a>  
   </div>
 </div>
 @endif
 @endif
 

@if(!is_null($history->details_three))
@php
    $details_three = json_decode($history->details_three);
 @endphp
 @if(!is_null($details_three))
 <div class="row">
<label class="col-md-12 text-left">โดยคณะผู้ตรวจประเมิน มีรายนามดังต่อไปนี้</label>
<div class="col-md-12">
<table class="table table-bordered">
   <thead class="bg-primary">
   <tr>
       <th class="text-center bg-info  text-white" width="2%">ลำดับ</th>
       <th class="text-center bg-info  text-white" width="30%">สถานะผู้ตรวจประเมิน</th>
       <th class="text-center bg-info  text-white" width="40%">ชื่อผู้ตรวจประเมิน</th>
       <th class="text-center bg-info  text-white" width="26%">หน่วยงาน</th>
   </tr>
   </thead>
   <tbody>
 
    @foreach($details_three as $key3 => $three)
   <tr>
       <td  class="text-center">{{ $key3 +1 }}</td>
       <td> 
             @if (!empty($three->status_id)) 
                @php
                    $auditor_title = App\Models\Bcertify\StatusAuditor::where('id',$three->status_id)->value('title');
                @endphp
                {{ !empty($auditor_title) ? $auditor_title : '-'  }}
            @endif
     </td>
       <td>
            {{ $three->temp_users ?? '-'  }}
       </td>
       <td>
             {{ $three->temp_departments ?? '-'  }}
       </td>
   </tr>
   @endforeach
   </tbody>
</table>
</div>
</div>
@endif
@endif
              
@if(!is_null($history->details_four))
@php
 $details_four = json_decode($history->details_four);
@endphp
<div class="row">
<label class="col-md-12 text-left">ค่าใช้จ่าย</label>
<div class="col-md-12">
<table class="table table-bordered">
 <thead class="bg-primary">
 <tr>
     <th class="text-center bg-info  text-white" width="2%">ลำดับ</th>
     <th class="text-center bg-info  text-white" width="38%">รายละเอียด</th>
     <th class="text-center bg-info  text-white" width="20%">จำนวนเงิน (บาท)</th>
     <th class="text-center bg-info  text-white" width="20%">จำนวนวัน (วัน)</th>
     <th class="text-center bg-info  text-white" width="20%">รวม (บาท)</th>
 </tr>
 </thead>
 <tbody>
        @php    
       $SumAmount = 0;
       @endphp
     @foreach($details_four as $key4 => $four)
         @php     
         $amount_date = !empty($four->amount_date) ? $four->amount_date : 0 ;
         $amount = !empty($four->amount) ? $four->amount : 0 ;
         $sum =   $amount*$amount_date;
         $SumAmount  +=  $sum;
         $details =  App\Models\Bcertify\StatusAuditor::where('id',$four->status_id)->first();
         @endphp
         <tr>
             <td class="text-center">{{ $key4+1 }}</td>
             <td>{{ !is_null($details) ? $details->title : null  }}</td>
             <td class="text-right">{{ number_format($amount, 2) }}</td>
             <td class="text-right">{{ $amount_date }}</td>
             <td class="text-right">{{ number_format($sum, 2) ?? '-'}}</td>
         </tr>
      @endforeach 
 </tbody>
 <footer>
     <tr>
         <td colspan="4" class="text-right">รวม</td>
         <td class="text-right">
              {{ !empty($SumAmount) ?  number_format($SumAmount, 2) : '-' }} 
         </td>
     </tr>
 </footer>
</table>
</div>
</div>
@endif

<hr>

@if(!is_null($history->status))
<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">กำหนดการตรวจประเมิน</p>
</div>
<div class="col-md-7 text-left">
<label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($history->status == 1 ) ? 'checked' : ' '  }}>  &nbsp;เห็นชอบดำเนินการแต่งตั้งคณะผู้ตรวจประเมินต่อไป &nbsp;</label>
<br>
<label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($history->status == 2 ) ? 'checked' : ' '  }}>  &nbsp;ไม่เห็นชอบ เพราะ  &nbsp;</label>
</div>
</div>
@endif

@if(isset($details_one->remark) &&  !is_null($details_one->remark))
<div class="row">
<div class="col-md-4 text-right">
 <p class="text-nowrap">หมายเหตุ</p>
</div>
<div class="col-md-7 text-left">
  {{ @$details_one->remark  ?? '-'}}
</div>
</div>
@endif



@if(!is_null($history->attachs_file))
@php 
 $attachs_file = json_decode($history->attachs_file);
@endphp 
@if(!is_null($attachs_file))
<div class="row">
<div class="col-md-12">
 {!! Form::label('no', 'หลักฐาน :', ['class' => 'col-md-4 control-label text-right']) !!}
<div class="col-md-8 text-left">
 @foreach($attachs_file as $files)
         <p> 
             {{  @$files->caption  }}
             <a href="{{url('funtions/get-view/'.$files->url.'/'.( !empty($files->filename) ? $files->filename : basename($files->new_filename) ))}}" target="_blank">
                {!! HP::FileExtension($files->filename)  ?? '' !!}
            </a>  
         </p>
     @endforeach
</div>
</div>
</div>
@endif
@endif

@if(!is_null($history->date))
 <div class="row">
 <div class="col-md-4 text-right">
     <p class="text-nowrap">วันที่ผู้ประกอบการบันทึก</p>
 </div>
 <div class="col-md-7 text-left">
     {{ HP::DateThai($history->date)  ?? '-'}}
 </div>
 </div>
@endif

 

@if(!is_null($history->details_auditors_cancel))
    <span class="text-danger">ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน</span>
    <hr>
    @php
    $auditors_cancel = json_decode($history->details_auditors_cancel);
    @endphp
    @if(!is_null(HP::UserTitle($auditors_cancel->created_cancel)) )
        <div class="row">
        <div class="col-md-4 text-right">
            <p class="text-nowrap">ผู้ยกเลิก</p>
        </div>
        <div class="col-md-7 text-left">
                {{HP::UserTitle($auditors_cancel->created_cancel)->FullName}}
        </div>
        </div>
    @endif
    @if(isset($auditors_cancel->date_cancel))
        <div class="row">
        <div class="col-md-4 text-right">
            <p class="text-nowrap">วันที่ยกเลิก</p>
        </div>
        <div class="col-md-7 text-left">
            {{ HP::DateThai($auditors_cancel->date_cancel)  ?? '-'}}
        </div>
        </div>
    @endif
    @if(isset($auditors_cancel->reason_cancel))
        <div class="row">
        <div class="col-md-4 text-right">
            <p class="text-nowrap">เหตุผลที่ยกเลิก</p>
        </div>
        <div class="col-md-7 text-left">
            {{ $auditors_cancel->reason_cancel   ?? '-'}}
        </div>
        </div>
    @endif
@endif
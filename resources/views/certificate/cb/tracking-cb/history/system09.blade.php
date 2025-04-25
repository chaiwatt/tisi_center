 
@if(!is_null($history->details_one))
@php 
$details_one = json_decode($history->details_one);
@endphp 
@if(!is_null($details_one))


<div class="row">
          <div class="col-md-4 text-right">
              <p class="text-nowrap">วันที่ประชุม :</p>
          </div>
          <div class="col-md-8 text-left">
             {!! !empty($details_one->report_date) ? HP::DateThai($details_one->report_date) : null  !!}
          </div>
 </div>

 @if(!empty($details_one->report_status))
 <div class="row">
           <div class="col-md-4 text-right">
                     <p class="text-nowrap">มติคณะกรรมการ :</p>
           </div>
           <div class="col-md-8 text-left">
                     <label>{!! Form::radio('', '1', $details_one->report_status == 1 ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;ยืนยัน Scope &nbsp;</label>
                     <label>{!! Form::radio('', '2', $details_one->report_status == 2 ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp;ขอแก้ไข Scope &nbsp;</label>
           </div>
 </div>
 @endif

 @if(!empty($details_one->details))
 <div class="row">
           <div class="col-md-4 text-right">
                     <p class="text-nowrap">รายละเอียด :</p>
           </div>
           <div class="col-md-8 text-left">
                    {!!  $details_one->details  !!}
           </div>
 </div>
 @endif

 @if(!is_null($history->attachs))
 @php 
 $attachs = json_decode($history->attachs);
 @endphp 
 @if(!is_null($attachs))
 
 <div class="row">
 <div class="col-md-4 text-right">
 <p class="text-nowrap">ขอบข่ายที่ได้รับการเห็นชอบ :</p>
 </div>
 <div class="col-md-8 text-left">
     <p> 
         <a href="{{url('funtions/get-view/'.$attachs->url.'/'.( !empty($attachs->filename) ? $attachs->filename : basename($attachs->new_filename) ))}}" target="_blank">
             {!! HP::FileExtension($attachs->filename)  ?? '' !!}
         </a>  
     </p>
 </div>
 </div>
 
 @endif
 @endif

 <div class="row">
          <div class="col-md-4 text-right">
                    <p class="text-nowrap">วันที่เริ่ม-สิ้นสุดขอบข่าย :</p>
          </div>
          <div class="col-md-8 text-left">
                    {{  !empty($details_one->start_date) && !empty($details_one->end_date) ? HP::DateFormatGroupTh($details_one->start_date,$details_one->end_date) :  '-' }}
          </div>
</div>

@if(!is_null($history->attachs_file))
@php 
$attachs_file = json_decode($history->attachs_file);
@endphp 
@if(!empty($attachs_file) && count($attachs_file) > 0)
<div class="row">
          <div class="col-md-4 text-right">
                    <p class="text-nowrap">ไฟล์แนบ :</p>
          </div>
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
@endif
@endif

@if(!is_null($history->created_at)) 
<div class="row">
          <div class="col-md-4 text-right">
                    <p class="text-nowrap">วันที่บันทึก :</p>
          </div>
          <div class="col-md-8 text-left">
                    {{ @HP::DateThai($history->created_at) ?? '-' }}
          </div>
</div>
@endif



@endif
@endif


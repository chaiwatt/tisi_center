@if(!is_null($history->attachs))
@php 
$attachs = json_decode($history->attachs);
@endphp 
@if(!is_null($attachs))

<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">หลักฐานขอบข่าย .pdf :</p>
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

@if(!is_null($history->attachs_file))
@php 
$attachs_file = json_decode($history->attachs_file);
@endphp 
@if(!empty($attachs_file))
<div class="row">
          <div class="col-md-4 text-right">
                  <p class="text-nowrap">หลักฐานขอบข่าย .docx,doc :</p>
          </div>
          <div class="col-md-8 text-left">
                    <p> 
                           <a href="{{url('funtions/get-view/'.$attachs_file->url.'/'.( !empty($attachs_file->filename) ? $attachs_file->filename : basename($attachs_file->new_filename) ))}}" target="_blank">
                                {!! HP::FileExtension($attachs_file->filename)  ?? '' !!}
                           </a>  
                    </p>
          </div>
</div>
@endif
@endif

<div class="row">
<div class="col-md-4 text-right">
                <p class="text-nowrap"> ออกให้ตั้งแต่วันที่ :</p>
</div>
<div class="col-md-8 text-left">
                <p> 
                      {!!  !empty($history->details_one)  &&  !empty($history->details_two) ? HP::formatDateThaiFull($history->details_one).' ถึง '.HP::formatDateThaiFull($history->details_two):''   !!}          
                </p>
</div>
</div>
 

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

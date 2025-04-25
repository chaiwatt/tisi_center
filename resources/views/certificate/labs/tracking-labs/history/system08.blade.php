@if(!is_null($history->attachs))
@php 
$attachs = json_decode($history->attachs);
@endphp 
@if(!is_null($attachs))

<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">Scope :</p>
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


@if(!is_null($history->file))
@php 
$file = json_decode($history->file);
@endphp 
@if(!is_null($file))

<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">สรุปรายงานการตรวจทุกครั้ง :</p>
</div>
<div class="col-md-8 text-left">
    <p> 
        <a href="{{url('funtions/get-view/'.$file->url.'/'.( !empty($file->filename) ? $file->filename : basename($file->new_filename) ))}}" target="_blank">
            {!! HP::FileExtension($file->filename)  ?? '' !!}
        </a>  
    </p>
</div>
</div>

@endif
@endif

<hr>

@if(!is_null($history->status))
<div class="row">
          <div class="col-md-4 text-right">
                    <p class="text-nowrap">สรุปรายงานการตรวจทุกครั้ง :</p>
          </div>
          <div class="col-md-8 text-left">
                    <label>{!! Form::radio('', '1', $history->status == 1 ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;ยืนยัน Scope &nbsp;</label>
                    <label>{!! Form::radio('', '2', $history->status == 2 ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp;ขอแก้ไข Scope &nbsp;</label>
          </div>
</div>
@endif

@if(!is_null($history->details_two))
<div class="row">
          <div class="col-md-4 text-right">
                    <p class="text-nowrap">หมายเหตุ :</p>
          </div>
          <div class="col-md-8 text-left">
                    {!!  !empty($history->details_two)  ? $history->details_two : '' !!}
          </div>
</div>
@endif

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




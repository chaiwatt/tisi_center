 
<div class="row">
    <div class="col-md-12">
        <div class="panel block4">
            <div class="panel-group" id="accordion05">
                <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion05" href="#information"> <dd style="color:Black"> <i class='fa fa-star' style="font-size:20px"></i> ข้อมูลความเชี่ยวชาญ </dd>  </a>
                    </h4>
                </div>

<div id="information" class="panel-collapse information in">

<div class="form-group  required{{ $errors->has('historycv_text') ? 'has-error' : ''}}">
        {!! Form::label('historycv_text', 'ระบุความเชี่ยวชาญ:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        @php
            $historycv_text = [];
            if(!is_null($registerexperts) && !empty($registerexperts->historycv_text)){
                  $historycv_text = json_decode($registerexperts->historycv_text,true);
                  if(is_null($historycv_text)){
                    $historycv_text = [];
                  }
            }
        @endphp
          @if (!empty($historycv_text) && is_array($historycv_text)   && count($historycv_text) > 0)
          @foreach ($historycv_text as $key => $item)
              {!! Form::text('', ($key+1).'. '.$item  ,  ['class' => 'form-control autofill', 'disabled' => true]) !!}
            @endforeach
         @else
            {!! Form::text('',  $registerexperts->historycv_text ?? null ,  ['class' => 'form-control autofill', 'disabled' => true]) !!}
         @endif
    </div>
</div>

<div class="form-group  required{{ $errors->has('') ? 'has-error' : ''}}">
         {!! Form::label('', 'ไฟล์ประวัติความเชี่ยวชาญ (CV):', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-8">
        @if (isset($registerexperts) && !empty($registerexperts->AttachFileHistorycvFileTo))
            @php
            $attach = $registerexperts->AttachFileHistorycvFileTo;
            @endphp
            <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                 {!! HP::FileExtension($attach->filename)  ?? '' !!}
            </a>
        @endif
    </div>
</div>
<br>



    
</div>
              </div>
         </div>
     </div> 
 </div> 
</div>

 
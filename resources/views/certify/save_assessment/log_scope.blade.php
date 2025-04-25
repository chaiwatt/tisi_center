@if(count($find_notice->LogNotice) > 0 )

<div class="row form-group">
    <div class="col-md-12">
     <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>ประวัติบันทึกผลการตรวจประเมิน</h3></legend>   

<div class="row">
    <div class="col-md-12">
        <div class="panel block4">
            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                     <div class="panel-heading">
                            <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#inspection"> <dd>ประวัติบันทึกผลการตรวจประเมิน</dd>  </a>
                            </h4>
                     </div>
        
                    <div id="inspection" class="panel-collapse collapse ">
                        <br>
@foreach($find_notice->LogNotice as $key => $item)

<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h3> ครั้งที่ {{ $key +1}} </h3></legend>
<div class="container-fluid">

<div class="form-group">
        <div class="col-md-6">
            <label class="col-md-6 text-right"> รายงานการตรวจประเมิน : </label>
            <div class="col-md-6">
                @if(!is_null($item->file))
                   <p>
                    <a href="{{url('certify/check/file_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                        title="{{  !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file)}}"  target="_blank">
                         {!! HP::FileExtension($item->file)  ?? '' !!}
                         {{-- {{basename($item->file)}} --}}
                     </a>
                 </p>
                @endif
            </div>
        </div>
        
        <div class="col-md-6">
            @if(!is_null($item->details_date))
            <label class="col-md-6 text-right"> รายงานปิด Car : </label>
            <div class="col-md-6">
                <p>
                    <a href="{{url('certify/check/file_client/'.$item->details_date.'/'.( !empty($item->attach_client_name) ? $item->attach_client_name : 'null' ))}}" 
                        title="{{  !empty($item->attach_client_name) ? $item->attach_client_name :  basename($item->details_date)}}"  target="_blank">
                        {!! HP::FileExtension($item->details_date)  ?? '' !!}
                       {{-- {{basename($item->details_date)}} --}}
                    </a> 
                </p>
            </div>
            @endif
        </div>
</div>

<div class="form-group">
    @if(!is_null($item->details_table))
        <div class="col-md-6">
            <label class="col-md-6 text-right"> รายงาน Scope : </label>
            <div class="col-md-6">
                     @php
                          $details_table = json_decode($item->details_table);
                    @endphp
                    @if(!is_null($details_table))
                    @foreach ($details_table as $item1)
                        <p>
                            <a href="{{url('certify/check/file_client/'.$item1->attachs.'/'.( !empty($item1->attachs_client_name) ? $item1->attachs_client_name : 'null' ))}}" 
                                title="{{  !empty($item1->attachs_client_name) ? $item->attachs_client_name :  basename($item1->attachs)}}"  target="_blank">
                                {!! HP::FileExtension($item1->attachs)  ?? '' !!}
                                 {{-- {{basename($item2->attachs)}} --}}
                             </a>
                        </p>
                    @endforeach
                    @endif
            </div>
        </div>
     @endif
     @if(!is_null($item->attachs))
     <div class="col-md-6">
         <label class="col-md-6 text-right"> ไฟล์แนบ : </label>
         <div class="col-md-6">
                  @php
                       $attachs = json_decode($item->attachs);
                 @endphp
                 @if(!is_null($attachs))
                 @foreach ($attachs as $item3)
                     <p>
                         <a href="{{url('certify/check/file_client/'.$item3->attachs.'/'.( !empty($item3->attachs_client_name) ? $item3->attachs_client_name : 'null' ))}}" 
                            title="{{  !empty($item3->attachs_client_name) ? $item3->attachs_client_name :  basename($item3->attachs)}}"  target="_blank">
                            {!! HP::FileExtension($item3->attachs)  ?? '' !!}
                            {{-- {{basename($item3->attachs)}} --}}
                        </a>
                     </p>
                 @endforeach
                 @endif
         </div>
     </div>
    @endif
</div>



<hr>
@if(!is_null($item->status_scope))
<div class="form-group">
    <div class="col-md-12">
        <label class="col-md-3 text-right">  เห็นชอบกับ Scope : </label>
        <div class="col-md-7">
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item->status_scope == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน Scope &nbsp;</label>
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item->status_scope == 2 ) ? 'checked' : ' '  }}>  &nbsp; แก้ไข Scope &nbsp;</label>
        </div>
    </div>
</div>
@endif

@if(!is_null($item->remark))
<div class="form-group">
    <div class="col-md-12">
        <label class="col-md-3 text-right"> หมายเหตุ : </label>
        <div class="col-md-7">
                {{ $item->remark ?? null }}
        </div>
    </div>
</div>
@endif

<div class="form-group">
    <div class="col-md-12">
        @if(!is_null($item->evidence))
        <label class="col-md-3 text-right"> ไฟล์แนบ : </label>
        <div class="col-md-7">
                @php
                      $evidence = json_decode($item->evidence);
                @endphp
                @if(!is_null($evidence))
                @foreach ($evidence as $item3)
                    <p>
                         {{ @$item->file_desc_text }}
                         <a href="{{ url('certify/check/files/'.$item3->attachs) }}" title="{{basename($item3->attachs)}}" target="_blank">
                            {!! HP::FileExtension($item3->attachs)  ?? '' !!}
                            {{-- {{basename($item3->attachs)}} --}}
                        </a>
                    </p>
                @endforeach
                @endif
        </div>
         @endif
    </div>
</div>

@if(!is_null($item->date)) 
<div class="row">
<div class="col-md-3 text-right">
    <p class="text-nowrap">วันที่บันทึก</p>
</div>
<div class="col-md-9">
    {{ @HP::DateThai($item->date) ?? '-' }}
</div>
</div>
@endif
 
</div>
        </div>
    </div>
</div>

  @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        </div>
    </div>
</div>

@endif
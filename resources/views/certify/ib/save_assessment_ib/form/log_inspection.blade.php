@if(count($assessment->LogCertiIbHistorys) > 0 )

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
@foreach($assessment->LogCertiIbHistorys as $key => $item1)

<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h3> ครั้งที่ {{ $key +1}} </h3></legend>
<div class="container-fluid">

    <div class="row ">
        <div class="col-md-6">
            <label class="col-md-6 text-right"> รายงานการตรวจประเมิน : </label>
            <div class="col-md-6">
                @if(!is_null($item1->details_three))
                   <p>
                    <a href="{{url('certify/check/file_ib_client/'.$item1->details_three.'/'.( !empty($item1->file_client_name) ? $item1->file_client_name : basename($item1->details_three) ))}}" 
                        title="{{ !empty($item1->file_client_name) ? $item1->file_client_name :  basename($item1->details_three) }}" target="_blank">
                        {!! HP::FileExtension($item1->details_three)  ?? '' !!}
                    </a>
                 </p>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            @if(!is_null($item1->attachs_car))
            <label class="col-md-6 text-right"> รายงานปิด Car : </label>
            <div class="col-md-6">
                        <p>
                            <a href="{{url('certify/check/file_ib_client/'.$item1->attachs_car.'/'.( !empty($item1->attach_client_name) ? $item1->attach_client_name : basename($item1->attachs_car) ))}}" 
                                title="{{ !empty($item1->attach_client_name) ? $item1->attach_client_name :  basename($item1->attachs_car) }}" target="_blank">
                                {!! HP::FileExtension($item1->attachs_car)  ?? '' !!}
                            </a>
                        </p>
            </div>
            @endif
        </div>
</div>

<div class="row">
    @if(!is_null($item1->details_four))
    <div class="col-md-6">
        <label class="col-md-6 text-right">Scope : </label>
        <div class="col-md-6">
                 @php
                      $details_four = json_decode($item1->details_four);
                @endphp
                @if(!is_null($details_four))
                @foreach ($details_four as $item2)
                    {{-- <p> --}}
                        <a href="{{url('certify/check/file_ib_client/'.$item2->file.'/'.( !empty($item2->file_client_name) ? $item2->file_client_name :   basename($item2->file) ))}}" 
                            title="{{ !empty($item2->file_client_name) ? $item2->file_client_name :  basename($item2->file) }}" target="_blank">
                             {!! HP::FileExtension($item2->file)  ?? '' !!}
                        </a>
                    {{-- </p> --}}
                @endforeach
                @endif
        </div>
    </div>
    @endif
    @if(!is_null($item1->attachs))
    <div class="col-md-6">
        <label class="col-md-7 text-right"> สรุปรายงานการตรวจทุกครั้ง : </label>
        <div class="col-md-5">
                 @php
                      $attachs = json_decode($item1->attachs);
                @endphp
                @if(!is_null($attachs))
                @foreach ($attachs as $item3)
                    {{-- <p> --}}
                        <a href="{{url('certify/check/file_ib_client/'.$item3->file.'/'.( !empty($item3->file_client_name) ? $item3->file_client_name :  basename($item3->file) ))}}" 
                            title="{{ !empty($item3->file_client_name) ? $item3->file_client_name :  basename($item3->file) }}" target="_blank">
                            {!! HP::FileExtension($item3->file)  ?? '' !!}
                        </a>
                    {{-- </p> --}}
                @endforeach
                @endif
        </div>
    </div>
    @endif
</div>
<div class="row">
    @if(!is_null($item1->file))
    <div class="col-md-6">
        <label class="col-md-6 text-right"> ไฟล์แนบ : </label>
        <div class="col-md-6">
                 @php
                      $files = json_decode($item1->file);
                @endphp
                @if(!is_null($files))
                @foreach ($files as $item4)
                    {{-- <p> --}}
                        <a href="{{url('certify/check/file_ib_client/'.$item4->file.'/'.( !empty($item4->file_client_name) ? $item4->file_client_name :  basename($item4->file) ))}}" 
                            title="{{ !empty($item4->file_client_name) ? $item4->file_client_name :  basename($item4->file) }}" target="_blank">
                           {!! HP::FileExtension($item4->file)  ?? '' !!}
                       </a>
                    {{-- </p> --}}
                @endforeach
                @endif
        </div>
    </div>
    @endif
</div>
<hr>
@if(!is_null($item1->status))
<div class="form-group">
    <div class="col-md-12">
        <label class="col-md-3 text-right">  เห็นชอบกับ Scope : </label>
        <div class="col-md-7">
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item1->status == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน Scope &nbsp;</label>
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item1->status == 2 ) ? 'checked' : ' '  }}>  &nbsp; แก้ไข Scope &nbsp;</label>
        </div>
    </div>
</div>
@endif

@if(!is_null($item1->remark))
<div class="form-group">
    <div class="col-md-12">
        <label class="col-md-3 text-right"> หมายเหตุ : </label>
        <div class="col-md-7">
                {{ $item1->remark ?? null }}
        </div>
    </div>
</div>
@endif

<div class="form-group">
    <div class="col-md-12">
        @if(!is_null($item1->attachs_file))
        <label class="col-md-3 text-right"> ไฟล์แนบ : </label>
        <div class="col-md-7">
                @php
                      $attachs_file = json_decode($item1->attachs_file);
                @endphp
                @foreach ($attachs_file as $item13)
                    <p>
                        {{ @$item13->file_desc}}
                        <a href="{{url('certify/check/file_ib_client/'.$item13->file.'/'.( !empty($item13->file_client_name) ? $item13->file_client_name :  basename($item13->file) ))}}" 
                            title="{{ !empty($item13->file_client_name) ? $item13->file_client_name :  basename($item13->file) }}" target="_blank">
                            {!! HP::FileExtension($item13->file)  ?? '' !!}
                        </a>
                    </p>
                @endforeach
         
        </div>
         @endif
    </div>
</div>

@if(!is_null($item1->date)) 
<div class="row">
<div class="col-md-3 text-right">
    <p class="text-nowrap">วันที่บันทึก</p>
</div>
<div class="col-md-9">
    {{ @HP::DateThai($item1->date) ?? '-' }}
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
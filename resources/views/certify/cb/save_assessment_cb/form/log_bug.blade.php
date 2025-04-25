
 
@if(count($assessment->CertiCBHistorys) >= 2 )

<div class="row form-group">
    <div class="col-md-12">
     <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>ประวัติบันทึกแก้ไขข้อบกพร่อง/ข้อสังเกต</h3></legend>  
<div class="row">
    <div class="col-md-12">
         <div class="panel block4">
            <div class="panel-group" id="accordion">
               <div class="panel panel-info">
                   <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapse"> <dd>ประวัติบันทึกแก้ไขข้อบกพร่อง/ข้อสังเกต</dd>  </a>
                    </h4>
                  </div>
  {{-- {{ ($assessment->degree < 5) ? 'in' : '' }} --}}
<div id="collapse" class="panel-collapse collapse ">
    <br>
 @foreach($assessment->CertiCBHistorys as $key1 => $item1)

 <div class="row form-group">
     <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
   <legend><h3> ครั้งที่ {{ $key1 +1}} </h3></legend>

   <div class="container-fluid">
    @if(!is_null($item1->details_two))
    @php 
        $details_two = json_decode($item1->details_two);
    @endphp 
    <table class="table color-bordered-table primary-bordered-table table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="2%">ลำดับ</th>
                <th class="text-center" width="15%">รายงานที่</th>
                <th class="text-center" width="15%">ผลการประเมินที่พบ</th>
                <th class="text-center" width="15%">
                    {{  
                         !empty($assessment->CertiCBCostTo->FormulaTo->title) ?   
                                $assessment->CertiCBCostTo->FormulaTo->title :'' 
                     }}
                </th>
                <th class="text-center" width="10%">ประเภท</th>
                <th class="text-center" width="20%">แนวทางการแก้ไข</th>

                @if($key1 > 0) 
                <th class="text-center" width="25%" >หลักฐาน</th>
                @endif
            </tr>
        </thead>
        <tbody>
          @if (!is_null($details_two))
            @foreach($details_two as $key2 => $item2)
            @php
             $type =   ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต'];
            @endphp
            <tr>
                <td class="text-center">{{ $key2+ 1 }}</td>
                <td>
                    {{ $item2->report ?? null }}
                </td>
                <td>
                     {{ $item2->remark ?? null }}
                </td>
                <td>
                    {{ $item2->no ?? null }}
                </td>
                <td>
                    {{  array_key_exists($item2->type,$type) ? $type[$item2->type] : '-' }}  
                </td>
              
                <td>
                    {{ @$item2->details ?? null }}
                    <br>
                    @if($item2->status == 1) 
                      <label for="app_name"> <span> <i class="fa fa-check-square" style="font-size:20px;color:rgb(0, 255, 42)"></i></span> ผ่าน </label> 
                    @elseif(!is_null($item2->comment)) 
                    <label for="app_name"><span>  <i class="fa  fa-close" style="font-size:20px;color:rgb(255, 0, 0)"></i> {{  'ไม่ผ่าน:'.$item2->comment ?? null   }}</span> </label> 
                   @endif
                </td>
                @if($key1 > 0) 
                  <td>
                         @if($item2->status == 1) 
                                     @if($item2->file_status == 1)
                                              <span> <i class="fa fa-check-square" style="font-size:20px;color:rgb(0, 255, 42)"></i> ผ่าน</span>  
                                     @elseif(isset($item2->file_comment))
                                            @if(!is_null($item2->file_comment))
                                              <span> <i class="fa  fa-close" style="font-size:20px;color:rgb(255, 0, 0)"></i> ไม่ผ่าน </span> 
                                              {!!   " : ".$item2->file_comment ?? null  !!}
                                            @endif
                                    @endif
                                <label for="app_name">
                                    <span>
                                         @if($item2->attachs !='' && HP::checkFileStorage($attach_path.$item2->attachs))
                                                <a href="{{url('certify/check/file_cb_client/'.$item2->attachs.'/'.( !empty($item2->attach_client_name) ? $item2->attach_client_name :   basename($item2->attachs) ))}}" 
                                                     title="{{ !empty($item2->attach_client_name) ? $item2->attach_client_name :  basename($item2->attachs) }}" target="_blank">
                                                    {!! HP::FileExtension($item2->attachs)  ?? '' !!}
                                                </a>
                                        @endif
                                    </span> 
                                </label> 
                        @endif
                 </td>
                @endif
              
            </tr>
            @endforeach 
          @endif
        </tbody>
    </table>
    @endif

    @if(!is_null($item1->details_three)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">รายงานการตรวจประเมิน :</p>
    </div>
    <div class="col-md-9">
        <p>
            {{-- @if($item1->details_three !='' && HP::checkFileStorage($attach_path.$item1->details_three)) --}}
              <a href="{{url('certify/check/file_cb_client/'.$item1->details_three.'/'.( !empty($item1->file_client_name) ? $item1->file_client_name :  basename($item1->details_three) ))}}" 
                  title="{{ !empty($item1->file_client_name) ? $item1->file_client_name :  basename($item1->details_three) }}" target="_blank">
                {!! HP::FileExtension($item1->details_three)  ?? '' !!}
              </a> 
            {{-- @endif --}}
        </p>
    </div>
    </div>
    @endif

    @if(!is_null($item1->file)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">ไฟล์แนบ :</p>
    </div>
    <div class="col-md-9">
            @php 
                $files = json_decode($item1->file);
            @endphp  
            @foreach($files as  $key => $item2)
                 {{-- @if($item2->file !='' && HP::checkFileStorage($attach_path.$item2->file)) --}}
                    <a href="{{url('certify/check/file_cb_client/'.$item2->file.'/'.( !empty($item2->file_client_name) ? $item2->file_client_name :  basename($item2->file) ))}}" 
                            title="{{ !empty($item2->file_client_name) ? $item2->file_client_name :  basename($item2->file) }}" target="_blank">
                        {!! HP::FileExtension($item2->file)  ?? '' !!}
                    </a> 
                 {{-- @endif --}}
 
            @endforeach
    </div>
    </div> 
    @endif
    @if(!is_null($item1->attachs_car)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap"> รายงานปิด Car :</p>
    </div>
    <div class="col-md-9">
        <p>
            {{-- @if($item1->attachs_car !='' && HP::checkFileStorage($attach_path.$item1->attachs_car)) --}}
                 <a href="{{url('certify/check/file_cb_client/'.$item1->attachs_car.'/'.( !empty($item1->attach_client_name) ? $item1->attach_client_name : basename($item1->attachs_car) ))}}" 
                    title="{{ !empty($item1->attach_client_name) ? $item1->attach_client_name :  basename($item1->attachs_car) }}" target="_blank">
                    {!! HP::FileExtension($item1->attachs_car)  ?? '' !!}
                </a>
            {{-- @endif --}}
        </p>
    </div>
    </div>
    @endif

    @if(!is_null($item1->created_at)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">วันที่เจ้าหน้าที่บันทึก :</p>
    </div>
    <div class="col-md-9">
        {{ @HP::DateThai($item1->created_at) ?? '-' }}
    </div>
    </div>
    @endif

    @if(!is_null($item1->date)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">วันที่ผู้ประกอบการบันทึก :</p>
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
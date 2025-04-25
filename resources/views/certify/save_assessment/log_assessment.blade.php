@if(count($find_notice->CertificateHistorys) >= 2 )

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

<div id="collapse" class="panel-collapse collapse ">
    <br>
 @foreach($find_notice->CertificateHistorys as $key1 => $item1)

 <div class="row form-group">
     <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
   <legend><h3> ครั้งที่ {{ $key1 +1}} </h3></legend>

   <div class="container-fluid">
    @if(!is_null($item1->details_table))
    @php 
        $details_table = json_decode($item1->details_table);
    @endphp 
     @if(!is_null($details_table))
    <table class="table color-bordered-table primary-bordered-table table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="2%">ลำดับ</th>
                <th class="text-center" width="15%">รายงานที่</th>
                <th class="text-center" width="15%">ผลการประเมินที่พบ</th>
                <th class="text-center" width="15%">มอก. 17025 : ข้อ</th>
                <th class="text-center" width="10%">ประเภท</th>
                @if($key1 > 0) 
                <th class="text-center" width="15%" >สาเหตุ</th>
                @endif
                <th class="text-center" width="20%">แนวทางการแก้ไข</th>

                @if($key1 > 0) 
                <th class="text-center" width="10%" >หลักฐาน</th>
                @endif
            </tr>
        </thead>
        <tbody>
                @foreach($details_table as $key2 => $item2)
                @php
                $type =   ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต'];
                @endphp
                <tr>
                    <td class="text-center">{{ $key2+1 }}</td>
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

                    @if($key1 > 0) 
                    <td>
                        {{ @$item2->cause ?? null }}
                    </td>
                    @endif
                
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
                                        @elseif(isset($item2->comment_file))
                                                @if(!is_null($item2->comment_file))
                                                <span> <i class="fa  fa-close" style="font-size:20px;color:rgb(255, 0, 0)"></i> ไม่ผ่าน </span> 
                                                {!!   " : ".$item2->comment_file ?? null  !!}
                                                @endif
                                        @endif
                                    <label for="app_name">
                                        <span>
                                            @if(!is_null($item2->attachs) && isset($item2->attachs) )
                                                <a href="{{url('certify/check/file_client/'.$item2->attachs.'/'.( !empty($item2->attachs_client_name) ? $item2->attachs_client_name : 'null' ))}}" target="_blank">
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
          </tbody>
       </table>
    @endif
    @endif

    @if(!is_null($item1->file)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">รายงานการตรวจประเมิน :</p>
    </div>
    <div class="col-md-9">
        <p>
            <a href="{{url('certify/check/file_client/'.$item1->file.'/'.( !empty($item1->file_client_name) ? $item1->file_client_name : 'null' ))}}" 
                title=" {{ !empty($item1->file_client_name) ? $item1->file_client_name : basename($item1->file)}}"   target="_blank">
                {!! HP::FileExtension($item1->file)  ?? '' !!}
           </a>
        </p>
    </div>
    </div>
    @endif

    @if(!is_null($item1->attachs)) 
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">ไฟล์แนบ :</p>
    </div>
    <div class="col-md-9">
            @php 
                $attachs = json_decode($item1->attachs);
            @endphp  
            @foreach($attachs as  $key => $item2)
                <p>
                    <a href="{{url('certify/check/file_client/'.$item2->attachs.'/'.( !empty($item2->attachs_client_name) ? $item2->attachs_client_name : 'null' ))}}" 
                        title=" {{ !empty($item2->attachs_client_name) ? $item2->attachs_client_name :  basename($item2->attachs)}}"  target="_blank">
                        {!! HP::FileExtension($item2->attachs)  ?? '' !!}
                    </a>
               </p>
            @endforeach
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

@endif